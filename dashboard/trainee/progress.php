<?php
session_start();
require_once '../../include/connect.php';

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get student ID
$student_query = "SELECT student_id FROM students WHERE user_id = ?";
$stmt = $conn->prepare($student_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$student_result = $stmt->get_result()->fetch_assoc();

if (!$student_result) {
    header('Location: ../../login.php');
    exit;
}

$student_id = $student_result['student_id'];

// Get progress statistics
$stats = [];

// Total enrolled courses
$total_courses_query = "SELECT COUNT(*) as total FROM enrollments WHERE student_id = ? AND payment_status = 'Paid'";
$stmt = $conn->prepare($total_courses_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stats['total_courses'] = $stmt->get_result()->fetch_assoc()['total'];

// Completed courses (100% progress)
$completed_courses_query = "SELECT COUNT(*) as completed FROM enrollments WHERE student_id = ? AND payment_status = 'Paid' AND progress_percentage = 100";
$stmt = $conn->prepare($completed_courses_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stats['completed_courses'] = $stmt->get_result()->fetch_assoc()['completed'];

// Total XP (study hours approximation)
$xp_query = "SELECT total_xp, study_streak FROM user_xp WHERE user_id = ?";
$stmt = $conn->prepare($xp_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$xp_result = $stmt->get_result()->fetch_assoc();
$stats['total_xp'] = $xp_result ? $xp_result['total_xp'] : 0;
$stats['study_streak'] = $xp_result ? $xp_result['study_streak'] : 0;
$stats['study_hours'] = round($stats['total_xp'] / 8); // Approximate hours (8 XP per hour)

// Certificates earned
$certificates_query = "SELECT COUNT(*) as certificates FROM certificates WHERE student_id = ?";
$stmt = $conn->prepare($certificates_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stats['certificates'] = $stmt->get_result()->fetch_assoc()['certificates'];

// Get current courses with progress
$current_courses_query = "SELECT 
    c.course_id,
    c.course_title,
    c.category,
    c.image_url,
    e.progress_percentage,
    e.enrolled_at,
    COUNT(DISTINCT cm.module_id) as total_modules,
    COUNT(DISTINCT cl.lesson_id) as total_lessons
FROM enrollments e
JOIN courses c ON e.course_id = c.course_id
LEFT JOIN course_modules cm ON c.course_id = cm.course_id
LEFT JOIN course_lessons cl ON cm.module_id = cl.module_id
WHERE e.student_id = ? AND e.payment_status = 'Paid'
GROUP BY c.course_id, c.course_title, c.category, c.image_url, e.progress_percentage, e.enrolled_at
ORDER BY e.enrolled_at DESC";

$stmt = $conn->prepare($current_courses_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$current_courses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get recent achievements/badges
$achievements_query = "SELECT 
    b.badge_name,
    b.badge_description,
    b.badge_icon,
    b.badge_color,
    ub.earned_at
FROM user_badges ub
JOIN badges b ON ub.badge_id = b.badge_id
WHERE ub.user_id = ?
ORDER BY ub.earned_at DESC
LIMIT 5";

$stmt = $conn->prepare($achievements_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$achievements = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calculate completion rate
$completion_rate = $stats['total_courses'] > 0 ? round(($stats['completed_courses'] / $stats['total_courses']) * 100) : 0;

// Get XP progress data for charts (last 30 days)
$xp_progress_query = "SELECT 
    DATE(earned_at) as date,
    SUM(xp_earned) as daily_xp,
    description
FROM xp_transactions 
WHERE user_id = ? AND earned_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY DATE(earned_at), description
ORDER BY date";

$stmt = $conn->prepare($xp_progress_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$xp_progress = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get activity breakdown for the chart
$activity_breakdown_query = "SELECT 
    description,
    SUM(xp_earned) as total_xp,
    COUNT(*) as activity_count
FROM xp_transactions 
WHERE user_id = ? AND earned_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY description
ORDER BY total_xp DESC";

$stmt = $conn->prepare($activity_breakdown_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$activity_breakdown = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress - Saltel • Trainee Dashboard</title>
    <?php include '../../include/imports.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../../assets/js/progress.js" defer></script>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainee-Sidebar.php'; ?>

        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Header -->
            <?php include '../../components/Trainee-Header.php'; ?>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-y-auto">
                <!-- Page Header -->
                <div class="mb-8">
                    <h1 class="mb-2 text-3xl font-bold text-gray-900">My Progress</h1>
                    <p class="text-gray-600">Track your learning journey and achievements</p>
                </div>

                <!-- Progress Overview Cards -->
                <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Total Courses -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Courses</p>
                                <p class="text-2xl font-bold text-gray-900"><?php echo $stats['total_courses']; ?></p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                                <i class="text-xl text-blue-600 fas fa-book"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-blue-600">
                                <i class="mr-1 fas fa-graduation-cap"></i>
                                <span><?php echo $stats['study_streak']; ?> day streak</span>
                            </div>
                        </div>
                    </div>

                    <!-- Completed Courses -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Completed</p>
                                <p class="text-2xl font-bold text-gray-900"><?php echo $stats['completed_courses']; ?></p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg">
                                <i class="text-xl text-green-600 fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-green-600">
                                <i class="mr-1 fas fa-percentage"></i>
                                <span><?php echo $completion_rate; ?>% completion rate</span>
                            </div>
                        </div>
                    </div>

                    <!-- Study Hours -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Study Hours</p>
                                <p class="text-2xl font-bold text-gray-900"><?php echo $stats['study_hours']; ?></p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg">
                                <i class="text-xl text-purple-600 fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-purple-600">
                                <i class="mr-1 fas fa-star"></i>
                                <span><?php echo $stats['total_xp']; ?> XP earned</span>
                            </div>
                        </div>
                    </div>

                    <!-- Certificates -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Certificates</p>
                                <p class="text-2xl font-bold text-gray-900"><?php echo $stats['certificates']; ?></p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg">
                                <i class="text-xl text-yellow-600 fas fa-award"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-yellow-600">
                                <i class="mr-1 fas fa-trophy"></i>
                                <span><?php echo count($achievements); ?> recent badges</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-2">
                    <!-- XP Activity Breakdown Chart -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">XP Activity Breakdown</h3>
                            <span class="text-sm text-gray-500">Last 30 days</span>
                        </div>
                        <div class="h-64">
                            <canvas id="activityChart"></canvas>
                        </div>
                    </div>

                    <!-- Course Completion Rate -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Course Completion Rate</h3>
                            <span class="text-sm text-gray-500"><?php echo $completion_rate; ?>% overall</span>
                        </div>
                        <div class="flex items-center justify-center h-64">
                            <div class="relative w-48 h-48">
                                <canvas id="completionChart"></canvas>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-3xl font-bold text-gray-900"><?php echo $completion_rate; ?>%</div>
                                        <div class="text-sm text-gray-500">Completed</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Courses Progress -->
                <div class="p-6 mb-8 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <h3 class="mb-6 text-lg font-semibold text-gray-900">Current Courses Progress</h3>
                    <div class="space-y-6">
                        <?php if (empty($current_courses)): ?>
                            <div class="py-8 text-center">
                                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-lg">
                                    <i class="text-2xl text-gray-400 fas fa-book-open"></i>
                                </div>
                                <h4 class="mb-2 text-lg font-medium text-gray-900">No Courses Enrolled</h4>
                                <p class="mb-4 text-gray-500">Start your learning journey by enrolling in courses</p>
                                <a href="../courses/" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                    <i class="mr-2 fas fa-search"></i>
                                    Browse Courses
                                </a>
                            </div>
                        <?php else: ?>
                            <?php
                            $colors = [
                                'Design' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'icon' => 'fas fa-paint-brush'],
                                'Development' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'icon' => 'fas fa-code'],
                                'Data Science' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'icon' => 'fas fa-chart-line'],
                                'Marketing' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'icon' => 'fas fa-bullhorn'],
                                'Business' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600', 'icon' => 'fas fa-briefcase'],
                                'default' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'icon' => 'fas fa-book']
                            ];

                            foreach ($current_courses as $course):
                                $color = $colors[$course['category']] ?? $colors['default'];
                                $progress = $course['progress_percentage'];

                                // Determine status
                                if ($progress == 100) {
                                    $status = 'Completed';
                                    $status_color = 'text-green-700 bg-green-100';
                                } elseif ($progress >= 50) {
                                    $status = 'In Progress';
                                    $status_color = 'text-blue-700 bg-blue-100';
                                } elseif ($progress > 0) {
                                    $status = 'Started';
                                    $status_color = 'text-yellow-700 bg-yellow-100';
                                } else {
                                    $status = 'Not Started';
                                    $status_color = 'text-gray-700 bg-gray-100';
                                }

                                // Determine progress bar color
                                if ($progress == 100) {
                                    $bar_color = 'bg-green-600';
                                    $text_color = 'text-green-600';
                                } elseif ($progress >= 50) {
                                    $bar_color = 'bg-blue-600';
                                    $text_color = 'text-blue-600';
                                } else {
                                    $bar_color = 'bg-yellow-600';
                                    $text_color = 'text-yellow-600';
                                }
                            ?>
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center justify-center w-12 h-12 <?php echo $color['bg']; ?> rounded-lg">
                                            <i class="text-lg <?php echo $color['text']; ?> <?php echo $color['icon']; ?>"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900"><?php echo htmlspecialchars($course['course_title']); ?></h4>
                                            <p class="text-sm text-gray-500"><?php echo htmlspecialchars($course['category']); ?> • <?php echo $course['total_lessons']; ?> lessons</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="w-32">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs text-gray-500">Progress</span>
                                                <span class="text-xs font-medium <?php echo $text_color; ?>"><?php echo $progress; ?>%</span>
                                            </div>
                                            <div class="w-full h-2 bg-gray-200 rounded-full">
                                                <div class="h-2 <?php echo $bar_color; ?> rounded-full" style="width: <?php echo $progress; ?>%"></div>
                                            </div>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-medium <?php echo $status_color; ?> rounded-full"><?php echo $status; ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Achievements -->
                <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <h3 class="mb-6 text-lg font-semibold text-gray-900">Recent Achievements</h3>
                    <div class="space-y-4">
                        <?php if (empty($achievements)): ?>
                            <div class="py-8 text-center">
                                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-lg">
                                    <i class="text-2xl text-gray-400 fas fa-trophy"></i>
                                </div>
                                <h4 class="mb-2 text-lg font-medium text-gray-900">No Achievements Yet</h4>
                                <p class="text-gray-500">Keep learning to unlock your first badge!</p>
                            </div>
                        <?php else: ?>
                            <?php
                            $badge_colors = [
                                'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'border' => 'border-green-200'],
                                'blue' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'border' => 'border-blue-200'],
                                'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'border' => 'border-purple-200'],
                                'red' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'border' => 'border-red-200'],
                                'yellow' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600', 'border' => 'border-yellow-200'],
                                'gold' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600', 'border' => 'border-yellow-200'],
                                'orange' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600', 'border' => 'border-orange-200'],
                                'platinum' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'border' => 'border-gray-200'],
                                'legendary' => ['bg' => 'bg-gradient-to-r from-purple-100 to-pink-100', 'text' => 'text-purple-600', 'border' => 'border-purple-200']
                            ];

                            foreach ($achievements as $achievement):
                                $color = $badge_colors[$achievement['badge_color']] ?? $badge_colors['blue'];
                                $time_ago = time() - strtotime($achievement['earned_at']);

                                if ($time_ago < 86400) { // Less than 1 day
                                    $time_text = 'Earned today';
                                } elseif ($time_ago < 604800) { // Less than 1 week
                                    $days = floor($time_ago / 86400);
                                    $time_text = "Earned $days day" . ($days > 1 ? 's' : '') . " ago";
                                } elseif ($time_ago < 2592000) { // Less than 1 month
                                    $weeks = floor($time_ago / 604800);
                                    $time_text = "Earned $weeks week" . ($weeks > 1 ? 's' : '') . " ago";
                                } else {
                                    $months = floor($time_ago / 2592000);
                                    $time_text = "Earned $months month" . ($months > 1 ? 's' : '') . " ago";
                                }
                            ?>
                                <div class="flex items-center p-4 space-x-4 border <?php echo $color['border']; ?> rounded-lg">
                                    <div class="flex items-center justify-center w-12 h-12 <?php echo $color['bg']; ?> rounded-full">
                                        <i class="text-lg <?php echo $color['text']; ?> <?php echo htmlspecialchars($achievement['badge_icon']); ?>"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900"><?php echo htmlspecialchars($achievement['badge_name']); ?></h4>
                                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($achievement['badge_description']); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo $time_text; ?></p>
                                    </div>
                                    <div class="<?php echo $color['text']; ?>">
                                        <i class="text-2xl fas fa-medal"></i>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Pass data to JavaScript -->
    <script>
        window.progressData = {
            completionRate: <?php echo $completion_rate; ?>,
            totalCourses: <?php echo $stats['total_courses']; ?>,
            completedCourses: <?php echo $stats['completed_courses']; ?>,
            xpProgress: <?php echo json_encode($xp_progress); ?>,
            activityBreakdown: <?php echo json_encode($activity_breakdown); ?>,
            studyHours: <?php echo $stats['study_hours']; ?>,
            totalXP: <?php echo $stats['total_xp']; ?>
        };
    </script>
</body>

</html>