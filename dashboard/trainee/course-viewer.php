<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Viewer - Saltel • Trainee</title>
    <?php
    include '../../include/imports.php';
    require_once '../../include/connect.php';

    session_start();
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../../login.php');
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $course_id = isset($_GET['course']) ? (int)$_GET['course'] : 0;
    $lesson_id = isset($_GET['lesson']) ? (int)$_GET['lesson'] : null;

    if ($course_id <= 0) {
        header('Location: courses.php');
        exit();
    }

    // Get course information
    $course_query = "SELECT c.*, u.first_name, u.last_name, 
                           COUNT(DISTINCT cm.module_id) as total_modules,
                           COUNT(DISTINCT cl.lesson_id) as total_lessons
                    FROM courses c 
                    JOIN users u ON c.teacher_id = u.user_id
                    LEFT JOIN course_modules cm ON c.course_id = cm.course_id AND cm.is_published = 0
                    LEFT JOIN course_lessons cl ON cm.module_id = cl.module_id
                    WHERE c.course_id = ? AND c.status = 'Published'
                    GROUP BY c.course_id";

    $stmt = $conn->prepare($course_query);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $course_result = $stmt->get_result();

    if ($course_result->num_rows === 0) {
        header('Location: courses.php');
        exit();
    }

    $course = $course_result->fetch_assoc();

    // Check if user is enrolled
    $enrollment_query = "SELECT e.*, s.student_id 
                        FROM enrollments e 
                        JOIN students s ON e.student_id = s.student_id 
                        WHERE s.user_id = ? AND e.course_id = ? AND e.payment_status = 'Paid'";

    $stmt = $conn->prepare($enrollment_query);
    $stmt->bind_param("ii", $user_id, $course_id);
    $stmt->execute();
    $enrollment_result = $stmt->get_result();

    if ($enrollment_result->num_rows === 0) {
        header('Location: courses.php?error=not_enrolled');
        exit();
    }

    $enrollment = $enrollment_result->fetch_assoc();
    $student_id = $enrollment['student_id'];

    // Get course modules with lessons
    $modules_query = "SELECT cm.*, 
                            COUNT(cl.lesson_id) as lesson_count,
                            SUM(CASE WHEN cl.lesson_id IS NOT NULL THEN cm.duration_minutes ELSE 0 END) as total_duration
                     FROM course_modules cm
                     LEFT JOIN course_lessons cl ON cm.module_id = cl.module_id
                     WHERE cm.course_id = ? AND cm.is_published = 0
                     GROUP BY cm.module_id
                     ORDER BY cm.sort_order ASC";

    $stmt = $conn->prepare($modules_query);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $modules_result = $stmt->get_result();
    $modules = $modules_result->fetch_all(MYSQLI_ASSOC);
    // var_dump($modules);

    // if (!empty($modules)) {
    //     error_log("First module: " . print_r($modules[0], true));
    // }

    // Get current lesson or first lesson if none specified
    if (!$lesson_id && !empty($modules)) {
        $first_lesson_query = "SELECT lesson_id FROM course_lessons cl
                              JOIN course_modules cm ON cl.module_id = cm.module_id
                              WHERE cm.course_id = ? AND cm.is_published = 1
                              ORDER BY cm.sort_order ASC, cl.sort_order ASC
                              LIMIT 1";
        $stmt = $conn->prepare($first_lesson_query);
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $first_lesson_result = $stmt->get_result();
        if ($first_lesson_result->num_rows > 0) {
            $lesson_id = $first_lesson_result->fetch_assoc()['lesson_id'];
        }
    }

    $current_lesson = null;
    $current_module = null;

    if ($lesson_id) {
        // Get current lesson details
        $lesson_query = "SELECT cl.*, cm.title as module_title, cm.module_id
                        FROM course_lessons cl
                        JOIN course_modules cm ON cl.module_id = cm.module_id
                        WHERE cl.lesson_id = ? AND cm.course_id = ?";

        $stmt = $conn->prepare($lesson_query);
        $stmt->bind_param("ii", $lesson_id, $course_id);
        $stmt->execute();
        $lesson_result = $stmt->get_result();

        if ($lesson_result->num_rows > 0) {
            $current_lesson = $lesson_result->fetch_assoc();
            $current_module = $current_lesson;
        }
    }

    // Get lesson resources
    $resources = [];
    if ($lesson_id) {
        $resources_query = "SELECT * FROM course_resources 
                           WHERE lesson_id = ? 
                           ORDER BY resource_id ASC";
        $stmt = $conn->prepare($resources_query);
        $stmt->bind_param("i", $lesson_id);
        $stmt->execute();
        $resources_result = $stmt->get_result();
        $resources = $resources_result->fetch_all(MYSQLI_ASSOC);
    }

    // Calculate progress
    $total_lessons = (int)$course['total_lessons'];

    // Fetch completed lessons from database
    $completed_lessons_query = "SELECT COUNT(DISTINCT lc.lesson_id) as completed_count
                               FROM lesson_completions lc
                               JOIN course_lessons cl ON lc.lesson_id = cl.lesson_id
                               JOIN course_modules cm ON cl.module_id = cm.module_id
                               WHERE cm.course_id = ? AND lc.user_id = ?";
    $stmt = $conn->prepare($completed_lessons_query);
    $stmt->bind_param("ii", $course_id, $user_id);
    $stmt->execute();
    $completion_result = $stmt->get_result();
    $completion_data = $completion_result->fetch_assoc();
    $completed_lessons = (int)$completion_data['completed_count'];

    $progress_percentage = $total_lessons > 0 ? round(($completed_lessons / $total_lessons) * 100) : 0;
    // echo $total_lessons;
    ?>
    <script src="../../assets/js/course-viewer.js" defer></script>
    <script src="../../assets/js/gamification.js" defer></script>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Header -->

            <!-- Course Content -->
            <main class="flex flex-1 overflow-hidden">
                <!-- Course Navigation Sidebar -->
                <div class="overflow-y-auto bg-white border-r border-gray-200 w-80">
                    <!-- Course Header -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center mb-4">
                            <a href="courses.php">
                                <button class="p-2 mr-3 text-gray-600 transition-colors hover:text-blue-600" id="backToCourses">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                            </a>
                            <h1 class="text-lg font-bold text-gray-900" id="courseTitle"><?php echo htmlspecialchars($course['course_title']); ?></h1>
                        </div>

                        <!-- Progress Overview -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Course Progress</span>
                                <span class="text-sm font-semibold text-blue-600" id="overallProgress"><?php echo $progress_percentage; ?>%</span>
                            </div>
                            <div class="w-full h-2 bg-gray-200 rounded-full">
                                <div class="h-2 transition-all duration-300 bg-blue-600 rounded-full" style="width: <?php echo $progress_percentage; ?>%" id="progressBar"></div>
                            </div>
                            <div class="flex items-center justify-between mt-2 text-xs text-gray-500">
                                <span id="completedLessons"><?php echo $completed_lessons; ?> of <?php echo $total_lessons; ?> lessons</span>
                                <span id="timeRemaining">~5 weeks left</span>
                            </div>
                        </div>

                        <!-- Course Stats -->
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div class="p-3 rounded-lg bg-blue-50">
                                <div class="text-lg font-bold text-blue-600" id="totalModules"><?php echo count($modules); ?></div>
                                <div class="text-xs text-gray-600">Modules</div>
                            </div>
                            <div class="p-3 rounded-lg bg-green-50">
                                <div class="text-lg font-bold text-green-600" id="completedModules">5</div>
                                <div class="text-xs text-gray-600">Completed</div>
                            </div>
                        </div>
                    </div>

                    <!-- Module Navigation -->
                    <div class="p-4">
                        <h3 class="mb-3 text-sm font-semibold text-gray-700">Course Modules</h3>
                        <div class="space-y-2" id="moduleList">
                            <!-- Modules will be loaded dynamically via JavaScript -->
                            <?php if (empty($modules)): ?>
                                <div class="p-4 text-center text-gray-500">
                                    <i class="mb-2 text-2xl fas fa-spinner fa-spin"></i>
                                    <p>Loading course modules...</p>
                                </div>
                            <?php else: ?>
                                <!-- Fallback: Show PHP modules if JavaScript fails -->
                                <?php foreach ($modules as $index => $module):
                                    $module_progress = 0; // TODO: Calculate actual module progress
                                    $is_current = $current_module && isset($current_module['module_id']) && $current_module['module_id'] == $module['module_id'];
                                    $is_completed = $module_progress >= 100;
                                    $is_locked = false; // TODO: Implement module prerequisites
                                    $status_class = $is_completed ? 'bg-green-500' : ($is_current ? 'bg-blue-600' : 'bg-gray-200');
                                    $status_icon = $is_completed ? 'fa-check' : ($is_current ? 'fa-play' : 'fa-lock');
                                    $text_color = $is_locked ? 'text-gray-500' : 'text-gray-900';
                                    $border_class = $is_current ? 'border-2 border-blue-300 bg-blue-50' : 'border border-gray-200';
                                ?>
                                    <div class="p-3 transition-colors rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-300 <?php echo $border_class; ?> fallback-module"
                                        data-module="<?php echo $module['module_id']; ?>">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="flex items-center justify-center w-8 h-8 mr-3 text-white rounded-full <?php echo $status_class; ?>">
                                                    <i class="text-xs fas <?php echo $status_icon; ?>"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium <?php echo $text_color; ?>"><?php echo htmlspecialchars($module['title']); ?></div>
                                                    <div class="text-xs text-gray-500">
                                                        <?php echo $module['lesson_count']; ?> lessons
                                                        <?php if ($module['total_duration']): ?>
                                                            • <?php echo floor($module['total_duration'] / 60); ?>h <?php echo $module['total_duration'] % 60; ?>m
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-xs font-medium <?php echo $is_completed ? 'text-green-600' : ($is_current ? 'text-blue-600' : 'text-gray-400'); ?>">
                                                <?php echo $is_locked ? 'Locked' : $module_progress . '%'; ?>
                                            </div>
                                        </div>
                                        <?php if ($is_current && $module_progress > 0 && $module_progress < 100): ?>
                                            <div class="mt-2 ml-11">
                                                <div class="w-full h-1 bg-gray-200 rounded-full">
                                                    <div class="h-1 bg-blue-600 rounded-full" style="width: <?php echo $module_progress; ?>%"></div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
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
                                    <span id="currentModule"><?php echo $current_module ? htmlspecialchars($current_module['module_title']) : 'Select Module'; ?></span>
                                    <?php if ($current_lesson): ?>
                                        <i class="text-xs fas fa-chevron-right"></i>
                                        <span id="currentLesson">Lesson</span>
                                    <?php endif; ?>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900" id="lessonTitle"><?php echo $current_lesson ? htmlspecialchars($current_lesson['title']) : 'Select a lesson to begin'; ?></h2>
                                <p class="mt-1 text-sm text-gray-600" id="lessonDescription"><?php echo $current_lesson && $current_lesson['content'] ? strip_tags(substr($current_lesson['content'], 0, 150)) . '...' : 'Choose a module and lesson from the sidebar to start learning.'; ?></p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button class="p-2 text-gray-600 transition-colors hover:text-blue-600" id="bookmarkLesson" title="Bookmark">
                                    <i class="far fa-bookmark"></i>
                                </button>
                                <button class="p-2 text-gray-600 transition-colors hover:text-blue-600" id="takeNotes" title="Take Notes">
                                    <i class="fas fa-sticky-note"></i>
                                </button>
                                <button class="p-2 text-gray-600 transition-colors hover:text-blue-600" id="shareLesson" title="Share">
                                    <i class="fas fa-share-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Lesson Content -->
                    <div class="flex-1 overflow-y-auto bg-gray-50">
                        <div class="max-w-4xl p-6 mx-auto">
                            <!-- Dynamic Content Container -->
                            <div id="lessonContent">
                                <?php if ($current_lesson): ?>
                                    <?php
                                    // Check for video resources
                                    $video_resources = array_filter($resources, function ($r) {
                                        return $r['resource_type'] === 'video';
                                    });
                                    ?>

                                    <?php if (!empty($video_resources)): ?>
                                        <!-- Video Content -->
                                        <div class="mb-6 bg-white shadow-sm rounded-xl" id="videoSection">
                                            <?php $video = reset($video_resources); ?>
                                            <div class="relative overflow-hidden bg-gray-900 aspect-video rounded-t-xl">
                                                <video class="object-cover w-full h-full" controls id="lessonVideo">
                                                    <source src="../../<?php echo htmlspecialchars($video['resource_url']); ?>" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                                <!-- Video Overlay -->
                                                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50" id="videoOverlay">
                                                    <button class="flex items-center justify-center transition-all bg-white rounded-full size-[50px] bg-opacity-20 hover:bg-opacity-30" id="playButton" onclick="document.getElementById('videoOverlay').style.display='none'; document.getElementById('lessonVideo').play();">
                                                        <i class="text-2xl text-white fas fa-play"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="p-4">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                        <span><i class="mr-1 far fa-clock"></i><?php echo htmlspecialchars($video['resource_name']); ?></span>
                                                        <?php if ($video['file_size']): ?>
                                                            <span><i class="mr-1 fas fa-download"></i><?php echo round($video['file_size'] / (1024 * 1024), 1); ?> MB</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <?php if ($video['is_downloadable']): ?>
                                                            <a href="<?php echo htmlspecialchars($video['resource_url']); ?>" download class="text-sm font-medium text-blue-600 hover:text-blue-700">Download</a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Text Content -->
                                    <?php if ($current_lesson['content']): ?>
                                        <div class="p-6 mb-6 bg-white shadow-sm rounded-xl" id="textContent">
                                            <div class="prose max-w-none">
                                                <?php echo $current_lesson['content']; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <!-- No Lesson Selected -->
                                    <div class="p-8 text-center bg-white shadow-sm rounded-xl">
                                        <i class="mb-4 text-4xl text-gray-400 fas fa-book-open"></i>
                                        <h3 class="mb-2 text-lg font-semibold text-gray-900">Select a Module to Begin</h3>
                                        <p class="text-gray-600">Choose a module from the sidebar to start your learning journey.</p>
                                    </div>
                                <?php endif; ?>

                                <?php if ($current_lesson): ?>
                                    <?php
                                    // Get quiz questions for this lesson
                                    $quiz_query = "SELECT qq.*, qao.option_id, qao.option_text, qao.is_correct, qao.option_order 
                                                  FROM quiz_questions qq 
                                                  LEFT JOIN quiz_answer_options qao ON qq.question_id = qao.question_id 
                                                  WHERE qq.lesson_id = ? 
                                                  ORDER BY qq.question_order ASC, qao.option_order ASC";
                                    $stmt = $conn->prepare($quiz_query);
                                    $stmt->bind_param("i", $lesson_id);
                                    $stmt->execute();
                                    $quiz_result = $stmt->get_result();

                                    $quiz_questions = [];
                                    while ($row = $quiz_result->fetch_assoc()) {
                                        if (!isset($quiz_questions[$row['question_id']])) {
                                            $quiz_questions[$row['question_id']] = [
                                                'question_id' => $row['question_id'],
                                                'question_text' => $row['question_text'],
                                                'question_type' => $row['question_type'],
                                                'points' => $row['points'],
                                                'options' => []
                                            ];
                                        }
                                        if ($row['option_text']) {
                                            $quiz_questions[$row['question_id']]['options'][] = [
                                                'id' => $row['option_id'],
                                                'text' => $row['option_text'],
                                                'is_correct' => $row['is_correct']
                                            ];
                                        }
                                    }
                                    ?>

                                    <?php if (!empty($quiz_questions)): ?>
                                        <!-- Interactive Quiz -->
                                        <div class="p-6 mb-6 bg-white shadow-sm rounded-xl" id="quizSection">
                                            <div class="flex items-center mb-4">
                                                <i class="mr-3 text-xl text-blue-600 fas fa-question-circle"></i>
                                                <h3 class="text-lg font-semibold text-gray-900">Knowledge Check</h3>
                                            </div>

                                            <?php foreach ($quiz_questions as $question): ?>
                                                <div class="mb-6">
                                                    <p class="mb-4 text-gray-700"><?php echo htmlspecialchars($question['question_text']); ?></p>
                                                    <div class="space-y-3">
                                                        <?php foreach ($question['options'] as $index => $option): ?>
                                                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                                                <input type="radio" name="quiz_<?php echo $question['question_id']; ?>" value="<?php echo $option['id']; ?>" class="mr-3">
                                                                <!-- <input type="radio" name="quiz_1" value="<?php echo $index; ?>" class="mr-3"> -->
                                                                <span><?php echo htmlspecialchars($option['text']); ?></span>
                                                            </label>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>

                                            <button class="px-6 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700" id="submitQuiz">
                                                Submit Answer
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if ($current_lesson && !empty($resources)): ?>
                                    <!-- Resources Section -->
                                    <div class="p-6 mb-6 bg-white shadow-sm rounded-xl" id="resourcesSection">
                                        <h3 class="mb-4 text-lg font-semibold text-gray-900">
                                            <i class="mr-2 text-blue-600 fas fa-download"></i>
                                            Lesson Resources
                                        </h3>
                                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                            <?php foreach ($resources as $resource):
                                                $icon_class = '';
                                                $color_class = '';
                                                switch ($resource['resource_type']) {
                                                    case 'document':
                                                        $icon_class = 'fa-file-pdf';
                                                        $color_class = 'text-red-500';
                                                        break;
                                                    case 'video':
                                                        $icon_class = 'fa-play-circle';
                                                        $color_class = 'text-blue-500';
                                                        break;
                                                    case 'audio':
                                                        $icon_class = 'fa-volume-up';
                                                        $color_class = 'text-green-500';
                                                        break;
                                                    case 'image':
                                                        $icon_class = 'fa-image';
                                                        $color_class = 'text-purple-500';
                                                        break;
                                                    case 'link':
                                                        $icon_class = 'fa-link';
                                                        $color_class = 'text-blue-600';
                                                        break;
                                                    default:
                                                        $icon_class = 'fa-file';
                                                        $color_class = 'text-gray-500';
                                                }

                                                $file_size_text = '';
                                                if ($resource['file_size']) {
                                                    $size_mb = round($resource['file_size'] / (1024 * 1024), 1);
                                                    $file_size_text = $size_mb . ' MB';
                                                }

                                                $mime_type_display = strtoupper(pathinfo($resource['resource_name'], PATHINFO_EXTENSION));
                                            ?>
                                                <a href="../../<?php echo htmlspecialchars($resource['resource_url']); ?>"
                                                    <?php echo $resource['resource_type'] === 'link' ? 'target="_blank"' : ($resource['is_downloadable'] ? 'download' : ''); ?>
                                                    class="flex items-center p-4 transition-colors border border-gray-200 rounded-lg hover:bg-gray-50">
                                                    <i class="mr-3 text-xl <?php echo $color_class; ?> fas <?php echo $icon_class; ?>"></i>
                                                    <div>
                                                        <div class="font-medium text-gray-900"><?php echo htmlspecialchars($resource['resource_name']); ?></div>
                                                        <div class="text-sm text-gray-500">
                                                            <?php echo $mime_type_display; ?>
                                                            <?php if ($file_size_text): ?>
                                                                • <?php echo $file_size_text; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Footer -->
                    <div class="px-6 py-4 bg-white border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <button class="flex items-center px-4 py-2 text-gray-600 transition-colors hover:text-blue-600" id="prevLesson">
                                <i class="mr-2 fas fa-chevron-left"></i>
                                Previous Lesson
                            </button>

                            <div class="flex items-center space-x-4">
                                <button class="flex items-center px-4 py-2 text-green-600 transition-colors border border-green-200 rounded-lg hover:text-green-700" id="markComplete">
                                    <i class="mr-2 fas fa-check"></i>
                                    Mark as Complete
                                </button>
                                <button class="flex items-center px-6 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700" id="nextLesson">
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

    <!-- Notes Modal -->
    <div id="notesModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Lesson Notes</h3>
                <button class="text-gray-400 transition-colors hover:text-gray-600" id="closeNotes">
                    <i class="text-xl fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <textarea class="w-full h-64 p-4 border border-gray-300 rounded-lg resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Add your notes for this lesson..." id="notesTextarea"></textarea>
                <div class="flex justify-end mt-4 space-x-3">
                    <button class="px-4 py-2 text-gray-600 transition-colors hover:text-gray-700" id="cancelNotes">
                        Cancel
                    </button>
                    <button class="px-6 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700" id="saveNotes">
                        Save Notes
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>