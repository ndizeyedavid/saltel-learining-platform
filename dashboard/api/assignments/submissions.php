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
            handleGetSubmissions();
            break;
        case 'PUT':
            handleUpdateGrade();
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

function handleGetSubmissions() {
    global $conn;
    
    $user_id = $_SESSION['user_id'];
    $assignment_id = isset($_GET['assignment_id']) ? intval($_GET['assignment_id']) : null;
    $status_filter = isset($_GET['status']) ? $_GET['status'] : null;
    $search = isset($_GET['search']) ? $_GET['search'] : null;
    
    // Build the query
    $query = "SELECT 
                s.submission_id,
                s.assignment_id,
                s.student_id,
                s.file_url,
                s.submitted_at,
                s.grade,
                s.feedback,
                a.title as assignment_title,
                a.due_date,
                u.first_name,
                u.last_name,
                u.email,
                st.institution,
                c.course_title,
                CASE 
                    WHEN s.grade IS NOT NULL THEN 'graded'
                    WHEN s.submitted_at > a.due_date THEN 'late'
                    ELSE 'pending'
                END as status
              FROM submissions s
              JOIN assignments a ON s.assignment_id = a.assignment_id
              JOIN courses c ON a.course_id = c.course_id
              JOIN students st ON s.student_id = st.student_id
              JOIN users u ON st.user_id = u.user_id
              WHERE c.teacher_id = ?";
    
    $params = [$user_id];
    $types = "i";
    
    // Add filters
    if ($assignment_id) {
        $query .= " AND s.assignment_id = ?";
        $params[] = $assignment_id;
        $types .= "i";
    }
    
    if ($search) {
        $query .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ? OR a.title LIKE ?)";
        $searchParam = "%$search%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $params[] = $searchParam;
        $params[] = $searchParam;
        $types .= "ssss";
    }
    
    // Add status filter (applied after CASE statement)
    $havingClause = "";
    if ($status_filter && $status_filter !== 'all') {
        $havingClause = " HAVING status = ?";
        $params[] = $status_filter;
        $types .= "s";
    }
    
    $query .= $havingClause . " ORDER BY s.submitted_at DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $submissions = [];
    while ($row = $result->fetch_assoc()) {
        $submissions[] = [
            'submission_id' => $row['submission_id'],
            'assignment_id' => $row['assignment_id'],
            'assignment_title' => $row['assignment_title'],
            'course_title' => $row['course_title'],
            'student_id' => $row['student_id'],
            'student_name' => $row['first_name'] . ' ' . $row['last_name'],
            'student_email' => $row['email'],
            'institution' => $row['institution'],
            'file_url' => $row['file_url'],
            'submitted_at' => $row['submitted_at'],
            'due_date' => $row['due_date'],
            'grade' => $row['grade'],
            'feedback' => $row['feedback'],
            'status' => $row['status'],
            'is_late' => $row['due_date'] && $row['submitted_at'] > $row['due_date']
        ];
    }
    
    echo json_encode(['submissions' => $submissions]);
}

function handleUpdateGrade() {
    global $conn, $input;
    
    if (!isset($input['submission_id']) || !isset($input['grade'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Submission ID and grade are required']);
        return;
    }
    
    $submission_id = intval($input['submission_id']);
    $grade = floatval($input['grade']);
    $feedback = isset($input['feedback']) ? $input['feedback'] : null;
    $user_id = $_SESSION['user_id'];
    
    // Validate grade range
    if ($grade < 0 || $grade > 100) {
        http_response_code(400);
        echo json_encode(['error' => 'Grade must be between 0 and 100']);
        return;
    }
    
    // Verify submission ownership through assignment
    $stmt = $conn->prepare("SELECT s.submission_id 
                           FROM submissions s
                           JOIN assignments a ON s.assignment_id = a.assignment_id
                           JOIN courses c ON a.course_id = c.course_id 
                           WHERE s.submission_id = ? AND c.teacher_id = ?");
    $stmt->bind_param("ii", $submission_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Submission not found or access denied']);
        return;
    }
    
    // Update grade and feedback
    $stmt = $conn->prepare("UPDATE submissions SET grade = ?, feedback = ? WHERE submission_id = ?");
    $stmt->bind_param("dsi", $grade, $feedback, $submission_id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Grade updated successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update grade']);
    }
}
?>
