<?php
session_start();
require_once '../../../include/connect.php';
require_once '../../../include/trainer-guard.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['summary'])) {
                handleGetSummary();
            } else {
                handleGetTransactions();
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error: ' . $e->getMessage()]);
}

function handleGetTransactions() {
    global $conn;
    
    $user_id = $_SESSION['user_id'];
    $course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : null;
    $status_filter = isset($_GET['status']) ? $_GET['status'] : null;
    $search = isset($_GET['search']) ? $_GET['search'] : null;
    $date_from = isset($_GET['date_from']) ? $_GET['date_from'] : null;
    $date_to = isset($_GET['date_to']) ? $_GET['date_to'] : null;
    
    // Build the query to get paid enrollments (transactions)
    $query = "SELECT 
                e.enrollment_id,
                e.student_id,
                e.course_id,
                e.payment_status,
                e.enrolled_at as transaction_date,
                c.course_title,
                c.price as amount,
                c.category,
                u.first_name,
                u.last_name,
                u.email,
                st.institution
              FROM enrollments e
              JOIN courses c ON e.course_id = c.course_id
              JOIN students st ON e.student_id = st.student_id
              JOIN users u ON st.user_id = u.user_id
              WHERE c.teacher_id = ?";
    
    $params = [$user_id];
    $types = "i";
    
    // Add filters
    if ($course_id) {
        $query .= " AND e.course_id = ?";
        $params[] = $course_id;
        $types .= "i";
    }
    
    if ($status_filter && $status_filter !== 'all') {
        $query .= " AND e.payment_status = ?";
        $params[] = ucfirst($status_filter);
        $types .= "s";
    }
    
    if ($search) {
        $query .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ? OR c.course_title LIKE ?)";
        $searchParam = "%$search%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $params[] = $searchParam;
        $params[] = $searchParam;
        $types .= "ssss";
    }
    
    if ($date_from) {
        $query .= " AND DATE(e.enrolled_at) >= ?";
        $params[] = $date_from;
        $types .= "s";
    }
    
    if ($date_to) {
        $query .= " AND DATE(e.enrolled_at) <= ?";
        $params[] = $date_to;
        $types .= "s";
    }
    
    $query .= " ORDER BY e.enrolled_at DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $transactions = [];
    while ($row = $result->fetch_assoc()) {
        $transactions[] = [
            'enrollment_id' => $row['enrollment_id'],
            'student_id' => $row['student_id'],
            'course_id' => $row['course_id'],
            'student_name' => $row['first_name'] . ' ' . $row['last_name'],
            'student_email' => $row['email'],
            'institution' => $row['institution'],
            'course_title' => $row['course_title'],
            'category' => $row['category'],
            'amount' => floatval($row['amount']),
            'payment_status' => $row['payment_status'],
            'transaction_date' => $row['transaction_date'],
            'formatted_amount' => '$' . number_format($row['amount'], 2)
        ];
    }
    
    echo json_encode(['transactions' => $transactions]);
}

function handleGetSummary() {
    global $conn;
    
    $user_id = $_SESSION['user_id'];
    $period = isset($_GET['period']) ? $_GET['period'] : 'all';
    
    // Base query for summary statistics
    $baseQuery = "FROM enrollments e
                  JOIN courses c ON e.course_id = c.course_id
                  WHERE c.teacher_id = ?";
    
    $params = [$user_id];
    $types = "i";
    
    // Add date filter based on period
    $dateFilter = "";
    switch ($period) {
        case 'today':
            $dateFilter = " AND DATE(e.enrolled_at) = CURDATE()";
            break;
        case 'week':
            $dateFilter = " AND e.enrolled_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
            break;
        case 'month':
            $dateFilter = " AND e.enrolled_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            break;
        case 'year':
            $dateFilter = " AND e.enrolled_at >= DATE_SUB(NOW(), INTERVAL 365 DAY)";
            break;
        default:
            $dateFilter = "";
    }
    
    // Total revenue (paid enrollments)
    $stmt = $conn->prepare("SELECT COALESCE(SUM(c.price), 0) as total_revenue " . $baseQuery . " AND e.payment_status = 'Paid'" . $dateFilter);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $totalRevenue = $stmt->get_result()->fetch_assoc()['total_revenue'];
    
    // Pending revenue
    $stmt = $conn->prepare("SELECT COALESCE(SUM(c.price), 0) as pending_revenue " . $baseQuery . " AND e.payment_status = 'Pending'" . $dateFilter);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $pendingRevenue = $stmt->get_result()->fetch_assoc()['pending_revenue'];
    
    // Total enrollments
    $stmt = $conn->prepare("SELECT COUNT(*) as total_enrollments " . $baseQuery . $dateFilter);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $totalEnrollments = $stmt->get_result()->fetch_assoc()['total_enrollments'];
    
    // Paid enrollments
    $stmt = $conn->prepare("SELECT COUNT(*) as paid_enrollments " . $baseQuery . " AND e.payment_status = 'Paid'" . $dateFilter);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $paidEnrollments = $stmt->get_result()->fetch_assoc()['paid_enrollments'];
    
    // Revenue by course (top 5)
    $stmt = $conn->prepare("SELECT 
                              c.course_title,
                              c.course_id,
                              COUNT(*) as enrollments,
                              COALESCE(SUM(CASE WHEN e.payment_status = 'Paid' THEN c.price ELSE 0 END), 0) as revenue
                            " . $baseQuery . " AND e.payment_status = 'Paid'" . $dateFilter . "
                            GROUP BY c.course_id, c.course_title
                            ORDER BY revenue DESC
                            LIMIT 5");
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $topCourses = [];
    while ($row = $result->fetch_assoc()) {
        $topCourses[] = [
            'course_id' => $row['course_id'],
            'course_title' => $row['course_title'],
            'enrollments' => intval($row['enrollments']),
            'revenue' => floatval($row['revenue']),
            'formatted_revenue' => '$' . number_format($row['revenue'], 2)
        ];
    }
    
    // Monthly revenue trend (last 6 months)
    $stmt = $conn->prepare("SELECT 
                              DATE_FORMAT(e.enrolled_at, '%Y-%m') as month,
                              COALESCE(SUM(CASE WHEN e.payment_status = 'Paid' THEN c.price ELSE 0 END), 0) as revenue
                            " . $baseQuery . " AND e.payment_status = 'Paid'
                            AND e.enrolled_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                            GROUP BY DATE_FORMAT(e.enrolled_at, '%Y-%m')
                            ORDER BY month ASC");
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $monthlyTrend = [];
    while ($row = $result->fetch_assoc()) {
        $monthlyTrend[] = [
            'month' => $row['month'],
            'revenue' => floatval($row['revenue'])
        ];
    }
    
    echo json_encode([
        'summary' => [
            'total_revenue' => floatval($totalRevenue),
            'pending_revenue' => floatval($pendingRevenue),
            'total_enrollments' => intval($totalEnrollments),
            'paid_enrollments' => intval($paidEnrollments),
            'conversion_rate' => $totalEnrollments > 0 ? round(($paidEnrollments / $totalEnrollments) * 100, 1) : 0,
            'formatted_total_revenue' => '$' . number_format($totalRevenue, 2),
            'formatted_pending_revenue' => '$' . number_format($pendingRevenue, 2)
        ],
        'top_courses' => $topCourses,
        'monthly_trend' => $monthlyTrend
    ]);
}
?>
