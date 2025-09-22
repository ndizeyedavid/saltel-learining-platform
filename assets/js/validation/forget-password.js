// Configure toastr options
toastr.options = {
  closeButton: true,
  debug: false,
  newestOnTop: true,
  progressBar: true,
  positionClass: "toast-top-right",
  preventDuplicates: false,
  onclick: null,
  showDuration: "300",
  hideDuration: "1000",
  timeOut: "5000",
  extendedTimeOut: "1000",
  showEasing: "swing",
  hideEasing: "linear",
  showMethod: "fadeIn",
  hideMethod: "fadeOut",
};

// Form validation and submission for password reset
$(document).ready(function () {
  const form = $("form");
  const emailInput = $("#email");
  const emailError = $("#email-error");
  const resetBtn = $("#login-btn");
  const btnText = $("#btn-text");
  const btnSpinner = $("#btn-spinner");

  // Email validation function
  function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  // Show field error
  function showFieldError(input, errorElement, message) {
    input.removeClass("border-saltel-cream").addClass("border-red-500");
    errorElement.text(message).removeClass("hidden");
  }

  // Hide field error
  function hideFieldError(input, errorElement) {
    input.removeClass("border-red-500").addClass("border-saltel-cream");
    errorElement.addClass("hidden");
  }

  // Real-time validation
  emailInput.on("blur", function () {
    const email = $(this).val().trim();
    if (email === "") {
      showFieldError(emailInput, emailError, "Email is required");
    } else if (!validateEmail(email)) {
      showFieldError(
        emailInput,
        emailError,
        "Please enter a valid email address"
      );
    } else {
      hideFieldError(emailInput, emailError);
    }
  });

  // Form submission
  form.on("submit", function (e) {
    e.preventDefault();

    const email = emailInput.val().trim();
    let isValid = true;

    // Validate email
    if (email === "") {
      showFieldError(emailInput, emailError, "Email is required");
      isValid = false;
    } else if (!validateEmail(email)) {
      showFieldError(
        emailInput,
        emailError,
        "Please enter a valid email address"
      );
      isValid = false;
    } else {
      hideFieldError(emailInput, emailError);
    }

    if (!isValid) {
      // Reset button state if validation fails
      resetBtn.prop("disabled", false);
      btnText.removeClass("hidden");
      btnSpinner.addClass("hidden");
      toastr.error("Please fix the errors above", "Validation Error");
      return false; // Prevent form submission
    }

    // Show loading state
    resetBtn.prop("disabled", true);
    btnText.addClass("hidden");
    btnSpinner.removeClass("hidden");

    // If validation passes, allow normal form submission to PHP
    form.off("submit"); // Remove this event handler
    form.submit(); // Submit the form normally to PHP
  });

  // Clear errors on input focus
  emailInput.on("focus", function () {
    hideFieldError(emailInput, emailError);
  });
});
