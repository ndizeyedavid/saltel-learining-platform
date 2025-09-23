document.addEventListener("DOMContentLoaded", function () {
  // Check if content is completed
  if (!checkCourseProgress("settings")) return;

  // Load existing settings
  const courseData = courseState.getCourseData();
  if (courseData.settings) {
    Object.entries(courseData.settings).forEach(([key, value]) => {
      const input = document.querySelector(`[name="${key}"]`);
      if (input) {
        if (input.type === "checkbox") {
          input.checked = value;
        } else {
          input.value = value;
        }
      }
    });
  }

  // Handle settings form submission
  const form = document.querySelector("#courseSettingsForm");
  form.addEventListener("submit", function (e) {
    e.preventDefault();

    // Gather form data
    const formData = new FormData(form);
    const settings = Object.fromEntries(formData);

    // Add checkbox values
    form.querySelectorAll('input[type="checkbox"]').forEach((checkbox) => {
      settings[checkbox.name] = checkbox.checked;
    });

    // Save settings
    courseState.saveSettings(settings);

    // Submit complete course
    submitCourseData();
  });

  // Handle visibility changes
  document
    .querySelector('[name="visibility"]')
    .addEventListener("change", function (e) {
      const passwordField = document.querySelector("#passwordProtection");
      if (e.target.value === "password_protected") {
        passwordField.classList.remove("hidden");
      } else {
        passwordField.classList.add("hidden");
      }
    });

  // Handle enrollment type changes
  document
    .querySelector('[name="enrollmentType"]')
    .addEventListener("change", function (e) {
      const maxStudentsField = document.querySelector("#maxStudents");
      const waitlistField = document.querySelector("#waitlistOption");

      if (e.target.value === "open") {
        maxStudentsField.classList.remove("hidden");
        waitlistField.classList.remove("hidden");
      } else {
        maxStudentsField.classList.add("hidden");
        waitlistField.classList.add("hidden");
      }
    });

  // Handle certificate enable/disable
  document
    .querySelector("#enableCertificate")
    .addEventListener("change", function (e) {
      const certificateOptions = document.querySelector("#certificateOptions");
      if (e.target.checked) {
        certificateOptions.classList.remove("hidden");
      } else {
        certificateOptions.classList.add("hidden");
      }
    });

  // Auto-save on field changes
  form.querySelectorAll("input, select, textarea").forEach((input) => {
    input.addEventListener("change", function () {
      const formData = new FormData(form);
      const settings = Object.fromEntries(formData);
      courseState.saveSettings(settings);
    });
  });
});
