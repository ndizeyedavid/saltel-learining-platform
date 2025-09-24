<?php
// Authentication check for protected pages
session_start();
include_once 'connect.php';

function checkAuth() {
    global $conn;
    
    // Check if user is logged in via session
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        return true;
    }
    
    // Check remember me cookie
    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];
        
        $query = $conn->prepare("SELECT user_id, first_name, last_name, email, role FROM users WHERE remember_token = ? AND remember_expires > NOW()");
        $query->bind_param("s", $token);
        $query->execute();
        $result = $query->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Restore session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            
            return true;
        } else {
            // Invalid or expired token, clear cookie
            setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        }
    }
    
    return false;
}

function requireAuth($redirect_to = '../login.php') {
    if (!checkAuth()) {
        $_SESSION['error'] = "Please log in to access this page.";
        header("Location: $redirect_to");
        exit();
    }
}

function requireRole($required_role, $redirect_to = '../login.php') {
    requireAuth($redirect_to);
    
    if ($_SESSION['user_role'] !== $required_role) {
        $_SESSION['error'] = "You don't have permission to access this page.";
        header("Location: $redirect_to");
        exit();
    }
}
?>
