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
                        <button class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors" id="downloadAllBtn">
                            <i class="mr-2 fas fa-download"></i>
                            Download All
                        </button>
                    </div>
                </div>

                <!-- Certificates Grid -->
                <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3" id="certificatesGrid">
                    <!-- Certificate 1 -->
                    <div class="bg-white border border-gray-200 shadow-sm rounded-xl overflow-hidden certificate-card hover:shadow-md transition-shadow" data-category="design" data-year="2024">
                        <div class="relative">
                            <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <i class="text-4xl mb-4 fas fa-certificate"></i>
                                    <h3 class="text-lg font-bold">Certificate of Completion</h3>
                                    <p class="text-sm opacity-90">UI/UX Design Fundamentals</p>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4">
                                <span class="px-2 py-1 text-xs font-medium text-white bg-black bg-opacity-20 rounded-full">Design</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-900 mb-1">UI/UX Design Fundamentals</h4>
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
                                    <button class="p-2 text-gray-600 hover:text-blue-600 transition-colors view-btn" title="View Certificate">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="p-2 text-gray-600 hover:text-green-600 transition-colors download-btn" title="Download Certificate">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate 2 -->
                    <div class="bg-white border border-gray-200 shadow-sm rounded-xl overflow-hidden certificate-card hover:shadow-md transition-shadow" data-category="development" data-year="2024">
                        <div class="relative">
                            <div class="h-48 bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <i class="text-4xl mb-4 fas fa-certificate"></i>
                                    <h3 class="text-lg font-bold">Certificate of Completion</h3>
                                    <p class="text-sm opacity-90">React Development Mastery</p>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4">
                                <span class="px-2 py-1 text-xs font-medium text-white bg-black bg-opacity-20 rounded-full">Development</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-900 mb-1">React Development Mastery</h4>
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
                                    <button class="p-2 text-gray-600 hover:text-blue-600 transition-colors view-btn" title="View Certificate">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="p-2 text-gray-600 hover:text-green-600 transition-colors download-btn" title="Download Certificate">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate 3 -->
                    <div class="bg-white border border-gray-200 shadow-sm rounded-xl overflow-hidden certificate-card hover:shadow-md transition-shadow" data-category="business" data-year="2024">
                        <div class="relative">
                            <div class="h-48 bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <i class="text-4xl mb-4 fas fa-certificate"></i>
                                    <h3 class="text-lg font-bold">Certificate of Completion</h3>
                                    <p class="text-sm opacity-90">Digital Marketing Essentials</p>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4">
                                <span class="px-2 py-1 text-xs font-medium text-white bg-black bg-opacity-20 rounded-full">Business</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-900 mb-1">Digital Marketing Essentials</h4>
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
                                    <button class="p-2 text-gray-600 hover:text-blue-600 transition-colors view-btn" title="View Certificate">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="p-2 text-gray-600 hover:text-green-600 transition-colors download-btn" title="Download Certificate">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate 4 -->
                    <div class="bg-white border border-gray-200 shadow-sm rounded-xl overflow-hidden certificate-card hover:shadow-md transition-shadow" data-category="design" data-year="2023">
                        <div class="relative">
                            <div class="h-48 bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <i class="text-4xl mb-4 fas fa-certificate"></i>
                                    <h3 class="text-lg font-bold">Certificate of Completion</h3>
                                    <p class="text-sm opacity-90">Advanced Graphic Design</p>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4">
                                <span class="px-2 py-1 text-xs font-medium text-white bg-black bg-opacity-20 rounded-full">Design</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-900 mb-1">Advanced Graphic Design</h4>
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
                                    <button class="p-2 text-gray-600 hover:text-blue-600 transition-colors view-btn" title="View Certificate">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="p-2 text-gray-600 hover:text-green-600 transition-colors download-btn" title="Download Certificate">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate 5 -->
                    <div class="bg-white border border-gray-200 shadow-sm rounded-xl overflow-hidden certificate-card hover:shadow-md transition-shadow" data-category="marketing" data-year="2023">
                        <div class="relative">
                            <div class="h-48 bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <i class="text-4xl mb-4 fas fa-certificate"></i>
                                    <h3 class="text-lg font-bold">Certificate of Completion</h3>
                                    <p class="text-sm opacity-90">Content Marketing Strategy</p>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4">
                                <span class="px-2 py-1 text-xs font-medium text-white bg-black bg-opacity-20 rounded-full">Marketing</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <h4 class="font-semibold text-gray-900 mb-1">Content Marketing Strategy</h4>
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
                                    <button class="p-2 text-gray-600 hover:text-blue-600 transition-colors view-btn" title="View Certificate">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="p-2 text-gray-600 hover:text-green-600 transition-colors download-btn" title="Download Certificate">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificate Modal -->
                <div id="certificateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                        <div class="flex items-center justify-between p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Certificate Preview</h3>
                            <button class="text-gray-400 hover:text-gray-600 transition-colors" id="closeModal">
                                <i class="text-xl fas fa-times"></i>
                            </button>
                        </div>
                        <div class="p-6">
                            <div class="bg-gradient-to-br from-blue-500 to-purple-600 text-white p-12 rounded-lg text-center">
                                <div class="mb-8">
                                    <i class="text-6xl mb-4 fas fa-award"></i>
                                    <h2 class="text-3xl font-bold mb-2">Certificate of Completion</h2>
                                    <p class="text-lg opacity-90">This is to certify that</p>
                                </div>
                                <div class="mb-8">
                                    <h3 class="text-4xl font-bold mb-4">Christopher</h3>
                                    <p class="text-lg mb-2">has successfully completed the course</p>
                                    <h4 class="text-2xl font-semibold" id="modalCourseName">UI/UX Design Fundamentals</h4>
                                </div>
                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="text-sm opacity-75">Date of Completion</p>
                                        <p class="font-semibold" id="modalDate">March 15, 2024</p>
                                    </div>
                                    <div>
                                        <p class="text-sm opacity-75">Issued by</p>
                                        <p class="font-semibold">Saltel Learning Platform</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-center mt-6">
                                <button class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" id="downloadModalBtn">
                                    <i class="mr-2 fas fa-download"></i>
                                    Download Certificate
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