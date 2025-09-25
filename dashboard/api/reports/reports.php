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
            $report_type = normalizeReportType($_GET['type'] ?? 'overview');
            switch ($report_type) {
                case 'overview':
                    handleGetOverview();
                    break;
                case 'course_performance':
                    handleGetCoursePerformance();
                    break;
                case 'student_engagement':
                    handleGetStudentEngagement();
                    break;
                case 'revenue_analysis':
                    handleGetRevenueAnalysis();
                    break;
                case 'assignment_analytics':
                    handleGetAssignmentAnalytics();
                    break;
                default:
                    http_response_code(400);
                    echo json_encode(['error' => 'Invalid report type']);
                    break;
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
    error_log($e->getMessage());
}

function safePercentage($numerator, $denominator, $precision = 1)
{
    $num = floatval($numerator);
    $den = floatval($denominator);
    if ($den <= 0) {
        return 0;
    }
    return round(($num / $den) * 100, $precision);
}

function normalizeReportType($type)
{
    if (!is_string($type) || $type === '') {
        return 'overview';
    }
    // Convert camelCase to snake_case, then replace hyphens with underscores
    $snake = preg_replace('/([a-z])([A-Z])/', '$1_$2', $type);
    $snake = strtolower(str_replace('-', '_', $snake));
    return $snake;
}

