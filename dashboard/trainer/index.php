<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Trainer Dashboard</title>
    <?php include '../../include/trainer-imports.php'; ?>
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
                    <h1 class="text-2xl font-bold text-gray-900">Welcome back, Trainer!</h1>
                    <p class="mt-1 text-sm text-gray-600">Here's what's happening with your courses today.</p>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Active Courses -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-blue-100 text-blue-600">
                                <i class="fas fa-graduation-cap text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Active Courses</h3>
                                <p class="text-2xl font-semibold text-gray-900">8</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Students -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-green-100 text-green-600">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Total Students</h3>
                                <p class="text-2xl font-semibold text-gray-900">245</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Assignments -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-yellow-100 text-yellow-600">
                                <i class="fas fa-tasks text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Pending Reviews</h3>
                                <p class="text-2xl font-semibold text-gray-900">12</p>
                            </div>
                        </div>
                    </div>

                    <!-- Course Completion -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-purple-100 text-purple-600">
                                <i class="fas fa-chart-line text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Avg. Completion</h3>
                                <p class="text-2xl font-semibold text-gray-900">85%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity and Tasks -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Recent Activity -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h2>
                        <div class="space-y-4">
                            <!-- Activity Item -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-user-plus text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-900">New student enrolled in <span class="font-medium">Web Development Basics</span></p>
                                    <p class="text-xs text-gray-500">2 hours ago</p>
                                </div>
                            </div>

                            <!-- Activity Item -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <i class="fas fa-file-alt text-green-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-900">Assignment submitted for <span class="font-medium">JavaScript Fundamentals</span></p>
                                    <p class="text-xs text-gray-500">3 hours ago</p>
                                </div>
                            </div>

                            <!-- Activity Item -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <i class="fas fa-comment text-yellow-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-900">New discussion in <span class="font-medium">React Fundamentals</span></p>
                                    <p class="text-xs text-gray-500">5 hours ago</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Tasks -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Upcoming Tasks</h2>
                        <div class="space-y-4">
                            <!-- Task Item -->
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <input type="checkbox" class="h-4 w-4 text-[#17a3d6] rounded border-gray-300">
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Review Assignment Submissions</p>
                                        <p class="text-xs text-gray-500">Web Development • Due Today</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">High</span>
                            </div>

                            <!-- Task Item -->
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <input type="checkbox" class="h-4 w-4 text-[#17a3d6] rounded border-gray-300">
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Prepare Next Week's Content</p>
                                        <p class="text-xs text-gray-500">React Fundamentals • Due Tomorrow</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Medium</span>
                            </div>

                            <!-- Task Item -->
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <input type="checkbox" class="h-4 w-4 text-[#17a3d6] rounded border-gray-300">
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Schedule Live Session</p>
                                        <p class="text-xs text-gray-500">JavaScript • Due in 3 days</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Low</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Overview -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900">Course Overview</h2>
                        <button class="text-sm text-[#17a3d6] hover:text-[#1792c0]">View All</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Students</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded bg-gray-200"></div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Web Development Basics</div>
                                                <div class="text-sm text-gray-500">8 weeks • Beginner</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">32 Students</div>
                                        <div class="text-xs text-gray-500">4 New this week</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-[#17a3d6] h-2.5 rounded-full" style="width: 75%"></div>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">Week 6 of 8</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                            Active
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="text-[#17a3d6] hover:text-[#1792c0] mr-3">View</button>
                                        <button class="text-gray-600 hover:text-gray-900">Edit</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>