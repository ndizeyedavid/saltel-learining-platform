// Dashboard functionality for trainee index page
document.addEventListener("DOMContentLoaded", function () {
  // Todo functionality
  initializeTodoHandlers();

  // Chart initialization
  initializeCharts();
});

function initializeTodoHandlers() {
  // Handle todo checkbox changes
  document.addEventListener("change", function (e) {
    if (e.target.classList.contains("todo-checkbox")) {
      const todoId = e.target.dataset.todoId;
      const isCompleted = e.target.checked;
      updateTodoStatus(todoId, isCompleted);
    }
  });

  // Handle add task button
  const addTaskBtn = document.querySelector(".add-task-btn");
  if (addTaskBtn) {
    addTaskBtn.addEventListener("click", function () {
      showAddTodoModal();
    });
  }
}

function updateTodoStatus(todoId, isCompleted) {
  fetch("/elearning/dashboard/api/todos/update.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      todo_id: parseInt(todoId),
      is_completed: isCompleted,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Update UI
        const todoElement = document
          .querySelector(`input[data-todo-id="${todoId}"]`)
          .closest(".flex");
        const textElement = todoElement.querySelector(".todo-text");

        if (isCompleted) {
          textElement.classList.add("text-gray-500", "line-through");
          textElement.classList.remove("text-gray-900");
        } else {
          textElement.classList.remove("text-gray-500", "line-through");
          textElement.classList.add("text-gray-900");
        }
      } else {
        console.error("Failed to update todo:", data.error);
        // Show error message
        Swal.fire({
          icon: 'error',
          title: 'Update Failed',
          text: data.error || 'Failed to update task status',
          confirmButtonColor: '#3b82f6'
        });
        // Revert checkbox state
        document.querySelector(`input[data-todo-id="${todoId}"]`).checked =
          !isCompleted;
      }
    })
    .catch((error) => {
      console.error("Error updating todo:", error);
      // Show error message
      Swal.fire({
        icon: 'error',
        title: 'Connection Error',
        text: 'Unable to update task. Please check your connection and try again.',
        confirmButtonColor: '#3b82f6'
      });
      // Revert checkbox state
      document.querySelector(`input[data-todo-id="${todoId}"]`).checked =
        !isCompleted;
    });
}

async function showAddTodoModal() {
  const { value: formValues } = await Swal.fire({
    title: 'Add New Task',
    html: `
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Task Title</label>
          <input id="swal-input1" class="swal2-input" placeholder="Enter task title" style="margin: 0; width: 100%;">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Due Date (Optional)</label>
          <input id="swal-input2" type="date" class="swal2-input" style="margin: 0; width: 100%;">
        </div>
      </div>
    `,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText: 'Add Task',
    cancelButtonText: 'Cancel',
    confirmButtonColor: '#3b82f6',
    cancelButtonColor: '#6b7280',
    preConfirm: () => {
      const title = document.getElementById('swal-input1').value;
      const dueDate = document.getElementById('swal-input2').value;
      
      if (!title || !title.trim()) {
        Swal.showValidationMessage('Please enter a task title');
        return false;
      }
      
      return {
        title: title.trim(),
        dueDate: dueDate || null
      };
    }
  });

  if (formValues) {
    createTodo(formValues.title, formValues.dueDate);
  }
}

function createTodo(title, dueDate) {
  fetch("/elearning/dashboard/api/todos/create.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      title: title,
      due_date: dueDate,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Show success message and reload
        Swal.fire({
          icon: 'success',
          title: 'Task Created!',
          text: 'Your new task has been added successfully.',
          confirmButtonColor: '#3b82f6',
          timer: 2000,
          timerProgressBar: true
        }).then(() => {
          window.location.reload();
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Creation Failed',
          text: data.error || 'Failed to create task',
          confirmButtonColor: '#3b82f6'
        });
      }
    })
    .catch((error) => {
      console.error("Error creating todo:", error);
      Swal.fire({
        icon: 'error',
        title: 'Connection Error',
        text: 'Unable to create task. Please check your connection and try again.',
        confirmButtonColor: '#3b82f6'
      });
    });
}

function initializeCharts() {
  // Hours Spent Chart
  const hoursCtx = document.getElementById("hoursChart");
  if (hoursCtx) {
    new Chart(hoursCtx, {
      type: "doughnut",
      data: {
        labels: ["Study", "Online Test"],
        datasets: [
          {
            data: [75, 25],
            backgroundColor: ["#3b82f6", "#e5e7eb"],
            borderWidth: 0,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
        },
      },
    });
  }

  // Performance Gauge Chart
  const performanceCtx = document.getElementById("performanceGauge");
  if (performanceCtx) {
    new Chart(performanceCtx, {
      type: "doughnut",
      data: {
        datasets: [
          {
            data: [89.66, 10.34],
            backgroundColor: ["#10b981", "#e5e7eb"],
            borderWidth: 0,
            circumference: 180,
            rotation: 270,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
        },
      },
    });
  }
}

// Achievement badge hover effects
document.addEventListener("DOMContentLoaded", function () {
  const achievementBadges = document.querySelectorAll(".achievement-badge");
  achievementBadges.forEach((badge) => {
    badge.addEventListener("mouseenter", function () {
      this.style.backgroundColor = "#f3f4f6";
    });

    badge.addEventListener("mouseleave", function () {
      this.style.backgroundColor = "transparent";
    });
  });

  // Streak day hover effects
  const streakDays = document.querySelectorAll(".streak-day");
  streakDays.forEach((day) => {
    day.addEventListener("mouseenter", function () {
      this.style.transform = "scale(1.1)";
    });

    day.addEventListener("mouseleave", function () {
      this.style.transform = "scale(1)";
    });
  });
});
