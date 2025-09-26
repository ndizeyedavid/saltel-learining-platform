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
$module_id = isset($_GET['module_id']) ? (int)$_GET['module_id'] : 0;
$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

if (!$module_id || !$course_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Module ID and Course ID are required']);
    exit;
}

try {
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
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Not enrolled in this course']);
        exit;
    }

    // Verify module belongs to course
    $module_check_query = "SELECT module_id FROM course_modules WHERE module_id = ? AND course_id = ?";
    $stmt = $conn->prepare($module_check_query);
    $stmt->bind_param("ii", $module_id, $course_id);
    $stmt->execute();
    $module_exists = $stmt->get_result()->fetch_assoc();

    if (!$module_exists) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Module not found in this course']);
        exit;
    }

    // Get lessons for the module
    $lessons_query = "SELECT cl.*, 
                      CASE WHEN lc.lesson_id IS NOT NULL THEN 1 ELSE 0 END as is_completed,
                      CASE WHEN cl.lesson_id = (
                          SELECT cl2.lesson_id 
                          FROM course_lessons cl2 
                          LEFT JOIN lesson_completions lc2 ON cl2.lesson_id = lc2.lesson_id AND lc2.user_id = ?
                          WHERE cl2.module_id = ? AND lc2.lesson_id IS NULL
                          ORDER BY cl2.sort_order ASC 
                          LIMIT 1
                      ) THEN 1 ELSE 0 END as is_current
                      FROM course_lessons cl
                      LEFT JOIN lesson_completions lc ON cl.lesson_id = lc.lesson_id AND lc.user_id = ?
                      WHERE cl.module_id = ?
                      ORDER BY cl.sort_order ASC";

    $stmt = $conn->prepare($lessons_query);
    $stmt->bind_param("iiii", $user_id, $module_id, $user_id, $module_id);
    $stmt->execute();
    $lessons_result = $stmt->get_result();

    $lessons = [];
    while ($lesson = $lessons_result->fetch_assoc()) {
        // Calculate estimated duration (you can store this in DB or calculate based on content)
        $duration = $lesson['estimated_duration'] ?? '5 min';

        $lessons[] = [
            'lesson_id' => $lesson['lesson_id'],
            'title' => $lesson['title'],
            // 'description' => $lesson['description'],
            'lesson_order' => $lesson['sort_order'],
            'duration' => $duration,
            'is_completed' => (bool)$lesson['is_completed'],
            'is_current' => (bool)$lesson['is_current'],
            'created_at' => $lesson['created_at']
        ];
    }

    echo json_encode([
        'success' => true,
        'lessons' => $lessons,
        'module_id' => $module_id
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    error_log($e);
}