function handleGetOverview()
{
    global $conn;
    $user_id = $_SESSION['user_id'];
    $period = $_GET['period'] ?? 'month';

    $dateFilter = getDateFilter($period);

    // Total courses
    $stmt = $conn->prepare("SELECT COUNT(*) as total_courses FROM courses WHERE teacher_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $totalCourses = $stmt->get_result()->fetch_assoc()['total_courses'];

    // Total students (unique enrollments)
    $stmt = $conn->prepare("SELECT COUNT(DISTINCT e.student_id) as total_students 
                           FROM enrollments e 
                           JOIN courses c ON e.course_id = c.course_id 
                           WHERE c.teacher_id = ?" . $dateFilter);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $totalStudents = $stmt->get_result()->fetch_assoc()['total_students'];

    // Total revenue
    $stmt = $conn->prepare("SELECT COALESCE(SUM(c.price), 0) as total_revenue 
                           FROM enrollments e 
                           JOIN courses c ON e.course_id = c.course_id 
                           WHERE c.teacher_id = ? AND e.payment_status = 'Paid'" . $dateFilter);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $totalRevenue = $stmt->get_result()->fetch_assoc()['total_revenue'];

    // Total assignments
    $stmt = $conn->prepare("SELECT COUNT(*) as total_assignments 
                           FROM assignments a 
                           JOIN courses c ON a.course_id = c.course_id 
                           WHERE c.teacher_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $totalAssignments = $stmt->get_result()->fetch_assoc()['total_assignments'];

    // Completion rate (assignments with submissions)
    $stmt = $conn->prepare("SELECT 
                              COUNT(DISTINCT a.assignment_id) as assignments_with_submissions,
                              COUNT(DISTINCT s.submission_id) as total_submissions
                           FROM assignments a 
                           JOIN courses c ON a.course_id = c.course_id 
                           LEFT JOIN submissions s ON a.assignment_id = s.assignment_id
                           WHERE c.teacher_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    $denominator = intval($totalAssignments) * intval($totalStudents);
    $completionRate = safePercentage($result['total_submissions'], $denominator);

    echo json_encode([
        'success' => [
            'overview' => [
                'total_courses' => intval($totalCourses),
                'total_students' => intval($totalStudents),
                'total_revenue' => floatval($totalRevenue),
                'total_assignments' => intval($totalAssignments),
                'completion_rate' => $completionRate,
                'formatted_revenue' => '$' . number_format($totalRevenue, 2)
            ]
        ]
    ]);
}

function handleGetCoursePerformance()
{
    global $conn;
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT 
                              c.course_id,
                              c.course_title,
                              c.category,
                              c.price,
                              c.created_at,
                              COUNT(DISTINCT e.student_id) as total_enrollments,
                              COUNT(DISTINCT CASE WHEN e.payment_status = 'Paid' THEN e.student_id END) as paid_enrollments,
                              COALESCE(SUM(CASE WHEN e.payment_status = 'Paid' THEN c.price ELSE 0 END), 0) as revenue,
                              COUNT(DISTINCT a.assignment_id) as total_assignments,
                              COUNT(DISTINCT s.submission_id) as total_submissions,
                              AVG(s.grade) as avg_grade
                           FROM courses c
                           LEFT JOIN enrollments e ON c.course_id = e.course_id
                           LEFT JOIN assignments a ON c.course_id = a.course_id
                           LEFT JOIN submissions s ON a.assignment_id = s.assignment_id
                           WHERE c.teacher_id = ?
                           GROUP BY c.course_id
                           ORDER BY revenue DESC");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $courses = [];
    while ($row = $result->fetch_assoc()) {
        $conversionRate = safePercentage($row['paid_enrollments'], $row['total_enrollments']);

        $completionRate = safePercentage(
            $row['total_submissions'],
            intval($row['total_assignments']) * intval($row['paid_enrollments'])
        );

        $courses[] = [
            'course_id' => $row['course_id'],
            'course_title' => $row['course_title'],
            'category' => $row['category'],
            'price' => floatval($row['price']),
            'total_enrollments' => intval($row['total_enrollments']),
            'paid_enrollments' => intval($row['paid_enrollments']),
            'revenue' => floatval($row['revenue']),
            'total_assignments' => intval($row['total_assignments']),
            'total_submissions' => intval($row['total_submissions']),
            'avg_grade' => $row['avg_grade'] ? round(floatval($row['avg_grade']), 1) : null,
            'conversion_rate' => $conversionRate,
            'completion_rate' => $completionRate,
            'formatted_revenue' => '$' . number_format($row['revenue'], 2)
        ];
    }

    echo json_encode(['success' => ['courses' => $courses]]);
}

function handleGetStudentEngagement()
{
    global $conn;
    $user_id = $_SESSION['user_id'];

    // Top performing students
    $stmt = $conn->prepare("SELECT 
                              u.first_name,
                              u.last_name,
                              u.email,
                              COUNT(DISTINCT e.course_id) as courses_enrolled,
                              COUNT(DISTINCT s.submission_id) as submissions_count,
                              AVG(s.grade) as avg_grade,
                              SUM(c.price) as total_spent
                           FROM users u
                           JOIN students st ON u.user_id = st.user_id
                           JOIN enrollments e ON st.student_id = e.student_id
                           JOIN courses c ON e.course_id = c.course_id
                           LEFT JOIN assignments a ON c.course_id = a.course_id
                           LEFT JOIN submissions s ON a.assignment_id = s.assignment_id AND s.student_id = st.student_id
                           WHERE c.teacher_id = ? AND e.payment_status = 'Paid'
                           GROUP BY u.user_id
                           ORDER BY avg_grade DESC, submissions_count DESC
                           LIMIT 10");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $topStudents = [];
    while ($row = $result->fetch_assoc()) {
        $topStudents[] = [
            'student_name' => $row['first_name'] . ' ' . $row['last_name'],
            'email' => $row['email'],
            'courses_enrolled' => intval($row['courses_enrolled']),
            'submissions_count' => intval($row['submissions_count']),
            'avg_grade' => $row['avg_grade'] ? round(floatval($row['avg_grade']), 1) : null,
            'total_spent' => floatval($row['total_spent']),
            'formatted_spent' => '$' . number_format($row['total_spent'], 2)
        ];
    }

    // Engagement metrics by course
    $stmt = $conn->prepare("SELECT 
                              c.course_title,
                              COUNT(DISTINCT e.student_id) as enrolled_students,
                              COUNT(DISTINCT s.submission_id) as total_submissions,
                              COUNT(DISTINCT CASE WHEN s.grade >= 70 THEN s.student_id END) as passing_students
                           FROM courses c
                           LEFT JOIN enrollments e ON c.course_id = e.course_id AND e.payment_status = 'Paid'
                           LEFT JOIN assignments a ON c.course_id = a.course_id
                           LEFT JOIN submissions s ON a.assignment_id = s.assignment_id
                           WHERE c.teacher_id = ?
                           GROUP BY c.course_id
                           ORDER BY enrolled_students DESC");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $courseEngagement = [];
    while ($row = $result->fetch_assoc()) {
        $passRate = safePercentage($row['passing_students'], $row['enrolled_students']);

        $courseEngagement[] = [
            'course_title' => $row['course_title'],
            'enrolled_students' => intval($row['enrolled_students']),
            'total_submissions' => intval($row['total_submissions']),
            'passing_students' => intval($row['passing_students']),
            'pass_rate' => $passRate
        ];
    }

    echo json_encode([
        'success' => [
            'top_students' => $topStudents,
            'course_engagement' => $courseEngagement
        ]
    ]);
}

function handleGetRevenueAnalysis()
{
    global $conn;
    $user_id = $_SESSION['user_id'];

    // Monthly revenue trend (last 12 months)
    $stmt = $conn->prepare("SELECT 
                              DATE_FORMAT(e.enrolled_at, '%Y-%m') as month,
                              COUNT(*) as enrollments,
                              COUNT(CASE WHEN e.payment_status = 'Paid' THEN 1 END) as paid_enrollments,
                              SUM(CASE WHEN e.payment_status = 'Paid' THEN c.price ELSE 0 END) as revenue
                           FROM enrollments e
                           JOIN courses c ON e.course_id = c.course_id
                           WHERE c.teacher_id = ? AND e.enrolled_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                           GROUP BY DATE_FORMAT(e.enrolled_at, '%Y-%m')
                           ORDER BY month ASC");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $monthlyRevenue = [];
    while ($row = $result->fetch_assoc()) {
        $monthlyRevenue[] = [
            'month' => $row['month'],
            'enrollments' => intval($row['enrollments']),
            'paid_enrollments' => intval($row['paid_enrollments']),
            'revenue' => floatval($row['revenue'])
        ];
    }

    // Revenue by category
    $stmt = $conn->prepare("SELECT 
                              c.category,
                              COUNT(DISTINCT e.enrollment_id) as total_enrollments,
                              COUNT(DISTINCT CASE WHEN e.payment_status = 'Paid' THEN e.enrollment_id END) as paid_enrollments,
                              SUM(CASE WHEN e.payment_status = 'Paid' THEN c.price ELSE 0 END) as revenue
                           FROM courses c
                           LEFT JOIN enrollments e ON c.course_id = e.course_id
                           WHERE c.teacher_id = ?
                           GROUP BY c.category
                           ORDER BY revenue DESC");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $categoryRevenue = [];
    while ($row = $result->fetch_assoc()) {
        $categoryRevenue[] = [
            'category' => $row['category'],
            'total_enrollments' => intval($row['total_enrollments']),
            'paid_enrollments' => intval($row['paid_enrollments']),
            'revenue' => floatval($row['revenue']),
            'formatted_revenue' => '$' . number_format($row['revenue'], 2)
        ];
    }

    echo json_encode([
        'success' => [
            'monthly_revenue' => $monthlyRevenue,
            'category_revenue' => $categoryRevenue
        ]
    ]);
}

function handleGetAssignmentAnalytics()
{
    global $conn;
    $user_id = $_SESSION['user_id'];

    // Assignment performance
    $stmt = $conn->prepare("SELECT 
                              a.title as assignment_title,
                              c.course_title,
                              COUNT(DISTINCT s.submission_id) as total_submissions,
                              AVG(s.grade) as avg_grade,
                              COUNT(DISTINCT CASE WHEN s.grade >= 70 THEN s.submission_id END) as passing_submissions,
                              COUNT(DISTINCT CASE WHEN s.submitted_at > a.due_date THEN s.submission_id END) as late_submissions
                           FROM assignments a
                           JOIN courses c ON a.course_id = c.course_id
                           LEFT JOIN submissions s ON a.assignment_id = s.assignment_id
                           WHERE c.teacher_id = ?
                           GROUP BY a.assignment_id
                           ORDER BY avg_grade DESC");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $assignments = [];
    while ($row = $result->fetch_assoc()) {
        $passRate = safePercentage($row['passing_submissions'], $row['total_submissions']);

        $lateRate = safePercentage($row['late_submissions'], $row['total_submissions']);

        $assignments[] = [
            'assignment_title' => $row['assignment_title'],
            'course_title' => $row['course_title'],
            'total_submissions' => intval($row['total_submissions']),
            'avg_grade' => $row['avg_grade'] ? round(floatval($row['avg_grade']), 1) : null,
            'passing_submissions' => intval($row['passing_submissions']),
            'late_submissions' => intval($row['late_submissions']),
            'pass_rate' => $passRate,
            'late_rate' => $lateRate
        ];
    }

    echo json_encode(['success' => ['assignments' => $assignments]]);
}

function getDateFilter($period)
{
    switch ($period) {
        case 'today':
            return " AND DATE(e.enrolled_at) = CURDATE()";
        case 'week':
            return " AND e.enrolled_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        case 'month':
            return " AND e.enrolled_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        case 'year':
            return " AND e.enrolled_at >= DATE_SUB(NOW(), INTERVAL 365 DAY)";
        default:
            return "";
    }
}
