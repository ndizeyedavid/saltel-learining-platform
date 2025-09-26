<?php
session_start();
require_once '../../../include/connect.php';

header('Content-Type: application/json');

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
$certificate_id = $input['certificate_id'] ?? '';

if (empty($certificate_id)) {
    echo json_encode([
        'success' => false,
        'message' => 'Certificate ID required'
    ]);
    exit;
}

// Verify certificate exists and get details
$verify_query = "SELECT c.*, co.course_name, s.first_name, s.last_name, u.email
                FROM certificates c 
                JOIN courses co ON c.course_id = co.course_id 
                JOIN students s ON c.student_id = s.student_id
                JOIN users u ON s.user_id = u.user_id
                WHERE c.certificate_id = ?";

$stmt = $conn->prepare($verify_query);
$stmt->bind_param("s", $certificate_id);
$stmt->execute();
$certificate = $stmt->get_result()->fetch_assoc();

if (!$certificate) {
    echo json_encode([
        'success' => false,
        'message' => 'Certificate not found or invalid'
    ]);
    exit;
}

// Return verification details
echo json_encode([
    'success' => true,
    'certificate_id' => $certificate['certificate_id'],
    'course_name' => $certificate['course_name'],
    'student_name' => $certificate['first_name'] . ' ' . $certificate['last_name'],
    'completion_date' => date('F j, Y', strtotime($certificate['completion_date'])),
    'issued_date' => date('F j, Y', strtotime($certificate['issued_date'])),
    'status' => 'verified'
]);
?>
