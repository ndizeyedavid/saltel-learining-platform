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
            <main class="flex-1 p-6 overflow-y-auto">
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
                                <p class="text-2xl font-bold text-gray-900">5</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg">
                                <i class="text-xl text-yellow-600 fas fa-award"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-green-600">
                                <i class="mr-1 fas fa-arrow-up"></i>
                                <span>+1 this month</span>
                            </div>
                        </div>
                    </div>

                    <!-- Completed Courses -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Completed Courses</p>
                                <p class="text-2xl font-bold text-gray-900">8</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg">
                                <i class="text-xl text-green-600 fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-green-600">
                                <i class="mr-1 fas fa-arrow-up"></i>
                                <span>+3 this month</span>
                            </div>
                        </div>
                    </div>

                    <!-- Completion Rate -->
                    <div class="p-6 bg-white border border-gray-200 shadow-sm rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Completion Rate</p>
                                <p class="text-2xl font-bold text-gray-900">63%</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                                <i class="text-xl text-blue-600 fas fa-chart-pie"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center text-sm text-green-600">
                                <i class="mr-1 fas fa-arrow-up"></i>
                                <span>+5% this month</span>
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
                    <!-- Certificate 1 -->
                    <div class="overflow-hidden transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl certificate-card hover:shadow-md" data-category="design" data-year="2024">
                        <div class="relative">
                            <div class="flex items-center justify-center h-48 bg-gradient-to-br from-blue-500 to-purple-600">
                                <div class="text-center text-white">
                                    <i class="mb-4 text-4xl fas fa-certificate"></i>
                                    <h3 class="text-lg font-bold">Certificate of Completion</h3>
                                    <p class="text-sm opacity-90">UI/UX Design Fundamentals</p>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4">
                                <span class="px-2 py-1 text-xs font-medium text-white bg-black rounded-full bg-opacity-20">Design</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <h4 class="mb-1 font-semibold text-gray-900">UI/UX Design Fundamentals</h4>
                                <p class="text-sm text-gray-500">Completed on March 15, 2024</p>
                                <p class="text-sm text-gray-500">Issued by Saltel Learning Platform</p>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="text-sm text-gray-600">Excellent</span>
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

                    <!-- Certificate 2 -->
                    <div class="overflow-hidden transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl certificate-card hover:shadow-md" data-category="development" data-year="2024">
                        <div class="relative">
                            <div class="flex items-center justify-center h-48 bg-gradient-to-br from-green-500 to-teal-600">
                                <div class="text-center text-white">
                                    <i class="mb-4 text-4xl fas fa-certificate"></i>
                                    <h3 class="text-lg font-bold">Certificate of Completion</h3>
                                    <p class="text-sm opacity-90">React Development Mastery</p>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4">
                                <span class="px-2 py-1 text-xs font-medium text-white bg-black rounded-full bg-opacity-20">Development</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <h4 class="mb-1 font-semibold text-gray-900">React Development Mastery</h4>
                                <p class="text-sm text-gray-500">Completed on February 28, 2024</p>
                                <p class="text-sm text-gray-500">Issued by Saltel Learning Platform</p>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="far fa-star"></i>
                                    </div>
                                    <span class="text-sm text-gray-600">Very Good</span>
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

                    <!-- Certificate 3 -->
                    <div class="overflow-hidden transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl certificate-card hover:shadow-md" data-category="business" data-year="2024">
                        <div class="relative">
                            <div class="flex items-center justify-center h-48 bg-gradient-to-br from-orange-500 to-red-600">
                                <div class="text-center text-white">
                                    <i class="mb-4 text-4xl fas fa-certificate"></i>
                                    <h3 class="text-lg font-bold">Certificate of Completion</h3>
                                    <p class="text-sm opacity-90">Digital Marketing Essentials</p>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4">
                                <span class="px-2 py-1 text-xs font-medium text-white bg-black rounded-full bg-opacity-20">Business</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <h4 class="mb-1 font-semibold text-gray-900">Digital Marketing Essentials</h4>
                                <p class="text-sm text-gray-500">Completed on January 20, 2024</p>
                                <p class="text-sm text-gray-500">Issued by Saltel Learning Platform</p>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="text-sm text-gray-600">Excellent</span>
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

                    <!-- Certificate 4 -->
                    <div class="overflow-hidden transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl certificate-card hover:shadow-md" data-category="design" data-year="2023">
                        <div class="relative">
                            <div class="flex items-center justify-center h-48 bg-gradient-to-br from-purple-500 to-pink-600">
                                <div class="text-center text-white">
                                    <i class="mb-4 text-4xl fas fa-certificate"></i>
                                    <h3 class="text-lg font-bold">Certificate of Completion</h3>
                                    <p class="text-sm opacity-90">Advanced Graphic Design</p>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4">
                                <span class="px-2 py-1 text-xs font-medium text-white bg-black rounded-full bg-opacity-20">Design</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <h4 class="mb-1 font-semibold text-gray-900">Advanced Graphic Design</h4>
                                <p class="text-sm text-gray-500">Completed on December 10, 2023</p>
                                <p class="text-sm text-gray-500">Issued by Saltel Learning Platform</p>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="far fa-star"></i>
                                    </div>
                                    <span class="text-sm text-gray-600">Very Good</span>
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

                    <!-- Certificate 5 -->
                    <div class="overflow-hidden transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl certificate-card hover:shadow-md" data-category="marketing" data-year="2023">
                        <div class="relative">
                            <div class="flex items-center justify-center h-48 bg-gradient-to-br from-indigo-500 to-blue-600">
                                <div class="text-center text-white">
                                    <i class="mb-4 text-4xl fas fa-certificate"></i>
                                    <h3 class="text-lg font-bold">Certificate of Completion</h3>
                                    <p class="text-sm opacity-90">Content Marketing Strategy</p>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4">
                                <span class="px-2 py-1 text-xs font-medium text-white bg-black rounded-full bg-opacity-20">Marketing</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <h4 class="mb-1 font-semibold text-gray-900">Content Marketing Strategy</h4>
                                <p class="text-sm text-gray-500">Completed on November 5, 2023</p>
                                <p class="text-sm text-gray-500">Issued by Saltel Learning Platform</p>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="text-sm text-gray-600">Excellent</span>
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

                    <!-- Certificate 6 - LOCKED -->
                    <div class="relative overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl certificate-card locked-certificate" data-category="development" data-year="2024" data-locked="true" data-unlock-requirement="Complete Advanced Database Design">
                        <div class="absolute inset-0 z-10 flex items-center justify-center bg-gray-900 bg-opacity-70 rounded-xl">
                            <div class="text-center text-white">
                                <i class="mb-4 text-4xl fas fa-lock"></i>
                                <h4 class="mb-2 text-lg font-semibold">Certificate Locked</h4>
                                <p class="px-4 text-sm opacity-90">Complete "Advanced Database Design" to unlock</p>
                                <div class="mt-4">
                                    <span class="px-3 py-1 text-xs font-medium text-black bg-yellow-500 rounded-full">
                                        <i class="mr-1 fas fa-trophy"></i>
                                        Unlock Reward: Database Expert Badge
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="opacity-40">
                            <div class="relative">
                                <div class="flex items-center justify-center h-48 bg-gradient-to-br from-gray-400 to-gray-600">
                                    <div class="text-center text-white">
                                        <i class="mb-4 text-4xl fas fa-certificate"></i>
                                        <h3 class="text-lg font-bold">Certificate of Completion</h3>
                                        <p class="text-sm opacity-90">Database Architecture Mastery</p>
                                    </div>
                                </div>
                                <div class="absolute top-4 right-4">
                                    <span class="px-2 py-1 text-xs font-medium text-white bg-black rounded-full bg-opacity-20">Development</span>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="mb-4">
                                    <h4 class="mb-1 font-semibold text-gray-900">Database Architecture Mastery</h4>
                                    <p class="text-sm text-gray-500">Not yet earned</p>
                                    <p class="text-sm text-gray-500">Will be issued by Saltel Learning Platform</p>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <div class="flex text-gray-400">
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <span class="text-sm text-gray-400">Not rated</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="p-2 text-gray-400 cursor-not-allowed" disabled title="Certificate Locked">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                        <button class="p-2 text-gray-400 cursor-not-allowed" disabled title="Certificate Locked">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate 7 - LOCKED -->
                    <div class="relative overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl certificate-card locked-certificate" data-category="design" data-year="2024" data-locked="true" data-unlock-requirement="Complete UI/UX Design Fundamentals + Pass Final Project">
                        <div class="absolute inset-0 z-10 flex items-center justify-center bg-gray-900 bg-opacity-70 rounded-xl">
                            <div class="text-center text-white">
                                <i class="mb-4 text-4xl fas fa-lock"></i>
                                <h4 class="mb-2 text-lg font-semibold">Certificate Locked</h4>
                                <p class="px-4 text-sm opacity-90">Complete "UI/UX Design Fundamentals" + Pass Final Project (85%+)</p>
                                <div class="mt-4 space-y-2 w-[90%] mx-auto">
                                    <span class="block px-3 py-1 text-xs font-medium text-black bg-yellow-500 rounded-full">
                                        <i class="mr-1 fas fa-trophy"></i>
                                        Unlock Reward: +800 XP
                                    </span>
                                    <span class="block px-3 py-1 text-xs font-medium text-white bg-purple-500 rounded-full">
                                        <i class="mr-1 fas fa-medal"></i>
                                        UX Master Badge
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="opacity-40">
                            <div class="relative">
                                <div class="flex items-center justify-center h-48 bg-gradient-to-br from-gray-400 to-gray-600">
                                    <div class="text-center text-white">
                                        <i class="mb-4 text-4xl fas fa-certificate"></i>
                                        <h3 class="text-lg font-bold">Certificate of Completion</h3>
                                        <p class="text-sm opacity-90">Advanced UX Design</p>
                                    </div>
                                </div>
                                <div class="absolute top-4 right-4">
                                    <span class="px-2 py-1 text-xs font-medium text-white bg-black rounded-full bg-opacity-20">Design</span>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="mb-4">
                                    <h4 class="mb-1 font-semibold text-gray-900">Advanced UX Design</h4>
                                    <p class="text-sm text-gray-500">Not yet earned</p>
                                    <p class="text-sm text-gray-500">Will be issued by Saltel Learning Platform</p>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <div class="flex text-gray-400">
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <span class="text-sm text-gray-400">Not rated</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="p-2 text-gray-400 cursor-not-allowed" disabled title="Certificate Locked">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                        <button class="p-2 text-gray-400 cursor-not-allowed" disabled title="Certificate Locked">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate 8 - LOCKED -->
                    <div class="relative overflow-hidden bg-white border border-gray-200 shadow-sm rounded-xl certificate-card locked-certificate" data-category="business" data-year="2024" data-locked="true" data-unlock-requirement="Complete Digital Marketing Essentials + Content Marketing Strategy">
                        <div class="absolute inset-0 z-10 flex items-center justify-center bg-gray-900 bg-opacity-70 rounded-xl">
                            <div class="text-center text-white">
                                <i class="mb-4 text-4xl fas fa-lock"></i>
                                <h4 class="mb-2 text-lg font-semibold">Certificate Locked</h4>
                                <p class="px-4 text-sm opacity-90">Complete both "Digital Marketing Essentials" & "Content Marketing Strategy"</p>
                                <div class="mt-4">
                                    <span class="px-3 py-1 text-xs font-medium text-black bg-yellow-500 rounded-full">
                                        <i class="mr-1 fas fa-crown"></i>
                                        Marketing Master Certificate
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="opacity-40">
                            <div class="relative">
                                <div class="flex items-center justify-center h-48 bg-gradient-to-br from-gray-400 to-gray-600">
                                    <div class="text-center text-white">
                                        <i class="mb-4 text-4xl fas fa-certificate"></i>
                                        <h3 class="text-lg font-bold">Master Certificate</h3>
                                        <p class="text-sm opacity-90">Digital Marketing Mastery</p>
                                    </div>
                                </div>
                                <div class="absolute top-4 right-4">
                                    <span class="px-2 py-1 text-xs font-medium text-white bg-black rounded-full bg-opacity-20">Business</span>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="mb-4">
                                    <h4 class="mb-1 font-semibold text-gray-900">Digital Marketing Mastery</h4>
                                    <p class="text-sm text-gray-500">Not yet earned</p>
                                    <p class="text-sm text-gray-500">Will be issued by Saltel Learning Platform</p>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <div class="flex text-gray-400">
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <span class="text-sm text-gray-400">Not rated</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="p-2 text-gray-400 cursor-not-allowed" disabled title="Certificate Locked">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                        <button class="p-2 text-gray-400 cursor-not-allowed" disabled title="Certificate Locked">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificate Modal -->
                <div id="certificateModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50">
                    <div class="bg-white rounded-xl shadow-2xl max-w-5xl w-full mx-4 max-h-[95vh] overflow-y-auto">
                        <div class="flex items-center justify-between p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Certificate Preview</h3>
                            <button class="text-gray-400 transition-colors hover:text-gray-600" id="closeModal">
                                <i class="text-xl fas fa-times"></i>
                            </button>
                        </div>
                        <div class="p-6">
                            <!-- Certificate Design -->
                            <div class="relative overflow-hidden bg-white border-8 border-blue-800 border-double rounded-lg" style="aspect-ratio: 4/3;">
                                <!-- Decorative Background Pattern -->
                                <div class="absolute inset-0 opacity-5">
                                    <div class="absolute w-16 h-16 border-2 border-blue-300 rounded-full top-4 left-4"></div>
                                    <div class="absolute w-12 h-12 border-2 border-purple-300 rounded-full top-8 right-8"></div>
                                    <div class="absolute w-20 h-20 border-2 border-indigo-300 rounded-full bottom-4 left-8"></div>
                                    <div class="absolute border-2 border-blue-300 rounded-full bottom-8 right-4 w-14 h-14"></div>
                                </div>

                                <!-- Header Section -->
                                <div class="relative pt-8 pb-4 text-center">
                                    <div class="flex items-center justify-center mb-4">
                                        <img src="../../assets/images/logo.png" alt="Logo" class="w-[250px]">

                                    </div>
                                    <div class="w-32 h-1 mx-auto mb-4 bg-gradient-to-r from-blue-600 to-purple-600"></div>
                                </div>

                                <!-- Main Content -->
                                <div class="px-12 py-6 text-center">
                                    <!-- Certificate Title -->
                                    <div class="mb-6">
                                        <h2 class="mb-2 text-4xl font-bold text-gray-800" style="font-family: 'Times New Roman', serif;">
                                            Certificate of Achievement
                                        </h2>
                                        <p class="text-lg italic text-gray-600">This is to certify that</p>
                                    </div>

                                    <!-- Student Name -->
                                    <div class="mb-6">
                                        <h3 class="mb-2 text-5xl font-bold text-blue-800" style="font-family: 'Times New Roman', serif;">
                                            Christopher David
                                        </h3>
                                        <div class="w-64 h-0.5 bg-gray-400 mx-auto"></div>
                                    </div>

                                    <!-- Course Information -->
                                    <div class="mb-8">
                                        <p class="mb-2 text-lg text-gray-700">has successfully completed the course</p>
                                        <h4 class="mb-4 text-3xl font-bold text-gray-800" id="modalCourseName" style="font-family: 'Times New Roman', serif;">
                                            UI/UX Design Fundamentals
                                        </h4>
                                        <p class="mb-2 text-base text-gray-600">with outstanding performance and dedication</p>
                                        <div class="flex items-center justify-center mb-4 space-x-1">
                                            <i class="text-yellow-400 fas fa-star"></i>
                                            <i class="text-yellow-400 fas fa-star"></i>
                                            <i class="text-yellow-400 fas fa-star"></i>
                                            <i class="text-yellow-400 fas fa-star"></i>
                                            <i class="text-yellow-400 fas fa-star"></i>
                                            <span class="ml-2 text-sm text-gray-600">Excellent Grade</span>
                                        </div>
                                    </div>

                                    <!-- Footer Section -->
                                    <div class="flex items-end justify-between px-8">
                                        <!-- Date -->
                                        <div class="text-center">
                                            <div class="w-32 h-0.5 bg-gray-400 mb-2"></div>
                                            <p class="text-sm text-gray-600">Date of Completion</p>
                                            <p class="font-semibold text-gray-800" id="modalDate">March 15, 2024</p>
                                        </div>

                                        <!-- Seal/Badge -->
                                        <div class="flex flex-col items-center">
                                            <div class="flex items-center justify-center w-20 h-20 mb-2 border-4 border-yellow-300 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500">
                                                <i class="text-2xl text-white fas fa-award"></i>
                                            </div>
                                            <p class="text-xs text-gray-600">Official Seal</p>
                                        </div>

                                        <!-- Signature -->
                                        <div class="text-center">
                                            <div class="w-32 h-0.5 bg-gray-400 mb-2"></div>
                                            <p class="text-sm text-gray-600">Director</p>
                                            <p class="font-semibold text-gray-800">Saltel Learning Platform</p>
                                        </div>
                                    </div>

                                    <!-- Certificate ID -->
                                    <div class="pt-4 mt-6 border-t border-gray-200">
                                        <p class="text-xs text-gray-500">
                                            Certificate ID: <span class="font-mono" id="certificateId">SLT-2024-001234</span> |
                                            Verify at: saltel.edu/verify
                                        </p>
                                    </div>
                                </div>

                                <!-- Decorative Corner Elements -->
                                <div class="absolute top-0 left-0 w-16 h-16">
                                    <div class="absolute w-12 h-12 border-t-4 border-l-4 border-blue-600 rounded-tl-lg top-2 left-2"></div>
                                </div>
                                <div class="absolute top-0 right-0 w-16 h-16">
                                    <div class="absolute w-12 h-12 border-t-4 border-r-4 border-blue-600 rounded-tr-lg top-2 right-2"></div>
                                </div>
                                <div class="absolute bottom-0 left-0 w-16 h-16">
                                    <div class="absolute w-12 h-12 border-b-4 border-l-4 border-blue-600 rounded-bl-lg bottom-2 left-2"></div>
                                </div>
                                <div class="absolute bottom-0 right-0 w-16 h-16">
                                    <div class="absolute w-12 h-12 border-b-4 border-r-4 border-blue-600 rounded-br-lg bottom-2 right-2"></div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-center mt-6 space-x-4">
                                <button class="px-6 py-3 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700" id="downloadModalBtn">
                                    <i class="mr-2 fas fa-download"></i>
                                    Download PDF
                                </button>
                                <button class="px-6 py-3 text-white transition-colors bg-gray-600 rounded-lg hover:bg-gray-700" id="shareModalBtn">
                                    <i class="mr-2 fas fa-share-alt"></i>
                                    Share Certificate
                                </button>
                                <button class="px-6 py-3 text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700" id="verifyModalBtn">
                                    <i class="mr-2 fas fa-shield-alt"></i>
                                    Verify Authenticity
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>