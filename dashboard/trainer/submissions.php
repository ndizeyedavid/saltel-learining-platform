<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Submissions</title>
    <?php include '../../include/trainer-guard.php'; ?>
    <?php include '../../include/trainer-imports.php'; ?>
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
                    <h1 class="text-2xl font-bold text-gray-900">Submissions</h1>
                    <p class="mt-1 text-sm text-gray-600">Review and grade student submissions</p>
                </div>

                <!-- Submission Filters -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex flex-wrap gap-4 items-center mb-6">
                        <select id="assignmentFilter" class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                            <option value="">All Assignments</option>
                            <!-- Assignments will be loaded dynamically -->
                        </select>
                        <select id="statusFilter" class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                            <option value="all">All Status</option>
                            <option value="pending">Pending Review</option>
                            <option value="graded">Graded</option>
                            <option value="late">Late</option>
                        </select>
                        <input type="text" id="searchInput" placeholder="Search student..." class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                        <button id="refreshBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-refresh mr-2"></i>Refresh
                        </button>
                    </div>

                    <!-- Loading State -->
                    <div id="loadingState" class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">Loading submissions...</p>
                    </div>

                    <!-- Submissions Table -->
                    <div id="submissionsTableContainer" class="hidden">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignment</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="submissionsTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Submissions will be loaded here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- No Submissions State -->
                    <div id="noSubmissionsState" class="hidden text-center py-12">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No submissions found</h3>
                        <p class="text-gray-500">No submissions match your current filters.</p>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Grading Modal -->
    <div id="gradingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Grade Submission</h3>
                    <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div id="modalContent" class="space-y-4">
                    <!-- Modal content will be loaded here -->
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button id="cancelGrading" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Cancel
                    </button>
                    <button id="saveGrade" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Save Grade
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let submissions = [];
        let assignments = [];
        let currentSubmission = null;

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadAssignments();
            loadSubmissions();
            setupEventListeners();
        });

        function setupEventListeners() {
            document.getElementById('assignmentFilter').addEventListener('change', loadSubmissions);
            document.getElementById('statusFilter').addEventListener('change', loadSubmissions);
            document.getElementById('searchInput').addEventListener('input', debounce(loadSubmissions, 300));
            document.getElementById('refreshBtn').addEventListener('click', loadSubmissions);
            document.getElementById('closeModal').addEventListener('click', closeGradingModal);
            document.getElementById('cancelGrading').addEventListener('click', closeGradingModal);
            document.getElementById('saveGrade').addEventListener('click', saveGrade);
        }

        async function loadAssignments() {
            try {
                const response = await fetch('../api/assignments/assignments.php');
                const result = await response.json();
                
                if (result.assignments) {
                    assignments = result.assignments;
                    populateAssignmentFilter();
                }
            } catch (error) {
                console.error('Error loading assignments:', error);
            }
        }

        function populateAssignmentFilter() {
            const select = document.getElementById('assignmentFilter');
            select.innerHTML = '<option value="">All Assignments</option>';
            
            assignments.forEach(assignment => {
                const option = document.createElement('option');
                option.value = assignment.assignment_id;
                option.textContent = assignment.title;
                select.appendChild(option);
            });
        }

        async function loadSubmissions() {
            const loadingState = document.getElementById('loadingState');
            const tableContainer = document.getElementById('submissionsTableContainer');
            const noSubmissionsState = document.getElementById('noSubmissionsState');
            
            // Show loading state
            loadingState.classList.remove('hidden');
            tableContainer.classList.add('hidden');
            noSubmissionsState.classList.add('hidden');
            
            try {
                const params = new URLSearchParams();
                
                const assignmentId = document.getElementById('assignmentFilter').value;
                const status = document.getElementById('statusFilter').value;
                const search = document.getElementById('searchInput').value;
                
                if (assignmentId) params.append('assignment_id', assignmentId);
                if (status && status !== 'all') params.append('status', status);
                if (search) params.append('search', search);
                
                const response = await fetch(`../api/assignments/submissions.php?${params.toString()}`);
                const result = await response.json();
                
                if (result.submissions) {
                    submissions = result.submissions;
                    renderSubmissions();
                } else {
                    showError(result.error || 'Failed to load submissions');
                }
            } catch (error) {
                console.error('Error loading submissions:', error);
                showError('Failed to load submissions');
            } finally {
                loadingState.classList.add('hidden');
            }
        }

        function renderSubmissions() {
            const tableContainer = document.getElementById('submissionsTableContainer');
            const noSubmissionsState = document.getElementById('noSubmissionsState');
            const tbody = document.getElementById('submissionsTableBody');
            
            if (submissions.length === 0) {
                tableContainer.classList.add('hidden');
                noSubmissionsState.classList.remove('hidden');
                return;
            }
            
            noSubmissionsState.classList.add('hidden');
            tableContainer.classList.remove('hidden');
            
            tbody.innerHTML = '';
            
            submissions.forEach(submission => {
                const row = createSubmissionRow(submission);
                tbody.appendChild(row);
            });
        }

        function createSubmissionRow(submission) {
            const row = document.createElement('tr');
            
            const statusClass = getStatusClass(submission.status);
            const statusText = getStatusText(submission.status);
            const gradeDisplay = submission.grade !== null ? `${submission.grade}/100` : '--/100';
            const submittedDate = new Date(submission.submitted_at).toLocaleDateString();
            const submittedTime = new Date(submission.submitted_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-600">${submission.student_name.charAt(0)}</span>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">${submission.student_name}</div>
                            <div class="text-sm text-gray-500">${submission.student_email}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${submission.assignment_title}</div>
                    <div class="text-xs text-gray-500">${submission.course_title}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${submittedDate}</div>
                    <div class="text-xs text-gray-500">${submittedTime}</div>
                    ${submission.is_late ? '<div class="text-xs text-red-500"><i class="fas fa-clock mr-1"></i>Late</div>' : ''}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                        ${statusText}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${gradeDisplay}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button onclick="openGradingModal(${submission.submission_id})" class="text-[#17a3d6] hover:text-[#1792c0] mr-3">
                        ${submission.grade !== null ? 'Edit Grade' : 'Grade'}
                    </button>
                    ${submission.file_url ? `<a href="${submission.file_url}" target="_blank" class="text-gray-600 hover:text-gray-900">View File</a>` : '<span class="text-gray-400">No File</span>'}
                </td>
            `;
            
            return row;
        }

        function getStatusClass(status) {
            switch (status) {
                case 'graded': return 'bg-green-100 text-green-800';
                case 'late': return 'bg-red-100 text-red-800';
                case 'pending': return 'bg-yellow-100 text-yellow-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        }

        function getStatusText(status) {
            switch (status) {
                case 'graded': return 'Graded';
                case 'late': return 'Late';
                case 'pending': return 'Pending Review';
                default: return 'Unknown';
            }
        }

        function openGradingModal(submissionId) {
            currentSubmission = submissions.find(s => s.submission_id === submissionId);
            if (!currentSubmission) return;
            
            const modal = document.getElementById('gradingModal');
            const modalContent = document.getElementById('modalContent');
            
            modalContent.innerHTML = `
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h4 class="font-medium text-gray-900 mb-2">Student Information</h4>
                    <p><strong>Name:</strong> ${currentSubmission.student_name}</p>
                    <p><strong>Email:</strong> ${currentSubmission.student_email}</p>
                    <p><strong>Assignment:</strong> ${currentSubmission.assignment_title}</p>
                    <p><strong>Submitted:</strong> ${new Date(currentSubmission.submitted_at).toLocaleString()}</p>
                    ${currentSubmission.is_late ? '<p class="text-red-600"><strong>Status:</strong> Late Submission</p>' : ''}
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Grade (0-100)</label>
                        <input type="number" id="gradeInput" min="0" max="100" step="0.1" 
                               value="${currentSubmission.grade || ''}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                               placeholder="Enter grade...">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Feedback (Optional)</label>
                        <textarea id="feedbackInput" rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                                  placeholder="Provide feedback to the student...">${currentSubmission.feedback || ''}</textarea>
                    </div>
                    
                    ${currentSubmission.file_url ? `
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Submitted File</label>
                        <a href="${currentSubmission.file_url}" target="_blank" 
                           class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-file mr-2"></i>View Submission
                        </a>
                    </div>
                    ` : ''}
                </div>
            `;
            
            modal.classList.remove('hidden');
        }

        function closeGradingModal() {
            document.getElementById('gradingModal').classList.add('hidden');
            currentSubmission = null;
        }

        async function saveGrade() {
            if (!currentSubmission) return;
            
            const grade = parseFloat(document.getElementById('gradeInput').value);
            const feedback = document.getElementById('feedbackInput').value;
            
            if (isNaN(grade) || grade < 0 || grade > 100) {
                showError('Please enter a valid grade between 0 and 100');
                return;
            }
            
            try {
                const response = await fetch('../api/assignments/submissions.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        submission_id: currentSubmission.submission_id,
                        grade: grade,
                        feedback: feedback
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showSuccess('Grade saved successfully');
                    closeGradingModal();
                    loadSubmissions(); // Refresh the table
                } else {
                    showError(result.error || 'Failed to save grade');
                }
            } catch (error) {
                console.error('Error saving grade:', error);
                showError('Failed to save grade');
            }
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

        function showSuccess(message) {
            const existingAlert = document.querySelector('.alert');
            if (existingAlert) {
                existingAlert.remove();
            }

            const alert = document.createElement('div');
            alert.className = 'alert fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
            alert.innerHTML = `
                <div class="flex items-center">
                    <i class="mr-2 fas fa-check-circle"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-green-500 hover:text-green-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            document.body.appendChild(alert);

            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 3000);
        }
    </script>
</body>

</html>