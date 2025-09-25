<?php
// Protect trainer pages - require logged in Teacher
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../php/check_auth.php';

// Check if this is an API request
$is_api_request = strpos($_SERVER['REQUEST_URI'], '/api/') !== false;

if ($is_api_request) {
    // For API requests, return JSON error instead of redirecting
    if (!checkAuth()) {
        http_response_code(401);
        echo json_encode(['error' => 'Authentication required']);
        exit();
    }
    
    if ($_SESSION['user_role'] !== 'Teacher') {
        http_response_code(403);
        echo json_encode(['error' => 'Teacher access required']);
        exit();
    }
} else {
    // For regular pages, redirect to login
    requireRole('Teacher', '../../dashboard/trainer/login.php');
}
