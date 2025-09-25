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
            handleGetQuestions();
            break;
        case 'POST':
            handleCreateQuestion();
            break;
        case 'PUT':
            handleUpdateQuestion();
            break;
        case 'DELETE':
            handleDeleteQuestion();
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

function handleGetQuestions() {
    global $conn;
    
    if (!isset($_GET['assignment_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Assignment ID is required']);
        return;
    }
    
    $assignment_id = intval($_GET['assignment_id']);
    $user_id = $_SESSION['user_id'];
    
    // Verify assignment ownership
    $stmt = $conn->prepare("SELECT a.assignment_id FROM assignments a 
                           JOIN courses c ON a.course_id = c.course_id 
                           WHERE a.assignment_id = ? AND c.teacher_id = ?");
    $stmt->bind_param("ii", $assignment_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Assignment not found or access denied']);
        return;
    }
    
    // Get questions with their options
    $stmt = $conn->prepare("SELECT q.question_id, q.assignment_id, q.question_text, 
                           q.points, q.explanation, q.sort_order,
                           o.option_id, o.option_text, o.is_correct, o.sort_order as option_sort_order
                           FROM assignment_questions q
                           LEFT JOIN assignment_options o ON q.question_id = o.question_id
                           WHERE q.assignment_id = ?
                           ORDER BY q.sort_order ASC, o.sort_order ASC");
    $stmt->bind_param("i", $assignment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $questions = [];
    $current_question = null;
    
    while ($row = $result->fetch_assoc()) {
        if ($current_question === null || $current_question['question_id'] !== $row['question_id']) {
            if ($current_question !== null) {
                $questions[] = $current_question;
            }
            
            $current_question = [
                'question_id' => $row['question_id'],
                'assignment_id' => $row['assignment_id'],
                'question_text' => $row['question_text'],
                'points' => $row['points'],
                'explanation' => $row['explanation'],
                'sort_order' => $row['sort_order'],
                'options' => []
            ];
        }
        
        if ($row['option_id']) {
            $current_question['options'][] = [
                'option_id' => $row['option_id'],
                'option_text' => $row['option_text'],
                'is_correct' => (bool)$row['is_correct'],
                'sort_order' => $row['option_sort_order']
            ];
        }
    }
    
    if ($current_question !== null) {
        $questions[] = $current_question;
    }
    
    echo json_encode(['questions' => $questions]);
}

function handleCreateQuestion() {
    global $conn, $input;
    
    if (!isset($input['assignment_id']) || !isset($input['question_text'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Assignment ID and question text are required']);
        return;
    }
    
    $assignment_id = intval($input['assignment_id']);
    $question_text = $input['question_text'];
    $points = isset($input['points']) ? intval($input['points']) : 1;
    $explanation = isset($input['explanation']) ? $input['explanation'] : null;
    $sort_order = isset($input['sort_order']) ? intval($input['sort_order']) : 0;
    $user_id = $_SESSION['user_id'];
    
    // Verify assignment ownership
    $stmt = $conn->prepare("SELECT a.assignment_id FROM assignments a 
                           JOIN courses c ON a.course_id = c.course_id 
                           WHERE a.assignment_id = ? AND c.teacher_id = ?");
    $stmt->bind_param("ii", $assignment_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Assignment not found or access denied']);
        return;
    }
    
    // If no sort_order provided, get the next available order
    if ($sort_order === 0) {
        $stmt = $conn->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 as next_order FROM assignment_questions WHERE assignment_id = ?");
        $stmt->bind_param("i", $assignment_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $sort_order = $row['next_order'];
    }
    
    // Insert question
    $stmt = $conn->prepare("INSERT INTO assignment_questions (assignment_id, question_text, points, explanation, sort_order) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isisi", $assignment_id, $question_text, $points, $explanation, $sort_order);
    
    if ($stmt->execute()) {
        $question_id = $conn->insert_id;
        
        // Insert options if provided
        if (isset($input['options']) && is_array($input['options'])) {
            foreach ($input['options'] as $index => $option) {
                if (isset($option['option_text'])) {
                    $option_text = $option['option_text'];
                    $is_correct = isset($option['is_correct']) ? (bool)$option['is_correct'] : false;
                    $option_sort_order = isset($option['sort_order']) ? intval($option['sort_order']) : $index;
                    
                    $stmt = $conn->prepare("INSERT INTO assignment_options (question_id, option_text, is_correct, sort_order) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("isii", $question_id, $option_text, $is_correct, $option_sort_order);
                    $stmt->execute();
                }
            }
        }
        
        echo json_encode([
            'success' => true,
            'question_id' => $question_id,
            'message' => 'Question created successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create question']);
    }
}

function handleUpdateQuestion() {
    global $conn, $input;
    
    if (!isset($input['question_id']) || !isset($input['question_text'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Question ID and question text are required']);
        return;
    }
    
    $question_id = intval($input['question_id']);
    $question_text = $input['question_text'];
    $points = isset($input['points']) ? intval($input['points']) : 1;
    $explanation = isset($input['explanation']) ? $input['explanation'] : null;
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
    
    // Update question
    $stmt = $conn->prepare("UPDATE assignment_questions SET question_text = ?, points = ?, explanation = ?, sort_order = ? WHERE question_id = ?");
    $stmt->bind_param("sisii", $question_text, $points, $explanation, $sort_order, $question_id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Question updated successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update question']);
    }
}

function handleDeleteQuestion() {
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
    
    // Delete options first (foreign key constraint)
    $stmt = $conn->prepare("DELETE FROM assignment_options WHERE question_id = ?");
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    
    // Delete question
    $stmt = $conn->prepare("DELETE FROM assignment_questions WHERE question_id = ?");
    $stmt->bind_param("i", $question_id);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Question deleted successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete question']);
    }
}
?>
