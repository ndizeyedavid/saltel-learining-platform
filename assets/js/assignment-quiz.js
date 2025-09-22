// Assignment Quiz JavaScript
class AssignmentQuiz {
    constructor() {
        this.questions = [
            {
                id: 1,
                question: "What is the primary goal of supervised learning?",
                options: [
                    "To find hidden patterns in unlabeled data",
                    "To learn from labeled training data to make predictions",
                    "To optimize reward functions through trial and error",
                    "To reduce the dimensionality of datasets"
                ],
                correctAnswer: 1,
                explanation: "Supervised learning uses labeled training data to learn patterns and make predictions on new, unseen data."
            },
            {
                id: 2,
                question: "Which algorithm is commonly used for classification tasks?",
                options: [
                    "K-means clustering",
                    "Principal Component Analysis (PCA)",
                    "Random Forest",
                    "DBSCAN"
                ],
                correctAnswer: 2,
                explanation: "Random Forest is a popular ensemble method used for both classification and regression tasks."
            },
            {
                id: 3,
                question: "What does overfitting mean in machine learning?",
                options: [
                    "The model performs well on both training and test data",
                    "The model performs poorly on training data",
                    "The model memorizes training data but fails to generalize",
                    "The model has too few parameters"
                ],
                correctAnswer: 2,
                explanation: "Overfitting occurs when a model learns the training data too well, including noise, making it perform poorly on new data."
            },
            {
                id: 4,
                question: "Which metric is best for evaluating a binary classification model with imbalanced classes?",
                options: [
                    "Accuracy",
                    "F1-score",
                    "Mean Squared Error",
                    "R-squared"
                ],
                correctAnswer: 1,
                explanation: "F1-score balances precision and recall, making it ideal for imbalanced datasets where accuracy can be misleading."
            },
            {
                id: 5,
                question: "What is the purpose of cross-validation?",
                options: [
                    "To increase the size of the training dataset",
                    "To evaluate model performance and reduce overfitting",
                    "To clean the data before training",
                    "To select the best features"
                ],
                correctAnswer: 1,
                explanation: "Cross-validation helps assess how well a model will generalize to unseen data by testing it on multiple data splits."
            },
            {
                id: 6,
                question: "Which type of neural network is best suited for image recognition?",
                options: [
                    "Recurrent Neural Network (RNN)",
                    "Convolutional Neural Network (CNN)",
                    "Long Short-Term Memory (LSTM)",
                    "Multilayer Perceptron (MLP)"
                ],
                correctAnswer: 1,
                explanation: "CNNs are specifically designed to process grid-like data such as images, using convolution operations to detect features."
            },
            {
                id: 7,
                question: "What is the main advantage of ensemble methods?",
                options: [
                    "They require less computational power",
                    "They combine multiple models to improve performance",
                    "They work only with linear relationships",
                    "They eliminate the need for feature selection"
                ],
                correctAnswer: 1,
                explanation: "Ensemble methods combine predictions from multiple models, often resulting in better performance than individual models."
            },
            {
                id: 8,
                question: "Which preprocessing step is essential for algorithms sensitive to feature scales?",
                options: [
                    "Feature normalization/standardization",
                    "Adding polynomial features",
                    "Removing all categorical variables",
                    "Increasing the learning rate"
                ],
                correctAnswer: 0,
                explanation: "Normalization or standardization ensures all features have similar scales, which is crucial for algorithms like SVM and neural networks."
            },
            {
                id: 9,
                question: "What is the bias-variance tradeoff?",
                options: [
                    "The balance between model complexity and interpretability",
                    "The balance between training time and accuracy",
                    "The balance between underfitting and overfitting",
                    "The balance between precision and recall"
                ],
                correctAnswer: 2,
                explanation: "The bias-variance tradeoff describes the balance between a model's ability to fit training data (bias) and its sensitivity to changes in training data (variance)."
            },
            {
                id: 10,
                question: "Which algorithm is used for dimensionality reduction?",
                options: [
                    "Linear Regression",
                    "Decision Tree",
                    "Principal Component Analysis (PCA)",
                    "K-Nearest Neighbors"
                ],
                correctAnswer: 2,
                explanation: "PCA reduces the dimensionality of data while preserving as much variance as possible, making it useful for visualization and noise reduction."
            },
            {
                id: 11,
                question: "What is the purpose of regularization in machine learning?",
                options: [
                    "To increase model complexity",
                    "To prevent overfitting by adding penalty terms",
                    "To speed up training time",
                    "To improve data quality"
                ],
                correctAnswer: 1,
                explanation: "Regularization adds penalty terms to the loss function to prevent the model from becoming too complex and overfitting."
            },
            {
                id: 12,
                question: "Which metric measures the proportion of actual positive cases correctly identified?",
                options: [
                    "Precision",
                    "Recall (Sensitivity)",
                    "Specificity",
                    "F1-score"
                ],
                correctAnswer: 1,
                explanation: "Recall (or Sensitivity) measures the proportion of actual positive cases that were correctly identified by the model."
            },
            {
                id: 13,
                question: "What is gradient descent used for?",
                options: [
                    "Feature selection",
                    "Data preprocessing",
                    "Optimizing model parameters",
                    "Model evaluation"
                ],
                correctAnswer: 2,
                explanation: "Gradient descent is an optimization algorithm used to minimize the loss function by iteratively adjusting model parameters."
            },
            {
                id: 14,
                question: "Which type of learning does not require labeled data?",
                options: [
                    "Supervised learning",
                    "Unsupervised learning",
                    "Semi-supervised learning",
                    "Reinforcement learning"
                ],
                correctAnswer: 1,
                explanation: "Unsupervised learning finds patterns in data without using labeled examples, such as clustering or dimensionality reduction."
            },
            {
                id: 15,
                question: "What is the main purpose of feature engineering?",
                options: [
                    "To reduce the dataset size",
                    "To create or modify features to improve model performance",
                    "To visualize the data",
                    "To split the data into training and testing sets"
                ],
                correctAnswer: 1,
                explanation: "Feature engineering involves creating, transforming, or selecting features to help machine learning algorithms perform better."
            }
        ];

        this.currentQuestionIndex = 0;
        this.userAnswers = [];
        this.timeLimit = 30 * 60; // 30 minutes in seconds
        this.timeRemaining = this.timeLimit;
        this.timerInterval = null;
        this.startTime = null;
        this.warningShown = false;
        this.quizStarted = false;

        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // Start assignment button
        document.getElementById('startAssignmentBtn').addEventListener('click', () => {
            this.startQuiz();
        });

        // Next question button
        document.getElementById('nextQuestionBtn').addEventListener('click', () => {
            this.nextQuestion();
        });

        // Continue quiz button (warning modal)
        document.getElementById('continueQuizBtn').addEventListener('click', () => {
            this.closeTimeWarningModal();
        });

        // View results button (time up modal)
        document.getElementById('viewResultsBtn').addEventListener('click', () => {
            this.closeTimeUpModal();
        });

        // Retake assignment button
        document.getElementById('retakeAssignmentBtn').addEventListener('click', () => {
            this.retakeQuiz();
        });

        // Review answers button
        document.getElementById('reviewAnswersBtn').addEventListener('click', () => {
            this.reviewAnswers();
        });

        // Prevent page refresh/close during quiz
        window.addEventListener('beforeunload', (e) => {
            if (this.quizStarted && this.currentQuestionIndex < this.questions.length) {
                e.preventDefault();
                e.returnValue = 'Are you sure you want to leave? Your progress will be lost.';
                return e.returnValue;
            }
        });

        // Handle visibility change (tab switching)
        document.addEventListener('visibilitychange', () => {
            if (this.quizStarted && document.hidden) {
                console.log('User switched tabs during quiz');
                // Could implement additional security measures here
            }
        });
    }

