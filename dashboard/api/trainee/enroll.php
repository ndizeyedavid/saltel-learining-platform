<?php
session_start();
require_once '../../../include/connect.php';

header('Content-Type: application/json');

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'POST':
            handleEnrollment();
            break;
        case 'GET':
            handleGetEnrollments();
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

function handleEnrollment()
{
    global $conn;

    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['course_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Course ID is required']);
        return;
    }

    $user_id = $_SESSION['user_id'];
    $course_id = (int)$input['course_id'];

    // Get student_id from user_id and check if profile is complete
    $student_query = "SELECT student_id, institution, level_year, program FROM students WHERE user_id = ?";
    $stmt = $conn->prepare($student_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(403);
        echo json_encode([
            'error' => 'Student profile not found',
            'redirect' => 'settings.php',
            'message' => 'Please complete your student profile before enrolling in courses'
        ]);
        error_log("Student profile not found for user_id: $user_id");
        return;
    }

    $student_data = $result->fetch_assoc();
    $student_id = $student_data['student_id'];

    // Check if profile is complete (all required fields filled)
    if (empty($student_data['institution']) || empty($student_data['level_year']) || empty($student_data['program'])) {
        http_response_code(403);
        echo json_encode([
            'error' => 'Incomplete student profile',
            'redirect' => 'settings.php',
            'message' => 'Please complete your student profile (institution, level, and program) before enrolling in courses'
        ]);
        error_log("Incomplete student profile for user_id: $user_id");
        return;
    }

    // Check if already enrolled
    $enrollment_check = "SELECT enrollment_id FROM enrollments WHERE student_id = ? AND course_id = ?";
    $stmt = $conn->prepare($enrollment_check);
    $stmt->bind_param("ii", $student_id, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['error' => 'Already enrolled in this course']);
        error_log("Already enrolled in this course");
        return;
    }

    // Get course details
    $course_query = "SELECT course_title, price, status FROM courses WHERE course_id = ?";
    $stmt = $conn->prepare($course_query);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Course not found']);
        error_log("Course not found");
        return;
    }

    $course = $result->fetch_assoc();

    if ($course['status'] !== 'Published') {
        http_response_code(400);
        echo json_encode(['error' => 'Course is not available for enrollment']);
        error_log("Course is not available for enrollment");
        return;
    }

    // Determine payment status based on course price
    $payment_status = ($course['price'] > 0) ? 'Pending' : 'Paid';

    // Insert enrollment
    $enrollment_query = "INSERT INTO enrollments (student_id, course_id, payment_status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($enrollment_query);
    $stmt->bind_param("iis", $student_id, $course_id, $payment_status);

    if ($stmt->execute()) {
        $enrollment_id = $conn->insert_id;

        // Award XP for enrollment if it's a free course
        if ($course['price'] == 0) {
            awardXP($user_id, 'course_enrollment', 50, 'Enrolled in free course: ' . $course['course_title']);
        }

        echo json_encode([
            'success' => true,
            'enrollment_id' => $enrollment_id,
            'payment_status' => $payment_status,
            'message' => 'Successfully enrolled in course'
        ]);
    } else {
        error_log("Failed to create enrollment: " . $stmt->error);
        throw new Exception('Failed to create enrollment');
    }
}

function handleGetEnrollments()
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
        error_log("Student profile not found");
        return;
    }

    $student_id = $result->fetch_assoc()['student_id'];

    // Get enrollments with course details
    $enrollments_query = "
        SELECT 
            e.enrollment_id,
            e.course_id,
            e.payment_status,
            e.enrolled_at,
            c.course_title,
            c.description,
            c.price,
            c.category,
            c.level,
            c.status,
            c.image_url,
            u.first_name,
            u.last_name,
            COUNT(DISTINCT cl.lesson_id) as total_lessons,
            COUNT(DISTINCT a.assignment_id) as total_assignments
        FROM enrollments e
        JOIN courses c ON e.course_id = c.course_id
        JOIN users u ON c.teacher_id = u.user_id
        LEFT JOIN course_lessons cl ON c.course_id = cl.course_id
        LEFT JOIN assignments a ON c.course_id = a.course_id
        WHERE e.student_id = ?
        GROUP BY e.enrollment_id
        ORDER BY e.enrolled_at DESC
    ";

    $stmt = $conn->prepare($enrollments_query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $enrollments = [];
    while ($row = $result->fetch_assoc()) {
        $enrollments[] = [
            'enrollment_id' => (int)$row['enrollment_id'],
            'course_id' => (int)$row['course_id'],
            'course_title' => $row['course_title'],
            'description' => $row['description'],
            'price' => (float)$row['price'],
            'category' => $row['category'],
            'level' => $row['level'],
            'status' => $row['status'],
            'image_url' => $row['image_url'],
            'teacher_name' => $row['first_name'] . ' ' . $row['last_name'],
            'payment_status' => $row['payment_status'],
            'enrolled_at' => $row['enrolled_at'],
            'total_lessons' => (int)$row['total_lessons'],
            'total_assignments' => (int)$row['total_assignments']
        ];
    }

    echo json_encode(['enrollments' => $enrollments]);
}

function awardXP($user_id, $activity_name, $xp_amount, $description = '')
{
    global $conn;

    try {
        // Get activity_id
        $activity_query = "SELECT activity_id FROM xp_activities WHERE activity_name = ?";
        $stmt = $conn->prepare($activity_query);
        $stmt->bind_param("s", $activity_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return; // Activity not found
        }

        $activity_id = $result->fetch_assoc()['activity_id'];

        // Insert XP transaction
        $xp_query = "INSERT INTO xp_transactions (user_id, activity_id, xp_earned, description) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($xp_query);
        $stmt->bind_param("iiis", $user_id, $activity_id, $xp_amount, $description);
        $stmt->execute();

        // Update user XP
        $update_xp_query = "
            INSERT INTO user_xp (user_id, total_xp, current_level, xp_to_next_level) 
            VALUES (?, ?, 1, 100)
            ON DUPLICATE KEY UPDATE 
            total_xp = total_xp + ?,
            current_level = FLOOR((total_xp + ?) / 100) + 1,
            xp_to_next_level = 100 - ((total_xp + ?) % 100)
        ";
        $stmt = $conn->prepare($update_xp_query);
        $stmt->bind_param("iiiii", $user_id, $xp_amount, $xp_amount, $xp_amount, $xp_amount);
        $stmt->execute();
    } catch (Exception $e) {
        // Log error but don't fail the enrollment
        error_log("XP Award Error: " . $e->getMessage());
    }
}
