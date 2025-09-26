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
$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

if (!$course_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Course ID is required']);
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
        error_log('User not enrolled in course: ' . $course_id . "User Id: " . $user_id);
        exit;
    }

    // Get course information
    $course_query = "SELECT c.*, u.first_name, u.last_name, u.profile_image_url,
                     COUNT(DISTINCT cm.module_id) as total_modules,
                     COUNT(DISTINCT cl.lesson_id) as total_lessons,
                     COUNT(DISTINCT lc.lesson_id) as completed_lessons
                     FROM courses c
                     LEFT JOIN users u ON c.teacher_id = u.user_id
                     LEFT JOIN course_modules cm ON c.course_id = cm.course_id AND cm.is_published = 0
                     LEFT JOIN course_lessons cl ON cm.module_id = cl.module_id
                     LEFT JOIN lesson_completions lc ON cl.lesson_id = lc.lesson_id AND lc.user_id = ?
                     WHERE c.course_id = ? AND c.status = 'Published'
                     GROUP BY c.course_id";

    $stmt = $conn->prepare($course_query);
    $stmt->bind_param("ii", $user_id, $course_id);
    $stmt->execute();
    $course = $stmt->get_result()->fetch_assoc();

    if (!$course) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Course not found']);
        exit;
    }

    // Calculate progress
    $progress = $course['total_lessons'] > 0 ?
        round(($course['completed_lessons'] / $course['total_lessons']) * 100, 1) : 0;

    // Get completed modules count
    $completed_modules_query = "SELECT COUNT(DISTINCT cm.module_id) as completed_modules
                               FROM course_modules cm
                               JOIN course_lessons cl ON cm.module_id = cl.module_id
                               LEFT JOIN lesson_completions lc ON cl.lesson_id = lc.lesson_id AND lc.user_id = ?
                               WHERE cm.course_id = ?
                               GROUP BY cm.module_id
                               HAVING COUNT(cl.lesson_id) = COUNT(lc.lesson_id)";

    $stmt = $conn->prepare($completed_modules_query);
    $stmt->bind_param("ii", $user_id, $course_id);
    $stmt->execute();
    $completed_modules_result = $stmt->get_result();
    $completed_modules = $completed_modules_result->num_rows;

    // Get current lesson info
    $current_lesson_query = "SELECT cl.title as lesson_title, cm.title as module_title
                            FROM course_lessons cl
                            JOIN course_modules cm ON cl.module_id = cm.module_id
                            LEFT JOIN lesson_completions lc ON cl.lesson_id = lc.lesson_id AND lc.user_id = ?
                            WHERE cm.course_id = ? AND lc.lesson_id IS NULL
                            ORDER BY cm.sort_order ASC, cl.sort_order ASC
                            LIMIT 1";

    $stmt = $conn->prepare($current_lesson_query);
    $stmt->bind_param("ii", $user_id, $course_id);
    $stmt->execute();
    $current_lesson = $stmt->get_result()->fetch_assoc();

    // Get modules with progress
    $modules_query = "SELECT cm.*, 
                      COUNT(DISTINCT cl.lesson_id) as lesson_count,
                      COUNT(DISTINCT lc.lesson_id) as completed_lessons,
                      CASE 
                        WHEN COUNT(cl.lesson_id) > 0 THEN 
                          ROUND((COUNT(lc.lesson_id) / COUNT(cl.lesson_id)) * 100, 1)
                        ELSE 0 
                      END as progress,
                      CASE 
                        WHEN COUNT(cl.lesson_id) = COUNT(lc.lesson_id) AND COUNT(cl.lesson_id) > 0 THEN 1
                        ELSE 0 
                      END as is_completed
                      FROM course_modules cm
                      LEFT JOIN course_lessons cl ON cm.module_id = cl.module_id
                      LEFT JOIN lesson_completions lc ON cl.lesson_id = lc.lesson_id AND lc.user_id = ?
                      WHERE cm.course_id = ? AND cm.is_published = 0
                      GROUP BY cm.module_id
                      ORDER BY cm.sort_order ASC";

    $stmt = $conn->prepare($modules_query);
    $stmt->bind_param("ii", $user_id, $course_id);
    $stmt->execute();
    $modules_result = $stmt->get_result();

    // error_log(var_dump($modules_result));

    $modules = [];
    while ($module = $modules_result->fetch_assoc()) {
        $module['is_current'] = ($current_lesson && $current_lesson['lesson_title'] === $module['title']) ? 1 : 0;
        $modules[] = $module;
    }

    // Prepare course data
    $course_data = [
        'course_id' => $course['course_id'],
        'title' => $course['course_title'],
        'description' => $course['description'],
        'instructor_name' => $course['first_name'] . ' ' . $course['last_name'],
        'instructor_image' => $course['profile_image_url'],
        'progress' => $progress,
        'total_lessons' => (int)$course['total_lessons'],
        'completed_lessons' => (int)$course['completed_lessons'],
        'total_modules' => (int)$course['total_modules'],
        'completed_modules' => $completed_modules,
        'current_module' => $current_lesson ? $current_lesson['module_title'] : null,
        'current_lesson' => $current_lesson ? $current_lesson['lesson_title'] : null,
        'enrollment_date' => $enrollment['enrollment_date'] ?? null
    ];

    echo json_encode([
        'success' => true,
        'course' => $course_data,
        'modules' => $modules
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    error_log($e);
}
