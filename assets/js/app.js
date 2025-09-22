// Trainee Dashboard JavaScript
document.addEventListener("DOMContentLoaded", function () {
  // Initialize Charts
  initializeHoursChart();
  initializePerformanceChart();

  // Initialize Interactive Elements
  initializeLearningStreak();
  initializeTodoList();
  initializeResourceButtons();
});

// Hours Spent Bar Chart
function initializeHoursChart() {
  const ctx = document.getElementById("hoursChart");
  if (!ctx) return;

  // Set fixed dimensions for the canvas
  ctx.style.width = "100%";
  ctx.style.height = "192px";
  ctx.width = ctx.offsetWidth;
  ctx.height = 192;

  new Chart(ctx, {
    type: "bar",
    data: {
      labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
      datasets: [
        {
          label: "Study",
          data: [20, 35, 30, 45, 40, 50],
          backgroundColor: "#3B82F6",
          borderRadius: 4,
          barThickness: 20,
        },
        {
          label: "Online Test",
          data: [15, 25, 20, 30, 25, 35],
          backgroundColor: "#D1D5DB",
          borderRadius: 4,
          barThickness: 20,
        },
      ],
    },
    options: {
      responsive: false,
      maintainAspectRatio: false,
      animation: {
        duration: 1000,
      },
      plugins: {
        legend: {
          display: false,
        },
      },
      scales: {
        x: {
          grid: {
            display: false,
          },
        },
        y: {
          beginAtZero: true,
          max: 60,
          ticks: {
            stepSize: 20,
            callback: function (value) {
              return value + "H";
            },
          },
        },
      },
    },
  });
}

// Performance Speedometer Gauge
function initializePerformanceChart() {
  const canvas = document.getElementById("performanceGauge");
  if (!canvas) return;

  // Create modern gauge options
  var opts = {
    angle: 0, // The span of the gauge arc
    lineWidth: 0.23, // The line thickness
    radiusScale: 1, // Relative radius
    pointer: {
      length: 0.35, // // Relative to gauge radius
      strokeWidth: 0.035, // The thickness
      color: "#136eff", // Fill color
    },
    limitMax: false, // If false, max value increases automatically if value > maxValue
    limitMin: false, // If true, the min value of the gauge will be fixed
    colorStart: "#136eff", // Colors
    colorStop: "#136eff", // just experiment with them
    strokeColor: "#E0E0E0", // to see which ones work best for you
    generateGradient: true,
    highDpiSupport: true, // High resolution support
    // renderTicks is Optional
    renderTicks: {
      divisions: 3,
      divWidth: 0.9,
      divLength: 0.24,
      divColor: "#545454",
      subDivisions: 3,
      subLength: 0.3,
      subWidth: 1,
      subColor: "#404040",
    },
  };

  // Create gauge
  const gauge = new Gauge(canvas).setOptions(opts);
  gauge.maxValue = 10; // set max gauge value
  gauge.setMinValue(0); // set min value
  gauge.animationSpeed = 32; // set animation speed (32 is default value)

  // Animate to the target value
  setTimeout(() => {
    gauge.set(8.966); // set actual value with animation
  }, 500);

  // Add modern center text with better positioning
}

// Learning Streak Animation
function initializeLearningStreak() {
  const streakDays = document.querySelectorAll(".streak-day");

  // Animate streak days on load
  streakDays.forEach((day, index) => {
    setTimeout(() => {
      day.style.transform = "scale(1.1)";
      setTimeout(() => {
        day.style.transform = "scale(1)";
      }, 200);
    }, index * 100);
  });

  // Add hover effects for achievement badges
  const badges = document.querySelectorAll(".achievement-badge");
  badges.forEach((badge) => {
    badge.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-2px)";
      this.style.boxShadow = "0 4px 12px rgba(0, 0, 0, 0.1)";
    });

    badge.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0)";
      this.style.boxShadow = "none";
    });
  });
}

// To-Do List Functionality
function initializeTodoList() {
  const todoCheckboxes = document.querySelectorAll(".todo-checkbox");
  const addTaskBtn = document.querySelector(".add-task-btn");

  // Handle checkbox changes
  todoCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
      const taskText = this.nextElementSibling.querySelector("p");

      if (this.checked) {
        taskText.classList.add("line-through", "text-gray-500");
      } else {
        taskText.classList.remove("line-through", "text-gray-500");
      }
    });
  });

  // Handle add task button
  if (addTaskBtn) {
    addTaskBtn.addEventListener("click", function () {
      const taskName = prompt("Enter new task name:");
      if (taskName && taskName.trim()) {
        const todoList = document.getElementById("todoList");
        const newTask = createSimpleTaskElement(taskName.trim());
        todoList.insertBefore(newTask, todoList.firstChild);

        // Re-initialize event listeners for the new task
        initializeSimpleTaskListeners(newTask);

        toastr.success(
          `"${taskName}" added to your todo list`,
          "New Task Added"
        );
      }
    });
  }
}

