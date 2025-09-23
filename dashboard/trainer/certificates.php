<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Certificates</title>
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
                    <h1 class="text-2xl font-bold text-gray-900">Certificates</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage and issue course completion certificates</p>
                </div>

                <!-- Certificate Management Tools -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Certificate Templates -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg font-semibold text-gray-900">Certificate Templates</h2>
                                <button class="text-[#17a3d6] hover:text-[#1792c0]">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <!-- Template Card -->
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <img src="../../assets/images/certificate-template.jpg" alt="Certificate Template" class="w-full h-32 object-cover rounded-lg mb-3">
                                    <h3 class="text-sm font-medium text-gray-900 mb-2">Default Template</h3>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">Last modified: Mar 15, 2024</span>
                                        <button class="text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Add New Template Button -->
                                <button class="w-full flex items-center justify-center px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Add New Template
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate Management -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-lg font-semibold text-gray-900">Issued Certificates</h2>
                                <button class="bg-[#17a3d6] text-white px-4 py-2 rounded-lg hover:bg-[#1792c0] transition-colors">
                                    <i class="fas fa-certificate mr-2"></i>Issue Certificate
                                </button>
                            </div>

                            <!-- Filters -->
                            <div class="flex flex-wrap gap-4 mb-6">
                                <select class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                                    <option>All Courses</option>
                                    <option>Web Development</option>
                                    <option>Data Science</option>
                                </select>
                                <input type="text" placeholder="Search student..." class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                            </div>

                            <!-- Certificates Table -->
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issue Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
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
                                            <div class="text-sm text-gray-900">Mar 15, 2024</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Issued
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button class="text-[#17a3d6] hover:text-[#1792c0] mr-3">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="text-gray-600 hover:text-gray-900">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>