    startQuiz() {
        this.quizStarted = true;
        this.startTime = new Date();
        
        // Hide landing page and show quiz interface
        document.getElementById('landingPage').classList.add('hidden');
        document.getElementById('quizInterface').classList.remove('hidden');

        // Start timer
        this.startTimer();

        // Load first question
        this.loadQuestion();
    }

    startTimer() {
        this.timerInterval = setInterval(() => {
            this.timeRemaining--;
            this.updateTimerDisplay();

            // Show warning at 5 minutes
            if (this.timeRemaining === 5 * 60 && !this.warningShown) {
                this.showTimeWarning();
                this.warningShown = true;
            }

            // Auto-submit when time is up
            if (this.timeRemaining <= 0) {
                this.timeUp();
            }
        }, 1000);
    }

    updateTimerDisplay() {
        const minutes = Math.floor(this.timeRemaining / 60);
        const seconds = this.timeRemaining % 60;
        const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        const timerElement = document.getElementById('timeRemaining');
        const timerDisplay = document.getElementById('timerDisplay');
        
        timerElement.textContent = timeString;

        // Add warning styling when less than 5 minutes
        if (this.timeRemaining <= 5 * 60) {
            timerDisplay.classList.remove('bg-blue-50');
            timerDisplay.classList.add('bg-red-50', 'timer-warning');
            timerElement.classList.remove('text-blue-600');
            timerElement.classList.add('text-red-600');
        }
    }

