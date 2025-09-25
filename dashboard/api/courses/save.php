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
    $trainer_id = $_SESSION['user_id'];
    $course_id = isset($_POST['course_id']) ? (int)$_POST['course_id'] : null;
    $action = $_POST['action'] ?? 'draft';

    // Validate required fields
    $required_fields = ['course_title', 'description', 'category', 'level', 'price'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("$field is required");
        }
    }

    // Sanitize input data
    $course_title = trim($_POST['course_title']);
    $description = trim($_POST['description']);
    $category = $_POST['category'];
    $level = $_POST['level'];
    $price = (float)$_POST['price'];
    $max_students = !empty($_POST['max_students']) ? (int)$_POST['max_students'] : null;
    $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;

    // Set status based on action
    $status = ($action === 'publish') ? 'Published' : 'Draft';
    $visibility = $_POST['visibility'] ?? 'Public';

    // Validate dates
    if ($start_date && $end_date && strtotime($start_date) > strtotime($end_date)) {
        throw new Exception("End date must be after start date");
    }

    // Handle file upload if present
    $image_path = null;
    if (isset($_FILES['course_image']) && $_FILES['course_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../../uploads/courses/';

        // Create directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_info = pathinfo($_FILES['course_image']['name']);
        $file_extension = strtolower($file_info['extension']);

        // Validate file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_extension, $allowed_types)) {
            throw new Exception("Invalid file type. Only JPG, PNG, and GIF are allowed.");
        }

        // Validate file size (2MB max)
        if ($_FILES['course_image']['size'] > 2 * 1024 * 1024) {
            throw new Exception("File size too large. Maximum 2MB allowed.");
        }

        // Generate unique filename
        $filename = 'course_' . time() . '_' . uniqid() . '.' . $file_extension;
        $image_path = $upload_dir . $filename;

        if (!move_uploaded_file($_FILES['course_image']['tmp_name'], $image_path)) {
            throw new Exception("Failed to upload image");
        }

        // Store relative path for database
        $image_path = 'uploads/courses/' . $filename;
    }

    if ($course_id) {
        // Update existing course
        // First verify the course belongs to this trainer
        $verify_stmt = $conn->prepare("SELECT course_id FROM courses WHERE course_id = ? AND teacher_id = ?");
        $verify_stmt->bind_param("ii", $course_id, $trainer_id);
        $verify_stmt->execute();

        if ($verify_stmt->get_result()->num_rows === 0) {
            throw new Exception("Course not found or access denied");
        }

        // Build update query
        $update_fields = [
            'course_title = ?',
            'description = ?',
            'category = ?',
            'level = ?',
            'price = ?',
            'status = ?',
            'visibility = ?',
            'max_students = ?',
            'start_date = ?',
            'end_date = ?'
        ];

        $params = [$course_title, $description, $category, $level, $price, $status, $visibility, $max_students, $start_date, $end_date];
        $types = 'ssssdssiis';

        // Add image update if new image was uploaded
        if ($image_path) {
            $update_fields[] = 'image_url = ?';
            $params[] = $image_path;
            $types .= 's';
        }

        $params[] = $course_id;
        $types .= 'i';

        $sql = "UPDATE courses SET " . implode(', ', $update_fields) . " WHERE course_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {
            throw new Exception("Failed to update course");
        }

        $response = [
            'success' => true,
            'message' => 'Course updated successfully',
            'course_id' => $course_id,
            'action' => $action
        ];
    } else {
        // Create new course
        $sql = "INSERT INTO courses (
                    teacher_id,
                    course_title,
                    description,
                    category,
                    level,
                    image_url,
                    price,
                    status,
                    visibility,
                    max_students,
                    start_date,
                    end_date,
                    created_at
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()
                )";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "isssssdsssss",
            $trainer_id,
            $course_title,
            $description,
            $category,
            $level,
            $image_path,
            $price,
            $status,
            $visibility,
            $max_students,
            $start_date,
            $end_date
        );

        if (!$stmt->execute()) {
            throw new Exception("Failed to create course");
        }

        $course_id = $conn->insert_id;

        $response = [
            'success' => true,
            'message' => 'Course created successfully',
            'course_id' => $course_id,
            'action' => $action
        ];
    }

    // Set success message in session for redirect
    if ($action === 'publish') {
        $_SESSION['success_message'] = 'Course published successfully!';
    } else {
        $_SESSION['success_message'] = 'Course saved as draft successfully!';
    }

    // Return JSON response for AJAX or redirect for form submission
    if (isset($_POST['ajax']) && $_POST['ajax'] === '1') {
        echo json_encode($response);
    } else {
        // Redirect to course management page
        header("Location: ../../trainer/courses.php");
        exit();
    }
} catch (Exception $e) {
    $error_response = [
        'success' => false,
        'error' => $e->getMessage()
    ];

    if (isset($_POST['ajax']) && $_POST['ajax'] === '1') {
        http_response_code(400);
        echo json_encode($error_response);
    } else {
        $_SESSION['error'] = $e->getMessage();
        header("Location: ../../trainer/course-builder.php" . ($course_id ? "?id=$course_id" : ""));
        exit();
    }
}
