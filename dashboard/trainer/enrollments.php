<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Enrollments</title>
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
                    <h1 class="text-2xl font-bold text-gray-900">Enrollments</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage student enrollments and course access</p>
                </div>

                <!-- Enrollment Management Tools -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex flex-wrap gap-4 items-center justify-between mb-6">
                        <div class="flex gap-4">
                            <select class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                                <option>All Courses</option>
                                <option>Web Development</option>
                                <option>Data Science</option>
                            </select>
                            <select class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                                <option>All Status</option>
                                <option>Active</option>
                                <option>Pending</option>
                                <option>Completed</option>
                            </select>
                        </div>
                        <button class="bg-[#17a3d6] text-white px-4 py-2 rounded-lg hover:bg-[#1792c0] transition-colors">
                            <i class="fas fa-user-plus mr-2"></i>Add New Enrollment
                        </button>
                    </div>

                    <!-- Enrollment Table -->
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrollment Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gray-200"></div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">John Doe</div>
                                            <div class="text-sm text-gray-500">john@example.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Web Development Basics</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">2024-03-15</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-[#17a3d6] h-2.5 rounded-full" style="width: 45%"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button class="text-[#17a3d6] hover:text-[#1792c0] mr-3">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>