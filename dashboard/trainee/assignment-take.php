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

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .question-fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .progress-bar {
            transition: width 0.3s ease;
        }

        /* Anti-cheat styles */
        .no-select {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-touch-callout: none;
            -webkit-tap-highlight-color: transparent;
        }

        .anti-cheat-warning {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            padding: 12px;
            text-align: center;
            font-weight: bold;
            z-index: 9999;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transform: translateY(-100%);
            transition: transform 0.3s ease;
        }

        .anti-cheat-warning.show {
            transform: translateY(0);
        }

        .saltel-logo {
            max-height: 80px;
            width: auto;
        }

        .footer-logo {
            max-height: 40px;
            width: auto;
        }
    </style>
</head>

<body class="font-sans bg-gray-50">
    <!-- Assignment Landing Page -->
    <div id="landingPage" class="flex flex-col items-center justify-center min-h-screen p-4">
        <div class="flex items-center justify-center mb-3">
            <img src="../../assets/images/logo.png" alt="Saltel Learning Platform" class="saltel-logo">
        </div>
        <div class="w-full max-w-2xl overflow-hidden bg-white shadow-xl rounded-2xl">
            <!-- Header with Saltel Logo -->

            <div class="p-8 text-white bg-blue-600">

                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <a href="assignments.php" class="p-2 mr-4 transition-colors rounded-lg hover:bg-white hover:bg-opacity-20">
                            <i class="text-xl fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold">Data Science Assignment</h1>
                            <!-- <p class="text-lg text-blue-100">Module 6: Machine Learning Basics</p> -->
                        </div>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="p-4 mb-4 bg-blue-700 bg-opacity-50 border border-blue-400 rounded-lg">
                    <div class="flex items-start">
                        <i class="mt-1 mr-3 text-yellow-300 fas fa-shield-alt"></i>
                        <div>
                            <h3 class="font-semibold text-yellow-300">Secure Assessment Environment</h3>
                            <p class="text-sm text-blue-100">This assessment is monitored for academic integrity. Copying, switching tabs, or leaving the window may result in automatic submission.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignment Details -->
            <div class="p-8">
                <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
                    <div class="p-4 text-center border-2 border-blue-50 rounded-xl">
                        <i class="mb-2 text-2xl text-blue-600 fas fa-clock"></i>
                        <div class="text-lg font-semibold text-gray-900">30 Minutes</div>
                        <div class="text-sm text-gray-600">Time Limit</div>
                    </div>
                    <div class="p-4 text-center border-2 border-green-50 rounded-xl">
                        <i class="mb-2 text-2xl text-green-600 fas fa-question-circle"></i>
                        <div class="text-lg font-semibold text-gray-900">15 Questions</div>
                        <div class="text-sm text-gray-600">Multiple Choice</div>
                    </div>
                    <div class="p-4 text-center border-2 border-purple-50 rounded-xl">
                        <i class="mb-2 text-2xl text-purple-600 fas fa-trophy"></i>
                        <div class="text-lg font-semibold text-gray-900">75% to Pass</div>
                        <div class="text-sm text-gray-600">Passing Score</div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="mb-8">
                    <h2 class="mb-4 text-xl font-semibold text-gray-900">Instructions</h2>
                    <div class="p-6 space-y-3 bg-gray-50 rounded-xl">
                        <div class="flex items-start">
                            <i class="mt-1 mr-3 text-green-500 fas fa-check-circle"></i>
                            <p class="text-gray-700">You have <strong>30 minutes</strong> to complete all 15 questions</p>
                        </div>
                        <div class="flex items-start">
                            <i class="mt-1 mr-3 text-green-500 fas fa-check-circle"></i>
                            <p class="text-gray-700">Each question has only <strong>one correct answer</strong></p>
                        </div>
                        <div class="flex items-start">
                            <i class="mt-1 mr-3 text-green-500 fas fa-check-circle"></i>
                            <p class="text-gray-700">You <strong>cannot go back</strong> to previous questions once answered</p>
                        </div>
                        <div class="flex items-start">
                            <i class="mt-1 mr-3 text-green-500 fas fa-check-circle"></i>
                            <p class="text-gray-700">The quiz will <strong>auto-submit</strong> when time runs out</p>
                        </div>
                        <div class="flex items-start">
                            <i class="mt-1 mr-3 text-yellow-500 fas fa-exclamation-triangle"></i>
                            <p class="text-gray-700">You will receive a <strong>5-minute warning</strong> before time expires</p>
                        </div>
                        <div class="flex items-start">
                            <i class="mt-1 mr-3 text-red-500 fas fa-shield-alt"></i>
                            <p class="text-gray-700">This is a <strong>secure assessment</strong> - copying text or leaving the window is not allowed</p>
                        </div>
                    </div>
                </div>

                <!-- Start Button -->
                <div class="text-center">
                    <button id="startAssignmentBtn" class="px-8 py-4 text-lg font-semibold text-white transition-colors bg-blue-600 shadow-lg rounded-xl hover:bg-blue-700">
                        <i class="mr-2 fas fa-play"></i>
                        Start Assignment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quiz Interface -->
    <div id="quizInterface" class="hidden min-h-screen bg-gray-50">
        <!-- Top Bar with Timer and Progress -->
        <div class="sticky top-0 z-10 bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-4xl px-6 py-4 mx-auto">
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
                        <div id="timerDisplay" class="flex items-center px-4 py-2 space-x-2 rounded-lg bg-blue-50">
                            <i class="text-blue-600 fas fa-clock"></i>
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
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div id="progressBar" class="h-2 bg-blue-600 rounded-full progress-bar" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Question Content -->
        <div class="max-w-4xl px-6 py-8 mx-auto">
            <div id="questionContainer" class="p-8 bg-white shadow-lg rounded-2xl question-fade-in">
                <!-- Question will be loaded here -->
            </div>

            <!-- Navigation -->
            <div class="flex items-center justify-between mt-8">
                <div></div> <!-- Empty div for spacing -->
                <button id="nextQuestionBtn" class="px-6 py-3 font-semibold text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Next Question
                    <i class="ml-2 fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Results Page -->
    <div id="resultsPage" class="hidden items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-2xl overflow-hidden bg-white shadow-xl rounded-2xl">
            <!-- Results Header -->
            <div id="resultsHeader" class="p-8 text-center text-white">
                <i id="resultsIcon" class="mb-4 text-6xl"></i>
                <h1 class="mb-2 text-3xl font-bold" id="resultsTitle">Quiz Completed!</h1>
                <p class="text-lg opacity-90" id="resultsSubtitle">Here are your results</p>
            </div>

            <!-- Results Content -->
            <div class="p-8">
                <!-- Score Display -->
                <div class="mb-8 text-center">
                    <div class="mb-2 text-6xl font-bold text-gray-900" id="finalScore">0%</div>
                    <div class="text-lg text-gray-600" id="scoreDescription">You answered 0 out of 15 questions correctly</div>
                </div>

                <!-- Performance Breakdown -->
                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="p-4 text-center bg-green-50 rounded-xl">
                        <div class="text-2xl font-bold text-green-600" id="correctAnswers">0</div>
                        <div class="text-sm text-gray-600">Correct</div>
                    </div>
                    <div class="p-4 text-center bg-red-50 rounded-xl">
                        <div class="text-2xl font-bold text-red-600" id="incorrectAnswers">0</div>
                        <div class="text-sm text-gray-600">Incorrect</div>
                    </div>
                    <div class="p-4 text-center bg-gray-50 rounded-xl">
                        <div class="text-2xl font-bold text-gray-600" id="timeSpent">00:00</div>
                        <div class="text-sm text-gray-600">Time Used</div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-4 sm:flex-row">
                    <button id="reviewAnswersBtn" class="flex-1 px-6 py-3 font-semibold text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                        <i class="mr-2 fas fa-eye"></i>
                        Review Answers
                    </button>
                    <button id="retakeAssignmentBtn" class="flex-1 px-6 py-3 font-semibold text-white transition-colors bg-gray-600 rounded-lg hover:bg-gray-700">
                        <i class="mr-2 fas fa-redo"></i>
                        Retake Quiz
                    </button>
                    <a href="assignments.php" class="flex-1 px-6 py-3 font-semibold text-center text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                        <i class="mr-2 fas fa-home"></i>
                        Back to Assignments
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Time Warning Modal -->
    <div id="timeWarningModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50">
        <div class="w-full max-w-md mx-4 bg-white shadow-2xl rounded-xl">
            <div class="p-6 text-center">
                <i class="mb-4 text-4xl text-yellow-500 fas fa-exclamation-triangle"></i>
                <h3 class="mb-2 text-xl font-semibold text-gray-900">Time Warning!</h3>
                <p class="mb-6 text-gray-600">You have <strong>5 minutes</strong> remaining to complete the quiz.</p>
                <button id="continueQuizBtn" class="px-6 py-2 font-semibold text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                    Continue Quiz
                </button>
            </div>
        </div>
    </div>

    <!-- Time Up Modal -->
    <div id="timeUpModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50">
        <div class="w-full max-w-md mx-4 bg-white shadow-2xl rounded-xl">
            <div class="p-6 text-center">
                <i class="mb-4 text-4xl text-red-500 fas fa-clock"></i>
                <h3 class="mb-2 text-xl font-semibold text-gray-900">Time's Up!</h3>
                <p class="mb-6 text-gray-600">Your quiz has been automatically submitted.</p>
                <button id="viewResultsBtn" class="px-6 py-2 font-semibold text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                    View Results
                </button>
            </div>
        </div>
    </div>

    <!-- Saltel Footer -->
    <footer class="py-6 mt-8 text-white bg-gray-800">
        <div class="container px-6 mx-auto">
            <div class="flex flex-col items-center justify-between md:flex-row">
                <div class="flex items-center mb-4 md:mb-0">
                    <img src="../../assets/images/logo.png" alt="Saltel Learning Platform" class="mr-3 footer-logo">
                    <div>
                        <h3 class="text-lg font-semibold">Saltel Learning Platform</h3>
                        <p class="text-sm text-gray-400">Empowering minds through quality education</p>
                    </div>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-sm text-gray-400">&copy; 2025 Saltel Learning Platform. All rights reserved.</p>
                    <p class="mt-1 text-xs text-gray-500">Secure Assessment Environment</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Anti-cheat Warning Banner -->
    <div id="antiCheatWarning" class="anti-cheat-warning">
        <i class="mr-2 fas fa-exclamation-triangle"></i>
        <span id="warningMessage">Warning: Suspicious activity detected!</span>
    </div>

    <script src="../../assets/js/assignment-quiz.js"></script>

    <!-- Anti-cheat JavaScript -->
    <script>
        class AntiCheatSystem {
            constructor() {
                this.violations = 0;
                this.maxViolations = 3;
                this.isQuizActive = false;
                this.warningTimeout = null;
                this.initializeAntiCheat();
            }

            initializeAntiCheat() {
                // Disable right-click context menu
                document.addEventListener('contextmenu', (e) => {
                    if (this.isQuizActive) {
                        e.preventDefault();
                        this.showWarning('Right-click is disabled during the assessment');
                    }
                });

                // Disable text selection
                document.body.classList.add('no-select');

                // Disable copy/paste keyboard shortcuts
                document.addEventListener('keydown', (e) => {
                    if (this.isQuizActive) {
                        // Disable Ctrl+C, Ctrl+V, Ctrl+A, Ctrl+X, Ctrl+S, F12, etc.
                        if ((e.ctrlKey || e.metaKey) && (
                                e.key === 'c' || e.key === 'v' || e.key === 'a' ||
                                e.key === 'x' || e.key === 's' || e.key === 'p'
                            )) {
                            e.preventDefault();
                            this.recordViolation('Copy/paste operations are not allowed');
                        }

                        // Disable F12 (Developer Tools)
                        if (e.key === 'F12') {
                            e.preventDefault();
                            this.recordViolation('Developer tools access is not allowed');
                        }

                        // Disable Ctrl+Shift+I (Developer Tools)
                        if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'I') {
                            e.preventDefault();
                            this.recordViolation('Developer tools access is not allowed');
                        }

                        // Disable Alt+Tab (minimize risk)
                        if (e.altKey && e.key === 'Tab') {
                            e.preventDefault();
                            this.recordViolation('Switching applications is not allowed');
                        }
                    }
                });

                // Monitor window focus/blur events
                window.addEventListener('blur', () => {
                    if (this.isQuizActive) {
                        this.recordViolation('You left the assessment window');
                    }
                });

                window.addEventListener('focus', () => {
                    if (this.isQuizActive && this.violations > 0) {
                        this.showWarning('Please keep the assessment window in focus');
                    }
                });

                // Monitor mouse leave events
                document.addEventListener('mouseleave', () => {
                    if (this.isQuizActive) {
                        this.recordViolation('Mouse cursor left the assessment area');
                    }
                });

                // Disable drag and drop
                document.addEventListener('dragstart', (e) => {
                    if (this.isQuizActive) {
                        e.preventDefault();
                        this.showWarning('Drag and drop is not allowed');
                    }
                });

                // Monitor visibility change (tab switching)
                document.addEventListener('visibilitychange', () => {
                    if (this.isQuizActive && document.hidden) {
                        this.recordViolation('Tab switching detected');
                    }
                });

                // Disable print
                window.addEventListener('beforeprint', (e) => {
                    if (this.isQuizActive) {
                        e.preventDefault();
                        this.recordViolation('Printing is not allowed during assessment');
                    }
                });
            }

            startMonitoring() {
                this.isQuizActive = true;
                this.showWarning('Anti-cheat monitoring is now active', 'info');

                // Request fullscreen (optional)
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen().catch(() => {
                        this.showWarning('For best security, please use fullscreen mode');
                    });
                }
            }

            stopMonitoring() {
                this.isQuizActive = false;
            }

            recordViolation(message) {
                this.violations++;
                console.warn(`Anti-cheat violation ${this.violations}/${this.maxViolations}: ${message}`);

                if (this.violations >= this.maxViolations) {
                    this.handleMaxViolations();
                } else {
                    this.showWarning(`${message} (Warning ${this.violations}/${this.maxViolations})`);
                }
            }

            handleMaxViolations() {
                this.showWarning('Maximum violations reached. Assessment will be submitted automatically.', 'error');

                // Auto-submit after 5 seconds
                setTimeout(() => {
                    if (typeof window.quizManager !== 'undefined' && window.quizManager.submitQuiz) {
                        window.quizManager.submitQuiz(true); // Force submit
                    } else {
                        alert('Assessment terminated due to security violations.');
                        window.location.href = 'assignments.php';
                    }
                }, 5000);
            }

            showWarning(message, type = 'warning') {
                const warningBanner = document.getElementById('antiCheatWarning');
                const warningMessage = document.getElementById('warningMessage');

                warningMessage.textContent = message;

                // Update styling based on type
                warningBanner.className = 'anti-cheat-warning show';
                if (type === 'error') {
                    warningBanner.style.background = 'linear-gradient(135deg, #dc2626, #991b1b)';
                } else if (type === 'info') {
                    warningBanner.style.background = 'linear-gradient(135deg, #2563eb, #1d4ed8)';
                } else {
                    warningBanner.style.background = 'linear-gradient(135deg, #dc2626, #b91c1c)';
                }

                // Clear existing timeout
                if (this.warningTimeout) {
                    clearTimeout(this.warningTimeout);
                }

                // Hide warning after delay (except for errors)
                if (type !== 'error') {
                    this.warningTimeout = setTimeout(() => {
                        warningBanner.classList.remove('show');
                    }, type === 'info' ? 3000 : 5000);
                }
            }

            getViolationCount() {
                return this.violations;
            }
        }

        // Initialize anti-cheat system
        const antiCheat = new AntiCheatSystem();

        // Make it globally accessible
        window.antiCheat = antiCheat;

        // Start monitoring when quiz begins
        document.addEventListener('DOMContentLoaded', () => {
            const startBtn = document.getElementById('startAssignmentBtn');
            if (startBtn) {
                startBtn.addEventListener('click', () => {
                    setTimeout(() => {
                        antiCheat.startMonitoring();
                    }, 1000);
                });
            }
        });
    </script>
</body>

</html>