<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Course Content</title>
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
                    <h1 class="text-2xl font-bold text-gray-900">Course Content</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage and organize your course materials</p>
                </div>

                <!-- Content Management Tools -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Course Navigation Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg font-semibold text-gray-900">Courses</h2>
                                <button class="text-[#17a3d6] hover:text-[#1792c0]">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="space-y-2">
                                <button class="w-full text-left px-3 py-2 rounded-lg bg-[#17a3d6] bg-opacity-10 text-[#17a3d6]">
                                    Web Development Basics
                                </button>
                                <button class="w-full text-left px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-50">
                                    Advanced JavaScript
                                </button>
                                <button class="w-full text-left px-3 py-2 rounded-lg text-gray-600 hover:bg-gray-50">
                                    React Fundamentals
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="lg:col-span-3">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <!-- Course Header -->
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-xl font-semibold text-gray-900">Web Development Basics</h2>
                                <div class="flex gap-3">
                                    <button class="bg-[#17a3d6] text-white px-4 py-2 rounded-lg hover:bg-[#1792c0] transition-colors">
                                        <i class="fas fa-plus mr-2"></i>Add Module
                                    </button>
                                    <button class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                                        <i class="fas fa-cog mr-2"></i>Settings
                                    </button>
                                </div>
                            </div>

                            <!-- Course Modules -->
                            <div class="space-y-4">
                                <!-- Module 1 -->
                                <div class="border border-gray-200 rounded-lg">
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-t-lg">
                                        <h3 class="text-lg font-medium text-gray-900">Module 1: Introduction to HTML</h3>
                                        <div class="flex gap-2">
                                            <button class="text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <div class="space-y-3">
                                            <!-- Content Items -->
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                <div class="flex items-center">
                                                    <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                                                    <span class="text-gray-900">HTML Basics Guide.pdf</span>
                                                </div>
                                                <div class="flex gap-2">
                                                    <button class="text-gray-400 hover:text-gray-600">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="text-gray-400 hover:text-gray-600">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                <div class="flex items-center">
                                                    <i class="fas fa-video text-blue-500 mr-3"></i>
                                                    <span class="text-gray-900">Introduction to HTML Tags</span>
                                                </div>
                                                <div class="flex gap-2">
                                                    <button class="text-gray-400 hover:text-gray-600">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="text-gray-400 hover:text-gray-600">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                        
                                            <!-- Add Content Button -->
                                            <button class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">
                                                <i class="fas fa-plus mr-2"></i>Add Content
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Module 2 -->
                                <div class="border border-gray-200 rounded-lg">
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-t-lg">
                                        <h3 class="text-lg font-medium text-gray-900">Module 2: CSS Fundamentals</h3>
                                        <div class="flex gap-2">
                                            <button class="text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <button class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-plus mr-2"></i>Add Content
                                        </button>
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