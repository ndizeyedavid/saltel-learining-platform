<?php
session_start();
require_once '../../include/connect.php';

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get student ID
$student_query = "SELECT student_id FROM students WHERE user_id = ?";
$stmt = $conn->prepare($student_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$student_result = $stmt->get_result()->fetch_assoc();

if (!$student_result) {
    header('Location: ../../login.php');
    exit;
}

$student_id = $student_result['student_id'];

// Get certificate statistics
$stats = [];

// Total certificates earned
$total_certificates_query = "SELECT COUNT(*) as total FROM certificates WHERE student_id = ?";
$stmt = $conn->prepare($total_certificates_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stats['total_certificates'] = $stmt->get_result()->fetch_assoc()['total'];

// Completed courses (100% progress)
$completed_courses_query = "SELECT COUNT(*) as completed FROM enrollments WHERE student_id = ? AND payment_status = 'Paid' AND progress_percentage = 100";
$stmt = $conn->prepare($completed_courses_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stats['completed_courses'] = $stmt->get_result()->fetch_assoc()['completed'];

// Total enrolled courses
$total_courses_query = "SELECT COUNT(*) as total FROM enrollments WHERE student_id = ? AND payment_status = 'Paid'";
$stmt = $conn->prepare($total_courses_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stats['total_courses'] = $stmt->get_result()->fetch_assoc()['total'];

// Calculate completion rate
$stats['completion_rate'] = $stats['total_courses'] > 0 ? round(($stats['completed_courses'] / $stats['total_courses']) * 100) : 0;

// Get certificates with course details
$certificates_query = "SELECT 
    cert.certificate_id,
    cert.certificate_code,
    cert.issued_at,
    c.course_title,
    c.category,
    c.image_url,
    c.course_id
FROM certificates cert
JOIN courses c ON cert.course_id = c.course_id
WHERE cert.student_id = ?
ORDER BY cert.issued_at DESC";

$stmt = $conn->prepare($certificates_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$certificates = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get available categories for filter
$categories_query = "SELECT DISTINCT c.category 
FROM courses c 
JOIN enrollments e ON c.course_id = e.course_id 
WHERE e.student_id = ? AND e.payment_status = 'Paid'
ORDER BY c.category";

$stmt = $conn->prepare($categories_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$categories = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get available years for filter
$years_query = "SELECT DISTINCT YEAR(cert.issued_at) as year 
FROM certificates cert 
WHERE cert.student_id = ? 
ORDER BY year DESC";

$stmt = $conn->prepare($years_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$years = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calculate monthly growth
$this_month_query = "SELECT COUNT(*) as count FROM certificates WHERE student_id = ? AND MONTH(issued_at) = MONTH(CURRENT_DATE()) AND YEAR(issued_at) = YEAR(CURRENT_DATE())";
$stmt = $conn->prepare($this_month_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stats['this_month_certificates'] = $stmt->get_result()->fetch_assoc()['count'];

$last_month_query = "SELECT COUNT(*) as count FROM certificates WHERE student_id = ? AND MONTH(issued_at) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) AND YEAR(issued_at) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))";
$stmt = $conn->prepare($last_month_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stats['last_month_certificates'] = $stmt->get_result()->fetch_assoc()['count'];

$stats['monthly_growth'] = $stats['this_month_certificates'] - $stats['last_month_certificates'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificates - Saltel • Trainee Dashboard</title>
    <?php include '../../include/imports.php'; ?>
    <script src="../../assets/js/certificates.js" defer></script>
</head>

<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainee-Sidebar.php'; ?>

        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Header -->
            <?php include '../../components/Trainee-Header.php'; ?>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-y-auto ">
                <!-- Page Header -->
                <div class="mb-8">
                    <h1 class="mb-2 text-3xl font-bold text-gray-900">My Certificates</h1>
                    <p class="text-gray-600">View and download your earned certificates</p>
                </div>

                <!-- Certificates Stats -->
                <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
                    <!-- Total Certificates -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Certificates</p>
                                <p class="text-2xl font-bold text-gray-900"><?php echo $stats['total_certificates']; ?></p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg">
                                <i class="text-xl text-yellow-600 fas fa-award"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm <?php echo $stats['monthly_growth'] >= 0 ? 'text-green-600' : 'text-red-600'; ?>">
                                <i class="mr-1 fas <?php echo $stats['monthly_growth'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'; ?>"></i>
                                <span><?php echo $stats['monthly_growth'] >= 0 ? '+' : ''; ?><?php echo $stats['monthly_growth']; ?> this month</span>
                            </div>
                        </div>
                    </div>

                    <!-- Completed Courses -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Completed Courses</p>
                                <p class="text-2xl font-bold text-gray-900"><?php echo $stats['completed_courses']; ?></p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg">
                                <i class="text-xl text-green-600 fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-green-600">
                                <i class="mr-1 fas fa-graduation-cap"></i>
                                <span><?php echo $stats['total_courses']; ?> total enrolled</span>
                            </div>
                        </div>
                    </div>

                    <!-- Completion Rate -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Completion Rate</p>
                                <p class="text-2xl font-bold text-gray-900"><?php echo $stats['completion_rate']; ?>%</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                                <i class="text-xl text-blue-600 fas fa-chart-pie"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-blue-600">
                                <i class="mr-1 fas fa-percentage"></i>
                                <span><?php echo $stats['completion_rate']; ?>% completion rate</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="categoryFilter">
                            <option value="">All Categories</option>
                            <option value="design">Design</option>
                            <option value="development">Development</option>
                            <option value="business">Business</option>
                            <option value="marketing">Marketing</option>
                        </select>
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="yearFilter">
                            <option value="">All Years</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="px-4 py-2 text-sm font-medium text-blue-600 transition-colors rounded-lg bg-blue-50 hover:bg-blue-100" id="downloadAllBtn">
                            <i class="mr-2 fas fa-download"></i>
                            Download All
                        </button>
                    </div>
                </div>

                <!-- Certificates Grid -->
                <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3" id="certificatesGrid">
                    <?php
                    // Function to get gradient colors based on category
                    function getCategoryGradient($category)
                    {
                        $gradients = [
                            'Design' => 'from-blue-500 to-purple-600',
                            'Development' => 'from-green-500 to-teal-600',
                            'Business' => 'from-orange-500 to-red-600',
                            'Marketing' => 'from-indigo-500 to-blue-600',
                            'Technology' => 'from-purple-500 to-pink-600',
                            'Data Science' => 'from-cyan-500 to-blue-600',
                            'default' => 'from-gray-500 to-gray-600'
                        ];
                        return $gradients[$category] ?? $gradients['default'];
                    }

                    // Function to get rating stars
                    function getRatingStars($score)
                    {
                        $stars = '';
                        $fullStars = floor($score / 20); // Convert 100-point scale to 5-star
                        $emptyStars = 5 - $fullStars;

                        for ($i = 0; $i < $fullStars; $i++) {
                            $stars .= '<i class="fas fa-star"></i>';
                        }
                        for ($i = 0; $i < $emptyStars; $i++) {
                            $stars .= '<i class="far fa-star"></i>';
                        }
                        return $stars;
                    }

                    // Function to get rating text
                    function getRatingText($score)
                    {
                        if ($score >= 90) return 'Excellent';
                        if ($score >= 80) return 'Very Good';
                        if ($score >= 70) return 'Good';
                        if ($score >= 60) return 'Fair';
                        return 'Needs Improvement';
                    }

                    // Display earned certificates
                    if (!empty($certificates)) {
                        foreach ($certificates as $certificate) {
                            $gradient = getCategoryGradient($certificate['category']);
                            $completionDate = date('F j, Y', strtotime($certificate['issued_at']));
                            $year = date('Y', strtotime($certificate['issued_at']));
                            // For now, we'll use a default score since we don't have final_score in certificates table
                            $defaultScore = 85; // Can be updated when we add scoring to certificates
                            $ratingStars = getRatingStars($defaultScore);
                            $ratingText = getRatingText($defaultScore);
                    ?>
                            <div class="overflow-hidden transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl certificate-card hover:shadow-md"
                                data-category="<?php echo strtolower($certificate['category']); ?>"
                                data-year="<?php echo $year; ?>"
                                data-certificate-id="<?php echo htmlspecialchars($certificate['certificate_id']); ?>">
                                <div class="relative">
                                    <div class="flex items-center justify-center h-48 bg-gradient-to-br <?php echo $gradient; ?>">
                                        <div class="text-center text-white">
                                            <i class="mb-4 text-4xl fas fa-certificate"></i>
                                            <h3 class="text-lg font-bold">Certificate of Completion</h3>
                                            <p class="text-sm opacity-90"><?php echo htmlspecialchars($certificate['course_title']); ?></p>
                                        </div>
                                    </div>
                                    <div class="absolute top-4 right-4">
                                        <span class="px-2 py-1 text-xs font-medium text-white bg-black rounded-full bg-opacity-20">
                                            <?php echo htmlspecialchars($certificate['category']); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="mb-4">
                                        <h4 class="mb-1 font-semibold text-gray-900"><?php echo htmlspecialchars($certificate['course_title']); ?></h4>
                                        <p class="text-sm text-gray-500">Completed on <?php echo $completionDate; ?></p>
                                        <p class="text-sm text-gray-500">Issued by Saltel Learning Platform</p>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <div class="flex text-yellow-400">
                                                <?php echo $ratingStars; ?>
                                            </div>
                                            <span class="text-sm text-gray-600"><?php echo $ratingText; ?></span>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button class="p-2 text-gray-600 transition-colors hover:text-blue-600 view-btn" title="View Certificate">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="p-2 text-gray-600 transition-colors hover:text-green-600 download-btn" title="Download Certificate">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    } else {
                        // Show message when no certificates are available
                        ?>
                        <div class="py-12 text-center col-span-full">
                            <div class="text-gray-400">
                                <i class="mb-4 text-4xl fas fa-certificate"></i>
                                <p class="text-lg font-medium text-gray-600">No certificates earned yet</p>
                                <p class="text-sm text-gray-500">Complete courses to earn your first certificate!</p>
                            </div>
                        </div>
                    <?php
                    }
                    ?>

                </div>
        </div>
    </div>
    </div>

    <!-- Certificate View Modal -->
    <div id="certificateModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
        <div class="flex items-center justify-center px-4 mx-auto scale-75 w-fit">
            <div class="relative w-full max-w-6xl p-6 mx-auto bg-white rounded-lg shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Certificate Preview</h3>
                    <button id="closeCertificateModal" class="text-gray-400 hover:text-gray-600">
                        <i class="text-xl fas fa-times"></i>
                    </button>
                </div>

                <!-- Certificate Design -->
                <div id="certificateContent" class="relative overflow-hidden bg-white border-8 border-double rounded-lg shadow-2xl border-gradient-to-r from-blue-600 to-purple-600" style="aspect-ratio: 4/3; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">

                    <!-- Decorative Background Pattern -->
                    <div class="absolute inset-0 opacity-5">
                        <div class="absolute w-32 h-32 border-4 border-blue-300 rounded-full top-8 left-8 animate-pulse"></div>
                        <div class="absolute w-24 h-24 border-4 border-purple-300 rounded-full top-16 right-16 animate-pulse" style="animation-delay: 1s;"></div>
                        <div class="absolute w-40 h-40 border-4 border-indigo-300 rounded-full bottom-8 left-16 animate-pulse" style="animation-delay: 2s;"></div>
                        <div class="absolute border-4 border-blue-300 rounded-full w-28 h-28 bottom-16 right-8 animate-pulse" style="animation-delay: 0.5s;"></div>
                    </div>

                    <!-- Decorative Corner Elements -->
                    <div class="absolute top-0 left-0 w-24 h-24">
                        <div class="absolute w-16 h-16 border-t-8 border-l-8 border-blue-600 rounded-tl-2xl top-4 left-4"></div>
                        <div class="absolute w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 to-purple-600 top-2 left-2"></div>
                    </div>
                    <div class="absolute top-0 right-0 w-24 h-24">
                        <div class="absolute w-16 h-16 border-t-8 border-r-8 border-purple-600 rounded-tr-2xl top-4 right-4"></div>
                        <div class="absolute w-8 h-8 rounded-full bg-gradient-to-bl from-purple-600 to-blue-600 top-2 right-2"></div>
                    </div>
                    <div class="absolute bottom-0 left-0 w-24 h-24">
                        <div class="absolute w-16 h-16 border-b-8 border-l-8 border-blue-600 rounded-bl-2xl bottom-4 left-4"></div>
                        <div class="absolute w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-purple-600 bottom-2 left-2"></div>
                    </div>
                    <div class="absolute bottom-0 right-0 w-24 h-24">
                        <div class="absolute w-16 h-16 border-b-8 border-r-8 border-purple-600 rounded-br-2xl bottom-4 right-4"></div>
                        <div class="absolute w-8 h-8 rounded-full bg-gradient-to-tl from-purple-600 to-blue-600 bottom-2 right-2"></div>
                    </div>

                    <!-- Header Section -->
                    <div class="relative pt-12 pb-6 text-center">
                        <div class="flex items-center justify-center mb-6">
                            <!-- Saltel Logo Placeholder - Replace with actual logo -->
                            <div class="flex items-center space-x-4">
                                <img src="../../assets/images/logo.png" alt="Saltel Logo" class="w-auto h-24">
                            </div>
                        </div>
                        <div class="w-48 h-1 mx-auto mb-4 rounded-full bg-gradient-to-r from-blue-600 via-purple-600 to-blue-600"></div>
                    </div>

                    <!-- Main Content -->
                    <div class="px-16 py-8 text-center">
                        <!-- Certificate Title -->
                        <div class="mb-8">
                            <h2 class="mb-3 text-5xl font-bold text-blue-600" style="font-family: 'Times New Roman', serif;">
                                Certificate of Achievement
                            </h2>
                            <p class="text-xl italic text-gray-700">This is to certify that</p>
                        </div>

                        <!-- Student Name -->
                        <div class="mb-8">
                            <h3 id="modalStudentName" class="hidden mb-3 text-6xl font-bold text-gray-800" style="font-family: 'Times New Roman', serif;"></h3>
                            <h3 class="mb-3 text-6xl font-bold text-gray-800" style="font-family: 'Times New Roman', serif;">
                                <?php echo $_SESSION['user_name'] ?>
                            </h3>
                            <div class="h-1 mx-auto w-80 bg-gradient-to-r from-transparent via-gray-400 to-transparent"></div>
                        </div>

                        <!-- Course Information -->
                        <div class="mb-10">
                            <p class="mb-3 text-xl text-gray-700">has successfully completed the course</p>
                            <h4 id="modalCourseName" class="mb-6 text-4xl font-bold text-gray-800" style="font-family: 'Times New Roman', serif;">
                                [Course Name]
                            </h4>
                            <p class="mb-4 text-lg text-gray-600">with outstanding performance and dedication</p>
                            <div id="modalRating" class="flex items-center justify-center mb-4 space-x-1">
                                <!-- Stars will be populated by JavaScript -->
                            </div>
                        </div>

                        <!-- Footer Section -->
                        <div class="flex items-end justify-between px-12">
                            <!-- Date -->
                            <div class="text-center">
                                <div class="w-40 h-1 mb-3 bg-gradient-to-r from-transparent via-gray-400 to-transparent"></div>
                                <p class="text-sm font-medium text-gray-600">Date of Completion</p>
                                <p id="modalDate" class="text-lg font-bold text-gray-800">[Date]</p>
                            </div>

                            <!-- Official Seal -->
                            <div class="flex flex-col items-center">
                                <div class="relative flex items-center justify-center w-24 h-24 mb-3 border-4 border-yellow-400 rounded-full shadow-lg bg-gradient-to-br from-yellow-400 via-orange-500 to-red-500">
                                    <i class="text-3xl text-white fas fa-award"></i>
                                    <div class="absolute inset-0 border-2 border-yellow-300 rounded-full animate-ping opacity-20"></div>
                                </div>
                                <p class="text-xs font-medium text-gray-600">Official Seal</p>
                            </div>

                            <!-- Signature -->
                            <div class="text-center">
                                <div class="w-40 h-1 mb-3 bg-gradient-to-r from-transparent via-gray-400 to-transparent"></div>
                                <p class="text-sm font-medium text-gray-600">Director</p>
                                <p class="text-lg font-bold text-gray-800">Saltel Learning Platform</p>
                            </div>
                        </div>

                        <!-- Certificate ID and Verification -->
                        <div class="pt-6 mt-8 border-t border-gray-300">
                            <p class="text-sm text-gray-500">
                                Certificate ID: <span id="modalCertificateId" class="font-mono font-bold text-blue-600">[Certificate ID]</span>
                            </p>
                            <p class="mt-1 text-xs text-gray-400">
                                Verify authenticity at: <span class="font-medium">saltel.edu/verify</span>
                            </p>
                        </div>
                    </div>

                    <!-- Artistic Elements -->
                    <div class="absolute w-2 h-16 rounded-full top-1/4 left-4 bg-gradient-to-b from-blue-600 to-purple-600 opacity-30"></div>
                    <div class="absolute w-2 h-20 rounded-full top-1/3 right-4 bg-gradient-to-b from-purple-600 to-blue-600 opacity-30"></div>
                    <div class="absolute w-16 h-2 rounded-full bottom-1/4 left-8 bg-gradient-to-r from-blue-600 to-purple-600 opacity-30"></div>
                    <div class="absolute w-20 h-2 rounded-full bottom-1/3 right-8 bg-gradient-to-r from-purple-600 to-blue-600 opacity-30"></div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-center mt-6 space-x-4">
                    <button id="downloadCertificateBtn" class="px-8 py-3 text-white transition-all duration-200 transform rounded-lg shadow-lg bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 hover:scale-105">
                        <i class="mr-2 fas fa-download"></i>
                        Download PDF
                    </button>
                    <button id="shareCertificateBtn" class="px-8 py-3 text-gray-700 transition-all duration-200 transform rounded-lg shadow-lg bg-gradient-to-r from-gray-200 to-gray-300 hover:from-gray-300 hover:to-gray-400 hover:scale-105">
                        <i class="mr-2 fas fa-share"></i>
                        Share Certificate
                    </button>
                    <button id="verifyCertificateBtn" class="hidden px-8 py-3 text-white transition-all duration-200 transform rounded-lg shadow-lg bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 hover:scale-105">
                        <i class="mr-2 fas fa-shield-alt"></i>
                        Verify Authenticity
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Certificate Verification Modal -->
    <div id="verificationModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="relative w-full max-w-md p-6 mx-auto bg-white rounded-lg shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Verify Certificate</h3>
                    <button id="closeVerificationModal" class="text-gray-400 hover:text-gray-600">
                        <i class="text-xl fas fa-times"></i>
                    </button>
                </div>
                <div class="mb-4">
                    <label for="certificateId" class="block mb-2 text-sm font-medium text-gray-700">Certificate ID</label>
                    <input type="text" id="certificateId" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Enter certificate ID">
                </div>
                <button id="verifyCertificateBtn" class="w-full px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    <i class="mr-2 fas fa-search"></i>
                    Verify Certificate
                </button>
                <div id="verificationResult" class="mt-4">
                    <!-- Verification result will be displayed here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Pass student name to JavaScript
        window.studentName = "<?php echo isset($student_name) ? htmlspecialchars($student_name) : 'Student Name'; ?>";
    </script>
    <script src="../../assets/js/certificates.js"></script>
</body>

</html>