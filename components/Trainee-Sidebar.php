<?php
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!-- Sidebar -->
<div class="flex flex-col w-64 bg-white border-r border-gray-200 shadow-lg">
    <!-- Logo Section -->
    <div class="p-[16.7px] border-b border-gray-200">
        <!-- <div class="border-b border-gray-200"> -->
        <div class="flex flex-col items-center justify-center">
            <img src="../../assets/images/logo.png" alt="Logo" class="w-[143px]">
            <!-- <h1 class="text-xl font-bold text-gray-900">E-Learning</h1> -->
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 py-6">
        <ul class="px-4 space-y-1">
            <!-- Dashboard -->
            <li>
                <a href="./" <?php echo ($current_page == 'index') ? 'id="active"' : ''; ?> class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-[#17a3d6]">
                    <i class="flex items-center justify-center mr-3 size-5 fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="courses.php" <?php echo ($current_page == 'courses') ? 'id="active"' : ''; ?> class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-[#17a3d6]">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-search"></i>
                    Browse Courses
                </a>
            </li>
            <li>
                <a href="assignments.php" <?php echo ($current_page == 'assignments') ? 'id="active"' : ''; ?> class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-[#17a3d6]">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-tasks"></i>
                    My Assignments
                </a>
            </li>

            <li>
                <a href="progress.php" <?php echo ($current_page == 'progress') ? 'id="active"' : ''; ?> class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-[#17a3d6]">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-chart-line"></i>
                    My Progress
                </a>
            </li>
            <li>
                <a href="certificates.php" <?php echo ($current_page == 'certificates') ? 'id="active"' : ''; ?> class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-[#17a3d6]">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-award"></i>
                    My Certificates
                </a>
            </li>

            <!-- Communication -->
            <li>
                <a href="discussions.php" <?php echo ($current_page == 'discussions') ? 'id="active"' : ''; ?> class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-[#17a3d6]">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-comments"></i>
                    Discussions
                </a>
            </li>

        </ul>
    </nav>
</div>