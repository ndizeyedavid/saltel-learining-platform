// Course Viewer Functionality
document.addEventListener("DOMContentLoaded", function () {
    // Initialize course viewer
    initializeCourseViewer();
    initializeVideoPlayer();
    initializeQuizzes();
    initializeNavigation();
    initializeNotes();
    loadCourseData();

    // Course data structure
    const courseData = {
        "data-science": {
            title: "Data Science & Analytics",
            progress: 65,
            completedLessons: 31,
            totalLessons: 48,
            totalModules: 8,
            completedModules: 5,
            timeRemaining: "5 weeks",
            currentModule: "Module 6",
            currentLesson: "Lesson 3",
            modules: [
                {
                    id: 1,
                    title: "Introduction to Data Science",
                    lessons: 6,
                    completed: true,
                    progress: 100
                },
                {
                    id: 2,
                    title: "Python Fundamentals",
                    lessons: 8,
                    completed: true,
                    progress: 100
                },
                {
                    id: 3,
                    title: "Data Manipulation with Pandas",
                    lessons: 7,
                    completed: true,
                    progress: 100
                },
                {
                    id: 4,
                    title: "Data Visualization",
                    lessons: 5,
                    completed: true,
                    progress: 100
                },
                {
                    id: 5,
                    title: "Statistical Analysis",
                    lessons: 6,
                    completed: true,
                    progress: 100
                },
                {
                    id: 6,
                    title: "Machine Learning Basics",
                    lessons: 8,
                    completed: false,
                    progress: 38,
                    current: true
                },
                {
                    id: 7,
                    title: "Advanced ML Algorithms",
                    lessons: 5,
                    completed: false,
                    progress: 0
                },
                {
                    id: 8,
                    title: "Final Project",
                    lessons: 3,
                    completed: false,
                    progress: 0
                }
            ]
        },
        "web-development": {
            title: "React Development Mastery",
            progress: 100,
            completedLessons: 36,
            totalLessons: 36,
            totalModules: 6,
            completedModules: 6,
            timeRemaining: "Completed",
            currentModule: "Course Complete",
            currentLesson: "Review",
            modules: [
                {
                    id: 1,
                    title: "React Fundamentals",
                    lessons: 8,
                    completed: true,
                    progress: 100
                },
                {
                    id: 2,
                    title: "Components & Props",
                    lessons: 6,
                    completed: true,
                    progress: 100
                },
                {
                    id: 3,
                    title: "State Management",
                    lessons: 7,
                    completed: true,
                    progress: 100
                },
                {
                    id: 4,
                    title: "Hooks & Context",
                    lessons: 5,
                    completed: true,
                    progress: 100
                },
                {
                    id: 5,
                    title: "Redux Integration",
                    lessons: 6,
                    completed: true,
                    progress: 100
                },
                {
                    id: 6,
                    title: "Final Project",
                    lessons: 4,
                    completed: true,
                    progress: 100
                }
            ]
        }
    };

    function initializeCourseViewer() {
        // Get course ID from URL parameters or default
        const urlParams = new URLSearchParams(window.location.search);
        const courseId = urlParams.get('course') || 'data-science';
        
        // Load course data
        loadCourseContent(courseId);
    }

    function loadCourseData() {
        // Load modules into sidebar
        renderModules();
    }

    function loadCourseContent(courseId) {
        const course = courseData[courseId];
        if (!course) return;

        // Update course header
        document.getElementById('courseTitle').textContent = course.title;
        document.getElementById('overallProgress').textContent = `${course.progress}%`;
        document.getElementById('progressBar').style.width = `${course.progress}%`;
        document.getElementById('completedLessons').textContent = `${course.completedLessons} of ${course.totalLessons} lessons`;
        document.getElementById('timeRemaining').textContent = course.timeRemaining;
        document.getElementById('totalModules').textContent = course.totalModules;
        document.getElementById('completedModules').textContent = course.completedModules;
        document.getElementById('currentModule').textContent = course.currentModule;
        document.getElementById('currentLesson').textContent = course.currentLesson;

        // Update progress bar color based on completion
        const progressBar = document.getElementById('progressBar');
        if (course.progress === 100) {
            progressBar.classList.remove('bg-blue-600');
            progressBar.classList.add('bg-green-600');
        }

        // Load modules
        renderModules(course.modules);
    }

    function renderModules(modules = courseData['data-science'].modules) {
        const moduleList = document.getElementById('moduleList');
        moduleList.innerHTML = '';

        modules.forEach((module, index) => {
            const moduleElement = document.createElement('div');
            moduleElement.className = `module-item ${module.current ? 'current' : ''} ${module.completed ? 'completed' : ''}`;
            
            moduleElement.innerHTML = `
                <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors" data-module="${module.id}">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            ${module.completed 
                                ? '<i class="fas fa-check-circle text-green-500"></i>'
                                : module.current 
                                    ? '<i class="fas fa-play-circle text-blue-500"></i>'
                                    : '<i class="far fa-circle text-gray-400"></i>'
                            }
                        </div>
                        <div>
                            <div class="font-medium text-sm text-gray-900">${module.title}</div>
                            <div class="text-xs text-gray-500">${module.lessons} lessons</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-medium ${module.completed ? 'text-green-600' : module.current ? 'text-blue-600' : 'text-gray-500'}">
                            ${module.progress}%
                        </div>
                        ${!module.completed && module.progress > 0 ? `
                            <div class="w-12 bg-gray-200 rounded-full h-1 mt-1">
                                <div class="bg-blue-600 h-1 rounded-full" style="width: ${module.progress}%"></div>
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;

            moduleList.appendChild(moduleElement);
        });

        // Add click handlers for modules
        moduleList.addEventListener('click', function(e) {
            const moduleItem = e.target.closest('[data-module]');
            if (moduleItem) {
                const moduleId = moduleItem.dataset.module;
                loadModule(moduleId);
            }
        });
    }

    function loadModule(moduleId) {
        // Update current module styling
        document.querySelectorAll('.module-item').forEach(item => {
            item.classList.remove('current');
        });
        document.querySelector(`[data-module="${moduleId}"]`).closest('.module-item').classList.add('current');

        // Update lesson content based on module
        updateLessonContent(moduleId);
    }

    function updateLessonContent(moduleId) {
        const lessonTitles = {
            1: "Introduction to Data Science",
            2: "Python Environment Setup",
            3: "Working with DataFrames",
            4: "Creating Visualizations",
            5: "Statistical Methods",
            6: "Machine Learning Algorithms",
            7: "Deep Learning Basics",
            8: "Project Planning"
        };

        const lessonDescriptions = {
            1: "Understanding the fundamentals of data science and its applications",
            2: "Setting up Python environment and essential libraries",
            3: "Learn to manipulate data using Pandas DataFrames",
            4: "Create compelling visualizations with Matplotlib and Seaborn",
            5: "Apply statistical methods to analyze data patterns",
            6: "Learn about supervised and unsupervised learning techniques",
            7: "Introduction to neural networks and deep learning",
            8: "Plan and execute your final capstone project"
        };

        document.getElementById('lessonTitle').textContent = lessonTitles[moduleId] || "Machine Learning Algorithms";
        document.getElementById('lessonDescription').textContent = lessonDescriptions[moduleId] || "Learn about supervised and unsupervised learning techniques";
    }

    function initializeVideoPlayer() {
        const video = document.getElementById('lessonVideo');
        const playButton = document.getElementById('playButton');
        const videoOverlay = document.getElementById('videoOverlay');

        playButton.addEventListener('click', function() {
            video.play();
            videoOverlay.style.display = 'none';
        });

        video.addEventListener('play', function() {
            videoOverlay.style.display = 'none';
        });

        video.addEventListener('pause', function() {
            if (video.currentTime === 0) {
                videoOverlay.style.display = 'flex';
            }
        });

        video.addEventListener('ended', function() {
            videoOverlay.style.display = 'flex';
            playButton.innerHTML = '<i class="fas fa-redo text-white text-2xl"></i>';
        });
    }

    function initializeQuizzes() {
        const submitButton = document.getElementById('submitQuiz');
        
        submitButton.addEventListener('click', function() {
            const selectedAnswer = document.querySelector('input[name="quiz1"]:checked');
            
            if (!selectedAnswer) {
                alert('Please select an answer before submitting.');
                return;
            }

            const isCorrect = selectedAnswer.value === 'supervised';
            
            // Show feedback
            const quizSection = document.getElementById('quizSection');
            const feedbackDiv = document.createElement('div');
            feedbackDiv.className = `mt-4 p-4 rounded-lg ${isCorrect ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'}`;
            
            feedbackDiv.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${isCorrect ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500'} mr-2"></i>
                    <span class="font-medium ${isCorrect ? 'text-green-800' : 'text-red-800'}">
                        ${isCorrect ? 'Correct!' : 'Incorrect.'}
                    </span>
                </div>
                <p class="mt-2 text-sm ${isCorrect ? 'text-green-700' : 'text-red-700'}">
                    ${isCorrect 
                        ? 'Supervised learning uses labeled data to train models.' 
                        : 'The correct answer is Supervised Learning. It uses labeled data for training.'
                    }
                </p>
            `;

            // Remove existing feedback
            const existingFeedback = quizSection.querySelector('.mt-4.p-4.rounded-lg');
            if (existingFeedback) {
                existingFeedback.remove();
            }

            quizSection.appendChild(feedbackDiv);
            submitButton.disabled = true;
            submitButton.textContent = 'Submitted';
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
        });
    }

    function initializeNavigation() {
        const backButton = document.getElementById('backToCourses');
        const prevButton = document.getElementById('prevLesson');
        const nextButton = document.getElementById('nextLesson');
        const markCompleteButton = document.getElementById('markComplete');

        backButton.addEventListener('click', function() {
            window.location.href = 'courses.php';
        });

        prevButton.addEventListener('click', function() {
            // Navigate to previous lesson
            showNotification('Loading previous lesson...', 'info');
        });

        nextButton.addEventListener('click', function() {
            // Navigate to next lesson
            showNotification('Loading next lesson...', 'info');
        });

        markCompleteButton.addEventListener('click', function() {
            // Mark lesson as complete
            markCompleteButton.innerHTML = '<i class="fas fa-check mr-2"></i>Completed';
            markCompleteButton.classList.remove('text-green-600', 'hover:text-green-700', 'border-green-200');
            markCompleteButton.classList.add('text-green-700', 'bg-green-50', 'border-green-300');
            markCompleteButton.disabled = true;
            
            showNotification('Lesson marked as complete!', 'success');
            
            // Update progress
            updateProgress();
        });
    }

    function initializeNotes() {
        const takeNotesButton = document.getElementById('takeNotes');
        const notesModal = document.getElementById('notesModal');
        const closeNotesButton = document.getElementById('closeNotes');
        const cancelNotesButton = document.getElementById('cancelNotes');
        const saveNotesButton = document.getElementById('saveNotes');
        const notesTextarea = document.getElementById('notesTextarea');

        takeNotesButton.addEventListener('click', function() {
            notesModal.classList.remove('hidden');
            notesModal.classList.add('flex');
            notesTextarea.focus();
        });

        closeNotesButton.addEventListener('click', closeNotesModal);
        cancelNotesButton.addEventListener('click', closeNotesModal);

        function closeNotesModal() {
            notesModal.classList.add('hidden');
            notesModal.classList.remove('flex');
        }

        saveNotesButton.addEventListener('click', function() {
            const notes = notesTextarea.value.trim();
            if (notes) {
                // Save notes (in real app, this would save to database)
                localStorage.setItem('lesson-notes', notes);
                showNotification('Notes saved successfully!', 'success');
                closeNotesModal();
            }
        });

        // Load existing notes
        const savedNotes = localStorage.getItem('lesson-notes');
        if (savedNotes) {
            notesTextarea.value = savedNotes;
        }

        // Close modal when clicking outside
        notesModal.addEventListener('click', function(e) {
            if (e.target === notesModal) {
                closeNotesModal();
            }
        });
    }

    function updateProgress() {
        // Simulate progress update
        const currentProgress = parseInt(document.getElementById('overallProgress').textContent);
        const newProgress = Math.min(currentProgress + 2, 100);
        
        document.getElementById('overallProgress').textContent = `${newProgress}%`;
        document.getElementById('progressBar').style.width = `${newProgress}%`;
        
        // Update completed lessons
        const completedLessons = document.getElementById('completedLessons');
        const currentCount = parseInt(completedLessons.textContent.split(' ')[0]);
        completedLessons.textContent = `${currentCount + 1} of 48 lessons`;
    }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
        
        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            info: 'bg-blue-500 text-white',
            warning: 'bg-yellow-500 text-black'
        };
        
        notification.classList.add(...colors[type].split(' '));
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Animate out and remove
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Bookmark functionality
    document.getElementById('bookmarkLesson').addEventListener('click', function() {
        const icon = this.querySelector('i');
        if (icon.classList.contains('far')) {
            icon.classList.remove('far');
            icon.classList.add('fas');
            this.classList.add('text-blue-600');
            showNotification('Lesson bookmarked!', 'success');
        } else {
            icon.classList.remove('fas');
            icon.classList.add('far');
            this.classList.remove('text-blue-600');
            showNotification('Bookmark removed', 'info');
        }
    });

    // Share functionality
    document.getElementById('shareLesson').addEventListener('click', function() {
        if (navigator.share) {
            navigator.share({
                title: 'Machine Learning Algorithms - Data Science Course',
                text: 'Check out this lesson on machine learning algorithms!',
                url: window.location.href
            });
        } else {
            // Fallback to clipboard
            navigator.clipboard.writeText(window.location.href).then(() => {
                showNotification('Lesson link copied to clipboard!', 'success');
            });
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    document.getElementById('prevLesson').click();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    document.getElementById('nextLesson').click();
                    break;
                case 'Enter':
                    e.preventDefault();
                    document.getElementById('markComplete').click();
                    break;
            }
        }
    });

    // Auto-save progress periodically
    setInterval(function() {
        // In a real app, this would save progress to the server
        const progress = {
            courseId: 'data-science',
            lessonId: 'ml-algorithms',
            timestamp: new Date().toISOString(),
            videoProgress: document.getElementById('lessonVideo').currentTime || 0
        };
        localStorage.setItem('course-progress', JSON.stringify(progress));
    }, 30000); // Save every 30 seconds
});
