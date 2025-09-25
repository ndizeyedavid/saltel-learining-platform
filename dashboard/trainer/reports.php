<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Trainer Reports</title>
    <?php include '../../include/trainer-imports.php'; ?>
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
                    <h1 class="text-2xl font-bold text-gray-900">Training Analytics Dashboard</h1>
                    <p class="mt-1 text-sm text-gray-600">Comprehensive overview of course performance and trainee progress</p>
                </div>

                <!-- Date Range Filter -->
                <div class="p-4 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="flex flex-wrap items-center gap-4">
                        <select class="px-4 py-2 text-sm border border-gray-300 rounded-lg" id="timeFilter">
                            <option>Last 7 Days</option>
                            <option>Last 30 Days</option>
                            <option>Last 3 Months</option>
                            <option>Last 6 Months</option>
                        </select>
                        <select class="px-4 py-2 text-sm border border-gray-300 rounded-lg" id="courseFilter">
                            <option>All Courses</option>
                            <option>Web Development</option>
                            <option>Data Science</option>
                            <option>Mobile Development</option>
                        </select>
                        <button class="bg-[#17a3d6] text-white px-4 py-2 rounded-lg hover:bg-[#1792c0] transition-colors">
                            <i class="mr-2 fas fa-download"></i>Export Report
                        </button>
                    </div>
                </div>

                <!-- Key Metrics -->
                <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Course Completion Rate -->
                    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-500">Course Completion</h3>
                            <span class="text-sm text-green-500">↑ 8%</span>
                        </div>
                        <p class="text-2xl font-semibold text-gray-900">78%</p>
                        <div class="w-full h-2 mt-2 bg-gray-200 rounded-full">
                            <div class="h-2 bg-green-500 rounded-full" style="width: 78%"></div>
                        </div>
                    </div>

                    <!-- Average Score -->
                    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-500">Avg. Score</h3>
                            <span class="text-sm text-green-500">↑ 5%</span>
                        </div>
                        <p class="text-2xl font-semibold text-gray-900">82%</p>
                        <div class="w-full h-2 mt-2 bg-gray-200 rounded-full">
                            <div class="bg-[#17a3d6] h-2 rounded-full" style="width: 82%"></div>
                        </div>
                    </div>

                    <!-- Active Trainees -->
                    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-500">Active Trainees</h3>
                            <span class="text-sm text-green-500">↑ 12%</span>
                        </div>
                        <p class="text-2xl font-semibold text-gray-900">143</p>
                    </div>

                    <!-- Satisfaction Rating -->
                    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium text-gray-500">Satisfaction</h3>
                            <span class="text-sm text-green-500">↑ 3%</span>
                        </div>
                        <div class="flex items-center">
                            <p class="text-2xl font-semibold text-gray-900">4.6</p>
                            <div class="ml-2 text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
                    <!-- Course Progress -->
                    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900">Course Progress</h2>
                        <canvas id="courseProgressChart" height="300"></canvas>
                    </div>

                    <!-- Trainee Performance -->
                    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900">Trainee Performance</h2>
                        <canvas id="performanceChart" height="300"></canvas>
                    </div>
                </div>

                <!-- Detailed Analytics -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Top Performers -->
                    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900">Top Performers</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Trainee</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Course</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Score</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Progress</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="topPerformersTable">
                                    <!-- Will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Course Engagement -->
                    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900">Course Engagement</h2>
                        <canvas id="engagementChart" height="300"></canvas>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Charts Initialization with Mock Data -->
    <script>
        // Mock data generation
        function generateMockData() {
            return {
                courseProgress: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6'],
                    data: [15, 30, 45, 60, 75, 85]
                },
                performance: {
                    labels: ['90-100%', '80-89%', '70-79%', '60-69%', 'Below 60%'],
                    data: [12, 28, 35, 15, 10]
                },
                engagement: {
                    labels: ['Video Views', 'Assignments', 'Discussions', 'Quizzes', 'Resources'],
                    data: [85, 75, 60, 80, 65]
                },
                topPerformers: [{
                        name: 'Sarah Johnson',
                        course: 'Web Development',
                        score: '98%',
                        progress: '100%'
                    },
                    {
                        name: 'Michael Brown',
                        course: 'Data Science',
                        score: '96%',
                        progress: '95%'
                    },
                    {
                        name: 'Emily Davis',
                        course: 'Mobile Development',
                        score: '94%',
                        progress: '90%'
                    },
                    {
                        name: 'David Wilson',
                        course: 'Web Development',
                        score: '92%',
                        progress: '88%'
                    },
                    {
                        name: 'Jessica Lee',
                        course: 'Data Science',
                        score: '90%',
                        progress: '85%'
                    }
                ]
            };
        }

        // Initialize charts with mock data
        function initializeCharts() {
            const mockData = generateMockData();

            // Course Progress Chart
            new Chart(document.getElementById('courseProgressChart'), {
                type: 'line',
                data: {
                    labels: mockData.courseProgress.labels,
                    datasets: [{
                        label: 'Average Completion',
                        data: mockData.courseProgress.data,
                        borderColor: '#17a3d6',
                        backgroundColor: 'rgba(23, 163, 214, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });

            // Performance Chart
            new Chart(document.getElementById('performanceChart'), {
                type: 'bar',
                data: {
                    labels: mockData.performance.labels,
                    datasets: [{
                        label: 'Number of Trainees',
                        data: mockData.performance.data,
                        backgroundColor: '#17a3d6'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Engagement Chart
            new Chart(document.getElementById('engagementChart'), {
                type: 'radar',
                data: {
                    labels: mockData.engagement.labels,
                    datasets: [{
                        label: 'Engagement Level',
                        data: mockData.engagement.data,
                        borderColor: '#17a3d6',
                        backgroundColor: 'rgba(23, 163, 214, 0.2)',
                        pointBackgroundColor: '#17a3d6'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        r: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });

            // Populate top performers table
            const tableBody = document.getElementById('topPerformersTable');
            mockData.topPerformers.forEach(performer => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${performer.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${performer.course}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${performer.score}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: ${performer.progress}"></div>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', initializeCharts);
    </script>
</body>

</html>