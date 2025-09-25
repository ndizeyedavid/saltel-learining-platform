<?php
require_once __DIR__ . '/../_bootstrap.php';

function hasColumn($table, $column)
{
    global $conn;
    $stmt = $conn->prepare("SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ? LIMIT 1");
    $stmt->bind_param("ss", $table, $column);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res && $res->num_rows === 1;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'No image uploaded']);
    exit();
}

$file = $_FILES['image'];

// Validate size (<= 2MB)
if ($file['size'] > 2 * 1024 * 1024) {
    http_response_code(413);
    echo json_encode(['error' => 'File too large (max 2MB)']);
    exit();
}

// Validate mime type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);
$allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif'];
if (!isset($allowed[$mime])) {
    http_response_code(415);
    echo json_encode(['error' => 'Invalid image type']);
    exit();
}

$ext = $allowed[$mime];
$user_id = $_SESSION['user_id'];
$dir = __DIR__ . '/../../../uploads/profile';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

$filename = 'user_' . $user_id . '_' . time() . '.' . $ext;
$target = $dir . '/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $target)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save image']);
    exit();
}

// Build public URL (relative)
$publicPath = 'uploads/profile/' . $filename;

// Save to DB
global $conn;
$sql = hasColumn('users', 'profile_image_url') ?
    "UPDATE users SET profile_image_url = ? WHERE user_id = ?" :
    "INSERT INTO teacher_profiles (user_id, profile_image_url) VALUES (?, ?) ON DUPLICATE KEY UPDATE profile_image_url = VALUES(profile_image_url)";

if (strpos($sql, 'users') !== false) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $publicPath, $user_id);
} else {
    // teacher_profiles fallback
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $publicPath);
}
$ok = $stmt->execute();
if (!$ok) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update profile image']);
    exit();
}

echo json_encode(['success' => ['profile_image_url' => $publicPath]]);
