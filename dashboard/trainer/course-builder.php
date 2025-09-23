<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Course Builder</title>
    <?php include '../../include/trainer-imports.php'; ?>
    <script src="../../assets/js/course-state.js"></script>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainer-Sidebar.php'; ?>

        <div class="flex flex-col flex-1 overflow-hidden">
            <?php include '../../components/Trainer-Header.php'; ?>

            <main class="flex-1 p-6 overflow-y-auto">
                <!-- Course Builder Form -->
                <div class="max-w-3xl mx-auto">
                    <!-- Step Navigation -->
                    <div class="mb-8">
                    </div>

                    <!-- Form Fields -->
                    <div class="space-y-6">
                        <!-- Course Details -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold">Course Details</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Course Title</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Enter course title">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Course Description</label>
                                    <textarea class="w-full h-32 px-3 py-2 border border-gray-300 rounded-lg resize-none focus:ring-2 focus:ring-blue-500" placeholder="Describe your course"></textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Category</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                            <option value="">Select category</option>
                                            <option>Development</option>
                                            <option>Business</option>
                                            <option>Design</option>
                                            <option>Marketing</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Level</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                            <option value="">Select level</option>
                                            <option>Beginner</option>
                                            <option>Intermediate</option>
                                            <option>Advanced</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Course Image -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold">Course Image</h2>
                            <div class="flex items-center justify-center p-6 border-2 border-gray-300 border-dashed rounded-lg">
                                <div class="text-center">
                                    <i class="mb-4 text-3xl text-gray-400 fas fa-cloud-upload-alt"></i>
                                    <p class="mb-2 text-sm text-gray-600">Drag and drop your image here</p>
                                    <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                                    <button class="px-4 py-2 mt-4 text-sm text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50">
                                        Browse Files
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation -->
                        <div class="flex justify-between">
                            <a href="courses.php">
                                <button class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                                    Cancel
                                </button>
                            </a>
                            <div class="space-x-4">
                                <button class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                                    Save Draft
                                </button>
                                <a href="course-content.php">
                                    <button class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                        Next: Course Content
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>