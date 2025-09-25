<?php
require_once '../../../include/connect.php';
require_once '../../../include/trainer-guard.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGetResources();
        break;
    case 'POST':
        handleUploadResource();
        break;
    case 'DELETE':
        handleDeleteResource();
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function handleGetResources() {
    global $conn;
    
    $lesson_id = isset($_GET['lesson_id']) ? (int)$_GET['lesson_id'] : 0;
    $module_id = isset($_GET['module_id']) ? (int)$_GET['module_id'] : 0;
    
    if ($lesson_id <= 0 && $module_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Either lesson_id or module_id is required']);
        return;
    }
    
    // Verify ownership
    if ($lesson_id > 0) {
        $owner_check = $conn->prepare("
            SELECT c.teacher_id 
            FROM course_lessons l
            JOIN course_modules m ON l.module_id = m.module_id
            JOIN courses c ON m.course_id = c.course_id 
            WHERE l.lesson_id = ?
        ");
        $owner_check->bind_param('i', $lesson_id);
    } else {
        $owner_check = $conn->prepare("
            SELECT c.teacher_id 
            FROM course_modules m 
            JOIN courses c ON m.course_id = c.course_id 
            WHERE m.module_id = ?
        ");
        $owner_check->bind_param('i', $module_id);
    }
    
    $owner_check->execute();
    $owner_result = $owner_check->get_result();
    
    if ($owner_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Resource parent not found']);
        return;
    }
    
    $data = $owner_result->fetch_assoc();
    if ($data['teacher_id'] != $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        return;
    }
    
    // Get resources (using actual database schema)
    $where_clause = $lesson_id > 0 ? "lesson_id = ?" : "module_id = ? AND lesson_id IS NULL";
    $param_value = $lesson_id > 0 ? $lesson_id : $module_id;
    
    $stmt = $conn->prepare("
        SELECT 
            resource_id,
            resource_type,
            resource_name,
            resource_url,
            file_size,
            mime_type,
            is_downloadable,
            uploaded_at
        FROM course_resources 
        WHERE $where_clause
        ORDER BY uploaded_at DESC
    ");
    
    $stmt->bind_param('i', $param_value);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $resources = [];
    while ($row = $result->fetch_assoc()) {
        $resources[] = [
            'resource_id' => (int)$row['resource_id'],
            'resource_type' => $row['resource_type'],
            'resource_name' => $row['resource_name'],
            'resource_url' => $row['resource_url'],
            'file_size' => $row['file_size'] ? (int)$row['file_size'] : null,
            'mime_type' => $row['mime_type'],
            'is_downloadable' => (bool)$row['is_downloadable'],
            'uploaded_at' => $row['uploaded_at']
        ];
    }
    
    echo json_encode(['resources' => $resources]);
}

function handleUploadResource() {
    global $conn;
    
    $lesson_id = isset($_POST['lesson_id']) ? (int)$_POST['lesson_id'] : 0;
    $module_id = isset($_POST['module_id']) ? (int)$_POST['module_id'] : 0;
    $resource_name = isset($_POST['resource_name']) ? trim($_POST['resource_name']) : '';
    $is_downloadable = isset($_POST['is_downloadable']) ? (bool)$_POST['is_downloadable'] : true;
    
    if ($lesson_id <= 0 && $module_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Either lesson_id or module_id is required']);
        return;
    }
    
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['error' => 'No file uploaded or upload error']);
        return;
    }
    
    $file = $_FILES['file'];
    $file_size = $file['size'];
    $mime_type = $file['type'];
    $original_name = $file['name'];
    
    // Validate file size (max 50MB)
    if ($file_size > 50 * 1024 * 1024) {
        http_response_code(400);
        echo json_encode(['error' => 'File size too large (max 50MB)']);
        return;
    }
    
    // Determine resource type based on MIME type
    $resource_type = 'document';
    if (strpos($mime_type, 'video/') === 0) {
        $resource_type = 'video';
    } elseif (strpos($mime_type, 'audio/') === 0) {
        $resource_type = 'audio';
    } elseif (strpos($mime_type, 'image/') === 0) {
        $resource_type = 'image';
    }
    
    // Verify ownership
    if ($lesson_id > 0) {
        $owner_check = $conn->prepare("
            SELECT c.teacher_id 
            FROM course_lessons l
            JOIN course_modules m ON l.module_id = m.module_id
            JOIN courses c ON m.course_id = c.course_id 
            WHERE l.lesson_id = ?
        ");
        $owner_check->bind_param('i', $lesson_id);
    } else {
        $owner_check = $conn->prepare("
            SELECT c.teacher_id 
            FROM course_modules m 
            JOIN courses c ON m.course_id = c.course_id 
            WHERE m.module_id = ?
        ");
        $owner_check->bind_param('i', $module_id);
    }
    
    $owner_check->execute();
    $owner_result = $owner_check->get_result();
    
    if ($owner_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Resource parent not found']);
        return;
    }
    
    $data = $owner_result->fetch_assoc();
    if ($data['teacher_id'] != $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        return;
    }
    
    // Create upload directory
    $upload_dir = '../../../uploads/course_resources/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Generate unique filename
    $file_extension = pathinfo($original_name, PATHINFO_EXTENSION);
    $unique_filename = uniqid() . '_' . time() . '.' . $file_extension;
    $file_path = $upload_dir . $unique_filename;
    
    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $file_path)) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save file']);
        return;
    }
    
    // Save to database (using actual database schema)
    $resource_url = 'uploads/course_resources/' . $unique_filename;
    $display_name = !empty($resource_name) ? $resource_name : $original_name;
    
    $stmt = $conn->prepare("
        INSERT INTO course_resources (lesson_id, module_id, resource_type, resource_name, resource_url, file_size, mime_type, is_downloadable)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $lesson_param = $lesson_id > 0 ? $lesson_id : null;
    $module_param = $module_id > 0 ? $module_id : null;
    $downloadable_int = $is_downloadable ? 1 : 0;
    
    $stmt->bind_param('iissssii', $lesson_param, $module_param, $resource_type, $display_name, $resource_url, $file_size, $mime_type, $downloadable_int);
    
    if ($stmt->execute()) {
        $resource_id = $conn->insert_id;
        echo json_encode([
            'success' => true,
            'resource_id' => $resource_id,
            'resource_url' => $resource_url,
            'message' => 'Resource uploaded successfully'
        ]);
    } else {
        // Clean up uploaded file on database error
        unlink($file_path);
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save resource to database']);
    }
}

function handleDeleteResource() {
    global $conn;
    
    $resource_id = isset($_GET['resource_id']) ? (int)$_GET['resource_id'] : 0;
    
    if ($resource_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid resource ID']);
        return;
    }
    
    // Get resource details and verify ownership
    $stmt = $conn->prepare("
        SELECT 
            r.resource_url,
            c.teacher_id 
        FROM course_resources r
        LEFT JOIN course_lessons l ON r.lesson_id = l.lesson_id
        LEFT JOIN course_modules m ON (r.module_id = m.module_id OR l.module_id = m.module_id)
        JOIN courses c ON m.course_id = c.course_id 
        WHERE r.resource_id = ?
    ");
    
    $stmt->bind_param('i', $resource_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Resource not found']);
        return;
    }
    
    $resource_data = $result->fetch_assoc();
    if ($resource_data['teacher_id'] != $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        return;
    }
    
    // Delete from database
    $delete_stmt = $conn->prepare("DELETE FROM course_resources WHERE resource_id = ?");
    $delete_stmt->bind_param('i', $resource_id);
    
    if ($delete_stmt->execute()) {
        // Delete physical file
        $file_path = '../../../' . $resource_data['resource_url'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        echo json_encode(['success' => true, 'message' => 'Resource deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete resource']);
    }
}
