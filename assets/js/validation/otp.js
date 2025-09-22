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

// OTP validation and functionality
$(document).ready(function () {
  const form = $("form");
  const otpInputs = $(".otp-input");
  const otpValue = $("#otp-value");
  const otpError = $("#otp-error");
  const verifyBtn = $("#verify-btn");
  const btnText = $("#btn-text");
  const btnSpinner = $("#btn-spinner");
  const resendBtn = $("#resend-btn");
  const timerElement = $("#timer");
  const emailDisplay = $("#email-display");

  let timeLeft = 300; // 5 minutes in seconds
  let timerInterval;

  // Initialize
  init();

  function init() {
    // Get email from URL parameters or localStorage
    const urlParams = new URLSearchParams(window.location.search);
    const email = urlParams.get('email') || localStorage.getItem('resetEmail') || 'your email';
    
    // Display masked email
    if (email !== 'your email') {
      const maskedEmail = maskEmail(email);
      emailDisplay.text(`Code sent to: ${maskedEmail}`);
    }

    // Focus first input
    otpInputs.first().focus();

    // Start timer
    startTimer();

    // Setup OTP input handlers
    setupOTPInputs();
  }

  // Mask email for display
  function maskEmail(email) {
    const [username, domain] = email.split('@');
    if (username.length <= 2) return email;
    
    const maskedUsername = username[0] + '*'.repeat(username.length - 2) + username[username.length - 1];
    return `${maskedUsername}@${domain}`;
  }

  // Setup OTP input functionality
  function setupOTPInputs() {
    otpInputs.each(function(index) {
      $(this).on('input', function(e) {
        const value = e.target.value;
        
        // Only allow numbers
        if (!/^\d$/.test(value) && value !== '') {
          e.target.value = '';
          return;
        }

        // Move to next input
        if (value && index < otpInputs.length - 1) {
          otpInputs.eq(index + 1).focus();
        }

        // Update hidden input and validate
        updateOTPValue();
        validateOTP();
      });

      // Handle backspace
      $(this).on('keydown', function(e) {
        if (e.key === 'Backspace' && !e.target.value && index > 0) {
          otpInputs.eq(index - 1).focus();
        }
      });

      // Handle paste
      $(this).on('paste', function(e) {
        e.preventDefault();
        const pastedData = e.originalEvent.clipboardData.getData('text');
        const digits = pastedData.replace(/\D/g, '').slice(0, 6);
        
        digits.split('').forEach((digit, i) => {
          if (i < otpInputs.length) {
            otpInputs.eq(i).val(digit);
          }
        });

        // Focus last filled input or next empty
        const lastIndex = Math.min(digits.length - 1, otpInputs.length - 1);
        otpInputs.eq(lastIndex).focus();
        
        updateOTPValue();
        validateOTP();
      });

      // Clear error on focus
      $(this).on('focus', function() {
        hideOTPError();
      });
    });
  }

  // Update hidden OTP value
  function updateOTPValue() {
    let otp = '';
    otpInputs.each(function() {
      otp += $(this).val();
    });
    otpValue.val(otp);
  }

  // Validate OTP
  function validateOTP() {
    const otp = otpValue.val();
    if (otp.length === 6) {
      hideOTPError();
      verifyBtn.prop('disabled', false);
    } else {
      verifyBtn.prop('disabled', true);
    }
  }

  // Show OTP error
  function showOTPError(message) {
    otpInputs.removeClass('border-caritas-cream').addClass('border-red-500');
    otpError.text(message).removeClass('hidden');
  }

  // Hide OTP error
  function hideOTPError() {
    otpInputs.removeClass('border-red-500').addClass('border-caritas-cream');
    otpError.addClass('hidden');
  }

  // Start countdown timer
  function startTimer() {
    timerInterval = setInterval(function() {
      timeLeft--;
      
      const minutes = Math.floor(timeLeft / 60);
      const seconds = timeLeft % 60;
      const formattedTime = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
      
      timerElement.text(formattedTime);

      if (timeLeft <= 0) {
        clearInterval(timerInterval);
        timerElement.text('00:00').removeClass('text-caritas').addClass('text-red-500');
        resendBtn.prop('disabled', false).text('Resend Code');
        toastr.warning('Verification code has expired. Please request a new code.', 'Code Expired');
      }
    }, 1000);
  }

  // Reset timer
  function resetTimer() {
    clearInterval(timerInterval);
    timeLeft = 300;
    timerElement.removeClass('text-red-500').addClass('text-caritas');
    startTimer();
  }

  // Resend OTP
  resendBtn.on('click', function() {
    const email = new URLSearchParams(window.location.search).get('email') || localStorage.getItem('resetEmail');
    
    if (!email) {
      toastr.error('Email not found. Please restart the password reset process.', 'Error');
      return;
    }

    // Disable resend button
    resendBtn.prop('disabled', true).text('Sending...');

    // Simulate resend request (replace with actual AJAX call)
    setTimeout(function() {
      // Clear current OTP inputs
      otpInputs.val('');
      otpInputs.first().focus();
      updateOTPValue();
      validateOTP();
      hideOTPError();

      // Reset timer
      resetTimer();
      
      toastr.success('A new verification code has been sent to your email.', 'Code Sent');
      
      // Re-enable resend button after timer expires
      resendBtn.text('Resend Code');
    }, 2000);

    // Uncomment for actual implementation
    /*
    $.ajax({
      url: 'php/resend-otp.php',
      method: 'POST',
      data: { email: email },
      success: function(response) {
        if (response.success) {
          otpInputs.val('');
          otpInputs.first().focus();
          updateOTPValue();
          validateOTP();
          hideOTPError();
          resetTimer();
          toastr.success('A new verification code has been sent to your email.', 'Code Sent');
        } else {
          toastr.error(response.message || 'Failed to resend code', 'Error');
          resendBtn.prop('disabled', false).text('Resend Code');
        }
      },
      error: function() {
        toastr.error('An error occurred. Please try again.', 'Connection Error');
        resendBtn.prop('disabled', false).text('Resend Code');
      }
    });
    */
  });

  // Form submission
  form.on('submit', function(e) {
    e.preventDefault();

    const otp = otpValue.val();
    
    // Validate OTP length
    if (otp.length !== 6) {
      showOTPError('Please enter the complete 6-digit verification code');
      toastr.error('Please enter the complete verification code', 'Validation Error');
      return false;
    }

    // Check if timer expired
    if (timeLeft <= 0) {
      showOTPError('Verification code has expired');
      toastr.error('Verification code has expired. Please request a new code.', 'Code Expired');
      return false;
    }

    // Show loading state
    verifyBtn.prop('disabled', true);
    btnText.addClass('hidden');
    btnSpinner.removeClass('hidden');

    // If validation passes, allow normal form submission to PHP
    form.off('submit'); // Remove this event handler
    form.submit(); // Submit the form normally to PHP
  });

  // Clear OTP inputs on page load if needed
  $(window).on('beforeunload', function() {
    // Clear sensitive data
    otpInputs.val('');
    otpValue.val('');
  });
});
