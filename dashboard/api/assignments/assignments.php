<?php
// Prevent any output before JSON
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require_once '../../../include/connect.php';
require_once '../../../include/trainer-guard.php';

// Clear any output that might have been generated
ob_clean();
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGetAssignments();
        break;
    case 'POST':
        handleCreateAssignment();
        break;
    case 'PUT':
        handleUpdateAssignment();
        break;
    case 'DELETE':
        handleDeleteAssignment();
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function handleGetAssignments()
{
    global $conn;

    $teacher_id = $_SESSION['user_id'];
    $course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : null;
    $assignment_type = isset($_GET['type']) ? $_GET['type'] : null;

    // Build the query - using only existing columns
    $sql = "
        SELECT 
            a.assignment_id,
            a.title,
            a.description,
            a.due_date,
            c.course_title,
            c.course_id,
            COUNT(s.submission_id) as submission_count,
            COUNT(CASE WHEN s.grade IS NOT NULL THEN 1 END) as graded_count
        FROM assignments a
        JOIN courses c ON a.course_id = c.course_id
        LEFT JOIN submissions s ON a.assignment_id = s.assignment_id
        WHERE c.teacher_id = ?
    ";

    $params = [$teacher_id];
    $types = 'i';

    // Add course filter if specified
    if ($course_id) {
        $sql .= " AND a.course_id = ?";
        $params[] = $course_id;
        $types .= 'i';
    }

    // Skip type filter since assignment_type column doesn't exist yet

    $sql .= " GROUP BY a.assignment_id ORDER BY a.assignment_id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $assignments = [];
    while ($row = $result->fetch_assoc()) {
        $assignment = [
            'assignment_id' => (int)$row['assignment_id'],
            'title' => $row['title'],
            'description' => $row['description'],
            'assignment_type' => 'essay', // Default type since column doesn't exist yet
            'due_date' => $row['due_date'],
            'max_points' => 100, // Default points
            'time_limit' => null, // Default no time limit
            'instructions' => $row['description'], // Use description as instructions for now
            'status' => 'published', // Default status
            'created_at' => date('Y-m-d H:i:s'), // Default created time
            'course_title' => $row['course_title'],
            'course_id' => (int)$row['course_id'],
            'submission_count' => (int)$row['submission_count'],
            'graded_count' => (int)$row['graded_count'],
            'is_overdue' => $row['due_date'] && strtotime($row['due_date']) < time(),
            'days_until_due' => $row['due_date'] ? ceil((strtotime($row['due_date']) - time()) / (60 * 60 * 24)) : null
        ];

        $assignments[] = $assignment;
    }

    echo json_encode(['assignments' => $assignments]);
}

function handleCreateAssignment()
{
    global $conn;

    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['title']) || !isset($input['course_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }

    $teacher_id = $_SESSION['user_id'];
    $course_id = (int)$input['course_id'];
    $title = trim($input['title']);
    $description = isset($input['description']) ? trim($input['description']) : '';
    $due_date = isset($input['due_date']) ? $input['due_date'] : null;

    // Verify course ownership
    $owner_check = $conn->prepare("SELECT teacher_id FROM courses WHERE course_id = ?");
    $owner_check->bind_param('i', $course_id);
    $owner_check->execute();
    $owner_result = $owner_check->get_result();

    if ($owner_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Course not found']);
        return;
    }

    $course_data = $owner_result->fetch_assoc();
    if ($course_data['teacher_id'] != $teacher_id) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        return;
    }

    try {
        // Use only existing columns
        $stmt = $conn->prepare("
            INSERT INTO assignments (course_id, title, description, due_date)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param('isss', $course_id, $title, $description, $due_date);
        $stmt->execute();
        $assignment_id = $conn->insert_id;

        echo json_encode([
            'success' => true,
            'assignment_id' => $assignment_id,
            'message' => 'Assignment created successfully'
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create assignment']);
    }
}

function handleUpdateAssignment()
{
    global $conn;

    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['assignment_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Assignment ID is required']);
        return;
    }

    $assignment_id = (int)$input['assignment_id'];
    $teacher_id = $_SESSION['user_id'];

    // Verify assignment ownership
    $owner_check = $conn->prepare("
        SELECT a.assignment_id 
        FROM assignments a
        JOIN courses c ON a.course_id = c.course_id 
        WHERE a.assignment_id = ? AND c.teacher_id = ?
    ");
    $owner_check->bind_param('ii', $assignment_id, $teacher_id);
    $owner_check->execute();
    $owner_result = $owner_check->get_result();

    if ($owner_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Assignment not found']);
        return;
    }

    try {
        $updates = [];
        $params = [];
        $types = '';

        if (isset($input['title'])) {
            $updates[] = 'title = ?';
            $params[] = trim($input['title']);
            $types .= 's';
        }

        if (isset($input['description'])) {
            $updates[] = 'description = ?';
            $params[] = trim($input['description']);
            $types .= 's';
        }

        if (isset($input['due_date'])) {
            $updates[] = 'due_date = ?';
            $params[] = $input['due_date'];
            $types .= 's';
        }

        if (!empty($updates)) {
            $params[] = $assignment_id;
            $types .= 'i';

            $sql = "UPDATE assignments SET " . implode(', ', $updates) . " WHERE assignment_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
        }

        echo json_encode(['success' => true, 'message' => 'Assignment updated successfully']);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update assignment']);
    }
}

function handleDeleteAssignment()
{
    global $conn;

    $assignment_id = isset($_GET['assignment_id']) ? (int)$_GET['assignment_id'] : 0;

    if ($assignment_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid assignment ID']);
        return;
    }

    $teacher_id = $_SESSION['user_id'];

    // Verify assignment ownership
    $owner_check = $conn->prepare("
        SELECT a.assignment_id 
        FROM assignments a
        JOIN courses c ON a.course_id = c.course_id 
        WHERE a.assignment_id = ? AND c.teacher_id = ?
    ");
    $owner_check->bind_param('ii', $assignment_id, $teacher_id);
    $owner_check->execute();
    $owner_result = $owner_check->get_result();

    if ($owner_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Assignment not found']);
        return;
    }

    try {
        // Delete assignment (cascade will handle submissions)
        $stmt = $conn->prepare("DELETE FROM assignments WHERE assignment_id = ?");
        $stmt->bind_param('i', $assignment_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Assignment deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete assignment']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete assignment']);
    }
}
