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

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['assignment_id']) || !isset($input['answers'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

$assignment_id = (int)$input['assignment_id'];
$answers = $input['answers'];
$time_spent = isset($input['time_spent']) ? (int)$input['time_spent'] : 0;

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

    // Check if assignment exists and student is enrolled
    $assignment_query = "SELECT a.assignment_id, a.title
                        FROM assignments a
                        JOIN courses c ON a.course_id = c.course_id
                        JOIN enrollments e ON c.course_id = e.course_id 
                        WHERE a.assignment_id = ? AND e.student_id = ? AND e.payment_status = 'Paid'";

    $stmt = $conn->prepare($assignment_query);
    $stmt->bind_param("ii", $assignment_id, $student_id);
    $stmt->execute();
    $assignment = $stmt->get_result()->fetch_assoc();

    if (!$assignment) {
        throw new Exception('Assignment not found or access denied');
    }

    // Check if already submitted
    $existing_query = "SELECT submission_id FROM submissions WHERE assignment_id = ? AND student_id = ?";
    $stmt = $conn->prepare($existing_query);
    $stmt->bind_param("ii", $assignment_id, $student_id);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();

    if ($existing) {
        throw new Exception('Assignment already submitted');
    }

    // Get all questions and their options for this assignment
    $questions_query = "SELECT 
        aq.question_id,
        aq.points,
        ao.option_id,
        ao.option_text,
        ao.is_correct,
        ao.sort_order
    FROM assignment_questions aq
    JOIN assignment_options ao ON aq.question_id = ao.question_id
    WHERE aq.assignment_id = ?
    ORDER BY aq.question_id, ao.sort_order";

    $stmt = $conn->prepare($questions_query);
    $stmt->bind_param("i", $assignment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $questions_data = [];
    $total_points = 0;

    while ($row = $result->fetch_assoc()) {
        $question_id = $row['question_id'];
        
        if (!isset($questions_data[$question_id])) {
            $questions_data[$question_id] = [
                'points' => $row['points'],
                'options' => []
            ];
            $total_points += $row['points'];
        }
        
        $questions_data[$question_id]['options'][] = [
            'option_id' => $row['option_id'],
            'option_text' => $row['option_text'],
            'is_correct' => $row['is_correct'],
            'sort_order' => $row['sort_order']
        ];
    }

    // Calculate score
    $earned_points = 0;
    $correct_count = 0;
    $total_questions = count($questions_data);

    // Process each answer
    foreach ($answers as $answer) {
        $question_id = $answer['question_id'];
        $selected_option_index = $answer['selected_option'];
        
        if (isset($questions_data[$question_id])) {
            $options = $questions_data[$question_id]['options'];
            
            // Check if the selected option index is valid and corresponds to a correct answer
            if (isset($options[$selected_option_index]) && $options[$selected_option_index]['is_correct'] == 1) {
                $earned_points += $questions_data[$question_id]['points'];
                $correct_count++;
            }
        }
    }

    // Calculate percentage
    $percentage = $total_questions > 0 ? round(($correct_count / $total_questions) * 100, 2) : 0;

    // Create submission record
    $submission_query = "INSERT INTO submissions (assignment_id, student_id, grade, submitted_at) 
                        VALUES (?, ?, ?, NOW())";

    $stmt = $conn->prepare($submission_query);
    $stmt->bind_param("iid", $assignment_id, $student_id, $percentage);
    $stmt->execute();

    $submission_id = $conn->insert_id;

    // Store individual answers (optional - create assignment_answers table if needed)
    // For now, we'll just return the results

    echo json_encode([
        'success' => true,
        'submission_id' => $submission_id,
        'results' => [
            'total_questions' => $total_questions,
            'correct_answers' => $correct_count,
            'incorrect_answers' => $total_questions - $correct_count,
            'percentage' => $percentage,
            'grade' => $percentage,
            'passed' => $percentage >= 75, // 75% passing score
            'time_spent' => $time_spent
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    error_log($e);
}
