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

// Form validation and submission
$(document).ready(function () {
  const form = $("form");
  const emailInput = $("#email");
  const passwordInput = $("#password");
  const emailError = $("#email-error");
  const passwordError = $("#password-error");
  const loginBtn = $("#login-btn");
  const btnText = $("#btn-text");
  const btnSpinner = $("#btn-spinner");

  // Email validation function
  function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
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

  passwordInput.on("blur", function () {
    const password = $(this).val().trim();
    if (password === "") {
      showFieldError(passwordInput, passwordError, "Password is required");
    } else if (password.length < 6) {
      showFieldError(
        passwordInput,
        passwordError,
        "Password must be at least 6 characters"
      );
    } else {
      hideFieldError(passwordInput, passwordError);
    }
  });

  // Show field error
  function showFieldError(input, errorElement, message) {
    input.removeClass("border-caritas-cream").addClass("border-red-500");
    errorElement.text(message).removeClass("hidden");
  }

  // Hide field error
  function hideFieldError(input, errorElement) {
    input.removeClass("border-red-500").addClass("border-caritas-cream");
    errorElement.addClass("hidden");
  }

  // Form submission
  form.on("submit", function (e) {
    e.preventDefault();

    const email = emailInput.val().trim();
    const password = passwordInput.val().trim();
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

    // Validate password
    if (password === "") {
      showFieldError(passwordInput, passwordError, "Password is required");
      isValid = false;
    } else if (password.length < 6) {
      showFieldError(
        passwordInput,
        passwordError,
        "Password must be at least 6 characters"
      );
      isValid = false;
    } else {
      hideFieldError(passwordInput, passwordError);
    }

    if (!isValid) {
      // Reset button state if validation fails
      loginBtn.prop("disabled", false);
      btnText.removeClass("hidden");
      btnSpinner.addClass("hidden");
      toastr.error("Please fix the errors above", "Validation Error");
      return false; // Prevent form submission
    }

    // Show loading state
    loginBtn.prop("disabled", true);
    btnText.addClass("hidden");
    btnSpinner.removeClass("hidden");

    // If validation passes, allow normal form submission to PHP
    // Remove preventDefault and let the form submit naturally
    form.off("submit"); // Remove this event handler
    form.submit(); // Submit the form normally to PHP
  });

  // Clear errors on input focus
  emailInput.on("focus", function () {
    hideFieldError(emailInput, emailError);
  });

  passwordInput.on("focus", function () {
    hideFieldError(passwordInput, passwordError);
  });
});