    loadQuestion() {
        const question = this.questions[this.currentQuestionIndex];
        const questionContainer = document.getElementById('questionContainer');

        // Update question counter and progress
        document.getElementById('currentQuestionNum').textContent = this.currentQuestionIndex + 1;
        this.updateProgress();

        // Create question HTML
        questionContainer.innerHTML = `
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                    Question ${this.currentQuestionIndex + 1}
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed">
                    ${question.question}
                </p>
            </div>

            <div class="space-y-3">
                ${question.options.map((option, index) => `
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all option-label" data-option="${index}">
                        <input type="radio" name="question_${question.id}" value="${index}" class="sr-only option-input">
                        <div class="flex items-center justify-center w-6 h-6 border-2 border-gray-300 rounded-full mr-4 option-circle">
                            <div class="w-3 h-3 bg-blue-600 rounded-full hidden option-dot"></div>
                        </div>
                        <span class="text-gray-700 flex-1">${option}</span>
                    </label>
                `).join('')}
            </div>
        `;

        // Add click handlers for options
        const optionLabels = questionContainer.querySelectorAll('.option-label');
        optionLabels.forEach((label, index) => {
            label.addEventListener('click', () => {
                this.selectOption(index);
            });
        });

        // Reset next button
        document.getElementById('nextQuestionBtn').disabled = true;

        // Add fade-in animation
        questionContainer.classList.remove('question-fade-in');
        setTimeout(() => {
            questionContainer.classList.add('question-fade-in');
        }, 10);
    }

    selectOption(selectedIndex) {
        const questionContainer = document.getElementById('questionContainer');
        const optionLabels = questionContainer.querySelectorAll('.option-label');
        const optionCircles = questionContainer.querySelectorAll('.option-circle');
        const optionDots = questionContainer.querySelectorAll('.option-dot');

        // Clear previous selections
        optionLabels.forEach((label, index) => {
            label.classList.remove('border-blue-500', 'bg-blue-50');
            label.classList.add('border-gray-200');
            optionCircles[index].classList.remove('border-blue-500');
            optionCircles[index].classList.add('border-gray-300');
            optionDots[index].classList.add('hidden');
        });

        // Highlight selected option
        const selectedLabel = optionLabels[selectedIndex];
        const selectedCircle = optionCircles[selectedIndex];
        const selectedDot = optionDots[selectedIndex];

        selectedLabel.classList.remove('border-gray-200');
        selectedLabel.classList.add('border-blue-500', 'bg-blue-50');
        selectedCircle.classList.remove('border-gray-300');
        selectedCircle.classList.add('border-blue-500');
        selectedDot.classList.remove('hidden');

        // Store answer
        this.userAnswers[this.currentQuestionIndex] = selectedIndex;

        // Enable next button
        document.getElementById('nextQuestionBtn').disabled = false;

        // Update button text for last question
        const nextBtn = document.getElementById('nextQuestionBtn');
        if (this.currentQuestionIndex === this.questions.length - 1) {
            nextBtn.innerHTML = 'Submit Quiz <i class="fas fa-check ml-2"></i>';
        } else {
            nextBtn.innerHTML = 'Next Question <i class="fas fa-arrow-right ml-2"></i>';
        }
    }

