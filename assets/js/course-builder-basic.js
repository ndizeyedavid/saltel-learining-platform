document.addEventListener("DOMContentLoaded", function () {
  // Load existing data if any
  const courseData = courseState.getCourseData();
  if (courseData.basicInfo) {
    Object.entries(courseData.basicInfo).forEach(([key, value]) => {
      const input = document.querySelector(`[name="${key}"]`);
      if (input) input.value = value;
    });
  }

  // Handle form submission
  const form = document.querySelector("#courseBasicForm");
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);

    courseState.saveBasicInfo(data);
    window.location.href = "course-content.php";
  });

  // Handle save draft
  document.querySelector("#saveDraft").addEventListener("click", function () {
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    courseState.saveBasicInfo(data);
    alert("Draft saved successfully!");
  });

  // Handle image upload
  const imageUpload = document.querySelector("#courseImage");
  imageUpload.addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        courseState.saveBasicInfo({
          courseImage: e.target.result,
        });
        // Update preview if exists
        const preview = document.querySelector("#imagePreview");
        if (preview) preview.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });
});
