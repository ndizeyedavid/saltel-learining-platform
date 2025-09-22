// Progress Page Functionality with Charts
document.addEventListener("DOMContentLoaded", function () {
  // Initialize Charts
  initializeProgressChart();
  initializeCompletionChart();
  initializeAnimations();

  function initializeProgressChart() {
    const ctx = document.getElementById("progressChart").getContext("2d");
    
    new Chart(ctx, {
      type: "line",
      data: {
        labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
        datasets: [
          {
            label: "Hours Studied",
            data: [2, 3, 1, 4, 2, 5, 3],
            borderColor: "#3B82F6",
            backgroundColor: "rgba(59, 130, 246, 0.1)",
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: "#3B82F6",
            pointBorderColor: "#ffffff",
            pointBorderWidth: 2,
            pointRadius: 6,
          },
          {
            label: "Lessons Completed",
            data: [1, 2, 1, 3, 1, 4, 2],
            borderColor: "#10B981",
            backgroundColor: "rgba(16, 185, 129, 0.1)",
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: "#10B981",
            pointBorderColor: "#ffffff",
            pointBorderWidth: 2,
            pointRadius: 6,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "top",
            labels: {
              usePointStyle: true,
              padding: 20,
            },
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: "rgba(0, 0, 0, 0.1)",
            },
          },
          x: {
            grid: {
              display: false,
            },
          },
        },
        elements: {
          point: {
            hoverRadius: 8,
          },
        },
      },
    });
  }

  function initializeCompletionChart() {
    const ctx = document.getElementById("completionChart").getContext("2d");
    
    new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: ["Completed", "In Progress", "Not Started"],
        datasets: [
          {
            data: [67, 25, 8],
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
    const progressBars = document.querySelectorAll(".h-2.bg-blue-600, .h-2.bg-purple-600, .h-2.bg-green-600, .h-2.bg-red-600");
    
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
  const courseCards = document.querySelectorAll(".flex.items-center.justify-between.p-4.border");
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
  const achievementCards = document.querySelectorAll(".flex.items-center.space-x-4.p-4.bg-green-50, .flex.items-center.space-x-4.p-4.bg-blue-50, .flex.items-center.space-x-4.p-4.bg-purple-50");
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
        title: 'Achievement Details',
        html: `<strong>${title}</strong><br><br>${description}`,
        icon: 'success',
        confirmButtonText: 'Awesome!',
        confirmButtonColor: '#10B981'
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
        position: 'top-end',
        icon: 'info',
        title: `Showing progress for: ${period}`,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
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