    nextQuestion() {
        // Check if this is the last question
        if (this.currentQuestionIndex === this.questions.length - 1) {
            this.submitQuiz();
            return;
        }

        // Move to next question
        this.currentQuestionIndex++;
        this.loadQuestion();
    }

    updateProgress() {
        const progress = ((this.currentQuestionIndex + 1) / this.questions.length) * 100;
        document.getElementById('progressBar').style.width = `${progress}%`;
        document.getElementById('progressPercent').textContent = `${Math.round(progress)}%`;
    }

    showTimeWarning() {
        document.getElementById('timeWarningModal').classList.remove('hidden');
        document.getElementById('timeWarningModal').classList.add('flex');
    }

    closeTimeWarningModal() {
        document.getElementById('timeWarningModal').classList.add('hidden');
        document.getElementById('timeWarningModal').classList.remove('flex');
    }

    timeUp() {
        clearInterval(this.timerInterval);
        this.quizStarted = false;
        
        // Fill remaining answers as unanswered
        for (let i = this.userAnswers.length; i < this.questions.length; i++) {
            this.userAnswers[i] = -1; // -1 indicates no answer
        }

        document.getElementById('timeUpModal').classList.remove('hidden');
        document.getElementById('timeUpModal').classList.add('flex');

        setTimeout(() => {
            this.closeTimeUpModal();
        }, 3000);
    }

    closeTimeUpModal() {
        document.getElementById('timeUpModal').classList.add('hidden');
        document.getElementById('timeUpModal').classList.remove('flex');
        this.showResults();
    }

    submitQuiz() {
        clearInterval(this.timerInterval);
        this.quizStarted = false;
        this.showResults();
    }

    showResults() {
        // Calculate results
        let correctCount = 0;
        let incorrectCount = 0;

        this.userAnswers.forEach((answer, index) => {
            if (answer === this.questions[index].correctAnswer) {
                correctCount++;
            } else {
                incorrectCount++;
            }
        });

        const score = Math.round((correctCount / this.questions.length) * 100);
        const timeSpent = this.timeLimit - this.timeRemaining;
        const timeSpentMinutes = Math.floor(timeSpent / 60);
        const timeSpentSeconds = timeSpent % 60;

        // Update results display
        document.getElementById('finalScore').textContent = `${score}%`;
        document.getElementById('scoreDescription').textContent = 
            `You answered ${correctCount} out of ${this.questions.length} questions correctly`;
        document.getElementById('correctAnswers').textContent = correctCount;
        document.getElementById('incorrectAnswers').textContent = incorrectCount;
        document.getElementById('timeSpent').textContent = 
            `${timeSpentMinutes.toString().padStart(2, '0')}:${timeSpentSeconds.toString().padStart(2, '0')}`;

        // Update results header based on performance
        const resultsHeader = document.getElementById('resultsHeader');
        const resultsIcon = document.getElementById('resultsIcon');
        const resultsTitle = document.getElementById('resultsTitle');
        const resultsSubtitle = document.getElementById('resultsSubtitle');

        if (score >= 75) {
            resultsHeader.className = 'p-8 text-white text-center bg-gradient-to-r from-green-500 to-green-600';
            resultsIcon.className = 'fas fa-trophy text-6xl mb-4';
            resultsTitle.textContent = 'Congratulations!';
            resultsSubtitle.textContent = 'You passed the quiz!';
        } else if (score >= 50) {
            resultsHeader.className = 'p-8 text-white text-center bg-gradient-to-r from-yellow-500 to-yellow-600';
            resultsIcon.className = 'fas fa-medal text-6xl mb-4';
            resultsTitle.textContent = 'Good Effort!';
            resultsSubtitle.textContent = 'You can do better with more practice';
        } else {
            resultsHeader.className = 'p-8 text-white text-center bg-gradient-to-r from-red-500 to-red-600';
            resultsIcon.className = 'fas fa-times-circle text-6xl mb-4';
            resultsTitle.textContent = 'Keep Learning!';
            resultsSubtitle.textContent = 'Review the material and try again';
        }

        // Show results page
        document.getElementById('quizInterface').classList.add('hidden');
        document.getElementById('resultsPage').classList.remove('hidden');
    }

