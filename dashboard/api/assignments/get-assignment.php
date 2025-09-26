<?php
session_start();
require_once '../../../include/connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get assignment ID from URL parameter
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Assignment ID is required']);
    exit;
}

$assignment_id = (int)$_GET['id'];

try {
    // Get student ID
    $student_query = "SELECT student_id FROM students WHERE user_id = ?";
    $stmt = $conn->prepare($student_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    
    if (!$student) {
        throw new Exception('Student profile not found');
    }
    
    $student_id = $student['student_id'];
    
    // Get assignment details with enrollment check
    $assignment_query = "SELECT 
        a.assignment_id,
        a.title,
        a.description,
        a.due_date,
        c.course_title,
        c.course_id,
        s.submission_id,
        s.submitted_at,
        s.grade,
        s.feedback
    FROM assignments a
    JOIN courses c ON a.course_id = c.course_id
    JOIN enrollments e ON c.course_id = e.course_id AND e.student_id = ? AND e.payment_status = 'Paid'
    LEFT JOIN submissions s ON a.assignment_id = s.assignment_id AND s.student_id = ?
    WHERE a.assignment_id = ?";
    
    $stmt = $conn->prepare($assignment_query);
    $stmt->bind_param("iii", $student_id, $student_id, $assignment_id);
    $stmt->execute();
    $assignment = $stmt->get_result()->fetch_assoc();
    
    if (!$assignment) {
        throw new Exception('Assignment not found or access denied');
    }
    
    // Check if already submitted
    if ($assignment['submission_id']) {
        echo json_encode([
            'success' => false,
            'message' => 'Assignment already submitted',
            'submitted' => true,
            'submission_date' => $assignment['submitted_at'],
            'grade' => $assignment['grade'],
            'feedback' => $assignment['feedback']
        ]);
        exit;
    }
    
    // Get assignment questions
    $questions_query = "SELECT 
        aq.question_id,
        aq.question_text,
        aq.points,
        aq.explanation,
        aq.sort_order,
        ao.option_id,
        ao.option_text,
        ao.is_correct,
        ao.sort_order as option_order
    FROM assignment_questions aq
    LEFT JOIN assignment_options ao ON aq.question_id = ao.question_id
    WHERE aq.assignment_id = ?
    ORDER BY aq.sort_order ASC, ao.sort_order ASC";
    
    $stmt = $conn->prepare($questions_query);
    $stmt->bind_param("i", $assignment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $questions = [];
    while ($row = $result->fetch_assoc()) {
        if (!isset($questions[$row['question_id']])) {
            $questions[$row['question_id']] = [
                'question_id' => $row['question_id'],
                'question_text' => $row['question_text'],
                'points' => $row['points'],
                'explanation' => $row['explanation'],
                'sort_order' => $row['sort_order'],
                'options' => []
            ];
        }
        
        if ($row['option_id']) {
            $questions[$row['question_id']]['options'][] = [
                'option_id' => $row['option_id'],
                'option_text' => $row['option_text'],
                'is_correct' => (bool)$row['is_correct']
            ];
        }
    }
    
    // Convert to indexed array and sort by sort_order
    $questions = array_values($questions);
    usort($questions, function($a, $b) {
        return $a['sort_order'] - $b['sort_order'];
    });
    
    // Calculate assignment metadata
    $total_questions = count($questions);
    $total_points = array_sum(array_column($questions, 'points'));
    $time_limit = 30; // Default 30 minutes, could be stored in database
    
    // Format assignment data
    $assignment_data = [
        'assignment_id' => $assignment['assignment_id'],
        'title' => $assignment['title'],
        'description' => $assignment['description'],
        'course_title' => $assignment['course_title'],
        'due_date' => $assignment['due_date'],
        'due_date_formatted' => $assignment['due_date'] ? date('F j, Y', strtotime($assignment['due_date'])) : null,
        'total_questions' => $total_questions,
        'total_points' => $total_points,
        'time_limit' => $time_limit,
        'passing_score' => 75, // Could be stored in database
        'questions' => $questions
    ];
    
    echo json_encode([
        'success' => true,
        'assignment' => $assignment_data
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    error_log($e);
}
