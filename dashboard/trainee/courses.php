<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - Saltel • Trainee</title>
    <?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../");
        exit();
    }

    include '../../include/imports.php';
    require_once '../../include/connect.php';

    // Get student_id from user_id and check profile completion
    $user_id = $_SESSION['user_id'];
    $student_query = "SELECT student_id, institution, level_year, program FROM students WHERE user_id = ?";
    $stmt = $conn->prepare($student_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $profile_complete = false;
    $student_id = null;

    if ($result->num_rows > 0) {
        $student_data = $result->fetch_assoc();
        $student_id = $student_data['student_id'];
        // Check if all required fields are filled
        $profile_complete = !empty($student_data['institution']) &&
            !empty($student_data['level_year']) &&
            !empty($student_data['program']);
    }

    // Redirect to settings if profile is incomplete
    if (!$profile_complete) {
        header('Location: settings.php?incomplete=1');
        exit();
    }

    // Fetch all published courses with enrollment status for this student
    $sql = "SELECT c.*, u.first_name, u.last_name,
                   COUNT(DISTINCT e.enrollment_id) as enrolled_count,
                   COUNT(DISTINCT cl.lesson_id) as lesson_count,
                   COUNT(DISTINCT a.assignment_id) as assignment_count,
                   e2.enrollment_id as user_enrollment_id,
                   e2.payment_status as user_payment_status,
                   e2.enrolled_at as user_enrolled_at
            FROM courses c 
            LEFT JOIN users u ON c.teacher_id = u.user_id 
            LEFT JOIN enrollments e ON c.course_id = e.course_id
            LEFT JOIN course_lessons cl ON c.course_id = cl.course_id
            LEFT JOIN assignments a ON c.course_id = a.course_id
            LEFT JOIN enrollments e2 ON c.course_id = e2.course_id AND e2.student_id = ?
            WHERE c.status = 'Published' 
            GROUP BY c.course_id 
            ORDER BY c.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $courses = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Determine course status for the user
            $course_status = 'not_started';
            $progress_percentage = 0;

            if ($row['user_enrollment_id']) {
                if ($row['user_payment_status'] === 'Paid' || $row['price'] == 0) {
                    $course_status = 'enrolled';

                    // Calculate actual progress based on completed lessons and assignments
                    $total_activities = $row['lesson_count'] + $row['assignment_count'];
                    $completed_activities = 0;

                    if ($total_activities > 0) {
                        // Get completed lessons (study sessions >= 15 minutes)
                        $completed_lessons_query = "
                            SELECT COUNT(*) as completed_count
                            FROM study_sessions ss
                            WHERE ss.user_id = ? AND ss.course_id = ? AND ss.session_duration >= 15
                        ";
                        $stmt2 = $conn->prepare($completed_lessons_query);
                        $stmt2->bind_param("ii", $user_id, $row['course_id']);
                        $stmt2->execute();
                        $result2 = $stmt2->get_result();
                        $completed_lessons = $result2->fetch_assoc()['completed_count'];

                        // Get submitted assignments
                        $submitted_assignments_query = "
                            SELECT COUNT(*) as submitted_count
                            FROM submissions s
                            JOIN assignments a ON s.assignment_id = a.assignment_id
                            WHERE s.student_id = ? AND a.course_id = ?
                        ";
                        $stmt3 = $conn->prepare($submitted_assignments_query);
                        $stmt3->bind_param("ii", $student_id, $row['course_id']);
                        $stmt3->execute();
                        $result3 = $stmt3->get_result();
                        $submitted_assignments = $result3->fetch_assoc()['submitted_count'];

                        $completed_activities = $completed_lessons + $submitted_assignments;
                        $progress_percentage = round(($completed_activities / $total_activities) * 100);
                    } else {
                        $progress_percentage = 0;
                    }

                    if ($progress_percentage >= 100) {
                        $course_status = 'completed';
                    } elseif ($progress_percentage > 0) {
                        $course_status = 'in_progress';
                    }
                } else {
                    $course_status = 'pending_payment';
                    $progress_percentage = 0;
                }
            }

            // Check prerequisites for non-enrolled courses
            $has_prerequisites = false;
            $prerequisites_met = true;
            if (is_null($row['user_enrollment_id'])) {
                $prereq_query = "
                    SELECT COUNT(*) as prereq_count
                    FROM course_prerequisites cp
                    JOIN course_lessons cl ON cp.lesson_id = cl.lesson_id
                    WHERE cl.course_id = ?
                ";
                $stmt4 = $conn->prepare($prereq_query);
                $stmt4->bind_param("i", $row['course_id']);
                $stmt4->execute();
                $result4 = $stmt4->get_result();
                $prereq_count = $result4->fetch_assoc()['prereq_count'];

                if ($prereq_count > 0) {
                    $has_prerequisites = true;
                    // For now, we'll assume prerequisites are not met if they exist
                    // In a real implementation, you'd check each prerequisite
                    $prerequisites_met = false;
                }
            }

            $row['course_status'] = $course_status;
            $row['progress_percentage'] = $progress_percentage;
            $row['is_free'] = $row['price'] == 0;
            $row['is_enrolled'] = !is_null($row['user_enrollment_id']);
            $row['can_enroll'] = is_null($row['user_enrollment_id']) && $row['status'] === 'Published' && (!$has_prerequisites || $prerequisites_met);
            $row['has_prerequisites'] = $has_prerequisites;
            $row['prerequisites_met'] = $prerequisites_met;
            $row['teacher_name'] = $row['first_name'] . ' ' . $row['last_name'];

            $courses[] = $row;
        }
    }

    // Get unique categories for filter
    $categories_sql = "SELECT DISTINCT category FROM courses WHERE status = 'Published' ORDER BY category";
    $categories_result = $conn->query($categories_sql);
    $categories = [];
    if ($categories_result && $categories_result->num_rows > 0) {
        while ($row = $categories_result->fetch_assoc()) {
            $categories[] = $row['category'];
        }
    }
    ?>
    <script src="../../assets/js/courses.js" defer></script>
    <script src="../../assets/js/gamification.js" defer></script>
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
                <!-- Page Header -->
                <div class="mb-8">
                    <h1 class="mb-2 text-3xl font-bold text-gray-900">Browse Courses</h1>
                    <p class="text-gray-600">Discover and enroll in courses to enhance your skills</p>
                </div>

                <!-- Filter and Search Section -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Filter by course name"
                                class="py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                id="courseFilter">
                            <i class="absolute text-gray-400 left-3 top-3 fas fa-search"></i>
                        </div>
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="categoryFilter">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo strtolower(htmlspecialchars($category)); ?>"><?php echo htmlspecialchars($category); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <span>Filter by</span>
                        <button class="px-3 py-1 text-blue-600 transition-colors rounded-full bg-blue-50 hover:bg-blue-100 filter-btn active" data-filter="all">All</button>
                        <button class="px-3 py-1 text-gray-600 transition-colors rounded-full hover:text-blue-600 hover:bg-blue-50 filter-btn" data-filter="beginner">Beginner</button>
                        <button class="px-3 py-1 text-gray-600 transition-colors rounded-full hover:text-blue-600 hover:bg-blue-50 filter-btn" data-filter="intermediate">Intermediate</button>
                        <button class="px-3 py-1 text-gray-600 transition-colors rounded-full hover:text-blue-600 hover:bg-blue-50 filter-btn" data-filter="advanced">Advanced</button>
                    </div>
                </div>

                <!-- Courses Grid -->
                <div class="grid grid-cols-1 gap-8 mb-8 md:grid-cols-2 lg:grid-cols-3" id="coursesGrid">
                    <?php if (empty($courses)): ?>
                        <div class="col-span-full">
                            <div class="p-12 text-center bg-white border border-gray-300 border-dashed rounded-lg">
                                <i class="mb-4 text-4xl text-gray-300 fas fa-graduation-cap"></i>
                                <h3 class="mb-2 text-lg font-medium text-gray-900">No courses available</h3>
                                <p class="text-gray-600">There are no published courses at the moment. Check back later!</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($courses as $course): ?>
                            <div class="overflow-hidden relative transition-all duration-300 bg-white shadow-lg rounded-2xl hover:shadow-xl hover:-translate-y-1 course-card <?php echo ($course['has_prerequisites'] && !$course['prerequisites_met']) ? 'locked-course' : ''; ?>"
                                data-category="<?php echo strtolower(htmlspecialchars($course['category'])); ?>"
                                data-level="<?php echo strtolower($course['level']); ?>"
                                data-course-id="<?php echo $course['course_id']; ?>"
                                <?php if ($course['has_prerequisites'] && !$course['prerequisites_met']): ?>
                                data-locked="true"
                                data-unlock-requirement="Complete prerequisite courses"
                                <?php endif; ?>>

                                <!-- Lock Overlay for Prerequisites -->
                                <?php if ($course['has_prerequisites'] && !$course['prerequisites_met']): ?>
                                    <div class="absolute inset-0 z-10 flex items-center justify-center bg-gray-900 bg-opacity-70 rounded-2xl">
                                        <div class="px-6 text-center text-white">
                                            <i class="mb-4 text-5xl fas fa-lock"></i>
                                            <h4 class="mb-2 text-lg font-semibold">Course Locked</h4>
                                            <p class="mb-4 text-sm opacity-90">Complete prerequisite courses to unlock</p>
                                            <div class="space-y-2">
                                                <span class="block px-3 py-1 text-xs font-medium text-black bg-yellow-500 rounded-full">
                                                    <i class="mr-1 fas fa-trophy"></i>
                                                    Unlock Reward: +100 XP
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Hero Image -->
                                <div class="relative h-48 overflow-hidden bg-gradient-to-br from-blue-400 via-cyan-500 to-teal-500 <?php echo ($course['has_prerequisites'] && !$course['prerequisites_met']) ? 'opacity-50' : ''; ?>">
                                    <div class="absolute inset-0 bg-black bg-opacity-10"></div>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <?php if (!empty($course['image_url'])): ?>
                                            <img src="../../<?php echo htmlspecialchars($course['image_url']); ?>" alt="<?php echo htmlspecialchars($course['course_title']); ?>" class="object-cover w-full h-full">
                                        <?php else: ?>
                                            <img src="../../assets/images/courses/placeholder.png" alt="<?php echo htmlspecialchars($course['course_title']); ?>" class="object-cover w-full h-full">
                                        <?php endif; ?>
                                    </div>

                                    <!-- Status Badge -->
                                    <div class="absolute top-4 left-4">
                                        <?php
                                        $status_class = '';
                                        $status_text = '';
                                        switch ($course['course_status']) {
                                            case 'completed':
                                                $status_class = 'bg-green-500';
                                                $status_text = 'Completed';
                                                break;
                                            case 'in_progress':
                                                $status_class = 'bg-blue-500';
                                                $status_text = 'In Progress';
                                                break;
                                            case 'enrolled':
                                                $status_class = 'bg-blue-500';
                                                $status_text = 'Enrolled';
                                                break;
                                            case 'pending_payment':
                                                $status_class = 'bg-yellow-500';
                                                $status_text = 'Payment Pending';
                                                break;
                                            default:
                                                $status_class = 'bg-gray-500';
                                                $status_text = 'Not Started';
                                                break;
                                        }
                                        ?>
                                        <span class="px-3 py-1 text-xs font-semibold text-white <?php echo $status_class; ?> rounded-full shadow-lg">
                                            <?php echo $status_text; ?>
                                        </span>
                                    </div>

                                    <!-- Free Badge -->
                                    <?php if ($course['is_free']): ?>
                                        <div class="absolute top-4 right-4">
                                            <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                                FREE
                                            </span>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Progress Bar (for enrolled courses) -->
                                    <?php if (in_array($course['course_status'], ['in_progress', 'enrolled']) && $course['progress_percentage'] > 0): ?>
                                        <div class="absolute bottom-0 left-0 right-0 h-1 bg-black bg-opacity-20">
                                            <div class="h-full bg-white bg-opacity-80" style="width: <?php echo $course['progress_percentage']; ?>%"></div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Content -->
                                <div class="p-6 <?php echo ($course['has_prerequisites'] && !$course['prerequisites_met']) ? 'opacity-40' : ''; ?>">
                                    <div class="mb-4">
                                        <h3 class="mb-2 text-xl font-bold text-gray-900"><?php echo htmlspecialchars($course['course_title']); ?></h3>
                                        <p class="text-sm leading-relaxed text-gray-600">
                                            <?php echo htmlspecialchars(substr($course['description'], 0, 120)) . (strlen($course['description']) > 120 ? '...' : ''); ?>
                                        </p>
                                    </div>

                                    <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                                        <span><?php echo date('M j, Y', strtotime($course['created_at'])); ?> • <?php echo htmlspecialchars($course['category']); ?></span>
                                        <span><?php echo $course['enrolled_count']; ?> participants</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                                            <span><i class="mr-1 far fa-clock"></i><?php echo $course['lesson_count']; ?> lessons</span>
                                            <span><i class="mr-1 fas fa-tasks"></i><?php echo $course['assignment_count']; ?> assignments</span>
                                        </div>

                                        <!-- Action Button -->
                                        <div class="text-right">
                                            <?php if ($course['course_status'] === 'completed'): ?>
                                                <button onclick="viewCertificate(<?php echo $course['course_id']; ?>)" class="px-4 py-2 text-sm font-medium text-green-600 transition-colors rounded-lg bg-green-50 hover:bg-green-100">
                                                    View Certificate
                                                </button>
                                            <?php elseif (in_array($course['course_status'], ['in_progress', 'enrolled'])): ?>
                                                <a href="course-viewer.php?course=<?php echo $course['course_id']; ?>" class="px-2 py-2 text-xs font-medium text-blue-600 transition-colors rounded-lg bg-blue-50 hover:bg-blue-100">
                                                    Continue Learning
                                                </a>
                                            <?php elseif ($course['course_status'] === 'pending_payment'): ?>
                                                <button onclick="completePayment(<?php echo $course['course_id']; ?>, '<?php echo htmlspecialchars($course['course_title']); ?>', <?php echo $course['price']; ?>)" class="px-4 py-2 text-sm font-medium text-white transition-colors bg-yellow-600 rounded-lg hover:bg-yellow-700">
                                                    Complete Payment
                                                </button>
                                            <?php elseif ($course['can_enroll']): ?>
                                                <?php if ($course['is_free']): ?>
                                                    <button onclick="enrollInCourse(<?php echo $course['course_id']; ?>)" class="px-4 py-2 text-sm font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                                                        Start Free
                                                    </button>
                                                <?php else: ?>
                                                    <div>
                                                        <div class="text-lg font-bold text-gray-900"><?php echo number_format($course['price']); ?> RWF</div>
                                                        <button onclick="openCheckoutModal(<?php echo $course['course_id']; ?>, '<?php echo htmlspecialchars($course['course_title']); ?>', <?php echo $course['price']; ?>)" class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                                            Enroll Now
                                                        </button>
                                                    </div>
                                                <?php endif; ?>
                                            <?php elseif ($course['has_prerequisites'] && !$course['prerequisites_met']): ?>
                                                <button class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-200 rounded-lg cursor-not-allowed" disabled>
                                                    <i class="mr-1 fas fa-lock"></i>Locked
                                                </button>
                                            <?php else: ?>
                                                <button class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-200 rounded-lg cursor-not-allowed" disabled>
                                                    Not Available
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600">Show</span>
                        <select class="px-3 py-1 text-sm border border-gray-300 rounded-lg" id="itemsPerPage">
                            <option value="6">6</option>
                            <option value="9">9</option>
                            <option value="12">12</option>
                        </select>
                        <span class="text-sm text-gray-600">courses per page</span>
                    </div>

                    <nav class="flex items-center space-x-1" id="pagination">
                        <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-50 disabled:opacity-50" id="prevPage" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 hover:bg-blue-700 page-btn active" data-page="1">
                            1
                        </button>
                        <button class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 page-btn" data-page="2">
                            2
                        </button>
                        <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-50" id="nextPage">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </nav>
                </div>
            </main>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div id="checkoutModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">Course Enrollment</h3>
                <button class="text-gray-400 transition-colors hover:text-gray-600" onclick="closeCheckoutModal()">
                    <i class="text-xl fas fa-times"></i>
                </button>
            </div>

            <div class="p-6">
                <!-- Course Info -->
                <div class="mb-6">
                    <h4 class="mb-2 text-lg font-semibold text-gray-900" id="modalCourseTitle">Course Title</h4>
                    <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50">
                        <span class="text-gray-600">Course Price:</span>
                        <span class="text-2xl font-bold text-blue-600" id="modalCoursePrice">0 RWF</span>
                    </div>
                </div>

                <!-- Payment Form -->
                <form id="checkoutForm">
                    <div class="space-y-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="fullName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your full name" required>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your email" required>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Card Number</label>
                            <input type="text" name="cardNumber" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="1234 5678 9012 3456" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Expiry Date</label>
                                <input type="text" name="expiryDate" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="MM/YY" required>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">CVV</label>
                                <input type="text" name="cvv" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="123" required>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center mt-6 space-x-3">
                        <button type="button" class="flex-1 px-4 py-2 text-gray-600 transition-colors border border-gray-300 rounded-lg hover:text-gray-700 hover:bg-gray-50" onclick="closeCheckoutModal()">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-6 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                            <i class="mr-2 fas fa-credit-card"></i>
                            Complete Purchase
                        </button>
                    </div>
                </form>

                <!-- Security Notice -->
                <div class="flex items-center p-3 mt-4 rounded-lg bg-green-50">
                    <i class="mr-2 text-green-600 fas fa-shield-alt"></i>
                    <span class="text-sm text-green-700">Your payment is secured with 256-bit SSL encryption</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentCourse = null;

        function openCheckoutModal(courseId, courseTitle, coursePrice) {
            currentCourse = courseId;
            document.getElementById('modalCourseTitle').textContent = courseTitle;
            document.getElementById('modalCoursePrice').textContent = Number(coursePrice).toLocaleString() + ' RWF';
            document.getElementById('checkoutModal').classList.remove('hidden');
            document.getElementById('checkoutModal').classList.add('flex');
        }

        function closeCheckoutModal() {
            document.getElementById('checkoutModal').classList.add('hidden');
            document.getElementById('checkoutModal').classList.remove('flex');
            currentCourse = null;
        }

        // Handle pending payment completion
        async function completePayment(courseId, courseTitle, coursePrice) {
            // Show confirmation dialog
            const result = await Swal.fire({
                title: 'Complete Payment',
                text: `Complete payment for "${courseTitle}" (${Number(coursePrice).toLocaleString()} RWF)?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Continue to Payment',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280'
            });

            if (result.isConfirmed) {
                openCheckoutModal(courseId, courseTitle, coursePrice);
            }
        }

        // Handle certificate viewing
        async function viewCertificate(courseId) {
            try {
                const response = await fetch(`../../api/trainee/certificates.php?course_id=${courseId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    if (result.already_issued) {
                        // Certificate already exists, open it
                        window.open(result.certificate_url, '_blank');
                    } else {
                        // Generate new certificate
                        const generateResponse = await fetch('../../api/trainee/certificates.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                course_id: courseId
                            })
                        });

                        const generateResult = await generateResponse.json();

                        if (generateResponse.ok && generateResult.success) {
                            Swal.fire({
                                title: 'Certificate Generated!',
                                text: 'Your certificate has been generated successfully.',
                                icon: 'success',
                                confirmButtonText: 'View Certificate',
                                confirmButtonColor: '#10b981'
                            }).then(() => {
                                window.open(generateResult.certificate_url, '_blank');
                            });
                        } else {
                            Swal.fire({
                                title: 'Certificate Generation Failed',
                                text: generateResult.error || 'Something went wrong. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                } else {
                    Swal.fire({
                        title: 'Certificate Error',
                        text: result.error || 'Something went wrong. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            } catch (error) {
                console.error('Certificate error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Network error. Please check your connection and try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        }

        // Handle free course enrollment
        async function enrollInCourse(courseId) {
            // Show loading state
            const enrollButton = event.target;
            const originalText = enrollButton.innerHTML;
            enrollButton.innerHTML = '<i class="mr-2 fas fa-spinner fa-spin"></i>Enrolling...';
            enrollButton.disabled = true;

            try {
                // console.log(courseId);
                const response = await fetch('../api/trainee/enroll.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        course_id: courseId
                    })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    // Show success message with more details
                    let successMessage = 'You have been successfully enrolled in the course!';
                    if (result.payment_status === 'Paid') {
                        successMessage += ' You can now start learning immediately.';
                    } else if (result.payment_status === 'Pending') {
                        successMessage += ' Please complete your payment to access course content.';
                    }

                    Swal.fire({
                        title: 'Enrollment Successful!',
                        text: successMessage,
                        icon: 'success',
                        confirmButtonText: 'Start Learning',
                        confirmButtonColor: '#10b981',
                        showCancelButton: true,
                        cancelButtonText: 'View All Courses',
                        cancelButtonColor: '#6b7280'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to course viewer
                            window.location.href = `course-viewer.php?course=${courseId}`;
                        } else {
                            // Refresh course data without full page reload
                            window.location.reload();
                        }
                    });
                } else {
                    // Handle specific error cases
                    let errorTitle = 'Enrollment Failed';
                    let errorMessage = result.error || 'Something went wrong. Please try again.';
                    let showRedirectButton = false;

                    console.log(response);

                    if (response.status === 409) {
                        errorTitle = 'Already Enrolled';
                        errorMessage = 'You are already enrolled in this course.';
                    } else if (response.status === 404) {
                        errorTitle = 'Course Not Found';
                        errorMessage = 'The course you are trying to enroll in could not be found.';
                    } else if (response.status === 400) {
                        errorTitle = 'Enrollment Not Available';
                        errorMessage = 'This course is not available for enrollment at the moment.';
                    } else if (response.status === 403 && result.redirect) {
                        errorTitle = 'Profile Required';
                        errorMessage = result.message || 'Please complete your student profile before enrolling in courses.';
                        showRedirectButton = true;
                    }

                    if (showRedirectButton) {
                        Swal.fire({
                            title: errorTitle,
                            text: errorMessage,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Complete Profile',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#10b981',
                            cancelButtonColor: '#6b7280'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'settings.php';
                            }
                        });
                    } else {
                        Swal.fire({
                            title: errorTitle,
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                }
            } catch (error) {
                console.error('Enrollment error:', error);
                Swal.fire({
                    title: 'Network Error',
                    text: 'Unable to connect to the server. Please check your internet connection and try again.',
                    icon: 'error',
                    confirmButtonText: 'Retry',
                    confirmButtonColor: '#ef4444',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Retry enrollment
                        enrollInCourse(courseId);
                    }
                });
            } finally {
                // Reset button state
                enrollButton.innerHTML = originalText;
                enrollButton.disabled = false;
            }
        }

        // Handle form submission for paid courses
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!currentCourse) {
                Swal.fire({
                    title: 'Error',
                    text: 'No course selected.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Validate form data
            const formData = new FormData(e.target);
            const fullName = formData.get('fullName') || e.target.querySelector('input[type="text"]').value;
            const email = formData.get('email') || e.target.querySelector('input[type="email"]').value;
            const cardNumber = formData.get('cardNumber') || e.target.querySelector('input[placeholder*="1234"]').value;
            const expiryDate = formData.get('expiryDate') || e.target.querySelector('input[placeholder*="MM/YY"]').value;
            const cvv = formData.get('cvv') || e.target.querySelector('input[placeholder*="123"]').value;

            // Basic validation
            if (!fullName || !email || !cardNumber || !expiryDate || !cvv) {
                Swal.fire({
                    title: 'Validation Error',
                    text: 'Please fill in all required fields.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Simulate payment processing
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="mr-2 fas fa-spinner fa-spin"></i>Processing Payment...';
            submitBtn.disabled = true;

            // Simulate payment processing delay
            setTimeout(async () => {
                try {
                    // In a real application, you would process the payment here
                    // For now, we'll simulate a successful payment and enrollment
                    const response = await fetch('../../api/trainee/enroll.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            course_id: currentCourse
                        })
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        // Show payment success with enrollment details
                        Swal.fire({
                            title: 'Payment Successful!',
                            html: `
                                <div class="text-center">
                                    <i class="mb-4 text-4xl text-green-500 fas fa-check-circle"></i>
                                    <p class="mb-2">Your payment has been processed successfully.</p>
                                    <p class="mb-4">You have been enrolled in the course!</p>
                                    <div class="p-3 text-sm rounded-lg bg-gray-50">
                                        <p><strong>Enrollment ID:</strong> ${result.enrollment_id}</p>
                                        <p><strong>Status:</strong> ${result.payment_status}</p>
                                    </div>
                                </div>
                            `,
                            icon: 'success',
                            confirmButtonText: 'Start Learning',
                            confirmButtonColor: '#10b981',
                            showCancelButton: true,
                            cancelButtonText: 'View All Courses',
                            cancelButtonColor: '#6b7280'
                        }).then((result) => {
                            closeCheckoutModal();
                            if (result.isConfirmed) {
                                // Redirect to course viewer
                                window.location.href = `course-viewer.php?course=${currentCourse}`;
                            } else {
                                // Refresh course data without full page reload
                                refreshCourseData();
                            }
                        });
                    } else {
                        // Handle enrollment errors
                        let errorTitle = 'Payment Failed';
                        let errorMessage = result.error || 'Payment could not be processed. Please try again.';
                        let showRedirectButton = false;

                        if (response.status === 409) {
                            errorTitle = 'Already Enrolled';
                            errorMessage = 'You are already enrolled in this course.';
                        } else if (response.status === 404) {
                            errorTitle = 'Course Not Found';
                            errorMessage = 'The course you are trying to enroll in could not be found.';
                        } else if (response.status === 403 && result.redirect) {
                            errorTitle = 'Profile Required';
                            errorMessage = result.message || 'Please complete your student profile before enrolling in courses.';
                            showRedirectButton = true;
                        }

                        if (showRedirectButton) {
                            Swal.fire({
                                title: errorTitle,
                                text: errorMessage,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Complete Profile',
                                cancelButtonText: 'Cancel',
                                confirmButtonColor: '#10b981',
                                cancelButtonColor: '#6b7280'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    closeCheckoutModal();
                                    window.location.href = 'settings.php';
                                }
                            });
                        } else {
                            Swal.fire({
                                title: errorTitle,
                                text: errorMessage,
                                icon: 'error',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#ef4444'
                            });
                        }
                    }
                } catch (error) {
                    console.error('Payment error:', error);
                    Swal.fire({
                        title: 'Network Error',
                        text: 'Unable to process payment. Please check your connection and try again.',
                        icon: 'error',
                        confirmButtonText: 'Retry',
                        confirmButtonColor: '#ef4444',
                        showCancelButton: true,
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Retry payment
                            e.target.dispatchEvent(new Event('submit'));
                        }
                    });
                } finally {
                    // Reset button
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            }, 2000);
        });

        // Close modal when clicking outside
        document.getElementById('checkoutModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCheckoutModal();
            }
        });

        // Function to refresh course data without full page reload
        async function refreshCourseData() {
            try {
                // Show loading indicator
                const courseGrid = document.getElementById('courseGrid');
                const originalContent = courseGrid.innerHTML;
                courseGrid.innerHTML = '<div class="flex items-center justify-center py-8 col-span-full"><i class="text-2xl text-blue-500 fas fa-spinner fa-spin"></i></div>';

                // Fetch updated course data
                const response = await fetch('../../api/trainee/courses.php');
                const result = await response.json();

                if (response.ok && result.courses) {
                    // Update the course grid with new data
                    updateCourseGrid(result.courses);
                } else {
                    // Restore original content on error
                    courseGrid.innerHTML = originalContent;
                    console.error('Failed to refresh course data:', result.error);
                }
            } catch (error) {
                console.error('Error refreshing course data:', error);
                // Restore original content on error
                document.getElementById('courseGrid').innerHTML = originalContent;
            }
        }

        // Function to update course grid with new data
        function updateCourseGrid(courses) {
            const courseGrid = document.getElementById('courseGrid');
            courseGrid.innerHTML = '';

            if (courses.length === 0) {
                courseGrid.innerHTML = '<div class="py-8 text-center text-gray-500 col-span-full">No courses found matching your criteria.</div>';
                return;
            }

            courses.forEach(course => {
                const courseCard = createCourseCard(course);
                courseGrid.appendChild(courseCard);
            });
        }

        // Function to create course card HTML
        function createCourseCard(course) {
            const card = document.createElement('div');
            card.className = 'bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:scale-105';
            card.innerHTML = `
                <div class="relative">
                    <img src="${course.image_url || 'assets/images/courses/placeholder.png'}" alt="${course.course_title}" class="object-cover w-full h-48">
                    ${course.has_prerequisites && !course.prerequisites_met ? `
                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="text-center text-white">
                                <i class="mb-2 text-3xl fas fa-lock"></i>
                                <p class="text-sm">Prerequisites Required</p>
                            </div>
                        </div>
                    ` : ''}
                    ${course.is_free ? '<div class="absolute px-2 py-1 text-xs font-medium text-white bg-green-500 rounded top-2 right-2">FREE</div>' : ''}
                </div>
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2 py-1 text-xs font-medium rounded ${getStatusBadgeClass(course.course_status)}">${getStatusText(course.course_status)}</span>
                        <span class="text-sm text-gray-500">${course.category}</span>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-gray-900">${course.course_title}</h3>
                    <p class="mb-3 text-sm text-gray-600 line-clamp-2">${course.description}</p>
                    <div class="flex items-center justify-between mb-3 text-sm text-gray-500">
                        <span><i class="mr-1 fas fa-users"></i>${course.enrolled_count} enrolled</span>
                        <span><i class="mr-1 fas fa-play"></i>${course.lesson_count} lessons</span>
                        <span><i class="mr-1 fas fa-tasks"></i>${course.assignment_count} assignments</span>
                    </div>
                    ${course.course_status === 'in_progress' || course.course_status === 'enrolled' ? `
                        <div class="mb-3">
                            <div class="flex items-center justify-between mb-1 text-sm">
                                <span class="text-gray-600">Progress</span>
                                <span class="font-medium text-gray-900">${course.progress_percentage}%</span>
                            </div>
                            <div class="w-full h-2 bg-gray-200 rounded-full">
                                <div class="h-2 bg-blue-500 rounded-full" style="width: ${course.progress_percentage}%"></div>
                            </div>
                        </div>
                    ` : ''}
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            <span>by ${course.teacher_name}</span>
                        </div>
                        <div class="text-lg font-bold text-blue-600">
                            ${course.is_free ? 'FREE' : Number(course.price).toLocaleString() + ' RWF'}
                        </div>
                    </div>
                    <div class="mt-3">
                        ${getActionButton(course)}
                    </div>
                </div>
            `;
            return card;
        }

        // Helper functions for course card creation
        function getStatusBadgeClass(status) {
            const classes = {
                'completed': 'bg-green-100 text-green-800',
                'in_progress': 'bg-blue-100 text-blue-800',
                'enrolled': 'bg-blue-100 text-blue-800',
                'pending_payment': 'bg-yellow-100 text-yellow-800',
                'not_started': 'bg-gray-100 text-gray-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        }

        function getStatusText(status) {
            const texts = {
                'completed': 'Completed',
                'in_progress': 'In Progress',
                'enrolled': 'Enrolled',
                'pending_payment': 'Payment Pending',
                'not_started': 'Not Started'
            };
            return texts[status] || 'Unknown';
        }

        function getActionButton(course) {
            if (course.course_status === 'completed') {
                return `<button onclick="viewCertificate(${course.course_id})" class="w-full px-4 py-2 text-sm font-medium text-green-600 transition-colors rounded-lg bg-green-50 hover:bg-green-100">View Certificate</button>`;
            } else if (course.course_status === 'in_progress' || course.course_status === 'enrolled') {
                return `<a href="course-viewer.php?course=${course.course_id}&lesson=2" class="block w-full px-4 py-2 text-sm font-medium text-center text-blue-600 transition-colors rounded-lg bg-blue-50 hover:bg-blue-100">Continue Learning</a>`;
            } else if (course.course_status === 'pending_payment') {
                return `<button onclick="completePayment(${course.course_id}, '${course.course_title}', ${course.price})" class="w-full px-4 py-2 text-sm font-medium text-white transition-colors bg-yellow-600 rounded-lg hover:bg-yellow-700">Complete Payment</button>`;
            } else if (course.can_enroll) {
                if (course.is_free) {
                    return `<button onclick="enrollInCourse(${course.course_id})" class="w-full px-4 py-2 text-sm font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">Start Free</button>`;
                } else {
                    return `<button onclick="openCheckoutModal(${course.course_id}, '${course.course_title}', ${course.price})" class="w-full px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">Enroll Now</button>`;
                }
            } else if (course.has_prerequisites && !course.prerequisites_met) {
                return `<button disabled class="w-full px-4 py-2 text-sm font-medium text-gray-400 transition-colors bg-gray-100 rounded-lg cursor-not-allowed">Locked</button>`;
            } else {
                return `<button disabled class="w-full px-4 py-2 text-sm font-medium text-gray-400 transition-colors bg-gray-100 rounded-lg cursor-not-allowed">Not Available</button>`;
            }
        }

        // Course filtering functionality
        document.addEventListener('DOMContentLoaded', function() {
            const courseFilter = document.getElementById('courseFilter');
            const categoryFilter = document.getElementById('categoryFilter');
            const filterButtons = document.querySelectorAll('.filter-btn');

            function filterCourses() {
                const searchTerm = courseFilter.value.toLowerCase();
                const selectedCategory = categoryFilter.value.toLowerCase();
                const selectedLevel = document.querySelector('.filter-btn.active')?.dataset.filter || 'all';
                const courseCards = document.querySelectorAll('.course-card');

                courseCards.forEach(card => {
                    const title = card.querySelector('h3').textContent.toLowerCase();
                    const category = card.dataset.category;
                    const level = card.dataset.level;

                    const matchesSearch = title.includes(searchTerm);
                    const matchesCategory = !selectedCategory || category === selectedCategory;
                    const matchesLevel = selectedLevel === 'all' || level === selectedLevel;

                    if (matchesSearch && matchesCategory && matchesLevel) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            // Add event listeners
            courseFilter.addEventListener('input', filterCourses);
            categoryFilter.addEventListener('change', filterCourses);

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    filterCourses();
                });
            });
        });
    </script>
</body>

</html>