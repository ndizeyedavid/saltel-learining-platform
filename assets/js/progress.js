// Progress Page Functionality with Charts
document.addEventListener("DOMContentLoaded", function () {
  // Get data from PHP
  const data = window.progressData || {};

  // Initialize Charts
  initializeActivityChart();
  initializeCompletionChart();
  // initializeAnimations();

  function initializeActivityChart() {
    const ctx = document.getElementById("activityChart").getContext("2d");

    // Process activity breakdown data
    const activityData = data.activityBreakdown || [];

    if (activityData.length === 0) {
      // Show empty state
      ctx.font = "16px Arial";
      ctx.fillStyle = "#9CA3AF";
      ctx.textAlign = "center";
      ctx.fillText(
        "No activity data available",
        ctx.canvas.width / 2,
        ctx.canvas.height / 2
      );
      ctx.fillText(
        "Start learning to see your progress!",
        ctx.canvas.width / 2,
        ctx.canvas.height / 2 + 25
      );
      return;
    }

    const labels = activityData.map((item) => item.description);
    const xpValues = activityData.map((item) => parseInt(item.total_xp));
    const activityCounts = activityData.map((item) =>
      parseInt(item.activity_count)
    );

    // Color scheme for different activities
    const colors = [
      "#3B82F6", // Blue
      "#10B981", // Green
      "#F59E0B", // Yellow
      "#EF4444", // Red
      "#8B5CF6", // Purple
      "#06B6D4", // Cyan
      "#84CC16", // Lime
      "#F97316", // Orange
    ];

    new Chart(ctx, {
      type: "bar",
      data: {
        labels: labels,
        datasets: [
          {
            label: "XP Earned",
            data: xpValues,
            backgroundColor: colors.slice(0, labels.length),
            borderColor: colors.slice(0, labels.length),
            borderWidth: 1,
            borderRadius: 8,
            borderSkipped: false,
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
          tooltip: {
            callbacks: {
              label: function (context) {
                const activityCount = activityCounts[context.dataIndex];
                return [
                  `XP Earned: ${context.parsed.y}`,
                  `Activities: ${activityCount}`,
                ];
              },
            },
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: "rgba(0, 0, 0, 0.1)",
            },
            title: {
              display: true,
              text: "XP Points",
            },
          },
          x: {
            grid: {
              display: false,
            },
            ticks: {
              maxRotation: 45,
              minRotation: 0,
            },
          },
        },
        elements: {
          bar: {
            borderRadius: 4,
          },
        },
      },
    });
  }

  function initializeCompletionChart() {
    const ctx = document.getElementById("completionChart").getContext("2d");

    const completionRate = data.completionRate || 0;
    const totalCourses = data.totalCourses || 0;
    const completedCourses = data.completedCourses || 0;

    const inProgressRate =
      totalCourses > 0
        ? Math.round(((totalCourses - completedCourses) / totalCourses) * 100)
        : 0;
    const notStartedRate = 100 - completionRate - inProgressRate;

    new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: ["Completed", "In Progress", "Not Started"],
        datasets: [
          {
            data: [completionRate, inProgressRate, Math.max(0, notStartedRate)],
            backgroundColor: ["#10B981", "#3B82F6", "#E5E7EB"],
            borderWidth: 0,
            cutout: "70%",
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
          tooltip: {
            callbacks: {
              label: function (context) {
                return context.label + ": " + context.parsed + "%";
              },
            },
          },
        },
      },
    });
  }

  function initializeAnimations() {
    // Animate progress bars on scroll
    const progressBars = document.querySelectorAll(
      ".h-2.bg-blue-600, .h-2.bg-purple-600, .h-2.bg-green-600, .h-2.bg-red-600"
    );

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const progressBar = entry.target;
          const width = progressBar.style.width;
          progressBar.style.width = "0%";
          progressBar.style.transition = "width 1.5s ease-in-out";

          setTimeout(() => {
            progressBar.style.width = width;
          }, 200);
        }
      });
    });

    progressBars.forEach((bar) => {
      observer.observe(bar);
    });

    // Animate stat cards
    const statCards = document.querySelectorAll(".text-2xl.font-bold");

    statCards.forEach((card) => {
      const finalValue = parseInt(card.textContent);
      let currentValue = 0;
      const increment = finalValue / 50;
      const timer = setInterval(() => {
        currentValue += increment;
        if (currentValue >= finalValue) {
          currentValue = finalValue;
          clearInterval(timer);
        }
        card.textContent = Math.floor(currentValue);
      }, 30);
    });
  }

  // Add hover effects to course progress cards
  const courseCards = document.querySelectorAll(
    ".flex.items-center.justify-between.p-4.border"
  );
  courseCards.forEach((card) => {
    card.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-2px)";
      this.style.transition = "all 0.3s ease";
      this.style.boxShadow = "0 4px 12px rgba(0, 0, 0, 0.1)";
    });

    card.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0)";
      this.style.boxShadow = "none";
    });
  });

  // Add click effects to achievement cards
  const achievementCards = document.querySelectorAll(
    ".flex.items-center.space-x-4.p-4.bg-green-50, .flex.items-center.space-x-4.p-4.bg-blue-50, .flex.items-center.space-x-4.p-4.bg-purple-50"
  );
  achievementCards.forEach((card) => {
    card.addEventListener("click", function () {
      // Add pulse animation
      this.style.animation = "pulse 0.6s ease-in-out";

      setTimeout(() => {
        this.style.animation = "";
      }, 600);

      // Show achievement details
      const title = this.querySelector("h4").textContent;
      const description = this.querySelector("p").textContent;

      Swal.fire({
        title: "Achievement Details",
        html: `<strong>${title}</strong><br><br>${description}`,
        icon: "success",
        confirmButtonText: "Awesome!",
        confirmButtonColor: "#10B981",
      });
    });
  });

  // Time period selector functionality
  const timeSelector = document.querySelector("select");
  if (timeSelector) {
    timeSelector.addEventListener("change", function () {
      const period = this.value;

      // Update chart data based on selected period
      Swal.fire({
        toast: true,
        position: "top-end",
        icon: "info",
        title: `Showing progress for: ${period}`,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
      });

      // Here you would typically fetch new data and update the chart
      // For demo purposes, we'll just show a notification
    });
  }

  // Add CSS for pulse animation
  const style = document.createElement("style");
  style.textContent = `
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }
  `;
  document.head.appendChild(style);
});
