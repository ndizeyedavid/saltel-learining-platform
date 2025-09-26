<?php
// WebSocket signaling server for WebRTC peer connections
require_once '../php/connect.php';

// Simple signaling server using Server-Sent Events (SSE) and POST endpoints
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Create signaling_messages table if not exists
$create_table = "CREATE TABLE IF NOT EXISTS signaling_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id VARCHAR(50) NOT NULL,
    from_user_id INT NOT NULL,
    to_user_id INT NULL,
    message_type VARCHAR(50) NOT NULL,
    message_data JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_room_user (room_id, to_user_id)
)";
$conn->query($create_table);

switch ($method) {
    case 'POST':
        // Send signaling message
        if (!isset($input['room_id'], $input['type'], $input['data'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            exit();
        }

        $room_id = $input['room_id'];
        $message_type = $input['type'];
        $message_data = json_encode($input['data']);
        $from_user_id = $_SESSION['user_id'];
        $to_user_id = $input['to_user_id'] ?? null;

        $stmt = $conn->prepare("INSERT INTO signaling_messages (room_id, from_user_id, to_user_id, message_type, message_data) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siiss", $room_id, $from_user_id, $to_user_id, $message_type, $message_data);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to send message']);
        }
        break;

    case 'GET':
        // Get signaling messages for user
        $room_id = $_GET['room_id'] ?? '';
        $last_id = $_GET['last_id'] ?? 0;
        $user_id = $_SESSION['user_id'];

        if (!$room_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Room ID required']);
            exit();
        }

        // Get messages for this user in this room
        $stmt = $conn->prepare("
            SELECT id, from_user_id, message_type, message_data, created_at 
            FROM signaling_messages 
            WHERE room_id = ? AND id > ? AND (to_user_id = ? OR to_user_id IS NULL) AND from_user_id != ?
            ORDER BY id ASC
        ");
        $stmt->bind_param("siii", $room_id, $last_id, $user_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = [
                'id' => $row['id'],
                'from_user_id' => $row['from_user_id'],
                'type' => $row['message_type'],
                'data' => json_decode($row['message_data'], true),
                'timestamp' => $row['created_at']
            ];
        }

        echo json_encode(['messages' => $messages]);
        break;

    case 'DELETE':
        // Clean up old messages (optional)
        $room_id = $input['room_id'] ?? '';
        if ($room_id) {
            $stmt = $conn->prepare("DELETE FROM signaling_messages WHERE room_id = ? AND created_at < DATE_SUB(NOW(), INTERVAL 1 HOUR)");
            $stmt->bind_param("s", $room_id);
            $stmt->execute();
            echo json_encode(['success' => true]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
?>
