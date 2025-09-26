<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Trainee Dashboard</title>
    <?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../");
        exit();
    }
    include '../../include/imports.php';
    require_once '../../php/xp_system.php';

    // Initialize XP system and get user stats
    $xp_system = new XPSystem($conn);
    $user_stats = $xp_system->getUserStats($_SESSION['user_id']);
    $user_badges = $xp_system->getUserBadges($_SESSION['user_id']);
    $recent_transactions = $xp_system->getRecentTransactions($_SESSION['user_id'], 5);

    // Award daily login XP
    $xp_system->awardXP($_SESSION['user_id'], 'login', null, 'Daily login bonus');
    $xp_system->updateStudyStreak($_SESSION['user_id']);

    // Refresh stats after daily bonus
    $user_stats = $xp_system->getUserStats($_SESSION['user_id']);

    // Get dynamic dashboard data
    $student_id = $_SESSION['user_id'];

    // Get recent enrolled courses with progress

    // Check if student profile exists
    $student_query = "SELECT * FROM students WHERE user_id = ?";
    $stmt = $conn->prepare($student_query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $recent_courses_query = "SELECT c.course_id, c.course_title, c.image_url, c.category,
        COUNT(DISTINCT cl.lesson_id) as total_lessons,
        COUNT(DISTINCT lc.lesson_id) as completed_lessons,
        e.enrolled_at
        FROM enrollments e
        JOIN courses c ON e.course_id = c.course_id
        LEFT JOIN course_lessons cl ON c.course_id = cl.course_id
        LEFT JOIN lesson_completions lc ON cl.lesson_id = lc.lesson_id
        WHERE e.student_id = ? AND e.payment_status = 'Paid'
        GROUP BY c.course_id, c.course_title, c.image_url, c.category, e.enrolled_at
        ORDER BY e.enrolled_at DESC
        LIMIT 3";
    $recent_courses_stmt = $conn->prepare($recent_courses_query);
    $recent_courses_stmt->bind_param("i", $user['student_id']);
    $recent_courses_stmt->execute();
    $recent_courses = $recent_courses_stmt->get_result()->fetch_all(MYSQLI_ASSOC);


    // var_dump($recent_courses);


    // Get unlocked content count
    $unlocked_query = "SELECT COUNT(DISTINCT c.course_id) as unlocked_courses
        FROM enrollments e
        JOIN courses c ON e.course_id = c.course_id
        WHERE e.student_id = ? AND e.payment_status = 'completed'";
    $unlocked_stmt = $conn->prepare($unlocked_query);
    $unlocked_stmt->bind_param("i", $student_id);
    $unlocked_stmt->execute();
    $unlocked_result = $unlocked_stmt->get_result()->fetch_assoc();
    $unlocked_courses = $unlocked_result['unlocked_courses'];

    // Get total available courses
    $total_courses_query = "SELECT COUNT(*) as total_courses FROM courses WHERE status = 'active'";
    $total_courses_result = $conn->query($total_courses_query);
    $total_courses = $total_courses_result->fetch_assoc()['total_courses'];

    // Get student todos
    $todos_query = "SELECT * FROM student_todos WHERE student_id = ? ORDER BY created_at DESC LIMIT 10";
    $todos_stmt = $conn->prepare($todos_query);
    $todos_stmt->bind_param("i", $student_id);
    $todos_stmt->execute();
    $todos = $todos_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Get upcoming assignments
    $assignments_query = "SELECT a.assignment_id, a.title, a.due_date, c.course_title as course_title
        FROM assignments a
        JOIN courses c ON a.course_id = c.course_id
        JOIN enrollments e ON c.course_id = e.course_id
        WHERE e.student_id = ? AND a.due_date >= CURDATE()
        ORDER BY a.due_date ASC
        LIMIT 5";
    $assignments_stmt = $conn->prepare($assignments_query);
    $assignments_stmt->bind_param("i", $student_id);
    $assignments_stmt->execute();
    $upcoming_assignments = $assignments_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Get featured courses (not enrolled)
    $featured_query = "SELECT c.course_id, c.course_title, c.description, c.image_url, c.category, c.price,
        -- AVG(COALESCE(r.rating, 5)) as avg_rating,
        COUNT(DISTINCT e.enrollment_id) as enrollment_count
        FROM courses c
        LEFT JOIN enrollments r ON c.course_id = r.course_id
        LEFT JOIN enrollments e ON c.course_id = e.course_id
        WHERE c.status = 'Published'
        AND c.course_id NOT IN (
            SELECT course_id FROM enrollments WHERE student_id = ?
        )
        GROUP BY c.course_id, c.course_title, c.description, c.image_url, c.category, c.price
        ORDER BY enrollment_count DESC
        LIMIT 3";
    $featured_stmt = $conn->prepare($featured_query);
    $featured_stmt->bind_param("i", $student_id);
    $featured_stmt->execute();
    $featured_courses = $featured_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Helper function to get category icon
    function getCategoryIcon($category)
    {
        $icons = [
            'Design' => 'fas fa-palette',
            'Development' => 'fas fa-laptop-code',
            'Business' => 'fas fa-briefcase',
            'Marketing' => 'fas fa-bullhorn',
            'Technology' => 'fas fa-microchip',
            'Data Science' => 'fas fa-chart-line',
            'Mobile Development' => 'fas fa-mobile-alt',
            'Machine Learning' => 'fas fa-brain',
            'default' => 'fas fa-book'
        ];
        return $icons[$category] ?? $icons['default'];
    }

    // Get weekly login activity for streak visualization
    $weekly_activity_query = "SELECT DATE(earned_at) as login_date, COUNT(*) as activity_count
        FROM xp_transactions xt
        JOIN xp_activities xa ON xt.activity_id = xa.activity_id
        WHERE xt.user_id = ? AND xa.activity_name = 'login'
        AND DATE(earned_at) >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
        GROUP BY DATE(earned_at)
        ORDER BY login_date ASC";
    $weekly_stmt = $conn->prepare($weekly_activity_query);
    $weekly_stmt->bind_param("i", $student_id);
    $weekly_stmt->execute();
    $weekly_logins = $weekly_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // If no logins found with 'login', try alternative activity names
    if (empty($weekly_logins)) {
        // Try with different possible activity names
        $alt_query = "SELECT xa.activity_name, DATE(xt.earned_at) as login_date, COUNT(*) as activity_count
            FROM xp_transactions xt
            JOIN xp_activities xa ON xt.activity_id = xa.activity_id
            WHERE xt.user_id = ? 
            AND xa.activity_name IN ('login', 'daily_login', 'Daily Login', 'Login')
            AND DATE(earned_at) >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
            GROUP BY xa.activity_name, DATE(earned_at)
            ORDER BY login_date ASC";
        $alt_stmt = $conn->prepare($alt_query);
        $alt_stmt->bind_param("i", $student_id);
        $alt_stmt->execute();
        $alt_results = $alt_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Use the alternative results if found
        if (!empty($alt_results)) {
            $weekly_logins = $alt_results;
        }
    }

    // Create array of last 7 days with login status
    $week_days = [];
    $day_names = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];

    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $day_name = $day_names[date('w', strtotime($date))];
        $has_login = false;

        foreach ($weekly_logins as $login) {
            if ($login['login_date'] === $date) {
                $has_login = true;
                break;
            }
        }

        $week_days[] = [
            'date' => $date,
            'day_name' => $day_name,
            'has_login' => $has_login,
            'is_today' => $date === date('Y-m-d')
        ];
    }

    // Calculate this week's login count
    $this_week_logins = count($weekly_logins);

    // Helper function to calculate progress percentage
    function calculateProgress($completed, $total)
    {
        if ($total == 0) return 0;
        return round(($completed / $total) * 100);
    }
    ?>
    <script src="../../assets/js/gamification.js" defer></script>
    <script src="../../assets/js/dashboard.js" defer></script>
    <!-- FullCalendar CDN -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainee-Sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Header -->
            <?php include '../../components/Trainee-Header.php'; ?>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-y-auto">
                <!-- Welcome Section -->
                <div class="mb-8">
                    <h1 class="mb-2 text-3xl font-bold text-gray-900">Hello, <?php echo explode(" ", $_SESSION['user_name'])[1]; ?>👋</h1>
                    <p class="text-gray-600">Let's learn something new today!</p>
                </div>

                <!-- Gamification Stats -->
                <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
                    <!-- XP Points -->
                    <div class="p-6 text-white shadow-sm bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-purple-100">Experience Points</p>
                                <p class="text-2xl font-bold text-white"><?php echo number_format($user_stats['total_xp']); ?> XP</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-white rounded-lg bg-opacity-20">
                                <i class="text-xl text-white fas fa-star"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-purple-100" id="current-level">Level <?php echo $user_stats['current_level']; ?></span>
                                <span class="text-purple-100" id="xp-to-next"><?php echo $user_stats['xp_to_next_level']; ?> XP to Level <?php echo $user_stats['current_level'] + 1; ?></span>
                            </div>
                            <div class="w-full h-2 mt-2 bg-purple-400 rounded-full bg-opacity-30">
                                <?php
                                $level_base_xp = pow($user_stats['current_level'] - 1, 2) * 50;
                                $level_total_xp = pow($user_stats['current_level'], 2) * 50;
                                $level_progress = (($user_stats['total_xp'] - $level_base_xp) / ($level_total_xp - $level_base_xp)) * 100;
                                ?>
                                <div class="h-2 transition-all duration-500 bg-yellow-400 rounded-full" style="width: <?php echo $user_stats['total_xp'] * 100 / ($user_stats['total_xp'] + $user_stats['xp_to_next_level']); ?>%" id="level-progress"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Badges Earned -->
                    <div class="p-6 text-white shadow-sm bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-yellow-100">Badges Earned</p>
                                <p class="text-2xl font-bold text-white" id="badges-count"><?php echo count($user_badges); ?></p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-white rounded-lg bg-opacity-20">
                                <i class="text-xl text-white fas fa-medal"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-yellow-100">
                                <i class="mr-1 fas fa-arrow-up"></i>
                                <span>+2 this week</span>
                            </div>
                        </div>
                    </div>

                    <!-- Study Streak -->
                    <div class="p-6 text-white shadow-sm bg-gradient-to-br from-green-500 to-teal-600 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-100">Study Streak</p>
                                <p class="text-2xl font-bold text-white" id="study-streak"><?php echo $user_stats['study_streak']; ?> days</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-white rounded-lg bg-opacity-20">
                                <i class="text-xl text-white fas fa-fire"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-green-100">
                                <i class="mr-1 fas fa-calendar-check"></i>
                                <span><?php echo $user_stats['study_streak'] > 0 ? 'Keep it up!' : 'Start your streak today!'; ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Unlocked Content -->
                    <div class="p-6 text-white shadow-sm bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-100">Unlocked Content</p>
                                <p class="text-2xl font-bold text-white"><?php echo $unlocked_courses; ?>/<?php echo $total_courses; ?></p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-white rounded-lg bg-opacity-20">
                                <i class="text-xl text-white fas fa-unlock"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-blue-100">
                                <i class="mr-1 fas fa-lock"></i>
                                <span><?php echo ($total_courses - $unlocked_courses); ?> courses locked</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Row - Recent Course, Resources, Calendar -->
                <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-3">
                    <!-- Recent Enrolled Course -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">Recent enrolled course</h3>
                        <div class="space-y-4">
                            <?php if (!empty($recent_courses)): ?>
                                <?php foreach ($recent_courses as $course): ?>
                                    <?php
                                    $progress = calculateProgress($course['completed_lessons'], $course['total_lessons']);
                                    $icon = getCategoryIcon($course['category']);
                                    ?>
                                    <div class="flex items-center p-3 space-x-4 transition-colors rounded-lg cursor-pointer hover:bg-gray-50" onclick="window.location.href='course-viewer.php?course_id=<?php echo $course['course_id']; ?>'">
                                        <div class="flex items-center justify-center w-16 h-16 rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100">
                                            <i class="text-2xl text-blue-600 <?php echo $icon; ?>"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900"><?php echo htmlspecialchars($course['course_title']); ?></h4>
                                            <div class="w-full h-2 mt-2 bg-gray-200 rounded-full">
                                                <div class="h-2 transition-all duration-300 bg-blue-500 rounded-full" style="width: <?php echo $progress; ?>%"></div>
                                            </div>
                                            <p class="mt-1 text-sm text-blue-600"><?php echo $course['completed_lessons']; ?>/<?php echo $course['total_lessons']; ?> lessons</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs text-gray-500"><?php echo date('M j', strtotime($course['enrolled_at'])); ?></span>
                                            <div class="mt-1">
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                                                    <?php echo $progress; ?>%
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="py-8 text-center text-gray-500">
                                    <i class="mb-2 text-3xl fas fa-graduation-cap"></i>
                                    <p class="text-sm">No enrolled courses yet</p>
                                    <p class="text-xs">Browse our catalog to start learning!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>


                    <!-- Weekly Events Calendar -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">This Week's Events</h3>
                            <button class="text-sm font-medium text-blue-600 hover:text-blue-700">View All</button>
                        </div>

                        <!-- Mini Calendar -->
                        <div id="mini-calendar" class="mb-4 h-[550px]"></div>

                    </div>

                    <!-- Learning Streak -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-gray-900">Learning Streak</h3>
                            <div class="flex items-center space-x-1">
                                <i class="text-orange-500 fas fa-fire"></i>
                                <span class="text-sm font-medium text-orange-500"><?php echo $user_stats['study_streak']; ?> days</span>
                            </div>
                        </div>

                        <!-- Weekly Progress -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-medium text-gray-500">This Week</span>
                                <span class="text-xs text-gray-500"><?php echo $this_week_logins; ?>/7 days</span>
                            </div>
                            <div class="grid grid-cols-7 gap-1">
                                <?php foreach ($week_days as $day): ?>
                                    <div class="flex flex-col items-center">
                                        <?php if ($day['has_login']): ?>
                                            <?php if ($day['is_today']): ?>
                                                <div class="flex items-center justify-center w-8 h-8 mb-1 transition-transform duration-200 bg-blue-600 rounded-full ring-2 ring-blue-200 streak-day">
                                                    <i class="text-xs text-white fas fa-graduation-cap"></i>
                                                </div>
                                                <span class="text-xs font-medium text-blue-600"><?php echo $day['day_name']; ?></span>
                                            <?php else: ?>
                                                <div class="flex items-center justify-center w-8 h-8 mb-1 transition-transform duration-200 bg-green-500 rounded-full streak-day">
                                                    <i class="text-xs text-white fas fa-check"></i>
                                                </div>
                                                <span class="text-xs text-gray-500"><?php echo $day['day_name']; ?></span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="flex items-center justify-center w-8 h-8 mb-1 transition-transform duration-200 bg-gray-200 rounded-full streak-day">
                                                <i class="text-xs text-gray-400 fas fa-times"></i>
                                            </div>
                                            <span class="text-xs text-gray-400"><?php echo $day['day_name']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Achievement Badges -->
                        <div class="space-y-3">
                            <?php if (!empty($user_badges)): ?>
                                <?php foreach (array_slice($user_badges, 0, 3) as $badge): ?>
                                    <div class="flex items-center p-2 space-x-3 transition-all duration-200 rounded-lg cursor-pointer achievement-badge">
                                        <div class="flex items-center justify-center w-8 h-8 bg-yellow-100 rounded-full">
                                            <i class="text-sm text-yellow-600 <?php echo $badge['badge_icon']; ?>"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($badge['badge_name']); ?></p>
                                            <p class="text-xs text-gray-500"><?php echo htmlspecialchars($badge['badge_description']); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center text-gray-500">
                                    <i class="mb-2 text-2xl fas fa-medal"></i>
                                    <p class="text-sm">No badges earned yet</p>
                                    <p class="text-xs">Complete activities to earn your first badge!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Middle Row - Hours Spent, Performance, To Do List -->
                <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-3">
                    <!-- Hours Spent Chart -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">Hours Spent</h3>
                        <div class="flex items-center mb-4 space-x-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Study</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                                <span class="text-sm text-gray-600">Online Test</span>
                            </div>
                        </div>
                        <div class="relative w-full h-48">
                            <canvas id="hoursChart" class="absolute inset-0 w-full h-full"></canvas>
                        </div>
                    </div>

                    <!-- Performance -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Performance</h3>
                            <select class="px-3 py-1 text-sm border border-gray-200 rounded-lg">
                                <option>Monthly</option>
                                <option>Weekly</option>
                                <option>Daily</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-center mb-4">
                            <div class="relative w-full h-full">
                                <canvas id="performanceGauge" class="w-full h-full"></canvas>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Your Grade: <span class="font-semibold">8.966</span></p>
                        </div>
                    </div>

                    <!-- To Do List -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">To Do List</h3>

                            <button class="px-3 py-1 text-sm text-blue-600 transition-colors border border-blue-200 rounded-lg hover:bg-blue-50 add-task-btn">
                                <i class="mr-1 fas fa-plus"></i>Add Task
                            </button>
                        </div>
                        <div class="space-y-3" id="todoList">
                            <?php if (!empty($todos)): ?>
                                <?php foreach ($todos as $todo): ?>
                                    <div class="flex items-start space-x-3">
                                        <input type="checkbox"
                                            class="mt-1 border-gray-300 rounded todo-checkbox"
                                            data-todo-id="<?php echo $todo['todo_id']; ?>"
                                            <?php echo $todo['completed'] ? 'checked' : ''; ?>>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium <?php echo $todo['completed'] ? 'text-gray-500 line-through' : 'text-gray-900'; ?> todo-text">
                                                <?php echo htmlspecialchars($todo['title']); ?>
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                <?php echo $todo['due_date'] ? date('l, j F Y', strtotime($todo['due_date'])) : 'No due date'; ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="py-8 text-center text-gray-500">
                                    <i class="mb-2 text-3xl fas fa-tasks"></i>
                                    <p class="text-sm">No tasks yet</p>
                                    <p class="text-xs">Add your first task to get started!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Bottom Row - Recent Classes and Upcoming Lessons -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                    <!-- Recent XP Transactions -->
                    <div>
                        <div class="h-full p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">Recent XP Activity</h3>
                                <span class="text-sm text-gray-500">Last 7 days</span>
                            </div>

                            <div class="space-y-4">
                                <?php if (!empty($recent_transactions)): ?>
                                    <?php foreach ($recent_transactions as $transaction): ?>
                                        <div class="flex items-center justify-between p-3 transition-all duration-200 border border-gray-100 rounded-lg hover:bg-gray-50">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-full">
                                                    <i class="text-green-600 fas fa-plus"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($transaction['activity_name']); ?></p>
                                                    <p class="text-xs text-gray-500"><?php echo date('M j, Y g:i A', strtotime($transaction['earned_at'])); ?></p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-green-600">+<?php echo $transaction['xp_earned']; ?> XP</p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="py-8 text-center text-gray-500">
                                        <i class="mb-2 text-3xl fas fa-chart-line"></i>
                                        <p class="text-sm">No recent XP activity</p>
                                        <p class="text-xs">Start learning to earn your first XP!</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Lessons -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Featured Lessons</h3>
                            <a href="courses.php" class="text-sm text-blue-600 hover:text-blue-700">View All</a>
                        </div>

                        <div class="space-y-4">
                            <?php if (!empty($featured_courses)): ?>
                                <?php foreach ($featured_courses as $course): ?>
                                    <?php
                                    $icon = getCategoryIcon($course['category']);
                                    ?>
                                    <div class="p-4 transition-all border border-blue-200 rounded-lg cursor-pointer bg-blue-50/30 bg-blue-50 featured-lesson hover:shadow-md" onclick="window.location.href='../../courses.php?course_id=<?php echo $course['course_id']; ?>'">
                                        <div class="flex items-start space-x-4">
                                            <div class="flex items-center justify-center w-16 h-16 bg-blue-100/30 rounded-xl">
                                                <i class="text-2xl text-blue-600 <?php echo $icon; ?>"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2 space-x-2">
                                                    <h4 class="font-semibold text-gray-900"><?php echo htmlspecialchars($course['course_title']); ?></h4>
                                                </div>
                                                <p class="mb-3 text-sm text-gray-600"><?php echo htmlspecialchars(substr($course['description'], 0, 100)) . '...'; ?></p>
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                        <span><i class="mr-1 fas fa-tag"></i><?php echo htmlspecialchars($course['category']); ?></span>
                                                        <span><i class="mr-1 fas fa-users"></i><?php echo $course['enrollment_count']; ?> enrolled</span>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <?php if ($course['price'] > 0): ?>
                                                            <span class="text-lg font-bold text-green-600">$<?php echo number_format($course['price'], 2); ?></span>
                                                        <?php else: ?>
                                                            <span class="text-lg font-bold text-green-600">Free</span>
                                                        <?php endif; ?>
                                                        <button class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 enroll-btn" onclick="event.stopPropagation(); window.location.href='course-viewer.php?course=<?php echo $course['course_id']; ?>&lesson=2'">
                                                            Enroll Now
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="py-8 text-center text-gray-500">
                                    <i class="mb-2 text-3xl fas fa-search"></i>
                                    <p class="text-sm">No featured courses available</p>
                                    <p class="text-xs">Check back later for new courses!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

            </main>
        </div>
    </div>

    <!-- Include gamification JavaScript -->
    <script src="../../assets/js/gamification.js"></script>

    <!-- Pass assignment data to JavaScript -->
    <script>
        window.assignmentData = <?php echo json_encode($upcoming_assignments); ?>;
    </script>

    <!-- Mini Calendar JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dynamic event data from assignments
            const dynamicEvents = [];
            if (window.assignmentData && window.assignmentData.length > 0) {
                window.assignmentData.forEach(assignment => {
                    dynamicEvents.push({
                        title: assignment.title,
                        start: assignment.due_date + 'T23:59:00',
                        backgroundColor: '#ef4444',
                        borderColor: '#ef4444',
                        textColor: '#ffffff',
                        extendedProps: {
                            type: 'assignment',
                            courseTitle: assignment.course_title,
                            assignmentId: assignment.assignment_id
                        }
                    });
                });
            }

            // Add some sample study sessions and events
            const sampleEvents = [{
                    title: 'Daily Study Session',
                    start: new Date().toISOString().split('T')[0] + 'T14:00:00',
                    end: new Date().toISOString().split('T')[0] + 'T16:00:00',
                    backgroundColor: '#3b82f6',
                    borderColor: '#3b82f6',
                    textColor: '#ffffff'
                },
                {
                    title: 'Study Group',
                    start: new Date(Date.now() + 2 * 24 * 60 * 60 * 1000).toISOString().split('T')[0] + 'T18:00:00',
                    end: new Date(Date.now() + 2 * 24 * 60 * 60 * 1000).toISOString().split('T')[0] + 'T20:00:00',
                    backgroundColor: '#8b5cf6',
                    borderColor: '#8b5cf6',
                    textColor: '#ffffff'
                }
            ];

            const allEvents = [...dynamicEvents, ...sampleEvents];

            // Initialize mini calendar
            const calendarEl = document.getElementById('mini-calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'listWeek',
                height: 200,
                headerToolbar: {
                    left: '',
                    center: 'title',
                    right: 'prev,next'
                },
                events: allEvents,
                eventDisplay: 'block',
                dayMaxEvents: 2,
                eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    meridiem: 'short'
                },
                eventDidMount: function(info) {
                    // Custom styling for events
                    info.el.style.fontSize = '12px';
                    info.el.style.padding = '2px 6px';
                    info.el.style.borderRadius = '4px';
                    info.el.style.marginBottom = '2px';
                },
                eventClick: function(info) {
                    // Handle event click
                    alert('Event: ' + info.event.title);
                }
            });

            calendar.render();

            // Custom styling for the calendar
            setTimeout(() => {
                const calendarContainer = document.querySelector('#mini-calendar .fc');
                if (calendarContainer) {
                    calendarContainer.style.fontSize = '12px';
                }

                // Style the header
                const header = document.querySelector('#mini-calendar .fc-header-toolbar');
                if (header) {
                    header.style.marginBottom = '10px';
                }

                // Style the title
                const title = document.querySelector('#mini-calendar .fc-toolbar-title');
                if (title) {
                    title.style.fontSize = '14px';
                    title.style.fontWeight = '600';
                    title.style.color = '#374151';
                }

                // Style navigation buttons
                const buttons = document.querySelectorAll('#mini-calendar .fc-button');
                buttons.forEach(button => {
                    button.style.backgroundColor = '#f3f4f6';
                    button.style.borderColor = '#d1d5db';
                    button.style.color = '#374151';
                    button.style.fontSize = '12px';
                    button.style.padding = '4px 8px';
                });
            }, 100);
        });
    </script>
</body>

</html>