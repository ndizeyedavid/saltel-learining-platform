<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Assignments</title>
    <?php include '../../include/trainer-guard.php'; ?>
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
                    <h1 class="text-2xl font-bold text-gray-900">Assignments</h1>
                    <p class="mt-1 text-sm text-gray-600">Create and manage course assignments</p>
                </div>

                <!-- Assignment Management Tools -->
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div class="flex gap-4">
                        <select id="courseFilter" class="px-4 py-2 text-sm border border-gray-300 rounded-lg">
                            <option value="">All Courses</option>
                            <!-- Courses will be loaded dynamically -->
                        </select>
                        <select id="typeFilter" class="px-4 py-2 text-sm border border-gray-300 rounded-lg">
                            <option value="">All Types</option>
                            <option value="quiz">Quiz</option>
                            <option value="project">Project</option>
                            <option value="essay">Essay</option>
                            <option value="practical">Practical</option>
                        </select>
                    </div>
                    <a href="assignment-builder.php">
                        <button class="bg-[#17a3d6] text-white px-4 py-2 rounded-lg hover:bg-[#1792c0] transition-colors">
                            <i class="mr-2 fas fa-plus"></i>Create Assignment
                        </button>
                    </a>
                </div>

                <!-- Loading State -->
                <div id="loadingState" class="flex items-center justify-center py-12">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">Loading assignments...</p>
                    </div>
                </div>

                <!-- Assignment Cards -->
                <div id="assignmentsList" class="hidden">
                    <!-- Assignments will be loaded here -->
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="text-center py-12 hidden">
                    <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Assignments Yet</h3>
                    <p class="text-gray-600 mb-6">Create your first assignment to get started with student assessments.</p>
                    <a href="assignment-builder.php" class="inline-block bg-[#17a3d6] text-white px-6 py-3 rounded-lg hover:bg-[#1792c0] transition-colors">
                        <i class="mr-2 fas fa-plus"></i>Create Assignment
                    </a>
                </div>
            </main>
        </div>
    </div>

    <script>
        let assignments = [];
        let courses = [];
        let currentFilters = {
            course_id: '',
            type: ''
        };

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCourses();
            loadAssignments();
            setupEventListeners();
        });

        function setupEventListeners() {
            document.getElementById('courseFilter').addEventListener('change', function() {
                currentFilters.course_id = this.value;
                loadAssignments();
            });

            document.getElementById('typeFilter').addEventListener('change', function() {
                currentFilters.type = this.value;
                loadAssignments();
            });
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
            const courseFilter = document.getElementById('courseFilter');
            courseFilter.innerHTML = '<option value="">All Courses</option>';
            
            courses.forEach(course => {
                const option = document.createElement('option');
                option.value = course.course_id;
                option.textContent = course.course_title;
                courseFilter.appendChild(option);
            });
        }

        async function loadAssignments() {
            showLoading();
            
            try {
                let url = '../api/assignments/assignments.php?';
                const params = new URLSearchParams();
                
                if (currentFilters.course_id) {
                    params.append('course_id', currentFilters.course_id);
                }
                if (currentFilters.type) {
                    params.append('type', currentFilters.type);
                }
                
                const response = await fetch(url + params.toString());
                const result = await response.json();
                
                if (result.assignments) {
                    assignments = result.assignments;
                    displayAssignments();
                } else {
                    showError('Failed to load assignments');
                }
            } catch (error) {
                console.error('Error loading assignments:', error);
                showError('Error loading assignments');
            }
        }

        function showLoading() {
            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('assignmentsList').classList.add('hidden');
            document.getElementById('emptyState').classList.add('hidden');
        }

        function displayAssignments() {
            document.getElementById('loadingState').classList.add('hidden');
            
            if (assignments.length === 0) {
                document.getElementById('emptyState').classList.remove('hidden');
                document.getElementById('assignmentsList').classList.add('hidden');
                return;
            }

            document.getElementById('emptyState').classList.add('hidden');
            const assignmentsList = document.getElementById('assignmentsList');
            assignmentsList.className = 'grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3';
            
            assignmentsList.innerHTML = assignments.map(assignment => createAssignmentCard(assignment)).join('');
        }

        function createAssignmentCard(assignment) {
            const typeColors = {
                quiz: 'bg-blue-100 text-blue-800',
                project: 'bg-green-100 text-green-800',
                essay: 'bg-purple-100 text-purple-800',
                practical: 'bg-orange-100 text-orange-800'
            };

            const statusColors = {
                draft: 'bg-gray-100 text-gray-800',
                published: 'bg-green-100 text-green-800',
                archived: 'bg-red-100 text-red-800'
            };

            const dueDate = assignment.due_date ? new Date(assignment.due_date) : null;
            const isOverdue = assignment.is_overdue;
            const daysUntilDue = assignment.days_until_due;

            let dueDateDisplay = '';
            if (dueDate) {
                const options = { year: 'numeric', month: 'short', day: 'numeric' };
                dueDateDisplay = dueDate.toLocaleDateString('en-US', options);
                
                if (isOverdue) {
                    dueDateDisplay = `<span class="text-red-600">Overdue: ${dueDateDisplay}</span>`;
                } else if (daysUntilDue <= 3) {
                    dueDateDisplay = `<span class="text-orange-600">Due: ${dueDateDisplay}</span>`;
                } else {
                    dueDateDisplay = `Due: ${dueDateDisplay}`;
                }
            } else {
                dueDateDisplay = 'No due date';
            }

            const timeLimit = assignment.time_limit ? 
                `${assignment.time_limit} minutes` : 
                (assignment.assignment_type === 'project' ? 'No time limit' : '30 minutes');

            return `
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <span class="px-3 py-1 text-xs font-medium rounded-full ${typeColors[assignment.assignment_type] || 'bg-gray-100 text-gray-800'}">
                                ${assignment.assignment_type.charAt(0).toUpperCase() + assignment.assignment_type.slice(1)}
                            </span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full ${statusColors[assignment.status] || 'bg-gray-100 text-gray-800'}">
                                ${assignment.status.charAt(0).toUpperCase() + assignment.status.slice(1)}
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="editAssignment(${assignment.assignment_id})" class="text-gray-400 hover:text-blue-600 transition-colors" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteAssignment(${assignment.assignment_id})" class="text-gray-400 hover:text-red-600 transition-colors" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    
                    <h3 class="mb-2 text-lg font-semibold text-gray-900">${assignment.title}</h3>
                    <p class="mb-3 text-sm text-gray-600 line-clamp-2">${assignment.description || 'No description provided'}</p>
                    
                    <div class="mb-3 text-xs text-gray-500">
                        <span class="font-medium">${assignment.course_title}</span>
                    </div>
                    
                    <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                        <span><i class="mr-2 fas fa-clock"></i>${timeLimit}</span>
                        <span><i class="mr-2 fas fa-calendar"></i>${dueDateDisplay}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span><i class="mr-1 fas fa-users"></i>${assignment.submission_count} submissions</span>
                            <span><i class="mr-1 fas fa-check-circle"></i>${assignment.graded_count} graded</span>
                        </div>
                        <a href="submissions.php?assignment_id=${assignment.assignment_id}" class="text-[#17a3d6] hover:text-[#1792c0] font-medium text-sm">
                            View Submissions
                        </a>
                    </div>
                </div>
            `;
        }

        function editAssignment(assignmentId) {
            window.location.href = `assignment-builder.php?id=${assignmentId}`;
        }

        async function deleteAssignment(assignmentId) {
            if (!confirm('Are you sure you want to delete this assignment? This action cannot be undone.')) {
                return;
            }

            try {
                const response = await fetch(`../api/assignments/assignments.php?assignment_id=${assignmentId}`, {
                    method: 'DELETE'
                });

                const result = await response.json();

                if (result.success) {
                    // Remove from local array
                    assignments = assignments.filter(a => a.assignment_id !== assignmentId);
                    displayAssignments();
                } else {
                    alert(result.error || 'Failed to delete assignment');
                }
            } catch (error) {
                console.error('Error deleting assignment:', error);
                alert('Failed to delete assignment');
            }
        }

        function showError(message) {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('assignmentsList').innerHTML = `
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-400 mb-4"></i>
                    <p class="text-red-600">${message}</p>
                </div>
            `;
            document.getElementById('assignmentsList').classList.remove('hidden');
        }
    </script>
</body>

</html>