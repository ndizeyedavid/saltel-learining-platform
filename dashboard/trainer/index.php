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
    <div class="flex overflow-hidden h-screen">
        <?php include '../../components/Trainer-Sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="flex overflow-hidden flex-col flex-1">
            <?php include '../../components/Trainer-Header.php'; ?>

            <!-- Main Content -->
            <main class="overflow-y-auto flex-1 p-6">
                <!-- Welcome Section -->
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
                    <p class="mt-1 text-sm text-gray-600">Here's what's happening with your courses today.</p>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Active Courses -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-blue-100 text-blue-600">
                                <i class="fas fa-graduation-cap text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Active Courses</h3>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $active_courses; ?></p>
                                <p class="text-xs text-gray-400 mt-1">Published & Draft</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Students -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-green-100 text-green-600">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Total Students</h3>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $total_students; ?></p>
                                <p class="text-xs text-gray-400 mt-1">Enrolled students</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Assignments -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-yellow-100 text-yellow-600">
                                <i class="fas fa-clipboard-list text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Active Assignments</h3>
                                <p class="text-2xl font-semibold text-gray-900"><?php echo $pending_assignments; ?></p>
                                <p class="text-xs text-gray-400 mt-1">Due soon</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Revenue -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-purple-100 text-purple-600">
                                <i class="fas fa-dollar-sign text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Total Revenue</h3>
                                <p class="text-2xl font-semibold text-gray-900">$<?php echo number_format($total_revenue, 2); ?></p>
                                <p class="text-xs text-gray-400 mt-1">From enrollments</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Dashboard Content -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Recent Courses -->
                    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Recent Courses</h3>
                                <a href="courses.php" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View all</a>
                            </div>
                        </div>
                        <div class="p-6">
                            <?php if (!empty($recent_courses)): ?>
                                <div class="space-y-4">
                                    <?php foreach ($recent_courses as $course): ?>
                                        <div class="flex items-center justify-between p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-900"><?php echo htmlspecialchars($course['course_title']); ?></h4>
                                                <p class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars(substr($course['description'] ?? '', 0, 100)); ?>...</p>
                                                <div class="flex items-center mt-2 space-x-4 text-xs text-gray-500">
                                                    <span class="flex items-center">
                                                        <i class="fas fa-users mr-1"></i>
                                                        <?php echo $course['enrolled_count']; ?> students
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="fas fa-dollar-sign mr-1"></i>
                                                        $<?php echo number_format($course['price'], 2); ?>
                                                    </span>
                                                    <span class="px-2 py-1 rounded-full text-xs font-medium 
                                                        <?php echo $course['status'] === 'Published' ? 'bg-green-100 text-green-800' : 
                                                                  ($course['status'] === 'Draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'); ?>">
                                                        <?php echo $course['status']; ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 ml-4">
                                                <a href="course-edit.php?id=<?php echo $course['course_id']; ?>" 
                                                   class="p-2 text-gray-400 hover:text-blue-600 transition-colors">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="course-content.php?id=<?php echo $course['course_id']; ?>" 
                                                   class="p-2 text-gray-400 hover:text-green-600 transition-colors">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-8">
                                    <i class="fas fa-graduation-cap text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">No courses created yet</p>
                                    <a href="course-builder.php" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        Create Your First Course
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Recent Submissions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Recent Submissions</h3>
                                <a href="submissions.php" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View all</a>
                            </div>
                        </div>
                        <div class="p-6">
                            <?php if (!empty($recent_submissions)): ?>
                                <div class="space-y-4">
                                    <?php foreach ($recent_submissions as $submission): ?>
                                        <div class="flex items-start space-x-3 p-3 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-file-alt text-blue-600 text-sm"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($submission['first_name'] . ' ' . $submission['last_name']); ?>
                                                </p>
                                                <p class="text-xs text-gray-600"><?php echo htmlspecialchars($submission['assignment_title']); ?></p>
                                                <p class="text-xs text-gray-500 mt-1"><?php echo date('M j, Y', strtotime($submission['submitted_at'])); ?></p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <?php if ($submission['grade'] !== null): ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <?php echo $submission['grade']; ?>%
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Pending
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-8">
                                    <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">No submissions yet</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="course-builder.php" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors group">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-blue-200 transition-colors">
                                <i class="fas fa-plus text-blue-600 text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Create Course</span>
                        </a>
                        
                        <a href="assignment-builder.php" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 transition-colors group">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-green-200 transition-colors">
                                <i class="fas fa-tasks text-green-600 text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-900">New Assignment</span>
                        </a>
                        
                        <a href="submissions.php" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-yellow-50 hover:border-yellow-300 transition-colors group">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-yellow-200 transition-colors">
                                <i class="fas fa-clipboard-check text-yellow-600 text-xl"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Grade Submissions</span>
                        </a>
                        
                        <a href="reports.php" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors group">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-purple-200 transition-colors">
                                <i class="fas fa-chart-bar text-purple-600 text-xl"></i>
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