<?php
session_start();
require_once '../../../include/connect.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            handleGetCourses();
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

function handleGetCourses()
{
    global $conn;

    $user_id = $_SESSION['user_id'];

    // Get student_id from user_id
    $student_query = "SELECT student_id FROM students WHERE user_id = ?";
    $stmt = $conn->prepare($student_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Student profile not found']);
        return;
    }

    $student_id = $result->fetch_assoc()['student_id'];

    // Get filter parameters
    $category = isset($_GET['category']) ? $_GET['category'] : null;
    $level = isset($_GET['level']) ? $_GET['level'] : null;
    $search = isset($_GET['search']) ? $_GET['search'] : null;
    $price_filter = isset($_GET['price']) ? $_GET['price'] : null; // 'free', 'paid', 'all'

    // Build the query to get all published courses with enrollment status
    $query = "
        SELECT 
            c.course_id,
            c.course_title,
            c.description,
            c.price,
            c.category,
            c.level,
            c.status,
            c.visibility,
            c.image_url,
            c.created_at,
            c.start_date,
            c.end_date,
            c.max_students,
            u.first_name,
            u.last_name,
            COUNT(DISTINCT e.enrollment_id) as enrolled_count,
            COUNT(DISTINCT cl.lesson_id) as lesson_count,
            COUNT(DISTINCT a.assignment_id) as assignment_count,
            e2.enrollment_id as user_enrollment_id,
            e2.payment_status as user_payment_status,
            e2.enrolled_at as user_enrolled_at
        FROM courses c
        JOIN users u ON c.teacher_id = u.user_id
        LEFT JOIN enrollments e ON c.course_id = e.course_id
        LEFT JOIN course_lessons cl ON c.course_id = cl.course_id
        LEFT JOIN assignments a ON c.course_id = a.course_id
        LEFT JOIN enrollments e2 ON c.course_id = e2.course_id AND e2.student_id = ?
        WHERE c.status = 'Published'
    ";

    $params = [$student_id];
    $types = 'i';

    // Add filters
    if ($category) {
        $query .= " AND c.category = ?";
        $params[] = $category;
        $types .= 's';
    }

    if ($level) {
        $query .= " AND c.level = ?";
        $params[] = $level;
        $types .= 's';
    }

    if ($search) {
        $query .= " AND (c.course_title LIKE ? OR c.description LIKE ?)";
        $search_param = "%$search%";
        $params[] = $search_param;
        $params[] = $search_param;
        $types .= 'ss';
    }

    if ($price_filter === 'free') {
        $query .= " AND c.price = 0";
    } elseif ($price_filter === 'paid') {
        $query .= " AND c.price > 0";
    }

    $query .= " GROUP BY c.course_id ORDER BY c.created_at DESC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $courses = [];
    while ($row = $result->fetch_assoc()) {
        // Determine course status for the user
        $course_status = 'not_started';
        $progress_percentage = 0;

        if ($row['user_enrollment_id']) {
            if ($row['user_payment_status'] === 'Paid' || $row['price'] == 0) {
                $course_status = 'enrolled';
                // Calculate progress (simplified - you can enhance this based on lesson completion)
                $progress_percentage = rand(0, 100); // Placeholder - implement actual progress calculation

                if ($progress_percentage >= 100) {
                    $course_status = 'completed';
                } elseif ($progress_percentage > 0) {
                    $course_status = 'in_progress';
                }
            } else {
                $course_status = 'pending_payment';
            }
        }

        $courses[] = [
            'course_id' => (int)$row['course_id'],
            'course_title' => $row['course_title'],
            'description' => $row['description'],
            'price' => (float)$row['price'],
            'category' => $row['category'],
            'level' => $row['level'],
            'status' => $row['status'],
            'visibility' => $row['visibility'],
            'image_url' => $row['image_url'],
            'created_at' => $row['created_at'],
            'start_date' => $row['start_date'],
            'end_date' => $row['end_date'],
            'max_students' => $row['max_students'] ? (int)$row['max_students'] : null,
            'teacher_name' => $row['first_name'] . ' ' . $row['last_name'],
            'enrolled_count' => (int)$row['enrolled_count'],
            'lesson_count' => (int)$row['lesson_count'],
            'assignment_count' => (int)$row['assignment_count'],
            'user_enrollment_id' => $row['user_enrollment_id'] ? (int)$row['user_enrollment_id'] : null,
            'user_payment_status' => $row['user_payment_status'],
            'user_enrolled_at' => $row['user_enrolled_at'],
            'course_status' => $course_status,
            'progress_percentage' => $progress_percentage,
            'is_free' => $row['price'] == 0,
            'is_enrolled' => !is_null($row['user_enrollment_id']),
            'can_enroll' => is_null($row['user_enrollment_id']) && $row['status'] === 'Published'
        ];
    }

    echo json_encode(['courses' => $courses]);
}
