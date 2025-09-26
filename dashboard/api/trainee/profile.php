<?php
session_start();
require_once '../../../include/connect.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'POST':
            handleProfileUpdate();
            break;
        case 'GET':
            handleGetProfile();
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error: ' . $e->getMessage()]);
}

function handleProfileUpdate()
{
    global $conn;

    $user_id = $_SESSION['user_id'];

    // Validate required fields
    $required_fields = ['first_name', 'last_name', 'email', 'phone', 'gender', 'institution', 'level_year', 'program'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            http_response_code(400);
            echo json_encode(['error' => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
            return;
        }
    }

    // Sanitize input data
    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name'] ?? '');
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $gender = trim($_POST['gender']);
    $institution = trim($_POST['institution']);
    $level_year = trim($_POST['level_year']);
    $program = trim($_POST['program']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid email format']);
        return;
    }

    // Validate gender
    if (!in_array($gender, ['Male', 'Female', 'Other'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid gender selection']);
        return;
    }

    // Check if email is already taken by another user
    $email_check = "SELECT user_id FROM users WHERE email = ? AND user_id != ?";
    $stmt = $conn->prepare($email_check);
    $stmt->bind_param("si", $email, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['error' => 'Email address is already taken']);
        return;
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update users table
        $user_update = "UPDATE users SET first_name = ?, middle_name = ?, last_name = ?, email = ?, phone = ?, gender = ? WHERE user_id = ?";
        $stmt = $conn->prepare($user_update);
        $stmt->bind_param("ssssssi", $first_name, $middle_name, $last_name, $email, $phone, $gender, $user_id);

        if (!$stmt->execute()) {
            throw new Exception('Failed to update user information');
        }

        // Check if student record exists
        $student_check = "SELECT student_id FROM students WHERE user_id = ?";
        $stmt = $conn->prepare($student_check);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update existing student record
            $student_update = "UPDATE students SET institution = ?, level_year = ?, program = ? WHERE user_id = ?";
            $stmt = $conn->prepare($student_update);
            $stmt->bind_param("sssi", $institution, $level_year, $program, $user_id);

            if (!$stmt->execute()) {
                throw new Exception('Failed to update student information');
            }
        } else {
            // Insert new student record
            $student_insert = "INSERT INTO students (user_id, institution, level_year, program) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($student_insert);
            $stmt->bind_param("isss", $user_id, $institution, $level_year, $program);

            if (!$stmt->execute()) {
                throw new Exception('Failed to create student profile');
            }
        }

        // Commit transaction
        $conn->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        throw $e;
    }
}

function handleGetProfile()
{
    global $conn;

    $user_id = $_SESSION['user_id'];

    // Get user and student information
    $profile_query = "
        SELECT 
            u.user_id,
            u.first_name,
            u.middle_name,
            u.last_name,
            u.email,
            u.phone,
            u.gender,
            u.created_at,
            s.student_id,
            s.institution,
            s.level_year,
            s.program
        FROM users u
        LEFT JOIN students s ON u.user_id = s.user_id
        WHERE u.user_id = ?
    ";

    $stmt = $conn->prepare($profile_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        return;
    }

    $profile = $result->fetch_assoc();

    echo json_encode([
        'success' => true,
        'profile' => [
            'user_id' => (int)$profile['user_id'],
            'first_name' => $profile['first_name'],
            'middle_name' => $profile['middle_name'],
            'last_name' => $profile['last_name'],
            'email' => $profile['email'],
            'phone' => $profile['phone'],
            'gender' => $profile['gender'],
            'created_at' => $profile['created_at'],
            'student_id' => $profile['student_id'] ? (int)$profile['student_id'] : null,
            'institution' => $profile['institution'],
            'level_year' => $profile['level_year'],
            'program' => $profile['program'],
            'profile_complete' => !is_null($profile['student_id'])
        ]
    ]);
}
