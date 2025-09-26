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

if (!isset($input['title']) || empty(trim($input['title']))) {
    http_response_code(400);
    echo json_encode(['error' => 'Title is required']);
    exit();
}

$title = trim($input['title']);
$due_date = isset($input['due_date']) ? $input['due_date'] : null;
$student_id = $_SESSION['user_id'];

try {
    $insert_query = "INSERT INTO student_todos (student_id, title, due_date, completed, created_at) VALUES (?, ?, ?, 0, NOW())";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("iss", $student_id, $title, $due_date);

    if ($insert_stmt->execute()) {
        $todo_id = $conn->insert_id;
        echo json_encode([
            'success' => true,
            'message' => 'Todo created successfully',
            'todo_id' => $todo_id
        ]);
    } else {
        throw new Exception('Failed to create todo');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
