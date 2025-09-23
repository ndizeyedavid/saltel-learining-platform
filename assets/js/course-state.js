// Course state management
class CourseStateManager {
  constructor() {
    this.courseData = JSON.parse(localStorage.getItem("courseData")) || {
      basicInfo: {},
      content: [],
      settings: {},
    };
  }

  saveBasicInfo(data) {
    this.courseData.basicInfo = { ...this.courseData.basicInfo, ...data };
    this.saveToStorage();
  }

  saveContent(data) {
    this.courseData.content = data;
    this.saveToStorage();
  }

  saveSettings(data) {
    this.courseData.settings = { ...this.courseData.settings, ...data };
    this.saveToStorage();
  }

  getCourseData() {
    return this.courseData;
  }

  saveToStorage() {
    localStorage.setItem("courseData", JSON.stringify(this.courseData));
  }

  clearData() {
    localStorage.removeItem("courseData");
    this.courseData = {
      basicInfo: {},
      content: [],
      settings: {},
    };
  }
}

// Initialize state manager
const courseState = new CourseStateManager();

// Navigation guard
function checkCourseProgress(requiredStep) {
  const data = courseState.getCourseData();
  switch (requiredStep) {
    case "content":
      if (!data.basicInfo.title) {
        window.location.href = "course-builder.php";
        return false;
      }
      break;
    case "settings":
      if (!data.basicInfo.title || data.content.length === 0) {
        window.location.href = data.basicInfo.title
          ? "course-content.php"
          : "course-builder.php";
        return false;
      }
      break;
    case "review":
      if (
        !data.basicInfo.title ||
        data.content.length === 0 ||
        !data.settings.status
      ) {
        window.location.href = !data.basicInfo.title
          ? "course-builder.php"
          : data.content.length === 0
          ? "course-content.php"
          : "course-settings.php";
        return false;
      }
      break;
  }
  return true;
}

// Form submission handler
async function submitCourseData() {
  const courseData = courseState.getCourseData();
  try {
    const response = await fetch("../api/courses/create.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(courseData),
    });

    if (response.ok) {
      courseState.clearData();
      window.location.href = "courses.php";
    } else {
      throw new Error("Failed to create course");
    }
  } catch (error) {
    console.error("Error creating course:", error);
    alert("Failed to create course. Please try again.");
  }
}
