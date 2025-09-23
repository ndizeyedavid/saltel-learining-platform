<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Settings</title>
    <?php include '../../include/trainer-imports.php'; ?>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainer-Sidebar.php'; ?>

        <div class="flex flex-col flex-1 overflow-hidden">
            <?php include '../../components/Trainer-Header.php'; ?>

            <main class="flex-1 overflow-y-auto">
                <div class="px-6 py-8 mx-auto">
                    <h1 class="mb-8 text-2xl font-bold text-gray-900">Settings</h1>

                    <div class="space-y-6">
                        <!-- Account Settings -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Account Settings</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Current Password</label>
                                    <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">New Password</label>
                                    <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Confirm New Password</label>
                                    <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                                <button class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                    Update Password
                                </button>
                            </div>
                        </div>

                        <!-- Notification Settings -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Notification Settings</h2>
                            <div class="space-y-4">
                                <label class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked>
                                    <span class="ml-2 text-sm text-gray-700">Course enrollments</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked>
                                    <span class="ml-2 text-sm text-gray-700">Assignment submissions</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked>
                                    <span class="ml-2 text-sm text-gray-700">Course comments and discussions</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked>
                                    <span class="ml-2 text-sm text-gray-700">Email notifications</span>
                                </label>
                            </div>
                        </div>

                        <!-- Privacy Settings -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Privacy Settings</h2>
                            <div class="space-y-4">
                                <label class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked>
                                    <span class="ml-2 text-sm text-gray-700">Show my profile to students</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked>
                                    <span class="ml-2 text-sm text-gray-700">Show my social links</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Allow direct messages</span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>