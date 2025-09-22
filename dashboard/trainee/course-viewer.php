<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Viewer - Saltel • Trainee</title>
    <?php include '../../include/imports.php'; ?>
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
                            <h1 class="text-lg font-bold text-gray-900" id="courseTitle">Data Science & Analytics</h1>
                        </div>

                        <!-- Progress Overview -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Course Progress</span>
                                <span class="text-sm font-semibold text-blue-600" id="overallProgress">65%</span>
                            </div>
                            <div class="w-full h-2 bg-gray-200 rounded-full">
                                <div class="h-2 transition-all duration-300 bg-blue-600 rounded-full" style="width: 65%" id="progressBar"></div>
                            </div>
                            <div class="flex items-center justify-between mt-2 text-xs text-gray-500">
                                <span id="completedLessons">31 of 48 lessons</span>
                                <span id="timeRemaining">~5 weeks left</span>
                            </div>
                        </div>

                        <!-- Course Stats -->
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div class="p-3 rounded-lg bg-blue-50">
                                <div class="text-lg font-bold text-blue-600" id="totalModules">8</div>
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
                            <!-- Module 1 -->
                            <div class="p-3 transition-colors border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-300" data-module="1">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex items-center justify-center w-8 h-8 mr-3 text-white bg-green-500 rounded-full">
                                            <i class="text-xs fas fa-check"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Introduction to Data Science</div>
                                            <div class="text-xs text-gray-500">6 lessons • 2h 30m</div>
                                        </div>
                                    </div>
                                    <div class="text-xs font-medium text-green-600">100%</div>
                                </div>
                            </div>

                            <!-- Module 2 -->
                            <div class="p-3 transition-colors border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-300" data-module="2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex items-center justify-center w-8 h-8 mr-3 text-white bg-green-500 rounded-full">
                                            <i class="text-xs fas fa-check"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Python for Data Analysis</div>
                                            <div class="text-xs text-gray-500">8 lessons • 3h 45m</div>
                                        </div>
                                    </div>
                                    <div class="text-xs font-medium text-green-600">100%</div>
                                </div>
                            </div>

                            <!-- Module 3 -->
                            <div class="p-3 transition-colors border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-300" data-module="3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex items-center justify-center w-8 h-8 mr-3 text-white bg-green-500 rounded-full">
                                            <i class="text-xs fas fa-check"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Data Visualization</div>
                                            <div class="text-xs text-gray-500">5 lessons • 2h 15m</div>
                                        </div>
                                    </div>
                                    <div class="text-xs font-medium text-green-600">100%</div>
                                </div>
                            </div>

                            <!-- Module 4 -->
                            <div class="p-3 transition-colors border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-300" data-module="4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex items-center justify-center w-8 h-8 mr-3 text-white bg-green-500 rounded-full">
                                            <i class="text-xs fas fa-check"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Statistical Analysis</div>
                                            <div class="text-xs text-gray-500">7 lessons • 3h 20m</div>
                                        </div>
                                    </div>
                                    <div class="text-xs font-medium text-green-600">100%</div>
                                </div>
                            </div>

                            <!-- Module 5 -->
                            <div class="p-3 transition-colors border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-300" data-module="5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex items-center justify-center w-8 h-8 mr-3 text-white bg-green-500 rounded-full">
                                            <i class="text-xs fas fa-check"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Database & SQL</div>
                                            <div class="text-xs text-gray-500">6 lessons • 2h 50m</div>
                                        </div>
                                    </div>
                                    <div class="text-xs font-medium text-green-600">100%</div>
                                </div>
                            </div>

                            <!-- Module 6 - Current Module -->
                            <div class="p-3 transition-colors border-2 border-blue-300 rounded-lg cursor-pointer bg-blue-50" data-module="6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex items-center justify-center w-8 h-8 mr-3 text-white bg-blue-600 rounded-full">
                                            <i class="text-xs fas fa-play"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Machine Learning Basics</div>
                                            <div class="text-xs text-gray-500">8 lessons • 4h 10m</div>
                                        </div>
                                    </div>
                                    <div class="text-xs font-medium text-blue-600">38%</div>
                                </div>
                                <div class="mt-2 ml-11">
                                    <div class="w-full h-1 bg-gray-200 rounded-full">
                                        <div class="h-1 bg-blue-600 rounded-full" style="width: 38%"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Module 7 -->
                            <div class="p-3 transition-colors border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-300" data-module="7">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex items-center justify-center w-8 h-8 mr-3 text-gray-400 bg-gray-200 rounded-full">
                                            <i class="text-xs fas fa-lock"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-500">Advanced Analytics</div>
                                            <div class="text-xs text-gray-400">7 lessons • 3h 35m</div>
                                        </div>
                                    </div>
                                    <div class="text-xs font-medium text-gray-400">Locked</div>
                                </div>
                            </div>

                            <!-- Module 8 -->
                            <div class="p-3 transition-colors border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-300" data-module="8">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex items-center justify-center w-8 h-8 mr-3 text-gray-400 bg-gray-200 rounded-full">
                                            <i class="text-xs fas fa-lock"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-500">Capstone Project</div>
                                            <div class="text-xs text-gray-400">5 lessons • 6h 00m</div>
                                        </div>
                                    </div>
                                    <div class="text-xs font-medium text-gray-400">Locked</div>
                                </div>
                            </div>
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
                                    <span id="currentModule">Module 6</span>
                                    <i class="text-xs fas fa-chevron-right"></i>
                                    <span id="currentLesson">Lesson 3</span>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900" id="lessonTitle">Machine Learning Algorithms</h2>
                                <p class="mt-1 text-sm text-gray-600" id="lessonDescription">Learn about supervised and unsupervised learning techniques</p>
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
                                <!-- Video Content -->
                                <div class="mb-6 bg-white shadow-sm rounded-xl" id="videoSection">
                                    <div class="relative overflow-hidden bg-gray-900 aspect-video rounded-t-xl">
                                        <video class="object-cover w-full h-full" controls id="lessonVideo">
                                            <source src="https://img.pikbest.com/10/52/74/81CpIkbEsTVnR.mp4" type="video/mp4">
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
                                                <span><i class="mr-1 far fa-clock"></i>15:30 duration</span>
                                                <span><i class="mr-1 fas fa-eye"></i>1,234 views</span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <button class="text-sm font-medium text-blue-600 hover:text-blue-700">Download</button>
                                                <button class="text-sm font-medium text-blue-600 hover:text-blue-700">Transcript</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Text Content -->
                                <div class="p-6 mb-6 bg-white shadow-sm rounded-xl" id="textContent">
                                    <div class="prose max-w-none">
                                        <h3>Introduction to Machine Learning</h3>
                                        <p>Machine learning is a subset of artificial intelligence that enables computers to learn and make decisions from data without being explicitly programmed for every task.</p>

                                        <h4>Types of Machine Learning</h4>
                                        <ul>
                                            <li><strong>Supervised Learning:</strong> Uses labeled data to train models</li>
                                            <li><strong>Unsupervised Learning:</strong> Finds patterns in unlabeled data</li>
                                            <li><strong>Reinforcement Learning:</strong> Learns through interaction and feedback</li>
                                        </ul>

                                        <div class="p-4 my-6 border-l-4 border-blue-400 bg-blue-50">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <i class="text-blue-400 fas fa-info-circle"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm text-blue-700">
                                                        <strong>Pro Tip:</strong> Start with supervised learning algorithms as they're easier to understand and implement.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Interactive Quiz -->
                                <div class="p-6 mb-6 bg-white shadow-sm rounded-xl" id="quizSection">
                                    <div class="flex items-center mb-4">
                                        <i class="mr-3 text-xl text-blue-600 fas fa-question-circle"></i>
                                        <h3 class="text-lg font-semibold text-gray-900">Knowledge Check</h3>
                                    </div>

                                    <div class="mb-6">
                                        <p class="mb-4 text-gray-700">Which type of machine learning uses labeled data for training?</p>
                                        <div class="space-y-3">
                                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                                <input type="radio" name="quiz1" value="supervised" class="mr-3">
                                                <span>Supervised Learning</span>
                                            </label>
                                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                                <input type="radio" name="quiz1" value="unsupervised" class="mr-3">
                                                <span>Unsupervised Learning</span>
                                            </label>
                                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                                <input type="radio" name="quiz1" value="reinforcement" class="mr-3">
                                                <span>Reinforcement Learning</span>
                                            </label>
                                        </div>
                                    </div>

                                    <button class="px-6 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700" id="submitQuiz">
                                        Submit Answer
                                    </button>
                                </div>

                                <!-- Resources Section -->
                                <div class="p-6 mb-6 bg-white shadow-sm rounded-xl" id="resourcesSection">
                                    <h3 class="mb-4 text-lg font-semibold text-gray-900">
                                        <i class="mr-2 text-blue-600 fas fa-download"></i>
                                        Lesson Resources
                                    </h3>
                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                        <a href="#" class="flex items-center p-4 transition-colors border border-gray-200 rounded-lg hover:bg-gray-50">
                                            <i class="mr-3 text-xl text-red-500 fas fa-file-pdf"></i>
                                            <div>
                                                <div class="font-medium text-gray-900">ML Algorithms Guide</div>
                                                <div class="text-sm text-gray-500">PDF • 2.4 MB</div>
                                            </div>
                                        </a>
                                        <a href="#" class="flex items-center p-4 transition-colors border border-gray-200 rounded-lg hover:bg-gray-50">
                                            <i class="mr-3 text-xl text-green-500 fas fa-code"></i>
                                            <div>
                                                <div class="font-medium text-gray-900">Python Code Examples</div>
                                                <div class="text-sm text-gray-500">ZIP • 1.8 MB</div>
                                            </div>
                                        </a>
                                        <a href="#" class="flex items-center p-4 transition-colors border border-gray-200 rounded-lg hover:bg-gray-50">
                                            <i class="mr-3 text-xl text-blue-500 fas fa-database"></i>
                                            <div>
                                                <div class="font-medium text-gray-900">Sample Dataset</div>
                                                <div class="text-sm text-gray-500">CSV • 856 KB</div>
                                            </div>
                                        </a>
                                        <a href="#" class="flex items-center p-4 transition-colors border border-gray-200 rounded-lg hover:bg-gray-50">
                                            <i class="mr-3 text-xl text-purple-500 fas fa-link"></i>
                                            <div>
                                                <div class="font-medium text-gray-900">Additional Reading</div>
                                                <div class="text-sm text-gray-500">External Links</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
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