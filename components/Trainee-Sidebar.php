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

    <?php
    // Get current page name
    $current_page = basename($_SERVER['PHP_SELF']);
    $current_dir = basename(dirname($_SERVER['PHP_SELF']));

    // Define active classes
    $active_class = "flex items-center px-4 py-3 text-sm font-medium text-white bg-[#17a3d6] transition-colors rounded-lg";
    $inactive_class = "flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-[#17a3d6]";
    ?>

    <!-- Navigation Menu -->
    <nav class="flex-1 py-6">
        <ul class="px-4 space-y-1">
            <!-- Dashboard -->
            <li>
                <a href="./" class="<?php echo ($current_page == 'index.php' && $current_dir == 'trainee') ? $active_class : $inactive_class; ?>">
                    <i class="flex items-center justify-center mr-3 size-5 fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="courses.php" class="<?php echo ($current_page == 'courses.php') ? $active_class : $inactive_class; ?>">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-search"></i>
                    Browse Courses
                </a>
            </li>
            <li>
                <a href="assignments/" class="<?php echo ($current_dir == 'assignments') ? $active_class : $inactive_class; ?>">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-tasks"></i>
                    My Assignments
                </a>
            </li>
            <li>
                <a href="submissions/" class="<?php echo ($current_dir == 'submissions') ? $active_class : $inactive_class; ?>">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-upload"></i>
                    My Submissions
                </a>
            </li>

            <li>
                <a href="progress/" class="<?php echo ($current_dir == 'progress') ? $active_class : $inactive_class; ?>">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-chart-line"></i>
                    My Progress
                </a>
            </li>
            <li>
                <a href="certificates/" class="<?php echo ($current_dir == 'certificates') ? $active_class : $inactive_class; ?>">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-award"></i>
                    My Certificates
                </a>
            </li>

            <!-- Communication -->
            <li>
                <a href="discussions/" class="<?php echo ($current_dir == 'discussions') ? $active_class : $inactive_class; ?>">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-comments"></i>
                    Discussions
                </a>
            </li>

        </ul>
    </nav>
</div>