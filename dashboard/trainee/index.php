<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Trainee Dashboard</title>
    <?php include '../../include/imports.php'; ?>
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
                    <h1 class="mb-2 text-3xl font-bold text-gray-900">Hello Christopher👋</h1>
                    <p class="text-gray-600">Let's learn something new today!</p>
                </div>

                <!-- Top Row - Recent Course, Resources, Calendar -->
                <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-3">
                    <!-- Recent Enrolled Course -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">Recent enrolled course</h3>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center justify-center w-16 h-16 bg-gray-100 rounded-lg">
                                    <i class="text-2xl text-gray-600 fas fa-laptop-code"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">Product Design Course</h4>
                                    <div class="w-full h-2 mt-2 bg-gray-200 rounded-full">
                                        <div class="h-2 bg-blue-500 rounded-full" style="width: 65%"></div>
                                    </div>
                                    <p class="mt-1 text-sm text-blue-600">14/20 class</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center justify-center w-16 h-16 bg-gray-100 rounded-lg">
                                    <i class="text-2xl text-gray-600 fas fa-laptop-code"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">Product Design Course</h4>
                                    <div class="w-full h-2 mt-2 bg-gray-200 rounded-full">
                                        <div class="h-2 bg-blue-500 rounded-full" style="width: 65%"></div>
                                    </div>
                                    <p class="mt-1 text-sm text-blue-600">14/20 class</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center justify-center w-16 h-16 bg-gray-100 rounded-lg">
                                    <i class="text-2xl text-gray-600 fas fa-laptop-code"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">Product Design Course</h4>
                                    <div class="w-full h-2 mt-2 bg-gray-200 rounded-full">
                                        <div class="h-2 bg-blue-500 rounded-full" style="width: 65%"></div>
                                    </div>
                                    <p class="mt-1 text-sm text-blue-600">14/20 class</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Your Resources -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">Your Resources</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between resource-item">
                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center justify-center w-8 h-8 bg-red-100 rounded-lg">
                                        <i class="text-sm text-red-600 fas fa-file-pdf"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Auto layout.pdf</p>
                                        <p class="text-xs text-gray-500">83 MB</p>
                                    </div>
                                </div>
                                <button class="text-sm font-medium text-blue-600 resource-btn">Open</button>
                            </div>
                            <div class="flex items-center justify-between resource-item">
                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-lg">
                                        <i class="text-sm text-green-600 fas fa-file-alt"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Design - Figma</p>
                                        <p class="text-xs text-gray-500">829 KB</p>
                                    </div>
                                </div>
                                <button class="text-sm font-medium text-blue-600 resource-btn">Continue</button>
                            </div>
                            <div class="flex items-center justify-between resource-item">
                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-lg">
                                        <i class="text-sm text-blue-600 fas fa-video"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Basics of UI.mp4</p>
                                        <p class="text-xs text-gray-500">32 MB</p>
                                    </div>
                                </div>
                                <button class="text-sm font-medium text-blue-600 resource-btn">Continue</button>
                            </div>
                            <div class="flex items-center justify-between resource-item">
                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center justify-center w-8 h-8 bg-red-100 rounded-lg">
                                        <i class="text-sm text-red-600 fas fa-file-pdf"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Auto layout.pdf</p>
                                        <p class="text-xs text-gray-500">83 MB</p>
                                    </div>
                                </div>
                                <button class="text-sm font-medium text-blue-600 resource-btn">Open</button>
                            </div>
                            <div class="flex items-center justify-between resource-item">
                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-lg">
                                        <i class="text-sm text-green-600 fas fa-file-alt"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Design - Figma</p>
                                        <p class="text-xs text-gray-500">829 KB</p>
                                    </div>
                                </div>
                                <button class="text-sm font-medium text-blue-600 resource-btn">Continue</button>
                            </div>
                        </div>
                        <button class="mt-4 text-sm font-medium text-blue-600">See more</button>
                    </div>

                    <!-- Learning Streak -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-gray-900">Learning Streak</h3>
                            <div class="flex items-center space-x-1">
                                <i class="text-orange-500 fas fa-fire"></i>
                                <span class="text-sm font-medium text-orange-500">12 days</span>
                            </div>
                        </div>

                        <!-- Weekly Progress -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-medium text-gray-500">This Week</span>
                                <span class="text-xs text-gray-500">5/7 days</span>
                            </div>
                            <div class="grid grid-cols-7 gap-1">
                                <div class="flex flex-col items-center">
                                    <div class="flex items-center justify-center w-8 h-8 mb-1 transition-transform duration-200 bg-green-500 rounded-full streak-day">
                                        <i class="text-xs text-white fas fa-check"></i>
                                    </div>
                                    <span class="text-xs text-gray-500">M</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="flex items-center justify-center w-8 h-8 mb-1 transition-transform duration-200 bg-green-500 rounded-full streak-day">
                                        <i class="text-xs text-white fas fa-check"></i>
                                    </div>
                                    <span class="text-xs text-gray-500">T</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="flex items-center justify-center w-8 h-8 mb-1 transition-transform duration-200 bg-green-500 rounded-full streak-day">
                                        <i class="text-xs text-white fas fa-check"></i>
                                    </div>
                                    <span class="text-xs text-gray-500">W</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="flex items-center justify-center w-8 h-8 mb-1 transition-transform duration-200 bg-green-500 rounded-full streak-day">
                                        <i class="text-xs text-white fas fa-check"></i>
                                    </div>
                                    <span class="text-xs text-gray-500">T</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="flex items-center justify-center w-8 h-8 mb-1 transition-transform duration-200 bg-blue-600 rounded-full ring-2 ring-blue-200 streak-day">
                                        <i class="text-xs text-white fas fa-graduation-cap"></i>
                                    </div>
                                    <span class="text-xs font-medium text-blue-600">F</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="flex items-center justify-center w-8 h-8 mb-1 transition-transform duration-200 bg-gray-200 rounded-full streak-day">
                                        <i class="text-xs text-gray-400 fas fa-times"></i>
                                    </div>
                                    <span class="text-xs text-gray-400">S</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="flex items-center justify-center w-8 h-8 mb-1 transition-transform duration-200 bg-gray-200 rounded-full streak-day">
                                        <i class="text-xs text-gray-400 fas fa-times"></i>
                                    </div>
                                    <span class="text-xs text-gray-400">S</span>
                                </div>
                            </div>
                        </div>

                        <!-- Achievement Badges -->
                        <div class="space-y-3">
                            <div class="flex items-center p-2 space-x-3 transition-all duration-200 rounded-lg cursor-pointer achievement-badge">
                                <div class="flex items-center justify-center w-8 h-8 bg-yellow-100 rounded-full">
                                    <i class="text-sm text-yellow-600 fas fa-medal"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Week Warrior</p>
                                    <p class="text-xs text-gray-500">5 days this week</p>
                                </div>
                            </div>
                            <div class="flex items-center p-2 space-x-3 transition-all duration-200 rounded-lg cursor-pointer achievement-badge">
                                <div class="flex items-center justify-center w-8 h-8 bg-purple-100 rounded-full">
                                    <i class="text-sm text-purple-600 fas fa-trophy"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Course Crusher</p>
                                    <p class="text-xs text-gray-500">3 courses completed</p>
                                </div>
                            </div>
                            <div class="flex items-center p-2 space-x-3 transition-all duration-200 rounded-lg cursor-pointer achievement-badge">
                                <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-full">
                                    <i class="text-sm text-green-600 fas fa-clock"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Time Master</p>
                                    <p class="text-xs text-gray-500">50+ hours studied</p>
                                </div>
                            </div>
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
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" class="mt-1 border-gray-300 rounded todo-checkbox">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900 todo-text">Human Interaction Designs</p>
                                    <p class="text-xs text-gray-500">Tuesday, 30 June 2024</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" class="mt-1 border-gray-300 rounded todo-checkbox">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900 todo-text">Design system Basics</p>
                                    <p class="text-xs text-gray-500">Monday, 24 June 2024</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" checked class="mt-1 border-gray-300 rounded todo-checkbox">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 line-through todo-text">Introduction to UI</p>
                                    <p class="text-xs text-gray-500">Friday, 10 June 2024</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" checked class="mt-1 border-gray-300 rounded todo-checkbox">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 line-through todo-text">Basics of Figma</p>
                                    <p class="text-xs text-gray-500">Friday, 05 June 2024</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Row - Recent Classes and Upcoming Lessons -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Recent Enrolled Classes -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Enrolled Classes</h3>
                            <div class="flex items-center space-x-2">
                                <button class="px-3 py-1 text-sm text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">All</button>
                                <button class="p-2 transition-colors rounded-lg hover:bg-gray-100">
                                    <i class="text-gray-600 fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <!-- Course 1 - In Progress -->
                            <div class="p-4 transition-all duration-300 border border-blue-200 rounded-lg bg-blue-50/30 enrolled-course hover:shadow-md">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start w-full space-x-4">
                                        <div class="flex items-center justify-center bg-blue-100/40 w-14 h-14 rounded-xl">
                                            <i class="text-xl text-blue-600 fas fa-paint-brush"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="mb-1 font-semibold text-gray-900">User Experience (UX) Design</h4>

                                            <div class="flex items-center mb-3 space-x-4 text-sm text-gray-600">
                                                <span><i class="mr-1 far fa-clock"></i>5:30hrs</span>
                                                <span><i class="mr-1 fas fa-book"></i>05 Lessons</span>
                                                <span><i class="mr-1 fas fa-users"></i>1.2k students</span>
                                            </div>
                                            <!-- Progress Bar -->
                                            <div class="w-full h-2 mb-2 bg-gray-200 rounded-full">
                                                <div class="h-2 bg-blue-600 rounded-full progress-bar" style="width: 75%"></div>
                                            </div>
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-600">Progress: 75% complete</span>
                                                <span class="font-medium text-blue-600">3/4 modules</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Course 2 - Recently Started -->
                            <div class="p-4 transition-all duration-300 border border-blue-200 rounded-lg bg-blue-50/30 enrolled-course hover:shadow-md">
                                <div class="flex items-start">
                                    <div class="flex items-start w-full space-x-4">
                                        <div class="flex items-center justify-center bg-blue-100/40 w-14 h-14 rounded-xl">
                                            <i class="text-xl text-blue-600 fas fa-palette"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="mb-1 font-semibold text-gray-900">Visual Design and Branding</h4>

                                            <div class="flex items-center mb-3 space-x-4 text-sm text-gray-600">
                                                <span><i class="mr-1 far fa-clock"></i>4:00hrs</span>
                                                <span><i class="mr-1 fas fa-book"></i>06 Lessons</span>
                                                <span><i class="mr-1 fas fa-users"></i>890 students</span>
                                            </div>
                                            <!-- Progress Bar -->
                                            <div class="w-full h-2 mb-2 bg-gray-200 rounded-full">
                                                <div class="h-2 bg-blue-600 rounded-full progress-bar" style="width: 25%"></div>
                                            </div>
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-600">Progress: 25% complete</span>
                                                <span class="font-medium text-blue-600">1/4 modules</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Course 3 - Just Enrolled -->
                            <div class="p-4 transition-all duration-300 border border-blue-200 rounded-lg bg-blue-50/30 enrolled-course hover:shadow-md">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start w-full space-x-4">
                                        <div class="flex items-center justify-center bg-blue-100/40 w-14 h-14 rounded-xl">
                                            <i class="text-xl text-blue-600 fas fa-code"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="mb-1 font-semibold text-gray-900">Frontend Web Development</h4>

                                            <div class="flex items-center mb-3 space-x-4 text-sm text-gray-600">
                                                <span><i class="mr-1 far fa-clock"></i>12:00hrs</span>
                                                <span><i class="mr-1 fas fa-book"></i>15 Lessons</span>
                                                <span><i class="mr-1 fas fa-users"></i>3.5k students</span>
                                            </div>
                                            <!-- Progress Bar -->
                                            <div class="w-full h-2 mb-2 bg-gray-200 rounded-full">
                                                <div class="h-2 bg-blue-600 rounded-full progress-bar" style="width: 5%"></div>
                                            </div>
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-600">Just enrolled</span>
                                                <span class="font-medium text-blue-600">0/8 modules</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Lessons -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Featured Lessons</h3>
                            <button class="text-sm text-blue-600 hover:text-blue-700">View All</button>
                        </div>

                        <div class="space-y-4">
                            <!-- Featured Lesson 1 -->
                            <div class="p-4 transition-all border border-blue-200 rounded-lg bg-blue-50/30 bg-blue-50 featured-lesson hover:shadow-md">
                                <div class="flex items-start space-x-4">
                                    <div class="flex items-center justify-center w-16 h-16 bg-blue-100/30 rounded-xl">
                                        <i class="text-2xl text-blue-600 fas fa-brain"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2 space-x-2">
                                            <h4 class="font-semibold text-gray-900">Advanced Machine Learning</h4>
                                            <div class="flex text-yellow-400">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <span class="ml-1 text-sm text-gray-600">(4.9)</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                <span><i class="mr-1 far fa-clock"></i>8 weeks</span>
                                                <span><i class="mr-1 fas fa-certificate"></i>Certificate</span>
                                            </div>
                                            <button class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 enroll-btn">
                                                Enroll Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Featured Lesson 2 -->
                            <div class="p-4 transition-all border border-blue-200 rounded-lg bg-blue-50/30 bg-blue-50 featured-lesson hover:shadow-md">
                                <div class="flex items-start space-x-4">
                                    <div class="flex items-center justify-center w-16 h-16 bg-blue-100/30 rounded-xl">
                                        <i class="text-2xl text-blue-600 fas fa-mobile-alt"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2 space-x-2">
                                            <h4 class="font-semibold text-gray-900">React Native Development</h4>
                                            <div class="flex text-yellow-400">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <span class="ml-1 text-sm text-gray-600">(4.7)</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                <span><i class="mr-1 far fa-clock"></i>6 weeks</span>
                                                <span><i class="mr-1 fas fa-project-diagram"></i>5 Projects</span>
                                            </div>
                                            <button class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 enroll-btn">
                                                Enroll Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Featured Lesson 3 -->
                            <div class="p-4 transition-all border border-blue-200 rounded-lg bg-blue-50/30 bg-blue-50 featured-lesson hover:shadow-md">
                                <div class="flex items-start space-x-4">
                                    <div class="flex items-center justify-center w-16 h-16 bg-blue-100/30 rounded-xl">
                                        <i class="text-2xl text-blue-600 fas fa-chart-line"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2 space-x-2">
                                            <h4 class="font-semibold text-gray-900">Data Science & Analytics</h4>
                                            <div class="flex text-yellow-400">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <span class="ml-1 text-sm text-gray-600">(4.8)</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                <span><i class="mr-1 far fa-clock"></i>10 weeks</span>
                                                <span><i class="mr-1 fas fa-database"></i>Real Data</span>
                                            </div>
                                            <button class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 enroll-btn">
                                                Enroll Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>