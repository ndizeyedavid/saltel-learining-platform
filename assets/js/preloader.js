// Gamified Preloader with XP System
class GamePreloader {
  constructor() {
    // Prevent multiple instances
    if (GamePreloader.instance) {
      return GamePreloader.instance;
    }
    GamePreloader.instance = this;

    this.currentXP = 0;
    this.targetXP = 100;
    this.currentLevel = 1;
    this.isLoading = false;
    this.loadingInterval = null;
    this.loadingMessages = [
      "Loading your learning adventure...",
      "Preparing course materials...",
      "Setting up achievements...",
      "Optimizing your experience...",
    ];
    this.achievements = [
      "Quick Learner Unlocked!",
      "Speed Reader Achievement!",
      "Knowledge Seeker Badge!",
      "Learning Streak Activated!",
    ];
    this.init();
  }

  init() {
    // Check if preloader already exists
    if (document.getElementById("gamePreloader")) {
      return;
    }

    this.createPreloader();
    this.startLoading();
    this.createParticles();
  }

  createPreloader() {
    const preloaderHTML = `
            <div id="gamePreloader" class="preloader">
                <!-- Floating Particles -->
                <div class="particles" id="particles"></div>
                
                <!-- Achievement Popup -->
                <div class="achievement-popup" id="achievementPopup">
                    <i class="fas fa-trophy"></i>
                    <span id="achievementText">Welcome Back, Learner!</span>
                </div>
                
                <!-- Logo -->
                <div class="preloader-logo">
                    <img src="../../assets/images/logo.png" alt="Saltel Logo" onerror="this.style.display='none'">
                </div>
                
                <!-- Main Content -->
                <div class="preloader-content">
                    <p class="preloader-subtitle">Preparing your personalized learning experience</p>
                    
                    <!-- Level Indicator -->
                    <div class="level-indicator">
                        <div class="level-badge">
                            <i class="fas fa-star"></i>
                            Level <span id="currentLevel">${
                              this.currentLevel
                            }</span>
                        </div>
                        <div class="xp-text">
                            <span id="currentXP">${
                              this.currentXP
                            }</span> / <span id="targetXP">${
      this.targetXP
    }</span> XP
                        </div>
                    </div>
                    
                    <!-- XP Progress Bar -->
                    <div class="progress-container">
                        <div class="xp-bar-container">
                            <div class="xp-bar" id="xpBar"></div>
                        </div>
                    </div>
                    
                    <!-- Loading Spinner -->
                    <div class="loading-spinner">
                        <div class="spinner-ring"></div>
                        <div class="spinner-ring"></div>
                        <div class="spinner-ring"></div>
                    </div>
                    
                    <!-- Loading Messages -->
                    <div class="loading-messages" id="loadingMessages">
                        ${this.loadingMessages
                          .map(
                            (msg) => `<div class="loading-message">${msg}</div>`
                          )
                          .join("")}
                    </div>
                </div>
            </div>
        `;

    document.body.insertAdjacentHTML("afterbegin", preloaderHTML);

    // Start loading message animation
    this.startMessageAnimation();
  }

  startMessageAnimation() {
    const messages = document.querySelectorAll(".loading-message");
    let currentIndex = 0;

    // Show first message immediately
    if (messages[0]) {
      messages[0].style.opacity = "1";
      messages[0].style.transform = "translateY(0)";
    }

    // Cycle through messages
    this.messageInterval = setInterval(() => {
      // Hide current message
      if (messages[currentIndex]) {
        messages[currentIndex].style.opacity = "0";
        messages[currentIndex].style.transform = "translateY(-30px)";
      }

      // Move to next message
      currentIndex = (currentIndex + 1) % messages.length;

      // Show next message after a brief delay
      setTimeout(() => {
        if (messages[currentIndex]) {
          messages[currentIndex].style.opacity = "1";
          messages[currentIndex].style.transform = "translateY(0)";
        }
      }, 300);
    }, 2000);
  }

