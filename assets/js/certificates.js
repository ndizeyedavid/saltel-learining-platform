// Certificates Page Functionality
document.addEventListener("DOMContentLoaded", function () {
  // Global variables
  let allCertificates = [];
  let filteredCertificates = [];

  // Initialize all functionality
  initializeFilters();
  initializeCertificateActions();
  initializeModal();

  function initializeFilters() {
    const categoryFilter = document.getElementById("categoryFilter");
    const yearFilter = document.getElementById("yearFilter");

    // Get all certificate cards
    allCertificates = Array.from(
      document.querySelectorAll(".certificate-card")
    );
    filteredCertificates = [...allCertificates];

    // Category filter
    categoryFilter.addEventListener("change", function () {
      applyFilters();
    });

    // Year filter
    yearFilter.addEventListener("change", function () {
      applyFilters();
    });
  }

  function applyFilters() {
    const selectedCategory = document.getElementById("categoryFilter").value;
    const selectedYear = document.getElementById("yearFilter").value;

    filteredCertificates = allCertificates.filter((certificate) => {
      const category = certificate.dataset.category;
      const year = certificate.dataset.year;

      // Category filter
      const matchesCategory =
        selectedCategory === "" || category === selectedCategory;

      // Year filter
      const matchesYear = selectedYear === "" || year === selectedYear;

      return matchesCategory && matchesYear;
    });

    displayCertificates();
  }

  function displayCertificates() {
    // Hide all certificates
    allCertificates.forEach((certificate) => {
      certificate.style.display = "none";
    });

    // Show filtered certificates
    filteredCertificates.forEach((certificate) => {
      certificate.style.display = "block";
    });

    // Show message if no certificates found
    const grid = document.getElementById("certificatesGrid");
    const existingMessage = document.getElementById("noCertificatesMessage");

    if (filteredCertificates.length === 0) {
      if (!existingMessage) {
        const message = document.createElement("div");
        message.id = "noCertificatesMessage";
        message.className = "py-12 text-center col-span-full";
        message.innerHTML = `
          <div class="text-gray-400">
            <i class="text-4xl mb-4 fas fa-certificate"></i>
            <p class="text-lg font-medium text-gray-600">No certificates found</p>
            <p class="text-sm text-gray-500">Try adjusting your filters</p>
          </div>
        `;
        grid.appendChild(message);
      }
    } else if (existingMessage) {
      existingMessage.remove();
    }
  }

  function initializeCertificateActions() {
    // Handle locked certificate clicks
    document.addEventListener("click", function (e) {
      if (e.target.closest(".locked-certificate")) {
        const card = e.target.closest(".locked-certificate");
        const requirement = card.dataset.unlockRequirement;

        showLockedCertificateModal(requirement);
        return;
      }
    });

    // View certificate buttons
    document.addEventListener("click", function (e) {
      if (e.target.closest(".view-btn")) {
        const card = e.target.closest(".certificate-card");

        // Check if certificate is locked
        if (card.classList.contains("locked-certificate")) {
          return; // Prevent action on locked certificates
        }

        const courseName = card.querySelector("h4").textContent;
        const completionDate = card
          .querySelector("p")
          .textContent.replace("Completed on ", "");

        showCertificateModal(courseName, completionDate);
      }
    });

    // Download certificate buttons
    document.addEventListener("click", function (e) {
      if (e.target.closest(".download-btn")) {
        const card = e.target.closest(".certificate-card");

        // Check if certificate is locked
        if (card.classList.contains("locked-certificate")) {
          return; // Prevent action on locked certificates
        }

        const courseName = card.querySelector("h4").textContent;

        downloadCertificate(courseName);
      }
    });

    // Download all certificates button
    document
      .getElementById("downloadAllBtn")
      .addEventListener("click", function () {
        downloadAllCertificates();
      });
  }

  function initializeModal() {
    const modal = document.getElementById("certificateModal");
    const closeBtn = document.getElementById("closeModal");
    const downloadBtn = document.getElementById("downloadModalBtn");
    const shareBtn = document.getElementById("shareModalBtn");
    const verifyBtn = document.getElementById("verifyModalBtn");

    // Close modal
    closeBtn.addEventListener("click", function () {
      hideModal();
    });

    // Close modal when clicking outside
    modal.addEventListener("click", function (e) {
      if (e.target === modal) {
        hideModal();
      }
    });

    // Download from modal
    downloadBtn.addEventListener("click", function () {
      const courseName = document.getElementById("modalCourseName").textContent;
      downloadCertificate(courseName);
    });

    // Share certificate
    shareBtn.addEventListener("click", function () {
      shareCertificate();
    });

    // Verify certificate
    verifyBtn.addEventListener("click", function () {
      verifyCertificate();
    });

    // Close modal with Escape key
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && !modal.classList.contains("hidden")) {
        hideModal();
      }
    });
  }

  function showCertificateModal(courseName, completionDate) {
    const modal = document.getElementById("certificateModal");
    const courseNameEl = document.getElementById("modalCourseName");
    const dateEl = document.getElementById("modalDate");
    const certificateIdEl = document.getElementById("certificateId");

    // Update certificate content
    courseNameEl.textContent = courseName;
    dateEl.textContent = completionDate;

    // Generate unique certificate ID
    const certificateId = generateCertificateId(courseName);
    certificateIdEl.textContent = certificateId;

    modal.classList.remove("hidden");
    modal.classList.add("flex");

    // Prevent body scroll
    document.body.style.overflow = "hidden";

    // Add entrance animation
    const certificateContent = modal.querySelector(".border-8");
    certificateContent.style.transform = "scale(0.9)";
    certificateContent.style.opacity = "0";

    setTimeout(() => {
      certificateContent.style.transition = "all 0.3s ease";
      certificateContent.style.transform = "scale(1)";
      certificateContent.style.opacity = "1";
    }, 100);
  }

  function hideModal() {
    const modal = document.getElementById("certificateModal");
    modal.classList.add("hidden");
    modal.classList.remove("flex");

    // Restore body scroll
    document.body.style.overflow = "";
  }

  function downloadCertificate(courseName) {
    // Simulate certificate download
    const button = event.target.closest("button");
    const originalText = button.innerHTML;

    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;

    setTimeout(() => {
      button.innerHTML = originalText;
      button.disabled = false;

      // Show success notification
      Swal.fire({
        title: 'Download Complete!',
        text: `Certificate for "${courseName}" downloaded successfully!`,
        icon: 'success',
        confirmButtonText: 'Great!',
        confirmButtonColor: '#10B981'
      });

      // In a real application, you would trigger the actual download here
      // For demo purposes, we'll just show the notification
    }, 2000);
  }

  function downloadAllCertificates() {
    const button = document.getElementById("downloadAllBtn");
    const originalText = button.innerHTML;

    button.innerHTML =
      '<i class="fas fa-spinner fa-spin mr-2"></i>Preparing...';
    button.disabled = true;

    setTimeout(() => {
      button.innerHTML = originalText;
      button.disabled = false;

      // Show success notification
      Swal.fire({
        title: 'Download Complete!',
        text: 'All certificates downloaded as ZIP file!',
        icon: 'success',
        confirmButtonText: 'Awesome!',
        confirmButtonColor: '#10B981'
      });
    }, 3000);
  }

  // Add hover effects to certificate cards
  const certificateCards = document.querySelectorAll(".certificate-card");
  certificateCards.forEach((card) => {
    card.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-4px)";
      this.style.transition = "all 0.3s ease";
    });

    card.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0)";
    });
  });

  // Animate stats on page load
  const statNumbers = document.querySelectorAll(".text-2xl.font-bold");
  statNumbers.forEach((stat) => {
    const finalValue = parseInt(stat.textContent);
    let currentValue = 0;
    const increment = finalValue / 30;

    const timer = setInterval(() => {
      currentValue += increment;
      if (currentValue >= finalValue) {
        currentValue = finalValue;
        clearInterval(timer);
      }
      stat.textContent = Math.floor(currentValue);
    }, 50);
  });

  // Generate unique certificate ID
  function generateCertificateId(courseName) {
    const courseCode =
      courseName.replace(/[^A-Z]/g, "").substring(0, 3) || "GEN";
    const year = new Date().getFullYear();
    const randomNum = Math.floor(Math.random() * 999999)
      .toString()
      .padStart(6, "0");
    return `SLT-${year}-${courseCode}${randomNum}`;
  }

  // Share certificate function
  function shareCertificate() {
    const courseName = document.getElementById("modalCourseName").textContent;
    const certificateId = document.getElementById("certificateId").textContent;

    if (navigator.share) {
      navigator
        .share({
          title: `Certificate of Achievement - ${courseName}`,
          text: `I've completed ${courseName} and earned my certificate!`,
          url: `https://saltel.edu/verify/${certificateId}`,
        })
        .catch(console.error);
    } else {
      // Fallback for browsers that don't support Web Share API
      const shareUrl = `https://saltel.edu/verify/${certificateId}`;
      navigator.clipboard.writeText(shareUrl).then(() => {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          title: 'Certificate link copied to clipboard!',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true
        });
      });
    }
  }

  // Verify certificate function
  function verifyCertificate() {
    const certificateId = document.getElementById("certificateId").textContent;
    const button = document.getElementById("verifyModalBtn");
    const originalText = button.innerHTML;

    button.innerHTML =
      '<i class="fas fa-spinner fa-spin mr-2"></i>Verifying...';
    button.disabled = true;

    // Simulate verification process
    setTimeout(() => {
      button.innerHTML = originalText;
      button.disabled = false;

      Swal.fire({
        title: 'Verification Complete!',
        text: `Certificate ${certificateId} is authentic and verified!`,
        icon: 'success',
        confirmButtonText: 'Excellent!',
        confirmButtonColor: '#10B981'
      });
    }, 2000);
  }

  // Add locked certificate modal function
  function showLockedCertificateModal(requirement) {
    Swal.fire({
      title: '🔒 Certificate Locked',
      text: requirement,
      icon: 'info',
      confirmButtonText: 'Understood',
      confirmButtonColor: '#3b82f6'
    });
  }

  // Initialize with all certificates visible
  displayCertificates();
});
