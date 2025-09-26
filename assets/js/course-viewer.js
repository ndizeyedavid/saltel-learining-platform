// Course Viewer Functionality
document.addEventListener("DOMContentLoaded", function () {
  function initializeCourseViewer() {
    // Get course ID from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const courseId = urlParams.get("course");

    if (courseId) {
      // Load real course data from database
      loadCourseFromDatabase(courseId);
    } else {
      console.error("No course ID provided");
    }
  }

  function loadCourseFromDatabase(courseId) {
    // Fetch course data from the server
    fetch(`../api/courses/get-course-data.php?course_id=${courseId}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          console.log(data);
          updateCourseHeader(data.course);
          renderModulesFromDatabase(data.modules);
        } else {
          console.error("Failed to load course data:", data.message);
          showNotification("Failed to load course data", "error");
        }
      })
      .catch((error) => {
        console.error("Error fetching course data:", error);
        showNotification("Network error loading course", "error");
      });
  }

  function updateCourseHeader(courseData) {
    const elements = {
      courseTitle: courseData.title,
      overallProgress: `${courseData.progress}%`,
      completedLessons: `${courseData.completed_lessons} of ${courseData.total_lessons} lessons`,
      totalModules: courseData.total_modules,
      completedModules: courseData.completed_modules,
      currentModule: courseData.current_module || "Not Started",
      currentLesson: courseData.current_lesson || "Select a lesson",
    };

    Object.entries(elements).forEach(([id, value]) => {
      const element = document.getElementById(id);
      if (element) {
        element.textContent = value;
      }
    });

    // Update progress bar
    const progressBar = document.getElementById("progressBar");
    if (progressBar) {
      progressBar.style.width = `${courseData.progress}%`;
      if (courseData.progress >= 100) {
        progressBar.classList.remove("bg-blue-600");
        progressBar.classList.add("bg-green-600");
      }
    }
  }

  function renderModulesFromDatabase(modules) {
    const moduleList = document.getElementById("moduleList");
    if (!moduleList) return;

    // Clear existing content (including fallback modules)
    moduleList.innerHTML = "";

    if (!modules || modules.length === 0) {
      moduleList.innerHTML = `
        <div class="p-4 text-center text-gray-500">
          <i class="mb-2 text-2xl fas fa-exclamation-triangle"></i>
          <p>No modules found for this course.</p>
        </div>
      `;
      return;
    }

    modules.forEach((module) => {
      const moduleElement = document.createElement("div");
      moduleElement.className = `module-item ${
        module.is_current ? "current" : ""
      } ${module.is_completed ? "completed" : ""}`;

      moduleElement.innerHTML = `
                <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors" data-module="${
                  module.module_id
                }">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            ${
                              module.is_completed
                                ? '<i class="fas fa-check-circle text-green-500"></i>'
                                : module.is_current
                                ? '<i class="fas fa-play-circle text-blue-500"></i>'
                                : '<i class="far fa-circle text-gray-400"></i>'
                            }
                        </div>
                        <div>
                            <div class="font-medium text-sm text-gray-900">${
                              module.title
                            }</div>
                            <div class="text-xs text-gray-500">${
                              module.lesson_count
                            } lessons</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-medium ${
                          module.is_completed
                            ? "text-green-600"
                            : module.is_current
                            ? "text-blue-600"
                            : "text-gray-500"
                        }">
                            ${Math.round(module.progress)}%
                        </div>
                        ${
                          !module.is_completed && module.progress > 0
                            ? `
                            <div class="w-12 bg-gray-200 rounded-full h-1 mt-1">
                                <div class="bg-blue-600 h-1 rounded-full" style="width: ${module.progress}%"></div>
                            </div>
                        `
                            : ""
                        }
                    </div>
                </div>
            `;

      moduleList.appendChild(moduleElement);
    });

    // Add click handlers for modules
    moduleList.addEventListener("click", function (e) {
      const moduleItem = e.target.closest("[data-module]");
      if (moduleItem) {
        const moduleId = moduleItem.dataset.module;
        loadModuleLessons(moduleId);
      }
    });
  }

  function loadModuleLessons(moduleId) {
    const urlParams = new URLSearchParams(window.location.search);
    const courseId = urlParams.get("course");

    // Fetch lessons for the selected module
    fetch(
      `../api/courses/get-module-lessons.php?module_id=${moduleId}&course_id=${courseId}`
    )
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          displayModuleLessons(data.lessons, moduleId);
          updateCurrentModule(moduleId);
        } else {
          showNotification("Failed to load module lessons", "error");
        }
      })
      .catch((error) => {
        console.error("Error fetching module lessons:", error);
        showNotification("Network error loading lessons", "error");
      });
  }

  function displayModuleLessons(lessons, moduleId) {
    // Create lessons list in sidebar
    const moduleElement = document.querySelector(`[data-module="${moduleId}"]`);
    if (!moduleElement) return;

    // Remove existing lessons list if any
    const existingLessonsList =
      moduleElement.parentElement.querySelector(".lessons-list");
    if (existingLessonsList) {
      existingLessonsList.remove();
    }

    // Create new lessons list
    const lessonsList = document.createElement("div");
    lessonsList.className = "mt-2 ml-6 space-y-1 lessons-list";

    lessons.forEach((lesson) => {
      const lessonElement = document.createElement("div");
      lessonElement.className = `lesson-item p-2 rounded border-l-2 cursor-pointer transition-colors hover:bg-gray-50 ${
        lesson.is_completed
          ? "border-green-500 bg-green-50"
          : lesson.is_current
          ? "border-blue-500 bg-blue-50"
          : "border-gray-300"
      }`;

      lessonElement.innerHTML = `
                <div class="flex items-center justify-between" data-lesson="${
                  lesson.lesson_id
                }">
                    <div class="flex items-center space-x-2">
                        <i class="fas ${
                          lesson.is_completed
                            ? "fa-check-circle text-green-500"
                            : lesson.is_current
                            ? "fa-play-circle text-blue-500"
                            : "fa-circle text-gray-400"
                        } text-xs"></i>
                        <span class="text-xs font-medium text-gray-800">${
                          lesson.title
                        }</span>
                    </div>
                    <span class="text-xs text-gray-500">${
                      lesson.duration || "5 min"
                    }</span>
                </div>
            `;

      // Add click handler for lesson
      lessonElement.addEventListener("click", function (e) {
        e.stopPropagation();
        const lessonId = lesson.lesson_id;
        const courseId = new URLSearchParams(window.location.search).get(
          "course"
        );
        window.location.href = `course-viewer.php?course=${courseId}&lesson=${lessonId}`;
      });

      lessonsList.appendChild(lessonElement);
    });

    // Insert lessons list after the module element
    moduleElement.parentElement.insertBefore(
      lessonsList,
      moduleElement.nextSibling
    );
  }

  function updateCurrentModule(moduleId) {
    // Update current module styling
    document.querySelectorAll(".module-item").forEach((item) => {
      item.classList.remove("current");
    });

    const currentModule = document.querySelector(`[data-module="${moduleId}"]`);
    if (currentModule) {
      currentModule.closest(".module-item").classList.add("current");
    }
  }

  // Remove old static functions - no longer needed with database integration

  function initializeVideoPlayer() {
    const video = document.getElementById("lessonVideo");
    const playButton = document.getElementById("playButton");
    const videoOverlay = document.getElementById("videoOverlay");

    // Check if elements exist before adding event listeners
    if (playButton) {
      playButton.addEventListener("click", function () {
        if (video) {
          video.play();
        }
        if (videoOverlay) {
          videoOverlay.style.display = "none";
        }
      });
    }

    if (video) {
      video.addEventListener("play", function () {
        if (videoOverlay) {
          videoOverlay.style.display = "none";
        }
      });

      video.addEventListener("pause", function () {
        if (video.currentTime === 0 && videoOverlay) {
          videoOverlay.style.display = "flex";
        }
      });

      video.addEventListener("ended", function () {
        if (videoOverlay) {
          videoOverlay.style.display = "flex";
        }
        if (playButton) {
          playButton.innerHTML =
            '<i class="fas fa-redo text-white text-2xl"></i>';
        }
      });
    }
  }

  function initializeQuizzes() {
    const submitButton = document.getElementById("submitQuiz");

    if (submitButton) {
      submitButton.addEventListener("click", function () {
        // Find any selected quiz answer (dynamic question ID support)
        const selectedAnswer = document.querySelector(
          'input[name^="quiz_"]:checked'
        );

        if (!selectedAnswer) {
          Swal.fire({
            title: "Answer Required",
            text: "Please select an answer before submitting.",
            icon: "warning",
            confirmButtonText: "OK",
            confirmButtonColor: "#f59e0b",
          });
          return;
        }

        // Extract question ID from input name (quiz_123 -> 123)
        const inputName = selectedAnswer.name;
        const questionId = inputName.replace("quiz_", "");
        const selectedOptionId = selectedAnswer.value;
        // console.log(selectedOptionId);
        // Verify answer with database
        fetch("../api/quiz/verify-answer.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            question_id: parseInt(questionId),
            selected_option_id: parseInt(selectedOptionId),
          }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              showQuizFeedback(data);

              // Disable submit button after submission
              submitButton.disabled = true;
              submitButton.textContent = "Submitted";
              submitButton.classList.add("opacity-50", "cursor-not-allowed");
            } else {
              Swal.fire({
                title: "Error",
                text: data.message || "Failed to verify answer",
                icon: "error",
                confirmButtonText: "OK",
              });
            }
          })
          .catch((error) => {
            console.error("Error verifying answer:", error);
            Swal.fire({
              title: "Error",
              text: "Failed to verify answer. Please try again.",
              icon: "error",
              confirmButtonText: "OK",
            });
          });
      });
    }
  }

  function showQuizFeedback(data) {
    const quizSection = document.getElementById("quizSection");
    const feedbackDiv = document.createElement("div");

    feedbackDiv.className = `mt-4 p-4 rounded-lg ${
      data.is_correct
        ? "bg-green-50 border border-green-200"
        : "bg-red-50 border border-red-200"
    }`;

    feedbackDiv.innerHTML = `
      <div class="flex items-center">
        <i class="fas ${
          data.is_correct
            ? "fa-check-circle text-green-500"
            : "fa-times-circle text-red-500"
        } mr-2"></i>
        <span class="font-medium ${
          data.is_correct ? "text-green-800" : "text-red-800"
        }">
          ${data.is_correct ? "Correct!" : "Incorrect."}
        </span>
        ${
          data.points_earned > 0
            ? `<span class="ml-2 text-sm font-medium text-blue-600">+${data.points_earned} points</span>`
            : ""
        }
      </div>
      <p class="mt-2 text-sm ${
        data.is_correct ? "text-green-700" : "text-red-700"
      }">
        ${data.feedback}
      </p>
    `;

    // Remove existing feedback
    const existingFeedback = quizSection.querySelector(".mt-4.p-4.rounded-lg");
    if (existingFeedback) {
      existingFeedback.remove();
    }

    quizSection.appendChild(feedbackDiv);
  }

  function initializeNavigation() {
    const backButton = document.getElementById("backToCourses");
    const prevButton = document.getElementById("prevLesson");
    const nextButton = document.getElementById("nextLesson");
    const markCompleteButton = document.getElementById("markComplete");

    if (backButton) {
      backButton.addEventListener("click", function () {
        window.location.href = "courses.php";
      });
    }

    if (prevButton) {
      prevButton.addEventListener("click", function () {
        navigateToLesson("prev");
      });
    }

    if (nextButton) {
      nextButton.addEventListener("click", function () {
        navigateToLesson("next");
      });
    }

    if (markCompleteButton) {
      markCompleteButton.addEventListener("click", function () {
        markLessonComplete();
      });
    }
  }

  function navigateToLesson(direction) {
    const urlParams = new URLSearchParams(window.location.search);
    const currentLessonId = parseInt(urlParams.get("lesson")) || 1;
    const courseId = urlParams.get("course");

    let newLessonId;
    if (direction === "next") {
      newLessonId = currentLessonId + 1;
    } else {
      newLessonId = Math.max(1, currentLessonId - 1);
    }

    // Navigate to new lesson
    const newUrl = `course-viewer.php?course=${courseId}&lesson=${newLessonId}`;
    window.location.href = newUrl;
  }

  function markLessonComplete() {
    const urlParams = new URLSearchParams(window.location.search);
    const lessonId = parseInt(urlParams.get("lesson"));
    const courseId = parseInt(urlParams.get("course"));

    if (!lessonId || !courseId) {
      showNotification("Error: Missing lesson or course ID", "error");
      return;
    }

    const markCompleteButton = document.getElementById("markComplete");
    const originalContent = markCompleteButton.innerHTML;

    // Show loading state
    markCompleteButton.innerHTML =
      '<i class="fas fa-spinner fa-spin mr-2"></i>Completing...';
    markCompleteButton.disabled = true;

    // Send completion request to API
    fetch("../api/lessons/complete.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        lesson_id: lessonId,
        course_id: courseId,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Update button to completed state
          markCompleteButton.innerHTML =
            '<i class="fas fa-check mr-2"></i>Completed';
          markCompleteButton.classList.remove(
            "text-green-600",
            "hover:text-green-700",
            "border-green-200"
          );
          markCompleteButton.classList.add(
            "text-green-700",
            "bg-green-50",
            "border-green-300"
          );

          // Show success notification with XP earned
          let message = "Lesson completed successfully!";
          if (data.xp_earned > 0) {
            message += ` +${data.xp_earned} XP earned!`;
          }
          if (data.course_completed) {
            message += " 🎉 Course completed! Certificate generated!";
          }

          showNotification(message, "success");

          // Update progress display
          if (data.progress !== undefined) {
            updateProgressDisplay(data.progress);
          }

          // If course completed, show celebration
          if (data.course_completed) {
            showCourseCompletionCelebration();
          }
        } else {
          // Restore button on error
          markCompleteButton.innerHTML = originalContent;
          markCompleteButton.disabled = false;
          showNotification(
            data.message || "Failed to mark lesson as complete",
            "error"
          );
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        markCompleteButton.innerHTML = originalContent;
        markCompleteButton.disabled = false;
        showNotification("Network error. Please try again.", "error");
      });
  }

  function updateProgressDisplay(newProgress) {
    const progressElement = document.getElementById("overallProgress");
    const progressBar = document.getElementById("progressBar");

    if (progressElement) {
      progressElement.textContent = `${newProgress}%`;
    }
    if (progressBar) {
      progressBar.style.width = `${newProgress}%`;

      // Change color to green if completed
      if (newProgress >= 100) {
        progressBar.classList.remove("bg-blue-600");
        progressBar.classList.add("bg-green-600");
      }
    }
  }

  function showCourseCompletionCelebration() {
    Swal.fire({
      title: "🎉 Congratulations!",
      text: "You have successfully completed this course! Your certificate has been generated.",
      icon: "success",
      confirmButtonText: "View Certificate",
      confirmButtonColor: "#10b981",
      showCancelButton: true,
      cancelButtonText: "Continue Learning",
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "certificates.php";
      } else if (result.isDismissed) {
        window.location.reload();
      }
    });
  }

  function initializeNotes() {
    const takeNotesButton = document.getElementById("takeNotes");
    const notesModal = document.getElementById("notesModal");
    const closeNotesButton = document.getElementById("closeNotes");
    const cancelNotesButton = document.getElementById("cancelNotes");
    const saveNotesButton = document.getElementById("saveNotes");
    const notesTextarea = document.getElementById("notesTextarea");

    if (takeNotesButton && notesModal && notesTextarea) {
      takeNotesButton.addEventListener("click", function () {
        notesModal.classList.remove("hidden");
        notesModal.classList.add("flex");
        notesTextarea.focus();
      });
    }

    if (closeNotesButton) {
      closeNotesButton.addEventListener("click", closeNotesModal);
    }
    if (cancelNotesButton) {
      cancelNotesButton.addEventListener("click", closeNotesModal);
    }

    function closeNotesModal() {
      if (notesModal) {
        notesModal.classList.add("hidden");
        notesModal.classList.remove("flex");
      }
    }

    if (saveNotesButton && notesTextarea) {
      saveNotesButton.addEventListener("click", function () {
        const notes = notesTextarea.value.trim();
        if (notes) {
          // Save notes (in real app, this would save to database)
          localStorage.setItem("lesson-notes", notes);
          Swal.fire({
            toast: true,
            position: "top-end",
            icon: "success",
            title: "Progress saved successfully!",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
          });
          closeNotesModal();
        }
      });
    }

    // Load existing notes
    const savedNotes = localStorage.getItem("lesson-notes");
    if (savedNotes && notesTextarea) {
      notesTextarea.value = savedNotes;
    }

    // Close modal when clicking outside
    if (notesModal) {
      notesModal.addEventListener("click", function (e) {
        if (e.target === notesModal) {
          closeNotesModal();
        }
      });
    }
  }

  function showNotification(message, type = "info") {
    Swal.fire({
      toast: true,
      position: "top-end",
      icon: type,
      title: message,
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
    });
  }

  // Bookmark functionality
  const bookmarkButton = document.getElementById("bookmarkLesson");
  if (bookmarkButton) {
    bookmarkButton.addEventListener("click", function () {
      const icon = this.querySelector("i");
      if (icon.classList.contains("far")) {
        icon.classList.remove("far");
        icon.classList.add("fas");
        this.classList.add("text-blue-600");
        showNotification("Lesson bookmarked!", "success");
      } else {
        icon.classList.remove("fas");
        icon.classList.add("far");
        this.classList.remove("text-blue-600");
        showNotification("Bookmark removed", "info");
      }
    });
  }

  // Share functionality
  const shareButton = document.getElementById("shareLesson");
  if (shareButton) {
    shareButton.addEventListener("click", function () {
      if (navigator.share) {
        navigator.share({
          title: "Machine Learning Algorithms - Data Science Course",
          text: "Check out this lesson on machine learning algorithms!",
          url: window.location.href,
        });
      } else {
        // Fallback to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
          showNotification("Lesson link copied to clipboard!", "success");
        });
      }
    });
  }

  // Keyboard shortcuts
  document.addEventListener("keydown", function (e) {
    if (e.ctrlKey || e.metaKey) {
      switch (e.key) {
        case "ArrowLeft":
          e.preventDefault();
          const prevBtn = document.getElementById("prevLesson");
          if (prevBtn) prevBtn.click();
          break;
        case "ArrowRight":
          e.preventDefault();
          const nextBtn = document.getElementById("nextLesson");
          if (nextBtn) nextBtn.click();
          break;
        case "Enter":
          e.preventDefault();
          const completeBtn = document.getElementById("markComplete");
          if (completeBtn) completeBtn.click();
          break;
      }
    }
  });

  // Auto-save progress periodically
  setInterval(function () {
    // In a real app, this would save progress to the server
    const videoElement = document.getElementById("lessonVideo");
    const progress = {
      courseId: "data-science",
      lessonId: "ml-algorithms",
      timestamp: new Date().toISOString(),
      videoProgress: videoElement ? videoElement.currentTime : 0,
    };
    localStorage.setItem("course-progress", JSON.stringify(progress));
  }, 30000); // Save every 30 seconds

  // Initialize course viewer components after all functions are defined
  setTimeout(function () {
    initializeCourseViewer();
    initializeVideoPlayer();
    initializeQuizzes();
    initializeNavigation();
    initializeNotes();
  }, 0);
});
