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
            handleGetOptions();
            break;
        case 'POST':
            handleCreateOption();
            break;
        case 'PUT':
            handleUpdateOption();
            break;
        case 'DELETE':
            handleDeleteOption();
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

function handleGetOptions() {
    global $conn;
    
    if (!isset($_GET['question_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Question ID is required']);
        return;
    }
    
    $question_id = intval($_GET['question_id']);
    $user_id = $_SESSION['user_id'];
    
    // Verify question ownership through assignment
    $stmt = $conn->prepare("SELECT q.question_id FROM assignment_questions q
                           JOIN assignments a ON q.assignment_id = a.assignment_id
                           JOIN courses c ON a.course_id = c.course_id 
                           WHERE q.question_id = ? AND c.teacher_id = ?");
    $stmt->bind_param("ii", $question_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Question not found or access denied']);
        return;
    }
    
    // Get options for the question
    $stmt = $conn->prepare("SELECT option_id, question_id, option_text, is_correct, sort_order 
                           FROM assignment_options 
                           WHERE question_id = ? 
                           ORDER BY sort_order ASC");
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $options = [];
    while ($row = $result->fetch_assoc()) {
        $options[] = [
            'option_id' => $row['option_id'],
            'question_id' => $row['question_id'],
            'option_text' => $row['option_text'],
            'is_correct' => (bool)$row['is_correct'],
            'sort_order' => $row['sort_order']
        ];
    }
    
    echo json_encode(['options' => $options]);
}

function handleCreateOption() {
    global $conn, $input;
    
    if (!isset($input['question_id']) || !isset($input['option_text'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Question ID and option text are required']);
        return;
    }
    
    $question_id = intval($input['question_id']);
    $option_text = $input['option_text'];
    $is_correct = isset($input['is_correct']) ? (bool)$input['is_correct'] : false;
    $sort_order = isset($input['sort_order']) ? intval($input['sort_order']) : 0;
    $user_id = $_SESSION['user_id'];
    
    // Verify question ownership through assignment
    $stmt = $conn->prepare("SELECT q.question_id FROM assignment_questions q
                           JOIN assignments a ON q.assignment_id = a.assignment_id
                           JOIN courses c ON a.course_id = c.course_id 
                           WHERE q.question_id = ? AND c.teacher_id = ?");
    $stmt->bind_param("ii", $question_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Question not found or access denied']);
        return;
    }
    
    // If no sort_order provided, get the next available order
    if ($sort_order === 0) {
        $stmt = $conn->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 as next_order FROM assignment_options WHERE question_id = ?");
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $sort_order = $row['next_order'];
    }
    
    // Insert option
    $stmt = $conn->prepare("INSERT INTO assignment_options (question_id, option_text, is_correct, sort_order) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isii", $question_id, $option_text, $is_correct, $sort_order);
    
    if ($stmt->execute()) {
        $option_id = $conn->insert_id;
        echo json_encode([
            'success' => true,
            'option_id' => $option_id,
            'message' => 'Option created successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create option']);
    }
}

function handleUpdateOption() {
    global $conn, $input;
    
    if (!isset($input['option_id']) || !isset($input['option_text'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Option ID and option text are required']);
        return;
    }
    
    $option_id = intval($input['option_id']);
    $option_text = $input['option_text'];
    $is_correct = isset($input['is_correct']) ? (bool)$input['is_correct'] : false;
    $sort_order = isset($input['sort_order']) ? intval($input['sort_order']) : 0;
    $user_id = $_SESSION['user_id'];
    
    // Verify option ownership through question and assignment
    $stmt = $conn->prepare("SELECT o.option_id FROM assignment_options o
                           JOIN assignment_questions q ON o.question_id = q.question_id
                           JOIN assignments a ON q.assignment_id = a.assignment_id
                           JOIN courses c ON a.course_id = c.course_id 
                           WHERE o.option_id = ? AND c.teacher_id = ?");
    $stmt->bind_param("ii", $option_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Option not found or access denied']);
        return;
    }
    
    // Update option
    $stmt = $conn->prepare("UPDATE assignment_options SET option_text = ?, is_correct = ?, sort_order = ? WHERE option_id = ?");
    $stmt->bind_param("siii", $option_text, $is_correct, $sort_order, $option_id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Option updated successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update option']);
    }
}

function handleDeleteOption() {
    global $conn;
    
    if (!isset($_GET['option_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Option ID is required']);
        return;
    }
    
    $option_id = intval($_GET['option_id']);
    $user_id = $_SESSION['user_id'];
    
    // Verify option ownership through question and assignment
    $stmt = $conn->prepare("SELECT o.option_id FROM assignment_options o
                           JOIN assignment_questions q ON o.question_id = q.question_id
                           JOIN assignments a ON q.assignment_id = a.assignment_id
                           JOIN courses c ON a.course_id = c.course_id 
                           WHERE o.option_id = ? AND c.teacher_id = ?");
    $stmt->bind_param("ii", $option_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Option not found or access denied']);
        return;
    }
    
    // Delete option
    $stmt = $conn->prepare("DELETE FROM assignment_options WHERE option_id = ?");
    $stmt->bind_param("i", $option_id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Option deleted successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete option']);
    }
}
?>
