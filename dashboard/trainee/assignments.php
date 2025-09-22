<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignments - Saltel • Trainee Dashboard</title>
    <?php include '../../include/imports.php'; ?>
    <script src="../../assets/js/assignments.js" defer></script>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainee-Sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Header -->
            <?php include '../../components/Trainee-Header.php'; ?>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-y-auto">
                <!-- Page Header -->
                <div class="mb-8">
                    <h1 class="mb-2 text-3xl font-bold text-gray-900">Assignments</h1>
                    <p class="text-gray-600">View and manage your course assignments</p>
                </div>

                <!-- Filter Section -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="Done">Done</option>
                            <option value="Progress">In Progress</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <span>Filter by</span>
                        <button class="px-3 py-1 text-blue-600 transition-colors rounded-full bg-blue-50 hover:bg-blue-100 filter-btn active" data-filter="dates">dates</button>
                        <span>|</span>
                        <button class="px-3 py-1 text-blue-600 transition-colors rounded-full bg-blue-50 hover:bg-blue-100 filter-btn active" data-filter="Status">Status</button>
                    </div>
                </div>

                <!-- Assignments Table -->
                <div class="bg-white border border-gray-200 shadow-sm rounded-xl">
                    <div class="p-6">
                        <table id="assignmentsTable" class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Assignment Title</th>
                                    <th scope="col" class="px-6 py-3">Course/lessons</th>
                                    <th scope="col" class="px-6 py-3">Due Date</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Submit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">Conducting User Research</td>
                                    <td class="px-6 py-4 text-gray-500">User Research and Personas</td>
                                    <td class="px-6 py-4 text-gray-500">July 1, 2024</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="w-2 h-2 mr-1 bg-green-400 rounded-full"></span>
                                            Done
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-400">Submitted</span>
                                    </td>
                                </tr>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">Competitive Analysis Report</td>
                                    <td class="px-6 py-4 text-gray-500">Competitive Analysis in UX</td>
                                    <td class="px-6 py-4 text-gray-500">July 25, 2024</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <span class="w-2 h-2 mr-1 bg-blue-400 rounded-full"></span>
                                            Progress
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button class="px-3 py-1 text-xs font-medium text-blue-600 transition-colors rounded-lg bg-blue-50 hover:bg-blue-100 upload-btn">
                                            Upload
                                        </button>
                                    </td>
                                </tr>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">Creating Wireframes</td>
                                    <td class="px-6 py-4 text-gray-500">Wireframing and Prototyping</td>
                                    <td class="px-6 py-4 text-gray-500">August 1, 2024</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <span class="w-2 h-2 mr-1 bg-blue-400 rounded-full"></span>
                                            Progress
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button class="px-3 py-1 text-xs font-medium text-blue-600 transition-colors rounded-lg bg-blue-50 hover:bg-blue-100 upload-btn">
                                            Upload
                                        </button>
                                    </td>
                                </tr>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">Usability Testing and Feedback</td>
                                    <td class="px-6 py-4 text-gray-500">Usability Testing and Iteration</td>
                                    <td class="px-6 py-4 text-gray-500">August 22, 2024</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <span class="w-2 h-2 mr-1 bg-red-400 rounded-full"></span>
                                            Pending
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button class="px-3 py-1 text-xs font-medium text-blue-600 transition-colors rounded-lg bg-blue-50 hover:bg-blue-100 upload-btn">
                                            Upload
                                        </button>
                                    </td>
                                </tr>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">Developing Visual Design</td>
                                    <td class="px-6 py-4 text-gray-500">Visual Design and Branding</td>
                                    <td class="px-6 py-4 text-gray-500">August 29, 2024</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <span class="w-2 h-2 mr-1 bg-red-400 rounded-full"></span>
                                            Pending
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button class="px-3 py-1 text-xs font-medium text-blue-600 transition-colors rounded-lg bg-blue-50 hover:bg-blue-100 upload-btn">
                                            Upload
                                        </button>
                                    </td>
                                </tr>
                                <tr class="bg-white hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">Creating a Design System</td>
                                    <td class="px-6 py-4 text-gray-500">Design Systems and Components</td>
                                    <td class="px-6 py-4 text-gray-500">September 5, 2024</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <span class="w-2 h-2 mr-1 bg-red-400 rounded-full"></span>
                                            Pending
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button class="px-3 py-1 text-xs font-medium text-blue-600 transition-colors rounded-lg bg-blue-50 hover:bg-blue-100 upload-btn">
                                            Upload
                                        </button>
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