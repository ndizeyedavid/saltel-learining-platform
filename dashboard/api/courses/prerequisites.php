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
        handleGetPrerequisites();
        break;
    case 'POST':
        handleCreatePrerequisite();
        break;
    case 'DELETE':
        handleDeletePrerequisite();
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function handleGetPrerequisites()
{
    global $conn;

    $lesson_id = isset($_GET['lesson_id']) ? (int)$_GET['lesson_id'] : 0;
    if ($lesson_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid lesson ID']);
        return;
    }

    // Verify lesson ownership
    $owner_check = $conn->prepare("
        SELECT c.teacher_id 
        FROM course_lessons l
        JOIN course_modules m ON l.module_id = m.module_id
        JOIN courses c ON m.course_id = c.course_id 
        WHERE l.lesson_id = ?
    ");
    $owner_check->bind_param('i', $lesson_id);
    $owner_check->execute();
    $owner_result = $owner_check->get_result();

    if ($owner_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Lesson not found']);
        return;
    }

    $lesson_data = $owner_result->fetch_assoc();
    if ($lesson_data['teacher_id'] != $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        return;
    }

    // Get prerequisites for the lesson
    $stmt = $conn->prepare("
        SELECT 
            prerequisite_id,
            prerequisite_type,
            prerequisite_value,
            required_score,
            created_at
        FROM course_prerequisites
        WHERE lesson_id = ?
        ORDER BY created_at ASC
    ");

    $stmt->bind_param('i', $lesson_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $prerequisites = [];
    while ($row = $result->fetch_assoc()) {
        $prerequisite = [
            'prerequisite_id' => (int)$row['prerequisite_id'],
            'prerequisite_type' => $row['prerequisite_type'],
            'prerequisite_value' => $row['prerequisite_value'],
            'required_score' => $row['required_score'] ? (int)$row['required_score'] : null,
            'created_at' => $row['created_at']
        ];

        // Add human-readable description
        $prerequisite['description'] = getPrerequisiteDescription($prerequisite, $conn);
        $prerequisites[] = $prerequisite;
    }

    echo json_encode(['prerequisites' => $prerequisites]);
}

function handleCreatePrerequisite()
{
    global $conn;

    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['lesson_id']) || !isset($input['prerequisite_type'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }

    $lesson_id = (int)$input['lesson_id'];
    $prerequisite_type = $input['prerequisite_type'];
    $prerequisite_value = isset($input['prerequisite_value']) ? $input['prerequisite_value'] : null;
    $required_score = isset($input['required_score']) ? (int)$input['required_score'] : null;

    // Validate prerequisite type
    $valid_types = ['module_completion', 'lesson_completion', 'quiz_score', 'assignment_submission'];
    if (!in_array($prerequisite_type, $valid_types)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid prerequisite type']);
        return;
    }

    // Verify lesson ownership
    $owner_check = $conn->prepare("
        SELECT c.teacher_id 
        FROM course_lessons l
        JOIN course_modules m ON l.module_id = m.module_id
        JOIN courses c ON m.course_id = c.course_id 
        WHERE l.lesson_id = ?
    ");
    $owner_check->bind_param('i', $lesson_id);
    $owner_check->execute();
    $owner_result = $owner_check->get_result();

    if ($owner_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Lesson not found']);
        return;
    }

    $lesson_data = $owner_result->fetch_assoc();
    if ($lesson_data['teacher_id'] != $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        return;
    }

    try {
        // Insert prerequisite
        $stmt = $conn->prepare("
            INSERT INTO course_prerequisites (lesson_id, prerequisite_type, prerequisite_value, required_score)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param('issi', $lesson_id, $prerequisite_type, $prerequisite_value, $required_score);
        $stmt->execute();
        $prerequisite_id = $conn->insert_id;

        echo json_encode([
            'success' => true,
            'prerequisite_id' => $prerequisite_id,
            'message' => 'Prerequisite created successfully'
        ]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create prerequisite']);
    }
}

function handleDeletePrerequisite()
{
    global $conn;

    $prerequisite_id = isset($_GET['prerequisite_id']) ? (int)$_GET['prerequisite_id'] : 0;

    if ($prerequisite_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid prerequisite ID']);
        return;
    }

    // Verify prerequisite ownership
    $owner_check = $conn->prepare("
        SELECT c.teacher_id 
        FROM course_prerequisites p
        JOIN course_lessons l ON p.lesson_id = l.lesson_id
        JOIN course_modules m ON l.module_id = m.module_id
        JOIN courses c ON m.course_id = c.course_id 
        WHERE p.prerequisite_id = ?
    ");
    $owner_check->bind_param('i', $prerequisite_id);
    $owner_check->execute();
    $owner_result = $owner_check->get_result();

    if ($owner_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Prerequisite not found']);
        return;
    }

    $prerequisite_data = $owner_result->fetch_assoc();
    if ($prerequisite_data['teacher_id'] != $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        return;
    }

    // Delete prerequisite
    $stmt = $conn->prepare("DELETE FROM course_prerequisites WHERE prerequisite_id = ?");
    $stmt->bind_param('i', $prerequisite_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Prerequisite deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete prerequisite']);
    }
}

function getPrerequisiteDescription($prerequisite, $conn)
{
    $type = $prerequisite['prerequisite_type'];
    $value = $prerequisite['prerequisite_value'];
    $score = $prerequisite['required_score'];

    switch ($type) {
        case 'module_completion':
            if ($value) {
                $stmt = $conn->prepare("SELECT title FROM course_modules WHERE module_id = ?");
                $stmt->bind_param('i', $value);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc()) {
                    return "Complete module: " . $row['title'];
                }
            }
            return "Complete a specific module";

        case 'lesson_completion':
            if ($value) {
                $stmt = $conn->prepare("SELECT title FROM course_lessons WHERE lesson_id = ?");
                $stmt->bind_param('i', $value);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc()) {
                    return "Complete lesson: " . $row['title'];
                }
            }
            return "Complete a specific lesson";

        case 'quiz_score':
            $scoreText = $score ? $score . "%" : "passing score";
            if ($value) {
                $stmt = $conn->prepare("SELECT title FROM course_lessons WHERE lesson_id = ?");
                $stmt->bind_param('i', $value);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc()) {
                    return "Score " . $scoreText . " on quiz in: " . $row['title'];
                }
            }
            return "Achieve " . $scoreText . " on a quiz";

        case 'assignment_submission':
            if ($value) {
                $stmt = $conn->prepare("SELECT title FROM assignments WHERE assignment_id = ?");
                $stmt->bind_param('i', $value);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc()) {
                    return "Submit assignment: " . $row['title'];
                }
            }
            return "Submit a specific assignment";

        default:
            return "Unknown prerequisite";
    }
}
