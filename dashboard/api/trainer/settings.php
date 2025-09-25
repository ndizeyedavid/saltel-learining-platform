<?php
require_once __DIR__ . '/../_bootstrap.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'POST') {
        handleChangePassword();
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error']);
}

function handleChangePassword()
{
    global $conn;
    $user_id = $_SESSION['user_id'];

    $raw = file_get_contents('php://input');
    $body = json_decode($raw, true) ?: [];

    $current = (string)($body['current_password'] ?? '');
    $new = (string)($body['new_password'] ?? '');
    $confirm = (string)($body['confirm_password'] ?? '');

    if ($current === '' || $new === '' || $confirm === '') {
        http_response_code(400);
        echo json_encode(['error' => 'All password fields are required']);
        return;
    }
    if ($new !== $confirm) {
        http_response_code(400);
        echo json_encode(['error' => 'New passwords do not match']);
        return;
    }
    if (strlen($new) < 8) {
        http_response_code(400);
        echo json_encode(['error' => 'New password must be at least 8 characters']);
        return;
    }

    // Fetch current hash
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows !== 1) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        return;
    }
    $row = $res->fetch_assoc();
    $currentHash = (string)$row['password'];

    if (!password_verify($current, $currentHash)) {
        http_response_code(401);
        echo json_encode(['error' => 'Current password is incorrect']);
        return;
    }

    $newHash = password_hash($new, PASSWORD_BCRYPT);
    $stmt2 = $conn->prepare("UPDATE users SET password = ?, remember_token = NULL, remember_expires = NULL WHERE user_id = ?");
    $stmt2->bind_param("si", $newHash, $user_id);
    $ok = $stmt2->execute();
    if (!$ok) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update password']);
        return;
    }

    echo json_encode(['success' => ['message' => 'Password updated successfully']]);
}
