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
                <a href="../dashboard/" id="active" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-[#17a3d6]">
                    <i class="flex items-center justify-center mr-3 size-5 fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>

            <!-- Core Learning Features -->
            <li>
                <a href="../courses/" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-[#17a3d6]">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-graduation-cap"></i>
                    My Courses
                </a>
            </li>
            <li>
                <a href="../enrollments/" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-[#17a3d6]">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-user-plus"></i>
                    Enrollments
                </a>
            </li>
            <li>
                <a href="../assignments/" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-[#17a3d6]">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-tasks"></i>
                    Assignments
                </a>
            </li>
            <li>
                <a href="../submissions/" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-[#17a3d6]">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-upload"></i>
                    Submissions
                </a>
            </li>

            <!-- Content & Resources -->
            <li>
                <a href="../content/" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-[#17a3d6]">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-folder-open"></i>
                    Course Content
                </a>
            </li>
            <li>
                <a href="../certificates/" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-[#17a3d6]">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-certificate"></i>
                    Certificates
                </a>
            </li>

            <!-- Communication & Collaboration -->
            <li>
                <a href="../discussions/" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-[#17a3d6]">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-comments"></i>
                    Discussions
                </a>
            </li>

            <!-- User Management (Role-based visibility) -->
            <li class="admin-only" style="display: none;">
                <a href="../users/" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-caritas">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-users"></i>
                    User Management
                </a>
            </li>
            <li class="teacher-only" style="display: none;">
                <a href="../students/" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-caritas">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-user-graduate"></i>
                    My Students
                </a>
            </li>

            <!-- Reports & Analytics -->
            <li class="teacher-admin-only" style="display: none;">
                <a href="../reports/" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 transition-colors rounded-lg hover:bg-gray-50 hover:text-caritas">
                    <i class="flex items-center justify-center w-5 h-5 mr-3 fas fa-chart-bar"></i>
                    Reports
                </a>
            </li>
        </ul>
    </nav>
</div>