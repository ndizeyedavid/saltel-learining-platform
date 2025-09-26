<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../");
    exit();
}
include '../../include/imports.php';
require_once '../../php/connect.php';

$student_id = $_SESSION['user_id'];

// Get student profile
$student_query = "SELECT * FROM students WHERE user_id = ?";
$stmt = $conn->prepare($student_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Get upcoming assignments for calendar
$assignments_query = "SELECT a.assignment_id, a.title, a.due_date, a.description, c.course_title as course_title
    FROM assignments a
    JOIN courses c ON a.course_id = c.course_id
    JOIN enrollments e ON c.course_id = e.course_id
    WHERE e.student_id = ? AND a.due_date >= CURDATE()
    ORDER BY a.due_date ASC";
$assignments_stmt = $conn->prepare($assignments_query);
$assignments_stmt->bind_param("i", $student_id);
$assignments_stmt->execute();
$upcoming_assignments = $assignments_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get enrolled courses for potential events
$courses_query = "SELECT c.course_id, c.course_title, c.description, e.enrolled_at
    FROM enrollments e
    JOIN courses c ON e.course_id = c.course_id
    WHERE e.student_id = ? AND e.payment_status = 'Paid'
    ORDER BY e.enrolled_at DESC";
$courses_stmt = $conn->prepare($courses_query);
$courses_stmt->bind_param("i", $user['student_id']);
$courses_stmt->execute();
$enrolled_courses = $courses_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get study sessions or other calendar events (if you have such a table)
// For now, we'll create some sample study sessions
$study_sessions = [
    [
        'title' => 'Daily Study Session',
        'start' => date('Y-m-d') . 'T09:00:00',
        'end' => date('Y-m-d') . 'T11:00:00',
        'type' => 'study'
    ],
    [
        'title' => 'Review Session',
        'start' => date('Y-m-d', strtotime('+1 day')) . 'T14:00:00',
        'end' => date('Y-m-d', strtotime('+1 day')) . 'T16:00:00',
        'type' => 'review'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar - Saltel Learning Platform</title>
    <?php include '../../include/imports.php'; ?>
    <!-- FullCalendar CDN -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainee-Sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Header -->
            <?php include '../../components/Trainee-Header.php'; ?>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-y-auto">
                <div class="max-w-7xl mx-auto">
                    <!-- Header -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="mb-2 text-3xl font-bold text-gray-900">Event Calendar</h1>
                                <p class="text-gray-600">Manage your assignments, study sessions, and important dates</p>
                            </div>
                            <button id="addEventBtn" class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                <i class="mr-2 fas fa-plus"></i>Add Event
                            </button>
                        </div>
                    </div>

                    <!-- Calendar Controls -->
                    <div class="mb-6">
                        <div class="flex flex-wrap items-center justify-between gap-4 p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                    <span class="text-sm text-gray-600">Assignments</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                    <span class="text-sm text-gray-600">Study Sessions</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    <span class="text-sm text-gray-600">Course Events</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                                    <span class="text-sm text-gray-600">Personal</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button id="todayBtn" class="px-3 py-1 text-sm text-gray-600 transition-colors border border-gray-300 rounded hover:bg-gray-50">
                                    Today
                                </button>
                                <div class="flex border border-gray-300 rounded">
                                    <button id="prevBtn" class="px-3 py-1 text-sm text-gray-600 transition-colors hover:bg-gray-50">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button id="nextBtn" class="px-3 py-1 text-sm text-gray-600 transition-colors border-l border-gray-300 hover:bg-gray-50">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Calendar -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div id="calendar" class="p-6"></div>
                    </div>

                    <!-- Upcoming Events Sidebar -->
                    <div class="grid grid-cols-1 gap-6 mt-8 lg:grid-cols-3">
                        <!-- Upcoming Assignments -->
                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900">Upcoming Assignments</h3>
                            <div class="space-y-3">
                                <?php if (!empty($upcoming_assignments)): ?>
                                    <?php foreach (array_slice($upcoming_assignments, 0, 5) as $assignment): ?>
                                        <div class="p-3 border border-red-200 rounded-lg bg-red-50">
                                            <h4 class="font-medium text-gray-900"><?php echo htmlspecialchars($assignment['title']); ?></h4>
                                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($assignment['course_title']); ?></p>
                                            <p class="text-xs text-red-600">
                                                <i class="mr-1 far fa-clock"></i>
                                                Due: <?php echo date('M j, Y g:i A', strtotime($assignment['due_date'])); ?>
                                            </p>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-sm text-gray-500">No upcoming assignments</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Enrolled Courses -->
                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900">Active Courses</h3>
                            <div class="space-y-3">
                                <?php if (!empty($enrolled_courses)): ?>
                                    <?php foreach (array_slice($enrolled_courses, 0, 5) as $course): ?>
                                        <div class="p-3 border border-green-200 rounded-lg bg-green-50">
                                            <h4 class="font-medium text-gray-900"><?php echo htmlspecialchars($course['course_title']); ?></h4>
                                            <p class="text-xs text-green-600">
                                                <i class="mr-1 fas fa-calendar-plus"></i>
                                                Enrolled: <?php echo date('M j, Y', strtotime($course['enrolled_at'])); ?>
                                            </p>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-sm text-gray-500">No active courses</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900">This Month</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Assignments Due</span>
                                    <span class="font-semibold text-red-600"><?php echo count($upcoming_assignments); ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Active Courses</span>
                                    <span class="font-semibold text-green-600"><?php echo count($enrolled_courses); ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Study Hours</span>
                                    <span class="font-semibold text-blue-600">24h</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Completion Rate</span>
                                    <span class="font-semibold text-purple-600">85%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- Pass data to JavaScript -->
        <script>
            window.calendarData = {
                assignments: <?php echo json_encode($upcoming_assignments); ?>,
                courses: <?php echo json_encode($enrolled_courses); ?>,
                studySessions: <?php echo json_encode($study_sessions); ?>
            };
        </script>

        <!-- Calendar JavaScript -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const calendarEl = document.getElementById('calendar');

                // Prepare events from different sources
                const events = [];

                // Add assignments
                if (window.calendarData.assignments) {
                    window.calendarData.assignments.forEach(assignment => {
                        events.push({
                            id: 'assignment_' + assignment.assignment_id,
                            title: assignment.title,
                            start: assignment.due_date + 'T23:59:00',
                            backgroundColor: '#ef4444',
                            borderColor: '#dc2626',
                            textColor: '#ffffff',
                            extendedProps: {
                                type: 'assignment',
                                description: assignment.description,
                                course: assignment.course_title,
                                assignmentId: assignment.assignment_id
                            }
                        });
                    });
                }

                // Add study sessions
                if (window.calendarData.studySessions) {
                    window.calendarData.studySessions.forEach((session, index) => {
                        events.push({
                            id: 'study_' + index,
                            title: session.title,
                            start: session.start,
                            end: session.end,
                            backgroundColor: '#3b82f6',
                            borderColor: '#2563eb',
                            textColor: '#ffffff',
                            extendedProps: {
                                type: 'study',
                                description: 'Dedicated study time'
                            }
                        });
                    });
                }

                // Add course enrollment dates as milestones
                if (window.calendarData.courses) {
                    window.calendarData.courses.forEach(course => {
                        events.push({
                            id: 'course_' + course.course_id,
                            title: 'Started: ' + course.course_title,
                            start: course.enrolled_at,
                            backgroundColor: '#10b981',
                            borderColor: '#059669',
                            textColor: '#ffffff',
                            extendedProps: {
                                type: 'course',
                                description: course.description,
                                courseId: course.course_id
                            }
                        });
                    });
                }

                // Initialize FullCalendar
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    height: 'auto',
                    events: events,
                    eventDisplay: 'block',
                    dayMaxEvents: 3,
                    moreLinkClick: 'popover',
                    eventTimeFormat: {
                        hour: 'numeric',
                        minute: '2-digit',
                        meridiem: 'short'
                    },
                    eventClick: function(info) {
                        showEventDetails(info.event);
                    },
                    dateClick: function(info) {
                        showAddEventModal(info.dateStr);
                    },
                    eventDidMount: function(info) {
                        // Add tooltips
                        info.el.setAttribute('title', info.event.title);
                    }
                });

                calendar.render();

                // Calendar controls
                document.getElementById('todayBtn').addEventListener('click', function() {
                    calendar.today();
                });

                document.getElementById('prevBtn').addEventListener('click', function() {
                    calendar.prev();
                });

                document.getElementById('nextBtn').addEventListener('click', function() {
                    calendar.next();
                });

                document.getElementById('addEventBtn').addEventListener('click', function() {
                    showAddEventModal();
                });

                // Event details modal
                function showEventDetails(event) {
                    const props = event.extendedProps;
                    let content = `<div class="text-left">`;

                    if (props.type === 'assignment') {
                        content += `
                            <div class="mb-4">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                                    <i class="mr-1 fas fa-tasks"></i>Assignment
                                </span>
                            </div>
                            <h3 class="mb-2 text-lg font-semibold">${event.title}</h3>
                            <p class="mb-2 text-sm text-gray-600"><strong>Course:</strong> ${props.course}</p>
                            <p class="mb-2 text-sm text-gray-600"><strong>Due:</strong> ${event.start.toLocaleString()}</p>
                            ${props.description ? `<p class="text-sm text-gray-600"><strong>Description:</strong> ${props.description}</p>` : ''}
                        `;
                    } else if (props.type === 'study') {
                        content += `
                            <div class="mb-4">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                                    <i class="mr-1 fas fa-book"></i>Study Session
                                </span>
                            </div>
                            <h3 class="mb-2 text-lg font-semibold">${event.title}</h3>
                            <p class="mb-2 text-sm text-gray-600"><strong>Start:</strong> ${event.start.toLocaleString()}</p>
                            <p class="mb-2 text-sm text-gray-600"><strong>End:</strong> ${event.end ? event.end.toLocaleString() : 'No end time'}</p>
                            ${props.description ? `<p class="text-sm text-gray-600"><strong>Description:</strong> ${props.description}</p>` : ''}
                        `;
                    } else if (props.type === 'course') {
                        content += `
                            <div class="mb-4">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                    <i class="mr-1 fas fa-graduation-cap"></i>Course
                                </span>
                            </div>
                            <h3 class="mb-2 text-lg font-semibold">${event.title}</h3>
                            <p class="mb-2 text-sm text-gray-600"><strong>Enrolled:</strong> ${event.start.toLocaleDateString()}</p>
                            ${props.description ? `<p class="text-sm text-gray-600"><strong>Description:</strong> ${props.description}</p>` : ''}
                        `;
                    }

                    content += `</div>`;

                    Swal.fire({
                        title: 'Event Details',
                        html: content,
                        showCancelButton: false,
                        confirmButtonText: 'Close',
                        confirmButtonColor: '#3b82f6',
                        width: '500px'
                    });
                }

                // Add event modal
                function showAddEventModal(date = null) {
                    const today = new Date().toISOString().split('T')[0];
                    const defaultDate = date || today;

                    Swal.fire({
                        title: 'Add New Event',
                        html: `
                            <div class="space-y-4 text-left">
                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700">Event Title</label>
                                    <input id="eventTitle" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter event title">
                                </div>
                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700">Event Type</label>
                                    <select id="eventType" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="personal">Personal</option>
                                        <option value="study">Study Session</option>
                                        <option value="reminder">Reminder</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700">Date</label>
                                    <input id="eventDate" type="date" value="${defaultDate}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700">Time (Optional)</label>
                                    <input id="eventTime" type="time" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700">Description (Optional)</label>
                                    <textarea id="eventDescription" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Event description"></textarea>
                                </div>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Add Event',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#6b7280',
                        width: '500px',
                        preConfirm: () => {
                            const title = document.getElementById('eventTitle').value;
                            const type = document.getElementById('eventType').value;
                            const date = document.getElementById('eventDate').value;
                            const time = document.getElementById('eventTime').value;
                            const description = document.getElementById('eventDescription').value;

                            if (!title.trim()) {
                                Swal.showValidationMessage('Please enter an event title');
                                return false;
                            }

                            if (!date) {
                                Swal.showValidationMessage('Please select a date');
                                return false;
                            }

                            return {
                                title: title.trim(),
                                type: type,
                                date: date,
                                time: time,
                                description: description.trim()
                            };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            addNewEvent(result.value);
                        }
                    });
                }

                // Add new event to calendar
                function addNewEvent(eventData) {
                    const startDateTime = eventData.time ? 
                        eventData.date + 'T' + eventData.time + ':00' : 
                        eventData.date;

                    const colors = {
                        personal: { bg: '#8b5cf6', border: '#7c3aed' },
                        study: { bg: '#3b82f6', border: '#2563eb' },
                        reminder: { bg: '#f59e0b', border: '#d97706' }
                    };

                    const newEvent = {
                        id: 'custom_' + Date.now(),
                        title: eventData.title,
                        start: startDateTime,
                        backgroundColor: colors[eventData.type].bg,
                        borderColor: colors[eventData.type].border,
                        textColor: '#ffffff',
                        extendedProps: {
                            type: eventData.type,
                            description: eventData.description
                        }
                    };

                    calendar.addEvent(newEvent);

                    Swal.fire({
                        icon: 'success',
                        title: 'Event Added!',
                        text: 'Your event has been added to the calendar.',
                        confirmButtonColor: '#3b82f6',
                        timer: 2000,
                        timerProgressBar: true
                    });
                }
            });
        </script>

        <script src="../../assets/js/app.js"></script>
    </body>

</html>