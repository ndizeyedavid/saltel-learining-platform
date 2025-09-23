<header class="px-6 py-4 bg-white border-b border-gray-200 shadow-sm">
    <div class="flex items-center justify-between">
        <!-- Search Bar -->
        <div class="flex-1 max-w-lg">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="text-[#17a3d6] fas fa-search"></i>
                </div>
                <input type="text"
                    class="block w-full py-2 pl-10 pr-3 text-sm outline-none border-2 rounded-lg border-[#17a3d6]"
                    placeholder="Search">
            </div>
        </div>

        <!-- User Profile Section -->
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <div class="relative">
                <button id="notificationBtn" class="relative p-2 text-gray-400 transition-colors hover:text-saltel">
                    <i class="text-lg fas fa-bell"></i>
                    <span class="absolute flex items-center justify-center w-4 h-4 text-xs text-white rounded-full -top-1 -right-1 bg-saltel">3</span>
                </button>
                <!-- Notifications Dropdown -->
                <div id="notificationDropdown" class="absolute right-0 hidden w-80 mt-2 overflow-hidden bg-white border border-gray-200 rounded-lg shadow-lg">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <!-- Notification Items -->
                        <a href="#" class="block p-4 hover:bg-gray-50 border-b border-gray-200">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-user-graduate text-blue-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">New course enrollment</p>
                                    <p class="text-sm text-gray-500">John Doe enrolled in Web Development</p>
                                    <p class="mt-1 text-xs text-gray-400">2 hours ago</p>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="block p-4 hover:bg-gray-50 border-b border-gray-200">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-check text-green-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Assignment Submitted</p>
                                    <p class="text-sm text-gray-500">New submission for JavaScript Quiz</p>
                                    <p class="mt-1 text-xs text-gray-400">5 hours ago</p>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="block p-4 hover:bg-gray-50">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <i class="fas fa-comment text-yellow-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">New Discussion</p>
                                    <p class="text-sm text-gray-500">Student question in Data Science forum</p>
                                    <p class="mt-1 text-xs text-gray-400">1 day ago</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="p-4 bg-gray-50 border-t border-gray-200">
                        <a href="#" class="block text-sm text-center text-blue-600 hover:text-blue-700">View All Notifications</a>
                    </div>
                </div>
            </div>

            <!-- User Menu -->
            <div class="relative">
                <div class="flex items-center space-x-3">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-r from-saltel to-secondary">
                        <span class="text-sm font-medium text-white">MJ</span>
                    </div>
                    <div class="hidden md:block">
                        <p class="text-sm font-medium text-gray-900">Mellow</p>
                        <p class="text-xs text-gray-500">Trainer</p>
                    </div>
                    <button id="profileBtn" class="text-gray-400 transition-colors hover:text-saltel">
                        <i class="text-sm fas fa-chevron-down"></i>
                    </button>
                </div>
                <!-- Profile Dropdown -->
                <div id="profileDropdown" class="absolute right-0 hidden w-56 mt-2 overflow-hidden bg-white border border-gray-200 rounded-lg shadow-lg">
                    <div class="p-4 border-b border-gray-200">
                        <p class="text-sm font-medium text-gray-900">Signed in as</p>
                        <p class="text-sm text-gray-600">mellow@saltel.com</p>
                    </div>
                    <div class="py-2">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="w-5 fas fa-user-circle mr-2"></i>My Profile
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="w-5 fas fa-cog mr-2"></i>Settings
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="w-5 fas fa-question-circle mr-2"></i>Help Center
                        </a>
                    </div>
                    <div class="py-2 border-t border-gray-200">
                        <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                            <i class="w-5 fas fa-sign-out-alt mr-2"></i>Sign Out
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="../../assets/css/dropdown.css">
    <script src="../../assets/js/header-dropdowns.js"></script>
</header>