<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Transactions</title>
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
                    <h1 class="text-2xl font-bold text-gray-900">Transactions</h1>
                    <p class="mt-1 text-sm text-gray-600">Track course payments and revenue analytics</p>
                </div>

                <!-- Summary Cards -->
                <div id="summaryCards" class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Cards will be loaded here -->
                </div>

                <!-- Analytics Section -->
                <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-2">
                    <!-- Revenue Trend Chart -->
                    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Revenue Trend</h3>
                            <select id="periodFilter" class="px-3 py-1 text-sm border border-gray-300 rounded">
                                <option value="all">All Time</option>
                                <option value="year">This Year</option>
                                <option value="month">This Month</option>
                                <option value="week">This Week</option>
                                <option value="today">Today</option>
                            </select>
                        </div>
                        <div class="h-64">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    <!-- Top Courses -->
                    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Top Performing Courses</h3>
                        <div id="topCoursesList" class="space-y-3">
                            <!-- Top courses will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Transaction History</h3>
                        <div class="flex flex-wrap gap-4">
                            <select id="courseFilter" class="px-4 py-2 text-sm border border-gray-300 rounded-lg">
                                <option value="">All Courses</option>
                                <!-- Courses will be loaded dynamically -->
                            </select>
                            <select id="statusFilter" class="px-4 py-2 text-sm border border-gray-300 rounded-lg">
                                <option value="all">All Status</option>
                                <option value="paid">Paid</option>
                                <option value="pending">Pending</option>
                            </select>
                            <input type="date" id="dateFromFilter" class="px-4 py-2 text-sm border border-gray-300 rounded-lg" placeholder="From Date">
                            <input type="date" id="dateToFilter" class="px-4 py-2 text-sm border border-gray-300 rounded-lg" placeholder="To Date">
                            <input type="text" id="searchInput" placeholder="Search student..." class="px-4 py-2 text-sm border border-gray-300 rounded-lg">
                            <button id="exportBtn" class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700">
                                <i class="mr-2 fas fa-download"></i>Export
                            </button>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div id="loadingState" class="py-8 text-center">
                        <i class="mb-2 text-2xl text-gray-400 fas fa-spinner fa-spin"></i>
                        <p class="text-gray-500">Loading transactions...</p>
                    </div>

                    <!-- Transactions Table -->
                    <div id="transactionsTableContainer" class="hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Student</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Course</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="transactionsTableBody" class="bg-white divide-y divide-gray-200">
                                    <!-- Transactions will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- No Transactions State -->
                    <div id="noTransactionsState" class="hidden py-12 text-center">
                        <i class="mb-4 text-4xl text-gray-300 fas fa-receipt"></i>
                        <h3 class="mb-2 text-lg font-medium text-gray-900">No transactions found</h3>
                        <p class="text-gray-500">No transactions match your current filters.</p>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        let transactions = [];
        let courses = [];
        let summaryData = {};
        let revenueChart = null;

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCourses();
            loadSummary();
            loadTransactions();
            setupEventListeners();
        });

        function setupEventListeners() {
            document.getElementById('courseFilter').addEventListener('change', loadTransactions);
            document.getElementById('statusFilter').addEventListener('change', loadTransactions);
            document.getElementById('dateFromFilter').addEventListener('change', loadTransactions);
            document.getElementById('dateToFilter').addEventListener('change', loadTransactions);
            document.getElementById('searchInput').addEventListener('input', debounce(loadTransactions, 300));
            document.getElementById('periodFilter').addEventListener('change', loadSummary);
            document.getElementById('exportBtn').addEventListener('click', exportTransactions);
        }

        async function loadCourses() {
            try {
                const response = await fetch('../api/courses/courses.php');
                const result = await response.json();
                
                if (result.courses) {
                    courses = result.courses;
                    populateCourseFilter();
                }
            } catch (error) {
                console.error('Error loading courses:', error);
            }
        }

        function populateCourseFilter() {
            const select = document.getElementById('courseFilter');
            select.innerHTML = '<option value="">All Courses</option>';
            
            courses.forEach(course => {
                const option = document.createElement('option');
                option.value = course.course_id;
                option.textContent = course.course_title;
                select.appendChild(option);
            });
        }

        async function loadSummary() {
            try {
                const period = document.getElementById('periodFilter').value;
                const response = await fetch(`../api/transactions/transactions.php?summary=1&period=${period}`);
                const result = await response.json();
                
                if (result.summary) {
                    summaryData = result;
                    renderSummaryCards();
                    renderRevenueChart();
                    renderTopCourses();
                }
            } catch (error) {
                console.error('Error loading summary:', error);
            }
        }

        function renderSummaryCards() {
            const container = document.getElementById('summaryCards');
            const summary = summaryData.summary;
            
            container.innerHTML = `
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-md">
                                <i class="text-green-600 fas fa-dollar-sign"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                                <dd class="text-lg font-medium text-gray-900">${summary.formatted_total_revenue}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-yellow-100 rounded-md">
                                <i class="text-yellow-600 fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending Revenue</dt>
                                <dd class="text-lg font-medium text-gray-900">${summary.formatted_pending_revenue}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-md">
                                <i class="text-blue-600 fas fa-users"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Enrollments</dt>
                                <dd class="text-lg font-medium text-gray-900">${summary.total_enrollments}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-8 h-8 bg-purple-100 rounded-md">
                                <i class="text-purple-600 fas fa-percentage"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Conversion Rate</dt>
                                <dd class="text-lg font-medium text-gray-900">${summary.conversion_rate}%</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            `;
        }

        function renderRevenueChart() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            
            if (revenueChart) {
                revenueChart.destroy();
            }
            
            const monthlyData = summaryData.monthly_trend || [];
            const labels = monthlyData.map(item => {
                const date = new Date(item.month + '-01');
                return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
            });
            const data = monthlyData.map(item => item.revenue);
            
            revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue',
                        data: data,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
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
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        function renderTopCourses() {
            const container = document.getElementById('topCoursesList');
            const topCourses = summaryData.top_courses || [];
            
            if (topCourses.length === 0) {
                container.innerHTML = '<p class="text-gray-500">No course data available</p>';
                return;
            }
            
            container.innerHTML = topCourses.map((course, index) => `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <span class="flex items-center justify-center w-6 h-6 mr-3 text-xs font-medium text-white bg-blue-600 rounded-full">
                            ${index + 1}
                        </span>
                        <div>
                            <p class="text-sm font-medium text-gray-900">${course.course_title}</p>
                            <p class="text-xs text-gray-500">${course.enrollments} enrollments</p>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-green-600">${course.formatted_revenue}</span>
                </div>
            `).join('');
        }

        async function loadTransactions() {
            const loadingState = document.getElementById('loadingState');
            const tableContainer = document.getElementById('transactionsTableContainer');
            const noTransactionsState = document.getElementById('noTransactionsState');
            
            // Show loading state
            loadingState.classList.remove('hidden');
            tableContainer.classList.add('hidden');
            noTransactionsState.classList.add('hidden');
            
            try {
                const params = new URLSearchParams();
                
                const courseId = document.getElementById('courseFilter').value;
                const status = document.getElementById('statusFilter').value;
                const dateFrom = document.getElementById('dateFromFilter').value;
                const dateTo = document.getElementById('dateToFilter').value;
                const search = document.getElementById('searchInput').value;
                
                if (courseId) params.append('course_id', courseId);
                if (status && status !== 'all') params.append('status', status);
                if (dateFrom) params.append('date_from', dateFrom);
                if (dateTo) params.append('date_to', dateTo);
                if (search) params.append('search', search);
                
                const response = await fetch(`../api/transactions/transactions.php?${params.toString()}`);
                const result = await response.json();
                
                if (result.transactions) {
                    transactions = result.transactions;
                    renderTransactions();
                } else {
                    showError(result.error || 'Failed to load transactions');
                }
            } catch (error) {
                console.error('Error loading transactions:', error);
                showError('Failed to load transactions');
            } finally {
                loadingState.classList.add('hidden');
            }
        }

        function renderTransactions() {
            const tableContainer = document.getElementById('transactionsTableContainer');
            const noTransactionsState = document.getElementById('noTransactionsState');
            const tbody = document.getElementById('transactionsTableBody');
            
            if (transactions.length === 0) {
                tableContainer.classList.add('hidden');
                noTransactionsState.classList.remove('hidden');
                return;
            }
            
            noTransactionsState.classList.add('hidden');
            tableContainer.classList.remove('hidden');
            
            tbody.innerHTML = '';
            
            transactions.forEach(transaction => {
                const row = createTransactionRow(transaction);
                tbody.appendChild(row);
            });
        }

        function createTransactionRow(transaction) {
            const row = document.createElement('tr');
            
            const statusClass = transaction.payment_status === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
            const transactionDate = new Date(transaction.transaction_date).toLocaleDateString();
            const transactionTime = new Date(transaction.transaction_date).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 bg-gray-200 rounded-full">
                            <span class="text-sm font-medium text-gray-600">${transaction.student_name.charAt(0)}</span>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">${transaction.student_name}</div>
                            <div class="text-sm text-gray-500">${transaction.student_email}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${transaction.course_title}</div>
                    <div class="text-xs text-gray-500">${transaction.category}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${transaction.formatted_amount}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full ${statusClass}">
                        ${transaction.payment_status}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${transactionDate}</div>
                    <div class="text-xs text-gray-500">${transactionTime}</div>
                </td>
                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                    <button onclick="viewTransactionDetails(${transaction.enrollment_id})" class="text-blue-600 hover:text-blue-900">
                        View Details
                    </button>
                </td>
            `;
            
            return row;
        }

        function viewTransactionDetails(enrollmentId) {
            const transaction = transactions.find(t => t.enrollment_id === enrollmentId);
            if (!transaction) return;
            
            alert(`Transaction Details:
Student: ${transaction.student_name}
Course: ${transaction.course_title}
Amount: ${transaction.formatted_amount}
Status: ${transaction.payment_status}
Date: ${new Date(transaction.transaction_date).toLocaleString()}`);
        }

        function exportTransactions() {
            if (transactions.length === 0) {
                showError('No transactions to export');
                return;
            }
            
            const csvContent = generateCSV();
            downloadCSV(csvContent, 'transactions.csv');
        }

        function generateCSV() {
            const headers = ['Student Name', 'Student Email', 'Course Title', 'Category', 'Amount', 'Status', 'Date'];
            const rows = transactions.map(t => [
                t.student_name,
                t.student_email,
                t.course_title,
                t.category,
                t.amount,
                t.payment_status,
                new Date(t.transaction_date).toLocaleString()
            ]);
            
            const csvArray = [headers, ...rows];
            return csvArray.map(row => row.map(field => `"${field}"`).join(',')).join('\n');
        }

        function downloadCSV(content, filename) {
            const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', filename);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        function showError(message) {
            const existingAlert = document.querySelector('.alert');
            if (existingAlert) {
                existingAlert.remove();
            }

            const alert = document.createElement('div');
            alert.className = 'alert fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50';
            alert.innerHTML = `
                <div class="flex items-center">
                    <i class="mr-2 fas fa-exclamation-circle"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            document.body.appendChild(alert);

            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        }
    </script>
</body>

</html>
