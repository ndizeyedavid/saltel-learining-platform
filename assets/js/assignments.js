// Assignments Page Functionality with DataTables
document.addEventListener("DOMContentLoaded", function () {
  // Initialize DataTable
  const table = $("#assignmentsTable").DataTable({
    responsive: true,
    pageLength: 10,
    lengthMenu: [5, 10, 25, 50],
    order: [[2, "asc"]], // Sort by due date
    columnDefs: [
      {
        targets: [3, 4], // Status and Submit columns
        orderable: false,
      },
    ],
    language: {
      search: "",
      searchPlaceholder: "Search assignments...",
      lengthMenu: "Show _MENU_ assignments",
      info: "Showing _START_ to _END_ of _TOTAL_ assignments",
      infoEmpty: "No assignments found",
      infoFiltered: "(filtered from _MAX_ total assignments)",
    },
  });

  // Custom search functionality
  $("#assignmentSearch").on("keyup", function () {
    table.search(this.value).draw();
  });

  // Status filter functionality
  $("#statusFilter").on("change", function () {
    const selectedStatus = this.value;
    if (selectedStatus === "") {
      table.column(3).search("").draw();
    } else {
      table.column(3).search(selectedStatus).draw();
    }
  });

  // Upload button functionality
  $(document).on("click", ".upload-btn", function () {
    const row = $(this).closest("tr");
    const assignmentTitle = row.find("td:first").text();

    // Create file input
    const fileInput = document.createElement("input");
    fileInput.type = "file";
    fileInput.accept = ".pdf,.doc,.docx,.zip,.rar";
    fileInput.style.display = "none";

    fileInput.addEventListener("change", function (e) {
      const file = e.target.files[0];
      if (file) {
        // Show loading state
        const button = row.find(".upload-btn");
        const originalText = button.text();
        button.text("Uploading...").prop("disabled", true);

        // Simulate upload process
        setTimeout(() => {
          // Update status to "Progress" if it was "Pending"
          const statusCell = row.find("td:nth-child(4)");
          const currentStatus = statusCell.find("span").text().trim();

          if (currentStatus === "Pending") {
            statusCell.html(`
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                <span class="w-2 h-2 mr-1 bg-blue-400 rounded-full"></span>
                Progress
              </span>
            `);
          }

          // Reset button
          button.text(originalText).prop("disabled", false);

          // Show success notification
          if (typeof toastr !== "undefined") {
            toastr.success(
              `File "${file.name}" uploaded successfully for "${assignmentTitle}"`,
              "Upload Complete"
            );
          } else {
            alert(
              `File "${file.name}" uploaded successfully for "${assignmentTitle}"`
            );
          }
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
      if (typeof toastr !== "undefined") {
        toastr.info("Assignments sorted by due date", "Filter Applied");
      }
    } else if (filter === "Status") {
      // Sort by status
      table.order([3, "asc"]).draw();
      if (typeof toastr !== "undefined") {
        toastr.info("Assignments sorted by status", "Filter Applied");
      }
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
