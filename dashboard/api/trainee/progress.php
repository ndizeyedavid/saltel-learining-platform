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
            handleGetProgress();
            break;
        case 'POST':
            handleUpdateProgress();
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

function handleGetProgress()
{
    global $conn;

    $user_id = $_SESSION['user_id'];
    $course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : null;

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

    if ($course_id) {
        // Get progress for specific course
        getCourseProgress($student_id, $course_id);
    } else {
        // Get progress for all enrolled courses
        getAllProgress($student_id);
    }
}

function getCourseProgress($student_id, $course_id)
{
    global $conn;

    // Check if student is enrolled in the course
    $enrollment_check = "SELECT enrollment_id, payment_status FROM enrollments WHERE student_id = ? AND course_id = ?";
    $stmt = $conn->prepare($enrollment_check);
    $stmt->bind_param("ii", $student_id, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Not enrolled in this course']);
        return;
    }

    $enrollment = $result->fetch_assoc();

    // Get course details
    $course_query = "
        SELECT c.course_id, c.course_title, c.description, c.price,
               COUNT(DISTINCT cl.lesson_id) as total_lessons,
               COUNT(DISTINCT a.assignment_id) as total_assignments
        FROM courses c
        LEFT JOIN course_lessons cl ON c.course_id = cl.course_id
        LEFT JOIN assignments a ON c.course_id = a.course_id
        WHERE c.course_id = ?
        GROUP BY c.course_id
    ";

    $stmt = $conn->prepare($course_query);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Course not found']);
        return;
    }

    $course = $result->fetch_assoc();

    // Get completed lessons (simplified - you can enhance this with actual lesson completion tracking)
    $completed_lessons_query = "
        SELECT COUNT(*) as completed_count
        FROM study_sessions ss
        WHERE ss.user_id = ? AND ss.course_id = ? AND ss.session_duration >= 15
    ";

    $stmt = $conn->prepare($completed_lessons_query);
    $stmt->bind_param("ii", $_SESSION['user_id'], $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $completed_lessons = $result->fetch_assoc()['completed_count'];

    // Get submitted assignments
    $submitted_assignments_query = "
        SELECT COUNT(*) as submitted_count
        FROM submissions s
        JOIN assignments a ON s.assignment_id = a.assignment_id
        WHERE s.student_id = ? AND a.course_id = ?
    ";

    $stmt = $conn->prepare($submitted_assignments_query);
    $stmt->bind_param("ii", $student_id, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $submitted_assignments = $result->fetch_assoc()['submitted_count'];

    // Calculate progress percentage
    $total_activities = $course['total_lessons'] + $course['total_assignments'];
    $completed_activities = $completed_lessons + $submitted_assignments;
    $progress_percentage = $total_activities > 0 ? round(($completed_activities / $total_activities) * 100) : 0;

    // Determine course status
    $course_status = 'enrolled';
    if ($progress_percentage >= 100) {
        $course_status = 'completed';
    } elseif ($progress_percentage > 0) {
        $course_status = 'in_progress';
    }

    echo json_encode([
        'course_id' => (int)$course_id,
        'course_title' => $course['course_title'],
        'total_lessons' => (int)$course['total_lessons'],
        'total_assignments' => (int)$course['total_assignments'],
        'completed_lessons' => (int)$completed_lessons,
        'submitted_assignments' => (int)$submitted_assignments,
        'progress_percentage' => $progress_percentage,
        'course_status' => $course_status,
        'enrollment_status' => $enrollment['payment_status'],
        'is_completed' => $progress_percentage >= 100
    ]);
}

function getAllProgress($student_id)
{
    global $conn;

    // Get all enrolled courses with progress
    $progress_query = "
        SELECT 
            c.course_id,
            c.course_title,
            c.description,
            c.price,
            e.payment_status,
            e.enrolled_at,
            COUNT(DISTINCT cl.lesson_id) as total_lessons,
            COUNT(DISTINCT a.assignment_id) as total_assignments
        FROM enrollments e
        JOIN courses c ON e.course_id = c.course_id
        LEFT JOIN course_lessons cl ON c.course_id = cl.course_id
        LEFT JOIN assignments a ON c.course_id = a.course_id
        WHERE e.student_id = ?
        GROUP BY c.course_id
        ORDER BY e.enrolled_at DESC
    ";

    $stmt = $conn->prepare($progress_query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $courses_progress = [];
    while ($row = $result->fetch_assoc()) {
        $course_id = $row['course_id'];

        // Get completed lessons for this course
        $completed_lessons_query = "
            SELECT COUNT(*) as completed_count
            FROM study_sessions ss
            WHERE ss.user_id = ? AND ss.course_id = ? AND ss.session_duration >= 15
        ";

        $stmt2 = $conn->prepare($completed_lessons_query);
        $stmt2->bind_param("ii", $_SESSION['user_id'], $course_id);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $completed_lessons = $result2->fetch_assoc()['completed_count'];

        // Get submitted assignments for this course
        $submitted_assignments_query = "
            SELECT COUNT(*) as submitted_count
            FROM submissions s
            JOIN assignments a ON s.assignment_id = a.assignment_id
            WHERE s.student_id = ? AND a.course_id = ?
        ";

        $stmt3 = $conn->prepare($submitted_assignments_query);
        $stmt3->bind_param("ii", $student_id, $course_id);
        $stmt3->execute();
        $result3 = $stmt3->get_result();
        $submitted_assignments = $result3->fetch_assoc()['submitted_count'];

        // Calculate progress percentage
        $total_activities = $row['total_lessons'] + $row['total_assignments'];
        $completed_activities = $completed_lessons + $submitted_assignments;
        $progress_percentage = $total_activities > 0 ? round(($completed_activities / $total_activities) * 100) : 0;

        // Determine course status
        $course_status = 'enrolled';
        if ($progress_percentage >= 100) {
            $course_status = 'completed';
        } elseif ($progress_percentage > 0) {
            $course_status = 'in_progress';
        }

        $courses_progress[] = [
            'course_id' => (int)$course_id,
            'course_title' => $row['course_title'],
            'description' => $row['description'],
            'price' => (float)$row['price'],
            'total_lessons' => (int)$row['total_lessons'],
            'total_assignments' => (int)$row['total_assignments'],
            'completed_lessons' => (int)$completed_lessons,
            'submitted_assignments' => (int)$submitted_assignments,
            'progress_percentage' => $progress_percentage,
            'course_status' => $course_status,
            'enrollment_status' => $row['payment_status'],
            'enrolled_at' => $row['enrolled_at'],
            'is_completed' => $progress_percentage >= 100
        ];
    }

    echo json_encode(['courses' => $courses_progress]);
}

function handleUpdateProgress()
{
    global $conn;

    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['course_id']) || !isset($input['activity_type'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Course ID and activity type are required']);
        return;
    }

    $user_id = $_SESSION['user_id'];
    $course_id = (int)$input['course_id'];
    $activity_type = $input['activity_type']; // 'lesson_completed', 'assignment_submitted', 'study_session'
    $duration = isset($input['duration']) ? (int)$input['duration'] : 0;

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

    // Check if student is enrolled in the course
    $enrollment_check = "SELECT enrollment_id FROM enrollments WHERE student_id = ? AND course_id = ?";
    $stmt = $conn->prepare($enrollment_check);
    $stmt->bind_param("ii", $student_id, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Not enrolled in this course']);
        return;
    }

    // Record study session
    if ($activity_type === 'study_session' && $duration > 0) {
        $session_query = "INSERT INTO study_sessions (user_id, course_id, session_duration, xp_earned, session_date) VALUES (?, ?, ?, ?, CURDATE())";
        $xp_earned = min($duration / 15 * 15, 120); // Max 120 XP per day for study sessions
        $stmt = $conn->prepare($session_query);
        $stmt->bind_param("iiii", $user_id, $course_id, $duration, $xp_earned);

        if ($stmt->execute()) {
            // Award XP
            awardXP($user_id, 'study_session_15min', $xp_earned, "Study session: {$duration} minutes");

            echo json_encode([
                'success' => true,
                'message' => 'Study session recorded',
                'xp_earned' => $xp_earned
            ]);
        } else {
            throw new Exception('Failed to record study session');
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid activity type or duration']);
    }
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
        // Log error but don't fail the progress update
        error_log("XP Award Error: " . $e->getMessage());
    }
}
