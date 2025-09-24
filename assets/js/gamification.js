// Gamification System
document.addEventListener("DOMContentLoaded", function () {
  initializeGamification();

  function initializeGamification() {
    // Handle locked course clicks
    document.addEventListener("click", function (e) {
      if (e.target.closest(".locked-course")) {
        const card = e.target.closest(".locked-course");
        const requirement = card.dataset.unlockRequirement;
        const xpReward = card.dataset.xpReward || "100";
        const badgeReward = card.dataset.badgeReward || "Course Completion";

        showUnlockModal(requirement, xpReward, badgeReward);
      }
    });

    // Add hover effects to gamification cards
    addGamificationHoverEffects();
  }

  function showUnlockModal(requirement, xpReward, badgeReward) {
    Swal.fire({
      title: "🔒 Course Locked",
      html: `
        <div class="text-left">
          <p class="mb-3 text-gray-700">${requirement}</p>
          <div class="p-3 bg-blue-50 rounded-lg">
            <p class="text-sm font-medium text-blue-800">🏆 Unlock Rewards:</p>
            <ul class="mt-1 text-sm text-blue-700">
              <li>• +${xpReward} XP Points</li>
              <li>• ${badgeReward} Badge</li>
            </ul>
          </div>
        </div>
      `,
      icon: "info",
      confirmButtonText: "Got it!",
      confirmButtonColor: "#3b82f6",
      customClass: {
        popup: "text-left",
      },
    });
  }

  function animateCounter(element, finalValue, suffix) {
    let currentValue = 0;
    const increment = finalValue / 50;
    const timer = setInterval(() => {
      currentValue += increment;
      if (currentValue >= finalValue) {
        currentValue = finalValue;
        clearInterval(timer);
      }
      element.textContent =
        Math.floor(currentValue) + (suffix ? " " + suffix : "");
    }, 40);
  }

  function addGamificationHoverEffects() {
    const gamificationCards = document.querySelectorAll(".bg-gradient-to-br");
    gamificationCards.forEach((card) => {
      card.addEventListener("mouseenter", function () {
        this.style.transform = "translateY(-2px) scale(1.02)";
        this.style.transition = "all 0.3s ease";
      });

      card.addEventListener("mouseleave", function () {
        this.style.transform = "translateY(0) scale(1)";
      });
    });
  }

  // Unlock notification system
  function showUnlockNotification(courseName, xpReward) {
    Swal.fire({
      title: "New Content Available!",
      text: `🎉 ${courseName} unlocked! +${xpReward} XP`,
      icon: "success",
      timer: 4000,
      showConfirmButton: false,
      toast: true,
      position: "top-end",
    });
  }

  // Simulate unlocking content (for demo purposes)
  function simulateUnlock(contentType, contentName, xpReward) {
    showUnlockNotification(contentName, xpReward);

    // Update XP counter
    const xpElement = document.querySelector(".text-2xl.font-bold");
    if (xpElement && xpElement.textContent.includes("XP")) {
      const currentXP = parseInt(xpElement.textContent.replace(/[^\d]/g, ""));
      const newXP = currentXP + parseInt(xpReward);
      animateCounter(xpElement, newXP, "XP");
    }
  }

  // Make functions globally available for demo purposes
  window.gamification = {
    simulateUnlock: simulateUnlock,
    showUnlockNotification: showUnlockNotification,
  };
});
