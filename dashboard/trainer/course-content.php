<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Course Content Builder</title>
    <?php include '../../include/trainer-guard.php'; ?>
    <?php include '../../include/trainer-imports.php'; ?>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
</head>

<?php
// Get course ID from URL
$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

if ($course_id <= 0) {
    // header('Location: courses.php');
    exit();
}

// Verify course ownership
$course_check = $conn->prepare("SELECT course_title, teacher_id FROM courses WHERE course_id = ?");
$course_check->bind_param('i', $course_id);
$course_check->execute();
$course_result = $course_check->get_result();

if ($course_result->num_rows === 0) {
    header('Location: courses.php');
    exit();
}

$course_data = $course_result->fetch_assoc();
if ($course_data['teacher_id'] != $_SESSION['user_id']) {
    header('Location: courses.php');
    exit();
}

$course_title = $course_data['course_title'];
?>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainer-Sidebar.php'; ?>

        <div class="flex flex-col flex-1 overflow-hidden">
            <?php include '../../components/Trainer-Header.php'; ?>

            <main class="flex flex-1 overflow-hidden">
                <!-- Content Navigation -->
                <div class="overflow-y-auto bg-white border-r border-gray-200 w-80">
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Course Content</h2>
                            <button class="text-blue-600 hover:text-blue-700" id="addModuleBtn">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="relative">
                            <input type="text" placeholder="Search content..." class="w-full px-3 py-2 pl-8 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <i class="absolute text-gray-400 fas fa-search left-3 top-3"></i>
                        </div>
                    </div>

                    <!-- Module List -->
                    <div id="moduleList" class="p-4 space-y-2">
                        <!-- Module items will be added here -->
                    </div>
                </div>

                <!-- Content Editor -->
                <div class="flex-1 overflow-y-auto">
                    <div class="p-6">
                        <!-- Module/Lesson Editor -->
                        <div id="contentEditor" class="max-w-3xl mx-auto">
                            <div class="mb-6">
                                <input type="text" id="lessonTitle" placeholder="Enter lesson title" class="w-full px-4 py-2 text-2xl font-bold border-none focus:ring-0">
                            </div>

                            <!-- Rich Text Editor -->
                            <div id="editor" class="bg-white border border-gray-200 rounded-lg h-[200px]"></div>

                            <!-- Media Upload Section -->
                            <div class="p-6 mt-6 bg-white border border-gray-200 rounded-lg">
                                <h3 class="mb-4 text-lg font-semibold">Content Resources</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Video Upload -->
                                    <div class="p-4 text-center border border-gray-300 border-dashed rounded-lg">
                                        <i class="mb-2 text-2xl text-gray-400 fas fa-video"></i>
                                        <p class="mb-2 text-sm text-gray-600">Upload Video</p>
                                        <input type="file" accept="video/*" class="hidden" id="videoUpload">
                                        <button onclick="document.getElementById('videoUpload').click()" class="px-4 py-2 text-sm text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50">
                                            Choose File
                                        </button>
                                    </div>

                                    <!-- Document Upload -->
                                    <div class="p-4 text-center border border-gray-300 border-dashed rounded-lg">
                                        <i class="mb-2 text-2xl text-gray-400 fas fa-file-alt"></i>
                                        <p class="mb-2 text-sm text-gray-600">Upload Documents</p>
                                        <input type="file" multiple accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt" class="hidden" id="docUpload">
                                        <button onclick="document.getElementById('docUpload').click()" class="px-4 py-2 text-sm text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50">
                                            Choose Files
                                        </button>
                                    </div>
                                </div>

                                <!-- Uploaded Resources List -->
                                <div class="mt-4">
                                    <h4 class="mb-2 text-sm font-medium text-gray-700">Uploaded Resources</h4>
                                    <div id="resourcesList" class="space-y-2">
                                        <!-- Resources will be loaded here -->
                                    </div>
                                </div>
                            </div>

                            <!-- Quiz Builder -->
                            <div class="p-6 mt-6 bg-white border border-gray-200 rounded-lg">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold">Knowledge Check</h3>
                                    <button class="px-4 py-2 text-sm text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50" id="addQuestionBtn">
                                        Add Question
                                    </button>
                                </div>

                                <!-- Questions List -->
                                <div class="space-y-4" id="questionsList">
                                    <!-- Questions will be loaded here -->
                                </div>
                            </div>

                            <!-- Prerequisites -->
                            <div class="p-6 mt-6 bg-white border border-gray-200 rounded-lg">
                                <div class="hidden grid-cols-2 gap-4">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Duration (minutes)</label>
                                        <input type="number" id="lessonDuration" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-700">Points</label>
                                        <input type="number" id="lessonPoints" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold">Prerequisites</h3>
                                    <button class="px-4 py-2 text-sm text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50" id="addPrerequisiteBtn">
                                        Add Prerequisite
                                    </button>
                                </div>

                                <!-- Prerequisites List -->
                                <div class="space-y-3" id="prerequisitesList">
                                    <!-- Prerequisites will be loaded here -->
                                </div>

                                <div class="mt-4 text-sm text-gray-500">
                                    <p>Prerequisites control when students can access this lesson. Students must meet all requirements before they can view the content.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end mt-6 space-x-4">
                            <button id="previewBtn" class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                                Preview
                            </button>
                            <button id="saveChangesBtn" class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
        </div>
        </main>
    </div>
    </div>

    <!-- Add Module Modal -->
    <div id="moduleModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50">
        <div class="absolute w-full max-w-md p-6 transform -translate-x-1/2 -translate-y-1/2 bg-white top-1/2 left-1/2 rounded-xl">
            <h3 class="mb-4 text-lg font-semibold">Add New Module</h3>
            <div class="space-y-4">
                <input type="text" placeholder="Module title" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <textarea placeholder="Module description (optional)" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div class="flex justify-end mt-6 space-x-3">
                <button class="px-4 py-2 text-gray-600 hover:text-gray-800" id="cancelModule">Cancel</button>
                <button class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700" id="saveModule">Add Module</button>
            </div>
        </div>
    </div>

    <!-- Add Prerequisite Modal -->
    <div id="prerequisiteModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50">
        <div class="absolute w-full max-w-lg p-6 transform -translate-x-1/2 -translate-y-1/2 bg-white top-1/2 left-1/2 rounded-xl">
            <h3 class="mb-4 text-lg font-semibold" id="prerequisiteModalTitle">Add Prerequisite</h3>
            <div class="space-y-4">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Prerequisite Type *</label>
                    <select id="prerequisiteType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="module_completion">Module Completion</option>
                        <option value="lesson_completion">Lesson Completion</option>
                        <option value="quiz_score">Minimum Quiz Score</option>
                        <option value="assignment_submission">Assignment Submission</option>
                    </select>
                </div>

                <div id="prerequisiteValueSection">
                    <label class="block mb-2 text-sm font-medium text-gray-700" id="prerequisiteValueLabel">Select Module</label>
                    <select id="prerequisiteValue" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>

                <div id="requiredScoreSection" class="hidden">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Required Score (%)</label>
                    <input type="number" id="requiredScore" min="0" max="100" value="70" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="flex justify-end mt-6 space-x-3">
                <button class="px-4 py-2 text-gray-600 hover:text-gray-800" id="cancelPrerequisite">Cancel</button>
                <button class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700" id="savePrerequisite">Save Prerequisite</button>
            </div>
        </div>
    </div>

    <!-- Add Question Modal -->
    <div id="questionModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50">
        <div class="absolute w-full max-w-2xl p-6 transform -translate-x-1/2 -translate-y-1/2 bg-white top-1/2 left-1/2 rounded-xl max-h-[90vh] overflow-y-auto">
            <h3 class="mb-4 text-lg font-semibold" id="questionModalTitle">Add New Question</h3>
            <div class="space-y-4">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Question Text *</label>
                    <textarea id="questionText" placeholder="Enter your question here..." rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Question Type</label>
                        <select id="questionType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="true_false">True/False</option>
                            <option value="short_answer">Short Answer</option>
                            <option value="essay">Essay</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Points</label>
                        <input type="number" id="questionPoints" value="1" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Answer Options (for multiple choice and true/false) -->
                <div id="answerOptions" class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700">Answer Options</label>
                    <div id="optionsList">
                        <!-- Options will be added here -->
                    </div>
                    <button type="button" id="addOptionBtn" class="px-3 py-2 text-sm text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50">
                        Add Option
                    </button>
                </div>
            </div>
            <div class="flex justify-end mt-6 space-x-3">
                <button class="px-4 py-2 text-gray-600 hover:text-gray-800" id="cancelQuestion">Cancel</button>
                <button class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700" id="saveQuestion">Save Question</button>
            </div>
        </div>
    </div>

    <script>
        const courseId = <?php echo $course_id; ?>;
        let currentModuleId = null;
        let currentLessonId = null;
        let currentLessonData = null;

        // Initialize Quill editor
        var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{
                        'header': 1
                    }, {
                        'header': 2
                    }],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }],
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });

        // Initialize Sortable for module list
        new Sortable(document.getElementById('moduleList'), {
            animation: 150,
            handle: '.module-handle'
        });

        // Modal handlers
        const moduleModal = document.getElementById('moduleModal');
        const addModuleBtn = document.getElementById('addModuleBtn');
        const cancelModule = document.getElementById('cancelModule');
        const saveModule = document.getElementById('saveModule');

        addModuleBtn.addEventListener('click', () => moduleModal.classList.remove('hidden'));
        cancelModule.addEventListener('click', () => moduleModal.classList.add('hidden'));

        // Save Changes button functionality
        document.getElementById('saveChangesBtn').addEventListener('click', async () => {
            if (!currentModuleId) {
                alert('Please select a module first');
                return;
            }

            await saveLessonContent();
        });

        // File upload handlers
        document.getElementById('videoUpload').addEventListener('change', handleFileUpload);
        document.getElementById('docUpload').addEventListener('change', handleFileUpload);

        // Quiz functionality
        document.getElementById('addQuestionBtn').addEventListener('click', () => {
            if (!currentLessonId) {
                alert('Please save the lesson first before adding questions');
                return;
            }
            openQuestionModal();
        });

        // Prerequisites functionality
        document.getElementById('addPrerequisiteBtn').addEventListener('click', () => {
            if (!currentLessonId) {
                alert('Please save the lesson first before adding prerequisites');
                return;
            }
            openPrerequisiteModal();
        });

        // Question modal handlers
        const questionModal = document.getElementById('questionModal');
        const cancelQuestion = document.getElementById('cancelQuestion');
        const saveQuestion = document.getElementById('saveQuestion');
        const questionType = document.getElementById('questionType');
        const addOptionBtn = document.getElementById('addOptionBtn');

        cancelQuestion.addEventListener('click', () => questionModal.classList.add('hidden'));
        saveQuestion.addEventListener('click', saveQuizQuestion);
        questionType.addEventListener('change', handleQuestionTypeChange);
        addOptionBtn.addEventListener('click', addAnswerOption);

        // Prerequisite modal handlers
        const prerequisiteModal = document.getElementById('prerequisiteModal');
        const cancelPrerequisite = document.getElementById('cancelPrerequisite');
        const savePrerequisite = document.getElementById('savePrerequisite');
        const prerequisiteType = document.getElementById('prerequisiteType');

        cancelPrerequisite.addEventListener('click', () => prerequisiteModal.classList.add('hidden'));
        savePrerequisite.addEventListener('click', savePrerequisiteData);
        prerequisiteType.addEventListener('change', handlePrerequisiteTypeChange);

        // Load modules on page load
        loadModules();

        // Create new module
        saveModule.addEventListener('click', async () => {
            const titleInput = moduleModal.querySelector('input[type="text"]');
            const descInput = moduleModal.querySelector('textarea');

            const moduleData = {
                course_id: courseId,
                module_title: titleInput.value.trim(),
                module_description: descInput.value.trim()
            };

            if (!moduleData.module_title) {
                alert('Module title is required');
                return;
            }

            try {
                const response = await fetch('../api/courses/modules.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(moduleData)
                });

                const result = await response.json();

                if (result.success) {
                    titleInput.value = '';
                    descInput.value = '';
                    moduleModal.classList.add('hidden');
                    loadModules(); // Reload modules
                } else {
                    console.log(result);
                    // alert(result.error || 'Failed to create module');
                }
            } catch (error) {
                console.error('Error creating module:', error);
                alert('Failed to create module');
            }
        });

        // Load modules function
        async function loadModules() {
            try {
                const response = await fetch(`../api/courses/modules.php?course_id=${courseId}`);
                const result = await response.json();

                const moduleList = document.getElementById('moduleList');
                moduleList.innerHTML = '';

                if (result.modules && result.modules.length > 0) {
                    result.modules.forEach(module => {
                        const moduleHtml = createModuleHTML(module);
                        moduleList.insertAdjacentHTML('beforeend', moduleHtml);
                    });
                } else {
                    moduleList.innerHTML = '<p class="text-sm text-gray-500">No modules yet. Click + to add your first module.</p>';
                }
            } catch (error) {
                console.error('Error loading modules:', error);
            }
        }

        // Create module HTML
        function createModuleHTML(module) {
            return `
                <div class="p-3 bg-white border border-gray-200 rounded-lg module-item" data-module-id="${module.module_id}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center cursor-pointer" onclick="selectModule(${module.module_id})">
                            <i class="mr-3 text-gray-400 cursor-move fas fa-grip-vertical module-handle"></i>
                            <div>
                                <h4 class="text-sm font-medium">${module.module_title}</h4>
                                <p class="text-xs text-gray-500">${module.lesson_count} lessons</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="text-gray-400 hover:text-gray-600" onclick="editModule(${module.module_id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-gray-400 hover:text-red-600" onclick="deleteModule(${module.module_id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Select module function
        async function selectModule(moduleId) {
            currentModuleId = moduleId;
            currentLessonId = null;

            // Highlight selected module
            document.querySelectorAll('.module-item').forEach(item => {
                item.classList.remove('bg-blue-50', 'border-blue-200');
            });
            document.querySelector(`[data-module-id="${moduleId}"]`).classList.add('bg-blue-50', 'border-blue-200');

            // Load module content in editor
            // For now, show module editing interface
            showModuleEditor(moduleId);
        }

        // Show module editor
        async function showModuleEditor(moduleId) {
            // Load existing lesson for this module or create new one
            await loadLessonForModule(moduleId);
        }

        // Load lesson for module
        async function loadLessonForModule(moduleId) {
            try {
                const response = await fetch(`../api/courses/lessons.php?module_id=${moduleId}`);
                const result = await response.json();

                if (result.lessons && result.lessons.length > 0) {
                    // Load first lesson for the module
                    currentLessonData = result.lessons[0];
                    currentLessonId = currentLessonData.lesson_id;

                    // Populate editor with lesson data
                    document.getElementById('lessonTitle').value = currentLessonData.lesson_title || '';
                    quill.root.innerHTML = currentLessonData.lesson_content || '';
                    document.getElementById('lessonDuration').value = currentLessonData.duration_minutes || '';
                    document.getElementById('lessonPoints').value = currentLessonData.points || '';
                } else {
                    // No lesson exists, clear editor for new lesson
                    currentLessonData = null;
                    currentLessonId = null;
                    document.getElementById('lessonTitle').value = '';
                    quill.setContents([]);
                    document.getElementById('lessonDuration').value = '';
                    document.getElementById('lessonPoints').value = '';
                }

                // Load resources for the lesson/module
                await loadResources();

                // Load quiz questions for the lesson
                await loadQuizQuestions();

                // Load prerequisites for the lesson
                await loadPrerequisites();

            } catch (error) {
                console.error('Error loading lesson:', error);
                // Clear editor on error
                document.getElementById('lessonTitle').value = '';
                quill.setContents([]);
                document.getElementById('lessonDuration').value = '';
                document.getElementById('lessonPoints').value = '';
            }
        }

        // Save lesson content
        async function saveLessonContent() {
            const lessonTitle = document.getElementById('lessonTitle').value.trim();
            const lessonContent = quill.root.innerHTML;
            const duration = document.getElementById('lessonDuration').value;
            const points = document.getElementById('lessonPoints').value;

            if (!lessonTitle) {
                alert('Please enter a lesson title');
                return;
            }

            const lessonData = {
                module_id: currentModuleId,
                lesson_title: lessonTitle,
                lesson_content: lessonContent,
                duration_minutes: duration ? parseInt(duration) : null,
                points: points ? parseInt(points) : 0,
                lesson_type: 'text'
            };

            try {
                let response;
                if (currentLessonId) {
                    // Update existing lesson
                    lessonData.lesson_id = currentLessonId;
                    response = await fetch('../api/courses/lessons.php', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(lessonData)
                    });
                } else {
                    // Create new lesson
                    response = await fetch('../api/courses/lessons.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(lessonData)
                    });
                }

                const result = await response.json();

                if (result.success) {
                    if (!currentLessonId && result.lesson_id) {
                        currentLessonId = result.lesson_id;
                    }

                    // Show success message
                    const saveBtn = document.getElementById('saveChangesBtn');
                    const originalText = saveBtn.textContent;
                    saveBtn.textContent = 'Saved!';
                    saveBtn.classList.add('bg-green-600');
                    saveBtn.classList.remove('bg-blue-600');

                    setTimeout(() => {
                        saveBtn.textContent = originalText;
                        saveBtn.classList.remove('bg-green-600');
                        saveBtn.classList.add('bg-blue-600');
                    }, 2000);
                } else {
                    alert(result.error || 'Failed to save lesson');
                }
            } catch (error) {
                console.error('Error saving lesson:', error);
                alert('Failed to save lesson');
            }
        }

        // Edit module function
        function editModule(moduleId) {
            // For now, just select the module to edit it
            selectModule(moduleId);
        }

        // File upload handler
        async function handleFileUpload(event) {
            const files = event.target.files;
            if (!files.length) return;

            if (!currentLessonId) {
                alert('Please save the lesson first before uploading resources');
                return;
            }

            for (let file of files) {
                await uploadResource(file);
            }

            // Clear the input
            event.target.value = '';
        }

        // Upload resource function
        async function uploadResource(file) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('lesson_id', currentLessonId);
            formData.append('module_id', currentModuleId);
            formData.append('resource_name', file.name);

            // Determine resource type
            let resourceType = 'document';
            if (file.type.startsWith('video/')) {
                resourceType = 'video';
            } else if (file.type.startsWith('image/')) {
                resourceType = 'image';
            } else if (file.type.startsWith('audio/')) {
                resourceType = 'audio';
            }
            formData.append('resource_type', resourceType);

            try {
                const response = await fetch('../api/courses/resources.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Reload resources list
                    await loadResources();
                } else {
                    alert(result.error || 'Failed to upload resource');
                }
            } catch (error) {
                console.error('Error uploading resource:', error);
                alert('Failed to upload resource');
            }
        }

        // Load resources function
        async function loadResources() {
            try {
                let url = '../api/courses/resources.php?';
                if (currentLessonId) {
                    url += `lesson_id=${currentLessonId}`;
                } else if (currentModuleId) {
                    url += `module_id=${currentModuleId}`;
                } else {
                    return;
                }

                const response = await fetch(url);
                const result = await response.json();

                const resourcesList = document.getElementById('resourcesList');
                resourcesList.innerHTML = '';

                if (result.resources && result.resources.length > 0) {
                    result.resources.forEach(resource => {
                        const resourceHtml = createResourceHTML(resource);
                        resourcesList.insertAdjacentHTML('beforeend', resourceHtml);
                    });
                } else {
                    resourcesList.innerHTML = '<p class="text-sm text-gray-500">No resources uploaded yet.</p>';
                }
            } catch (error) {
                console.error('Error loading resources:', error);
            }
        }

        // Create resource HTML
        function createResourceHTML(resource) {
            const iconClass = getResourceIcon(resource.resource_type, resource.mime_type);
            const fileSize = formatFileSize(resource.file_size);

            return `
                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                    <div class="flex items-center">
                        <i class="mr-3 ${iconClass}"></i>
                        <div>
                            <span class="text-sm text-gray-700">${resource.resource_name}</span>
                            <p class="text-xs text-gray-500">${fileSize} • ${resource.resource_type}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="../../${resource.resource_url}" target="_blank" class="text-blue-600 hover:text-blue-700" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button onclick="deleteResource(${resource.resource_id})" class="text-gray-400 hover:text-red-600" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        }

        // Get resource icon
        function getResourceIcon(type, mimeType) {
            switch (type) {
                case 'video':
                    return 'fas fa-play-circle text-red-500';
                case 'image':
                    return 'fas fa-image text-green-500';
                case 'audio':
                    return 'fas fa-music text-purple-500';
                case 'document':
                default:
                    if (mimeType && mimeType.includes('pdf')) {
                        return 'fas fa-file-pdf text-red-500';
                    } else if (mimeType && (mimeType.includes('word') || mimeType.includes('document'))) {
                        return 'fas fa-file-word text-blue-500';
                    } else if (mimeType && (mimeType.includes('excel') || mimeType.includes('sheet'))) {
                        return 'fas fa-file-excel text-green-500';
                    } else if (mimeType && (mimeType.includes('powerpoint') || mimeType.includes('presentation'))) {
                        return 'fas fa-file-powerpoint text-orange-500';
                    }
                    return 'fas fa-file-alt text-gray-500';
            }
        }

        // Format file size
        function formatFileSize(bytes) {
            if (!bytes) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Delete resource function
        async function deleteResource(resourceId) {
            if (!confirm('Are you sure you want to delete this resource?')) {
                return;
            }

            try {
                const response = await fetch(`../api/courses/resources.php?resource_id=${resourceId}`, {
                    method: 'DELETE'
                });

                const result = await response.json();

                if (result.success) {
                    await loadResources(); // Reload resources
                } else {
                    alert(result.error || 'Failed to delete resource');
                }
            } catch (error) {
                console.error('Error deleting resource:', error);
                alert('Failed to delete resource');
            }
        }

        // Quiz functionality
        let currentEditingQuestionId = null;

        // Open question modal
        function openQuestionModal(questionData = null) {
            const modal = document.getElementById('questionModal');
            const title = document.getElementById('questionModalTitle');

            if (questionData) {
                // Edit mode
                title.textContent = 'Edit Question';
                currentEditingQuestionId = questionData.question_id;
                document.getElementById('questionText').value = questionData.question_text;
                document.getElementById('questionType').value = questionData.question_type;
                document.getElementById('questionPoints').value = questionData.points;

                // Load options
                loadQuestionOptions(questionData.options);
            } else {
                // Add mode
                title.textContent = 'Add New Question';
                currentEditingQuestionId = null;
                document.getElementById('questionText').value = '';
                document.getElementById('questionType').value = 'multiple_choice';
                document.getElementById('questionPoints').value = '1';

                // Initialize with default options
                initializeDefaultOptions();
            }

            handleQuestionTypeChange();
            modal.classList.remove('hidden');
        }

        // Handle question type change
        function handleQuestionTypeChange() {
            const questionType = document.getElementById('questionType').value;
            const answerOptions = document.getElementById('answerOptions');

            if (questionType === 'multiple_choice') {
                answerOptions.style.display = 'block';
                if (document.getElementById('optionsList').children.length === 0) {
                    initializeDefaultOptions();
                }
            } else if (questionType === 'true_false') {
                answerOptions.style.display = 'block';
                initializeTrueFalseOptions();
            } else {
                answerOptions.style.display = 'none';
            }
        }

        // Initialize default options for multiple choice
        function initializeDefaultOptions() {
            const optionsList = document.getElementById('optionsList');
            optionsList.innerHTML = '';

            for (let i = 0; i < 4; i++) {
                addAnswerOption();
            }
        }

        // Initialize true/false options
        function initializeTrueFalseOptions() {
            const optionsList = document.getElementById('optionsList');
            optionsList.innerHTML = '';

            // Add True option
            const trueOption = createOptionElement('True', true);
            optionsList.appendChild(trueOption);

            // Add False option
            const falseOption = createOptionElement('False', false);
            optionsList.appendChild(falseOption);
        }

        // Add answer option
        function addAnswerOption() {
            const optionsList = document.getElementById('optionsList');
            const optionElement = createOptionElement('', false);
            optionsList.appendChild(optionElement);
        }

        // Create option element
        function createOptionElement(text = '', isCorrect = false) {
            const div = document.createElement('div');
            div.className = 'flex items-center space-x-3 p-3 border border-gray-200 rounded-lg';

            div.innerHTML = `
                <input type="radio" name="correctAnswer" ${isCorrect ? 'checked' : ''} class="text-blue-600">
                <input type="text" value="${text}" placeholder="Enter option text..." class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-700">
                    <i class="fas fa-trash"></i>
                </button>
            `;

            return div;
        }

        // Load question options
        function loadQuestionOptions(options) {
            const optionsList = document.getElementById('optionsList');
            optionsList.innerHTML = '';

            options.forEach(option => {
                const optionElement = createOptionElement(option.option_text, option.is_correct);
                optionsList.appendChild(optionElement);
            });
        }

        // Save quiz question
        async function saveQuizQuestion() {
            const questionText = document.getElementById('questionText').value.trim();
            const questionType = document.getElementById('questionType').value;
            const points = parseInt(document.getElementById('questionPoints').value);

            if (!questionText) {
                alert('Please enter a question text');
                return;
            }

            // Collect options
            const options = [];
            const optionElements = document.querySelectorAll('#optionsList > div');

            optionElements.forEach((element, index) => {
                const textInput = element.querySelector('input[type="text"]');
                const radioInput = element.querySelector('input[type="radio"]');

                if (textInput.value.trim()) {
                    options.push({
                        text: textInput.value.trim(),
                        is_correct: radioInput.checked
                    });
                }
            });

            // Validate options for multiple choice and true/false
            if ((questionType === 'multiple_choice' || questionType === 'true_false') && options.length === 0) {
                alert('Please add at least one answer option');
                return;
            }

            const questionData = {
                lesson_id: currentLessonId,
                question_text: questionText,
                question_type: questionType,
                points: points,
                options: options
            };

            try {
                let response;
                if (currentEditingQuestionId) {
                    // Update existing question
                    questionData.question_id = currentEditingQuestionId;
                    response = await fetch('../api/courses/quiz.php', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(questionData)
                    });
                } else {
                    // Create new question
                    response = await fetch('../api/courses/quiz.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(questionData)
                    });
                }

                // Check if response is ok and has content
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const responseText = await response.text();
                if (!responseText) {
                    throw new Error('Empty response from server');
                }

                let result;
                try {
                    result = JSON.parse(responseText);
                } catch (e) {
                    console.error('Invalid JSON response:', responseText);
                    throw new Error('Invalid JSON response from server');
                }

                if (result.success) {
                    document.getElementById('questionModal').classList.add('hidden');
                    await loadQuizQuestions(); // Reload questions
                } else {
                    alert(result.error || 'Failed to save question');
                }
            } catch (error) {
                console.error('Error saving question:', error);
                alert('Failed to save question');
            }
        }

        // Load quiz questions
        async function loadQuizQuestions() {
            if (!currentLessonId) {
                document.getElementById('questionsList').innerHTML = '<p class="text-sm text-gray-500">Save the lesson first to add quiz questions.</p>';
                return;
            }

            try {
                const response = await fetch(`../api/courses/quiz.php?lesson_id=${currentLessonId}`);
                const result = await response.json();

                const questionsList = document.getElementById('questionsList');
                questionsList.innerHTML = '';

                if (result.questions && result.questions.length > 0) {
                    result.questions.forEach((question, index) => {
                        const questionHtml = createQuestionHTML(question, index + 1);
                        questionsList.insertAdjacentHTML('beforeend', questionHtml);
                    });
                } else {
                    questionsList.innerHTML = '<p class="text-sm text-gray-500">No quiz questions yet. Click "Add Question" to get started.</p>';
                }
            } catch (error) {
                console.error('Error loading quiz questions:', error);
            }
        }

        // Create question HTML
        function createQuestionHTML(question, questionNumber) {
            const optionsHtml = question.options.map(option => `
                <div class="flex items-center">
                    <input type="radio" class="mr-2" disabled ${option.is_correct ? 'checked' : ''}>
                    <span class="text-sm text-gray-700">${option.option_text} ${option.is_correct ? '(Correct)' : ''}</span>
                </div>
            `).join('');

            return `
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">Question ${questionNumber} (${question.points} point${question.points !== 1 ? 's' : ''})</h4>
                            <p class="text-sm text-gray-600">${question.question_text}</p>
                            <span class="inline-block px-2 py-1 mt-2 text-xs bg-gray-100 rounded-full">${question.question_type.replace('_', ' ')}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="editQuestion(${question.question_id})" class="text-gray-400 hover:text-blue-600" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteQuestion(${question.question_id})" class="text-gray-400 hover:text-red-600" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    ${question.options.length > 0 ? `<div class="space-y-2">${optionsHtml}</div>` : '<p class="text-sm italic text-gray-500">Open-ended question</p>'}
                </div>
            `;
        }

        // Edit question
        async function editQuestion(questionId) {
            try {
                const response = await fetch(`../api/courses/quiz.php?lesson_id=${currentLessonId}`);
                const result = await response.json();

                const question = result.questions.find(q => q.question_id === questionId);
                if (question) {
                    openQuestionModal(question);
                }
            } catch (error) {
                console.error('Error loading question:', error);
            }
        }

        // Delete question
        async function deleteQuestion(questionId) {
            if (!confirm('Are you sure you want to delete this question?')) {
                return;
            }

            try {
                const response = await fetch(`../api/courses/quiz.php?question_id=${questionId}`, {
                    method: 'DELETE'
                });

                const result = await response.json();

                if (result.success) {
                    await loadQuizQuestions(); // Reload questions
                } else {
                    alert(result.error || 'Failed to delete question');
                }
            } catch (error) {
                console.error('Error deleting question:', error);
                alert('Failed to delete question');
            }
        }

        // Delete module function
        async function deleteModule(moduleId) {
            if (!confirm('Are you sure you want to delete this module? This will also delete all lessons and resources within it.')) {
                return;
            }

            try {
                const response = await fetch(`../api/courses/modules.php?module_id=${moduleId}`, {
                    method: 'DELETE'
                });

                const result = await response.json();

                if (result.success) {
                    loadModules(); // Reload modules
                    if (currentModuleId === moduleId) {
                        currentModuleId = null;
                        currentLessonId = null;
                        // Clear editor
                        document.querySelector('#contentEditor input[type="text"]').value = '';
                        quill.setContents([]);
                    }
                } else {
                    alert(result.error || 'Failed to delete module');
                }
            } catch (error) {
                console.error('Error deleting module:', error);
                alert('Failed to delete module');
            }
        }

        // Prerequisites functionality
        async function loadPrerequisites() {
            if (!currentLessonId) {
                document.getElementById('prerequisitesList').innerHTML = '<p class="text-sm text-gray-500">Save the lesson first to add prerequisites.</p>';
                return;
            }

            try {
                const response = await fetch(`../api/courses/prerequisites.php?lesson_id=${currentLessonId}`);
                const result = await response.json();

                const prerequisitesList = document.getElementById('prerequisitesList');
                prerequisitesList.innerHTML = '';

                if (result.prerequisites && result.prerequisites.length > 0) {
                    result.prerequisites.forEach(prerequisite => {
                        const prerequisiteHtml = createPrerequisiteHTML(prerequisite);
                        prerequisitesList.insertAdjacentHTML('beforeend', prerequisiteHtml);
                    });
                } else {
                    prerequisitesList.innerHTML = '<p class="text-sm text-gray-500">No prerequisites set. This lesson can be accessed immediately.</p>';
                }
            } catch (error) {
                console.error('Error loading prerequisites:', error);
            }
        }

        function createPrerequisiteHTML(prerequisite) {
            return `
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg bg-gray-50">
                    <div class="flex items-center">
                        <i class="mr-3 text-blue-600 fas fa-lock"></i>
                        <div>
                            <span class="text-sm text-gray-700">${prerequisite.description}</span>
                            <div class="text-xs text-gray-500">${prerequisite.prerequisite_type.replace('_', ' ').toUpperCase()}</div>
                        </div>
                    </div>
                    <button onclick="deletePrerequisite(${prerequisite.prerequisite_id})" class="text-gray-400 hover:text-red-600" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
        }

        function openPrerequisiteModal() {
            const modal = document.getElementById('prerequisiteModal');
            const title = document.getElementById('prerequisiteModalTitle');

            title.textContent = 'Add Prerequisite';
            document.getElementById('prerequisiteType').value = 'module_completion';
            document.getElementById('requiredScore').value = '70';

            handlePrerequisiteTypeChange();
            modal.classList.remove('hidden');
        }

        function handlePrerequisiteTypeChange() {
            const prerequisiteType = document.getElementById('prerequisiteType').value;
            const valueSection = document.getElementById('prerequisiteValueSection');
            const scoreSection = document.getElementById('requiredScoreSection');
            const valueLabel = document.getElementById('prerequisiteValueLabel');
            const valueSelect = document.getElementById('prerequisiteValue');

            // Show/hide score section
            if (prerequisiteType === 'quiz_score') {
                scoreSection.classList.remove('hidden');
            } else {
                scoreSection.classList.add('hidden');
            }

            // Update label and load options
            switch (prerequisiteType) {
                case 'module_completion':
                    valueLabel.textContent = 'Select Module';
                    loadModuleOptions();
                    break;
                case 'lesson_completion':
                    valueLabel.textContent = 'Select Lesson';
                    loadLessonOptions();
                    break;
                case 'quiz_score':
                    valueLabel.textContent = 'Select Lesson with Quiz';
                    loadLessonOptions();
                    break;
                case 'assignment_submission':
                    valueLabel.textContent = 'Select Assignment';
                    loadAssignmentOptions();
                    break;
            }
        }

        async function loadModuleOptions() {
            try {
                const response = await fetch(`../api/courses/modules.php?course_id=${courseId}`);
                const result = await response.json();
                const select = document.getElementById('prerequisiteValue');

                select.innerHTML = '<option value="">Select a module...</option>';

                if (result.modules) {
                    result.modules.forEach(module => {
                        select.innerHTML += `<option value="${module.module_id}">${module.module_title}</option>`;
                    });
                }
            } catch (error) {
                console.error('Error loading modules:', error);
            }
        }

        async function loadLessonOptions() {
            try {
                const response = await fetch(`../api/courses/modules.php?course_id=${courseId}`);
                const result = await response.json();
                const select = document.getElementById('prerequisiteValue');

                select.innerHTML = '<option value="">Select a lesson...</option>';
                console.log(result);
                if (result.modules) {
                    for (const module of result.modules) {
                        const lessonsResponse = await fetch(`../api/courses/lessons.php?module_id=${module.module_id}`);
                        const lessonsResult = await lessonsResponse.json();

                        if (lessonsResult.lessons) {
                            lessonsResult.lessons.forEach(lesson => {
                                if (lesson.lesson_id !== currentLessonId) { // Don't include current lesson
                                    select.innerHTML += `<option value="${lesson.lesson_id}">${module.module_title} - ${lesson.lesson_title}</option>`;
                                }
                            });
                        }
                    }
                }
            } catch (error) {
                console.error('Error loading lessons:', error);
            }
        }

        async function loadAssignmentOptions() {
            // For now, show placeholder - you can implement this when assignments are ready
            const select = document.getElementById('prerequisiteValue');
            select.innerHTML = '<option value="">No assignments available yet</option>';
        }

        async function savePrerequisiteData() {
            const prerequisiteType = document.getElementById('prerequisiteType').value;
            const prerequisiteValue = document.getElementById('prerequisiteValue').value;
            const requiredScore = document.getElementById('requiredScore').value;

            if (!prerequisiteValue && prerequisiteType !== 'assignment_submission') {
                alert('Please select a value for the prerequisite');
                return;
            }

            const prerequisiteData = {
                lesson_id: currentLessonId,
                prerequisite_type: prerequisiteType,
                prerequisite_value: prerequisiteValue,
                required_score: prerequisiteType === 'quiz_score' ? parseInt(requiredScore) : null
            };

            try {
                const response = await fetch('../api/courses/prerequisites.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(prerequisiteData)
                });

                const responseText = await response.text();
                if (!responseText) {
                    throw new Error('Empty response from server');
                }

                let result;
                try {
                    result = JSON.parse(responseText);
                } catch (e) {
                    console.error('Invalid JSON response:', responseText);
                    throw new Error('Invalid JSON response from server');
                }

                if (result.success) {
                    document.getElementById('prerequisiteModal').classList.add('hidden');
                    await loadPrerequisites();
                } else {
                    alert(result.error || 'Failed to save prerequisite');
                }
            } catch (error) {
                console.error('Error saving prerequisite:', error);
                alert('Failed to save prerequisite');
            }
        }

        async function deletePrerequisite(prerequisiteId) {
            if (!confirm('Are you sure you want to delete this prerequisite?')) {
                return;
            }

            try {
                const response = await fetch(`../api/courses/prerequisites.php?prerequisite_id=${prerequisiteId}`, {
                    method: 'DELETE'
                });

                const result = await response.json();

                if (result.success) {
                    await loadPrerequisites();
                } else {
                    alert(result.error || 'Failed to delete prerequisite');
                }
            } catch (error) {
                console.error('Error deleting prerequisite:', error);
                alert('Failed to delete prerequisite');
            }
        }
    </script>
</body>

</html>