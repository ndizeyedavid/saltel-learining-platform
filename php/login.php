<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']);

    // Validate input
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: ../login.php");
        exit();
    }

    // Check if user exists
    $query = $conn->prepare("SELECT user_id, first_name, last_name, email, password, is_verified, role FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: ../login.php");
        exit();
    }

    $user = $result->fetch_assoc();

    // Verify password
    if (!password_verify($password, $user['password'])) {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: ../login.php");
        exit();
    }

    // Check if email is verified
    if (!$user['is_verified']) {
        $_SESSION['error'] = "Please verify your email address before logging in. Check your inbox for the verification code.";
        $_SESSION['unverified_email'] = $email;
        header("Location: ../login.php");
        exit();
    }

    // Login successful - Set session variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['logged_in'] = true;

    // Handle "Remember Me" functionality
    if ($remember_me) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
        
        // Store remember token in database
        $remember_query = $conn->prepare("UPDATE users SET remember_token = ?, remember_expires = ? WHERE user_id = ?");
        $remember_query->bind_param("ssi", $token, $expires, $user['user_id']);
        $remember_query->execute();
        
        // Set cookie
        setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
    }

    // Update last login time
    $update_login = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
    $update_login->bind_param("i", $user['user_id']);
    $update_login->execute();

    // Redirect based on user role
    switch ($user['role']) {
        case 'SuperAdmin':
            header("Location: ../dashboard/admin/index.php");
            break;
        case 'Teacher':
            header("Location: ../dashboard/trainer/index.php");
            break;
        case 'Student':
            header("Location: ../dashboard/trainee/index.php");
            break;
        default:
            header("Location: ../dashboard/trainee/index.php");
            break;
    }
    exit();

} else {
    // Direct access not allowed
    header("Location: ../login.php");
    exit();
}
?>
