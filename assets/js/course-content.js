document.addEventListener("DOMContentLoaded", function () {
  // Check if basic info is completed
  if (!checkCourseProgress("content")) return;

  // Initialize content from state
  const courseData = courseState.getCourseData();
  if (courseData.content) {
    courseData.content.forEach((module) => {
      addModuleToList(module);
    });
  }

  // Initialize Quill editor
  const quill = new Quill("#editor", {
    theme: "snow",
    modules: {
      toolbar: [
        ["bold", "italic", "underline", "strike"],
        ["blockquote", "code-block"],
        [{ header: 1 }, { header: 2 }],
        [{ list: "ordered" }, { list: "bullet" }],
        ["link", "image", "video"],
        ["clean"],
      ],
    },
  });

  // Handle module addition
  document
    .querySelector("#addModuleBtn")
    .addEventListener("click", function () {
      const module = {
        title: "New Module",
        lessons: [],
      };
      addModuleToList(module);
      updateModuleOrder();
    });

  // Handle module ordering
  new Sortable(document.querySelector("#moduleList"), {
    animation: 150,
    handle: ".module-handle",
    onEnd: updateModuleOrder,
  });

  // Save content changes
  document.querySelector("#saveContent").addEventListener("click", function () {
    const modules = [];
    document.querySelectorAll(".module-item").forEach((moduleEl) => {
      const module = {
        title: moduleEl.querySelector(".module-title").value,
        lessons: [],
      };
      moduleEl.querySelectorAll(".lesson-item").forEach((lessonEl) => {
        module.lessons.push({
          title: lessonEl.querySelector(".lesson-title").value,
          type: lessonEl.querySelector(".lesson-type").value,
          content: lessonEl.querySelector(".lesson-content").value,
        });
      });
      modules.push(module);
    });
    courseState.saveContent(modules);
    window.location.href = "course-settings.php";
  });

  // Handle file uploads
  document
    .querySelector("#videoUpload")
    .addEventListener("change", handleFileUpload);
  document
    .querySelector("#docUpload")
    .addEventListener("change", handleFileUpload);

  function handleFileUpload(e) {
    const files = Array.from(e.target.files);
    files.forEach((file) => {
      const reader = new FileReader();
      reader.onload = function (e) {
        addResourceToList({
          name: file.name,
          type: file.type,
          data: e.target.result,
        });
      };
      reader.readAsDataURL(file);
    });
  }

  function addModuleToList(module) {
    const moduleHtml = `
            <div class="module-item p-4 bg-white border border-gray-200 rounded-lg mb-2">
                <div class="flex items-center">
                    <i class="fas fa-grip-vertical module-handle mr-3 text-gray-400 cursor-move"></i>
                    <input type="text" class="module-title flex-1 border-none focus:ring-0" value="${
                      module.title
                    }">
                    <button class="text-gray-400 hover:text-red-600 delete-module">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="lessons-list ml-8 mt-2">
                    ${module.lessons
                      .map((lesson) => getLessonHTML(lesson))
                      .join("")}
                </div>
                <button class="add-lesson ml-8 mt-2 text-blue-600 hover:text-blue-700">
                    <i class="fas fa-plus mr-1"></i>Add Lesson
                </button>
            </div>
        `;
    document
      .querySelector("#moduleList")
      .insertAdjacentHTML("beforeend", moduleHtml);
  }

  function getLessonHTML(lesson = {}) {
    return `
            <div class="lesson-item p-2 bg-gray-50 rounded mb-2">
                <div class="flex items-center">
                    <input type="text" class="lesson-title flex-1 text-sm border-none bg-transparent focus:ring-0" 
                           value="${lesson.title || ""}">
                    <select class="lesson-type text-sm border-none bg-transparent">
                        <option value="video" ${
                          lesson.type === "video" ? "selected" : ""
                        }>Video</option>
                        <option value="text" ${
                          lesson.type === "text" ? "selected" : ""
                        }>Text</option>
                        <option value="quiz" ${
                          lesson.type === "quiz" ? "selected" : ""
                        }>Quiz</option>
                    </select>
                    <button class="text-gray-400 hover:text-red-600 delete-lesson">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
  }

  function updateModuleOrder() {
    const modules = [];
    document.querySelectorAll(".module-item").forEach((moduleEl) => {
      const module = {
        title: moduleEl.querySelector(".module-title").value,
        lessons: [],
      };
      moduleEl.querySelectorAll(".lesson-item").forEach((lessonEl) => {
        module.lessons.push({
          title: lessonEl.querySelector(".lesson-title").value,
          type: lessonEl.querySelector(".lesson-type").value,
        });
      });
      modules.push(module);
    });
    courseState.saveContent(modules);
  }
});
