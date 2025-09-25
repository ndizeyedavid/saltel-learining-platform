<?php
require_once __DIR__ . '/../_bootstrap.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            handleGetProfile();
            break;
        case 'PUT':
        case 'PATCH':
            handleUpdateProfile();
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error']);
}

function hasTable($table)
{
    global $conn;
    $stmt = $conn->prepare("SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ? LIMIT 1");
    $stmt->bind_param("s", $table);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res && $res->num_rows === 1;
}

function hasColumn($table, $column)
{
    global $conn;
    $stmt = $conn->prepare("SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ? LIMIT 1");
    $stmt->bind_param("ss", $table, $column);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res && $res->num_rows === 1;
}

function handleGetProfile()
{
    global $conn;
    $user_id = $_SESSION['user_id'];

    $selectCols = "user_id, first_name, middle_name, last_name, gender, email, phone, role, last_login, created_at";
    if (hasColumn('users', 'profile_image_url')) {
        $selectCols .= ", profile_image_url";
    }
    $stmt = $conn->prepare("SELECT $selectCols FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows !== 1) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        return;
    }

    $user = $result->fetch_assoc();

    // Load teacher profile extras if table exists
    $extra = [
        'expertise' => null,
        'bio' => null,
        'linkedin_url' => null,
        'website_url' => null
    ];
    if (hasTable('teacher_profiles')) {
        $stmt2 = $conn->prepare("SELECT expertise, bio, linkedin_url, website_url FROM teacher_profiles WHERE user_id = ?");
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();
        $res2 = $stmt2->get_result();
        if ($row2 = $res2->fetch_assoc()) {
            $extra = $row2;
        }
    }

    echo json_encode([
        'success' => [
            'user' => [
                'user_id' => intval($user['user_id']),
                'first_name' => $user['first_name'],
                'middle_name' => $user['middle_name'],
                'last_name' => $user['last_name'],
                'gender' => $user['gender'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'role' => $user['role'],
                'last_login' => $user['last_login'],
                'created_at' => $user['created_at'],
                'expertise' => $extra['expertise'],
                'bio' => $extra['bio'],
                'linkedin_url' => $extra['linkedin_url'],
                'website_url' => $extra['website_url'],
                'profile_image_url' => (isset($user['profile_image_url']) ? $user['profile_image_url'] : null)
            ]
        ]
    ]);
}

function handleUpdateProfile()
{
    global $conn;
    $user_id = $_SESSION['user_id'];
    $body = read_json_body();

    $first_name = trim($body['first_name'] ?? '');
    $middle_name = trim($body['middle_name'] ?? '');
    $last_name = trim($body['last_name'] ?? '');
    $email = trim($body['email'] ?? '');
    $phone = trim($body['phone'] ?? '');
    $gender = trim($body['gender'] ?? '');
    $expertise = trim($body['expertise'] ?? '');
    $bio = trim($body['bio'] ?? '');
    $linkedin = trim($body['linkedin_url'] ?? '');
    $website = trim($body['website_url'] ?? '');

    if ($first_name === '' || $last_name === '' || $email === '' || $gender === '') {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }

    // Ensure email is unique to this user
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id <> ? LIMIT 1");
    $stmt->bind_param("si", $email, $user_id);
    $stmt->execute();
    $dupe = $stmt->get_result();
    if ($dupe->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['error' => 'Email already in use']);
        return;
    }

    $stmt = $conn->prepare("UPDATE users SET first_name = ?, middle_name = ?, last_name = ?, email = ?, phone = ?, gender = ? WHERE user_id = ?");
    $stmt->bind_param("ssssssi", $first_name, $middle_name, $last_name, $email, $phone, $gender, $user_id);
    $ok = $stmt->execute();

    if (!$ok) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update profile']);
        return;
    }

    // Upsert teacher profile extras
    $stmt3 = $conn->prepare("INSERT INTO teacher_profiles (user_id, expertise, bio, linkedin_url, website_url) VALUES (?, ?, ?, ?, ?) 
        ON DUPLICATE KEY UPDATE expertise = VALUES(expertise), bio = VALUES(bio), linkedin_url = VALUES(linkedin_url), website_url = VALUES(website_url)");
    $stmt3->bind_param("issss", $user_id, $expertise, $bio, $linkedin, $website);
    $ok2 = $stmt3->execute();
    if (!$ok2) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update teacher profile']);
        return;
    }

    // Update session display name/email if changed
    $_SESSION['user_email'] = $email;
    $_SESSION['user_name'] = $first_name . ' ' . $last_name;

    echo json_encode([
        'success' => [
            'message' => 'Profile updated successfully'
        ]
    ]);
}
