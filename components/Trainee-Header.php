<!-- Gamified Header for Saltel Learning Platform -->
<header class="px-6 py-4 bg-white border-b border-gray-200 shadow-sm" style="z-index: 10; position: relative;">
    <div class="flex items-center justify-between">
        <!-- Gamified Progress & Quest Panel -->
        <div class="flex-1 max-w-2xl">
            <div class="flex items-center">
                <!-- XP & Level Display -->
                <div class="flex items-center px-4 py-2 space-x-3 text-white transition-all duration-300 transform shadow-lg bg-saltel rounded-xl hover:shadow-xl">
                    <div class="flex items-center space-x-2">
                        <i class="text-yellow-400 fas fa-star"></i>
                        <div class="text-left">
                            <p class="text-xs font-medium opacity-90">Level 8</p>
                            <p class="text-sm font-bold">2,450 XP</p>
                        </div>
                    </div>
                    <div class="w-16 h-2 bg-white rounded-full bg-opacity-30">
                        <div class="h-2 transition-all duration-500 bg-yellow-400 rounded-full" style="width: 78%"></div>
                    </div>
                    <span class="text-xs opacity-90">550 to Level 9</span>
                </div>
            </div>
        </div>

        <!-- Right Section: Notifications & User Menu -->
        <div class="flex items-center space-x-4">
            <!-- Enhanced Notifications -->
            <div class="relative">
                <button id="notificationBtn" class="relative text-gray-600 transition-all duration-300 transform bg-white border-2 border-transparent rounded-full shadow-lg size-12 notification-btn hover:text-blue-600 hover:shadow-xl hover:border-blue-200">
                    <i class="text-xl fas fa-bell"></i>
                    <span class="absolute flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 rounded-full notification-badge -top-1 -right-1">4</span>
                </button>

                <!-- Notification Dropdown -->
                <div id="notificationDropdown" class="absolute right-0 mt-2 transition-all duration-300 transform translate-y-2 bg-white border border-gray-200 shadow-2xl opacity-0 pointer-events-none top-full w-96 rounded-xl" style="z-index: 99999; position: fixed;">
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Notifications</h3>
                            <button class="text-sm font-medium text-blue-600 hover:text-blue-800">Mark all read</button>
                        </div>

                        <div class="space-y-3 overflow-y-auto max-h-80">
                            <!-- Assignment Due Notification -->
                            <div class="flex items-start p-3 space-x-3 transition-all duration-300 border border-gray-200 rounded-lg notification-item bg-gray-50 hover:bg-gray-100">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-10 h-10 bg-red-500 rounded-full">
                                        <i class="text-white fas fa-exclamation-triangle"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">Assignment Due Soon!</p>
                                    <p class="text-xs text-gray-600">Data Science Quiz due today at 11:59 PM</p>
                                    <p class="mt-1 text-xs text-gray-500">2 hours remaining</p>
                                </div>
                                <button class="text-red-600 hover:text-red-800">
                                    <i class="text-sm fas fa-external-link-alt"></i>
                                </button>
                            </div>

                            <!-- New Badge Earned -->
                            <div class="flex items-start p-3 space-x-3 transition-all duration-300 border border-gray-200 rounded-lg notification-item bg-gray-50 hover:bg-gray-100">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-10 h-10 bg-yellow-500 rounded-full">
                                        <i class="text-white fas fa-medal"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">New Badge Earned! 🏆</p>
                                    <p class="text-xs text-gray-600">"Quick Learner" badge unlocked</p>
                                    <p class="mt-1 text-xs text-gray-500">+50 XP bonus • 15 minutes ago</p>
                                </div>
                            </div>

                            <!-- Course Progress -->
                            <div class="flex items-start p-3 space-x-3 transition-all duration-300 border border-gray-200 rounded-lg notification-item bg-gray-50 hover:bg-gray-100">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-10 h-10 bg-green-500 rounded-full">
                                        <i class="text-white fas fa-chart-line"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">Course Progress Update</p>
                                    <p class="text-xs text-gray-600">You're now 75% through Machine Learning Basics</p>
                                    <p class="mt-1 text-xs text-gray-500">1 hour ago</p>
                                </div>
                            </div>

                            <!-- New Content Unlocked -->
                            <div class="flex items-start p-3 space-x-3 transition-all duration-300 border border-gray-200 rounded-lg notification-item bg-gray-50 hover:bg-gray-100">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-10 h-10 bg-purple-500 rounded-full">
                                        <i class="text-white fas fa-unlock"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">New Content Unlocked! 🔓</p>
                                    <p class="text-xs text-gray-600">Advanced Analytics module is now available</p>
                                    <p class="mt-1 text-xs text-gray-500">3 hours ago</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 mt-4 border-t border-gray-200">
                            <button class="w-full px-4 py-2 text-sm text-gray-600 transition-colors rounded-lg hover:text-gray-800 hover:bg-gray-50">
                                View All Notifications
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gamified User Avatar & Dropdown -->
            <div class="relative">
                <button id="userMenuBtn" class="flex items-center p-1 space-x-3 transition-all duration-300 transform bg-white border-2 border-transparent shadow-lg user-menu-btn rounded-xl hover:shadow-xl hover:border-purple-200">
                    <div class="relative">
                        <!-- Avatar with Level Ring -->
                        <div class="size-10 rounded-full bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 p-0.5">
                            <div class="flex items-center justify-center w-full h-full bg-white rounded-full">
                                <span class="text-lg font-bold text-transparent bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text">CD</span>
                            </div>
                        </div>
                        <!-- Level Badge -->
                        <div class="absolute flex items-center justify-center border-2 border-white rounded-full size-5 -bottom-1 -right-1 bg-gradient-to-r from-yellow-400 to-orange-500">
                            <span class="text-[9px] font-bold text-white">8</span>
                        </div>
                    </div>
                    <div class="hidden text-left md:block">
                        <p class="text-sm font-bold text-gray-900">C. David</p>
                        <p class="text-xs text-gray-600">Level 8 • 2,450 XP</p>
                    </div>
                    <i class="text-gray-400 transition-transform duration-300 fas fa-chevron-down" id="userChevron"></i>
                </button>

                <!-- User Dropdown Menu -->
                <div id="userDropdown" class="absolute right-0 mt-2 transition-all duration-300 transform translate-y-2 bg-white border border-gray-200 shadow-2xl opacity-0 pointer-events-none top-full w-80 rounded-xl" style="z-index: 99999; position: fixed;">
                    <div class="p-4">
                        <!-- User Info Header -->
                        <div class="flex items-center pb-4 space-x-3 border-b border-gray-200">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 p-0.5">
                                <div class="flex items-center justify-center w-full h-full bg-white rounded-full">
                                    <span class="text-xl font-bold text-transparent bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text">CD</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900">Christopher David</h3>
                                <p class="text-sm text-gray-600">Computer Science Student</p>
                                <div class="flex items-center mt-1 space-x-2">
                                    <span class="px-2 py-1 text-xs font-medium text-purple-800 bg-purple-100 rounded-full">Level 8</span>
                                    <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">2,450 XP</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="grid grid-cols-3 gap-3 py-4 border-b border-gray-200">
                            <div class="text-center">
                                <div class="text-lg font-bold text-blue-600">8</div>
                                <div class="text-xs text-gray-500">Courses</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-green-600">5</div>
                                <div class="text-xs text-gray-500">Certificates</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-purple-600">12</div>
                                <div class="text-xs text-gray-500">Badges</div>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-2">
                            <button class="flex items-center w-full px-3 py-2 space-x-3 transition-colors rounded-lg hover:bg-gray-50 hover:text-gray-600">
                                <i class="w-5 fas fa-user"></i>
                                <span class="font-medium">Profile</span>
                            </button>
                            <button class="flex items-center w-full px-3 py-2 space-x-3 transition-colors rounded-lg hover:bg-gray-50 hover:text-gray-600">
                                <i class="w-5 fas fa-cog"></i>
                                <span class="font-medium">Settings</span>
                            </button>
                            <button class="flex items-center w-full px-3 py-2 space-x-3 transition-colors rounded-lg hover:bg-blue-50 hover:text-blue-600">
                                <i class="w-5 fas fa-question-circle"></i>
                                <span class="font-medium">FAQ</span>
                            </button>
                        </div>

                        <!-- Bottom Actions -->
                        <div class="pt-2 border-t border-gray-200">
                            <button class="flex items-center w-full px-3 py-2 space-x-3 transition-colors rounded-lg hover:bg-red-50 hover:text-red-600">
                                <i class="w-5 fas fa-sign-out-alt"></i>
                                <span class="font-medium">Sign Out</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- JavaScript for Interactive Elements -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quest Panel Toggle
        const questBtn = document.getElementById('questPanelBtn');
        const questDropdown = document.getElementById('questDropdown');

        if (questBtn && questDropdown) {
            questBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleDropdown(questDropdown);
            });
        }

        // Notification Toggle
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationDropdown = document.getElementById('notificationDropdown');

        if (notificationBtn && notificationDropdown) {
            notificationBtn.addEventListener('click', function(e) {
                e.stopPropagation();

                // Calculate position for fixed dropdown
                const rect = notificationBtn.getBoundingClientRect();
                notificationDropdown.style.top = (rect.bottom + 8) + 'px';
                notificationDropdown.style.right = (window.innerWidth - rect.right) + 'px';

                toggleDropdown(notificationDropdown);
            });
        }

        // User Menu Toggle
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');
        const userChevron = document.getElementById('userChevron');

        if (userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();

                // Calculate position for fixed dropdown
                const rect = userMenuBtn.getBoundingClientRect();
                userDropdown.style.top = (rect.bottom + 8) + 'px';
                userDropdown.style.right = (window.innerWidth - rect.right) + 'px';

                toggleDropdown(userDropdown);
                if (userChevron) {
                    userChevron.classList.toggle('rotate-180');
                }
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function() {
            closeAllDropdowns();
        });

        function toggleDropdown(dropdown) {
            const isOpen = !dropdown.classList.contains('opacity-0');

            // Close all dropdowns first
            closeAllDropdowns();

            // Open the clicked dropdown if it wasn't already open
            if (!isOpen) {
                dropdown.classList.remove('opacity-0', 'translate-y-2', 'pointer-events-none');
                dropdown.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
            }
        }

        function closeAllDropdowns() {
            const dropdowns = [questDropdown, notificationDropdown, userDropdown];
            dropdowns.forEach(dropdown => {
                if (dropdown) {
                    dropdown.classList.add('opacity-0', 'translate-y-2', 'pointer-events-none');
                    dropdown.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
                }
            });

            if (userChevron) {
                userChevron.classList.remove('rotate-180');
            }
        }

        // Animate XP bar on load
        setTimeout(() => {
            const xpBar = document.querySelector('.bg-yellow-400');
            if (xpBar) {
                xpBar.style.width = '0%';
                setTimeout(() => {
                    xpBar.style.width = '78%';
                }, 500);
            }
        }, 1000);
    });
</script>