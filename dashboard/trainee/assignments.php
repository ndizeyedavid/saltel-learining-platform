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
    <div class="flex h-screen">
        <?php include '../../components/Trainee-Sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1">
            <!-- Header -->
            <?php include '../../components/Trainee-Header.php'; ?>

            <!-- Assignment Content -->
            <div class="flex-1 overflow-y-auto">
                <main class="flex-1 bg-gray-50">
                    <div class="container px-6 py-8 mx-auto">
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-gray-900">Assignments</h1>
                            <p class="mt-2 text-gray-600">Track and submit your course assignments</p>
                        </div>

                        <!-- Assignment Statistics -->
                        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-4">
                            <div class="p-6 bg-white border-l-4 shadow-sm rounded-xl border-l-green-400">
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center bg-green-100 rounded-full size-10">
                                        <i class="text-xl text-green-600 fas fa-check-circle"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Completed</p>
                                        <p class="text-2xl font-bold text-gray-900">1</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 bg-white border-l-4 shadow-sm rounded-xl border-l-blue-400">
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center bg-blue-100 rounded-full size-10">
                                        <i class="text-xl text-blue-600 fas fa-clock"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">In Progress</p>
                                        <p class="text-2xl font-bold text-gray-900">2</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 bg-white border-l-4 shadow-sm rounded-xl border-l-red-400">
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center bg-red-100 rounded-full size-10">
                                        <i class="text-xl text-red-600 fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Overdue</p>
                                        <p class="text-2xl font-bold text-gray-900">1</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 bg-white border-l-4 shadow-sm rounded-xl border-l-gray-400">
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center bg-gray-100 rounded-full size-10">
                                        <i class="text-xl text-gray-600 fas fa-lock"></i>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Locked</p>
                                        <p class="text-2xl font-bold text-gray-900">2</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assignment Filters -->
                        <div class="p-6 mb-6 bg-white shadow-sm rounded-xl">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center space-x-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Filter Assignments</h3>
                                    <div class="flex space-x-2">
                                        <button class="px-3 py-1 text-xs font-medium text-blue-800 transition-colors bg-blue-100 rounded-full hover:bg-blue-200 filter-btn" data-filter="all">All</button>
                                        <button class="px-3 py-1 text-xs font-medium text-gray-700 transition-colors bg-gray-100 rounded-full hover:bg-gray-200 filter-btn" data-filter="quiz">Quizzes</button>
                                        <button class="px-3 py-1 text-xs font-medium text-gray-700 transition-colors bg-gray-100 rounded-full hover:bg-gray-200 filter-btn" data-filter="technical">Technical</button>
                                        <button class="px-3 py-1 text-xs font-medium text-gray-700 transition-colors bg-gray-100 rounded-full hover:bg-gray-200 filter-btn" data-filter="document">Documents</button>
                                        <button class="px-3 py-1 text-xs font-medium text-gray-700 transition-colors bg-gray-100 rounded-full hover:bg-gray-200 filter-btn" data-filter="overdue">Overdue</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-2 bg-white shadow-sm rounded-xl">
                            <table id="assignmentsTable" class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 font-semibold">Assignment Title</th>
                                        <th scope="col" class="px-6 py-4 font-semibold">Course/lessons</th>
                                        <th scope="col" class="px-6 py-4 font-semibold">Due Date</th>
                                        <th scope="col" class="px-6 py-4 font-semibold">Status</th>
                                        <th scope="col" class="px-6 py-4 font-semibold">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Completed Assignment -->
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="font-medium text-gray-900">Conducting User Research</div>
                                                    <div class="flex items-center space-x-2 text-xs text-gray-500">
                                                        <span class="px-2 py-1 font-medium text-blue-800 bg-blue-100 rounded-full">Document</span>
                                                        <span class="font-medium text-green-600">• Submitted on July 1, 2024</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">User Research and Personas</td>
                                        <td class="px-6 py-4">
                                            <div class="text-gray-500">July 1, 2024</div>
                                            <div class="text-xs font-medium text-green-600">Submitted early</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-800 bg-green-100 border border-green-200 rounded-full">
                                                <i class="mr-1 fas fa-check-circle"></i>
                                                Completed
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm font-medium text-green-600">✓ Submitted</span>
                                                <button class="text-xs text-blue-600 underline hover:text-blue-800">View Feedback</button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Technical Assignment - In Progress -->
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">

                                                <div>
                                                    <div class="font-medium text-gray-900">React Portfolio Website</div>
                                                    <div class="flex items-center space-x-2 text-xs text-gray-500">
                                                        <span class="px-2 py-1 font-medium text-purple-800 bg-purple-100 rounded-full">Technical</span>
                                                        <span class="font-medium text-blue-600">• ZIP Upload Required</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">React Development Mastery</td>
                                        <td class="px-6 py-4">
                                            <div class="text-gray-500">December 30, 2024</div>
                                            <div class="text-xs font-medium text-blue-600">5 days remaining</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-800 bg-blue-100 border border-blue-200 rounded-full">
                                                <i class="mr-1 fas fa-clock"></i>
                                                In Progress
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button class="px-4 py-2 text-sm font-medium text-white transition-all bg-purple-600 rounded-lg hover:bg-purple-700 hover:shadow-lg upload-code-btn" data-assignment="react-portfolio">
                                                <i class="mr-2 fas fa-upload"></i>
                                                Upload Code
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Quiz Assignment - Available -->
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">

                                                <div>
                                                    <a href="assignment-take.php?id=1" class="font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                                        Data Science Quiz - Module 6
                                                    </a>
                                                    <div class="flex items-center space-x-2 text-xs text-gray-500">
                                                        <span class="px-2 py-1 font-medium text-green-800 bg-green-100 rounded-full">Quiz</span>
                                                        <span class="text-gray-600">• 15 Questions</span>
                                                        <span class="font-medium text-orange-600">• 30 min limit</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">Machine Learning Basics</td>
                                        <td class="px-6 py-4">
                                            <div class="text-gray-500">December 25, 2024</div>
                                            <div class="text-xs font-medium text-orange-600">Due today!</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 border border-yellow-200 rounded-full">
                                                <i class="mr-1 fas fa-hourglass-half"></i>
                                                Available
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="assignment-take.php?id=1" class="px-4 py-2 text-sm font-medium text-white transition-all bg-green-600 rounded-lg hover:bg-green-700 hover:shadow-lg">
                                                <i class="mr-2 fas fa-play"></i>
                                                Take Quiz
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Locked Assignment - Course Incomplete -->
                                    <tr class="border-b opacity-75 bg-gray-50 hover:bg-gray-100">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">

                                                <div>
                                                    <div class="font-medium text-gray-600">Advanced ML Algorithms Quiz</div>
                                                    <div class="flex items-center space-x-2 text-xs text-gray-500">
                                                        <span class="px-2 py-1 font-medium text-gray-700 bg-gray-200 rounded-full">Quiz</span>
                                                        <span class="font-medium text-red-600">• Requires: Machine Learning Basics</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">Advanced Analytics</td>
                                        <td class="px-6 py-4">
                                            <div class="text-gray-500">January 15, 2025</div>
                                            <div class="text-xs text-gray-500">21 days remaining</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-full">
                                                <i class="mr-1 fas fa-lock"></i>
                                                Locked
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button class="px-4 py-2 text-sm font-medium text-gray-600 transition-colors bg-gray-200 rounded-lg cursor-not-allowed hover:bg-gray-300 locked-assignment-btn"
                                                data-required-course="Machine Learning Basics"
                                                data-course-link="course-viewer.php?course=machine-learning-basics">
                                                <i class="mr-2 fas fa-lock"></i>
                                                View Requirements
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Technical Assignment - Python -->
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">

                                                <div>
                                                    <div class="font-medium text-gray-900">Data Analysis Project</div>
                                                    <div class="flex items-center space-x-2 text-xs text-gray-500">
                                                        <span class="px-2 py-1 font-medium text-purple-800 bg-purple-100 rounded-full">Technical</span>
                                                        <span class="font-medium text-red-600">• Overdue by 2 days</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">Python Data Analytics</td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-red-600">January 5, 2025</div>
                                            <div class="text-xs font-bold text-red-600">OVERDUE</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-800 bg-red-100 border border-red-200 rounded-full">
                                                <i class="mr-1 fas fa-exclamation-triangle"></i>
                                                Overdue
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button class="px-4 py-2 text-sm font-medium text-white transition-all bg-red-600 rounded-lg hover:bg-red-700 hover:shadow-lg upload-code-btn" data-assignment="python-data-analysis">
                                                <i class="mr-2 fas fa-upload"></i>
                                                Upload Now
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Document Assignment -->
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">

                                                <div>
                                                    <div class="font-medium text-gray-900">Usability Testing Report</div>
                                                    <div class="flex items-center space-x-2 text-xs text-gray-500">
                                                        <span class="px-2 py-1 font-medium text-blue-800 bg-blue-100 rounded-full">Document</span>
                                                        <span class="font-medium text-orange-600">• PDF, DOC, PPT accepted</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">Usability Testing and Iteration</td>
                                        <td class="px-6 py-4">
                                            <div class="text-gray-500">August 22, 2024</div>
                                            <div class="text-xs font-medium text-orange-600">3 days remaining</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-orange-800 bg-orange-100 border border-orange-200 rounded-full">
                                                <i class="mr-1 fas fa-clock"></i>
                                                Pending
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button class="px-4 py-2 text-sm font-medium text-blue-600 transition-all rounded-lg bg-blue-50 hover:bg-blue-100 hover:shadow-lg upload-doc-btn" data-assignment="usability-report">
                                                <i class="mr-2 fas fa-upload"></i>
                                                Upload Document
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Locked Technical Assignment -->
                                    <tr class="border-b opacity-75 bg-gray-50 hover:bg-gray-100">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">

                                                <div>
                                                    <div class="font-medium text-gray-600">Full-Stack E-commerce App</div>
                                                    <div class="flex items-center space-x-2 text-xs text-gray-500">
                                                        <span class="px-2 py-1 font-medium text-gray-700 bg-gray-200 rounded-full">Technical</span>
                                                        <span class="font-medium text-red-600">• Requires: React Development Mastery</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">Advanced Web Development</td>
                                        <td class="px-6 py-4">
                                            <div class="text-gray-500">February 1, 2025</div>
                                            <div class="text-xs text-gray-500">32 days remaining</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-full">
                                                <i class="mr-1 fas fa-lock"></i>
                                                Locked
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button class="px-4 py-2 text-sm font-medium text-gray-600 transition-colors bg-gray-200 rounded-lg cursor-not-allowed hover:bg-gray-300 locked-assignment-btn"
                                                data-required-course="React Development Mastery"
                                                data-course-link="course-viewer.php?course=react-development">
                                                <i class="mr-2 fas fa-lock"></i>
                                                View Requirements
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Quiz Assignment - Blockchain -->
                                    <tr class="bg-white hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">

                                                <div>
                                                    <div class="font-medium text-gray-900">Blockchain Fundamentals Quiz</div>
                                                    <div class="flex items-center space-x-2 text-xs text-gray-500">
                                                        <span class="px-2 py-1 font-medium text-green-800 bg-green-100 rounded-full">Quiz</span>
                                                        <span class="text-gray-600">• 20 Questions</span>
                                                        <span class="font-medium text-blue-600">• 45 min limit</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">Blockchain Development</td>
                                        <td class="px-6 py-4">
                                            <div class="text-gray-500">January 20, 2025</div>
                                            <div class="text-xs font-medium text-green-600">15 days remaining</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-800 bg-green-100 border border-green-200 rounded-full">
                                                <i class="mr-1 fas fa-play-circle"></i>
                                                Ready
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="assignment-take.php?id=2" class="px-4 py-2 text-sm font-medium text-white transition-all bg-green-600 rounded-lg hover:bg-green-700 hover:shadow-lg">
                                                <i class="mr-2 fas fa-play"></i>
                                                Take Quiz
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <!-- Locked Assignment Modal -->
        <div id="lockedAssignmentModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50">
            <div class="w-full max-w-md mx-4 bg-white shadow-2xl rounded-xl">
                <div class="p-6 text-center">
                    <i class="mb-4 text-4xl text-gray-400 fas fa-lock"></i>
                    <h3 class="mb-2 text-xl font-semibold text-gray-900">Assignment Locked</h3>
                    <p class="mb-4 text-gray-600">You need to complete <strong id="requiredCourse"></strong> before accessing this assignment.</p>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button id="closeLockedModal" class="flex-1 px-4 py-2 text-gray-600 transition-colors border border-gray-300 rounded-lg hover:text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <a id="goToCourseBtn" href="#" class="flex-1 px-6 py-2 text-center text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                            <i class="mr-2 fas fa-book"></i>
                            Go to Course
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technical Assignment Upload Modal -->
        <div id="codeUploadModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50">
            <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">Upload Technical Assignment</h3>
                    <button id="closeCodeModal" class="text-gray-400 transition-colors hover:text-gray-600">
                        <i class="text-xl fas fa-times"></i>
                    </button>
                </div>

                <div class="p-6">
                    <div class="mb-6">
                        <h4 class="mb-2 text-lg font-semibold text-gray-900" id="codeAssignmentTitle">Assignment Title</h4>
                        <p class="text-gray-600">Upload your project files as a ZIP archive containing your complete solution.</p>
                    </div>

                    <!-- Upload Requirements -->
                    <div class="p-4 mb-6 rounded-lg bg-blue-50">
                        <h5 class="mb-2 font-semibold text-blue-900">
                            <i class="mr-2 fas fa-info-circle"></i>
                            Upload Requirements
                        </h5>
                        <ul class="space-y-1 text-sm text-blue-800">
                            <li>• ZIP file format only</li>
                            <li>• Maximum file size: 50MB</li>
                            <li>• Include README.md with setup instructions</li>
                            <li>• Remove node_modules and build folders</li>
                            <li>• Include all source code and configuration files</li>
                        </ul>
                    </div>

                    <!-- File Upload Area -->
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Project Files (ZIP)</label>
                        <div id="dropZone" class="p-8 text-center transition-colors border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:border-blue-400">
                            <i class="mb-4 text-4xl text-gray-400 fas fa-cloud-upload-alt"></i>
                            <p class="mb-2 text-gray-600">Drag and drop your ZIP file here, or click to browse</p>
                            <p class="text-sm text-gray-500">Supported format: .zip (max 50MB)</p>
                            <input type="file" id="codeFileInput" class="hidden" accept=".zip" />
                        </div>
                        <div id="selectedFile" class="hidden p-3 mt-3 border border-green-200 rounded-lg bg-green-50">
                            <div class="flex items-center">
                                <i class="mr-3 text-green-600 fas fa-file-archive"></i>
                                <div class="flex-1">
                                    <div class="font-medium text-green-900" id="fileName"></div>
                                    <div class="text-sm text-green-700" id="fileSize"></div>
                                </div>
                                <button id="removeFile" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Comments -->
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Comments (Optional)</label>
                        <textarea id="codeComments" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Add any notes about your implementation, challenges faced, or special instructions..."></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button id="cancelCodeUpload" class="flex-1 px-4 py-2 text-gray-600 transition-colors border border-gray-300 rounded-lg hover:text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button id="submitCodeAssignment" class="flex-1 px-6 py-2 text-white transition-colors bg-purple-600 rounded-lg hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <i class="mr-2 fas fa-upload"></i>
                            Submit Assignment
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Assignment Upload Modal -->
        <div id="docUploadModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50">
            <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">Upload Document Assignment</h3>
                    <button id="closeDocModal" class="text-gray-400 transition-colors hover:text-gray-600">
                        <i class="text-xl fas fa-times"></i>
                    </button>
                </div>

                <div class="p-6">
                    <div class="mb-6">
                        <h4 class="mb-2 text-lg font-semibold text-gray-900" id="docAssignmentTitle">Assignment Title</h4>
                        <p class="text-gray-600">Upload your completed document or report for this assignment.</p>
                    </div>

                    <!-- Upload Requirements -->
                    <div class="p-4 mb-6 rounded-lg bg-green-50">
                        <h5 class="mb-2 font-semibold text-green-900">
                            <i class="mr-2 fas fa-info-circle"></i>
                            Accepted Formats
                        </h5>
                        <ul class="space-y-1 text-sm text-green-800">
                            <li>• PDF documents (.pdf)</li>
                            <li>• Word documents (.doc, .docx)</li>
                            <li>• PowerPoint presentations (.ppt, .pptx)</li>
                            <li>• Maximum file size: 25MB</li>
                        </ul>
                    </div>

                    <!-- File Upload Area -->
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Document File</label>
                        <div id="docDropZone" class="p-8 text-center transition-colors border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:border-green-400">
                            <i class="mb-4 text-4xl text-gray-400 fas fa-file-upload"></i>
                            <p class="mb-2 text-gray-600">Drag and drop your document here, or click to browse</p>
                            <p class="text-sm text-gray-500">PDF, DOC, DOCX, PPT, PPTX (max 25MB)</p>
                            <input type="file" id="docFileInput" class="hidden" accept=".pdf,.doc,.docx,.ppt,.pptx" />
                        </div>
                        <div id="selectedDocFile" class="hidden p-3 mt-3 border border-blue-200 rounded-lg bg-blue-50">
                            <div class="flex items-center">
                                <i class="mr-3 text-blue-600 fas fa-file-alt"></i>
                                <div class="flex-1">
                                    <div class="font-medium text-blue-900" id="docFileName"></div>
                                    <div class="text-sm text-blue-700" id="docFileSize"></div>
                                </div>
                                <button id="removeDocFile" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Comments -->
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Comments (Optional)</label>
                        <textarea id="docComments" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Add any additional notes or explanations about your submission..."></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button id="cancelDocUpload" class="flex-1 px-4 py-2 text-gray-600 transition-colors border border-gray-300 rounded-lg hover:text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button id="submitDocAssignment" class="flex-1 px-6 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <i class="mr-2 fas fa-upload"></i>
                            Submit Assignment
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Assignment Management JavaScript
            class AssignmentManager {
                constructor() {
                    this.initializeEventListeners();
                }

                initializeEventListeners() {
                    // Locked assignment buttons
                    document.querySelectorAll('.locked-assignment-btn').forEach(btn => {
                        btn.addEventListener('click', (e) => {
                            const requiredCourse = e.target.getAttribute('data-required-course');
                            const courseLink = e.target.getAttribute('data-course-link');
                            this.showLockedModal(requiredCourse, courseLink);
                        });
                    });

                    // Technical assignment upload buttons
                    document.querySelectorAll('.upload-code-btn').forEach(btn => {
                        btn.addEventListener('click', (e) => {
                            const assignmentId = e.target.getAttribute('data-assignment');
                            this.showCodeUploadModal(assignmentId);
                        });
                    });

                    // Document assignment upload buttons
                    document.querySelectorAll('.upload-doc-btn').forEach(btn => {
                        btn.addEventListener('click', (e) => {
                            const assignmentId = e.target.getAttribute('data-assignment');
                            this.showDocUploadModal(assignmentId);
                        });
                    });

                    // Modal close buttons
                    document.getElementById('closeLockedModal').addEventListener('click', () => {
                        this.closeModal('lockedAssignmentModal');
                    });

                    document.getElementById('closeCodeModal').addEventListener('click', () => {
                        this.closeModal('codeUploadModal');
                    });

                    document.getElementById('closeDocModal').addEventListener('click', () => {
                        this.closeModal('docUploadModal');
                    });

                    // File upload handling
                    this.setupFileUpload();
                }

                showLockedModal(requiredCourse, courseLink) {
                    document.getElementById('requiredCourse').textContent = requiredCourse;
                    document.getElementById('goToCourseBtn').href = courseLink;
                    this.openModal('lockedAssignmentModal');
                }

                showCodeUploadModal(assignmentId) {
                    const assignments = {
                        'react-portfolio': 'React Portfolio Website',
                        'python-data-analysis': 'Data Analysis Project'
                    };

                    document.getElementById('codeAssignmentTitle').textContent = assignments[assignmentId] || 'Technical Assignment';
                    this.openModal('codeUploadModal');
                }

                showDocUploadModal(assignmentId) {
                    const assignments = {
                        'usability-report': 'Usability Testing Report'
                    };

                    document.getElementById('docAssignmentTitle').textContent = assignments[assignmentId] || 'Document Assignment';
                    this.openModal('docUploadModal');
                }

                setupFileUpload() {
                    // Code file upload
                    const codeDropZone = document.getElementById('dropZone');
                    const codeFileInput = document.getElementById('codeFileInput');
                    const submitCodeBtn = document.getElementById('submitCodeAssignment');

                    codeDropZone.addEventListener('click', () => codeFileInput.click());
                    codeDropZone.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        codeDropZone.classList.add('border-blue-400', 'bg-blue-50');
                    });
                    codeDropZone.addEventListener('dragleave', () => {
                        codeDropZone.classList.remove('border-blue-400', 'bg-blue-50');
                    });
                    codeDropZone.addEventListener('drop', (e) => {
                        e.preventDefault();
                        codeDropZone.classList.remove('border-blue-400', 'bg-blue-50');
                        const files = e.dataTransfer.files;
                        if (files.length > 0) {
                            this.handleCodeFileSelect(files[0]);
                        }
                    });

                    codeFileInput.addEventListener('change', (e) => {
                        if (e.target.files.length > 0) {
                            this.handleCodeFileSelect(e.target.files[0]);
                        }
                    });

                    document.getElementById('removeFile').addEventListener('click', () => {
                        this.clearSelectedFile();
                    });

                    submitCodeBtn.addEventListener('click', () => {
                        this.submitCodeAssignment();
                    });

                    document.getElementById('cancelCodeUpload').addEventListener('click', () => {
                        this.closeModal('codeUploadModal');
                        this.clearSelectedFile();
                    });

                    // Document file upload
                    const docDropZone = document.getElementById('docDropZone');
                    const docFileInput = document.getElementById('docFileInput');
                    const submitDocBtn = document.getElementById('submitDocAssignment');

                    docDropZone.addEventListener('click', () => docFileInput.click());
                    docDropZone.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        docDropZone.classList.add('border-green-400', 'bg-green-50');
                    });
                    docDropZone.addEventListener('dragleave', () => {
                        docDropZone.classList.remove('border-green-400', 'bg-green-50');
                    });
                    docDropZone.addEventListener('drop', (e) => {
                        e.preventDefault();
                        docDropZone.classList.remove('border-green-400', 'bg-green-50');
                        const files = e.dataTransfer.files;
                        if (files.length > 0) {
                            this.handleDocFileSelect(files[0]);
                        }
                    });

                    docFileInput.addEventListener('change', (e) => {
                        if (e.target.files.length > 0) {
                            this.handleDocFileSelect(e.target.files[0]);
                        }
                    });

                    document.getElementById('removeDocFile').addEventListener('click', () => {
                        this.clearSelectedDocFile();
                    });

                    submitDocBtn.addEventListener('click', () => {
                        this.submitDocAssignment();
                    });

                    document.getElementById('cancelDocUpload').addEventListener('click', () => {
                        this.closeModal('docUploadModal');
                        this.clearSelectedDocFile();
                    });
                }

                handleCodeFileSelect(file) {
                    if (!file.name.endsWith('.zip')) {
                        alert('Please select a ZIP file.');
                        return;
                    }

                    if (file.size > 50 * 1024 * 1024) { // 50MB
                        alert('File size must be less than 50MB.');
                        return;
                    }

                    document.getElementById('fileName').textContent = file.name;
                    document.getElementById('fileSize').textContent = this.formatFileSize(file.size);
                    document.getElementById('selectedFile').classList.remove('hidden');
                    document.getElementById('submitCodeAssignment').disabled = false;
                }

                handleDocFileSelect(file) {
                    const allowedTypes = ['.pdf', '.doc', '.docx', '.ppt', '.pptx'];
                    const fileExt = '.' + file.name.split('.').pop().toLowerCase();

                    if (!allowedTypes.includes(fileExt)) {
                        alert('Please select a valid document file (PDF, DOC, DOCX, PPT, PPTX).');
                        return;
                    }

                    if (file.size > 25 * 1024 * 1024) { // 25MB
                        alert('File size must be less than 25MB.');
                        return;
                    }

                    document.getElementById('docFileName').textContent = file.name;
                    document.getElementById('docFileSize').textContent = this.formatFileSize(file.size);
                    document.getElementById('selectedDocFile').classList.remove('hidden');
                    document.getElementById('submitDocAssignment').disabled = false;
                }

                clearSelectedFile() {
                    document.getElementById('selectedFile').classList.add('hidden');
                    document.getElementById('codeFileInput').value = '';
                    document.getElementById('submitCodeAssignment').disabled = true;
                }

                clearSelectedDocFile() {
                    document.getElementById('selectedDocFile').classList.add('hidden');
                    document.getElementById('docFileInput').value = '';
                    document.getElementById('submitDocAssignment').disabled = true;
                }

                submitCodeAssignment() {
                    const submitBtn = document.getElementById('submitCodeAssignment');
                    const originalText = submitBtn.innerHTML;

                    submitBtn.innerHTML = '<i class="mr-2 fas fa-spinner fa-spin"></i>Uploading...';
                    submitBtn.disabled = true;

                    // Simulate upload process
                    setTimeout(() => {
                        alert('Technical assignment submitted successfully!');
                        this.closeModal('codeUploadModal');
                        this.clearSelectedFile();
                        document.getElementById('codeComments').value = '';

                        // Reset button
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;

                        // Refresh page to show updated status
                        location.reload();
                    }, 2000);
                }

                submitDocAssignment() {
                    const submitBtn = document.getElementById('submitDocAssignment');
                    const originalText = submitBtn.innerHTML;

                    submitBtn.innerHTML = '<i class="mr-2 fas fa-spinner fa-spin"></i>Uploading...';
                    submitBtn.disabled = true;

                    // Simulate upload process
                    setTimeout(() => {
                        alert('Document assignment submitted successfully!');
                        this.closeModal('docUploadModal');
                        this.clearSelectedDocFile();
                        document.getElementById('docComments').value = '';

                        // Reset button
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;

                        // Refresh page to show updated status
                        location.reload();
                    }, 2000);
                }

                formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                }

                openModal(modalId) {
                    const modal = document.getElementById(modalId);
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }

                closeModal(modalId) {
                    const modal = document.getElementById(modalId);
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            }

            // Assignment Filter and Search Manager
            class AssignmentFilterManager {
                constructor() {
                    this.initializeFilters();
                    this.initializeSearch();
                }

                initializeFilters() {
                    const filterButtons = document.querySelectorAll('.filter-btn');
                    filterButtons.forEach(button => {
                        button.addEventListener('click', (e) => {
                            this.handleFilterClick(e.target);
                        });
                    });
                }

                initializeSearch() {
                    const searchInput = document.getElementById('assignmentSearch');
                    if (searchInput) {
                        searchInput.addEventListener('input', (e) => {
                            this.handleSearch(e.target.value);
                        });
                    }
                }

                handleFilterClick(button) {
                    // Update active filter button
                    document.querySelectorAll('.filter-btn').forEach(btn => {
                        btn.classList.remove('bg-blue-100', 'text-blue-800');
                        btn.classList.add('bg-gray-100', 'text-gray-700');
                    });

                    button.classList.remove('bg-gray-100', 'text-gray-700');
                    button.classList.add('bg-blue-100', 'text-blue-800');

                    const filter = button.getAttribute('data-filter');
                    this.filterAssignments(filter);
                }

                filterAssignments(filter) {
                    const rows = document.querySelectorAll('#assignmentsTable tbody tr');

                    rows.forEach(row => {
                        let shouldShow = true;

                        if (filter !== 'all') {
                            const assignmentType = this.getAssignmentType(row);
                            const assignmentStatus = this.getAssignmentStatus(row);

                            switch (filter) {
                                case 'quiz':
                                    shouldShow = assignmentType === 'quiz';
                                    break;
                                case 'technical':
                                    shouldShow = assignmentType === 'technical';
                                    break;
                                case 'document':
                                    shouldShow = assignmentType === 'document';
                                    break;
                                case 'overdue':
                                    shouldShow = assignmentStatus === 'overdue';
                                    break;
                            }
                        }

                        row.style.display = shouldShow ? '' : 'none';
                    });
                }

                getAssignmentType(row) {
                    const typeSpan = row.querySelector('.bg-blue-100, .bg-purple-100, .bg-green-100, .bg-gray-200');
                    if (typeSpan) {
                        const text = typeSpan.textContent.toLowerCase();
                        if (text.includes('quiz')) return 'quiz';
                        if (text.includes('technical')) return 'technical';
                        if (text.includes('document')) return 'document';
                    }
                    return 'unknown';
                }

                getAssignmentStatus(row) {
                    const statusSpan = row.querySelector('.bg-red-100');
                    if (statusSpan && statusSpan.textContent.toLowerCase().includes('overdue')) {
                        return 'overdue';
                    }
                    return 'normal';
                }

                handleSearch(searchTerm) {
                    const rows = document.querySelectorAll('#assignmentsTable tbody tr');
                    const term = searchTerm.toLowerCase();

                    rows.forEach(row => {
                        const title = row.querySelector('.font-medium').textContent.toLowerCase();
                        const course = row.cells[1].textContent.toLowerCase();

                        const shouldShow = title.includes(term) || course.includes(term);
                        row.style.display = shouldShow ? '' : 'none';
                    });
                }
            }

            // Initialize managers when DOM is loaded
            document.addEventListener('DOMContentLoaded', () => {
                new AssignmentManager();
                new AssignmentFilterManager();

                // Add deadline warning notifications
                setTimeout(() => {
                    const overdueAssignments = document.querySelectorAll('.text-red-600');
                    const dueTodayAssignments = document.querySelectorAll('.text-orange-600');

                    let overdueCount = 0;
                    let dueTodayCount = 0;

                    overdueAssignments.forEach(el => {
                        if (el.textContent.includes('OVERDUE')) overdueCount++;
                    });

                    dueTodayAssignments.forEach(el => {
                        if (el.textContent.includes('Due today')) dueTodayCount++;
                    });

                    if (overdueCount > 0 || dueTodayCount > 0) {
                        let message = '';
                        if (overdueCount > 0) {
                            message += `⚠️ You have ${overdueCount} overdue assignment(s). `;
                        }
                        if (dueTodayCount > 0) {
                            message += `📅 You have ${dueTodayCount} assignment(s) due today.`;
                        }

                        const notification = document.createElement('div');
                        notification.className = 'fixed top-4 right-4 bg-orange-100 border border-orange-400 text-orange-700 px-4 py-3 rounded-lg shadow-lg z-50 max-w-sm';
                        notification.innerHTML = `
                        <div class="flex items-start">
                            <i class="mt-1 mr-2 text-orange-500 fas fa-exclamation-triangle"></i>
                            <div>
                                <p class="font-medium">Assignment Reminder</p>
                                <p class="text-sm">${message}</p>
                            </div>
                            <button class="ml-2 text-orange-500 hover:text-orange-700" onclick="this.parentElement.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                        document.body.appendChild(notification);

                        // Auto-remove after 8 seconds
                        setTimeout(() => {
                            if (notification.parentElement) {
                                notification.remove();
                            }
                        }, 8000);
                    }
                }, 1000);
            });
        </script>
</body>

</html>