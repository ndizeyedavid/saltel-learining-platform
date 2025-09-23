document.addEventListener("DOMContentLoaded", function () {
  // Get dropdown elements
  const notificationBtn = document.getElementById("notificationBtn");
  const notificationDropdown = document.getElementById("notificationDropdown");
  const profileBtn = document.getElementById("profileBtn");
  const profileDropdown = document.getElementById("profileDropdown");
  const profileArrow = profileBtn?.querySelector("i");

  // Add animation classes
  notificationDropdown?.classList.add("dropdown-enter");
  profileDropdown?.classList.add("dropdown-enter");
  notificationBtn?.querySelector("span")?.classList.add("notification-dot");

  // Toggle notification dropdown
  notificationBtn?.addEventListener("click", function (e) {
    e.stopPropagation();
    toggleDropdown(notificationDropdown, profileDropdown);
    profileArrow?.classList.remove("open");
  });

  // Toggle profile dropdown
  profileBtn?.addEventListener("click", function (e) {
    e.stopPropagation();
    toggleDropdown(profileDropdown, notificationDropdown);
    profileArrow?.classList.toggle("open");
  });

  function toggleDropdown(targetDropdown, otherDropdown) {
    if (targetDropdown.classList.contains("hidden")) {
      // Hide other dropdown first
      otherDropdown.classList.add("hidden");
      otherDropdown.classList.remove("show");

      // Show target dropdown with animation
      targetDropdown.classList.remove("hidden");
      requestAnimationFrame(() => {
        targetDropdown.classList.add("show");
      });
    } else {
      // Hide target dropdown with animation
      targetDropdown.classList.remove("show");
      setTimeout(() => {
        targetDropdown.classList.add("hidden");
      }, 200); // Match transition duration
    }
  }

  // Close dropdowns when clicking outside
  document.addEventListener("click", function (e) {
    if (
      !notificationBtn?.contains(e.target) &&
      !notificationDropdown?.contains(e.target)
    ) {
      notificationDropdown?.classList.add("hidden");
    }
    if (
      !profileBtn?.contains(e.target) &&
      !profileDropdown?.contains(e.target)
    ) {
      profileDropdown?.classList.add("hidden");
    }
  });

  // Close dropdowns when pressing escape
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      notificationDropdown?.classList.add("hidden");
      profileDropdown?.classList.add("hidden");
    }
  });

  // Mark notifications as read when clicking
  const notificationItems = notificationDropdown?.querySelectorAll("a");
  notificationItems?.forEach((item) => {
    item.addEventListener("click", function () {
      // Here you would typically make an API call to mark the notification as read
      this.classList.add("opacity-50");
    });
  });
});
