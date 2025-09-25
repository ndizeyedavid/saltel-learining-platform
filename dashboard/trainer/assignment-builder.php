<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Assignment Builder</title>
    <?php include '../../include/trainer-guard.php'; ?>
    <?php include '../../include/trainer-imports.php'; ?>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
</head>

<?php
// Check if editing existing assignment
$assignment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$assignment_data = null;

if ($assignment_id > 0) {
    // Fetch assignment data for editing
    $stmt = $conn->prepare("
        SELECT a.*, c.course_title 
        FROM assignments a 
        JOIN courses c ON a.course_id = c.course_id 
        WHERE a.assignment_id = ? AND c.teacher_id = ?
    ");
    $stmt->bind_param('ii', $assignment_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $assignment_data = $result->fetch_assoc();
    } else {
        // Assignment not found or access denied
        header('Location: assignments.php');
        exit();
    }
}
?>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainer-Sidebar.php'; ?>

        <div class="flex flex-col flex-1 overflow-hidden">
            <?php include '../../components/Trainer-Header.php'; ?>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto">
                <div class="max-w-4xl px-6 py-8 mx-auto">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center">
                            <a href="assignments.php" class="p-2 mr-4 text-gray-600 transition-colors rounded-lg hover:bg-gray-100">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900"><?php echo $assignment_data ? 'Edit Assignment' : 'Create New Assignment'; ?></h1>
                                <p class="mt-1 text-sm text-gray-600"><?php echo $assignment_data ? 'Update your assignment details' : 'Design your quiz or assignment'; ?></p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button id="previewBtn" class="hidden px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                <i class="mr-2 fas fa-eye"></i>Preview
                            </button>
                            <button id="publishBtn" class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700">
                                <i class="mr-2 fas fa-check"></i>Publish
                            </button>
                        </div>
                    </div>

                    <!-- Assignment Form -->
                    <form id="assignmentForm">
                        <?php if ($assignment_data): ?>
                            <input type="hidden" id="assignmentId" value="<?php echo $assignment_data['assignment_id']; ?>">
                        <?php endif; ?>

                        <div class="space-y-6">
                            <!-- Basic Info -->
                            <div class="p-6 bg-white rounded-lg shadow-sm">
                                <h2 class="mb-4 text-lg font-semibold text-gray-900">Assignment Details</h2>
                                <div class="grid gap-6">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Title</label>
                                        <input type="text" id="assignmentTitle" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                            placeholder="Enter assignment title" value="<?php echo $assignment_data ? htmlspecialchars($assignment_data['title']) : ''; ?>" required>
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Description</label>
                                        <textarea id="assignmentDescription" class="w-full h-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                            placeholder="Enter assignment description"><?php echo $assignment_data ? htmlspecialchars($assignment_data['description']) : ''; ?></textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-700">Course</label>
                                            <select id="courseSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                                <option value="">Select course</option>
                                                <!-- Courses will be loaded dynamically -->
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block mb-2 text-sm font-medium text-gray-700">Due Date</label>
                                            <input type="date" id="dueDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                                value="<?php echo $assignment_data ? $assignment_data['due_date'] : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Questions Section -->
                            <div class="p-6 bg-white rounded-lg shadow-sm">
                                <div class="flex items-center justify-between mb-4">
                                    <h2 class="text-lg font-semibold text-gray-900">Questions</h2>
                                    <button type="button" id="addQuestionBtn" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                        <i class="mr-2 fas fa-plus"></i>Add Question
                                    </button>
                                </div>
                                <div id="questionsContainer" class="space-y-4">
                                    <!-- Questions will be loaded here -->
                                </div>
                                <div id="noQuestionsMessage" class="py-8 text-center text-gray-500">
                                    <i class="mb-2 text-3xl fas fa-question-circle"></i>
                                    <p>No questions added yet. Click "Add Question" to get started.</p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end mt-6 space-x-4">
                                <button type="button" id="saveDraftBtn" class="hidden px-6 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                                    Save as Draft
                                </button>
                                <button type="submit" id="saveAssignmentBtn" class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                    <?php echo $assignment_data ? 'Update Assignment' : 'Create Assignment'; ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        let courses = [];
        let questions = [];
        let questionCounter = 0;
        const assignmentId = document.getElementById('assignmentId')?.value || null;
        const isEditing = !!assignmentId;

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCourses();
            if (isEditing) {
                loadQuestions();
            }
            setupEventListeners();
        });

        function setupEventListeners() {
            document.getElementById('assignmentForm').addEventListener('submit', handleSubmit);
            document.getElementById('saveDraftBtn').addEventListener('click', () => handleSubmit(null, true));
            document.getElementById('addQuestionBtn').addEventListener('click', addNewQuestion);
        }

        async function loadCourses() {
            try {
                const response = await fetch('../api/courses/courses.php');
                const result = await response.json();

                if (result.courses) {
                    courses = result.courses;
                    populateCourseSelect();
                }
            } catch (error) {
                console.error('Error loading courses:', error);
                showError('Failed to load courses');
            }
        }

        function populateCourseSelect() {
            const courseSelect = document.getElementById('courseSelect');
            courseSelect.innerHTML = '<option value="">Select course</option>';

            courses.forEach(course => {
                const option = document.createElement('option');
                option.value = course.course_id;
                option.textContent = course.course_title;

                // Select current course if editing
                <?php if ($assignment_data): ?>
                    if (course.course_id === <?php echo $assignment_data['course_id']; ?>) {
                        option.selected = true;
                    }
                <?php endif; ?>

                courseSelect.appendChild(option);
            });
        }

        async function loadQuestions() {
            if (!assignmentId) return;

            try {
                const response = await fetch(`../api/assignments/questions.php?assignment_id=${assignmentId}`);
                const result = await response.json();

                if (result.questions) {
                    questions = result.questions;
                    renderQuestions();
                }
            } catch (error) {
                console.error('Error loading questions:', error);
                showError('Failed to load questions');
            }
        }

        function renderQuestions() {
            const container = document.getElementById('questionsContainer');
            const noQuestionsMessage = document.getElementById('noQuestionsMessage');

            if (questions.length === 0) {
                container.innerHTML = '';
                noQuestionsMessage.style.display = 'block';
                return;
            }

            noQuestionsMessage.style.display = 'none';
            container.innerHTML = '';

            questions.forEach((question, index) => {
                const questionElement = createQuestionElement(question, index);
                container.appendChild(questionElement);
            });
        }

        function createQuestionElement(question, index) {
            const isNew = !question.question_id;
            const questionId = question.question_id || `new_${questionCounter++}`;

            const questionDiv = document.createElement('div');
            questionDiv.className = 'p-4 border border-gray-200 rounded-lg';
            questionDiv.dataset.questionId = questionId;

            questionDiv.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium text-gray-900 text-md">Question ${index + 1}</h3>
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="moveQuestion(${index}, -1)" class="p-1 text-gray-400 hover:text-gray-600" ${index === 0 ? 'disabled' : ''}>
                            <i class="fas fa-chevron-up"></i>
                        </button>
                        <button type="button" onclick="moveQuestion(${index}, 1)" class="p-1 text-gray-400 hover:text-gray-600" ${index === questions.length - 1 ? 'disabled' : ''}>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <button type="button" onclick="deleteQuestion(${index})" class="p-1 text-red-400 hover:text-red-600">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Question Text</label>
                        <textarea class="w-full h-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                                  placeholder="Enter your question..." 
                                  onchange="updateQuestion(${index}, 'question_text', this.value)">${question.question_text || ''}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Points</label>
                            <input type="number" min="1" value="${question.points || 1}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                   onchange="updateQuestion(${index}, 'points', parseInt(this.value))">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Question Type</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" disabled>
                                <option>Multiple Choice</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Explanation (Optional)</label>
                        <textarea class="w-full h-20 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                                  placeholder="Explain the correct answer..." 
                                  onchange="updateQuestion(${index}, 'explanation', this.value)">${question.explanation || ''}</textarea>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-sm font-medium text-gray-700">Answer Options</label>
                            <button type="button" onclick="addOption(${index})" class="px-3 py-1 text-sm text-blue-600 border border-blue-600 rounded hover:bg-blue-50">
                                <i class="mr-1 fas fa-plus"></i>Add Option
                            </button>
                        </div>
                        <div class="space-y-2" id="options-${questionId}">
                            ${renderOptions(question.options || [], index)}
                        </div>
                    </div>
                </div>
            `;

            return questionDiv;
        }

        function renderOptions(options, questionIndex) {
            if (options.length === 0) {
                return '<p class="py-2 text-sm text-gray-500">No options added yet.</p>';
            }

            return options.map((option, optionIndex) => `
                <div class="flex items-center p-3 space-x-3 rounded-lg bg-gray-50">
                    <input type="radio" name="correct-${questionIndex}" 
                           ${option.is_correct ? 'checked' : ''} 
                           onchange="setCorrectOption(${questionIndex}, ${optionIndex})"
                           class="text-blue-600">
                    <input type="text" value="${option.option_text || ''}" 
                           placeholder="Enter option text..." 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                           onchange="updateOption(${questionIndex}, ${optionIndex}, 'option_text', this.value)">
                    <button type="button" onclick="deleteOption(${questionIndex}, ${optionIndex})" 
                            class="p-1 text-red-400 hover:text-red-600">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `).join('');
        }

        function addNewQuestion() {
            const newQuestion = {
                question_text: '',
                points: 1,
                explanation: '',
                options: [{
                        option_text: '',
                        is_correct: true
                    },
                    {
                        option_text: '',
                        is_correct: false
                    }
                ]
            };

            questions.push(newQuestion);
            renderQuestions();
        }

        function updateQuestion(questionIndex, field, value) {
            if (questions[questionIndex]) {
                questions[questionIndex][field] = value;
            }
        }

        function deleteQuestion(questionIndex) {
            if (confirm('Are you sure you want to delete this question?')) {
                questions.splice(questionIndex, 1);
                renderQuestions();
            }
        }

        function moveQuestion(questionIndex, direction) {
            const newIndex = questionIndex + direction;
            if (newIndex >= 0 && newIndex < questions.length) {
                [questions[questionIndex], questions[newIndex]] = [questions[newIndex], questions[questionIndex]];
                renderQuestions();
            }
        }

        function addOption(questionIndex) {
            if (!questions[questionIndex].options) {
                questions[questionIndex].options = [];
            }

            questions[questionIndex].options.push({
                option_text: '',
                is_correct: false
            });

            renderQuestions();
        }

        function updateOption(questionIndex, optionIndex, field, value) {
            if (questions[questionIndex] && questions[questionIndex].options[optionIndex]) {
                questions[questionIndex].options[optionIndex][field] = value;
            }
        }

        function setCorrectOption(questionIndex, optionIndex) {
            if (questions[questionIndex] && questions[questionIndex].options) {
                questions[questionIndex].options.forEach((option, index) => {
                    option.is_correct = index === optionIndex;
                });
            }
        }

        function deleteOption(questionIndex, optionIndex) {
            if (questions[questionIndex] && questions[questionIndex].options) {
                questions[questionIndex].options.splice(optionIndex, 1);
                renderQuestions();
            }
        }

        async function saveQuestions(assignmentId) {
            const savePromises = [];

            for (let i = 0; i < questions.length; i++) {
                const question = questions[i];
                question.sort_order = i;

                if (question.question_id) {
                    // Update existing question
                    savePromises.push(
                        fetch('../api/assignments/questions.php', {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                question_id: question.question_id,
                                question_text: question.question_text,
                                points: question.points,
                                explanation: question.explanation,
                                sort_order: question.sort_order
                            })
                        })
                    );
                } else {
                    // Create new question
                    savePromises.push(
                        fetch('../api/assignments/questions.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                assignment_id: assignmentId,
                                question_text: question.question_text,
                                points: question.points,
                                explanation: question.explanation,
                                sort_order: question.sort_order,
                                options: question.options
                            })
                        })
                    );
                }
            }

            await Promise.all(savePromises);
        }

        async function handleSubmit(event, isDraft = false) {
            if (event) event.preventDefault();

            const formData = {
                title: document.getElementById('assignmentTitle').value.trim(),
                description: document.getElementById('assignmentDescription').value.trim(),
                course_id: document.getElementById('courseSelect').value,
                due_date: document.getElementById('dueDate').value || null
            };

            // Validation
            if (!formData.title) {
                showError('Please enter an assignment title');
                return;
            }

            if (!formData.course_id) {
                showError('Please select a course');
                return;
            }

            try {
                let url = '../api/assignments/assignments.php';
                let method = 'POST';

                if (isEditing) {
                    formData.assignment_id = parseInt(assignmentId);
                    method = 'PUT';
                }

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.success) {
                    const currentAssignmentId = isEditing ? assignmentId : result.assignment_id;

                    // Save questions if any exist
                    if (questions.length > 0) {
                        try {
                            await saveQuestions(currentAssignmentId);
                        } catch (error) {
                            console.error('Error saving questions:', error);
                            showError('Assignment saved but failed to save questions');
                            return;
                        }
                    }

                    showSuccess(result.message || (isEditing ? 'Assignment updated successfully' : 'Assignment created successfully'));

                    // Redirect after a short delay
                    setTimeout(() => {
                        window.location.href = 'assignments.php';
                    }, 1500);
                } else {
                    showError(result.error || 'Failed to save assignment');
                }
            } catch (error) {
                console.error('Error saving assignment:', error);
                showError('Failed to save assignment');
            }
        }

        function showError(message) {
            // Remove existing alerts
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

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        }

        function showSuccess(message) {
            // Remove existing alerts
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

            // Auto remove after 3 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 3000);
        }
    </script>
</body>

</html>