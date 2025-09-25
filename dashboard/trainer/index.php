<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Trainer Dashboard</title>
    <?php
    include '../../include/trainer-guard.php';
    include '../../include/connect.php';

    // Get trainer statistics
    $trainer_id = $_SESSION['user_id'];

    // Get active courses count
    $active_courses_query = "SELECT COUNT(*) as count FROM courses WHERE teacher_id = ? AND status != 'Archived'";
    $stmt = $conn->prepare($active_courses_query);
    $stmt->bind_param("i", $trainer_id);
    $stmt->execute();
    $active_courses = $stmt->get_result()->fetch_assoc()['count'];

    // Get total students enrolled in trainer's courses
    $total_students_query = "SELECT COUNT(DISTINCT e.student_id) as count 
                            FROM enrollments e 
                            JOIN courses c ON e.course_id = c.course_id 
                            WHERE c.teacher_id = ?";
    $stmt = $conn->prepare($total_students_query);
    $stmt->bind_param("i", $trainer_id);
    $stmt->execute();
    $total_students = $stmt->get_result()->fetch_assoc()['count'];

    // Get pending assignments count
    $pending_assignments_query = "SELECT COUNT(*) as count 
                                 FROM assignments a 
                                 JOIN courses c ON a.course_id = c.course_id 
                                 WHERE c.teacher_id = ? AND a.due_date >= CURDATE()";
    $stmt = $conn->prepare($pending_assignments_query);
    $stmt->bind_param("i", $trainer_id);
    $stmt->execute();
    $pending_assignments = $stmt->get_result()->fetch_assoc()['count'];

    // Get total revenue (assuming all enrollments are paid)
    $revenue_query = "SELECT SUM(c.price) as revenue 
                     FROM enrollments e 
                     JOIN courses c ON e.course_id = c.course_id 
                     WHERE c.teacher_id = ? AND e.payment_status = 'Paid'";
    $stmt = $conn->prepare($revenue_query);
    $stmt->bind_param("i", $trainer_id);
    $stmt->execute();
    $revenue_result = $stmt->get_result()->fetch_assoc();
    $total_revenue = $revenue_result['revenue'] ?? 0;

    // Get recent courses
    $recent_courses_query = "SELECT course_id, course_title, description, price, status, created_at,
                            (SELECT COUNT(*) FROM enrollments WHERE course_id = c.course_id) as enrolled_count
                            FROM courses c 
                            WHERE teacher_id = ? 
                            ORDER BY created_at DESC 
                            LIMIT 5";
    $stmt = $conn->prepare($recent_courses_query);
    $stmt->bind_param("i", $trainer_id);
    $stmt->execute();
    $recent_courses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Get recent submissions for grading
    $recent_submissions_query = "SELECT s.submission_id, s.submitted_at, s.grade, 
                                u.first_name, u.last_name, a.title as assignment_title, c.course_title
                                FROM submissions s
                                JOIN assignments a ON s.assignment_id = a.assignment_id
                                JOIN courses c ON a.course_id = c.course_id
                                JOIN students st ON s.student_id = st.student_id
                                JOIN users u ON st.user_id = u.user_id
                                WHERE c.teacher_id = ?
                                ORDER BY s.submitted_at DESC
                                LIMIT 5";
    $stmt = $conn->prepare($recent_submissions_query);
    $stmt->bind_param("i", $trainer_id);
    $stmt->execute();
    $recent_submissions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    include '../../include/trainer-imports.php';
    ?>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainer-Sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <?php include '../../components/Trainer-Header.php'; ?>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-y-auto">
                <!-- Welcome Section -->
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
                    <p class="mt-1 text-sm text-gray-600">Here's what's happening with your courses today.</p>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Active Courses -->
                    <div class="p-6 transition-shadow bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-12 h-12 text-blue-600 bg-blue-100 rounded-lg">
                                <i class="text-xl fas fa-graduation-cap"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Active Courses</h3>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $active_courses; ?></p>
                                <p class="mt-1 text-xs text-gray-400">Published & Draft</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Students -->
                    <div class="p-6 transition-shadow bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-12 h-12 text-green-600 bg-green-100 rounded-lg">
                                <i class="text-xl fas fa-users"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Total Students</h3>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $total_students; ?></p>
                                <p class="mt-1 text-xs text-gray-400">Enrolled students</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Assignments -->
                    <div class="p-6 transition-shadow bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-12 h-12 text-yellow-600 bg-yellow-100 rounded-lg">
                                <i class="text-xl fas fa-clipboard-list"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Active Assignments</h3>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $pending_assignments; ?></p>
                                <p class="mt-1 text-xs text-gray-400">Due soon</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Revenue -->
                    <div class="p-6 transition-shadow bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-12 h-12 text-purple-600 bg-purple-100 rounded-lg">
                                <i class="text-xl fas fa-dollar-sign"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Total Revenue</h3>
                                <p class="text-2xl font-semibold text-gray-900">$<?php echo number_format($total_revenue, 2); ?></p>
                                <p class="mt-1 text-xs text-gray-400">From enrollments</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Dashboard Content -->
                <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
                    <!-- Recent Courses -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm lg:col-span-2">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Recent Courses</h3>
                                <a href="courses.php" class="text-sm font-medium text-blue-600 hover:text-blue-700">View all</a>
                            </div>
                        </div>
                        <div class="p-6">
                            <?php if (!empty($recent_courses)): ?>
                                <div class="space-y-4">
                                    <?php foreach ($recent_courses as $course): ?>
                                        <div class="flex items-center justify-between p-4 transition-colors border border-gray-100 rounded-lg hover:bg-gray-50">
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-900"><?php echo htmlspecialchars($course['course_title']); ?></h4>
                                                <p class="mt-1 text-sm text-gray-600"><?php echo htmlspecialchars(substr($course['description'] ?? '', 0, 100)); ?>...</p>
                                                <div class="flex items-center mt-2 space-x-4 text-xs text-gray-500">
                                                    <span class="flex items-center">
                                                        <i class="mr-1 fas fa-users"></i>
                                                        <?php echo $course['enrolled_count']; ?> students
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="mr-1 fas fa-dollar-sign"></i>
                                                        $<?php echo number_format($course['price'], 2); ?>
                                                    </span>
                                                    <span class="px-2 py-1 rounded-full text-xs font-medium 
                                                        <?php echo $course['status'] === 'Published' ? 'bg-green-100 text-green-800' : ($course['status'] === 'Draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'); ?>">
                                                        <?php echo $course['status']; ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex items-center ml-4 space-x-2">
                                                <a href="course-content.php?course_id=<?php echo $course['course_id']; ?>"
                                                    class="p-2 text-gray-400 transition-colors hover:text-blue-600">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="course-content.php?id=<?php echo $course['course_id']; ?>"
                                                    class="p-2 text-gray-400 transition-colors hover:text-green-600">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="py-8 text-center">
                                    <i class="mb-4 text-4xl text-gray-300 fas fa-graduation-cap"></i>
                                    <p class="text-gray-500">No courses created yet</p>
                                    <a href="course-builder.php" class="inline-block px-4 py-2 mt-4 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                        Create Your First Course
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Recent Submissions -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Recent Submissions</h3>
                                <a href="submissions.php" class="text-sm font-medium text-blue-600 hover:text-blue-700">View all</a>
                            </div>
                        </div>
                        <div class="p-6">
                            <?php if (!empty($recent_submissions)): ?>
                                <div class="space-y-4">
                                    <?php foreach ($recent_submissions as $submission): ?>
                                        <div class="flex items-start p-3 space-x-3 transition-colors border border-gray-100 rounded-lg hover:bg-gray-50">
                                            <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full">
                                                <i class="text-sm text-blue-600 fas fa-file-alt"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($submission['first_name'] . ' ' . $submission['last_name']); ?>
                                                </p>
                                                <p class="text-xs text-gray-600"><?php echo htmlspecialchars($submission['assignment_title']); ?></p>
                                                <p class="mt-1 text-xs text-gray-500"><?php echo date('M j, Y', strtotime($submission['submitted_at'])); ?></p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <?php if ($submission['grade'] !== null): ?>
                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                                        <?php echo $submission['grade']; ?>%
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">
                                                        Pending
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="py-8 text-center">
                                    <i class="mb-4 text-4xl text-gray-300 fas fa-clipboard-list"></i>
                                    <p class="text-gray-500">No submissions yet</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Quick Actions</h3>
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                        <a href="course-builder.php" class="flex flex-col items-center p-4 transition-colors border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 group">
                            <div class="flex items-center justify-center w-12 h-12 mb-3 transition-colors bg-blue-100 rounded-lg group-hover:bg-blue-200">
                                <i class="text-xl text-blue-600 fas fa-plus"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Create Course</span>
                        </a>

                        <a href="assignment-builder.php" class="flex flex-col items-center p-4 transition-colors border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 group">
                            <div class="flex items-center justify-center w-12 h-12 mb-3 transition-colors bg-green-100 rounded-lg group-hover:bg-green-200">
                                <i class="text-xl text-green-600 fas fa-tasks"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-900">New Assignment</span>
                        </a>

                        <a href="submissions.php" class="flex flex-col items-center p-4 transition-colors border border-gray-200 rounded-lg hover:bg-yellow-50 hover:border-yellow-300 group">
                            <div class="flex items-center justify-center w-12 h-12 mb-3 transition-colors bg-yellow-100 rounded-lg group-hover:bg-yellow-200">
                                <i class="text-xl text-yellow-600 fas fa-clipboard-check"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Grade Submissions</span>
                        </a>

                        <a href="reports.php" class="flex flex-col items-center p-4 transition-colors border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 group">
                            <div class="flex items-center justify-center w-12 h-12 mb-3 transition-colors bg-purple-100 rounded-lg group-hover:bg-purple-200">
                                <i class="text-xl text-purple-600 fas fa-chart-bar"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-900">View Reports</span>
                        </a>
                    </div>
                </div>

            </main>
        </div>
    </div>
</body>

</html>