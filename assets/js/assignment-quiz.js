// Assignment Quiz JavaScript
class AssignmentQuiz {
  constructor() {
    this.assignmentId = this.getAssignmentIdFromUrl();
    this.assignmentData = null;
    this.questions = [];

    this.currentQuestionIndex = 0;
    this.userAnswers = [];
    this.timeLimit = 30 * 60; // 30 minutes in seconds
    this.timeRemaining = this.timeLimit;
    this.timerInterval = null;
    this.startTime = null;
    this.warningShown = false;
    this.quizStarted = false;

    this.initializeEventListeners();
    this.loadAssignmentData();
  }

  getAssignmentIdFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get("id");
  }

  async loadAssignmentData() {
    if (!this.assignmentId) {
      this.showError("Assignment ID not found in URL");
      return;
    }

    try {
      const response = await fetch(
        `../api/assignments/get-assignment.php?id=${this.assignmentId}`
      );
      const data = await response.json();
      //   console.log(data);
      if (data.success) {
        this.assignmentData = data.assignment;
        this.questions = data.questions;
        this.timeLimit = parseInt(this.assignmentData.time_limit) * 60; // Convert minutes to seconds
        this.timeRemaining = this.timeLimit;

        // Update UI with assignment data
        this.updateAssignmentInfo();
        this.updateTimerDisplay();
      } else {
        this.showError(data.message || "Failed to load assignment data");
      }
    } catch (error) {
      console.error("Error loading assignment:", error);
      this.showError("Failed to load assignment. Please try again.");
    }
  }

  updateAssignmentInfo() {
    // Update assignment title and description
    const titleElement = document.getElementById("assignmentTitle");
    const descriptionElement = document.getElementById("assignmentDescription");
    const questionsCountElement = document.getElementById("questionsCount");
    const timeLimitElement = document.getElementById("timeLimit");

    if (titleElement) titleElement.textContent = this.assignmentData.title;
    if (descriptionElement)
      descriptionElement.textContent = this.assignmentData.description;
    if (questionsCountElement)
      questionsCountElement.textContent = this.questions.length;
    if (timeLimitElement)
      timeLimitElement.textContent = `${this.assignmentData.time_limit} minutes`;

    // Update total questions count in quiz interface
    // console.log(this.assignmentData);
    document.getElementById("totalQuestions").textContent =
      this.assignmentData.questions.length;
  }

  showError(message) {
    const errorContainer = document.createElement("div");
    errorContainer.className =
      "fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50";
    errorContainer.innerHTML = `
            <div class="bg-white rounded-lg p-8 max-w-md mx-4">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Error</h3>
                    <p class="text-gray-600 mb-4">${message}</p>
                    <button onclick="window.history.back()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Go Back
                    </button>
                </div>
            </div>
        `;
    document.body.appendChild(errorContainer);
  }

  initializeEventListeners() {
    // Start assignment button
    document
      .getElementById("startAssignmentBtn")
      .addEventListener("click", () => {
        this.startQuiz();
      });

    // Next question button
    document.getElementById("nextQuestionBtn").addEventListener("click", () => {
      this.nextQuestion();
    });

    // Continue quiz button (warning modal)
    document.getElementById("continueQuizBtn").addEventListener("click", () => {
      this.closeTimeWarningModal();
    });

    // View results button (time up modal)
    document.getElementById("viewResultsBtn").addEventListener("click", () => {
      this.closeTimeUpModal();
    });

    // Retake assignment button
    document
      .getElementById("retakeAssignmentBtn")
      .addEventListener("click", () => {
        this.retakeQuiz();
      });

    // Review answers button
    document
      .getElementById("reviewAnswersBtn")
      .addEventListener("click", () => {
        this.reviewAnswers();
      });

    // Prevent page refresh/close during quiz
    window.addEventListener("beforeunload", (e) => {
      if (
        this.quizStarted &&
        this.currentQuestionIndex < this.questions.length
      ) {
        e.preventDefault();
        e.returnValue =
          "Are you sure you want to leave? Your progress will be lost.";
        return e.returnValue;
      }
    });

    // Handle visibility change (tab switching)
    document.addEventListener("visibilitychange", () => {
      if (this.quizStarted && document.hidden) {
        console.log("User switched tabs during quiz");
        // Could implement additional security measures here
      }
    });
  }

  startQuiz() {
    this.quizStarted = true;
    this.startTime = new Date();

    // Hide landing page and show quiz interface
    document.getElementById("landingPage").classList.add("hidden");
    document.getElementById("quizInterface").classList.remove("hidden");

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
    const timeString = `${minutes.toString().padStart(2, "0")}:${seconds
      .toString()
      .padStart(2, "0")}`;

    const timerElement = document.getElementById("timeRemaining");
    const timerDisplay = document.getElementById("timerDisplay");

    timerElement.textContent = timeString;

    // Add warning styling when less than 5 minutes
    if (this.timeRemaining <= 5 * 60) {
      timerDisplay.classList.remove("bg-blue-50");
      timerDisplay.classList.add("bg-red-50", "timer-warning");
      timerElement.classList.remove("text-blue-600");
      timerElement.classList.add("text-red-600");
    }
  }

  loadQuestion() {
    // console.log(this.assignmentData.questions[this.currentQuestionIndex]);
    const question = this.assignmentData.questions[this.currentQuestionIndex];
    const questionContainer = document.getElementById("questionContainer");

    // Update question counter and progress
    document.getElementById("currentQuestionNum").textContent =
      this.currentQuestionIndex + 1;
    this.updateProgress();

    // Create question HTML
    questionContainer.innerHTML = `
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                    Question ${this.currentQuestionIndex + 1}
                </h2>
                <p class="text-lg text-gray-700 leading-relaxed">
                    ${question.question_text}
                </p>
            </div>

            <div class="space-y-3">
                ${question.options
                  .map(
                    (option, index) => `
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all option-label" data-option="${index}">
                        <input type="radio" name="question_${question.question_id}" value="${index}" class="sr-only option-input">
                        <div class="flex items-center justify-center w-6 h-6 border-2 border-gray-300 rounded-full mr-4 option-circle">
                            <div class="w-3 h-3 bg-blue-600 rounded-full hidden option-dot"></div>
                        </div>
                        <span class="text-gray-700 flex-1">${option.option_text}</span>
                    </label>
                `
                  )
                  .join("")}
            </div>
        `;

    // Add click handlers for options
    const optionLabels = questionContainer.querySelectorAll(".option-label");
    optionLabels.forEach((label, index) => {
      label.addEventListener("click", () => {
        this.selectOption(index);
      });
    });

    // Reset next button
    document.getElementById("nextQuestionBtn").disabled = true;

    // Add fade-in animation
    questionContainer.classList.remove("question-fade-in");
    setTimeout(() => {
      questionContainer.classList.add("question-fade-in");
    }, 10);
  }

  selectOption(selectedIndex) {
    const questionContainer = document.getElementById("questionContainer");
    const optionLabels = questionContainer.querySelectorAll(".option-label");
    const optionCircles = questionContainer.querySelectorAll(".option-circle");
    const optionDots = questionContainer.querySelectorAll(".option-dot");

    // Clear previous selections
    optionLabels.forEach((label, index) => {
      label.classList.remove("border-blue-500", "bg-blue-50");
      label.classList.add("border-gray-200");
      optionCircles[index].classList.remove("border-blue-500");
      optionCircles[index].classList.add("border-gray-300");
      optionDots[index].classList.add("hidden");
    });

    // Highlight selected option
    const selectedLabel = optionLabels[selectedIndex];
    const selectedCircle = optionCircles[selectedIndex];
    const selectedDot = optionDots[selectedIndex];

    selectedLabel.classList.remove("border-gray-200");
    selectedLabel.classList.add("border-blue-500", "bg-blue-50");
    selectedCircle.classList.remove("border-gray-300");
    selectedCircle.classList.add("border-blue-500");
    selectedDot.classList.remove("hidden");

    // Store answer
    this.userAnswers[this.currentQuestionIndex] = selectedIndex;

    // Enable next button
    document.getElementById("nextQuestionBtn").disabled = false;

    // Update button text for last question
    const nextBtn = document.getElementById("nextQuestionBtn");
    if (
      this.assignmentData.currentQuestionIndex ===
      this.assignmentData.questions.length - 1
    ) {
      nextBtn.innerHTML = 'Submit Quiz <i class="fas fa-check ml-2"></i>';
    } else {
      nextBtn.innerHTML =
        'Next Question <i class="fas fa-arrow-right ml-2"></i>';
    }
  }

  nextQuestion() {
    // Check if this is the last question
    // console.log(this.currentQuestionIndex);
    if (
      this.currentQuestionIndex ===
      this.assignmentData.questions.length - 1
    ) {
      this.submitQuiz();
      return;
    }

    // Move to next question
    this.currentQuestionIndex++;
    this.loadQuestion();
  }

  updateProgress() {
    // console.log(this.assignmentData)
    const progress =
      ((this.currentQuestionIndex + 1) / this.assignmentData.questions.length) *
      100;
    document.getElementById("progressBar").style.width = `${progress}%`;
    document.getElementById("progressPercent").textContent = `${Math.round(
      progress
    )}%`;
  }

  showTimeWarning() {
    document.getElementById("timeWarningModal").classList.remove("hidden");
    document.getElementById("timeWarningModal").classList.add("flex");
  }

  closeTimeWarningModal() {
    document.getElementById("timeWarningModal").classList.add("hidden");
    document.getElementById("timeWarningModal").classList.remove("flex");
  }

  timeUp() {
    clearInterval(this.timerInterval);
    this.quizStarted = false;

    // Fill remaining answers as unanswered
    for (let i = this.userAnswers.length; i < this.questions.length; i++) {
      this.userAnswers[i] = -1; // -1 indicates no answer
    }

    document.getElementById("timeUpModal").classList.remove("hidden");
    document.getElementById("timeUpModal").classList.add("flex");

    setTimeout(() => {
      this.closeTimeUpModal();
    }, 3000);
  }

  closeTimeUpModal() {
    document.getElementById("timeUpModal").classList.add("hidden");
    document.getElementById("timeUpModal").classList.remove("flex");
    this.showResults();
  }

  async submitQuiz() {
    clearInterval(this.timerInterval);
    this.quizStarted = false;

    // Show loading state
    this.showSubmissionLoading();

    try {
      // Prepare submission data
      const submissionData = {
        assignment_id: this.assignmentId,
        answers: this.userAnswers.map((answer, index) => ({
          question_id: this.assignmentData.questions[index].question_id,
          selected_option: answer,
        })),
      };

      const response = await fetch("../api/assignments/submit-assignment.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(submissionData),
      });

      const result = await response.json();

      if (result.success) {
        console.log(result);
        // Store submission results
        this.submissionResult = result;
        this.hideSubmissionLoading();
        this.showResults();
      } else {
        this.hideSubmissionLoading();
        this.showError(result.message || "Failed to submit assignment");
      }
    } catch (error) {
      console.error("Error submitting assignment:", error);
      this.hideSubmissionLoading();
      this.showError("Failed to submit assignment. Please try again.");
    }
  }

  showSubmissionLoading() {
    const loadingModal = document.createElement("div");
    loadingModal.id = "submissionLoadingModal";
    loadingModal.className =
      "fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50";
    loadingModal.innerHTML = `
            <div class="bg-white rounded-lg p-8 max-w-md mx-4 text-center">
                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Submitting Assignment</h3>
                <p class="text-gray-600">Please wait while we process your answers...</p>
            </div>
        `;
    document.body.appendChild(loadingModal);
  }

  hideSubmissionLoading() {
    const loadingModal = document.getElementById("submissionLoadingModal");
    if (loadingModal) {
      document.body.removeChild(loadingModal);
    }
  }

  showResults() {
    // Use submission results if available, otherwise calculate locally
    let score, correctCount, incorrectCount, timeSpent;

    console.log(this.submissionResult.results);

    if (this.submissionResult) {
      score = this.submissionResult.results.grade;
      correctCount = this.submissionResult.results.correct_answers;
      incorrectCount = this.submissionResult.results.incorrect_answers;
    } else {
      // Fallback to local calculation (for time-up scenarios)
      correctCount = 0;
      this.userAnswers.forEach((answer, index) => {
        const question = this.assignmentData.questions[index];
        const correctOptionIndex = question.options.findIndex(
          (opt) => opt.is_correct === 1
        );
        if (answer === correctOptionIndex) {
          correctCount++;
        }
      });

      incorrectCount = this.submissionResult.results.incorrect_answers;
      score = Math.round(
        (correctCount / this.assignmentData.questions.length) * 100
      );
    }

    timeSpent = this.timeLimit - this.timeRemaining;
    const timeSpentMinutes = Math.floor(timeSpent / 60);
    const timeSpentSeconds = timeSpent % 60;

    // Update results display
    document.getElementById("finalScore").textContent = `${score}%`;
    document.getElementById(
      "scoreDescription"
    ).textContent = `You answered ${correctCount} out of ${this.assignmentData.questions.length} questions correctly`;
    document.getElementById("correctAnswers").textContent = correctCount;
    document.getElementById("incorrectAnswers").textContent = incorrectCount;
    document.getElementById("timeSpent").textContent = `${timeSpentMinutes
      .toString()
      .padStart(2, "0")}:${timeSpentSeconds.toString().padStart(2, "0")}`;

    // Update results header based on performance
    const resultsHeader = document.getElementById("resultsHeader");
    const resultsIcon = document.getElementById("resultsIcon");
    const resultsTitle = document.getElementById("resultsTitle");
    const resultsSubtitle = document.getElementById("resultsSubtitle");

    if (score >= 75) {
      resultsHeader.className =
        "p-8 text-center text-white bg-gradient-to-r from-green-500 to-green-600";
      resultsIcon.className = "mb-4 text-6xl fas fa-trophy";
      resultsTitle.textContent = "Congratulations!";
      resultsSubtitle.textContent = "You passed the quiz!";
    } else if (score >= 50) {
      resultsHeader.className =
        "p-8 text-center text-white bg-gradient-to-r from-yellow-500 to-yellow-600";
      resultsIcon.className = "mb-4 text-6xl fas fa-medal";
      resultsTitle.textContent = "Good Effort!";
      resultsSubtitle.textContent = "You can do better with more practice";
    } else {
      resultsHeader.className =
        "p-8 text-center text-white bg-gradient-to-r from-red-500 to-red-600";
      resultsIcon.className = "mb-4 text-6xl fas fa-times-circle";
      resultsTitle.textContent = "Keep Learning!";
      resultsSubtitle.textContent = "Review the material and try again";
    }

    // Show results page
    document.getElementById("quizInterface").classList.add("hidden");
    const resultsPage = document.getElementById("resultsPage");
    resultsPage.classList.remove("hidden");
    resultsPage.classList.add("flex");
    resultsPage.style.display = "flex";
  }

  retakeQuiz() {
    // Reset quiz state
    this.currentQuestionIndex = 0;
    this.userAnswers = [];
    this.timeRemaining = this.timeLimit;
    this.warningShown = false;
    this.quizStarted = false;

    // Reset UI
    const resultsPage = document.getElementById("resultsPage");
    resultsPage.classList.add("hidden");
    resultsPage.classList.remove("flex");
    resultsPage.style.display = "none";
    document.getElementById("landingPage").classList.remove("hidden");

    // Reset timer display
    const timerDisplay = document.getElementById("timerDisplay");
    const timerElement = document.getElementById("timeRemaining");
    timerDisplay.classList.remove("bg-red-50", "timer-warning");
    timerDisplay.classList.add("bg-blue-50");
    timerElement.classList.remove("text-red-600");
    timerElement.classList.add("text-blue-600");

    // Update timer display with actual time limit
    const minutes = Math.floor(this.timeLimit / 60);
    const seconds = this.timeLimit % 60;
    timerElement.textContent = `${minutes.toString().padStart(2, "0")}:${seconds
      .toString()
      .padStart(2, "0")}`;
  }

  reviewAnswers() {
    // Create review interface
    const reviewContainer = document.createElement("div");
    reviewContainer.id = "reviewInterface";
    reviewContainer.className = "min-h-screen p-6 bg-gray-50";

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
    // console.log(this);
    this.assignmentData.questions.forEach((question, index) => {
      const userAnswer = this.userAnswers[index];

      const correctOptionIndex = question.options.findIndex(
        (opt) => opt.is_correct === true
      );

      const isCorrect = userAnswer === correctOptionIndex;
      const wasAnswered = userAnswer !== -1;

      reviewHTML += `
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                    <div class="flex items-start justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Question ${
                          index + 1
                        }</h2>
                        <div class="flex items-center">
                            ${
                              isCorrect
                                ? '<i class="fas fa-check-circle text-green-500 text-xl"></i>'
                                : '<i class="fas fa-times-circle text-red-500 text-xl"></i>'
                            }
                        </div>
                    </div>
                    
                    <p class="text-lg text-gray-700 mb-6">${
                      question.question_text
                    }</p>
                    
                    <div class="space-y-3 mb-6">
                        ${question.options
                          .map((option, optionIndex) => {
                            let classes =
                              "flex items-center p-4 border-2 rounded-xl";
                            let icon = "";

                            if (option.is_correct === 1) {
                              classes += " border-green-500 bg-green-50";
                              icon =
                                '<i class="fas fa-check text-green-600 mr-3"></i>';
                            } else if (
                              optionIndex === userAnswer &&
                              userAnswer !== correctOptionIndex
                            ) {
                              classes += " border-red-500 bg-red-50";
                              icon =
                                '<i class="fas fa-times text-red-600 mr-3"></i>';
                            } else {
                              classes += " border-gray-200";
                            }

                            return `
                                <div class="${classes}">
                                    ${icon}
                                    <span class="text-gray-700">${option.option_text}</span>
                                </div>
                            `;
                          })
                          .join("")}
                    </div>
                    
                    ${
                      !wasAnswered
                        ? '<div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg mb-4"><p class="text-yellow-800"><i class="fas fa-exclamation-triangle mr-2"></i>No answer provided</p></div>'
                        : ""
                    }
                    
                    ${
                      question.explanation
                        ? `
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-blue-800"><strong>Explanation:</strong> ${question.explanation}</p>
                        </div>
                    `
                        : ""
                    }
                </div>
            `;
    });

    reviewHTML += "</div>";
    reviewContainer.innerHTML = reviewHTML;

    // Replace results page with review
    const resultsPage = document.getElementById("resultsPage");
    resultsPage.classList.add("hidden");
    resultsPage.classList.remove("flex");
    resultsPage.style.display = "none";
    document.body.appendChild(reviewContainer);

    // Add back button functionality
    document
      .getElementById("backToResultsBtn")
      .addEventListener("click", () => {
        document.body.removeChild(reviewContainer);
        const resultsPage = document.getElementById("resultsPage");
        resultsPage.classList.remove("hidden");
        resultsPage.classList.add("flex");
        resultsPage.style.display = "flex";
      });
  }
}

// Initialize quiz when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  new AssignmentQuiz();
});
