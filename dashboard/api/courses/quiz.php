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
        handleGetQuizQuestions();
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

function handleGetQuizQuestions()
{
    global $conn;

    $lesson_id = isset($_GET['lesson_id']) ? (int)$_GET['lesson_id'] : 0;
    if ($lesson_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid lesson ID']);
        return;
    }

    // Verify lesson ownership through course
    $owner_check = $conn->prepare("
        SELECT c.teacher_id 
        FROM course_lessons l 
        JOIN courses c ON l.course_id = c.course_id 
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

    // Get quiz questions with answer options
    $stmt = $conn->prepare("
        SELECT 
            q.question_id,
            q.question_text,
            q.question_type,
            q.points,
            q.question_order,
            q.created_at
        FROM quiz_questions q
        WHERE q.lesson_id = ?
        ORDER BY q.question_order ASC, q.created_at ASC
    ");

    $stmt->bind_param('i', $lesson_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $questions = [];
    while ($row = $result->fetch_assoc()) {
        $question = [
            'question_id' => (int)$row['question_id'],
            'question_text' => $row['question_text'],
            'question_type' => $row['question_type'],
            'points' => (int)$row['points'],
            'question_order' => (int)$row['question_order'],
            'created_at' => $row['created_at'],
            'options' => []
        ];

        // Get answer options for this question
        $options_stmt = $conn->prepare("
            SELECT option_id, option_text, is_correct, option_order
            FROM quiz_answer_options 
            WHERE question_id = ? 
            ORDER BY option_order ASC
        ");
        $options_stmt->bind_param('i', $row['question_id']);
        $options_stmt->execute();
        $options_result = $options_stmt->get_result();

        while ($option_row = $options_result->fetch_assoc()) {
            $question['options'][] = [
                'option_id' => (int)$option_row['option_id'],
                'option_text' => $option_row['option_text'],
                'is_correct' => (bool)$option_row['is_correct'],
                'option_order' => (int)$option_row['option_order']
            ];
        }

        $questions[] = $question;
    }

    echo json_encode(['questions' => $questions]);
}

function handleCreateQuestion()
{
    global $conn;

    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['lesson_id']) || !isset($input['question_text'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }

    $lesson_id = (int)$input['lesson_id'];
    $question_text = trim($input['question_text']);
    $question_type = isset($input['question_type']) ? $input['question_type'] : 'multiple_choice';
    $points = isset($input['points']) ? (int)$input['points'] : 1;
    $options = isset($input['options']) ? $input['options'] : [];

    if (empty($question_text)) {
        http_response_code(400);
        echo json_encode(['error' => 'Question text is required']);
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

    // Get next question order
    $order_stmt = $conn->prepare("SELECT COALESCE(MAX(question_order), 0) + 1 as next_order FROM quiz_questions WHERE lesson_id = ?");
    $order_stmt->bind_param('i', $lesson_id);
    $order_stmt->execute();
    $order_result = $order_stmt->get_result();
    $next_order = $order_result->fetch_assoc()['next_order'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert question
        $stmt = $conn->prepare("
            INSERT INTO quiz_questions (lesson_id, question_text, question_type, points, question_order)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param('issii', $lesson_id, $question_text, $question_type, $points, $next_order);
        $stmt->execute();
        $question_id = $conn->insert_id;

        // Insert answer options
        if (!empty($options)) {
            $option_stmt = $conn->prepare("
                INSERT INTO quiz_answer_options (question_id, option_text, is_correct, option_order)
                VALUES (?, ?, ?, ?)
            ");

            foreach ($options as $index => $option) {
                $option_text = trim($option['option_text']);
                $is_correct = isset($option['is_correct']) ? (bool)$option['is_correct'] : false;
                $option_order = $index + 1;

                if (!empty($option_text)) {
                    $option_stmt->bind_param('isii', $question_id, $option_text, $is_correct ? 1 : 0, $option_order);
                    $option_stmt->execute();
                }
            }
        }

        $conn->commit();

        echo json_encode([
            'success' => true,
            'question_id' => $question_id,
            'message' => 'Question created successfully'
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create question']);
    }
}

function handleUpdateQuestion()
{
    global $conn;

    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['question_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Question ID is required']);
        return;
    }

    $question_id = (int)$input['question_id'];

    // Verify question ownership
    $owner_check = $conn->prepare("
        SELECT c.teacher_id 
        FROM quiz_questions q
        JOIN course_lessons l ON q.lesson_id = l.lesson_id
        JOIN courses c ON l.course_id = c.course_id 
        WHERE q.question_id = ?
    ");
    $owner_check->bind_param('i', $question_id);
    $owner_check->execute();
    $owner_result = $owner_check->get_result();

    if ($owner_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Question not found']);
        return;
    }

    $question_data = $owner_result->fetch_assoc();
    if ($question_data['teacher_id'] != $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        return;
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update question if fields provided
        $updates = [];
        $params = [];
        $types = '';

        if (isset($input['question_text'])) {
            $updates[] = 'question_text = ?';
            $params[] = trim($input['question_text']);
            $types .= 's';
        }

        if (isset($input['question_type'])) {
            $updates[] = 'question_type = ?';
            $params[] = $input['question_type'];
            $types .= 's';
        }

        if (isset($input['points'])) {
            $updates[] = 'points = ?';
            $params[] = (int)$input['points'];
            $types .= 'i';
        }

        if (!empty($updates)) {
            $params[] = $question_id;
            $types .= 'i';

            $sql = "UPDATE quiz_questions SET " . implode(', ', $updates) . " WHERE question_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
        }

        // Update options if provided
        if (isset($input['options'])) {
            // Delete existing options
            $delete_stmt = $conn->prepare("DELETE FROM quiz_answer_options WHERE question_id = ?");
            $delete_stmt->bind_param('i', $question_id);
            $delete_stmt->execute();

            // Insert new options
            $option_stmt = $conn->prepare("
                INSERT INTO quiz_answer_options (question_id, option_text, is_correct, option_order)
                VALUES (?, ?, ?, ?)
            ");

            foreach ($input['options'] as $index => $option) {
                $option_text = trim($option['text']);
                $is_correct = isset($option['is_correct']) ? (bool)$option['is_correct'] : false;
                $option_order = $index + 1;

                if (!empty($option_text)) {
                    $option_stmt->bind_param('isii', $question_id, $option_text, $is_correct ? 1 : 0, $option_order);
                    $option_stmt->execute();
                }
            }
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Question updated successfully']);
    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update question']);
    }
}

function handleDeleteQuestion()
{
    global $conn;

    $question_id = isset($_GET['question_id']) ? (int)$_GET['question_id'] : 0;

    if ($question_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid question ID']);
        return;
    }

    // Verify question ownership
    $owner_check = $conn->prepare("
        SELECT c.teacher_id 
        FROM quiz_questions q
        JOIN course_lessons l ON q.lesson_id = l.lesson_id
        JOIN courses c ON l.course_id = c.course_id 
        WHERE q.question_id = ?
    ");
    $owner_check->bind_param('i', $question_id);
    $owner_check->execute();
    $owner_result = $owner_check->get_result();

    if ($owner_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Question not found']);
        return;
    }

    $question_data = $owner_result->fetch_assoc();
    if ($question_data['teacher_id'] != $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        return;
    }

    // Delete question (cascade will handle options)
    $stmt = $conn->prepare("DELETE FROM quiz_questions WHERE question_id = ?");
    $stmt->bind_param('i', $question_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Question deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete question']);
    }
}
