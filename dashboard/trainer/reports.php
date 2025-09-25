<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Reports</title>
    <?php include '../../include/trainer-guard.php'; ?>
    <?php include '../../include/trainer-imports.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainer-Sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <?php include '../../components/Trainer-Header.php'; ?>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-y-auto">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Analytics & Reports</h1>
                    <p class="mt-1 text-sm text-gray-600">Comprehensive insights into your teaching performance and student engagement</p>
                </div>

                <!-- Report Navigation -->
                <div class="p-4 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex flex-wrap gap-2">
                            <button id="overviewTab" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:opacity-90 active-tab">
                                Overview
                            </button>
                            <button id="coursePerformanceTab" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:opacity-90">
                                Course Performance
                            </button>
                            <button id="studentEngagementTab" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:opacity-90">
                                Student Engagement
                            </button>
                            <button id="revenueAnalysisTab" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:opacity-90">
                                Revenue Analysis
                            </button>
                            <button id="assignmentAnalyticsTab" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:opacity-90">
                                Assignment Analytics
                            </button>
                        </div>
                        <div class="flex gap-2">
                            <select id="periodFilter" class="px-4 py-2 text-sm border border-gray-300 rounded-lg">
                                <option value="month">This Month</option>
                                <option value="week">This Week</option>
                                <option value="year">This Year</option>
                                <option value="all">All Time</option>
                            </select>
                            <button id="exportBtn" class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700">
                                <i class="mr-2 fas fa-download"></i>Export
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="loadingState" class="flex items-center justify-center py-12">
                    <div class="text-center">
                        <div class="inline-block w-8 h-8 border-4 border-blue-600 border-solid rounded-full animate-spin border-t-transparent"></div>
                        <p class="mt-2 text-gray-600">Loading reports...</p>
                    </div>
                </div>

                <!-- Overview Report -->
                <div id="overviewReport" class="report-section">
                    <!-- Key Metrics -->
                    <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 lg:grid-cols-4">
                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium text-gray-500">Total Courses</h3>
                                <i class="text-blue-500 fas fa-book"></i>
                            </div>
                            <div id="totalCourses" class="text-2xl font-bold text-gray-900">-</div>
                            <p class="text-xs text-gray-500">Active courses</p>
                        </div>

                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium text-gray-500">Total Students</h3>
                                <i class="text-green-500 fas fa-users"></i>
                            </div>
                            <div id="totalStudents" class="text-2xl font-bold text-gray-900">-</div>
                            <p class="text-xs text-gray-500">Enrolled students</p>
                        </div>

                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium text-gray-500">Total Revenue</h3>
                                <i class="text-yellow-500 fas fa-dollar-sign"></i>
                            </div>
                            <div id="totalRevenue" class="text-2xl font-bold text-gray-900">-</div>
                            <p class="text-xs text-gray-500">All time earnings</p>
                        </div>

                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium text-gray-500">Completion Rate</h3>
                                <i class="text-purple-500 fas fa-chart-line"></i>
                            </div>
                            <div id="completionRate" class="text-2xl font-bold text-gray-900">-</div>
                            <p class="text-xs text-gray-500">Average completion</p>
                        </div>
                    </div>
                </div>

                <!-- Course Performance Report -->
                <div id="coursePerformanceReport" class="hidden report-section">
                    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900">Course Performance</h3>
                            <canvas id="coursePerformanceChart" width="400" height="200" style="height:300px; max-height:300px;"></canvas>
                        </div>
                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900">Top Performing Courses</h3>
                            <div id="topCoursesList" class="space-y-3">
                                <!-- Dynamic content -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Engagement Report -->
                <div id="studentEngagementReport" class="hidden report-section">
                    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900">Student Engagement</h3>
                            <canvas id="engagementChart" width="400" height="200" style="height:300px; max-height:300px;"></canvas>
                        </div>
                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900">Top Students</h3>
                            <div id="topStudentsList" class="space-y-3">
                                <!-- Dynamic content -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Analysis Report -->
                <div id="revenueAnalysisReport" class="hidden report-section">
                    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900">Revenue Trends</h3>
                            <canvas id="revenueChart" width="400" height="200" style="height:300px; max-height:300px;"></canvas>
                        </div>
                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900">Revenue by Course</h3>
                            <div id="revenueBreakdown" class="space-y-3">
                                <!-- Dynamic content -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assignment Analytics Report -->
                <div id="assignmentAnalyticsReport" class="hidden report-section">
                    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900">Assignment Performance</h3>
                            <canvas id="assignmentChart" width="400" height="200" style="height:300px; max-height:300px;"></canvas>
                        </div>
                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900">Assignment Statistics</h3>
                            <div id="assignmentStats" class="space-y-4">
                                <!-- Dynamic content -->
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        let currentReport = 'overview';
        let charts = {};

        // Initialize the dashboard
        document.addEventListener('DOMContentLoaded', function() {
            initializeEventListeners();
            loadReport('overview');
        });

        function initializeEventListeners() {
            // Tab navigation
            document.querySelectorAll('[id$="Tab"]').forEach(tab => {
                tab.addEventListener('click', function() {
                    const reportType = this.id.replace('Tab', '');
                    switchTab(reportType);
                    loadReport(reportType);
                });
            });

            // Period filter
            document.getElementById('periodFilter').addEventListener('change', function() {
                loadReport(currentReport);
            });

            // Export button
            document.getElementById('exportBtn').addEventListener('click', function() {
                exportReport();
            });
        }

        function switchTab(reportType) {
            // Update tab styles
            document.querySelectorAll('[id$="Tab"]').forEach(tab => {
                tab.classList.remove('text-white', 'bg-blue-600', 'active-tab');
                tab.classList.add('text-gray-600', 'bg-gray-100');
            });

            document.getElementById(reportType + 'Tab').classList.remove('text-gray-600', 'bg-gray-100');
            document.getElementById(reportType + 'Tab').classList.add('text-white', 'bg-blue-600', 'active-tab');

            // Show/hide report sections
            document.querySelectorAll('.report-section').forEach(section => {
                section.classList.add('hidden');
            });

            const targetSection = document.getElementById(reportType + 'Report');
            if (targetSection) {
                targetSection.classList.remove('hidden');
            }

            currentReport = reportType;
        }

        async function loadReport(reportType) {
            showLoading();

            try {
                const period = document.getElementById('periodFilter').value;
                const response = await fetch(`../api/reports/reports.php?type=${reportType}&period=${period}`);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                const data = await response.json();
                console.log(data);

                if (data.success) {
                    renderReport(reportType, data.success);
                } else {
                    showError('Failed to load report data');
                }
            } catch (error) {
                console.error('Error loading report:', error);
                showError('Error loading report data');
            } finally {
                hideLoading();
            }
        }

        function renderReport(reportType, data) {
            switch (reportType) {
                case 'overview':
                    renderOverviewReport(data.overview || {});
                    break;
                case 'coursePerformance':
                    renderCoursePerformanceReport(Array.isArray(data.courses) ? data.courses : []);
                    break;
                case 'studentEngagement':
                    renderStudentEngagementReport(data);
                    break;
                case 'revenueAnalysis':
                    renderRevenueAnalysisReport(data);
                    break;
                case 'assignmentAnalytics':
                    renderAssignmentAnalyticsReport(Array.isArray(data.assignments) ? data.assignments : []);
                    break;
            }
        }

        function renderOverviewReport(data) {
            document.getElementById('totalCourses').textContent = data.total_courses ?? '0';
            document.getElementById('totalStudents').textContent = data.total_students ?? '0';
            document.getElementById('totalRevenue').textContent = data.formatted_revenue ?? ('$' + (data.total_revenue ?? '0'));
            document.getElementById('completionRate').textContent = (data.completion_rate ?? '0') + '%';
        }

        function resetCanvas(canvasId) {
            const oldCanvas = document.getElementById(canvasId);
            if (!oldCanvas) return;
            const parent = oldCanvas.parentNode;
            const newCanvas = oldCanvas.cloneNode(false);
            parent.replaceChild(newCanvas, oldCanvas);
        }

        function renderCoursePerformanceReport(courses) {
            // Destroy existing chart
            if (charts.coursePerformance) {
                charts.coursePerformance.destroy();
            }
            resetCanvas('coursePerformanceChart');

            const ctx = document.getElementById('coursePerformanceChart').getContext('2d');
            charts.coursePerformance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: courses.map(c => c.course_title),
                    datasets: [{
                        label: 'Enrollments',
                        data: courses.map(c => c.total_enrollments),
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Render top courses list
            const topCoursesList = document.getElementById('topCoursesList');
            topCoursesList.innerHTML = '';

            if (courses && courses.length) {
                courses.slice(0, 5).forEach((course) => {
                    const courseItem = document.createElement('div');
                    courseItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
                    courseItem.innerHTML = `
                        <div>
                            <h4 class="font-medium text-gray-900">${course.course_title}</h4>
                            <p class="text-sm text-gray-600">${course.total_enrollments} students</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">${course.formatted_revenue ?? ('$' + (course.revenue ?? 0))}</p>
                            <p class="text-sm text-gray-600">${course.completion_rate}% completion</p>
                        </div>
                    `;
                    topCoursesList.appendChild(courseItem);
                });
            }
        }

        function renderStudentEngagementReport(data) {
            // Destroy existing chart
            if (charts.engagement) {
                charts.engagement.destroy();
            }
            resetCanvas('engagementChart');

            const ctx = document.getElementById('engagementChart').getContext('2d');
            const engagementByCourse = Array.isArray(data.course_engagement) ? data.course_engagement : [];
            charts.engagement = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: engagementByCourse.map(e => e.course_title),
                    datasets: [{
                        label: 'Enrolled Students',
                        data: engagementByCourse.map(e => e.enrolled_students),
                        backgroundColor: 'rgba(34, 197, 94, 0.8)',
                        borderColor: 'rgb(34, 197, 94)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Render top students list
            const topStudentsList = document.getElementById('topStudentsList');
            topStudentsList.innerHTML = '';

            if (Array.isArray(data.top_students)) {
                data.top_students.forEach(student => {
                    const studentItem = document.createElement('div');
                    studentItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
                    studentItem.innerHTML = `
                        <div>
                            <h4 class="font-medium text-gray-900">${student.student_name}</h4>
                            <p class="text-sm text-gray-600">${student.courses_enrolled} courses</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">${student.avg_grade ?? 0}%</p>
                            <p class="text-sm text-gray-600">${student.formatted_spent ?? ''}</p>
                        </div>
                    `;
                    topStudentsList.appendChild(studentItem);
                });
            }
        }

        function renderRevenueAnalysisReport(data) {
            // Destroy existing chart
            if (charts.revenue) {
                charts.revenue.destroy();
            }
            resetCanvas('revenueChart');

            const ctx = document.getElementById('revenueChart').getContext('2d');
            charts.revenue = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: (data.monthly_revenue || []).map(m => m.month),
                    datasets: [{
                        label: 'Revenue',
                        data: (data.monthly_revenue || []).map(m => m.revenue),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value;
                                }
                            }
                        }
                    }
                }
            });

            // Render revenue breakdown
            const revenueBreakdown = document.getElementById('revenueBreakdown');
            revenueBreakdown.innerHTML = '';

            if (Array.isArray(data.category_revenue)) {
                data.category_revenue.forEach(category => {
                    const categoryItem = document.createElement('div');
                    categoryItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
                    categoryItem.innerHTML = `
                        <div>
                            <h4 class="font-medium text-gray-900">${category.category}</h4>
                            <p class="text-sm text-gray-600">${category.paid_enrollments} paid enrollments</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">${category.formatted_revenue ?? ('$' + (category.revenue ?? 0))}</p>
                        </div>
                    `;
                    revenueBreakdown.appendChild(categoryItem);
                });
            }
        }

        function renderAssignmentAnalyticsReport(assignments) {
            // Destroy existing chart
            if (charts.assignment) {
                charts.assignment.destroy();
            }
            resetCanvas('assignmentChart');

            const ctx = document.getElementById('assignmentChart').getContext('2d');
            const totals = (assignments || []).reduce((acc, a) => {
                acc.total_submissions += a.total_submissions || 0;
                acc.passed_submissions += a.passing_submissions || 0;
                acc.late_submissions += a.late_submissions || 0;
                if (typeof a.avg_grade === 'number') {
                    acc.grade_sum += a.avg_grade;
                    acc.grade_count += 1;
                }
                return acc;
            }, {
                total_submissions: 0,
                passed_submissions: 0,
                late_submissions: 0,
                grade_sum: 0,
                grade_count: 0
            });
            const average_grade = totals.grade_count ? (totals.grade_sum / totals.grade_count).toFixed(1) : '0';
            const pass_rate = totals.total_submissions ? ((totals.passed_submissions / totals.total_submissions) * 100).toFixed(1) : '0';
            const late_rate = totals.total_submissions ? ((totals.late_submissions / totals.total_submissions) * 100).toFixed(1) : '0';
            charts.assignment = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Submitted', 'Passed', 'Late'],
                    datasets: [{
                        label: 'Count',
                        data: [
                            totals.total_submissions,
                            totals.passed_submissions,
                            totals.late_submissions
                        ],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderColor: [
                            'rgb(59, 130, 246)',
                            'rgb(16, 185, 129)',
                            'rgb(239, 68, 68)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Render assignment statistics
            const assignmentStats = document.getElementById('assignmentStats');
            assignmentStats.innerHTML = `
                <div class="p-4 rounded-lg bg-blue-50">
                    <h4 class="font-medium text-blue-900">Average Grade</h4>
                    <p class="text-2xl font-bold text-blue-600">${average_grade}%</p>
                </div>
                <div class="p-4 rounded-lg bg-green-50">
                    <h4 class="font-medium text-green-900">Pass Rate</h4>
                    <p class="text-2xl font-bold text-green-600">${pass_rate}%</p>
                </div>
                <div class="p-4 rounded-lg bg-yellow-50">
                    <h4 class="font-medium text-yellow-900">Late Submission Rate</h4>
                    <p class="text-2xl font-bold text-yellow-600">${late_rate}%</p>
                </div>
            `;
        }

        function showLoading() {
            document.getElementById('loadingState').classList.remove('hidden');
            document.querySelectorAll('.report-section').forEach(section => {
                section.classList.add('hidden');
            });
        }

        function hideLoading() {
            document.getElementById('loadingState').classList.add('hidden');
            const currentSection = document.getElementById(currentReport + 'Report');
            if (currentSection) {
                currentSection.classList.remove('hidden');
            }
        }

        function showError(message) {
            // You can implement a toast notification here
            console.error(message);
        }

        function exportReport() {
            const period = document.getElementById('periodFilter').value;
            const url = `../api/reports/reports.php?type=${currentReport}&period=${period}&export=csv`;
            window.open(url, '_blank');
        }
    </script>

</body>

</html>