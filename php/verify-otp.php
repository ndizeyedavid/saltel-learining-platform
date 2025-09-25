<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['success'])) {
        $_SESSION['error'] = "Session expired. Please register again.";
        header("Location: ../register.php");
        exit();
    }

    $email = $_SESSION['success'];
    $entered_otp = $_POST['otp'];

    // Validate OTP input
    if (empty($entered_otp) || strlen($entered_otp) !== 6 || !ctype_digit($entered_otp)) {
        $_SESSION['error'] = "Please enter a valid 6-digit OTP code.";
        header("Location: ../otp.php");
        exit();
    }

    // Check OTP in database
    $query = $conn->prepare("SELECT user_id, otp, otp_expires_at, is_verified FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "User not found. Please register again.";
        header("Location: ../register.php");
        exit();
    }

    $user = $result->fetch_assoc();

    // Check if already verified
    if ($user['is_verified']) {
        $_SESSION['success_message'] = "Account already verified. You can now login.";
        header("Location: ../login.php");
        exit();
    }

    // Check if OTP has expired
    $current_time = date('Y-m-d H:i:s');
    if ($current_time > $user['otp_expires_at']) {
        $_SESSION['error'] = "OTP has expired. Please register again to get a new code.";
        header("Location: ../register.php");
        exit();
    }

    // Verify OTP
    if ($entered_otp === $user['otp']) {
        // OTP is correct, mark user as verified
        $update_query = $conn->prepare("UPDATE users SET is_verified = 1, otp = NULL, otp_expires_at = NULL WHERE email = ?");
        $update_query->bind_param("s", $email);
        
        if ($update_query->execute()) {
            // Clear session data
            session_unset();
            session_destroy();
            
            // Start new session for success message
            session_start();
            $_SESSION['success_message'] = "Email verified successfully! You can now login to your account.";
            header("Location: ../login.php");
            exit();
        } else {
            $_SESSION['error'] = "Verification failed. Please try again.";
            header("Location: ../otp.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid OTP code. Please check and try again.";
        header("Location: ../otp.php");
        exit();
    }
} else {
    // Direct access not allowed
    header("Location: ../otp.php");
    exit();
}
?>
