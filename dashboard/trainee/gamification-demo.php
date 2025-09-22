<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Gamification Demo</title>
    <?php include '../../include/imports.php'; ?>
    <script src="../../assets/js/gamification.js" defer></script>
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
                <!-- Header Section -->
                <div class="mb-8">
                    <h1 class="mb-2 text-3xl font-bold text-gray-900">Gamification Demo</h1>
                    <p class="text-gray-600">Test and explore all gamification features</p>
                </div>

                <!-- Demo Controls -->
                <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-2">
                    <!-- Unlock Simulation -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">Unlock Simulation</h3>
                        <div class="space-y-4">
                            <button onclick="window.gamification.simulateUnlock('course', 'Advanced JavaScript', '250')" 
                                    class="w-full px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                                <i class="mr-2 fas fa-unlock"></i>Unlock JavaScript Course (+250 XP)
                            </button>
                            <button onclick="window.gamification.simulateUnlock('certificate', 'React Development', '500')" 
                                    class="w-full px-4 py-2 text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition-colors">
                                <i class="mr-2 fas fa-certificate"></i>Unlock React Certificate (+500 XP)
                            </button>
                            <button onclick="window.gamification.simulateUnlock('badge', 'Code Master', '100')" 
                                    class="w-full px-4 py-2 text-white bg-yellow-600 rounded-lg hover:bg-yellow-700 transition-colors">
                                <i class="mr-2 fas fa-medal"></i>Unlock Code Master Badge (+100 XP)
                            </button>
                        </div>
                    </div>

                    <!-- Locked Content Examples -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">Locked Content Examples</h3>
                        <div class="space-y-4">
                            <!-- Locked Course Card -->
                            <div class="relative p-4 border border-gray-200 rounded-lg bg-gray-50 locked-course cursor-pointer" 
                                 data-unlock-requirement="Complete HTML Basics + CSS Fundamentals" 
                                 data-xp-reward="300" 
                                 data-badge-reward="Web Developer">
                                <div class="absolute inset-0 bg-gray-900 bg-opacity-50 rounded-lg flex items-center justify-center">
                                    <div class="text-center text-white">
                                        <i class="text-2xl mb-2 fas fa-lock"></i>
                                        <p class="text-sm">Click to see unlock requirements</p>
                                    </div>
                                </div>
                                <div class="opacity-40">
                                    <h4 class="font-semibold text-gray-900">Advanced Web Development</h4>
                                    <p class="text-sm text-gray-600">Master modern web technologies</p>
                                </div>
                            </div>

                            <!-- Locked Certificate Card -->
                            <div class="relative p-4 border border-gray-200 rounded-lg bg-gray-50 locked-certificate cursor-pointer" 
                                 data-unlock-requirement="Complete Full Stack Development Track + Pass Final Assessment (90%+)">
                                <div class="absolute inset-0 bg-gray-900 bg-opacity-50 rounded-lg flex items-center justify-center">
                                    <div class="text-center text-white">
                                        <i class="text-2xl mb-2 fas fa-lock"></i>
                                        <p class="text-sm">Click to see unlock requirements</p>
                                    </div>
                                </div>
                                <div class="opacity-40">
                                    <h4 class="font-semibold text-gray-900">Full Stack Developer Certificate</h4>
                                    <p class="text-sm text-gray-600">Professional certification</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Gamification Stats -->
                <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
                    <!-- XP Points -->
                    <div class="p-6 bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-sm rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-purple-100">Experience Points</p>
                                <p class="text-2xl font-bold text-white" id="xpCounter">2,450 XP</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-lg">
                                <i class="text-xl text-white fas fa-star"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-purple-100">Level 8</span>
                                <span class="text-purple-100">550 XP to Level 9</span>
                            </div>
                            <div class="w-full bg-purple-400 bg-opacity-30 rounded-full h-2 mt-2">
                                <div class="bg-yellow-400 h-2 rounded-full" style="width: 78%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Badges Earned -->
                    <div class="p-6 bg-gradient-to-br from-yellow-500 to-orange-600 text-white shadow-sm rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-yellow-100">Badges Earned</p>
                                <p class="text-2xl font-bold text-white" id="badgeCounter">12</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-lg">
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
                    <div class="p-6 bg-gradient-to-br from-green-500 to-emerald-600 text-white shadow-sm rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-100">Study Streak</p>
                                <p class="text-2xl font-bold text-white">15 Days</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-lg">
                                <i class="text-xl text-white fas fa-fire"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-green-100">
                                <i class="mr-1 fas fa-target"></i>
                                <span>Goal: 30 days</span>
                            </div>
                        </div>
                    </div>

                    <!-- Unlocked Content -->
                    <div class="p-6 bg-gradient-to-br from-blue-500 to-cyan-600 text-white shadow-sm rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-100">Unlocked Content</p>
                                <p class="text-2xl font-bold text-white">8/12</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-white bg-opacity-20 rounded-lg">
                                <i class="text-xl text-white fas fa-unlock"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-blue-100">
                                <i class="mr-1 fas fa-lock"></i>
                                <span>4 courses locked</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feature Overview -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Implemented Features -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">✅ Implemented Features</h3>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                                    <i class="text-sm text-green-600 fas fa-check"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">XP System with Progress Bars</p>
                                    <p class="text-xs text-gray-500">Animated counters and level progression</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                                    <i class="text-sm text-green-600 fas fa-check"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Badge System</p>
                                    <p class="text-xs text-gray-500">Achievement tracking and rewards</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                                    <i class="text-sm text-green-600 fas fa-check"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Locked Content System</p>
                                    <p class="text-xs text-gray-500">Courses and certificates with unlock requirements</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                                    <i class="text-sm text-green-600 fas fa-check"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Study Streak Tracking</p>
                                    <p class="text-xs text-gray-500">Daily learning progress visualization</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                                    <i class="text-sm text-green-600 fas fa-check"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Interactive Notifications</p>
                                    <p class="text-xs text-gray-500">Toast messages for user feedback</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                                    <i class="text-sm text-green-600 fas fa-check"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Progress Visualization</p>
                                    <p class="text-xs text-gray-500">Charts and statistics dashboard</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">🔗 Explore Gamified Pages</h3>
                        <div class="space-y-3">
                            <a href="index.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                                    <i class="text-sm text-blue-600 fas fa-home"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Dashboard</p>
                                    <p class="text-xs text-gray-500">Main dashboard with gamification stats</p>
                                </div>
                            </a>
                            <a href="courses.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                                    <i class="text-sm text-green-600 fas fa-book"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Courses</p>
                                    <p class="text-xs text-gray-500">Browse courses with locked content</p>
                                </div>
                            </a>
                            <a href="assignments.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-center w-8 h-8 bg-yellow-100 rounded-full">
                                    <i class="text-sm text-yellow-600 fas fa-tasks"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Assignments</p>
                                    <p class="text-xs text-gray-500">Interactive assignment management</p>
                                </div>
                            </a>
                            <a href="progress.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-center w-8 h-8 bg-purple-100 rounded-full">
                                    <i class="text-sm text-purple-600 fas fa-chart-line"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Progress</p>
                                    <p class="text-xs text-gray-500">Detailed progress tracking with charts</p>
                                </div>
                            </a>
                            <a href="certificates.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-center w-8 h-8 bg-red-100 rounded-full">
                                    <i class="text-sm text-red-600 fas fa-certificate"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Certificates</p>
                                    <p class="text-xs text-gray-500">Certificate gallery with locked certificates</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Additional demo functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Add click handlers for locked certificate demo
            document.addEventListener('click', function(e) {
                if (e.target.closest('.locked-certificate')) {
                    const card = e.target.closest('.locked-certificate');
                    const requirement = card.dataset.unlockRequirement;
                    
                    if (typeof toastr !== 'undefined') {
                        toastr.info(`🔒 ${requirement}`, 'Certificate Locked', {
                            timeOut: 5000,
                            extendedTimeOut: 2000,
                            positionClass: 'toast-top-center'
                        });
                    }
                }
            });
        });
    </script>
</body>

</html>
