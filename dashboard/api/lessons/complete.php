<?php
session_start();
require_once '../../../include/connect.php';

// Check if user is logged in and is a trainee
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['lesson_id']) || !isset($input['course_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

$lesson_id = (int)$input['lesson_id'];
$course_id = (int)$input['course_id'];

try {
    $conn->begin_transaction();

    // Verify enrollment
    $get_student_id = "SELECT student_id FROM students WHERE user_id = ?";
    $stmt = $conn->prepare($get_student_id);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();

    $enrollment_query = "SELECT enrollment_id FROM enrollments WHERE student_id = ? AND course_id = ? AND payment_status = 'Paid'";
    $stmt = $conn->prepare($enrollment_query);
    $stmt->bind_param("ii", $student['student_id'], $course_id);
    $stmt->execute();
    $enrollment = $stmt->get_result()->fetch_assoc();

    if (!$enrollment) {
        throw new Exception('Not enrolled in this course');
    }

    // Check if lesson completion already exists
    $check_query = "SELECT completion_id FROM lesson_completions WHERE user_id = ? AND lesson_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $user_id, $lesson_id);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();

    if (!$existing) {
        // Insert lesson completion
        $insert_query = "INSERT INTO lesson_completions (user_id, lesson_id, completed_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ii", $user_id, $lesson_id);
        $stmt->execute();

        // Award XP for lesson completion
        $activity_id = 2; // complete_lesson activity from xp_activities table
        $xp_amount = 25; // XP reward from xp_activities table

        // Insert XP transaction
        $xp_query = "INSERT INTO xp_transactions (user_id, activity_id, xp_earned, description, earned_at) VALUES (?, ?, ?, 'Lesson completion', NOW())";
        $stmt = $conn->prepare($xp_query);
        $stmt->bind_param("iii", $user_id, $activity_id, $xp_amount);
        $stmt->execute();

        // Update user's total XP in user_xp table
        $update_xp_query = "INSERT INTO user_xp (user_id, total_xp, last_activity_date) VALUES (?, ?, CURDATE()) 
                           ON DUPLICATE KEY UPDATE 
                           total_xp = total_xp + VALUES(total_xp), 
                           last_activity_date = CURDATE()";
        $stmt = $conn->prepare($update_xp_query);
        $stmt->bind_param("ii", $user_id, $xp_amount);
        $stmt->execute();
    }

    // Calculate course progress
    $progress_query = "SELECT 
        COUNT(DISTINCT l.lesson_id) as total_lessons,
        COUNT(DISTINCT lc.lesson_id) as completed_lessons
        FROM course_lessons l
        JOIN course_modules cm ON l.module_id = cm.module_id
        LEFT JOIN lesson_completions lc ON l.lesson_id = lc.lesson_id AND lc.user_id = ?
        WHERE cm.course_id = ?";
    $stmt = $conn->prepare($progress_query);
    $stmt->bind_param("ii", $user_id, $course_id);
    $stmt->execute();
    $progress = $stmt->get_result()->fetch_assoc();

    $progress_percentage = $progress['total_lessons'] > 0 ?
        round(($progress['completed_lessons'] / $progress['total_lessons']) * 100, 2) : 0;

    // Update enrollment progress
    $update_progress_query = "UPDATE enrollments SET progress_percentage = ? WHERE enrollment_id = ?";
    $stmt = $conn->prepare($update_progress_query);
    $stmt->bind_param("di", $progress_percentage, $enrollment['enrollment_id']);
    $stmt->execute();

    // Check if course is completed (100% progress)
    if ($progress_percentage >= 100) {
        // Award course completion XP
        $course_activity_id = 6; // course_completion activity from xp_activities table
        $course_xp = 200; // XP reward from xp_activities table

        // Insert course completion XP transaction
        $course_xp_query = "INSERT INTO xp_transactions (user_id, activity_id, xp_earned, description, earned_at) VALUES (?, ?, ?, 'Course completion', NOW())";
        $stmt = $conn->prepare($course_xp_query);
        $stmt->bind_param("iii", $user_id, $course_activity_id, $course_xp);
        $stmt->execute();

        // Update user's total XP in user_xp table
        $update_total_xp_query = "INSERT INTO user_xp (user_id, total_xp, last_activity_date) VALUES (?, ?, CURDATE()) 
                                 ON DUPLICATE KEY UPDATE 
                                 total_xp = total_xp + VALUES(total_xp), 
                                 last_activity_date = CURDATE()";
        $stmt = $conn->prepare($update_total_xp_query);
        $stmt->bind_param("ii", $user_id, $course_xp);
        $stmt->execute();

        // Generate certificate if not exists
        $get_student_id = "SELECT student_id FROM students WHERE user_id = ?";
        $stmt = $conn->prepare($get_student_id);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $student = $stmt->get_result()->fetch_assoc();

        $cert_check_query = "SELECT certificate_id FROM certificates WHERE student_id = ? AND course_id = ?";
        $stmt = $conn->prepare($cert_check_query);
        $stmt->bind_param("ii", $student['student_id'], $course_id);
        $stmt->execute();
        $cert_exists = $stmt->get_result()->fetch_assoc();

        if (!$cert_exists) {
            $cert_query = "INSERT INTO certificates (student_id, course_id, certificate_code, issued_at) 
                          VALUES (?, ?, CONCAT('CERT-', UPPER(SUBSTRING(MD5(RAND()), 1, 8))), NOW())";
            $stmt = $conn->prepare($cert_query);
            $stmt->bind_param("ii", $student['student_id'], $course_id);
            $stmt->execute();
        }
    }

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Lesson completed successfully',
        'progress' => $progress_percentage,
        'xp_earned' => $existing ? 0 : $xp_amount,
        'course_completed' => $progress_percentage >= 100
    ]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    error_log($e);
}
