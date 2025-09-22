// Courses Page Functionality
document.addEventListener("DOMContentLoaded", function () {
  // Global variables
  let currentPage = 1;
  let itemsPerPage = 6;
  let filteredCourses = [];
  let allCourses = [];

  // Initialize all functionality
  initializeFilters();
  initializePagination();
  initializeEnrollButtons();

  function initializeFilters() {
    const courseFilter = document.getElementById("courseFilter");
    const categoryFilter = document.getElementById("categoryFilter");
    const levelButtons = document.querySelectorAll(".filter-btn");

    // Get all course cards
    allCourses = Array.from(document.querySelectorAll(".course-card"));
    filteredCourses = [...allCourses];

    // Search filter
    courseFilter.addEventListener("input", function () {
      applyFilters();
    });

    // Category filter
    categoryFilter.addEventListener("change", function () {
      applyFilters();
    });

    // Level filter buttons
    levelButtons.forEach((button) => {
      button.addEventListener("click", function () {
        // Remove active class from all buttons
        levelButtons.forEach((btn) => {
          btn.classList.remove("active", "text-blue-600", "bg-blue-50");
          btn.classList.add("text-gray-600");
        });

        // Add active class to clicked button
        this.classList.add("active", "text-blue-600", "bg-blue-50");
        this.classList.remove("text-gray-600");

        applyFilters();
      });
    });
  }

  function applyFilters() {
    const searchTerm = document
      .getElementById("courseFilter")
      .value.toLowerCase();
    const selectedCategory = document.getElementById("categoryFilter").value;
    const selectedLevel =
      document.querySelector(".filter-btn.active").dataset.filter;

    filteredCourses = allCourses.filter((course) => {
      const title = course.querySelector("h3").textContent.toLowerCase();
      const description = course.querySelector("p").textContent.toLowerCase();
      const category = course.dataset.category;
      const level = course.dataset.level;

      // Search filter
      const matchesSearch =
        searchTerm === "" ||
        title.includes(searchTerm) ||
        description.includes(searchTerm);

      // Category filter
      const matchesCategory =
        selectedCategory === "" || category === selectedCategory;

      // Level filter
      const matchesLevel = selectedLevel === "all" || level === selectedLevel;

      return matchesSearch && matchesCategory && matchesLevel;
    });

    currentPage = 1;
    updatePagination();
    displayCourses();
  }

  function initializePagination() {
    const itemsPerPageSelect = document.getElementById("itemsPerPage");
    const prevButton = document.getElementById("prevPage");
    const nextButton = document.getElementById("nextPage");

    itemsPerPageSelect.addEventListener("change", function () {
      itemsPerPage = parseInt(this.value);
      currentPage = 1;
      updatePagination();
      displayCourses();
    });

    prevButton.addEventListener("click", function () {
      if (currentPage > 1) {
        currentPage--;
        updatePagination();
        displayCourses();
      }
    });

    nextButton.addEventListener("click", function () {
      const totalPages = Math.ceil(filteredCourses.length / itemsPerPage);
      if (currentPage < totalPages) {
        currentPage++;
        updatePagination();
        displayCourses();
      }
    });

    // Initialize pagination
    updatePagination();
    displayCourses();
  }

  function updatePagination() {
    const totalPages = Math.ceil(filteredCourses.length / itemsPerPage);
    const prevButton = document.getElementById("prevPage");
    const nextButton = document.getElementById("nextPage");
    const paginationContainer = document.getElementById("pagination");

    // Update prev/next buttons
    prevButton.disabled = currentPage === 1;
    nextButton.disabled = currentPage === totalPages || totalPages === 0;

    // Clear existing page buttons
    const existingPageButtons =
      paginationContainer.querySelectorAll(".page-btn");
    existingPageButtons.forEach((btn) => btn.remove());

    // Create new page buttons
    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

    if (endPage - startPage + 1 < maxVisiblePages) {
      startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
      const pageButton = document.createElement("button");
      pageButton.className = `px-3 py-2 text-sm font-medium border page-btn ${
        i === currentPage
          ? "text-white bg-blue-600 border-blue-600 hover:bg-blue-700"
          : "text-gray-700 bg-white border-gray-300 hover:bg-gray-50"
      }`;
      pageButton.textContent = i;
      pageButton.dataset.page = i;

      pageButton.addEventListener("click", function () {
        currentPage = parseInt(this.dataset.page);
        updatePagination();
        displayCourses();
      });

      // Insert before next button
      paginationContainer.insertBefore(pageButton, nextButton);
    }
  }

  function displayCourses() {
    const coursesGrid = document.getElementById("coursesGrid");

    // Hide all courses
    allCourses.forEach((course) => {
      course.style.display = "none";
    });

    // Calculate which courses to show
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const coursesToShow = filteredCourses.slice(startIndex, endIndex);

    // Show filtered courses
    coursesToShow.forEach((course) => {
      course.style.display = "block";
    });

    // Smooth scroll to top of courses grid
    coursesGrid.scrollIntoView({ behavior: "smooth", block: "start" });
  }

  function initializeEnrollButtons() {
    const enrollButtons = document.querySelectorAll(".enroll-btn");

    enrollButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const courseCard = this.closest(".course-card");
        const courseTitle = courseCard.querySelector("h3").textContent;
        const coursePrice = courseCard.querySelector(
          ".text-lg.font-semibold"
        ).textContent;

        // Add loading state
        const originalText = this.textContent;
        this.textContent = "Enrolling...";
        this.disabled = true;

        // Simulate enrollment process
        setTimeout(() => {
          this.textContent = "Enrolled ✓";
          this.classList.remove("bg-blue-600", "hover:bg-blue-700");
          this.classList.add("bg-green-600", "cursor-not-allowed");

          // Show success notification
          if (typeof toastr !== "undefined") {
            toastr.success(
              `Successfully enrolled in "${courseTitle}" for ${coursePrice}!`,
              "Enrollment Complete"
            );
          } else {
            alert(
              `Successfully enrolled in "${courseTitle}" for ${coursePrice}!`
            );
          }
        }, 1500);
      });
    });
  }
});
