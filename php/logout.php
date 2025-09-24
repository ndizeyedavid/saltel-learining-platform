<?php
session_start();
include 'connect.php';

// Clear remember token from database if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $clear_token = $conn->prepare("UPDATE users SET remember_token = NULL, remember_expires = NULL WHERE user_id = ?");
    $clear_token->bind_param("i", $user_id);
    $clear_token->execute();
}

// Clear remember me cookie
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/', '', false, true);
}

// Destroy session
session_unset();
session_destroy();

// Redirect to login with success message
session_start();
$_SESSION['success_message'] = "You have been logged out successfully.";
header("Location: ../login.php");
exit();
?>
