// Assignments Page Functionality
document.addEventListener("DOMContentLoaded", function () {
  loadAssignments();
  initializeModals();
});

async function loadAssignments() {
  const loadingSpinner = document.getElementById('loadingSpinner');
  const assignmentsTable = document.getElementById('assignmentsTable');
  const assignmentsTableBody = document.getElementById('assignmentsTableBody');
  
  try {
    const response = await fetch('../api/assignments/get-assignments.php');
    const data = await response.json();
    
    if (data.success) {
      // Hide loading spinner and show table
      loadingSpinner.classList.add('hidden');
      assignmentsTable.classList.remove('hidden');
      
      // Clear existing content
      assignmentsTableBody.innerHTML = '';
      
      if (data.assignments.length === 0) {
        assignmentsTableBody.innerHTML = `
          <tr>
            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
              <i class="fas fa-clipboard-list text-4xl mb-4 text-gray-300"></i>
              <p class="text-lg font-medium">No assignments found</p>
              <p class="text-sm">You don't have any assignments yet.</p>
            </td>
          </tr>
        `;
        return;
      }
      
      // Render assignments
      data.assignments.forEach(assignment => {
        const row = createAssignmentRow(assignment);
        assignmentsTableBody.appendChild(row);
      });
      
      // Update statistics
      updateAssignmentStats(data.assignments);
      
    } else {
      throw new Error(data.message || 'Failed to load assignments');
    }
  } catch (error) {
    console.error('Error loading assignments:', error);
    loadingSpinner.innerHTML = `
      <div class="text-center text-red-600">
        <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
        <p>Failed to load assignments</p>
        <button onclick="loadAssignments()" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
          Try Again
        </button>
      </div>
    `;
  }
}

function createAssignmentRow(assignment) {
  const row = document.createElement('tr');
  row.className = assignment.status === 'overdue' ? 'bg-red-50 border-b hover:bg-red-100' : 'bg-white border-b hover:bg-gray-50';
  
  const actionButton = getActionButton(assignment);
  
  row.innerHTML = `
    <td class="px-6 py-4">
      <div class="flex items-center">
        <div>
          <div class="font-medium text-gray-900">${assignment.title}</div>
          <div class="text-sm text-gray-500">${assignment.description || 'No description available'}</div>
        </div>
      </div>
    </td>
    <td class="px-6 py-4 text-gray-500">${assignment.course_title}</td>
    <td class="px-6 py-4">
      <div class="text-gray-500">${assignment.due_date_formatted || 'No due date'}</div>
      <div class="text-xs font-medium ${assignment.days_class}">${assignment.days_text}</div>
    </td>
    <td class="px-6 py-4">
      <span class="inline-flex items-center px-3 py-1 text-xs font-medium border rounded-full ${assignment.status_class}">
        <i class="mr-1 fas ${assignment.status_icon}"></i>
        ${assignment.status_text}
      </span>
    </td>
    <td class="px-6 py-4">
      ${actionButton}
    </td>
  `;
  
  return row;
}

function getActionButton(assignment) {
  if (assignment.status === 'submitted') {
    return `
      <div class="flex items-center space-x-2">
        <span class="text-sm font-medium text-green-600">✓ Submitted</span>
        ${assignment.grade ? `<span class="text-xs text-gray-500">Grade: ${assignment.grade}</span>` : ''}
      </div>
    `;
  }
  
  return `
    <a href="assignment-take.php?id=${assignment.assignment_id}" 
       class="px-4 py-2 text-sm font-medium text-white transition-all ${assignment.status === 'overdue' ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700'} rounded-lg hover:shadow-lg">
      <i class="mr-2 fas ${assignment.status === 'overdue' ? 'fa-exclamation-triangle' : 'fa-play'}"></i>
      ${assignment.status === 'overdue' ? 'Submit Now' : 'Start Assignment'}
    </a>
  `;
}

function updateAssignmentStats(assignments) {
  const stats = {
    completed: assignments.filter(a => a.status === 'submitted').length,
    inProgress: assignments.filter(a => a.status === 'pending' || a.status === 'due_soon').length,
    overdue: assignments.filter(a => a.status === 'overdue').length,
    locked: 0 // TODO: Implement locked assignments logic
  };
  
  // Update stat cards - using safer querySelector approach
  const statCards = document.querySelectorAll('.text-2xl.font-bold.text-gray-900');
  if (statCards.length >= 4) {
    statCards[0].textContent = stats.completed;
    statCards[1].textContent = stats.inProgress;
    statCards[2].textContent = stats.overdue;
    statCards[3].textContent = stats.locked;
  }
}

function initializeModals() {
  // Initialize any modal functionality here
}

          // Reset button
          button.text(originalText).prop("disabled", false);

          // Show success notification
          Swal.fire({
            title: "Upload Complete!",
            text: `File "${file.name}" uploaded successfully for "${assignmentTitle}"`,
            icon: "success",
            confirmButtonText: "Great!",
            confirmButtonColor: "#10B981",
          });
        }, 2000);
      }
    });

    // Trigger file input
    document.body.appendChild(fileInput);
    fileInput.click();
    document.body.removeChild(fileInput);
  });

  // Filter buttons functionality
  $(".filter-btn").on("click", function () {
    const filter = $(this).data("filter");

    // Toggle active state
    $(this).toggleClass("active");

    if (filter === "dates") {
      // Sort by due date
      table.order([2, "asc"]).draw();
      Swal.fire({
        toast: true,
        position: "top-end",
        icon: "info",
        title: "Assignments sorted by due date",
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
      });
    } else if (filter === "Status") {
      // Sort by status
      table.order([3, "asc"]).draw();
      Swal.fire({
        toast: true,
        position: "top-end",
        icon: "info",
        title: "Assignments sorted by status",
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
      });
    }
  });

  // Add custom styling to DataTable elements
  setTimeout(() => {
    // Style the search input
    $(".dataTables_filter input").addClass(
      "py-2 pl-3 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
    );

    // Style the length select
    $(".dataTables_length select").addClass(
      "px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
    );

    // Style pagination buttons
    $(".dataTables_paginate .paginate_button").addClass(
      "px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors"
    );

    $(".dataTables_paginate .paginate_button.current").addClass(
      "text-white bg-blue-600 border-blue-600 hover:bg-blue-700"
    );

    // Hide default search box since we have custom one
    $(".dataTables_filter").hide();
  }, 100);

  // Add row click functionality for better UX
  $("#assignmentsTable tbody").on("click", "tr", function () {
    $(this).toggleClass("bg-blue-50");
  });
});
