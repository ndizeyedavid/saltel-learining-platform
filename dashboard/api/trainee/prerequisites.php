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
            handleGetPrerequisites();
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

function handleGetPrerequisites()
{
    global $conn;

    $user_id = $_SESSION['user_id'];
    $course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : null;

    if (!$course_id) {
        http_response_code(400);
        echo json_encode(['error' => 'Course ID is required']);
        return;
    }

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

    // Get course prerequisites
    $prerequisites_query = "
        SELECT 
            cp.prerequisite_id,
            cp.prerequisite_type,
            cp.prerequisite_value,
            cp.required_score,
            cl.title as lesson_title,
            c.course_title,
            c.course_id
        FROM course_prerequisites cp
        JOIN course_lessons cl ON cp.lesson_id = cl.lesson_id
        JOIN courses c ON cl.course_id = c.course_id
        WHERE c.course_id = ?
    ";

    $stmt = $conn->prepare($prerequisites_query);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $prerequisites = [];
    $all_met = true;

    while ($row = $result->fetch_assoc()) {
        $prerequisite = [
            'prerequisite_id' => (int)$row['prerequisite_id'],
            'prerequisite_type' => $row['prerequisite_type'],
            'prerequisite_value' => $row['prerequisite_value'],
            'required_score' => $row['required_score'],
            'lesson_title' => $row['lesson_title'],
            'course_title' => $row['course_title'],
            'course_id' => (int)$row['course_id']
        ];

        // Check if prerequisite is met
        $is_met = checkPrerequisiteMet($student_id, $user_id, $prerequisite);
        $prerequisite['is_met'] = $is_met;
        $prerequisite['status'] = $is_met ? 'completed' : 'pending';

        if (!$is_met) {
            $all_met = false;
        }

        $prerequisites[] = $prerequisite;
    }

    echo json_encode([
        'course_id' => $course_id,
        'prerequisites' => $prerequisites,
        'all_prerequisites_met' => $all_met,
        'can_enroll' => $all_met
    ]);
}

function checkPrerequisiteMet($student_id, $user_id, $prerequisite)
{
    global $conn;

    switch ($prerequisite['prerequisite_type']) {
        case 'module_completion':
            return checkModuleCompletion($student_id, $prerequisite['prerequisite_value']);

        case 'lesson_completion':
            return checkLessonCompletion($user_id, $prerequisite['prerequisite_value']);

        case 'quiz_score':
            return checkQuizScore($student_id, $prerequisite['prerequisite_value'], $prerequisite['required_score']);

        case 'assignment_submission':
            return checkAssignmentSubmission($student_id, $prerequisite['prerequisite_value']);

        default:
            return false;
    }
}

function checkModuleCompletion($student_id, $module_id)
{
    global $conn;

    // Check if student has completed all lessons in the module
    $module_lessons_query = "
        SELECT COUNT(*) as total_lessons
        FROM course_lessons
        WHERE module_id = ?
    ";

    $stmt = $conn->prepare($module_lessons_query);
    $stmt->bind_param("i", $module_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_lessons = $result->fetch_assoc()['total_lessons'];

    if ($total_lessons == 0) {
        return true; // No lessons in module, consider it completed
    }

    // Check completed lessons in this module
    $completed_lessons_query = "
        SELECT COUNT(DISTINCT cl.lesson_id) as completed_lessons
        FROM course_lessons cl
        JOIN study_sessions ss ON cl.course_id = ss.course_id
        WHERE cl.module_id = ? AND ss.user_id = ? AND ss.session_duration >= 15
    ";

    $stmt = $conn->prepare($completed_lessons_query);
    $stmt->bind_param("ii", $module_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $completed_lessons = $result->fetch_assoc()['completed_lessons'];

    return $completed_lessons >= $total_lessons;
}

function checkLessonCompletion($user_id, $lesson_id)
{
    global $conn;

    // Check if user has completed the specific lesson (study session >= 15 minutes)
    $lesson_completion_query = "
        SELECT COUNT(*) as completed
        FROM study_sessions ss
        JOIN course_lessons cl ON ss.course_id = cl.course_id
        WHERE cl.lesson_id = ? AND ss.user_id = ? AND ss.session_duration >= 15
    ";

    $stmt = $conn->prepare($lesson_completion_query);
    $stmt->bind_param("ii", $lesson_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $completed = $result->fetch_assoc()['completed'];

    return $completed > 0;
}

function checkQuizScore($student_id, $quiz_id, $required_score)
{
    global $conn;

    // This is a simplified implementation
    // In a real system, you would have quiz attempts and scores stored
    // For now, we'll check if the student has any study sessions for the course containing this quiz

    $quiz_course_query = "
        SELECT c.course_id
        FROM courses c
        JOIN course_lessons cl ON c.course_id = cl.course_id
        WHERE cl.lesson_id = ? AND cl.lesson_type = 'quiz'
    ";

    $stmt = $conn->prepare($quiz_course_query);
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return false;
    }

    $course_id = $result->fetch_assoc()['course_id'];

    // Check if student has study sessions for this course (simplified check)
    $study_sessions_query = "
        SELECT COUNT(*) as sessions
        FROM study_sessions
        WHERE user_id = ? AND course_id = ? AND session_duration >= 15
    ";

    $stmt = $conn->prepare($study_sessions_query);
    $stmt->bind_param("ii", $_SESSION['user_id'], $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $sessions = $result->fetch_assoc()['sessions'];

    // Simplified: if student has study sessions, consider quiz passed
    // In reality, you'd check actual quiz scores
    return $sessions > 0;
}

function checkAssignmentSubmission($student_id, $assignment_id)
{
    global $conn;

    // Check if student has submitted the assignment
    $submission_query = "
        SELECT COUNT(*) as submitted
        FROM submissions
        WHERE student_id = ? AND assignment_id = ?
    ";

    $stmt = $conn->prepare($submission_query);
    $stmt->bind_param("ii", $student_id, $assignment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $submitted = $result->fetch_assoc()['submitted'];

    return $submitted > 0;
}
