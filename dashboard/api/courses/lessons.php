<?php
require_once '../../../include/connect.php';
require_once '../../../include/trainer-guard.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGetLessons();
        break;
    case 'POST':
        handleCreateLesson();
        break;
    case 'PUT':
        handleUpdateLesson();
        break;
    case 'DELETE':
        handleDeleteLesson();
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function handleGetLessons() {
    global $conn;
    
    // Check if requesting a specific lesson
    if (isset($_GET['lesson_id'])) {
        return handleGetSingleLesson();
    }
    
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
    
    // Get lessons with resource counts (using actual database schema)
    $stmt = $conn->prepare("
        SELECT 
            l.lesson_id,
            l.title as lesson_title,
            l.content as lesson_content,
            l.sort_order as lesson_order,
            0 as duration_minutes,
            0 as points,
            0 as is_published,
            l.lesson_type,
            l.created_at,
            0 as resource_count,
            0 as question_count
        FROM course_lessons l
        WHERE l.module_id = ?
        ORDER BY l.sort_order ASC, l.created_at ASC
    ");
    
    $stmt->bind_param('i', $module_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $lessons = [];
    while ($row = $result->fetch_assoc()) {
        $lessons[] = [
            'lesson_id' => (int)$row['lesson_id'],
            'lesson_title' => $row['lesson_title'],
            'lesson_content' => $row['lesson_content'],
            'lesson_order' => (int)$row['lesson_order'],
            'duration_minutes' => $row['duration_minutes'] ? (int)$row['duration_minutes'] : null,
            'points' => (int)$row['points'],
            'is_published' => (bool)$row['is_published'],
            'lesson_type' => $row['lesson_type'],
            'resource_count' => (int)$row['resource_count'],
            'question_count' => (int)$row['question_count'],
            'created_at' => $row['created_at']
        ];
    }
    
    echo json_encode(['lessons' => $lessons]);
}

function handleGetSingleLesson() {
    global $conn;
    
    $lesson_id = isset($_GET['lesson_id']) ? (int)$_GET['lesson_id'] : 0;
    if ($lesson_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid lesson ID']);
        return;
    }
    
    // Verify lesson ownership
    $owner_check = $conn->prepare("
        SELECT c.teacher_id 
        FROM course_lessons l
        JOIN course_modules m ON l.module_id = m.module_id
        JOIN courses c ON m.course_id = c.course_id 
        WHERE l.lesson_id = ?
    ");
    $owner_check->bind_param('i', $lesson_id);
    $owner_check->execute();
    $owner_result = $owner_check->get_result();
    
    if ($owner_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Lesson not found']);
        return;
    }
    
    $lesson_data = $owner_result->fetch_assoc();
    if ($lesson_data['teacher_id'] != $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        return;
    }
    
    // Get lesson details
    $stmt = $conn->prepare("
        SELECT 
            l.lesson_id,
            l.title as lesson_title,
            l.content as lesson_content,
            l.sort_order as lesson_order,
            l.lesson_type,
            l.created_at,
            m.title as module_title
        FROM course_lessons l
        JOIN course_modules m ON l.module_id = m.module_id
        WHERE l.lesson_id = ?
    ");
    
    $stmt->bind_param('i', $lesson_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Lesson not found']);
        return;
    }
    
    $lesson = $result->fetch_assoc();
    
    // Format the lesson data
    $formatted_lesson = [
        'lesson_id' => (int)$lesson['lesson_id'],
        'lesson_title' => $lesson['lesson_title'],
        'lesson_content' => $lesson['lesson_content'],
        'lesson_order' => (int)$lesson['lesson_order'],
        'lesson_type' => $lesson['lesson_type'],
        'module_title' => $lesson['module_title'],
        'created_at' => $lesson['created_at']
    ];
    
    echo json_encode(['lesson' => $formatted_lesson]);
}

function handleCreateLesson() {
    global $conn;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['module_id']) || !isset($input['lesson_title'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        return;
    }
    
    $module_id = (int)$input['module_id'];
    $lesson_title = trim($input['lesson_title']);
    $lesson_content = isset($input['lesson_content']) ? $input['lesson_content'] : '';
    $duration_minutes = isset($input['duration_minutes']) ? (int)$input['duration_minutes'] : null;
    $points = isset($input['points']) ? (int)$input['points'] : 0;
    $lesson_type = isset($input['lesson_type']) ? $input['lesson_type'] : 'text';
    
    if (empty($lesson_title)) {
        http_response_code(400);
        echo json_encode(['error' => 'Lesson title is required']);
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
    
    // Get next lesson order (using actual database schema)
    $order_stmt = $conn->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 as next_order FROM course_lessons WHERE module_id = ?");
    $order_stmt->bind_param('i', $module_id);
    $order_stmt->execute();
    $order_result = $order_stmt->get_result();
    $next_order = $order_result->fetch_assoc()['next_order'];
    
    // Insert new lesson (using actual database schema)
    $stmt = $conn->prepare("
        INSERT INTO course_lessons (module_id, title, content, sort_order, lesson_type, course_id)
        VALUES (?, ?, ?, ?, ?, (SELECT course_id FROM course_modules WHERE module_id = ?))
    ");
    
    $stmt->bind_param('issisi', $module_id, $lesson_title, $lesson_content, $next_order, $lesson_type, $module_id);
    
    if ($stmt->execute()) {
        $lesson_id = $conn->insert_id;
        echo json_encode([
            'success' => true,
            'lesson_id' => $lesson_id,
            'message' => 'Lesson created successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create lesson']);
    }
}

function handleUpdateLesson() {
    global $conn;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['lesson_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Lesson ID is required']);
        return;
    }
    
    $lesson_id = (int)$input['lesson_id'];
    
    // Verify lesson ownership
    $owner_check = $conn->prepare("
        SELECT c.teacher_id 
        FROM course_lessons l
        JOIN course_modules m ON l.module_id = m.module_id
        JOIN courses c ON m.course_id = c.course_id 
        WHERE l.lesson_id = ?
    ");
    $owner_check->bind_param('i', $lesson_id);
    $owner_check->execute();
    $owner_result = $owner_check->get_result();
    
    if ($owner_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Lesson not found']);
        return;
    }
    
    $lesson_data = $owner_result->fetch_assoc();
    if ($lesson_data['teacher_id'] != $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        return;
    }
    
    // Build update query dynamically (using actual database schema)
    $updates = [];
    $params = [];
    $types = '';
    
    if (isset($input['lesson_title'])) {
        $updates[] = 'title = ?';
        $params[] = trim($input['lesson_title']);
        $types .= 's';
    }
    
    if (isset($input['lesson_content'])) {
        $updates[] = 'content = ?';
        $params[] = $input['lesson_content'];
        $types .= 's';
    }
    
    if (isset($input['lesson_type'])) {
        $updates[] = 'lesson_type = ?';
        $params[] = $input['lesson_type'];
        $types .= 's';
    }
    
    // Note: duration_minutes, points, is_published columns don't exist in current schema
    
    if (empty($updates)) {
        http_response_code(400);
        echo json_encode(['error' => 'No fields to update']);
        return;
    }
    
    $params[] = $lesson_id;
    $types .= 'i';
    
    $sql = "UPDATE course_lessons SET " . implode(', ', $updates) . " WHERE lesson_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Lesson updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update lesson']);
    }
}

function handleDeleteLesson() {
    global $conn;
    
    $lesson_id = isset($_GET['lesson_id']) ? (int)$_GET['lesson_id'] : 0;
    
    if ($lesson_id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid lesson ID']);
        return;
    }
    
    // Verify lesson ownership
    $owner_check = $conn->prepare("
        SELECT c.teacher_id 
        FROM course_lessons l
        JOIN course_modules m ON l.module_id = m.module_id
        JOIN courses c ON m.course_id = c.course_id 
        WHERE l.lesson_id = ?
    ");
    $owner_check->bind_param('i', $lesson_id);
    $owner_check->execute();
    $owner_result = $owner_check->get_result();
    
    if ($owner_result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Lesson not found']);
        return;
    }
    
    $lesson_data = $owner_result->fetch_assoc();
    if ($lesson_data['teacher_id'] != $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        return;
    }
    
    // Delete lesson (cascade will handle resources and quiz questions)
    $stmt = $conn->prepare("DELETE FROM course_lessons WHERE lesson_id = ?");
    $stmt->bind_param('i', $lesson_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Lesson deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete lesson']);
    }
}
