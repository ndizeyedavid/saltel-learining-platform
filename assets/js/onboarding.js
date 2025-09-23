// Saltel Learning Platform Onboarding System
class SaltelOnboarding {
  constructor() {
    this.hasCompletedOnboarding =
      localStorage.getItem("saltel_onboarding_completed") === "true";
    this.currentPage = this.getCurrentPage();
    this.init();
  }

  init() {
    // Wait for page to be fully loaded
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", () => {
        this.checkAndStartOnboarding();
      });
    } else {
      this.checkAndStartOnboarding();
    }
  }

  getCurrentPage() {
    const path = window.location.pathname;
    if (path.includes("index.php") || path.endsWith("/trainee/")) {
      return "dashboard";
    } else if (path.includes("courses.php")) {
      return "courses";
    } else if (path.includes("profile.php")) {
      return "profile";
    } else if (path.includes("settings.php")) {
      return "settings";
    }
    return "dashboard"; // default
  }

  checkAndStartOnboarding() {
    // Only start onboarding on dashboard and if not completed
    if (!this.hasCompletedOnboarding && this.currentPage === "dashboard") {
      // Small delay to ensure all elements are rendered
      setTimeout(() => {
        this.startDashboardTour();
      }, 1000);
    }
  }

  startDashboardTour() {
    const tour = introJs();

    tour.setOptions({
      steps: [
        {
          title: "🎉 Welcome to Saltel Learning!",
          intro:
            "Let's take a quick tour to help you get started with your learning journey.",
          position: "center",
        },
        {
          element: ".sidebar",
          title: "📚 Navigation Menu",
          intro:
            "Use this sidebar to navigate between different sections like Courses, Assignments, and your Profile.",
          position: "right",
        },
        {
          element: '[href="courses.php"]',
          title: "🎓 Your Courses",
          intro:
            "Browse and enroll in available courses here. Track your progress and continue learning.",
          position: "right",
        },
        {
          element: '[href="assignments.php"]',
          title: "📝 Assignments",
          intro:
            "View and complete your assignments. Submit your work and track deadlines.",
          position: "right",
        },
        {
          element: '[href="progress.php"]',
          title: "📊 Progress Tracking",
          intro:
            "Monitor your learning progress, view statistics, and see your achievements.",
          position: "right",
        },
        {
          element: '[href="certificates.php"]',
          title: "🏆 Certificates",
          intro: "Download and view your earned certificates and badges.",
          position: "right",
        },
        {
          element: ".stats-card, .bg-white.rounded-xl.shadow-sm",
          title: "📈 Dashboard Stats",
          intro:
            "Keep track of your learning statistics - completed courses, study hours, and achievements.",
          position: "top",
        },
        {
          element: "#userMenuBtn",
          title: "🌙 Theme Switcher",
          intro:
            "Switch between light and dark modes for a comfortable learning experience.",
          position: "left",
        },
        {
          element: "#userMenuBtn",
          title: "👤 Your Profile",
          intro:
            "Manage your profile information, view your achievements, and track your learning journey.",
          position: "left",
        },
        {
          element: "#userMenuBtn",
          title: "⚙️ Settings",
          intro:
            "Customize your learning preferences, notifications, and account settings.",
          position: "left",
        },
        {
          title: "🚀 You're All Set!",
          intro:
            "You're ready to start your learning journey. Explore the platform and don't hesitate to reach out if you need help!",
          position: "center",
        },
      ],
      showProgress: true,
      showBullets: false,
      exitOnOverlayClick: false,
      exitOnEsc: true,
      nextLabel: "Next →",
      prevLabel: "← Back",
      skipLabel: "Skip",
      doneLabel: "Get Started! 🎯",
      tooltipClass: "saltel-tooltip",
      highlightClass: "saltel-highlight",
      overlayOpacity: 0.8,
      scrollToElement: true,
      scrollPadding: 30,
      disableInteraction: true,
    });

    // Event handlers
    tour.onbeforechange(() => {
      // Add custom animations or effects
      this.addStepAnimation();
    });

    tour.oncomplete(() => {
      this.completeOnboarding();
    });

    tour.onexit(() => {
      this.showSkipConfirmation();
    });

    // Start the tour
    tour.start();
  }

  addStepAnimation() {
    // Add subtle animation to highlighted elements
    const highlighted = document.querySelector(".saltel-highlight");
    if (highlighted) {
      highlighted.style.transition = "all 0.3s ease";
      highlighted.style.transform = "scale(1.02)";
      setTimeout(() => {
        if (highlighted) {
          highlighted.style.transform = "scale(1)";
        }
      }, 300);
    }
  }

  completeOnboarding() {
    localStorage.setItem("saltel_onboarding_completed", "true");
    this.hasCompletedOnboarding = true;

    // Show completion message
    if (typeof Swal !== "undefined") {
      Swal.fire({
        title: "🎉 Welcome Aboard!",
        text: "You've completed the onboarding tour. Happy learning!",
        icon: "success",
        confirmButtonText: "Start Learning",
        confirmButtonColor: "#3b82f6",
        timer: 5000,
        timerProgressBar: true,
      });
    }

    // Dispatch completion event
    window.dispatchEvent(new CustomEvent("onboardingCompleted"));
  }

  showSkipConfirmation() {
    if (typeof Swal !== "undefined") {
      Swal.fire({
        title: "Skip Onboarding?",
        text: "You can always restart the tour from Settings > Help.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Skip Tour",
        cancelButtonText: "Continue Tour",
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#3b82f6",
      }).then((result) => {
        if (result.isConfirmed) {
          this.completeOnboarding();
        } else {
          // Restart the tour
          setTimeout(() => {
            this.startDashboardTour();
          }, 500);
        }
      });
    }
  }

  // Method to manually start onboarding (for help/settings)
  static startTour() {
    const onboarding = new SaltelOnboarding();
    onboarding.hasCompletedOnboarding = false; // Force start
    onboarding.startDashboardTour();
  }

  // Method to reset onboarding
  static resetOnboarding() {
    localStorage.removeItem("saltel_onboarding_completed");
    if (typeof Swal !== "undefined") {
      Swal.fire({
        title: "Onboarding Reset",
        text: "The onboarding tour will show again on your next dashboard visit.",
        icon: "info",
        confirmButtonText: "OK",
      });
    }
  }

  // Method to check if user has completed onboarding
  static hasCompleted() {
    return localStorage.getItem("saltel_onboarding_completed") === "true";
  }
}

// Auto-initialize onboarding
// document.addEventListener("DOMContentLoaded", function () {
//   // Small delay to ensure preloader completes first
//   setTimeout(() => {
//     new SaltelOnboarding();
//   }, 2000);
// });

// Listen for preloader completion
window.addEventListener("preloaderComplete", function () {
  setTimeout(() => {
    new SaltelOnboarding();
  }, 300);
});

// Export for manual control
window.SaltelOnboarding = SaltelOnboarding;
