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
            handleGetCertificates();
            break;
        case 'POST':
            handleGenerateCertificate();
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

function handleGetCertificates()
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
        // Get certificate for specific course
        getCourseCertificate($student_id, $course_id);
    } else {
        // Get all certificates for the student
        getAllCertificates($student_id);
    }
}

function getCourseCertificate($student_id, $course_id)
{
    global $conn;

    // Check if student is enrolled and has completed the course
    $enrollment_query = "
        SELECT e.enrollment_id, e.payment_status, e.enrolled_at
        FROM enrollments e
        WHERE e.student_id = ? AND e.course_id = ?
    ";

    $stmt = $conn->prepare($enrollment_query);
    $stmt->bind_param("ii", $student_id, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Not enrolled in this course']);
        return;
    }

    $enrollment = $result->fetch_assoc();

    // Check if course is completed
    $completion_status = checkCourseCompletion($student_id, $course_id);

    if (!$completion_status['is_completed']) {
        http_response_code(400);
        echo json_encode([
            'error' => 'Course not completed',
            'completion_status' => $completion_status
        ]);
        return;
    }

    // Check if certificate already exists
    $certificate_query = "
        SELECT c.certificate_id, c.certificate_url, c.issued_at
        FROM certificates c
        WHERE c.student_id = ? AND c.course_id = ?
    ";

    $stmt = $conn->prepare($certificate_query);
    $stmt->bind_param("ii", $student_id, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Certificate already exists
        $certificate = $result->fetch_assoc();
        echo json_encode([
            'certificate_id' => (int)$certificate['certificate_id'],
            'certificate_url' => $certificate['certificate_url'],
            'issued_at' => $certificate['issued_at'],
            'already_issued' => true
        ]);
    } else {
        // Generate new certificate
        generateCertificate($student_id, $course_id);
    }
}

function getAllCertificates($student_id)
{
    global $conn;

    // Get all certificates for the student
    $certificates_query = "
        SELECT 
            c.certificate_id,
            c.course_id,
            c.certificate_url,
            c.issued_at,
            co.course_title,
            co.description,
            co.category,
            co.level,
            u.first_name,
            u.last_name
        FROM certificates c
        JOIN courses co ON c.course_id = co.course_id
        JOIN users u ON co.teacher_id = u.user_id
        WHERE c.student_id = ?
        ORDER BY c.issued_at DESC
    ";

    $stmt = $conn->prepare($certificates_query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $certificates = [];
    while ($row = $result->fetch_assoc()) {
        $certificates[] = [
            'certificate_id' => (int)$row['certificate_id'],
            'course_id' => (int)$row['course_id'],
            'course_title' => $row['course_title'],
            'description' => $row['description'],
            'category' => $row['category'],
            'level' => $row['level'],
            'teacher_name' => $row['first_name'] . ' ' . $row['last_name'],
            'certificate_url' => $row['certificate_url'],
            'issued_at' => $row['issued_at']
        ];
    }

    echo json_encode(['certificates' => $certificates]);
}

function handleGenerateCertificate()
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

    // Check if course is completed
    $completion_status = checkCourseCompletion($student_id, $course_id);

    if (!$completion_status['is_completed']) {
        http_response_code(400);
        echo json_encode([
            'error' => 'Course not completed',
            'completion_status' => $completion_status
        ]);
        return;
    }

    // Generate certificate
    generateCertificate($student_id, $course_id);
}

function checkCourseCompletion($student_id, $course_id)
{
    global $conn;

    // Get course details
    $course_query = "
        SELECT 
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
        return ['is_completed' => false, 'error' => 'Course not found'];
    }

    $course = $result->fetch_assoc();

    // Get completed lessons
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

    // Calculate completion percentage
    $total_activities = $course['total_lessons'] + $course['total_assignments'];
    $completed_activities = $completed_lessons + $submitted_assignments;
    $completion_percentage = $total_activities > 0 ? round(($completed_activities / $total_activities) * 100) : 0;

    $is_completed = $completion_percentage >= 100;

    return [
        'is_completed' => $is_completed,
        'completion_percentage' => $completion_percentage,
        'total_lessons' => (int)$course['total_lessons'],
        'total_assignments' => (int)$course['total_assignments'],
        'completed_lessons' => (int)$completed_lessons,
        'submitted_assignments' => (int)$submitted_assignments,
        'total_activities' => $total_activities,
        'completed_activities' => $completed_activities
    ];
}

function generateCertificate($student_id, $course_id)
{
    global $conn;

    // Get course and student details
    $details_query = "
        SELECT 
            co.course_title,
            co.description,
            co.category,
            co.level,
            u.first_name,
            u.last_name,
            st.institution
        FROM courses co
        JOIN users u ON co.teacher_id = u.user_id
        JOIN students st ON st.student_id = ?
        WHERE co.course_id = ?
    ";

    $stmt = $conn->prepare($details_query);
    $stmt->bind_param("ii", $student_id, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Course or student not found');
    }

    $details = $result->fetch_assoc();

    // Generate certificate URL (in a real system, you'd generate an actual certificate PDF)
    $certificate_url = "certificates/certificate_" . $student_id . "_" . $course_id . "_" . time() . ".pdf";

    // Insert certificate record
    $certificate_query = "INSERT INTO certificates (student_id, course_id, certificate_url) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($certificate_query);
    $stmt->bind_param("iis", $student_id, $course_id, $certificate_url);

    if ($stmt->execute()) {
        $certificate_id = $conn->insert_id;

        // Award XP for course completion
        awardXP($_SESSION['user_id'], 'course_completion', 200, 'Completed course: ' . $details['course_title']);

        echo json_encode([
            'success' => true,
            'certificate_id' => $certificate_id,
            'certificate_url' => $certificate_url,
            'course_title' => $details['course_title'],
            'issued_at' => date('Y-m-d H:i:s'),
            'message' => 'Certificate generated successfully'
        ]);
    } else {
        throw new Exception('Failed to generate certificate');
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
        // Log error but don't fail the certificate generation
        error_log("XP Award Error: " . $e->getMessage());
    }
}
