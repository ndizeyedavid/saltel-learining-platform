<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Course Settings</title>
    <?php include '../../include/trainer-guard.php'; ?>
    <?php include '../../include/trainer-imports.php'; ?>
    <script src="../../assets/js/course-state.js"></script>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainer-Sidebar.php'; ?>

        <div class="flex flex-col flex-1 overflow-hidden">
            <?php include '../../components/Trainer-Header.php'; ?>

            <main class="flex-1 p-6 overflow-y-auto">
                <div class="max-w-4xl mx-auto">
                    <div class="flex items-center mb-6">
                        <a href="course-builder.php" class="mr-4 text-gray-600 hover:text-gray-900">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-2xl font-bold text-gray-900">Course Settings</h1>
                    </div>

                    <!-- Settings Sections -->
                    <div class="space-y-6">
                        <!-- General Settings -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">General Settings</h2>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Course Status</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option>Draft</option>
                                        <option>Published</option>
                                        <option>Archived</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Visibility</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option>Public</option>
                                        <option>Private</option>
                                        <option>Password Protected</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Start Date</label>
                                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">End Date</label>
                                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Enrollment Settings -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Enrollment Settings</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Enrollment Type</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option>Open Enrollment</option>
                                        <option>Invitation Only</option>
                                        <option>Manual Approval</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Maximum Students</label>
                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" class="mr-2 text-blue-600 rounded focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">Allow Waitlist</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Completion Requirements -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Completion Requirements</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Minimum Progress</label>
                                    <div class="flex items-center">
                                        <input type="number" class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <span class="ml-2 text-gray-600">%</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Required Assignments</label>
                                    <select multiple class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option>Assignment 1</option>
                                        <option>Assignment 2</option>
                                        <option>Final Project</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Passing Grade</label>
                                    <div class="flex items-center">
                                        <input type="number" class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <span class="ml-2 text-gray-600">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Certificate Settings -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Certificate Settings</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="flex items-center mb-4">
                                        <input type="checkbox" class="mr-2 text-blue-600 rounded focus:ring-blue-500">
                                        <span class="text-sm text-gray-700">Enable Course Certificate</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Certificate Template</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option>Standard Template</option>
                                        <option>Professional Template</option>
                                        <option>Custom Template</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Custom Message</label>
                                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Communication Settings -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Communication Settings</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Announcements</label>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" class="mr-2 text-blue-600 rounded focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">Enable Email Notifications</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" class="mr-2 text-blue-600 rounded focus:ring-blue-500">
                                            <span class="text-sm text-gray-700">Enable In-App Notifications</span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Discussion Settings</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option>Enable All Discussions</option>
                                        <option>Module-Specific Only</option>
                                        <option>Disable Discussions</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-4">
                            <button class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                                Cancel
                            </button>
                            <button onclick="saveSettings()" class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                Save Settings
                            </button>
                            <script>
                                function saveSettings() {
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Course settings have been saved',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = 'courses.php';
                                        }
                                    });
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>