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

    // XP animations
    animateXPProgress();
    
    // Animate gamification stats on page load
    animateStats();
    
    // Add hover effects to gamification cards
    addGamificationHoverEffects();
  }

  function showUnlockModal(requirement, xpReward, badgeReward) {
    if (typeof toastr !== "undefined") {
      toastr.info(
        `🔒 ${requirement}<br>
         <small>🏆 Rewards: +${xpReward} XP, ${badgeReward} Badge</small>`, 
        "Course Locked", {
          timeOut: 6000,
          extendedTimeOut: 3000,
          positionClass: "toast-top-center",
          allowHtml: true
        }
      );
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

  function animateStats() {
    // Animate XP counter
    const xpElement = document.querySelector('.text-2xl.font-bold');
    if (xpElement && xpElement.textContent.includes('XP')) {
      animateCounter(xpElement, 2450, 'XP');
    }
    
    // Animate badges counter
    const badgeElements = document.querySelectorAll('.text-2xl.font-bold');
    badgeElements.forEach(element => {
      if (element.textContent === '12') {
        animateCounter(element, 12, '');
      }
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
      element.textContent = Math.floor(currentValue) + (suffix ? ' ' + suffix : '');
    }, 40);
  }

  function addGamificationHoverEffects() {
    const gamificationCards = document.querySelectorAll('.bg-gradient-to-br');
    gamificationCards.forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px) scale(1.02)';
        this.style.transition = 'all 0.3s ease';
      });
      
      card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
      });
    });
  }

  // Unlock notification system
  function showUnlockNotification(courseName, xpReward) {
    if (typeof toastr !== "undefined") {
      toastr.success(`🎉 ${courseName} unlocked! +${xpReward} XP`, "New Content Available!", {
        timeOut: 4000,
        positionClass: "toast-top-right"
      });
    }
  }

  // Simulate unlocking content (for demo purposes)
  function simulateUnlock(contentType, contentName, xpReward) {
    showUnlockNotification(contentName, xpReward);
    
    // Update XP counter
    const xpElement = document.querySelector('.text-2xl.font-bold');
    if (xpElement && xpElement.textContent.includes('XP')) {
      const currentXP = parseInt(xpElement.textContent.replace(/[^\d]/g, ''));
      const newXP = currentXP + parseInt(xpReward);
      animateCounter(xpElement, newXP, 'XP');
    }
  }

  // Make functions globally available for demo purposes
  window.gamification = {
    simulateUnlock: simulateUnlock,
    showUnlockNotification: showUnlockNotification
  };
});
