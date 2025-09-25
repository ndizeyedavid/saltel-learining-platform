document.addEventListener("DOMContentLoaded", function () {
  // Initialize Quill editor for description
  const quill = new Quill("#description", {
    theme: "snow",
    modules: {
      toolbar: [
        ["bold", "italic", "underline"],
        [{ list: "ordered" }, { list: "bullet" }],
        ["link"],
        ["clean"],
      ],
    },
    placeholder: "Enter assignment description...",
  });

  let questionCount = 1;

  // Initialize course selection handler
  const courseSelect = document.querySelector(
    'select[placeholder="Select course"]'
  );
  const moduleSelect = document.querySelector(
    'select[placeholder="Select module"]'
  );

  courseSelect?.addEventListener("change", function () {
    updateModuleOptions(this.value);
  });

  // Add new question
  document
    .getElementById("addQuestionBtn")
    .addEventListener("click", function () {
      questionCount++;
      const questionHtml = createQuestionTemplate(questionCount);
      const container = document.getElementById("questionsContainer");
      container.insertAdjacentHTML("beforeend", questionHtml);
    });

  // Delete question
  document.addEventListener("click", function (e) {
    if (e.target.closest(".delete-question")) {
      const questionBlock = e.target.closest(".question-block");
      if (document.querySelectorAll(".question-block").length > 1) {
        questionBlock.remove();
        updateQuestionNumbers();
      } else {
        alert("You must have at least one question.");
      }
    }
  });

  // Add option to question
  document.addEventListener("click", function (e) {
    if (e.target.closest(".add-option")) {
      const optionsContainer = e.target
        .closest(".question-block")
        .querySelector(".options-container");
      const questionNumber = e.target
        .closest(".question-block")
        .querySelector("h3")
        .textContent.split(" ")[1];
      const optionCount = optionsContainer.children.length + 1;

      const optionHtml = createOptionTemplate(questionNumber, optionCount);
      optionsContainer.insertAdjacentHTML("beforeend", optionHtml);
    }
  });

  // Delete option
  document.addEventListener("click", function (e) {
    if (e.target.closest(".delete-option")) {
      const optionsContainer = e.target.closest(".options-container");
      if (optionsContainer.children.length > 2) {
        e.target.closest(".flex").remove();
      } else {
        alert("You must have at least two options.");
      }
    }
  });

  // Preview button handler
  document.getElementById("previewBtn").addEventListener("click", function () {
    if (validateForm()) {
      const assignmentData = gatherFormData();
      localStorage.setItem("previewAssignment", JSON.stringify(assignmentData));
      window.open("assignment-preview.php", "_blank");
    }
  });

  // Publish button handler
  document
    .getElementById("publishBtn")
    .addEventListener("click", async function () {
      if (validateForm()) {
        const assignmentData = gatherFormData();
        try {
          const response = await submitAssignment(assignmentData);
          if (response.success) {
            alert("Assignment published successfully!");
            window.location.href = "assignments.php";
          } else {
            throw new Error(response.message);
          }
        } catch (error) {
          alert("Failed to publish assignment: " + error.message);
        }
      }
    });

  // Make questions sortable (if you include a sorting library)
  if (typeof Sortable !== "undefined") {
    new Sortable(document.getElementById("questionsContainer"), {
      animation: 150,
      handle: ".fa-arrows-alt",
      onEnd: updateQuestionNumbers,
    });
  }
});

function createQuestionTemplate(number) {
  return `
        <div class="p-6 bg-white rounded-lg shadow-sm question-block">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Question ${number}</h3>
                <div class="flex items-center space-x-2">
                    <button class="p-2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-arrows-alt"></i>
                    </button>
                    <button class="p-2 text-red-400 hover:text-red-600 delete-question">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Question Text</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                           placeholder="Enter your question">
                </div>
                <div class="options-container space-y-2">
                    <div class="flex items-center space-x-2">
                        <input type="radio" name="correct_${number}" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                               placeholder="Option 1">
                        <button class="p-2 text-red-400 hover:text-red-600 delete-option">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="radio" name="correct_${number}" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                               placeholder="Option 2">
                        <button class="p-2 text-red-400 hover:text-red-600 delete-option">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <button class="text-sm text-blue-600 hover:text-blue-700 add-option">
                    <i class="mr-1 fas fa-plus"></i>Add Option
                </button>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Explanation (Optional)</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                              placeholder="Explain the correct answer"></textarea>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Points</label>
                    <input type="number" class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                           value="1">
                </div>
            </div>
        </div>
    `;
}

