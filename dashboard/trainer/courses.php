<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • My Courses</title>
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
                    <h1 class="text-2xl font-bold text-gray-900">My Courses</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage your courses and track student progress</p>
                </div>

                <!-- Course Management Tools -->
                <div class="mb-6">
                    <button class="bg-[#17a3d6] text-white px-4 py-2 rounded-lg hover:bg-[#1792c0] transition-colors">
                        <i class="fas fa-plus mr-2"></i>Create New Course
                    </button>
                </div>

                <!-- Course Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Course Card Template -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="relative">
                            <img src="../../assets/images/courses/default-course.jpg" alt="Course thumbnail" class="w-full h-48 object-cover">
                            <span class="absolute top-3 right-3 bg-green-500 text-white text-xs px-2 py-1 rounded">Active</span>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Introduction to Web Development</h3>
                            <p class="text-sm text-gray-600 mb-4">Learn the basics of web development including HTML, CSS, and JavaScript.</p>
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <span><i class="fas fa-users mr-2"></i>32 Students</span>
                                <span><i class="fas fa-clock mr-2"></i>8 Weeks</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <button class="text-[#17a3d6] hover:text-[#1792c0] font-medium">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                                <button class="text-gray-600 hover:text-gray-800">
                                    <i class="fas fa-chart-line mr-1"></i>Analytics
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Repeat similar cards with different content -->
                </div>
            </main>
        </div>
    </div>
</body>
</html>