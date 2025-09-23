<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Create Assignment</title>
    <?php include '../../include/trainer-imports.php'; ?>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainer-Sidebar.php'; ?>

        <div class="flex flex-col flex-1 overflow-hidden">
            <?php include '../../components/Trainer-Header.php'; ?>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto">
                <div class="max-w-4xl px-6 py-8 mx-auto">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center">
                            <a href="assignments.php" class="p-2 mr-4 text-gray-600 transition-colors rounded-lg hover:bg-gray-100">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Create New Assignment</h1>
                                <p class="mt-1 text-sm text-gray-600">Design your quiz or assignment</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button id="previewBtn" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                <i class="mr-2 fas fa-eye"></i>Preview
                            </button>
                            <button id="publishBtn" class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700">
                                <i class="mr-2 fas fa-check"></i>Publish
                            </button>
                        </div>
                    </div>

                    <!-- Assignment Form -->
                    <div class="space-y-6">
                        <!-- Basic Info -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Assignment Details</h2>
                            <div class="grid gap-6">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Title</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        placeholder="Enter assignment title">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Description</label>
                                    <div id="description" class="h-32"></div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Course</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                            <option value="">Select course</option>
                                            <option>Web Development</option>
                                            <option>Data Science</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Module</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                            <option value="">Select module</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quiz Settings -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Quiz Settings</h2>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Time Limit (minutes)</label>
                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        value="30">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Passing Score (%)</label>
                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        value="75">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Due Date</label>
                                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Attempts Allowed</label>
                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        value="1">
                                </div>
                            </div>
                            <div class="mt-4 space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Randomize question order</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Show results immediately after submission</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked>
                                    <span class="ml-2 text-sm text-gray-700">Enable anti-cheat measures</span>
                                </label>
                            </div>
                        </div>

                        <!-- Questions Section -->
                        <div id="questionsContainer" class="space-y-4">
                            <!-- Question Template -->
                            <div class="p-6 bg-white rounded-lg shadow-sm question-block">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Question 1</h3>
                                    <div class="flex items-center space-x-2">
                                        <button class="p-2 text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-arrows-alt"></i>
                                        </button>
                                        <button class="p-2 text-red-400 hover:text-red-600 delete-question">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Question Text</label>
                                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                            placeholder="Enter your question">
                                    </div>
                                    <div class="options-container space-y-2">
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" name="correct_1" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                            <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                                placeholder="Option 1">
                                            <button class="p-2 text-red-400 hover:text-red-600 delete-option">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <button class="text-sm text-blue-600 hover:text-blue-700 add-option">
                                        <i class="mr-1 fas fa-plus"></i>Add Option
                                    </button>
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Explanation (Optional)</label>
                                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                            placeholder="Explain the correct answer"></textarea>
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Points</label>
                                        <input type="number" class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                            value="1">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add Question Button -->
                        <button id="addQuestionBtn" class="w-full p-4 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100">
                            <i class="mr-2 fas fa-plus"></i>Add Question
                        </button>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="../../assets/js/assignment-builder.js"></script>
</body>

</html>