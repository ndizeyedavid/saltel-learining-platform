<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

require_once '../php/connect.php';

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['room_key'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Room key is required']);
    exit();
}

$room_key = strtoupper(trim($input['room_key']));

// Validate room key format
if (strlen($room_key) !== 6) {
    echo json_encode(['success' => false, 'error' => 'Invalid room key format']);
    exit();
}

try {
    // Check if room exists and is active
    $query = "SELECT room_id, room_name, created_by FROM conference_rooms WHERE room_key = ? AND is_active = TRUE";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $room_key);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($room = $result->fetch_assoc()) {
        echo json_encode([
            'success' => true,
            'room_id' => $room['room_id'],
            'room_name' => $room['room_name'],
            'created_by' => $room['created_by']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Room not found or inactive'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
