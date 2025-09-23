<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Assignments</title>
    <?php include '../../include/trainer-imports.php'; ?>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainer-Sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <?php include '../../components/Trainer-Header.php'; ?>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-y-auto">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Assignments</h1>
                    <p class="mt-1 text-sm text-gray-600">Create and manage course assignments</p>
                </div>

                <!-- Assignment Management Tools -->
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div class="flex gap-4">
                        <select class="px-4 py-2 text-sm border border-gray-300 rounded-lg">
                            <option>All Courses</option>
                            <option>Web Development</option>
                            <option>Data Science</option>
                        </select>
                        <select class="px-4 py-2 text-sm border border-gray-300 rounded-lg">
                            <option>All Types</option>
                            <option>Quiz</option>
                            <option>Project</option>
                            <option>Essay</option>
                        </select>
                    </div>
                    <a href="assignment-builder.php">
                        <button class="bg-[#17a3d6] text-white px-4 py-2 rounded-lg hover:bg-[#1792c0] transition-colors">
                            <i class="mr-2 fas fa-plus"></i>Create Assignment
                        </button>
                    </a>
                </div>

                <!-- Assignment Cards -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Assignment Card -->
                    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">Quiz</span>
                            <div class="flex gap-2">
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">JavaScript Fundamentals Quiz</h3>
                        <p class="mb-4 text-sm text-gray-600">Test your understanding of basic JavaScript concepts including variables, functions, and control flow.</p>
                        <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                            <span><i class="mr-2 fas fa-clock"></i>45 minutes</span>
                            <span><i class="mr-2 fas fa-calendar"></i>Due: Mar 20, 2024</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500"><i class="mr-2 fas fa-users"></i>25 Submissions</span>
                            <a href="submissions.php" class="text-[#17a3d6] hover:text-[#1792c0] font-medium text-sm">
                                View Submissions
                            </a>
                        </div>
                    </div>

                    <!-- Project Assignment Card -->
                    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">Project</span>
                            <div class="flex gap-2">
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">Portfolio Website Project</h3>
                        <p class="mb-4 text-sm text-gray-600">Create a responsive portfolio website using HTML, CSS, and JavaScript.</p>
                        <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                            <span><i class="mr-2 fas fa-clock"></i>2 weeks</span>
                            <span><i class="mr-2 fas fa-calendar"></i>Due: Apr 5, 2024</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500"><i class="mr-2 fas fa-users"></i>18 Submissions</span>
                            <a href="submissions.php" class="text-[#17a3d6] hover:text-[#1792c0] font-medium text-sm">
                                View Submissions
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>