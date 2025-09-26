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

    // Get assignments for courses the student is enrolled in
    $assignments_query = "SELECT 
        a.assignment_id,
        a.title,
        a.description,
        a.due_date,
        c.course_title,
        c.course_id,
        s.submission_id,
        s.submitted_at,
        s.grade,
        s.feedback,
        CASE 
            WHEN s.submission_id IS NOT NULL THEN 'submitted'
            WHEN a.due_date < CURDATE() THEN 'overdue'
            WHEN DATEDIFF(a.due_date, CURDATE()) <= 3 THEN 'due_soon'
            ELSE 'pending'
        END as status,
        CASE 
            WHEN a.due_date >= CURDATE() THEN DATEDIFF(a.due_date, CURDATE())
            ELSE DATEDIFF(CURDATE(), a.due_date) * -1
        END as days_remaining
    FROM assignments a
    JOIN courses c ON a.course_id = c.course_id
    JOIN enrollments e ON c.course_id = e.course_id AND e.student_id = ? AND e.payment_status = 'Paid'
    LEFT JOIN submissions s ON a.assignment_id = s.assignment_id AND s.student_id = ?
    ORDER BY 
        CASE 
            WHEN s.submission_id IS NOT NULL THEN 4
            WHEN a.due_date < CURDATE() THEN 1
            WHEN DATEDIFF(a.due_date, CURDATE()) <= 3 THEN 2
            ELSE 3
        END,
        a.due_date ASC";

    $stmt = $conn->prepare($assignments_query);
    $stmt->bind_param("ii", $student_id, $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $assignments = $result->fetch_all(MYSQLI_ASSOC);

    // Format the data
    foreach ($assignments as &$assignment) {
        $assignment['due_date_formatted'] = $assignment['due_date'] ? date('F j, Y', strtotime($assignment['due_date'])) : null;
        $assignment['submitted_at_formatted'] = $assignment['submitted_at'] ? date('F j, Y g:i A', strtotime($assignment['submitted_at'])) : null;

        // Status display text
        switch ($assignment['status']) {
            case 'submitted':
                $assignment['status_text'] = 'Submitted';
                $assignment['status_class'] = 'text-green-800 bg-green-100 border-green-200';
                $assignment['status_icon'] = 'fa-check-circle';
                break;
            case 'overdue':
                $assignment['status_text'] = 'Overdue';
                $assignment['status_class'] = 'text-red-800 bg-red-100 border-red-200';
                $assignment['status_icon'] = 'fa-exclamation-triangle';
                break;
            case 'due_soon':
                $assignment['status_text'] = 'Due Soon';
                $assignment['status_class'] = 'text-orange-800 bg-orange-100 border-orange-200';
                $assignment['status_icon'] = 'fa-clock';
                break;
            default:
                $assignment['status_text'] = 'Pending';
                $assignment['status_class'] = 'text-blue-800 bg-blue-100 border-blue-200';
                $assignment['status_icon'] = 'fa-clock';
        }

        // Days remaining text
        if ($assignment['days_remaining'] > 0) {
            $assignment['days_text'] = $assignment['days_remaining'] . ' days remaining';
            $assignment['days_class'] = 'text-blue-600';
        } elseif ($assignment['days_remaining'] == 0) {
            $assignment['days_text'] = 'Due today';
            $assignment['days_class'] = 'text-orange-600';
        } else {
            $assignment['days_text'] = 'Overdue by ' . abs($assignment['days_remaining']) . ' days';
            $assignment['days_class'] = 'text-red-600';
        }
    }

    echo json_encode([
        'success' => true,
        'assignments' => $assignments
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    error_log($e);
}