    retakeQuiz() {
        // Reset quiz state
        this.currentQuestionIndex = 0;
        this.userAnswers = [];
        this.timeRemaining = this.timeLimit;
        this.warningShown = false;
        this.quizStarted = false;

        // Reset UI
        document.getElementById('resultsPage').classList.add('hidden');
        document.getElementById('landingPage').classList.remove('hidden');

        // Reset timer display
        const timerDisplay = document.getElementById('timerDisplay');
        const timerElement = document.getElementById('timeRemaining');
        timerDisplay.classList.remove('bg-red-50', 'timer-warning');
        timerDisplay.classList.add('bg-blue-50');
        timerElement.classList.remove('text-red-600');
        timerElement.classList.add('text-blue-600');
        timerElement.textContent = '30:00';
    }

    reviewAnswers() {
        // Create review interface
        const reviewContainer = document.createElement('div');
        reviewContainer.id = 'reviewInterface';
        reviewContainer.className = 'min-h-screen bg-gray-50 p-6';

        let reviewHTML = `
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">Answer Review</h1>
                        <button id="backToResultsBtn" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Results
                        </button>
                    </div>
                </div>
        `;

        this.questions.forEach((question, index) => {
            const userAnswer = this.userAnswers[index];
            const isCorrect = userAnswer === question.correctAnswer;
            const wasAnswered = userAnswer !== -1;

            reviewHTML += `
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                    <div class="flex items-start justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Question ${index + 1}</h2>
                        <div class="flex items-center">
                            ${isCorrect ? 
                                '<i class="fas fa-check-circle text-green-500 text-xl"></i>' : 
                                '<i class="fas fa-times-circle text-red-500 text-xl"></i>'
                            }
                        </div>
                    </div>
                    
                    <p class="text-lg text-gray-700 mb-6">${question.question}</p>
                    
                    <div class="space-y-3 mb-6">
                        ${question.options.map((option, optionIndex) => {
                            let classes = 'flex items-center p-4 border-2 rounded-xl';
                            let icon = '';
                            
                            if (optionIndex === question.correctAnswer) {
                                classes += ' border-green-500 bg-green-50';
                                icon = '<i class="fas fa-check text-green-600 mr-3"></i>';
                            } else if (optionIndex === userAnswer && userAnswer !== question.correctAnswer) {
                                classes += ' border-red-500 bg-red-50';
                                icon = '<i class="fas fa-times text-red-600 mr-3"></i>';
                            } else {
                                classes += ' border-gray-200';
                            }
                            
                            return `
                                <div class="${classes}">
                                    ${icon}
                                    <span class="text-gray-700">${option}</span>
                                </div>
                            `;
                        }).join('')}
                    </div>
                    
                    ${!wasAnswered ? 
                        '<div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg mb-4"><p class="text-yellow-800"><i class="fas fa-exclamation-triangle mr-2"></i>No answer provided</p></div>' : 
                        ''
                    }
                    
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-blue-800"><strong>Explanation:</strong> ${question.explanation}</p>
                    </div>
                </div>
            `;
        });

        reviewHTML += '</div>';
        reviewContainer.innerHTML = reviewHTML;

        // Replace results page with review
        document.getElementById('resultsPage').classList.add('hidden');
        document.body.appendChild(reviewContainer);

        // Add back button functionality
        document.getElementById('backToResultsBtn').addEventListener('click', () => {
            document.body.removeChild(reviewContainer);
            document.getElementById('resultsPage').classList.remove('hidden');
        });
    }
}

// Initialize quiz when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new AssignmentQuiz();
});
