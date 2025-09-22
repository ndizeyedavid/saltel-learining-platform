<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - Saltel • Trainee</title>
    <?php include '../../include/imports.php'; ?>
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
                            <option value="design">Design</option>
                            <option value="development">Development</option>
                            <option value="business">Business</option>
                            <option value="marketing">Marketing</option>
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
                <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3" id="coursesGrid">
                    <!-- Course Card 1 -->
                    <div class="transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md course-card" data-category="design" data-level="beginner">
                        <div class="p-6">
                            <div class="flex items-center mb-4 space-x-4">
                                <div class="flex items-center justify-center w-16 h-16 overflow-hidden rounded-xl">
                                    <img src="../../assets/images/courses/placeholder.png" class="object-cover w-full h-full" alt="Course 1">
                                </div>
                                <div class="flex-1">
                                    <h3 class="mb-1 font-semibold text-gray-900">UI/UX Design Fundamentals</h3>
                                    <p class="text-sm text-gray-500">Learn the basics of user interface and experience design</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span><i class="mr-1 far fa-clock"></i>8 weeks</span>
                                    <span><i class="mr-1 fas fa-book"></i>24 lessons</span>

                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="text-sm text-gray-600">(4.9)</span>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">Beginner</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-lg font-semibold text-gray-900">FREE</span>
                                <button class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 enroll-btn">
                                    Enroll Now
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Course Card 2 -->
                    <div class="transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md course-card" data-category="development" data-level="intermediate">
                        <div class="p-6">
                            <div class="flex items-center mb-4 space-x-4">
                                <div class="flex items-center justify-center w-16 h-16 bg-gray-100 rounded-xl">
                                    <i class="text-2xl text-purple-600 fas fa-code"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="mb-1 font-semibold text-gray-900">React Development Mastery</h3>
                                    <p class="text-sm text-gray-500">Build modern web applications with React and Redux</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span><i class="mr-1 far fa-clock"></i>12 weeks</span>
                                    <span><i class="mr-1 fas fa-book"></i>36 lessons</span>

                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="far fa-star"></i>
                                    </div>
                                    <span class="text-sm text-gray-600">(4.7)</span>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full">Intermediate</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-lg font-semibold text-gray-900">FREE</span>
                                <button class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 enroll-btn">
                                    Enroll Now
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Course Card 3 -->
                    <div class="transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md course-card" data-category="business" data-level="beginner">
                        <div class="p-6">
                            <div class="flex items-center mb-4 space-x-4">
                                <div class="flex items-center justify-center w-16 h-16 bg-gray-100 rounded-xl">
                                    <i class="text-2xl text-green-600 fas fa-chart-line"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="mb-1 font-semibold text-gray-900">Digital Marketing Essentials</h3>
                                    <p class="text-sm text-gray-500">Master social media, SEO, and content marketing</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span><i class="mr-1 far fa-clock"></i>6 weeks</span>
                                    <span><i class="mr-1 fas fa-book"></i>18 lessons</span>

                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="text-sm text-gray-600">(4.8)</span>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">Beginner</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-lg font-semibold text-gray-900">FREE</span>
                                <button class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 enroll-btn">
                                    Enroll Now
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Course Card 4 - LOCKED -->
                    <div class="relative transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md course-card locked-course" data-category="development" data-level="advanced" data-locked="true" data-unlock-requirement="Complete React Development Mastery">
                        <div class="absolute inset-0 bg-gray-900 bg-opacity-50 rounded-xl flex items-center justify-center z-10">
                            <div class="text-center text-white">
                                <i class="text-4xl mb-4 fas fa-lock"></i>
                                <h4 class="text-lg font-semibold mb-2">Course Locked</h4>
                                <p class="text-sm opacity-90 px-4">Complete "React Development Mastery" to unlock</p>
                                <div class="mt-4">
                                    <span class="px-3 py-1 bg-yellow-500 text-black text-xs font-medium rounded-full">
                                        <i class="mr-1 fas fa-trophy"></i>
                                        Unlock Reward: +500 XP
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 opacity-60">
                            <div class="flex items-center mb-4 space-x-4">
                                <div class="flex items-center justify-center w-16 h-16 bg-gray-100 rounded-xl">
                                    <i class="text-2xl text-red-600 fas fa-database"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="mb-1 font-semibold text-gray-900">Advanced Database Design</h3>
                                    <p class="text-sm text-gray-500">Master complex database architectures and optimization</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span><i class="mr-1 far fa-clock"></i>10 weeks</span>
                                    <span><i class="mr-1 fas fa-book"></i>30 lessons</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="text-sm text-gray-600">(4.8)</span>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">Advanced</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-lg font-semibold text-gray-900">FREE</span>
                                <button class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-200 rounded-lg cursor-not-allowed" disabled>
                                    Locked
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Course Card 5 -->
                    <div class="transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md course-card" data-category="design" data-level="intermediate">
                        <div class="p-6">
                            <div class="flex items-center mb-4 space-x-4">
                                <div class="flex items-center justify-center w-16 h-16 bg-gray-100 rounded-xl">
                                    <i class="text-2xl text-indigo-600 fas fa-palette"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="mb-1 font-semibold text-gray-900">Advanced Graphic Design</h3>
                                    <p class="text-sm text-gray-500">Create stunning visuals with advanced design techniques</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span><i class="mr-1 far fa-clock"></i>9 weeks</span>
                                    <span><i class="mr-1 fas fa-book"></i>27 lessons</span>

                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="text-sm text-gray-600">(4.9)</span>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full">Intermediate</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-lg font-semibold text-gray-900">35,000 RWF</span>
                                <button class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700 enroll-btn">
                                    Enroll Now
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Course Card 6 - LOCKED -->
                    <div class="relative transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md course-card locked-course" data-category="marketing" data-level="beginner" data-locked="true" data-unlock-requirement="Complete Digital Marketing Essentials + Pass Assessment">
                        <div class="absolute inset-0 bg-gray-900 bg-opacity-50 rounded-xl flex items-center justify-center z-10">
                            <div class="text-center text-white">
                                <i class="text-4xl mb-4 fas fa-lock"></i>
                                <h4 class="text-lg font-semibold mb-2">Course Locked</h4>
                                <p class="text-sm opacity-90 px-4">Complete "Digital Marketing Essentials" + Pass Assessment (80%+)</p>
                                <div class="mt-4 space-y-2">
                                    <span class="block px-3 py-1 bg-yellow-500 text-black text-xs font-medium rounded-full">
                                        <i class="mr-1 fas fa-trophy"></i>
                                        Unlock Reward: +300 XP
                                    </span>
                                    <span class="block px-3 py-1 bg-purple-500 text-white text-xs font-medium rounded-full">
                                        <i class="mr-1 fas fa-medal"></i>
                                        Marketing Expert Badge
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 opacity-60">
                            <div class="flex items-center mb-4 space-x-4">
                                <div class="flex items-center justify-center w-16 h-16 bg-gray-100 rounded-xl">
                                    <i class="text-2xl text-orange-600 fas fa-bullhorn"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="mb-1 font-semibold text-gray-900">Content Marketing Strategy</h3>
                                    <p class="text-sm text-gray-500">Build engaging content that converts and retains customers</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span><i class="mr-1 far fa-clock"></i>7 weeks</span>
                                    <span><i class="mr-1 fas fa-book"></i>21 lessons</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <span class="text-sm text-gray-600">(4.7)</span>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">Beginner</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-lg font-semibold text-gray-900">59,000 RWF</span>
                                <button class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-200 rounded-lg cursor-not-allowed" disabled>
                                    Locked
                                </button>
                            </div>
                        </div>
                    </div>
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
</body>

</html>