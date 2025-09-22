<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Saltel Learning Platform</title>
    <?php include '../../include/imports.php'; ?>
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
                <div class="max-w-4xl mx-auto">
                    <h1 class="mb-8 text-3xl font-bold text-gray-900">Settings</h1>

                    <!-- Settings Navigation -->
                    <div class="mb-8 bg-white shadow-sm rounded-xl">
                        <div class="border-b border-gray-200">
                            <nav class="flex px-6 space-x-8">
                                <button class="px-1 py-4 text-sm font-medium text-blue-600 border-b-2 border-blue-500 whitespace-nowrap settings-tab active" data-tab="account">
                                    Account Settings
                                </button>
                                <button class="px-1 py-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 whitespace-nowrap settings-tab" data-tab="notifications">
                                    Notifications
                                </button>
                                <button class="px-1 py-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 whitespace-nowrap settings-tab" data-tab="privacy">
                                    Privacy & Security
                                </button>
                                <button class="px-1 py-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 whitespace-nowrap settings-tab" data-tab="preferences">
                                    Learning Preferences
                                </button>
                            </nav>
                        </div>
                    </div>

                    <!-- Account Settings Tab -->
                    <div id="account-tab" class="settings-content">
                        <div class="p-6 mb-6 bg-white shadow-sm rounded-xl">
                            <h2 class="mb-6 text-xl font-semibold text-gray-900">Personal Information</h2>
                            <form class="space-y-6">
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">First Name</label>
                                        <input type="text" value="Christopher" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Last Name</label>
                                        <input type="text" value="David" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Email Address</label>
                                    <input type="email" value="christopherdavid@email.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Phone Number</label>
                                    <input type="tel" value="+250 788 888 888" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Bio</label>
                                    <textarea rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">Passionate data scientist with 3 years of experience in machine learning and analytics.</textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="px-6 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="p-6 bg-white shadow-sm rounded-xl">
                            <h2 class="mb-6 text-xl font-semibold text-gray-900">Change Password</h2>
                            <form class="space-y-6">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Current Password</label>
                                    <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">New Password</label>
                                    <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Confirm New Password</label>
                                    <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="px-6 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Notifications Tab -->
                    <div id="notifications-tab" class="hidden settings-content">
                        <div class="p-6 bg-white shadow-sm rounded-xl">
                            <h2 class="mb-6 text-xl font-semibold text-gray-900">Notification Preferences</h2>
                            <div class="space-y-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-medium text-gray-900">Email Notifications</h3>
                                        <p class="text-sm text-gray-500">Receive course updates and announcements via email</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" checked class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-medium text-gray-900">Push Notifications</h3>
                                        <p class="text-sm text-gray-500">Get notified about new lessons and achievements</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" checked class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-medium text-gray-900">Weekly Progress Report</h3>
                                        <p class="text-sm text-gray-500">Receive weekly summaries of your learning progress</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Privacy Tab -->
                    <div id="privacy-tab" class="hidden settings-content">
                        <div class="p-6 mb-6 bg-white shadow-sm rounded-xl">
                            <h2 class="mb-6 text-xl font-semibold text-gray-900">Privacy Settings</h2>
                            <div class="space-y-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-medium text-gray-900">Profile Visibility</h3>
                                        <p class="text-sm text-gray-500">Make your profile visible to other learners</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" checked class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-medium text-gray-900">Show Learning Progress</h3>
                                        <p class="text-sm text-gray-500">Display your course progress on your profile</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" checked class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 bg-white shadow-sm rounded-xl">
                            <h2 class="mb-6 text-xl font-semibold text-gray-900">Two-Factor Authentication</h2>
                            <div class="flex items-center justify-between p-4 rounded-lg bg-green-50">
                                <div class="flex items-center space-x-3">
                                    <i class="text-green-600 fas fa-shield-alt"></i>
                                    <div>
                                        <h3 class="font-medium text-gray-900">2FA is enabled</h3>
                                        <p class="text-sm text-gray-500">Your account is protected with two-factor authentication</p>
                                    </div>
                                </div>
                                <button class="px-4 py-2 text-white transition-colors bg-red-600 rounded-lg hover:bg-red-700">
                                    Disable 2FA
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Preferences Tab -->
                    <div id="preferences-tab" class="hidden settings-content">
                        <div class="p-6 bg-white shadow-sm rounded-xl">
                            <h2 class="mb-6 text-xl font-semibold text-gray-900">Learning Preferences</h2>
                            <div class="space-y-6">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Preferred Learning Time</label>
                                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option>Morning (6AM - 12PM)</option>
                                        <option selected>Afternoon (12PM - 6PM)</option>
                                        <option>Evening (6PM - 12AM)</option>
                                        <option>Night (12AM - 6AM)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Daily Study Goal (minutes)</label>
                                    <input type="number" value="60" min="15" max="480" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Learning Style</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <label class="flex items-center space-x-2">
                                            <input type="radio" name="learning_style" value="visual" checked class="text-blue-600">
                                            <span>Visual Learner</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="radio" name="learning_style" value="auditory" class="text-blue-600">
                                            <span>Auditory Learner</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="radio" name="learning_style" value="kinesthetic" class="text-blue-600">
                                            <span>Kinesthetic Learner</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="radio" name="learning_style" value="mixed" class="text-blue-600">
                                            <span>Mixed Style</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" class="px-6 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                        Save Preferences
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <script>
            // Settings tab functionality
            document.addEventListener('DOMContentLoaded', function() {
                const tabs = document.querySelectorAll('.settings-tab');
                const contents = document.querySelectorAll('.settings-content');

                tabs.forEach(tab => {
                    tab.addEventListener('click', function() {
                        const targetTab = this.dataset.tab;

                        // Remove active class from all tabs
                        tabs.forEach(t => {
                            t.classList.remove('active', 'border-blue-500', 'text-blue-600');
                            t.classList.add('border-transparent', 'text-gray-500');
                        });

                        // Add active class to clicked tab
                        this.classList.add('active', 'border-blue-500', 'text-blue-600');
                        this.classList.remove('border-transparent', 'text-gray-500');

                        // Hide all content
                        contents.forEach(content => {
                            content.classList.add('hidden');
                        });

                        // Show target content
                        document.getElementById(targetTab + '-tab').classList.remove('hidden');
                    });
                });
            });
        </script>
        <script src="../../assets/js/app.js"></script>
</body>

</html>