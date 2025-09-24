<?php
session_start();
session_unset();

include '../php/connect.php';
require_once '../services/EmailService.php';

// Function to generate 6-digit OTP
function generateOTP() {
    return sprintf("%06d", mt_rand(100000, 999999));
}

if (isset($_POST['register'])) {
    $fname = mysqli_real_escape_string($conn, $_POST['first_name']);
    $lname = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    if ($password != $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: ../register.php");
        exit();
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    
    // Generate OTP and set expiration time (10 minutes from now)
    $otp = generateOTP();
    $otp_expires = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    $query = "INSERT INTO users (first_name, last_name, email, phone, password, gender, otp, otp_expires_at, is_verified) VALUES ('$fname', '$lname', '$email', '$phone', '$password', '$gender', '$otp', '$otp_expires', 0)";

    // Check if email or phone exists
    $check_query = $conn->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
    $check_query->bind_param("ss", $email, $phone);
    $check_query->execute();
    $result = $check_query->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Email or Phone already exists.";
        echo "Exists";
        header("Location: ../register.php");
        exit();
    }

    if ($conn->query($query) === TRUE) {
        // Send OTP email
        try {
            $emailService = new EmailService();
            $fullName = $fname . ' ' . $lname;
            
            if ($emailService->sendOTP($email, $fullName, $otp)) {
                $_SESSION['success'] = $email;
                $_SESSION['otp_sent'] = "OTP has been sent to your email address.";
                header("Location: ../otp.php");
                exit();
            } else {
                // If email fails, still allow user to proceed but show warning
                $_SESSION['success'] = $email;
                $_SESSION['warning'] = "Registration successful, but email could not be sent. Please contact support.";
                header("Location: ../otp.php");
                exit();
            }
        } catch (Exception $e) {
            // Log error and proceed
            error_log("OTP Email sending failed: " . $e->getMessage());
            $_SESSION['success'] = $email;
            $_SESSION['warning'] = "Registration successful, but email could not be sent. Please contact support.";
            header("Location: ../otp.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Registration failed. Please try again";
        header("Location: ../register.php");
        exit();
    }
}
