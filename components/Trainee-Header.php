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
            <button class="relative p-2 text-gray-400 transition-colors hover:text-saltel">
                <i class="text-lg fas fa-bell"></i>
                <span class="absolute flex items-center justify-center w-4 h-4 text-xs text-white rounded-full -top-1 -right-1 bg-saltel">3</span>
            </button>

            <!-- User Menu -->
            <div class="flex items-center space-x-3">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-r from-saltel to-secondary">
                    <span class="text-sm font-medium text-white">MJ</span>
                </div>
                <div class="hidden md:block">
                    <p class="text-sm font-medium text-gray-900">Christopher</p>
                    <p class="text-xs text-gray-500">Student</p>
                </div>
                <button class="text-gray-400 transition-colors hover:text-saltel">
                    <i class="text-sm fas fa-chevron-down"></i>
                </button>
            </div>
        </div>
    </div>
</header>