<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • My Courses</title>
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
                <div class="flex items-center justify-between">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">My Courses</h1>
                        <p class="mt-1 text-sm text-gray-600">Manage your courses and track student progress</p>
                    </div>

                    <!-- Course Management Tools -->
                    <div class="mb-6">
                        <a href="course-builder.php">
                            <button class="bg-[#17a3d6] text-white px-4 py-2 rounded-lg hover:bg-[#1792c0] transition-colors">
                                <i class="mr-2 fas fa-plus"></i>Create New Course
                            </button>
                        </a>
                    </div>
                </div>

                <!-- Course Grid -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Course Card Template -->
                    <div class="overflow-hidden transition-shadow bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md">
                        <div class="relative">
                            <img src="../../assets/images/courses/web.png" alt="Course thumbnail" class="object-cover w-full h-48">
                            <span class="absolute px-2 py-1 text-xs text-white bg-green-500 rounded top-3 right-3">Active</span>
                        </div>
                        <div class="p-4">
                            <h3 class="mb-2 text-lg font-semibold text-gray-900">Introduction to Web Development</h3>
                            <p class="mb-4 text-sm text-gray-600">Learn the basics of web development including HTML, CSS, and JavaScript.</p>
                            <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                                <span><i class="mr-2 fas fa-users"></i>32 Students</span>
                                <span><i class="mr-2 fas fa-clock"></i>8 Weeks</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <button class="text-[#17a3d6] hover:text-[#1792c0] font-medium">
                                    <i class="mr-1 fas fa-edit"></i>Edit
                                </button>
                                <a href="enrollments.php" class="text-gray-600 hover:text-gray-800">
                                    <i class="mr-1 fas fa-chart-line"></i>Analytics
                                </a>
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