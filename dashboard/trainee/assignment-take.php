<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Science Quiz - Module 6 - Saltel • Trainee</title>
    <?php include '../../include/imports.php'; ?>
    <style>
        .timer-warning {
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .question-fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .progress-bar {
            transition: width 0.3s ease;
        }
    </style>
</head>

<body class="font-sans bg-gray-50">
    <!-- Assignment Landing Page -->
    <div id="landingPage" class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-2xl w-full bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-8 text-white">
                <div class="flex items-center mb-4">
                    <a href="assignments.php" class="mr-4 p-2 hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <h1 class="text-3xl font-bold">Data Science Quiz</h1>
                </div>
                <p class="text-blue-100 text-lg">Module 6: Machine Learning Basics</p>
            </div>

            <!-- Assignment Details -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="text-center p-4 bg-blue-50 rounded-xl">
                        <i class="fas fa-clock text-2xl text-blue-600 mb-2"></i>
                        <div class="text-lg font-semibold text-gray-900">30 Minutes</div>
                        <div class="text-sm text-gray-600">Time Limit</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-xl">
                        <i class="fas fa-question-circle text-2xl text-green-600 mb-2"></i>
                        <div class="text-lg font-semibold text-gray-900">15 Questions</div>
                        <div class="text-sm text-gray-600">Multiple Choice</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-xl">
                        <i class="fas fa-trophy text-2xl text-purple-600 mb-2"></i>
                        <div class="text-lg font-semibold text-gray-900">75% to Pass</div>
                        <div class="text-sm text-gray-600">Passing Score</div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Instructions</h2>
                    <div class="bg-gray-50 rounded-xl p-6 space-y-3">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <p class="text-gray-700">You have <strong>30 minutes</strong> to complete all 15 questions</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <p class="text-gray-700">Each question has only <strong>one correct answer</strong></p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <p class="text-gray-700">You <strong>cannot go back</strong> to previous questions once answered</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <p class="text-gray-700">The quiz will <strong>auto-submit</strong> when time runs out</p>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
                            <p class="text-gray-700">You will receive a <strong>5-minute warning</strong> before time expires</p>
                        </div>
                    </div>
                </div>

                <!-- Start Button -->
                <div class="text-center">
                    <button id="startAssignmentBtn" class="px-8 py-4 bg-blue-600 text-white text-lg font-semibold rounded-xl hover:bg-blue-700 transition-colors shadow-lg">
                        <i class="fas fa-play mr-2"></i>
                        Start Assignment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quiz Interface -->
    <div id="quizInterface" class="hidden min-h-screen bg-gray-50">
        <!-- Top Bar with Timer and Progress -->
        <div class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-10">
            <div class="max-w-4xl mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-6">
                        <h1 class="text-xl font-semibold text-gray-900">Data Science Quiz</h1>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Question</span>
                            <span id="currentQuestionNum" class="font-semibold text-blue-600">1</span>
                            <span class="text-gray-600">of</span>
                            <span id="totalQuestions" class="font-semibold text-gray-900">15</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div id="timerDisplay" class="flex items-center space-x-2 px-4 py-2 bg-blue-50 rounded-lg">
                            <i class="fas fa-clock text-blue-600"></i>
                            <span id="timeRemaining" class="font-mono font-semibold text-blue-600">30:00</span>
                        </div>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="mt-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Progress</span>
                        <span id="progressPercent" class="text-sm font-semibold text-gray-900">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div id="progressBar" class="progress-bar bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Question Content -->
        <div class="max-w-4xl mx-auto px-6 py-8">
            <div id="questionContainer" class="bg-white rounded-2xl shadow-lg p-8 question-fade-in">
                <!-- Question will be loaded here -->
            </div>

            <!-- Navigation -->
            <div class="flex justify-between items-center mt-8">
                <div></div> <!-- Empty div for spacing -->
                <button id="nextQuestionBtn" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Next Question
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Results Page -->
    <div id="resultsPage" class="hidden min-h-screen flex items-center justify-center p-4">
        <div class="max-w-2xl w-full bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Results Header -->
            <div id="resultsHeader" class="p-8 text-white text-center">
                <i id="resultsIcon" class="text-6xl mb-4"></i>
                <h1 class="text-3xl font-bold mb-2" id="resultsTitle">Quiz Completed!</h1>
                <p class="text-lg opacity-90" id="resultsSubtitle">Here are your results</p>
            </div>

            <!-- Results Content -->
            <div class="p-8">
                <!-- Score Display -->
                <div class="text-center mb-8">
                    <div class="text-6xl font-bold text-gray-900 mb-2" id="finalScore">0%</div>
                    <div class="text-lg text-gray-600" id="scoreDescription">You answered 0 out of 15 questions correctly</div>
                </div>

                <!-- Performance Breakdown -->
                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="text-center p-4 bg-green-50 rounded-xl">
                        <div class="text-2xl font-bold text-green-600" id="correctAnswers">0</div>
                        <div class="text-sm text-gray-600">Correct</div>
                    </div>
                    <div class="text-center p-4 bg-red-50 rounded-xl">
                        <div class="text-2xl font-bold text-red-600" id="incorrectAnswers">0</div>
                        <div class="text-sm text-gray-600">Incorrect</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-2xl font-bold text-gray-600" id="timeSpent">00:00</div>
                        <div class="text-sm text-gray-600">Time Used</div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <button id="reviewAnswersBtn" class="flex-1 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        Review Answers
                    </button>
                    <button id="retakeAssignmentBtn" class="flex-1 px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-redo mr-2"></i>
                        Retake Quiz
                    </button>
                    <a href="assignments.php" class="flex-1 px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors text-center">
                        <i class="fas fa-home mr-2"></i>
                        Back to Assignments
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Warning Modal -->
    <div id="timeWarningModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
            <div class="p-6 text-center">
                <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Time Warning!</h3>
                <p class="text-gray-600 mb-6">You have <strong>5 minutes</strong> remaining to complete the quiz.</p>
                <button id="continueQuizBtn" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    Continue Quiz
                </button>
            </div>
        </div>
    </div>

    <!-- Time Up Modal -->
    <div id="timeUpModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
            <div class="p-6 text-center">
                <i class="fas fa-clock text-4xl text-red-500 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Time's Up!</h3>
                <p class="text-gray-600 mb-6">Your quiz has been automatically submitted.</p>
                <button id="viewResultsBtn" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    View Results
                </button>
            </div>
        </div>
    </div>

    <script src="../../assets/js/assignment-quiz.js"></script>
</body>

</html>
