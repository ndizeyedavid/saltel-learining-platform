<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Submissions</title>
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
                    <h1 class="text-2xl font-bold text-gray-900">Submissions</h1>
                    <p class="mt-1 text-sm text-gray-600">Review and grade student submissions</p>
                </div>

                <!-- Submission Filters -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex flex-wrap gap-4 items-center mb-6">
                        <select class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                            <option>All Assignments</option>
                            <option>JavaScript Quiz</option>
                            <option>Portfolio Project</option>
                        </select>
                        <select class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                            <option>All Status</option>
                            <option>Pending Review</option>
                            <option>Graded</option>
                            <option>Late</option>
                        </select>
                        <input type="text" placeholder="Search student..." class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                    </div>

                    <!-- Submissions Table -->
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Pending Submission -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gray-200"></div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Sarah Johnson</div>
                                            <div class="text-sm text-gray-500">sarah@example.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">JavaScript Quiz</div>
                                    <div class="text-xs text-gray-500">Week 3</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Mar 15, 2024</div>
                                    <div class="text-xs text-gray-500">2:30 PM</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending Review
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    --/100
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-[#17a3d6] hover:text-[#1792c0] mr-3">Grade</button>
                                    <button class="text-gray-600 hover:text-gray-900">View</button>
                                </td>
                            </tr>

                            <!-- Graded Submission -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gray-200"></div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Mike Smith</div>
                                            <div class="text-sm text-gray-500">mike@example.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Portfolio Project</div>
                                    <div class="text-xs text-gray-500">Final Project</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Mar 14, 2024</div>
                                    <div class="text-xs text-gray-500">11:45 AM</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Graded
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    95/100
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-[#17a3d6] hover:text-[#1792c0] mr-3">Edit Grade</button>
                                    <button class="text-gray-600 hover:text-gray-900">View</button>
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