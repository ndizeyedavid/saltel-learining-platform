<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

require_once '../../php/connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['room_key']) || empty(trim($input['room_key']))) {
    http_response_code(400);
    echo json_encode(['error' => 'Room key is required']);
    exit();
}

$room_key = strtoupper(trim($input['room_key']));

try {
    $query = "SELECT room_id, room_name FROM conference_rooms WHERE room_key = ? AND is_active = TRUE";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $room_key);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $room = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'room_id' => $room['room_id'],
            'room_name' => $room['room_name']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Room not found'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
