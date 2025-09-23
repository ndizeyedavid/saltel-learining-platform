<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • My Profile</title>
    <?php include '../../include/trainer-imports.php'; ?>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainer-Sidebar.php'; ?>

        <div class="flex flex-col flex-1 overflow-hidden">
            <?php include '../../components/Trainer-Header.php'; ?>

            <main class="flex-1 overflow-y-auto">
                <div class="px-6 py-8 mx-auto">
                    <!-- Profile Header -->
                    <div class="flex items-center justify-between mb-8">
                        <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
                        <button class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            Save Changes
                        </button>
                    </div>

                    <!-- Profile Content -->
                    <div class="space-y-6">
                        <!-- Profile Picture -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Profile Picture</h2>
                            <div class="flex items-center space-x-6">
                                <div class="flex items-center justify-center w-24 h-24 overflow-hidden bg-gradient-to-r from-saltel to-secondary rounded-xl">
                                    <span class="text-3xl font-medium text-white">MJ</span>
                                </div>
                                <div>
                                    <button class="px-4 py-2 text-sm text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50">
                                        Upload New Photo
                                    </button>
                                    <p class="mt-2 text-xs text-gray-500">Maximum file size: 2MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Personal Information</h2>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">First Name</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="Mellow">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Last Name</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="Joseph">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" value="mellow@saltel.com">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Phone</label>
                                    <input type="tel" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Expertise -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Expertise</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Areas of Expertise</label>
                                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        placeholder="e.g., Web Development, Data Science">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Bio</label>
                                    <textarea class="w-full h-32 px-3 py-2 border border-gray-300 rounded-lg resize-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Tell us about yourself"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Social Links -->
                        <div class="p-6 bg-white rounded-lg shadow-sm">
                            <h2 class="mb-4 text-lg font-semibold text-gray-900">Social Links</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">LinkedIn</label>
                                    <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        placeholder="https://linkedin.com/in/username">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Website</label>
                                    <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        placeholder="https://yourwebsite.com">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>