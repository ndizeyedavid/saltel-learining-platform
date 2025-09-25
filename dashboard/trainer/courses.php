<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • My Courses</title>
    <?php
    include '../../include/trainer-guard.php';
    include '../../include/connect.php';

    // Get trainer's courses with enrollment counts
    $trainer_id = $_SESSION['user_id'];
    $courses_query = "SELECT c.course_id, c.course_title, c.description, c.price, c.created_at, 
                             COALESCE(c.status, 'Draft') as status,
                             COALESCE(c.visibility, 'Public') as visibility,
                             COALESCE(c.level, 'Beginner') as level,
                             c.category,
                             COUNT(DISTINCT e.enrollment_id) as enrolled_count,
                             COUNT(DISTINCT a.assignment_id) as assignment_count,
                             c.image_url
                      FROM courses c
                      LEFT JOIN enrollments e ON c.course_id = e.course_id
                      LEFT JOIN assignments a ON c.course_id = a.course_id
                      WHERE c.teacher_id = ?
                      GROUP BY c.course_id
                      ORDER BY c.created_at DESC";

    $stmt = $conn->prepare($courses_query);
    $stmt->bind_param("i", $trainer_id);
    $stmt->execute();
    $courses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    include '../../include/trainer-imports.php';
    ?>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainer-Sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <?php include '../../components/Trainer-Header.php'; ?>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-y-auto">
                <!-- Header Section -->
                <div class="flex flex-col mb-8 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">My Courses</h1>
                        <p class="mt-1 text-sm text-gray-600">Manage your courses and track student progress</p>
                    </div>

                    <!-- Course Management Tools -->
                    <div class="flex items-center mt-4 space-x-3 sm:mt-0">
                        <div class="relative">
                            <input type="text" id="searchCourses" placeholder="Search courses..."
                                class="py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <i class="absolute text-gray-400 fas fa-search left-3 top-3"></i>
                        </div>

                        <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Status</option>
                            <option value="Draft">Draft</option>
                            <option value="Published">Published</option>
                            <option value="Archived">Archived</option>
                        </select>

                        <a href="course-builder.php">
                            <button class="px-4 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                <i class="mr-2 fas fa-plus"></i>Create Course
                            </button>
                        </a>
                    </div>
                </div>

                <!-- Course Statistics -->
                <div class="grid grid-cols-1 gap-4 mb-8 md:grid-cols-4">
                    <div class="p-4 bg-white border rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i class="text-blue-600 fas fa-graduation-cap"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Total Courses</p>
                                <p class="text-lg font-semibold text-gray-900"><?php echo count($courses); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-white border rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <i class="text-green-600 fas fa-eye"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Published</p>
                                <p class="text-lg font-semibold text-gray-900"><?php echo count(array_filter($courses, fn($c) => $c['status'] === 'Published')); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-white border rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-100 rounded-lg">
                                <i class="text-yellow-600 fas fa-edit"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Drafts</p>
                                <p class="text-lg font-semibold text-gray-900"><?php echo count(array_filter($courses, fn($c) => $c['status'] === 'Draft')); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-white border rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <i class="text-purple-600 fas fa-users"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500">Total Students</p>
                                <p class="text-lg font-semibold text-gray-900"><?php echo array_sum(array_column($courses, 'enrolled_count')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Grid -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3" id="coursesGrid">
                    <?php if (empty($courses)): ?>
                        <div class="col-span-full">
                            <div class="p-12 text-center bg-white border border-gray-300 border-dashed rounded-lg">
                                <i class="mb-4 text-4xl text-gray-300 fas fa-graduation-cap"></i>
                                <h3 class="mb-2 text-lg font-medium text-gray-900">No courses yet</h3>
                                <p class="mb-6 text-gray-600">Create your first course to get started with teaching.</p>
                                <a href="course-builder.php">
                                    <button class="px-6 py-3 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                        <i class="mr-2 fas fa-plus"></i>Create Your First Course
                                    </button>
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($courses as $course): ?>
                            <div class="transition-shadow bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md course-card"
                                data-status="<?php echo $course['status']; ?>"
                                data-title="<?php echo strtolower($course['course_title']); ?>">

                                <!-- Course Image -->
                                <div class="relative">
                                    <?php if ($course['image_url']): ?>
                                        <img src="../../<?php echo $course['image_url']; ?>" alt="<?php echo htmlspecialchars($course['course_title']); ?>"
                                            class="object-cover w-full h-48 rounded-t-lg">
                                    <?php else: ?>
                                        <div class="flex items-center justify-center w-full h-48 rounded-t-lg bg-gradient-to-br from-blue-400 to-blue-600">
                                            <i class="text-4xl text-white fas fa-graduation-cap"></i>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Status Badge -->
                                    <div class="absolute top-3 right-3">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            <?php echo $course['status'] === 'Published' ? 'bg-green-100 text-green-800' : ($course['status'] === 'Draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'); ?>">
                                            <?php echo $course['status']; ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- Course Content -->
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-3">
                                        <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">
                                            <?php echo htmlspecialchars($course['course_title']); ?>
                                        </h3>
                                    </div>

                                    <p class="mb-4 text-sm text-gray-600 line-clamp-2">
                                        <?php echo htmlspecialchars(substr($course['description'], 0, 120)) . (strlen($course['description']) > 120 ? '...' : ''); ?>
                                    </p>

                                    <!-- Course Meta -->
                                    <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                                        <div class="flex items-center space-x-4">
                                            <span class="flex items-center">
                                                <i class="mr-1 fas fa-users"></i>
                                                <?php echo $course['enrolled_count']; ?> students
                                            </span>
                                            <span class="flex items-center">
                                                <i class="mr-1 fas fa-tasks"></i>
                                                <?php echo $course['assignment_count']; ?> assignments
                                            </span>
                                        </div>
                                        <span class="font-medium text-blue-600">
                                            $<?php echo number_format($course['price'], 2); ?>
                                        </span>
                                    </div>

                                    <!-- Course Tags -->
                                    <div class="flex items-center mb-4 space-x-2">
                                        <?php if ($course['category']): ?>
                                            <span class="px-2 py-1 text-xs text-blue-800 bg-blue-100 rounded-full">
                                                <?php echo $course['category']; ?>
                                            </span>
                                        <?php endif; ?>
                                        <span class="px-2 py-1 text-xs text-gray-800 bg-gray-100 rounded-full">
                                            <?php echo $course['level']; ?>
                                        </span>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <div class="flex items-center space-x-2">
                                            <a href="course-content.php?course_id=<?php echo $course['course_id']; ?>"
                                                class="text-blue-600 transition-colors hover:text-blue-700" title="View Content">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="course-builder.php?id=<?php echo $course['course_id']; ?>"
                                                class="text-green-600 transition-colors hover:text-green-700" title="Edit Course">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="deleteCourse(<?php echo $course['course_id']; ?>)"
                                                class="text-red-600 transition-colors hover:text-red-700" title="Delete Course">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <div class="text-xs text-gray-500">
                                            Created <?php echo date('M j, Y', strtotime($course['created_at'])); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- JavaScript for filtering and course management -->
    <script>
        // Search functionality
        document.getElementById('searchCourses').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            filterCourses();
        });

        // Status filter functionality
        document.getElementById('statusFilter').addEventListener('change', function() {
            filterCourses();
        });

        function filterCourses() {
            const searchTerm = document.getElementById('searchCourses').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const courseCards = document.querySelectorAll('.course-card');

            courseCards.forEach(card => {
                const title = card.dataset.title;
                const status = card.dataset.status;

                const matchesSearch = title.includes(searchTerm);
                const matchesStatus = !statusFilter || status === statusFilter;

                if (matchesSearch && matchesStatus) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Delete course function
        function deleteCourse(courseId) {
            if (confirm('Are you sure you want to delete this course? This action cannot be undone.')) {
                fetch('../../dashboard/api/courses/delete.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            course_id: courseId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error deleting course: ' + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the course.');
                    });
            }
        }

        // Add some CSS for line-clamp
        const style = document.createElement('style');
        style.textContent = `
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>