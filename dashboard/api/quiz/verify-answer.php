<?php
session_start();
require_once '../../../include/connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['question_id']) || !isset($input['selected_option_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

$question_id = (int)$input['question_id'];
$selected_option_id = (int)$input['selected_option_id'];

try {
    // Get the question details
    $question_query = "SELECT qq.question_text, qq.lesson_id, qq.points 
                      FROM quiz_questions qq 
                      WHERE qq.question_id = ?";
    $stmt = $conn->prepare($question_query);
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $question = $stmt->get_result()->fetch_assoc();

    if (!$question) {
        throw new Exception('Question not found');
    }

    // Get the selected option and check if it's correct
    $option_query = "SELECT option_text, is_correct 
                    FROM quiz_answer_options 
                    WHERE option_id = ? AND question_id = ?";
    $stmt = $conn->prepare($option_query);
    $stmt->bind_param("ii", $selected_option_id, $question_id);
    $stmt->execute();
    $selected_option = $stmt->get_result()->fetch_assoc();

    if (!$selected_option) {
        throw new Exception('Invalid option selected');
    }

    // Get the correct answer for feedback
    $correct_query = "SELECT option_text 
                     FROM quiz_answer_options 
                     WHERE question_id = ? AND is_correct = 1";
    $stmt = $conn->prepare($correct_query);
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $correct_answer = $stmt->get_result()->fetch_assoc();

    $is_correct = (bool)$selected_option['is_correct'];
    $points_earned = $is_correct ? $question['points'] : 0;

    // TODO: Store quiz attempt in database for tracking

    echo json_encode([
        'success' => true,
        'is_correct' => $is_correct,
        'points_earned' => $points_earned,
        'selected_answer' => $selected_option['option_text'],
        'correct_answer' => $correct_answer['option_text'],
        'feedback' => $is_correct
            ? 'Correct! Well done.'
            : 'Incorrect. The correct answer is: ' . $correct_answer['option_text']
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    error_log($e);
}
