<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Assignments</title>
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
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Assignments</h1>
                    <p class="mt-1 text-sm text-gray-600">Create and manage course assignments</p>
                </div>

                <!-- Assignment Management Tools -->
                <div class="flex flex-wrap gap-4 items-center justify-between mb-6">
                    <div class="flex gap-4">
                        <select class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                            <option>All Courses</option>
                            <option>Web Development</option>
                            <option>Data Science</option>
                        </select>
                        <select class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                            <option>All Types</option>
                            <option>Quiz</option>
                            <option>Project</option>
                            <option>Essay</option>
                        </select>
                    </div>
                    <button class="bg-[#17a3d6] text-white px-4 py-2 rounded-lg hover:bg-[#1792c0] transition-colors">
                        <i class="fas fa-plus mr-2"></i>Create Assignment
                    </button>
                </div>

                <!-- Assignment Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Assignment Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Quiz</span>
                            <div class="flex gap-2">
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">JavaScript Fundamentals Quiz</h3>
                        <p class="text-sm text-gray-600 mb-4">Test your understanding of basic JavaScript concepts including variables, functions, and control flow.</p>
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span><i class="fas fa-clock mr-2"></i>45 minutes</span>
                            <span><i class="fas fa-calendar mr-2"></i>Due: Mar 20, 2024</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500"><i class="fas fa-users mr-2"></i>25 Submissions</span>
                            <button class="text-[#17a3d6] hover:text-[#1792c0] font-medium text-sm">
                                View Submissions
                            </button>
                        </div>
                    </div>

                    <!-- Project Assignment Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Project</span>
                            <div class="flex gap-2">
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Portfolio Website Project</h3>
                        <p class="text-sm text-gray-600 mb-4">Create a responsive portfolio website using HTML, CSS, and JavaScript.</p>
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span><i class="fas fa-clock mr-2"></i>2 weeks</span>
                            <span><i class="fas fa-calendar mr-2"></i>Due: Apr 5, 2024</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500"><i class="fas fa-users mr-2"></i>18 Submissions</span>
                            <button class="text-[#17a3d6] hover:text-[#1792c0] font-medium text-sm">
                                View Submissions
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>