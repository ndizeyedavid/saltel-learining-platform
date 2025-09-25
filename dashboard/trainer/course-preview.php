<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Preview - Saltel • Trainer</title>
    <?php include '../../include/trainer-guard.php'; ?>
    <?php include '../../include/trainer-imports.php'; ?>
</head>

<?php
// Get course ID from URL
$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

if ($course_id <= 0) {
    header('Location: courses.php');
    exit();
}

// Verify course ownership
$course_check = $conn->prepare("SELECT course_title, teacher_id, description, level, category FROM courses WHERE course_id = ?");
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
$course_description = $course_data['description'];
$course_level = $course_data['level'];
$course_category = $course_data['category'];
?>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">

            <!-- Course Content -->
            <main class="flex flex-1 overflow-hidden">
                <!-- Course Navigation Sidebar -->
                <div class="overflow-y-auto bg-white border-r border-gray-200 w-80">
                    <!-- Course Header -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center mb-4">
                            <a href="course-content.php?course_id=<?php echo $course_id; ?>">
                                <button class="p-2 mr-3 text-gray-600 transition-colors hover:text-blue-600" id="backToEditor">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                            </a>
                            <div>
                                <h1 class="text-lg font-bold text-gray-900" id="courseTitle"><?php echo htmlspecialchars($course_title); ?></h1>
                                <div class="text-sm text-gray-500">Preview Mode</div>
                            </div>
                        </div>

                        <!-- Course Info -->
                        <div class="mb-4">
                            <div class="mb-2 text-sm text-gray-600"><?php echo htmlspecialchars($course_description); ?></div>
                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                <span class="px-2 py-1 text-blue-700 bg-blue-100 rounded"><?php echo htmlspecialchars($course_level); ?></span>
                                <span><?php echo htmlspecialchars($course_category); ?></span>
                            </div>
                        </div>

                        <!-- Course Stats -->
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div class="p-3 rounded-lg bg-blue-50">
                                <div class="text-lg font-bold text-blue-600" id="totalModules">0</div>
                                <div class="text-xs text-gray-600">Modules</div>
                            </div>
                            <div class="p-3 rounded-lg bg-green-50">
                                <div class="text-lg font-bold text-green-600" id="totalLessons">0</div>
                                <div class="text-xs text-gray-600">Lessons</div>
                            </div>
                        </div>
                    </div>

                    <!-- Module Navigation -->
                    <div class="p-4">
                        <h3 class="mb-3 text-sm font-semibold text-gray-700">Course Modules</h3>
                        <div class="space-y-2" id="moduleList">
                            <!-- Modules will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="flex flex-col flex-1 overflow-hidden">
                    <!-- Lesson Header -->
                    <div class="px-6 py-4 bg-white border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center mb-1 space-x-2 text-sm text-gray-500">
                                    <span id="currentModule">Select a module</span>
                                    <i class="text-xs fas fa-chevron-right" id="breadcrumbArrow" style="display: none;"></i>
                                    <span id="currentLesson" style="display: none;"></span>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900" id="lessonTitle">Welcome to Course Preview</h2>
                                <p class="mt-1 text-sm text-gray-600" id="lessonDescription">Select a module from the sidebar to preview its content</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="px-3 py-1 text-xs font-medium text-orange-700 bg-orange-100 rounded-full">
                                    PREVIEW MODE
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lesson Content -->
                    <div class="flex-1 overflow-y-auto bg-gray-50">
                        <div class="max-w-4xl p-6 mx-auto">
                            <!-- Dynamic Content Container -->
                            <div id="lessonContent">
                                <!-- Welcome Message -->
                                <div class="p-8 text-center bg-white shadow-sm rounded-xl" id="welcomeSection">
                                    <div class="mb-4">
                                        <i class="text-4xl text-blue-600 fas fa-eye"></i>
                                    </div>
                                    <h3 class="mb-2 text-xl font-semibold text-gray-900">Course Preview Mode</h3>
                                    <p class="mb-4 text-gray-600">This is how your course will appear to students. Navigate through modules and lessons to see the complete learning experience.</p>
                                    <div class="text-sm text-gray-500">
                                        <p>• All content is displayed as students will see it</p>
                                        <p>• Prerequisites and quiz functionality are shown</p>
                                        <p>• Resources and materials are accessible</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Footer -->
                    <div class="px-6 py-4 bg-white border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <button class="flex items-center px-4 py-2 text-gray-400 cursor-not-allowed" id="prevLesson" disabled>
                                <i class="mr-2 fas fa-chevron-left"></i>
                                Previous Lesson
                            </button>

                            <div class="flex items-center space-x-4">
                                <div class="text-sm text-gray-500">Preview Mode - No Progress Tracking</div>
                                <button class="flex items-center px-4 py-2 text-gray-400 cursor-not-allowed" id="nextLesson" disabled>
                                    Next Lesson
                                    <i class="ml-2 fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        const courseId = <?php echo $course_id; ?>;
        let currentModuleId = null;
        let currentLessonId = null;
        let modules = [];
        let lessons = {};

        // Load course data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCourseModules();
        });

        // Load course modules
        async function loadCourseModules() {
            try {
                const response = await fetch(`../api/courses/modules.php?course_id=${courseId}`);
                const result = await response.json();

                if (result.modules && result.modules.length > 0) {
                    modules = result.modules;
                    document.getElementById('totalModules').textContent = modules.length;

                    let totalLessons = 0;
                    const moduleList = document.getElementById('moduleList');
                    moduleList.innerHTML = '';

                    for (const module of modules) {
                        // Load lessons for each module
                        const lessonsResponse = await fetch(`../api/courses/lessons.php?module_id=${module.module_id}`);
                        const lessonsResult = await lessonsResponse.json();

                        const moduleLessons = lessonsResult.lessons || [];
                        lessons[module.module_id] = moduleLessons;
                        totalLessons += moduleLessons.length;

                        const moduleHtml = createModuleHTML(module, moduleLessons);
                        moduleList.insertAdjacentHTML('beforeend', moduleHtml);
                    }

                    document.getElementById('totalLessons').textContent = totalLessons;
                } else {
                    document.getElementById('moduleList').innerHTML = '<p class="text-sm text-gray-500">No modules created yet.</p>';
                }
            } catch (error) {
                console.error('Error loading modules:', error);
            }
        }

        function createModuleHTML(module, moduleLessons) {
            const lessonCount = moduleLessons.length;
            const moduleId = module.module_id;

            return `
                <div class="overflow-hidden border border-gray-200 rounded-lg">
                    <div class="p-3 transition-colors cursor-pointer hover:bg-blue-50 hover:border-blue-300" onclick="toggleModule(${moduleId})">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 mr-3 text-white bg-blue-600 rounded-full">
                                    <i class="text-xs fas fa-book"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">${module.module_title}</div>
                                    <div class="text-xs text-gray-500">${lessonCount} lesson${lessonCount !== 1 ? 's' : ''}</div>
                                </div>
                            </div>
                            <i class="text-gray-400 transition-transform fas fa-chevron-down" id="chevron-${moduleId}"></i>
                        </div>
                    </div>
                    <div class="hidden bg-gray-50" id="lessons-${moduleId}">
                        ${moduleLessons.map((lesson, index) => `
                            <div class="px-6 py-2 transition-colors border-t border-gray-200 cursor-pointer hover:bg-blue-50" onclick="selectLesson(${moduleId}, ${lesson.lesson_id}, '${lesson.lesson_title}', ${index + 1})">
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center w-6 h-6 mr-3 text-xs text-gray-600 bg-white border border-gray-300 rounded-full">
                                        ${index + 1}
                                    </div>
                                    <div class="text-sm text-gray-700">${lesson.lesson_title}</div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        function toggleModule(moduleId) {
            const lessonsDiv = document.getElementById(`lessons-${moduleId}`);
            const chevron = document.getElementById(`chevron-${moduleId}`);

            if (lessonsDiv.classList.contains('hidden')) {
                lessonsDiv.classList.remove('hidden');
                chevron.style.transform = 'rotate(180deg)';
            } else {
                lessonsDiv.classList.add('hidden');
                chevron.style.transform = 'rotate(0deg)';
            }
        }

        async function selectLesson(moduleId, lessonId, lessonTitle, lessonNumber) {
            currentModuleId = moduleId;
            currentLessonId = lessonId;

            // Update header
            const module = modules.find(m => m.module_id === moduleId);
            document.getElementById('currentModule').textContent = module.module_title;
            document.getElementById('currentLesson').textContent = `Lesson ${lessonNumber}`;
            document.getElementById('currentLesson').style.display = 'inline';
            document.getElementById('breadcrumbArrow').style.display = 'inline';
            document.getElementById('lessonTitle').textContent = lessonTitle;
            document.getElementById('lessonDescription').textContent = 'Loading lesson content...';

            // Load lesson content
            await loadLessonContent(lessonId);
        }

        async function loadLessonContent(lessonId) {
            try {
                // Load lesson details
                const lessonResponse = await fetch(`../api/courses/lessons.php?lesson_id=${lessonId}`);
                const lessonResult = await lessonResponse.json();

                if (lessonResult.lesson) {
                    const lesson = lessonResult.lesson;
                    document.getElementById('lessonDescription').textContent = lesson.lesson_content ? 'Interactive lesson content' : 'Text-based lesson';

                    let contentHtml = '';

                    // Text Content
                    if (lesson.lesson_content) {
                        contentHtml += `
                            <div class="p-6 mb-6 bg-white shadow-sm rounded-xl">
                                <div class="prose max-w-none">
                                    ${lesson.lesson_content}
                                </div>
                            </div>
                        `;
                    }

                    // Load Resources
                    const resourcesResponse = await fetch(`../api/courses/resources.php?lesson_id=${lessonId}`);
                    const resourcesResult = await resourcesResponse.json();

                    if (resourcesResult.resources && resourcesResult.resources.length > 0) {
                        contentHtml += `
                            <div class="p-6 mb-6 bg-white shadow-sm rounded-xl">
                                <h3 class="mb-4 text-lg font-semibold text-gray-900">
                                    <i class="mr-2 text-blue-600 fas fa-download"></i>
                                    Lesson Resources
                                </h3>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    ${resourcesResult.resources.map(resource => `
                                        <a href="../../${resource.resource_url}" target="_blank" class="flex items-center p-4 transition-colors border border-gray-200 rounded-lg hover:bg-gray-50">
                                            <i class="mr-3 text-xl ${getResourceIcon(resource.resource_type, resource.mime_type)}"></i>
                                            <div>
                                                <div class="font-medium text-gray-900">${resource.resource_name}</div>
                                                <div class="text-sm text-gray-500">${resource.resource_type.toUpperCase()} • ${formatFileSize(resource.file_size)}</div>
                                            </div>
                                        </a>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                    }

                    // Load Quiz Questions
                    const quizResponse = await fetch(`../api/courses/quiz.php?lesson_id=${lessonId}`);
                    const quizResult = await quizResponse.json();

                    if (quizResult.questions && quizResult.questions.length > 0) {
                        contentHtml += `
                            <div class="p-6 mb-6 bg-white shadow-sm rounded-xl">
                                <div class="flex items-center mb-4">
                                    <i class="mr-3 text-xl text-blue-600 fas fa-question-circle"></i>
                                    <h3 class="text-lg font-semibold text-gray-900">Knowledge Check</h3>
                                </div>
                                ${quizResult.questions.map((question, index) => `
                                    <div class="mb-6 ${index > 0 ? 'border-t border-gray-200 pt-6' : ''}">
                                        <p class="mb-4 font-medium text-gray-700">${index + 1}. ${question.question_text}</p>
                                        ${question.options.length > 0 ? `
                                            <div class="space-y-3">
                                                ${question.options.map(option => `
                                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg ${option.is_correct ? 'bg-green-50 border-green-200' : ''}">
                                                        <input type="radio" name="quiz${question.question_id}" class="mr-3" ${option.is_correct ? 'checked' : ''} disabled>
                                                        <span class="${option.is_correct ? 'text-green-700 font-medium' : ''}">${option.option_text}</span>
                                                        ${option.is_correct ? '<i class="ml-auto text-green-600 fas fa-check"></i>' : ''}
                                                    </label>
                                                `).join('')}
                                            </div>
                                        ` : '<p class="text-sm italic text-gray-500">Open-ended question</p>'}
                                        <div class="mt-2 text-sm text-gray-500">Points: ${question.points}</div>
                                    </div>
                                `).join('')}
                            </div>
                        `;
                    }

                    // Load Prerequisites
                    const prereqResponse = await fetch(`../api/courses/prerequisites.php?lesson_id=${lessonId}`);
                    const prereqResult = await prereqResponse.json();

                    if (prereqResult.prerequisites && prereqResult.prerequisites.length > 0) {
                        contentHtml += `
                            <div class="p-6 mb-6 bg-white shadow-sm rounded-xl">
                                <h3 class="mb-4 text-lg font-semibold text-gray-900">
                                    <i class="mr-2 text-orange-600 fas fa-lock"></i>
                                    Prerequisites
                                </h3>
                                <div class="space-y-3">
                                    ${prereqResult.prerequisites.map(prereq => `
                                        <div class="flex items-center p-3 border border-orange-200 rounded-lg bg-orange-50">
                                            <i class="mr-3 text-orange-600 fas fa-exclamation-triangle"></i>
                                            <span class="text-sm text-orange-700">${prereq.description}</span>
                                        </div>
                                    `).join('')}
                                </div>
                                <div class="mt-3 text-sm text-gray-600">
                                    Students must complete these requirements before accessing this lesson.
                                </div>
                            </div>
                        `;
                    }

                    if (!contentHtml) {
                        contentHtml = `
                            <div class="p-8 text-center bg-white shadow-sm rounded-xl">
                                <i class="mb-4 text-4xl text-gray-400 fas fa-file-alt"></i>
                                <h3 class="mb-2 text-lg font-semibold text-gray-900">No Content Yet</h3>
                                <p class="text-gray-600">This lesson doesn't have any content, resources, or quizzes yet.</p>
                            </div>
                        `;
                    }

                    document.getElementById('lessonContent').innerHTML = contentHtml;
                }
            } catch (error) {
                console.error('Error loading lesson content:', error);
                document.getElementById('lessonContent').innerHTML = `
                    <div class="p-8 text-center bg-white shadow-sm rounded-xl">
                        <i class="mb-4 text-4xl text-red-400 fas fa-exclamation-triangle"></i>
                        <h3 class="mb-2 text-lg font-semibold text-gray-900">Error Loading Content</h3>
                        <p class="text-gray-600">There was an error loading the lesson content.</p>
                    </div>
                `;
            }
        }

        function getResourceIcon(resourceType, mimeType) {
            switch (resourceType) {
                case 'video':
                    return 'text-red-500 fas fa-play-circle';
                case 'image':
                    return 'text-green-500 fas fa-image';
                case 'audio':
                    return 'text-purple-500 fas fa-volume-up';
                case 'document':
                    if (mimeType && mimeType.includes('pdf')) {
                        return 'text-red-500 fas fa-file-pdf';
                    } else if (mimeType && (mimeType.includes('word') || mimeType.includes('document'))) {
                        return 'text-blue-500 fas fa-file-word';
                    } else if (mimeType && (mimeType.includes('excel') || mimeType.includes('spreadsheet'))) {
                        return 'text-green-500 fas fa-file-excel';
                    } else if (mimeType && (mimeType.includes('powerpoint') || mimeType.includes('presentation'))) {
                        return 'text-orange-500 fas fa-file-powerpoint';
                    }
                    return 'text-gray-500 fas fa-file-alt';
                case 'link':
                    return 'text-blue-500 fas fa-external-link-alt';
                default:
                    return 'text-gray-500 fas fa-file';
            }
        }

        function formatFileSize(bytes) {
            if (!bytes) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>
</body>

</html>