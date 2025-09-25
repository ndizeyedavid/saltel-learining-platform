<?php
// Prevent any output before JSON
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require_once '../../../include/connect.php';
require_once '../../../include/trainer-guard.php';

// Clear any output that might have been generated
ob_clean();
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGetCourses();
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function handleGetCourses()
{
    global $conn;

    $teacher_id = $_SESSION['user_id'];

    try {
        // Get courses for the trainer
        $stmt = $conn->prepare("
            SELECT 
                course_id,
                course_title,
                description,
                price,
                level,
                category,
                status,
                created_at,
                (SELECT COUNT(*) FROM enrollments WHERE course_id = c.course_id) as enrolled_count
            FROM courses c
            WHERE teacher_id = ?
            ORDER BY created_at DESC
        ");

        $stmt->bind_param('i', $teacher_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $courses = [];
        while ($row = $result->fetch_assoc()) {
            $course = [
                'course_id' => (int)$row['course_id'],
                'course_title' => $row['course_title'],
                'description' => $row['description'],
                'price' => (float)$row['price'],
                'level' => $row['level'],
                'category' => $row['category'],
                'status' => $row['status'],
                'created_at' => $row['created_at'],
                'enrolled_count' => (int)$row['enrolled_count']
            ];

            $courses[] = $course;
        }

        echo json_encode(['courses' => $courses]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch courses']);
    }
}