function createOptionTemplate(questionNumber, optionNumber) {
  return `
        <div class="flex items-center space-x-2">
            <input type="radio" name="correct_${questionNumber}" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
            <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                   placeholder="Option ${optionNumber}">
            <button class="p-2 text-red-400 hover:text-red-600 delete-option">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
}

function updateQuestionNumbers() {
  document.querySelectorAll(".question-block").forEach((block, index) => {
    const number = index + 1;
    block.querySelector("h3").textContent = `Question ${number}`;
    const radioButtons = block.querySelectorAll('input[type="radio"]');
    radioButtons.forEach((radio) => {
      radio.name = `correct_${number}`;
    });
  });
}

function validateForm() {
  const title = document.querySelector(
    'input[placeholder="Enter assignment title"]'
  ).value;
  const course = document.querySelector("select").value;
  const timeLimit = document.querySelector('input[type="number"]').value;

  if (!title) {
    alert("Please enter an assignment title");
    return false;
  }
  if (!course) {
    alert("Please select a course");
    return false;
  }
  if (!timeLimit || timeLimit < 1) {
    alert("Please enter a valid time limit");
    return false;
  }

  let isValid = true;
  document.querySelectorAll(".question-block").forEach((block, index) => {
    const questionText = block.querySelector(
      'input[placeholder="Enter your question"]'
    ).value;
    const options = block.querySelectorAll(
      '.options-container input[type="text"]'
    );
    const correctAnswer = block.querySelector(
      `input[name="correct_${index + 1}"]:checked`
    );

    if (!questionText) {
      alert(`Please enter text for question ${index + 1}`);
      isValid = false;
    }
    if (options.length < 2) {
      alert(`Question ${index + 1} must have at least 2 options`);
      isValid = false;
    }
    if (!correctAnswer) {
      alert(`Please select a correct answer for question ${index + 1}`);
      isValid = false;
    }
  });

  return isValid;
}

function gatherFormData() {
  const quill = Quill.find(document.getElementById("description"));

  const formData = {
    title: document.querySelector('input[placeholder="Enter assignment title"]')
      .value,
    description: quill.root.innerHTML,
    course: document.querySelector("select").value,
    timeLimit: document.querySelector('input[type="number"]').value,
    passingScore: document.querySelector(
      'input[placeholder="Passing Score (%)"]'
    ).value,
    dueDate: document.querySelector('input[type="date"]').value,
    settings: {
      randomize: document.querySelector('input[type="checkbox"]').checked,
      showResults: document.querySelector(
        'input[type="checkbox"]:nth-of-type(2)'
      ).checked,
      antiCheat: document.querySelector('input[type="checkbox"]:nth-of-type(3)')
        .checked,
    },
    questions: [],
  };

  document.querySelectorAll(".question-block").forEach((block, index) => {
    const options = [];
    let correctAnswer;

    block
      .querySelectorAll('.options-container input[type="text"]')
      .forEach((option, optionIndex) => {
        options.push(option.value);
        if (
          block.querySelector(
            `input[name="correct_${index + 1}"]:nth-of-type(${optionIndex + 1})`
          ).checked
        ) {
          correctAnswer = optionIndex;
        }
      });

    formData.questions.push({
      text: block.querySelector('input[placeholder="Enter your question"]')
        .value,
      options: options,
      correctAnswer: correctAnswer,
      explanation: block.querySelector("textarea").value,
      points: block.querySelector('input[type="number"]').value,
    });
  });

  return formData;
}

async function submitAssignment(data) {
  try {
    const response = await fetch("../api/assignments/create.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    });
    return await response.json();
  } catch (error) {
    throw new Error("Failed to submit assignment");
  }
}

function updateModuleOptions(courseId) {
  // This would typically fetch modules from the server
  const moduleSelect = document.querySelector(
    'select[placeholder="Select module"]'
  );
  moduleSelect.innerHTML = '<option value="">Select module</option>';

  if (courseId) {
    // Call trainer API to load modules for selected course
    fetch(
      `../api/courses/modules.php?course_id=${encodeURIComponent(courseId)}`
    )
      .then((response) => response.json())
      .then((modules) => {
        modules.forEach((module) => {
          moduleSelect.innerHTML += `<option value="${module.id}">${module.title}</option>`;
        });
      })
      .catch((error) => console.error("Error loading modules:", error));
  }
}
