<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress - Saltel • Trainee Dashboard</title>
    <?php include '../../include/imports.php'; ?>
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
                                <p class="text-2xl font-bold text-gray-900">12</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                                <i class="text-xl text-blue-600 fas fa-book"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-green-600">
                                <i class="mr-1 fas fa-arrow-up"></i>
                                <span>+2 this month</span>
                            </div>
                        </div>
                    </div>

                    <!-- Completed Courses -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Completed</p>
                                <p class="text-2xl font-bold text-gray-900">8</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg">
                                <i class="text-xl text-green-600 fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-green-600">
                                <i class="mr-1 fas fa-arrow-up"></i>
                                <span>+3 this month</span>
                            </div>
                        </div>
                    </div>

                    <!-- Study Hours -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Study Hours</p>
                                <p class="text-2xl font-bold text-gray-900">124</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg">
                                <i class="text-xl text-purple-600 fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-green-600">
                                <i class="mr-1 fas fa-arrow-up"></i>
                                <span>+12 this week</span>
                            </div>
                        </div>
                    </div>

                    <!-- Certificates -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Certificates</p>
                                <p class="text-2xl font-bold text-gray-900">5</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg">
                                <i class="text-xl text-yellow-600 fas fa-award"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-green-600">
                                <i class="mr-1 fas fa-arrow-up"></i>
                                <span>+1 this month</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-2">
                    <!-- Learning Progress Chart -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Learning Progress</h3>
                            <select class="px-3 py-1 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option>Last 7 days</option>
                                <option>Last 30 days</option>
                                <option>Last 3 months</option>
                            </select>
                        </div>
                        <div class="h-64">
                            <canvas id="progressChart"></canvas>
                        </div>
                    </div>

                    <!-- Course Completion Rate -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Course Completion Rate</h3>
                            <span class="text-sm text-gray-500">67% overall</span>
                        </div>
                        <div class="flex items-center justify-center h-64">
                            <div class="relative w-48 h-48">
                                <canvas id="completionChart"></canvas>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-3xl font-bold text-gray-900">67%</div>
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
                        <!-- Course 1 -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                                    <i class="text-lg text-blue-600 fas fa-paint-brush"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">UI/UX Design Fundamentals</h4>
                                    <p class="text-sm text-gray-500">8 weeks • 24 lessons</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="w-32">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs text-gray-500">Progress</span>
                                        <span class="text-xs font-medium text-blue-600">85%</span>
                                    </div>
                                    <div class="w-full h-2 bg-gray-200 rounded-full">
                                        <div class="h-2 bg-blue-600 rounded-full" style="width: 85%"></div>
                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full">In Progress</span>
                            </div>
                        </div>

                        <!-- Course 2 -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg">
                                    <i class="text-lg text-purple-600 fas fa-code"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">React Development Mastery</h4>
                                    <p class="text-sm text-gray-500">12 weeks • 36 lessons</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="w-32">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs text-gray-500">Progress</span>
                                        <span class="text-xs font-medium text-purple-600">45%</span>
                                    </div>
                                    <div class="w-full h-2 bg-gray-200 rounded-full">
                                        <div class="h-2 bg-purple-600 rounded-full" style="width: 45%"></div>
                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium text-purple-700 bg-purple-100 rounded-full">In Progress</span>
                            </div>
                        </div>

                        <!-- Course 3 -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg">
                                    <i class="text-lg text-green-600 fas fa-chart-line"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Digital Marketing Essentials</h4>
                                    <p class="text-sm text-gray-500">6 weeks • 18 lessons</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="w-32">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs text-gray-500">Progress</span>
                                        <span class="text-xs font-medium text-green-600">100%</span>
                                    </div>
                                    <div class="w-full h-2 bg-gray-200 rounded-full">
                                        <div class="h-2 bg-green-600 rounded-full" style="width: 100%"></div>
                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">Completed</span>
                            </div>
                        </div>

                        <!-- Course 4 -->
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-lg">
                                    <i class="text-lg text-red-600 fas fa-database"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">Advanced Database Design</h4>
                                    <p class="text-sm text-gray-500">10 weeks • 30 lessons</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="w-32">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs text-gray-500">Progress</span>
                                        <span class="text-xs font-medium text-red-600">15%</span>
                                    </div>
                                    <div class="w-full h-2 bg-gray-200 rounded-full">
                                        <div class="h-2 bg-red-600 rounded-full" style="width: 15%"></div>
                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">Just Started</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Achievements -->
                <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                    <h3 class="mb-6 text-lg font-semibold text-gray-900">Recent Achievements</h3>
                    <div class="space-y-4">
                        <!-- Achievement 1 -->
                        <div class="flex items-center p-4 space-x-4 border border-green-200 rounded-lg">
                            <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-full">
                                <i class="text-lg text-green-600 fas fa-trophy"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">Course Completion Master</h4>
                                <p class="text-sm text-gray-600">Completed 5 courses in a single month</p>
                                <p class="text-xs text-gray-500">Earned 2 days ago</p>
                            </div>
                            <div class="text-green-600">
                                <i class="text-2xl fas fa-medal"></i>
                            </div>
                        </div>

                        <!-- Achievement 2 -->
                        <div class="flex items-center p-4 space-x-4 border border-green-200 rounded-lg">
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full">
                                <i class="text-lg text-blue-600 fas fa-fire"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">Study Streak Champion</h4>
                                <p class="text-sm text-gray-600">Maintained a 30-day learning streak</p>
                                <p class="text-xs text-gray-500">Earned 1 week ago</p>
                            </div>
                            <div class="text-blue-600">
                                <i class="text-2xl fas fa-star"></i>
                            </div>
                        </div>

                        <!-- Achievement 3 -->
                        <div class="flex items-center p-4 space-x-4 border border-green-200 rounded-lg">
                            <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-full">
                                <i class="text-lg text-purple-600 fas fa-graduation-cap"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">Knowledge Seeker</h4>
                                <p class="text-sm text-gray-600">Completed 100 hours of learning</p>
                                <p class="text-xs text-gray-500">Earned 2 weeks ago</p>
                            </div>
                            <div class="text-purple-600">
                                <i class="text-2xl fas fa-award"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>