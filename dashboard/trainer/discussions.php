<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Discussions</title>
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
                    <h1 class="text-2xl font-bold text-gray-900">Discussions</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage course discussions and student interactions</p>
                </div>

                <!-- Discussion Management -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Discussion Navigation -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                            <!-- Course Filter -->
                            <div class="mb-4">
                                <select class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                                    <option>All Courses</option>
                                    <option>Web Development</option>
                                    <option>Data Science</option>
                                </select>
                            </div>

                            <!-- Discussion Categories -->
                            <div class="space-y-2">
                                <button class="w-full text-left px-3 py-2 rounded-lg bg-[#17a3d6] bg-opacity-10 text-[#17a3d6]">
                                    <i class="fas fa-comments mr-2"></i>All Discussions
                                </button>
                                <button class="w-full text-left px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-50">
                                    <i class="fas fa-question-circle mr-2"></i>Questions
                                </button>
                                <button class="w-full text-left px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-50">
                                    <i class="fas fa-bullhorn mr-2"></i>Announcements
                                </button>
                                <button class="w-full text-left px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-50">
                                    <i class="fas fa-lightbulb mr-2"></i>General
                                </button>
                            </div>

                            <!-- Create Discussion Button -->
                            <div class="mt-4">
                                <button class="w-full bg-[#17a3d6] text-white px-4 py-2 rounded-lg hover:bg-[#1792c0] transition-colors">
                                    <i class="fas fa-plus mr-2"></i>New Discussion
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Discussion List -->
                    <div class="lg:col-span-3">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <!-- Search and Filter -->
                            <div class="flex flex-wrap gap-4 items-center mb-6">
                                <div class="flex-1">
                                    <input type="text" placeholder="Search discussions..." class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
                                </div>
                                <select class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                                    <option>Most Recent</option>
                                    <option>Most Active</option>
                                    <option>Unanswered</option>
                                </select>
                            </div>

                            <!-- Discussion Threads -->
                            <div class="space-y-4">
                                <!-- Discussion Thread -->
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full mr-2">Question</span>
                                            <span class="text-sm text-gray-500">Web Development Basics</span>
                                        </div>
                                        <button class="text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">How to structure HTML for better accessibility?</h3>
                                    <p class="text-sm text-gray-600 mb-4">I'm working on making my website more accessible. What are the best practices for structuring HTML elements?</p>
                                    <div class="flex items-center justify-between text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-gray-200 mr-2"></div>
                                            <span>John Doe</span>
                                            <span class="mx-2">•</span>
                                            <span>2 hours ago</span>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span><i class="fas fa-comment mr-1"></i>5 replies</span>
                                            <span><i class="fas fa-eye mr-1"></i>24 views</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Announcement Thread -->
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full mr-2">Announcement</span>
                                            <span class="text-sm text-gray-500">All Courses</span>
                                        </div>
                                        <button class="text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">New Course Materials Available</h3>
                                    <p class="text-sm text-gray-600 mb-4">I've just uploaded new materials for Week 3. Please review them before the next session.</p>
                                    <div class="flex items-center justify-between text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-gray-200 mr-2"></div>
                                            <span>Instructor</span>
                                            <span class="mx-2">•</span>
                                            <span>1 day ago</span>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span><i class="fas fa-comment mr-1"></i>12 replies</span>
                                            <span><i class="fas fa-eye mr-1"></i>156 views</span>
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