  startLoading() {
    // Prevent multiple loading processes
    if (this.isLoading) {
      return;
    }
    this.isLoading = true;

    const xpBar = document.getElementById("xpBar");
    const currentXPElement = document.getElementById("currentXP");

    if (!xpBar || !currentXPElement) {
      console.error("Preloader elements not found");
      return;
    }

    let progress = 0;
    const increment = 2;
    const interval = 50;

    this.loadingInterval = setInterval(() => {
      progress += increment;
      this.currentXP = Math.min(progress, this.targetXP);

      // Update XP bar
      const percentage = (this.currentXP / this.targetXP) * 100;
      xpBar.style.width = percentage + "%";
      currentXPElement.textContent = this.currentXP;

      // Show achievement at certain milestones
      if (
        this.currentXP === 25 ||
        this.currentXP === 50 ||
        this.currentXP === 75
      ) {
        this.showAchievement();
      }

      // Complete loading
      if (this.currentXP >= this.targetXP) {
        clearInterval(this.loadingInterval);
        this.loadingInterval = null;
        setTimeout(() => {
          this.completeLoading();
        }, 800);
      }
    }, interval);
  }

  createParticles() {
    const particlesContainer = document.getElementById("particles");
    if (!particlesContainer) return;

    const particleCount = 20;

    for (let i = 0; i < particleCount; i++) {
      const particle = document.createElement("div");
      particle.className = "particle";
      particle.style.left = Math.random() * 100 + "%";
      particle.style.animationDelay = Math.random() * 6 + "s";
      particle.style.animationDuration = 6 + Math.random() * 4 + "s";
      particlesContainer.appendChild(particle);
    }
  }

  showAchievement() {
    const achievementPopup = document.getElementById("achievementPopup");
    const achievementText = document.getElementById("achievementText");

    const randomAchievement =
      this.achievements[Math.floor(Math.random() * this.achievements.length)];
    achievementText.textContent = randomAchievement;

    // Trigger animation
    achievementPopup.style.animation = "none";
    setTimeout(() => {
      achievementPopup.style.animation = "achievementSlide 3s ease-in-out";
    }, 10);
  }

  completeLoading() {
    if (!this.isLoading) return;

    const preloader = document.getElementById("gamePreloader");
    if (!preloader) return;

    // Clear any remaining intervals
    if (this.loadingInterval) {
      clearInterval(this.loadingInterval);
      this.loadingInterval = null;
    }

    if (this.messageInterval) {
      clearInterval(this.messageInterval);
      this.messageInterval = null;
    }

    // Add completion effects
    this.showCompletionEffects();

    // Fade out preloader
    setTimeout(() => {
      preloader.classList.add("fade-out");

      setTimeout(() => {
        preloader.remove();
        this.onLoadingComplete();
        this.cleanup();
      }, 800);
    }, 500);
  }

  cleanup() {
    this.isLoading = false;
    if (this.messageInterval) {
      clearInterval(this.messageInterval);
      this.messageInterval = null;
    }
    GamePreloader.instance = null;
  }

  showCompletionEffects() {
    // Show final achievement
    const achievementPopup = document.getElementById("achievementPopup");
    const achievementText = document.getElementById("achievementText");

    achievementText.innerHTML =
      '<i class="fas fa-check-circle"></i> Ready to Learn!';
    achievementPopup.style.background = "rgba(16, 185, 129, 0.95)";
    achievementPopup.style.animation = "achievementSlide 2s ease-in-out";
  }

