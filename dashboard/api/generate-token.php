<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

require_once '../../php/connect.php';

// Get user data
$student_id = $_SESSION['user_id'];
$student_query = "SELECT * FROM students WHERE user_id = ?";
$stmt = $conn->prepare($student_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    http_response_code(404);
    echo json_encode(['error' => 'User not found']);
    exit();
}

// Stream.io configuration
$api_key = 'r5a2utvt3tm2'; // Replace with your actual API key
$api_secret = 'yx8rshwrd7h8w4xj6x7sca9fsce2ksrdx87crgqvc6qf4r3j9bmxpns7vfgfsr4y'; // Replace with your actual secret

// Generate JWT token for Stream.io
function generateStreamToken($user_id, $api_secret) {
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $payload = json_encode([
        'user_id' => $user_id,
        'iat' => time(),
        'exp' => time() + (24 * 60 * 60) // 24 hours expiration
    ]);
    
    $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    
    $signature = hash_hmac('sha256', $base64Header . "." . $base64Payload, $api_secret, true);
    $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    
    return $base64Header . "." . $base64Payload . "." . $base64Signature;
}

try {
    $user_id = (string)$student_id;
    $token = generateStreamToken($user_id, $api_secret);
    
    $response = [
        'success' => true,
        'token' => $token,
        'api_key' => $api_key,
        'user' => [
            'id' => $user_id,
            'name' => ($user['first_name'] ?? 'User') . ' ' . ($user['last_name'] ?? ''),
            'image' => $user['profile_picture'] ?? 'https://ui-avatars.com/api/?name=' . urlencode(($user['first_name'] ?? 'User') . ' ' . ($user['last_name'] ?? ''))
        ]
    ];
    
    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to generate token: ' . $e->getMessage()]);
}
?>
