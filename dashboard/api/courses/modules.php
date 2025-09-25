<?php
require_once '../../../include/connect.php';
require_once '../../../include/trainer-guard.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGetModules();
        break;
    case 'POST':
        handleCreateModule();
        break;
    case 'PUT':
        handleUpdateModule();
        break;
    case 'DELETE':
        handleDeleteModule();
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function handleGetModules()
{
    global $conn;

    $course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
    if ($course_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid course ID']);
        return;
    }

    // Verify course ownership
    $owner_check = $conn->prepare("SELECT teacher_id FROM courses WHERE course_id = ?");
    $owner_check->bind_param('i', $course_id);
    $owner_check->execute();
    $owner_result = $owner_check->get_result();

    if ($owner_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Course not found']);
        return;
    }

    $course_data = $owner_result->fetch_assoc();
    if ($course_data['teacher_id'] != $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        return;
    }

    // Get modules with lesson counts (using current database schema)
    $stmt = $conn->prepare("
        SELECT 
            m.module_id,
            m.title,
            m.description,
            m.sort_order,
            m.duration_minutes,
            m.points,
            m.is_published,
            m.created_at,
            0 as lesson_count
        FROM course_modules m
        WHERE m.course_id = ?
        ORDER BY m.sort_order ASC, m.created_at ASC
    ");

    $stmt->bind_param('i', $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $modules = [];
    while ($row = $result->fetch_assoc()) {
        $modules[] = [
            'module_id' => (int)$row['module_id'],
            'module_title' => $row['title'],
            'module_description' => $row['description'],
            'module_order' => (int)$row['sort_order'],
            'duration_minutes' => $row['duration_minutes'] ? (int)$row['duration_minutes'] : null,
            'points' => (int)$row['points'],
            'is_published' => (bool)$row['is_published'],
            'lesson_count' => (int)$row['lesson_count'],
            'created_at' => $row['created_at']
        ];
    }

    echo json_encode(['modules' => $modules]);
}

function handleCreateModule()
{
    global $conn;

    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['course_id']) || !isset($input['module_title'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }

    $course_id = (int)$input['course_id'];
    $module_title = trim($input['module_title']);
    $module_description = isset($input['module_description']) ? trim($input['module_description']) : '';
    $duration_minutes = isset($input['duration_minutes']) ? (int)$input['duration_minutes'] : null;
    $points = isset($input['points']) ? (int)$input['points'] : 0;

    if (empty($module_title)) {
        http_response_code(400);
        echo json_encode(['error' => 'Module title is required']);
        return;
    }

    // Verify course ownership
    $owner_check = $conn->prepare("SELECT teacher_id FROM courses WHERE course_id = ?");
    $owner_check->bind_param('i', $course_id);
    $owner_check->execute();
    $owner_result = $owner_check->get_result();

    if ($owner_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Course not found']);
        return;
    }

    $course_data = $owner_result->fetch_assoc();
    if ($course_data['teacher_id'] != $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        return;
    }

    // Get next module order (using current database schema)
    $order_stmt = $conn->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 as next_order FROM course_modules WHERE course_id = ?");
    $order_stmt->bind_param('i', $course_id);
    $order_stmt->execute();
    $order_result = $order_stmt->get_result();
    $next_order = $order_result->fetch_assoc()['next_order'];

    // Insert new module (using current database schema columns)
    $stmt = $conn->prepare("
        INSERT INTO course_modules (course_id, title, description, sort_order)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param('issi', $course_id, $module_title, $module_description, $next_order);

    if ($stmt->execute()) {
        $module_id = $conn->insert_id;
        echo json_encode([
            'success' => true,
            'module_id' => $module_id,
            'message' => 'Module created successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create module']);
    }
}

function handleUpdateModule()
{
    global $conn;

    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['module_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Module ID is required']);
        return;
    }

    $module_id = (int)$input['module_id'];

    // Verify module ownership
    $owner_check = $conn->prepare("
        SELECT c.teacher_id 
        FROM course_modules m 
        JOIN courses c ON m.course_id = c.course_id 
        WHERE m.module_id = ?
    ");
    $owner_check->bind_param('i', $module_id);
    $owner_check->execute();
    $owner_result = $owner_check->get_result();

    if ($owner_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Module not found']);
        return;
    }

    $module_data = $owner_result->fetch_assoc();
    if ($module_data['teacher_id'] != $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        return;
    }

    // Build update query dynamically (using current database schema)
    $updates = [];
    $params = [];
    $types = '';

    if (isset($input['module_title'])) {
        $updates[] = 'title = ?';
        $params[] = trim($input['module_title']);
        $types .= 's';
    }

    if (isset($input['module_description'])) {
        $updates[] = 'description = ?';
        $params[] = trim($input['module_description']);
        $types .= 's';
    }

    // Note: duration_minutes, points, is_published columns don't exist in current schema
    // They will be available after running the migration

    if (empty($updates)) {
        http_response_code(400);
        echo json_encode(['error' => 'No fields to update']);
        return;
    }

    $params[] = $module_id;
    $types .= 'i';

    $sql = "UPDATE course_modules SET " . implode(', ', $updates) . " WHERE module_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Module updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update module']);
    }
}

function handleDeleteModule()
{
    global $conn;

    $module_id = isset($_GET['module_id']) ? (int)$_GET['module_id'] : 0;

    if ($module_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid module ID']);
        return;
    }

    // Verify module ownership
    $owner_check = $conn->prepare("
        SELECT c.teacher_id 
        FROM course_modules m 
        JOIN courses c ON m.course_id = c.course_id 
        WHERE m.module_id = ?
    ");
    $owner_check->bind_param('i', $module_id);
    $owner_check->execute();
    $owner_result = $owner_check->get_result();

    if ($owner_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Module not found']);
        return;
    }

    $module_data = $owner_result->fetch_assoc();
    if ($module_data['teacher_id'] != $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        return;
    }

    // Delete module (cascade will handle lessons and resources)
    $stmt = $conn->prepare("DELETE FROM course_modules WHERE module_id = ?");
    $stmt->bind_param('i', $module_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Module deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete module']);
    }
}
