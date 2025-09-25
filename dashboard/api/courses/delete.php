<?php
session_start();
require_once '../../../include/connect.php';
require_once '../../../include/trainer-guard.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['course_id'])) {
        throw new Exception('Course ID is required');
    }
    
    $course_id = (int)$input['course_id'];
    $trainer_id = $_SESSION['user_id'];
    
    // Verify the course belongs to this trainer
    $verify_stmt = $conn->prepare("SELECT course_id, image_url FROM courses WHERE course_id = ? AND teacher_id = ?");
    $verify_stmt->bind_param("ii", $course_id, $trainer_id);
    $verify_stmt->execute();
    $result = $verify_stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Course not found or access denied");
    }
    
    $course_data = $result->fetch_assoc();
    
    // Check if there are any enrollments for this course
    $enrollment_check = $conn->prepare("SELECT COUNT(*) as count FROM enrollments WHERE course_id = ?");
    $enrollment_check->bind_param("i", $course_id);
    $enrollment_check->execute();
    $enrollment_count = $enrollment_check->get_result()->fetch_assoc()['count'];
    
    if ($enrollment_count > 0) {
        throw new Exception("Cannot delete course with active enrollments. Please archive the course instead.");
    }
    
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        // Delete related records first (to maintain referential integrity)
        
        // Delete course content
        $delete_content = $conn->prepare("DELETE FROM course_content WHERE course_id = ?");
        $delete_content->bind_param("i", $course_id);
        $delete_content->execute();
        
        // Delete assignments and their submissions
        $assignments_query = $conn->prepare("SELECT assignment_id FROM assignments WHERE course_id = ?");
        $assignments_query->bind_param("i", $course_id);
        $assignments_query->execute();
        $assignments_result = $assignments_query->get_result();
        
        while ($assignment = $assignments_result->fetch_assoc()) {
            // Delete submissions for this assignment
            $delete_submissions = $conn->prepare("DELETE FROM submissions WHERE assignment_id = ?");
            $delete_submissions->bind_param("i", $assignment['assignment_id']);
            $delete_submissions->execute();
        }
        
        // Delete assignments
        $delete_assignments = $conn->prepare("DELETE FROM assignments WHERE course_id = ?");
        $delete_assignments->bind_param("i", $course_id);
        $delete_assignments->execute();
        
        // Delete certificates
        $delete_certificates = $conn->prepare("DELETE FROM certificates WHERE course_id = ?");
        $delete_certificates->bind_param("i", $course_id);
        $delete_certificates->execute();
        
        // Finally, delete the course
        $delete_course = $conn->prepare("DELETE FROM courses WHERE course_id = ? AND teacher_id = ?");
        $delete_course->bind_param("ii", $course_id, $trainer_id);
        
        if (!$delete_course->execute()) {
            throw new Exception("Failed to delete course");
        }
        
        // Delete course image file if it exists
        if ($course_data['image_url'] && file_exists('../../../' . $course_data['image_url'])) {
            unlink('../../../' . $course_data['image_url']);
        }
        
        // Commit transaction
        $conn->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Course deleted successfully'
        ]);
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        throw $e;
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
