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
                <div class="grid grid-cols-1 gap-8 mb-8 md:grid-cols-2 lg:grid-cols-3" id="coursesGrid">
                    <!-- Course Card 2 - Web Development -->
                    <div class="overflow-hidden transition-all duration-300 bg-white shadow-lg rounded-2xl hover:shadow-xl hover:-translate-y-1 course-card" data-category="development" data-level="intermediate">
                        <!-- Hero Image -->
                        <div class="relative h-48 overflow-hidden bg-gradient-to-br from-blue-400 via-cyan-500 to-teal-500">
                            <div class="absolute inset-0 bg-black bg-opacity-10"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <img src="../../assets/images/courses/web.png" alt="Web Development" class="object-cover w-full h-full">
                            </div>
                            <!-- Status Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 text-xs font-semibold text-white bg-green-500 rounded-full shadow-lg">
                                    Completed
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="mb-2 text-xl font-bold text-gray-900">React Development Mastery</h3>
                                <p class="text-sm leading-relaxed text-gray-600">Practical, hands-on training for building modern web applications with React, Redux, and advanced JavaScript patterns.</p>
                            </div>

                            <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                                <span>Jul 10, 2024 • Development</span>
                                <span>85 participants</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span><i class="mr-1 far fa-clock"></i>12 weeks</span>
                                    <span><i class="mr-1 fas fa-book"></i>36 lessons</span>
                                </div>
                                <button class="px-4 py-2 text-sm font-medium text-green-600 transition-colors rounded-lg bg-green-50 hover:bg-green-100">
                                    View Certificate
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Course Card 3 - Mobile Development -->
                    <div class="overflow-hidden transition-all duration-300 bg-white shadow-lg rounded-2xl hover:shadow-xl hover:-translate-y-1 course-card" data-category="development" data-level="intermediate">
                        <!-- Hero Image -->
                        <div class="relative h-48 overflow-hidden bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-500">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <img src="../../assets/images/courses/mobile.png" alt="Web Development" class="object-cover w-full h-full">

                            </div>
                            <!-- Status Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 text-xs font-semibold text-white bg-green-500 rounded-full shadow-lg">
                                    Completed
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="mb-2 text-xl font-bold text-gray-900">Mobile App Development</h3>
                                <p class="text-sm leading-relaxed text-gray-600">Our first cohort completed an intensive program and showcased apps for local problems — from farm marketplaces to school management tools.</p>
                            </div>

                            <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                                <span>Jun 05, 2024 • Development</span>
                                <span>24 graduates</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span><i class="mr-1 far fa-clock"></i>10 weeks</span>
                                    <span><i class="mr-1 fas fa-book"></i>28 lessons</span>
                                </div>
                                <button class="px-4 py-2 text-sm font-medium text-green-600 transition-colors rounded-lg bg-green-50 hover:bg-green-100">
                                    View Certificate
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Course Card 4 - Python Data Analytics -->
                    <div class="overflow-hidden transition-all duration-300 bg-white shadow-lg rounded-2xl hover:shadow-xl hover:-translate-y-1 course-card" data-category="development" data-level="beginner">
                        <!-- Hero Image -->
                        <div class="relative h-48 overflow-hidden bg-gradient-to-br from-green-600 via-teal-500 to-cyan-600">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <img src="../../assets/images/courses/python-data.png" alt="Web Development" class="object-cover w-full h-full">

                            </div>
                            <!-- Status Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 text-xs font-semibold text-white bg-green-500 rounded-full shadow-lg">
                                    Completed
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="mb-2 text-xl font-bold text-gray-900">Python Data Analytics</h3>
                                <p class="text-sm leading-relaxed text-gray-600">Volunteers gathered across the slope to plant native seedlings, learn about nursery management, and map areas for future reforestation. The activity focused on...</p>
                            </div>

                            <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                                <span>Aug 20, 2024 • Data Science</span>
                                <span>1,000 seedlings planted</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span><i class="mr-1 far fa-clock"></i>8 weeks</span>
                                    <span><i class="mr-1 fas fa-book"></i>22 lessons</span>
                                </div>
                                <button class="px-4 py-2 text-sm font-medium text-green-600 transition-colors rounded-lg bg-green-50 hover:bg-green-100">
                                    View Certificate
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Course Card 6 - LOCKED -->
                    <div class="relative overflow-hidden transition-all duration-300 bg-white shadow-lg rounded-2xl hover:shadow-xl hover:-translate-y-1 course-card locked-course" data-category="development" data-level="advanced" data-locked="true" data-unlock-requirement="Complete Data Science & Analytics + Python Data Analytics">
                        <!-- Lock Overlay -->
                        <div class="absolute inset-0 z-10 flex items-center justify-center bg-gray-900 bg-opacity-70 rounded-2xl">
                            <div class="px-6 text-center text-white">
                                <i class="mb-4 text-5xl fas fa-lock"></i>
                                <h4 class="mb-2 text-lg font-semibold">Course Locked</h4>
                                <p class="mb-4 text-sm opacity-90">Complete "Data Science & Analytics" + "Python Data Analytics"</p>
                                <div class="space-y-2">
                                    <span class="block px-3 py-1 text-xs font-medium text-black bg-yellow-500 rounded-full">
                                        <i class="mr-1 fas fa-trophy"></i>
                                        Unlock Reward: +750 XP
                                    </span>
                                    <span class="block px-3 py-1 text-xs font-medium text-white bg-purple-500 rounded-full">
                                        <i class="mr-1 fas fa-medal"></i>
                                        AI Expert Badge
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Hero Image (Blurred) -->
                        <div class="relative h-48 overflow-hidden opacity-50 bg-gradient-to-br from-gray-400 to-gray-600">
                            <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <img src="../../assets/images/courses/ai-ml.png" alt="Web Development" class="object-cover w-full h-full">

                            </div>
                        </div>

                        <!-- Content (Dimmed) -->
                        <div class="p-6 opacity-40">
                            <div class="mb-4">
                                <h3 class="mb-2 text-xl font-bold text-gray-900">AI/ML Engineering</h3>
                                <p class="text-sm leading-relaxed text-gray-600">Advanced machine learning engineering, deep learning frameworks, and production AI system deployment.</p>
                            </div>

                            <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                                <span>Coming Soon • AI/ML</span>
                                <span>Advanced Track</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span><i class="mr-1 far fa-clock"></i>20 weeks</span>
                                    <span><i class="mr-1 fas fa-book"></i>60 lessons</span>
                                </div>
                                <button class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-200 rounded-lg cursor-not-allowed" disabled>
                                    Locked
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Course Card 7 - Blockchain Development (Not Started - Paid) -->
                    <div class="overflow-hidden transition-all duration-300 bg-white shadow-lg rounded-2xl hover:shadow-xl hover:-translate-y-1 course-card" data-category="development" data-level="advanced">
                        <!-- Hero Image -->
                        <div class="relative h-48 overflow-hidden bg-gradient-to-br from-yellow-500 via-orange-500 to-red-600">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <img src="../../assets/images/courses/blockchain.png" alt="Web Development" class="object-cover w-full h-full">

                            </div>
                            <!-- Status Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 text-xs font-semibold text-white bg-gray-500 rounded-full shadow-lg">
                                    Not Started
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="mb-2 text-xl font-bold text-gray-900">Blockchain Development</h3>
                                <p class="text-sm leading-relaxed text-gray-600">Learn to build decentralized applications using Ethereum, smart contracts, and Web3 technologies for the future of finance.</p>
                            </div>

                            <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                                <span>Available Now • Development</span>
                                <span>Premium Course</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span><i class="mr-1 far fa-clock"></i>14 weeks</span>
                                    <span><i class="mr-1 fas fa-book"></i>42 lessons</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-gray-900">$199</div>
                                    <button class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                        Enroll Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Course Card 8 - Data Science (In Progress) -->
                    <div class="overflow-hidden transition-all duration-300 bg-white shadow-lg rounded-2xl hover:shadow-xl hover:-translate-y-1 course-card" data-category="development" data-level="intermediate">
                        <!-- Hero Image -->
                        <div class="relative h-48 overflow-hidden bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-600">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <img src="../../assets/images/courses/data-science.png" alt="Web Development" class="object-cover w-full h-full">

                            </div>
                            <!-- Status Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 text-xs font-semibold text-white bg-blue-500 rounded-full shadow-lg">
                                    In Progress
                                </span>
                            </div>
                            <!-- Progress Bar -->
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-black bg-opacity-20">
                                <div class="h-full bg-white bg-opacity-80" style="width: 65%"></div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="mb-2 text-xl font-bold text-gray-900">Data Science & Analytics</h3>
                                <p class="text-sm leading-relaxed text-gray-600">Master Python, machine learning, and statistical analysis to extract insights from complex datasets and drive business decisions.</p>
                            </div>

                            <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                                <span>Started Oct 1, 2024 • Data Science</span>
                                <span>65% Complete</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span><i class="mr-1 far fa-clock"></i>16 weeks</span>
                                    <span><i class="mr-1 fas fa-book"></i>48 lessons</span>
                                </div>
                                <button class="px-4 py-2 text-sm font-medium text-blue-600 transition-colors rounded-lg bg-blue-50 hover:bg-blue-100">
                                    Continue Learning
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Course Card 9 - Cybersecurity (Not Started - Paid) -->
                    <div class="overflow-hidden transition-all duration-300 bg-white shadow-lg rounded-2xl hover:shadow-xl hover:-translate-y-1 course-card" data-category="development" data-level="advanced">
                        <!-- Hero Image -->
                        <div class="relative h-48 overflow-hidden bg-gradient-to-br from-red-600 via-pink-600 to-purple-700">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <img src="../../assets/images/courses/cybersecurity.png" alt="Web Development" class="object-cover w-full h-full">

                            </div>
                            <!-- Status Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 text-xs font-semibold text-white bg-gray-500 rounded-full shadow-lg">
                                    Not Started
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="mb-2 text-xl font-bold text-gray-900">Cybersecurity Fundamentals</h3>
                                <p class="text-sm leading-relaxed text-gray-600">Protect digital assets with comprehensive security practices, ethical hacking, and threat detection methodologies.</p>
                            </div>

                            <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                                <span>Available Now • Security</span>
                                <span>Professional Track</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span><i class="mr-1 far fa-clock"></i>12 weeks</span>
                                    <span><i class="mr-1 fas fa-book"></i>36 lessons</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-gray-900">$149</div>
                                    <button class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                        Enroll Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Course Card 10 - Business Analytics (Not Started - Free) -->
                    <div class="overflow-hidden transition-all duration-300 bg-white shadow-lg rounded-2xl hover:shadow-xl hover:-translate-y-1 course-card" data-category="business" data-level="beginner">
                        <!-- Hero Image -->
                        <div class="relative h-48 overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <img src="../../assets/images/courses/web.png" alt="Web Development" class="object-cover w-full h-full">

                            </div>
                            <!-- Status Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 text-xs font-semibold text-white bg-gray-500 rounded-full shadow-lg">
                                    Not Started
                                </span>
                            </div>
                            <!-- Free Badge -->
                            <div class="absolute top-4 right-4">
                                <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                    FREE
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="mb-2 text-xl font-bold text-gray-900">Business Analytics Basics</h3>
                                <p class="text-sm leading-relaxed text-gray-600">Learn to analyze business data, create reports, and make data-driven decisions using Excel and basic statistical methods.</p>
                            </div>

                            <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                                <span>Available Now • Business</span>
                                <span>Beginner Friendly</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span><i class="mr-1 far fa-clock"></i>6 weeks</span>
                                    <span><i class="mr-1 fas fa-book"></i>18 lessons</span>
                                </div>
                                <button class="px-4 py-2 text-sm font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                                    Start Free
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Course Card 11 - AI/ML Engineering (LOCKED) -->
                    <div class="relative overflow-hidden transition-all duration-300 bg-white shadow-lg rounded-2xl hover:shadow-xl hover:-translate-y-1 course-card locked-course" data-category="development" data-level="advanced" data-locked="true" data-unlock-requirement="Complete Data Science & Analytics + Python Data Analytics">
                        <!-- Lock Overlay -->
                        <div class="absolute inset-0 z-10 flex items-center justify-center bg-gray-900 bg-opacity-70 rounded-2xl">
                            <div class="px-6 text-center text-white">
                                <i class="mb-4 text-5xl fas fa-lock"></i>
                                <h4 class="mb-2 text-lg font-semibold">Course Locked</h4>
                                <p class="mb-4 text-sm opacity-90">Complete "Data Science & Analytics" + "Python Data Analytics"</p>
                                <div class="space-y-2">
                                    <span class="block px-3 py-1 text-xs font-medium text-black bg-yellow-500 rounded-full">
                                        <i class="mr-1 fas fa-trophy"></i>
                                        Unlock Reward: +750 XP
                                    </span>
                                    <span class="block px-3 py-1 text-xs font-medium text-white bg-purple-500 rounded-full">
                                        <i class="mr-1 fas fa-medal"></i>
                                        AI Expert Badge
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Hero Image (Blurred) -->
                        <div class="relative h-48 overflow-hidden opacity-50 bg-gradient-to-br from-gray-400 to-gray-600">
                            <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <img src="../../assets/images/courses/web.png" alt="Web Development" class="object-cover w-full h-full">

                            </div>
                        </div>

                        <!-- Content (Dimmed) -->
                        <div class="p-6 opacity-40">
                            <div class="mb-4">
                                <h3 class="mb-2 text-xl font-bold text-gray-900">AI/ML Engineering</h3>
                                <p class="text-sm leading-relaxed text-gray-600">Advanced machine learning engineering, deep learning frameworks, and production AI system deployment.</p>
                            </div>

                            <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                                <span>Coming Soon • AI/ML</span>
                                <span>Advanced Track</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span><i class="mr-1 far fa-clock"></i>20 weeks</span>
                                    <span><i class="mr-1 fas fa-book"></i>60 lessons</span>
                                </div>
                                <button class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-200 rounded-lg cursor-not-allowed" disabled>
                                    Locked
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Course Card 12 - Photography (Not Started - Paid) -->
                    <div class="overflow-hidden transition-all duration-300 bg-white shadow-lg rounded-2xl hover:shadow-xl hover:-translate-y-1 course-card" data-category="design" data-level="beginner">
                        <!-- Hero Image -->
                        <div class="relative h-48 overflow-hidden bg-gradient-to-br from-pink-500 via-rose-500 to-orange-500">
                            <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <i class="mb-4 text-6xl fas fa-camera opacity-80"></i>
                                    <div class="text-2xl font-bold tracking-wider">DIGITAL</div>
                                    <div class="text-sm opacity-90">PHOTOGRAPHY</div>
                                </div>
                            </div>
                            <!-- Status Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 text-xs font-semibold text-white bg-gray-500 rounded-full shadow-lg">
                                    Not Started
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="mb-2 text-xl font-bold text-gray-900">Digital Photography Mastery</h3>
                                <p class="text-sm leading-relaxed text-gray-600">Master composition, lighting, and post-processing techniques to create stunning photographs for personal and commercial use.</p>
                            </div>

                            <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                                <span>Available Now • Creative</span>
                                <span>Creative Track</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span><i class="mr-1 far fa-clock"></i>8 weeks</span>
                                    <span><i class="mr-1 fas fa-book"></i>24 lessons</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-gray-900">$89</div>
                                    <button class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                        Enroll Now
                                    </button>
                                </div>
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