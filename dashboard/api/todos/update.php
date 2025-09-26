<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

require_once '../../../include/connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['todo_id']) || !isset($input['is_completed'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}

$todo_id = (int)$input['todo_id'];
$is_completed = (bool)$input['is_completed'];
$student_id = $_SESSION['user_id'];

try {
    // Verify todo belongs to current user
    $verify_query = "SELECT todo_id FROM student_todos WHERE todo_id = ? AND student_id = ?";
    $verify_stmt = $conn->prepare($verify_query);
    $verify_stmt->bind_param("ii", $todo_id, $student_id);
    $verify_stmt->execute();

    if ($verify_stmt->get_result()->num_rows === 0) {
        http_response_code(403);
        echo json_encode(['error' => 'Todo not found or access denied']);
        exit();
    }

    // Update todo completion status
    $update_query = "UPDATE student_todos SET completed = ?, updated_at = NOW() WHERE todo_id = ? AND student_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("iii", $is_completed, $todo_id, $student_id);

    if ($update_stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Todo updated successfully']);
    } else {
        throw new Exception('Failed to update todo');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
