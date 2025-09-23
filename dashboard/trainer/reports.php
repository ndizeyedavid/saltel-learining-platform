<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Reports & Analytics</title>
    <?php include '../../include/trainer-imports.php'; ?>
    <!-- Chart.js for data visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="font-sans bg-gray-50">
    <div class="flex overflow-hidden h-screen">
        <?php include '../../components/Trainer-Sidebar.php'; ?>
        
        <!-- Main Content Area -->
        <div class="flex overflow-hidden flex-col flex-1">
            <?php include '../../components/Trainer-Header.php'; ?>
            
            <!-- Main Content -->
            <main class="overflow-y-auto flex-1 p-6">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Reports & Analytics</h1>
                    <p class="mt-1 text-sm text-gray-600">Track course performance and student progress</p>
                </div>

                <!-- Date Range Filter -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                    <div class="flex flex-wrap gap-4 items-center">
                        <select class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                            <option>Last 7 Days</option>
                            <option>Last 30 Days</option>
                            <option>Last 3 Months</option>
                            <option>Last 6 Months</option>
                            <option>Custom Range</option>
                        </select>
                        <select class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                            <option>All Courses</option>
                            <option>Web Development</option>
                            <option>Data Science</option>
                        </select>
                        <button class="bg-[#17a3d6] text-white px-4 py-2 rounded-lg hover:bg-[#1792c0] transition-colors">
                            <i class="fas fa-download mr-2"></i>Export Report
                        </button>
                    </div>
                </div>

                <!-- Key Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Average Course Rating -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-500">Avg. Course Rating</h3>
                            <span class="text-green-500 text-sm">↑ 12%</span>
                        </div>
                        <div class="flex items-center">
                            <p class="text-2xl font-semibold text-gray-900">4.8</p>
                            <div class="ml-2 text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Completion Rate -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-500">Completion Rate</h3>
                            <span class="text-green-500 text-sm">↑ 8%</span>
                        </div>
                        <p class="text-2xl font-semibold text-gray-900">85%</p>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>

                    <!-- Average Assignment Score -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-500">Avg. Assignment Score</h3>
                            <span class="text-red-500 text-sm">↓ 3%</span>
                        </div>
                        <p class="text-2xl font-semibold text-gray-900">78%</p>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-[#17a3d6] h-2 rounded-full" style="width: 78%"></div>
                        </div>
                    </div>

                    <!-- Student Engagement -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-500">Student Engagement</h3>
                            <span class="text-green-500 text-sm">↑ 15%</span>
                        </div>
                        <p class="text-2xl font-semibold text-gray-900">92%</p>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full" style="width: 92%"></div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Course Progress Chart -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Course Progress Overview</h2>
                        <canvas id="courseProgressChart" height="300"></canvas>
                    </div>

                    <!-- Student Performance Distribution -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Student Performance Distribution</h2>
                        <canvas id="performanceDistribution" height="300"></canvas>
                    </div>
                </div>

                <!-- Detailed Analytics -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Top Performing Students -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Top Performing Students</h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gray-200"></div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Sarah Johnson</p>
                                        <p class="text-xs text-gray-500">Web Development</p>
                                    </div>
                                </div>
                                <span class="text-sm font-medium text-gray-900">98%</span>
                            </div>
                            <!-- More student entries -->
                        </div>
                    </div>

                    <!-- Assignment Completion Rates -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Assignment Completion</h2>
                        <canvas id="assignmentCompletion" height="250"></canvas>
                    </div>

                    <!-- Course Engagement Metrics -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Engagement Metrics</h2>
                        <canvas id="engagementMetrics" height="250"></canvas>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Charts Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Course Progress Chart
            const courseProgressCtx = document.getElementById('courseProgressChart');
            if (courseProgressCtx) {
                new Chart(courseProgressCtx, {
                    type: 'line',
                    data: {
                        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6'],
                        datasets: [{
                            label: 'Average Progress',
                            data: [20, 35, 45, 60, 75, 85],
                            borderColor: '#17a3d6',
                            tension: 0.4,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }

            // Performance Distribution
            const performanceDistCtx = document.getElementById('performanceDistribution');
            if (performanceDistCtx) {
                new Chart(performanceDistCtx, {
                    type: 'bar',
                    data: {
                        labels: ['90-100%', '80-89%', '70-79%', '60-69%', 'Below 60%'],
                        datasets: [{
                            label: 'Number of Students',
                            data: [15, 25, 20, 10, 5],
                            backgroundColor: '#17a3d6'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }

            // Assignment Completion
            const assignmentCompCtx = document.getElementById('assignmentCompletion');
            if (assignmentCompCtx) {
                new Chart(assignmentCompCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Completed', 'In Progress', 'Not Started'],
                        datasets: [{
                            data: [70, 20, 10],
                            backgroundColor: ['#4CAF50', '#FFC107', '#F44336']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }

            // Engagement Metrics
            const engagementCtx = document.getElementById('engagementMetrics');
            if (engagementCtx) {
                new Chart(engagementCtx, {
                    type: 'radar',
                    data: {
                        labels: ['Video Views', 'Discussion Posts', 'Assignment Submissions', 'Quiz Attempts', 'Resource Downloads'],
                        datasets: [{
                            label: 'Current Period',
                            data: [85, 70, 75, 80, 90],
                            borderColor: '#17a3d6',
                            backgroundColor: 'rgba(23, 163, 214, 0.2)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            r: {
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>