<?php
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>

<!-- Sidebar -->
<div class="flex flex-col w-64 bg-white border-r border-gray-200 shadow-lg">
    <!-- Logo Section -->
    <div class="p-[16.7px] border-b border-gray-200">
        <!-- <div class="border-b border-gray-200"> -->
        <div class="flex flex-col items-center justify-center">
            <img src="../../assets/images/logo.png" alt="Logo" class="w-[120px]">
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

            <!-- Core Learning Features -->
            <li>
                <a href="courses.php" <?php echo ($current_page == 'courses' || $current_page == 'enrollments' || $current_page == 'course-builder' || $current_page == 'course-content' || $current_page == 'course-settings') ? 'id="active"' : ''; ?> class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-[#17a3d6]">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-graduation-cap"></i>
                    My Courses
                </a>
            </li>

            <li>
                <a href="assignments.php" <?php echo ($current_page == 'assignments' || $current_page == 'submissions' || $current_page == 'assignment-builder') ? 'id="active"' : ''; ?> class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-[#17a3d6]">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-tasks"></i>
                    Assignments
                </a>
            </li>
            <!-- <li>
                <a href="submissions.php" <?php echo ($current_page == 'submissions') ? 'id="active"' : ''; ?> class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-[#17a3d6]">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-upload"></i>
                    Submissions
                </a>
            </li> -->

            <!-- Communication & Collaboration -->
            <li>
                <a href="discussions.php" <?php echo ($current_page == 'discussions') ? 'id="active"' : ''; ?> class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-[#17a3d6]">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-comments"></i>
                    Discussions
                </a>
            </li>

            <!-- Reports & Analytics -->
            <li>
                <a href="reports.php" <?php echo ($current_page == 'reports') ? 'id="active"' : ''; ?> class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-saltel">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-chart-bar"></i>
                    Reports
                </a>
            </li>
        </ul>
    </nav>
</div>