// Certificates Page Functionality
document.addEventListener("DOMContentLoaded", function () {
  // Global variables
  let allCertificates = [];
  let filteredCertificates = [];

  // Initialize all functionality
  initializeFilters();
  initializeCertificateActions();
  initializeModal();
  loadCertificateData();

  function loadCertificateData() {
    // Get all certificate cards after page load
    setTimeout(() => {
      allCertificates = Array.from(
        document.querySelectorAll(".certificate-card")
      );
      filteredCertificates = [...allCertificates];
      
      // Populate filter options
      populateFilterOptions();
      
      // Initialize animations
      initializeAnimations();
    }, 100);
  }

  function populateFilterOptions() {
    const categoryFilter = document.getElementById("categoryFilter");
    const yearFilter = document.getElementById("yearFilter");
    
    if (!categoryFilter || !yearFilter) return;

    // Get unique categories and years from certificates
    const categories = new Set();
    const years = new Set();

    allCertificates.forEach(certificate => {
      const category = certificate.dataset.category;
      const year = certificate.dataset.year;
      
      if (category) categories.add(category);
      if (year) years.add(year);
    });

    // Clear existing options (except "All")
    categoryFilter.innerHTML = '<option value="">All Categories</option>';
    yearFilter.innerHTML = '<option value="">All Years</option>';

    // Add category options
    [...categories].sort().forEach(category => {
      const option = document.createElement('option');
      option.value = category;
      option.textContent = category;
      categoryFilter.appendChild(option);
    });

    // Add year options (newest first)
    [...years].sort((a, b) => b - a).forEach(year => {
      const option = document.createElement('option');
      option.value = year;
      option.textContent = year;
      yearFilter.appendChild(option);
    });
  }

  function initializeFilters() {
    const categoryFilter = document.getElementById("categoryFilter");
    const yearFilter = document.getElementById("yearFilter");

    if (!categoryFilter || !yearFilter) return;

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
    // Handle certificate view buttons
    document.addEventListener("click", function (e) {
      if (e.target.closest(".view-btn")) {
        const certificateCard = e.target.closest(".certificate-card");
        if (certificateCard) {
          viewCertificate(certificateCard);
        }
      }

      // Handle certificate download buttons
      if (e.target.closest(".download-btn")) {
        const certificateCard = e.target.closest(".certificate-card");
        if (certificateCard) {
          // Check if certificate is locked
          if (certificateCard.classList.contains("locked-certificate")) {
            return; // Prevent action on locked certificates
          }
          
          const courseName = certificateCard.querySelector("h4").textContent;
          const certificateId = certificateCard.dataset.certificateId;
          downloadCertificate(courseName, certificateId);
        }
      }
    });

    // Download all certificates button
    const downloadAllBtn = document.getElementById("downloadAllBtn");
    if (downloadAllBtn) {
      downloadAllBtn.addEventListener("click", function () {
        downloadAllCertificates();
      });
    }
  }

  function viewCertificate(certificateCard) {
    // Extract certificate data from the card
    const courseName = certificateCard.querySelector("h4").textContent;
    const completionDate = certificateCard.querySelector("p:nth-of-type(1)").textContent.replace("Completed on ", "");
    const certificateId = certificateCard.dataset.certificateId;
    const category = certificateCard.dataset.category;
    const ratingStars = certificateCard.querySelector(".text-yellow-400").innerHTML;
    const ratingText = certificateCard.querySelector(".text-gray-600").textContent;

    showCertificateModal(courseName, completionDate, certificateId, category, ratingStars, ratingText);
  }

  function initializeModal() {
    const modal = document.getElementById("certificateModal");
    const closeBtn = document.getElementById("closeCertificateModal");
    const downloadBtn = document.getElementById("downloadCertificateBtn");
    const shareBtn = document.getElementById("shareCertificateBtn");
    const verifyBtn = document.getElementById("verifyCertificateBtn");

    if (!modal || !closeBtn) return;

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
    if (downloadBtn) {
      downloadBtn.addEventListener("click", function () {
        const courseName = document.getElementById("modalCourseName").textContent;
        const certificateId = document.getElementById("modalCertificateId").textContent;
        downloadCertificate(courseName, certificateId);
      });
    }

    // Share certificate
    if (shareBtn) {
      shareBtn.addEventListener("click", function () {
        shareCertificate();
      });
    }

    // Verify certificate
    if (verifyBtn) {
      verifyBtn.addEventListener("click", function () {
        verifyCertificate();
      });
    }

    // Close modal with Escape key
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && !modal.classList.contains("hidden")) {
        hideModal();
      }
    });
  }

  function showCertificateModal(courseName, completionDate, certificateId, category, ratingStars, ratingText) {
    const modal = document.getElementById("certificateModal");
    const courseNameEl = document.getElementById("modalCourseName");
    const dateEl = document.getElementById("modalDate");
    const certificateIdEl = document.getElementById("modalCertificateId");
    const studentNameEl = document.getElementById("modalStudentName");
    const ratingEl = document.getElementById("modalRating");

    if (!modal || !courseNameEl || !dateEl || !certificateIdEl) return;

    // Update certificate content with dynamic data
    courseNameEl.textContent = courseName;
    dateEl.textContent = completionDate;
    certificateIdEl.textContent = certificateId;
    
    // Get student name from global variable set by PHP
    studentNameEl.textContent = window.studentName || "Student Name";
    
    // Update rating stars and text
    if (ratingEl) {
      ratingEl.innerHTML = `
        <div class="flex text-yellow-400 text-2xl">
          ${ratingStars}
        </div>
        <span class="ml-3 text-lg text-gray-600">${ratingText}</span>
      `;
    }

    // Show modal with animation
    modal.classList.remove("hidden");
    modal.style.display = "flex";

    // Prevent body scroll
    document.body.style.overflow = "hidden";

    // Add entrance animation
    const certificateContent = modal.querySelector("#certificateContent");
    if (certificateContent) {
      certificateContent.style.transform = "scale(0.9)";
      certificateContent.style.opacity = "0";

      setTimeout(() => {
        certificateContent.style.transition = "all 0.3s ease";
        certificateContent.style.transform = "scale(1)";
        certificateContent.style.opacity = "1";
      }, 100);
    }
  }

  function hideModal() {
    const modal = document.getElementById("certificateModal");
    modal.classList.add("hidden");
    modal.style.display = "none";

    // Restore body scroll
    document.body.style.overflow = "";
  }

  function shareCertificate() {
    const certificateId = document.getElementById("modalCertificateId").textContent;
    const courseName = document.getElementById("modalCourseName").textContent;
    
    // Create sharing URL
    const shareUrl = `${window.location.origin}/elearning/verify.php?id=${certificateId}`;
    
    // Copy to clipboard
    navigator.clipboard.writeText(shareUrl).then(() => {
      Swal.fire({
        title: 'Link Copied!',
        text: `Certificate verification link for "${courseName}" copied to clipboard!`,
        icon: 'success',
        confirmButtonText: 'Great!',
        confirmButtonColor: '#10B981'
      });
    }).catch(() => {
      // Fallback for older browsers
      const textArea = document.createElement('textarea');
      textArea.value = shareUrl;
      document.body.appendChild(textArea);
      textArea.select();
      document.execCommand('copy');
      document.body.removeChild(textArea);
      
      Swal.fire({
        title: 'Link Ready!',
        text: `Share this link to verify your certificate: ${shareUrl}`,
        icon: 'info',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3b82f6'
      });
    });
  }

  function verifyCertificate() {
    // Show verification modal
    const verificationModal = document.getElementById("verificationModal");
    if (verificationModal) {
      verificationModal.classList.remove("hidden");
      verificationModal.style.display = "flex";
      
      // Pre-fill with current certificate ID
      const certificateId = document.getElementById("modalCertificateId").textContent;
      const certificateIdInput = document.getElementById("certificateId");
      if (certificateIdInput) {
        certificateIdInput.value = certificateId;
      }
    }
  }

  function initializeAnimations() {
    // Add hover animations to certificate cards
    allCertificates.forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-4px)';
        this.style.transition = 'transform 0.2s ease';
      });
      
      card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
      });
    });
  }

  function downloadCertificate(courseName, certificateId = null) {
    const button = event.target.closest("button");
    const originalText = button.innerHTML;

    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;

    // Get certificate ID from card if not provided
    if (!certificateId) {
      const card = button.closest(".certificate-card");
      certificateId = card ? card.dataset.certificateId : null;
    }

    // Create download request
    fetch('/elearning/dashboard/api/certificates/download.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        certificate_id: certificateId,
        course_name: courseName
      })
    })
    .then(response => {
      if (response.ok) {
        return response.blob();
      }
      throw new Error('Download failed');
    })
    .then(blob => {
      // Create download link
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.style.display = 'none';
      a.href = url;
      a.download = `${courseName.replace(/[^a-zA-Z0-9]/g, '_')}_Certificate.pdf`;
      document.body.appendChild(a);
      a.click();
      window.URL.revokeObjectURL(url);
      document.body.removeChild(a);

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
    })
    .catch(error => {
      console.error('Download error:', error);
      button.innerHTML = originalText;
      button.disabled = false;

      // Show error notification
      Swal.fire({
        title: 'Download Failed',
        text: 'Unable to download certificate. Please try again.',
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#EF4444'
      });
    });
  }

  function downloadAllCertificates() {
    const button = document.getElementById("downloadAllBtn");
    const originalText = button.innerHTML;

    button.innerHTML =
      '<i class="fas fa-spinner fa-spin mr-2"></i>Preparing...';
    button.disabled = true;

    // Get all available certificate IDs
    const certificateIds = Array.from(document.querySelectorAll('.certificate-card:not(.locked-certificate)'))
      .map(card => card.dataset.certificateId)
      .filter(id => id);

    if (certificateIds.length === 0) {
      button.innerHTML = originalText;
      button.disabled = false;
      
      Swal.fire({
        title: 'No Certificates Available',
        text: 'You don\'t have any certificates to download yet.',
        icon: 'info',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3b82f6'
      });
      return;
    }

    // Request bulk download
    fetch('/elearning/dashboard/api/certificates/download-all.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        certificate_ids: certificateIds
      })
    })
    .then(response => {
      if (response.ok) {
        return response.blob();
      }
      throw new Error('Bulk download failed');
    })
    .then(blob => {
      // Create download link for ZIP file
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.style.display = 'none';
      a.href = url;
      a.download = `My_Certificates_${new Date().toISOString().split('T')[0]}.zip`;
      document.body.appendChild(a);
      a.click();
      window.URL.revokeObjectURL(url);
      document.body.removeChild(a);

      button.innerHTML = originalText;
      button.disabled = false;

      // Show success notification
      Swal.fire({
        title: 'Download Complete!',
        text: `All ${certificateIds.length} certificates downloaded as ZIP file!`,
        icon: 'success',
        confirmButtonText: 'Awesome!',
        confirmButtonColor: '#10B981'
      });
    })
    .catch(error => {
      console.error('Bulk download error:', error);
      button.innerHTML = originalText;
      button.disabled = false;

      // Show error notification
      Swal.fire({
        title: 'Download Failed',
        text: 'Unable to download certificates. Please try again.',
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#EF4444'
      });
    });
  }

  function initializeAnimations() {
    // Add hover effects to certificate cards
    const certificateCards = document.querySelectorAll(".certificate-card");
    certificateCards.forEach((card) => {
      card.addEventListener("mouseenter", function () {
        if (!this.classList.contains("locked-certificate")) {
          this.style.transform = "translateY(-4px)";
          this.style.transition = "all 0.3s ease";
        }
      });

      card.addEventListener("mouseleave", function () {
        this.style.transform = "translateY(0)";
      });
    });

    // Animate stats on page load
    animateStats();
  }

  function animateStats() {
    // Animate stats on page load
    const statNumbers = document.querySelectorAll(".text-2xl.font-bold");
    statNumbers.forEach((stat) => {
      const finalValue = parseInt(stat.textContent);
      if (isNaN(finalValue)) return;
      
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
  }

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
    
    // Use the actual domain for sharing
    const baseUrl = window.location.origin;
    const shareUrl = `${baseUrl}/elearning/verify-certificate.php?id=${certificateId}`;

    if (navigator.share) {
      navigator
        .share({
          title: `Certificate of Achievement - ${courseName}`,
          text: `I've completed ${courseName} and earned my certificate!`,
          url: shareUrl,
        })
        .catch(console.error);
    } else {
      // Fallback for browsers that don't support Web Share API
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
      }).catch(() => {
        // Fallback if clipboard API is not available
        Swal.fire({
          title: 'Share Certificate',
          html: `<p>Copy this link to share your certificate:</p>
                 <input type="text" value="${shareUrl}" class="w-full p-2 border rounded mt-2" readonly onclick="this.select()">`,
          icon: 'info',
          confirmButtonText: 'OK',
          confirmButtonColor: '#3b82f6'
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

    // Make API call to verify certificate
    fetch('/elearning/dashboard/api/certificates/verify.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        certificate_id: certificateId
      })
    })
    .then(response => response.json())
    .then(data => {
      button.innerHTML = originalText;
      button.disabled = false;

      if (data.success) {
        Swal.fire({
          title: 'Verification Complete!',
          html: `
            <div class="text-left">
              <p><strong>Certificate ID:</strong> ${certificateId}</p>
              <p><strong>Course:</strong> ${data.course_name}</p>
              <p><strong>Student:</strong> ${data.student_name}</p>
              <p><strong>Completion Date:</strong> ${data.completion_date}</p>
              <p><strong>Status:</strong> <span class="text-green-600">✓ Verified & Authentic</span></p>
            </div>
          `,
          icon: 'success',
          confirmButtonText: 'Excellent!',
          confirmButtonColor: '#10B981'
        });
      } else {
        Swal.fire({
          title: 'Verification Failed',
          text: data.message || 'Certificate could not be verified.',
          icon: 'error',
          confirmButtonText: 'OK',
          confirmButtonColor: '#EF4444'
        });
      }
    })
    .catch(error => {
      console.error('Verification error:', error);
      button.innerHTML = originalText;
      button.disabled = false;

      Swal.fire({
        title: 'Verification Error',
        text: 'Unable to verify certificate. Please try again.',
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#EF4444'
      });
    });
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
