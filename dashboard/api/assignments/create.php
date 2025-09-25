<?php
require_once __DIR__ . '/../_bootstrap.php';

global $conn;
$payload = read_json_body();

// Validate minimal fields
if (empty($payload['title']) || empty($payload['course'])) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Title and course are required']);
    exit();
}

$teacher_user_id = (int)$_SESSION['user_id'];
$course_id = (int)$payload['course'];
$title = $payload['title'];
$description = $payload['description'] ?? '';
$due_date = $payload['dueDate'] ?? null;

$conn->begin_transaction();
try {
    // Create assignment (assignments table assumes course-level)
    $assign_stmt = $conn->prepare("INSERT INTO assignments (course_id, title, description, due_date) VALUES (?, ?, ?, ?)");
    $assign_stmt->bind_param('isss', $course_id, $title, $description, $due_date);
    $assign_stmt->execute();
    $assignment_id = (int)$conn->insert_id;

    // If question tables exist, save quiz structure
    if (!empty($payload['questions']) && @($conn->query("SELECT 1 FROM assignment_questions LIMIT 1"))) {
        $order = 1;
        foreach ($payload['questions'] as $q) {
            $q_text = $q['text'] ?? '';
            $q_points = isset($q['points']) ? (int)$q['points'] : 1;
            $q_expl = $q['explanation'] ?? null;
            $q_stmt = $conn->prepare("INSERT INTO assignment_questions (assignment_id, question_text, points, explanation, sort_order) VALUES (?, ?, ?, ?, ?)");
            $q_stmt->bind_param('isisi', $assignment_id, $q_text, $q_points, $q_expl, $order);
            $q_stmt->execute();
            $question_id = (int)$conn->insert_id;

            if (!empty($q['options']) && @($conn->query("SELECT 1 FROM assignment_options LIMIT 1"))) {
                for ($i = 0; $i < count($q['options']); $i++) {
                    $opt_text = $q['options'][$i];
                    $is_correct = (isset($q['correctAnswer']) && $q['correctAnswer'] === $i) ? 1 : 0;
                    $o_stmt = $conn->prepare("INSERT INTO assignment_options (question_id, option_text, is_correct, sort_order) VALUES (?, ?, ?, ?)");
                    $o_stmt->bind_param('isii', $question_id, $opt_text, $is_correct, $i + 1);
                    $o_stmt->execute();
                }
            }
            $order++;
        }
    }

    $conn->commit();
    echo json_encode(['success' => true, 'assignment_id' => $assignment_id]);
} catch (Throwable $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error', 'error' => $e->getMessage()]);
}
