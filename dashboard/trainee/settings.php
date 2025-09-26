<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Saltel Learning Platform</title>
    <?php
    include '../../include/imports.php';
    require_once '../../include/connect.php';

    session_start();
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../../');
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Get user information
    $user_query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if student profile exists
    $student_query = "SELECT * FROM students WHERE user_id = ?";
    $stmt = $conn->prepare($student_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    $profile_complete = !empty($student);
    ?>
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

                    <!-- Profile Completion Alert -->
                    <?php if (!$profile_complete): ?>
                        <div class="p-4 mb-6 border border-yellow-200 rounded-lg bg-yellow-50">
                            <div class="flex items-center">
                                <i class="mr-3 text-yellow-600 fas fa-exclamation-triangle"></i>
                                <div>
                                    <h3 class="text-sm font-medium text-yellow-800">Complete Your Student Profile</h3>
                                    <p class="text-sm text-yellow-700">
                                        <?php if (isset($_GET['incomplete']) && $_GET['incomplete'] == '1'): ?>
                                            You need to complete your student profile before you can access courses and enroll in them.
                                        <?php else: ?>
                                            Please complete your student profile to access all course features and enroll in courses.
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Settings Navigation -->
                    <div class="mb-8 bg-white shadow-sm rounded-xl">
                        <div class="border-b border-gray-200">
                            <nav class="flex px-6 space-x-8">
                                <button class="px-1 py-4 text-sm font-medium <?php echo !$profile_complete ? 'text-blue-600 border-b-2 border-blue-500' : 'text-gray-500 border-b-2 border-transparent hover:text-gray-700'; ?> whitespace-nowrap settings-tab <?php echo !$profile_complete ? 'active' : ''; ?>" data-tab="profile">
                                    Student Profile <?php echo !$profile_complete ? '<span class="px-2 py-1 ml-1 text-xs text-white bg-red-500 rounded-full">Required</span>' : '<span class="px-2 py-1 ml-1 text-xs text-white bg-green-500 rounded-full">Complete</span>'; ?>
                                </button>
                                <button class="px-1 py-4 text-sm font-medium <?php echo $profile_complete ? 'text-blue-600 border-b-2 border-blue-500' : 'text-gray-500 border-b-2 border-transparent hover:text-gray-700'; ?> whitespace-nowrap settings-tab <?php echo $profile_complete ? 'active' : ''; ?>" data-tab="account">
                                    Account Settings
                                </button>
                                <button class="hidden px-1 py-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 whitespace-nowrap settings-tab" data-tab="notifications">
                                    Notifications
                                </button>
                                <button class="hidden px-1 py-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 whitespace-nowrap settings-tab" data-tab="privacy">
                                    Privacy & Security
                                </button>
                                <button class="hidden px-1 py-4 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 whitespace-nowrap settings-tab" data-tab="preferences">
                                    Learning Preferences
                                </button>
                            </nav>
                        </div>
                    </div>

                    <!-- Student Profile Tab -->
                    <div id="profile-tab" class="settings-content <?php echo !$profile_complete ? '' : 'hidden'; ?>">
                        <div class="p-6 bg-white shadow-sm rounded-xl">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-xl font-semibold text-gray-900">Complete Your Student Profile</h2>
                                <?php if ($profile_complete): ?>
                                    <span class="px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">
                                        <i class="mr-1 fas fa-check"></i>Profile Complete
                                    </span>
                                <?php endif; ?>
                            </div>

                            <form id="studentProfileForm" class="space-y-6">
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <?php
                                    $user_query = "SELECT * FROM users WHERE user_id = ?";
                                    $stmt = $conn->prepare($user_query);
                                    $stmt->bind_param("i", $user_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $user = $result->fetch_assoc();
                                    ?>

                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">First Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Last Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                    </div>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Middle Name</label>
                                    <input type="text" name="middle_name" value="<?php echo htmlspecialchars($user['middle_name'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                    </div>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Gender <span class="text-red-500">*</span></label>
                                    <select name="gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" <?php echo ($user['gender'] ?? '') === 'Male' ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo ($user['gender'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
                                        <option value="Other" <?php echo ($user['gender'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>

                                <div class="pt-6 border-t">
                                    <h3 class="mb-4 text-lg font-medium text-gray-900">Academic Information</h3>

                                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-700">School <span class="text-red-500">*</span></label>
                                            <input type="text" name="institution" value="<?php echo htmlspecialchars($student['institution'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="School Name" required>
                                        </div>
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-700">Class / Level <span class="text-red-500">*</span></label>
                                            <input type="text" name="level_year" value="<?php echo htmlspecialchars($student['level_year'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="S4 , S5 , S6 / L3 , L4 , L5" required>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Program/Field of Study <span class="text-red-500">*</span></label>
                                        <input type="text" name="program" value="<?php echo htmlspecialchars($student['program'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., Computer Science, Business Administration" required>
                                    </div>
                                </div>

                                <div class="flex justify-end space-x-3">
                                    <button type="button" onclick="skipProfile()" class="px-6 py-2 text-gray-600 transition-colors border border-gray-300 rounded-lg hover:text-gray-700 hover:bg-gray-50">
                                        Skip for Now
                                    </button>
                                    <button type="submit" class="px-6 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                        <i class="mr-2 fas fa-save"></i>Save Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Account Settings Tab -->
                    <div id="account-tab" class="settings-content <?php echo $profile_complete ? '' : 'hidden'; ?>">
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

                // Student profile form submission
                const studentProfileForm = document.getElementById('studentProfileForm');
                if (studentProfileForm) {
                    studentProfileForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        saveStudentProfile();
                    });
                }
            });

            // Save student profile
            async function saveStudentProfile() {
                const form = document.getElementById('studentProfileForm');
                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;

                // Show loading state
                submitBtn.innerHTML = '<i class="mr-2 fas fa-spinner fa-spin"></i>Saving...';
                submitBtn.disabled = true;

                try {
                    const response = await fetch('../api/trainee/profile.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        Swal.fire({
                            title: 'Profile Saved!',
                            text: 'Your student profile has been completed successfully.',
                            icon: 'success',
                            confirmButtonText: 'Continue',
                            confirmButtonColor: '#10b981'
                        }).then(() => {
                            // Reload the page to update the UI
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: result.error || 'Failed to save profile. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                } catch (error) {
                    console.error('Profile save error:', error);
                    Swal.fire({
                        title: 'Network Error',
                        text: 'Unable to save profile. Please check your connection and try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } finally {
                    // Reset button state
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            }

            // Skip profile completion
            function skipProfile() {
                Swal.fire({
                    title: 'Skip Profile?',
                    text: 'You can complete your profile later, but some features may be limited.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Skip for Now',
                    cancelButtonText: 'Complete Profile',
                    confirmButtonColor: '#6b7280',
                    cancelButtonColor: '#10b981'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to courses page
                        window.location.href = 'courses.php';
                    }
                });
            }
        </script>
        <script src="../../assets/js/app.js"></script>
</body>

</html>