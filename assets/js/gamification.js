// Gamification System
document.addEventListener("DOMContentLoaded", function () {
  initializeGamification();

  function initializeGamification() {
    // Handle locked course clicks
    document.addEventListener("click", function (e) {
      if (e.target.closest(".locked-course")) {
        const card = e.target.closest(".locked-course");
        const requirement = card.dataset.unlockRequirement;
        
        showUnlockModal(requirement);
      }
    });

    // XP animations
    animateXPProgress();
  }

  function showUnlockModal(requirement) {
    if (typeof toastr !== "undefined") {
      toastr.info(`🔒 ${requirement}`, "Course Locked", {
        timeOut: 5000,
        extendedTimeOut: 2000
      });
    }
  }

  function animateXPProgress() {
    const xpBar = document.querySelector(".bg-yellow-400");
    if (xpBar) {
      xpBar.style.width = "0%";
      setTimeout(() => {
        xpBar.style.transition = "width 2s ease-in-out";
        xpBar.style.width = "78%";
      }, 500);
    }
  }

  // Unlock notification system
  function showUnlockNotification(courseName, xpReward) {
    if (typeof toastr !== "undefined") {
      toastr.success(`🎉 ${courseName} unlocked! +${xpReward} XP`, "New Content Available!");
    }
  }
});
