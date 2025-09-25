<?php
require_once __DIR__ . '/../_bootstrap.php';

global $conn;

$payload = read_json_body();

// Basic validation
if (!isset($payload['basicInfo']['title']) || trim($payload['basicInfo']['title']) === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Course title is required']);
    exit();
}

$teacher_user_id = (int) $_SESSION['user_id'];
$title = $payload['basicInfo']['title'] ?? '';
$description = $payload['basicInfo']['description'] ?? null;
$category = $payload['basicInfo']['category'] ?? 'General';
$level = $payload['basicInfo']['level'] ?? 'Beginner';
$price = isset($payload['basicInfo']['price']) ? (float)$payload['basicInfo']['price'] : 0.0;

// Optional settings
$status = $payload['settings']['status'] ?? 'Draft';
$visibility = $payload['settings']['visibility'] ?? 'Public';
$start_date = $payload['settings']['startDate'] ?? null;
$end_date = $payload['settings']['endDate'] ?? null;
$max_students = isset($payload['settings']['maxStudents']) ? (int)$payload['settings']['maxStudents'] : null;

$conn->begin_transaction();
try {
    // Insert course (some columns may not exist until migrations are applied)
    $course_id = null;
    $insert_sql = "INSERT INTO courses (teacher_id, category, course_title, description, price) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param('isssd', $teacher_user_id, $category, $title, $description, $price);
    $stmt->execute();
    $course_id = (int)$conn->insert_id;

    // If extended columns exist, try to update them softly
    @$conn->query("UPDATE courses SET status='" . $conn->real_escape_string($status) . "' WHERE course_id=" . $course_id);
    @$conn->query("UPDATE courses SET visibility='" . $conn->real_escape_string($visibility) . "' WHERE course_id=" . $course_id);
    if ($start_date) {
        @$conn->query("UPDATE courses SET start_date='" . $conn->real_escape_string($start_date) . "' WHERE course_id=" . $course_id);
    }
    if ($end_date) {
        @$conn->query("UPDATE courses SET end_date='" . $conn->real_escape_string($end_date) . "' WHERE course_id=" . $course_id);
    }
    if ($level) {
        @$conn->query("UPDATE courses SET level='" . $conn->real_escape_string($level) . "' WHERE course_id=" . $course_id);
    }
    if ($max_students !== null) {
        @$conn->query("UPDATE courses SET max_students=" . (int)$max_students . " WHERE course_id=" . $course_id);
    }

    // Insert content structure into course_modules and course_lessons if available
    if (!empty($payload['content']) && is_array($payload['content'])) {
        $module_order = 1;
        foreach ($payload['content'] as $module) {
            $module_title = $module['title'] ?? ('Module ' . $module_order);
            $module_id = null;
            if (@$conn->query("SELECT 1 FROM course_modules LIMIT 1")) {
                $mod_stmt = $conn->prepare("INSERT INTO course_modules (course_id, title, sort_order) VALUES (?, ?, ?)");
                $mod_stmt->bind_param('isi', $course_id, $module_title, $module_order);
                $mod_stmt->execute();
                $module_id = (int)$conn->insert_id;
            }
            $lesson_order = 1;
            if (!empty($module['lessons']) && is_array($module['lessons'])) {
                foreach ($module['lessons'] as $lesson) {
                    $lesson_title = $lesson['title'] ?? ('Lesson ' . $lesson_order);
                    $lesson_type = $lesson['type'] ?? 'text';
                    $lesson_content = $lesson['content'] ?? '';
                    if (@$conn->query("SELECT 1 FROM course_lessons LIMIT 1")) {
                        $les_stmt = $conn->prepare("INSERT INTO course_lessons (course_id, module_id, title, lesson_type, content, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
                        $les_stmt->bind_param('iisssi', $course_id, $module_id, $lesson_title, $lesson_type, $lesson_content, $lesson_order);
                        $les_stmt->execute();
                    }
                    $lesson_order++;
                }
            }
            $module_order++;
        }
    }

    $conn->commit();
    echo json_encode(['success' => true, 'course_id' => $course_id]);
} catch (Throwable $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error', 'error' => $e->getMessage()]);
}