  createSparkles() {
    const sparkleCount = 15;
    const preloader = document.getElementById("gamePreloader");

    for (let i = 0; i < sparkleCount; i++) {
      const sparkle = document.createElement("div");
      sparkle.innerHTML = "✨";
      sparkle.style.position = "absolute";
      sparkle.style.left = Math.random() * 100 + "%";
      sparkle.style.top = Math.random() * 100 + "%";
      sparkle.style.fontSize = "20px";
      sparkle.style.animation = "sparkle 1s ease-out forwards";
      sparkle.style.animationDelay = Math.random() * 0.5 + "s";
      sparkle.style.pointerEvents = "none";

      preloader.appendChild(sparkle);

      setTimeout(() => sparkle.remove(), 1500);
    }

    // Add sparkle animation
    if (!document.getElementById("sparkleStyles")) {
      const sparkleCSS = `
                <style id="sparkleStyles">
                    @keyframes sparkle {
                        0% {
                            opacity: 0;
                            transform: scale(0) rotate(0deg);
                        }
                        50% {
                            opacity: 1;
                            transform: scale(1.2) rotate(180deg);
                        }
                        100% {
                            opacity: 0;
                            transform: scale(0) rotate(360deg);
                        }
                    }
                </style>
            `;
      document.head.insertAdjacentHTML("beforeend", sparkleCSS);
    }
  }

  onLoadingComplete() {
    // Dispatch custom event for other scripts
    window.dispatchEvent(new CustomEvent("preloaderComplete"));

    // Initialize page-specific functionality
    if (typeof window.initializePage === "function") {
      window.initializePage();
    }
  }

  // Static method to initialize preloader
  static init() {
    // Prevent multiple initializations
    if (GamePreloader.isInitialized) {
      return;
    }
    GamePreloader.isInitialized = true;

    // Check if preloader already exists in DOM
    if (
      document.getElementById("gamePreloader") ||
      document.getElementById("simpleLoader")
    ) {
      return;
    }

    // Detect if this is initial load or navigation
    const isInitialLoad = GamePreloader.isInitialSiteLoad();

    if (isInitialLoad) {
      // Show full gamified preloader on initial load
      if (
        document.readyState === "loading" ||
        document.readyState === "interactive"
      ) {
        new GamePreloader();
      }
    } else {
      // Show simple loader for page navigation
      GamePreloader.showSimpleLoader();
    }
  }

  static isInitialSiteLoad() {
    // Check navigation type first
    // Type 0 = navigate (link click, address bar, bookmark)
    // Type 1 = reload (refresh, F5)
    // Type 2 = back_forward (back/forward buttons)
    
    if (performance.navigation) {
      // If it's a reload/refresh, show full preloader
      if (performance.navigation.type === 1) {
        return true;
      }
      
      // If it's back/forward navigation, show simple loader
      if (performance.navigation.type === 2) {
        return false;
      }
    }
    
    // For type 0 (navigate), check if first visit to site
    const hasVisited = sessionStorage.getItem("saltel_visited");
    
    if (!hasVisited) {
      sessionStorage.setItem("saltel_visited", "true");
      return true; // First visit - show full preloader
    }
    
    // Subsequent navigation within session - show simple loader
    return false;
  }

  static showSimpleLoader() {
    const simpleLoaderHTML = `
      <div id="simpleLoader" class="simple-loader">
        <div class="simple-loader-content">
          <div class="simple-spinner">
            <div class="spinner-ring"></div>
          </div>
          <p class="simple-loader-text">Loading...</p>
        </div>
      </div>
    `;

    document.body.insertAdjacentHTML("afterbegin", simpleLoaderHTML);

    // Auto-hide after short duration
    setTimeout(() => {
      const loader = document.getElementById("simpleLoader");
      if (loader) {
        loader.classList.add("fade-out");
        setTimeout(() => loader.remove(), 500);
      }
    }, 800);
  }

  static reset() {
    GamePreloader.isInitialized = false;
    GamePreloader.instance = null;
  }
}

// Initialize static properties
GamePreloader.instance = null;
GamePreloader.isInitialized = false;

// Auto-initialize preloader only once
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", function () {
    GamePreloader.init();
  });
} else {
  // DOM already loaded
  GamePreloader.init();
}

// Export for manual initialization if needed
window.GamePreloader = GamePreloader;