// Helper function to create simple task element
function createSimpleTaskElement(taskName) {
  const taskDiv = document.createElement("div");
  taskDiv.className = "flex items-start space-x-3";

  taskDiv.innerHTML = `
    <input type="checkbox" class="mt-1 border-gray-300 rounded todo-checkbox">
    <div class="flex-1">
      <p class="text-sm font-medium text-gray-900 todo-text">${taskName}</p>
      <p class="text-xs text-gray-500">Added just now</p>
    </div>
  `;

  return taskDiv;
}

// Helper function to initialize listeners for new simple tasks
function initializeSimpleTaskListeners(taskElement) {
  const checkbox = taskElement.querySelector(".todo-checkbox");

  checkbox.addEventListener("change", function () {
    const taskText = this.nextElementSibling.querySelector("p");

    if (this.checked) {
      taskText.classList.add("line-through", "text-gray-500");
    } else {
      taskText.classList.remove("line-through", "text-gray-500");
    }
  });
}

// Resource Buttons
function initializeResourceButtons() {
  // Join class button functionality
  document.querySelectorAll(".join-class-btn").forEach((button) => {
    button.addEventListener("click", function () {
      toastr.success("Joining class...", "Success");
    });
  });

  // Featured lessons enroll button functionality
  document.querySelectorAll(".enroll-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const lessonCard = this.closest(".featured-lesson");
      const lessonTitle = lessonCard.querySelector("h4").textContent;

      // Add loading state
      const originalText = this.textContent;
      this.textContent = "Enrolling...";
      this.disabled = true;

      // Simulate enrollment process
      setTimeout(() => {
        this.textContent = "Enrolled ✓";
        this.classList.remove("bg-blue-600", "hover:bg-blue-700");
        this.classList.add("bg-gray-400", "cursor-not-allowed");
        toastr.success(
          `Successfully enrolled in "${lessonTitle}"!`,
          "Enrollment Complete"
        );
      }, 1500);
    });
  });

  // Recent enrolled classes functionality
  document.querySelectorAll(".continue-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const courseCard = this.closest(".enrolled-course");
      const courseTitle = courseCard.querySelector("h4").textContent;

      // Add loading state
      const originalText = this.textContent;
      this.textContent = "Loading...";
      this.disabled = true;

      // Simulate course loading
      setTimeout(() => {
        this.textContent = originalText;
        this.disabled = false;
        toastr.success(`Continuing "${courseTitle}"...`, "Course Loaded");
      }, 1000);
    });
  });

  document.querySelectorAll(".start-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const courseCard = this.closest(".enrolled-course");
      const courseTitle = courseCard.querySelector("h4").textContent;
      const progressBar = courseCard.querySelector(".progress-bar");

      // Add loading state
      const originalText = this.textContent;
      this.textContent = "Starting...";
      this.disabled = true;

      // Simulate course start
      setTimeout(() => {
        this.textContent = "Continue";
        this.classList.remove("start-btn");
        this.classList.add("continue-btn");
        this.disabled = false;

        // Update progress bar
        progressBar.style.width = "10%";

        // Update progress text
        const progressText = courseCard.querySelector(".text-gray-600");
        const moduleText = courseCard.querySelector(".font-medium");
        progressText.textContent = "Progress: 10% complete";
        moduleText.textContent = "1/8 modules";

        toastr.success(
          `Started "${courseTitle}"! Welcome to your learning journey.`,
          "Course Started"
        );
      }, 1500);
    });
  });

  const resourceButtons = document.querySelectorAll(".resource-btn");

  resourceButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const resourceName =
        this.closest(".resource-item").querySelector("p").textContent;

      // Show toast notification
      if (typeof toastr !== "undefined") {
        toastr.info(`Opening ${resourceName}...`);
      }

      // Simulate opening resource
      setTimeout(() => {
        if (typeof toastr !== "undefined") {
          toastr.success(`${resourceName} opened successfully!`);
        }
      }, 1000);
    });
  });
}

// Progress Animation
function animateProgress() {
  const progressBars = document.querySelectorAll(".progress-bar");

  progressBars.forEach((bar) => {
    const width = bar.style.width;
    bar.style.width = "0%";

    setTimeout(() => {
      bar.style.transition = "width 1s ease-in-out";
      bar.style.width = width;
    }, 100);
  });
}

// Join Class Button Functionality
document.addEventListener("click", function (e) {
  if (e.target.classList.contains("join-class-btn")) {
    const className = e.target
      .closest(".lesson-item")
      .querySelector("h4").textContent;

    if (typeof toastr !== "undefined") {
      toastr.success(`Joining ${className}...`);
    }

    // Simulate joining class
    setTimeout(() => {
      if (typeof toastr !== "undefined") {
        toastr.info("Redirecting to virtual classroom...");
      }
    }, 1500);
  }
});

// Smooth scroll for navigation
function smoothScroll(target) {
  document.querySelector(target).scrollIntoView({
    behavior: "smooth",
  });
}

// Initialize animations on page load
window.addEventListener("load", function () {
  animateProgress();
});
