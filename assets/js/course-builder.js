document.addEventListener("DOMContentLoaded", function () {
  // Step navigation
  let currentStep = 1;
  const totalSteps = 4;

  // Update progress indicators
  function updateStepIndicators(step) {
    // Update step numbers
    document.querySelectorAll(".steps-container .step").forEach((el, index) => {
      if (index + 1 < step) {
        el.classList.remove("bg-gray-200", "text-gray-600");
        el.classList.add("bg-blue-600", "text-white");
      } else if (index + 1 === step) {
        el.classList.remove("bg-gray-200", "text-gray-600");
        el.classList.add("bg-blue-600", "text-white");
      } else {
        el.classList.remove("bg-blue-600", "text-white");
        el.classList.add("bg-gray-200", "text-gray-600");
      }
    });

    // Update progress bars
    document.querySelectorAll(".progress-bar").forEach((el, index) => {
      if (index + 1 < step) {
        el.classList.remove("bg-gray-200");
        el.classList.add("bg-blue-600");
      } else {
        el.classList.remove("bg-blue-600");
        el.classList.add("bg-gray-200");
      }
    });
  }

  // Handle next/previous navigation
  document.querySelectorAll("[data-step]").forEach((button) => {
    button.addEventListener("click", (e) => {
      const direction = button.dataset.step;
      if (direction === "next" && currentStep < totalSteps) {
        currentStep++;
      } else if (direction === "prev" && currentStep > 1) {
        currentStep--;
      }
      updateStepIndicators(currentStep);
      showCurrentStep();
    });
  });

  // Show/hide steps
  function showCurrentStep() {
    document.querySelectorAll('[id^="step"]').forEach((step) => {
      step.classList.add("hidden");
    });
    document.getElementById(`step${currentStep}`).classList.remove("hidden");
  }

  // Module management
  let moduleCounter = 1;

  // Add new module
  document
    .querySelector('[data-action="add-module"]')
    ?.addEventListener("click", () => {
      moduleCounter++;
      const moduleTemplate = `
            <div class="p-4 mb-4 border border-gray-200 rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-8 h-8 mr-3 text-white bg-blue-600 rounded-full">${moduleCounter}</div>
                        <input type="text" class="text-lg font-medium bg-transparent border-none focus:ring-0" placeholder="Enter module title">
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-arrows-alt"></i>
                        </button>
                        <button class="p-2 text-gray-400 hover:text-red-600" data-action="delete-module">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="pl-11 space-y-3">
                    <button class="flex items-center w-full p-2 text-sm text-gray-600 hover:text-blue-600" data-action="add-lesson">
                        <i class="mr-2 fas fa-plus"></i>
                        Add Lesson
                    </button>
                </div>
            </div>
        `;
      document
        .querySelector(".modules-container")
        .insertAdjacentHTML("beforeend", moduleTemplate);
    });

  // Delete module
  document.addEventListener("click", (e) => {
    if (e.target.closest('[data-action="delete-module"]')) {
      e.target.closest(".module").remove();
      updateModuleNumbers();
    }
  });

  // Update module numbers
  function updateModuleNumbers() {
    document.querySelectorAll(".module-number").forEach((el, index) => {
      el.textContent = index + 1;
    });
  }

  // Lesson modal
  const lessonModal = document.getElementById("lessonModal");
  const addLessonBtns = document.querySelectorAll('[data-action="add-lesson"]');
  const closeLessonModal = document.querySelector(
    '#lessonModal [data-action="close"]'
  );

  addLessonBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      lessonModal.classList.remove("hidden");
      lessonModal.classList.add("flex");
    });
  });

  closeLessonModal?.addEventListener("click", () => {
    lessonModal.classList.add("hidden");
    lessonModal.classList.remove("flex");
  });

  // Close modal on outside click
  lessonModal?.addEventListener("click", (e) => {
    if (e.target === lessonModal) {
      lessonModal.classList.add("hidden");
      lessonModal.classList.remove("flex");
    }
  });

  // Add new lesson
  document
    .querySelector('[data-action="add-lesson-submit"]')
    ?.addEventListener("click", () => {
      const lessonTitle = document.getElementById("lessonTitle").value;
      const lessonType = document.getElementById("lessonType").value;

      const lessonTemplate = `
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center flex-1">
                    <i class="mr-3 text-gray-400 fas fa-file-alt"></i>
                    <input type="text" class="flex-1 bg-transparent border-none focus:ring-0" value="${lessonTitle}">
                </div>
                <div class="flex items-center space-x-4">
                    <select class="text-sm border-none rounded bg-gray-50">
                        <option ${
                          lessonType === "video" ? "selected" : ""
                        }>Video</option>
                        <option ${
                          lessonType === "document" ? "selected" : ""
                        }>Document</option>
                        <option ${
                          lessonType === "quiz" ? "selected" : ""
                        }>Quiz</option>
                    </select>
                    <button class="p-1 text-gray-400 hover:text-red-600" data-action="delete-lesson">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;

      // Add lesson to the current module
      const currentModule = document.querySelector(".module.active");
      currentModule
        ?.querySelector(".lessons-container")
        .insertAdjacentHTML("beforeend", lessonTemplate);

      // Close modal
      lessonModal.classList.add("hidden");
      lessonModal.classList.remove("flex");
    });

  // Initialize drag and drop for modules
  if (typeof Sortable !== "undefined") {
    const modulesContainer = document.querySelector(".modules-container");
    if (modulesContainer) {
      new Sortable(modulesContainer, {
        animation: 150,
        handle: ".fa-arrows-alt",
        onEnd: updateModuleNumbers,
      });
    }
  }
});
