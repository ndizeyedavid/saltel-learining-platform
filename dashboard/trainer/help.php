<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saltel • Help Center</title>
    <?php include '../../include/trainer-imports.php'; ?>
</head>
<body class="font-sans bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <?php include '../../components/Trainer-Sidebar.php'; ?>

        <div class="flex flex-col flex-1 overflow-hidden">
            <?php include '../../components/Trainer-Header.php'; ?>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-4xl px-6 py-8 mx-auto">
                    <h1 class="mb-8 text-2xl font-bold text-gray-900">Help Center</h1>

                    <!-- Search Bar -->
                    <div class="mb-8">
                        <div class="relative">
                            <input type="text" 
                                   class="w-full px-4 py-3 pl-12 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                                   placeholder="Search help articles...">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <i class="text-gray-400 fas fa-search"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3">
                        <a href="#" class="p-6 transition-shadow bg-white rounded-lg shadow-sm hover:shadow-md">
                            <div class="flex items-center mb-4">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100">
                                    <i class="text-blue-600 fas fa-book"></i>
                                </div>
                                <h3 class="ml-3 text-lg font-medium text-gray-900">Getting Started</h3>
                            </div>
                            <p class="text-sm text-gray-600">Learn the basics of creating and managing courses.</p>
                        </a>
                        <a href="#" class="p-6 transition-shadow bg-white rounded-lg shadow-sm hover:shadow-md">
                            <div class="flex items-center mb-4">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100">
                                    <i class="text-green-600 fas fa-chalkboard-teacher"></i>
                                </div>
                                <h3 class="ml-3 text-lg font-medium text-gray-900">Teaching Tools</h3>
                            </div>
                            <p class="text-sm text-gray-600">Discover tools and features for effective teaching.</p>
                        </a>
                        <a href="#" class="p-6 transition-shadow bg-white rounded-lg shadow-sm hover:shadow-md">
                            <div class="flex items-center mb-4">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-purple-100">
                                    <i class="text-purple-600 fas fa-chart-line"></i>
                                </div>
                                <h3 class="ml-3 text-lg font-medium text-gray-900">Analytics Guide</h3>
                            </div>
                            <p class="text-sm text-gray-600">Learn how to track and analyze student progress.</p>
                        </a>
                    </div>

                    <!-- FAQ Section -->
                    <div class="p-6 bg-white rounded-lg shadow-sm">
                        <h2 class="mb-6 text-xl font-semibold text-gray-900">Frequently Asked Questions</h2>
                        <div class="space-y-4">
                            <div class="p-4 transition-colors rounded-lg hover:bg-gray-50">
                                <button class="flex items-center justify-between w-full text-left">
                                    <h3 class="text-base font-medium text-gray-900">How do I create a new course?</h3>
                                    <i class="text-gray-400 fas fa-chevron-down"></i>
                                </button>
                                <div class="mt-2 text-sm text-gray-600">
                                    Click the "Create New Course" button on your dashboard and follow the step-by-step guide to set up your course content, assignments, and settings.
                                </div>
                            </div>
                            <div class="p-4 transition-colors rounded-lg hover:bg-gray-50">
                                <button class="flex items-center justify-between w-full text-left">
                                    <h3 class="text-base font-medium text-gray-900">How do I manage student enrollments?</h3>
                                    <i class="text-gray-400 fas fa-chevron-down"></i>
                                </button>
                                <div class="mt-2 text-sm text-gray-600">
                                    Navigate to your course settings to manage enrollment settings, view enrolled students, and handle enrollment requests.
                                </div>
                            </div>
                            <div class="p-4 transition-colors rounded-lg hover:bg-gray-50">
                                <button class="flex items-center justify-between w-full text-left">
                                    <h3 class="text-base font-medium text-gray-900">How do I grade assignments?</h3>
                                    <i class="text-gray-400 fas fa-chevron-down"></i>
                                </button>
                                <div class="mt-2 text-sm text-gray-600">
                                    Access the assignments section in your course, view submissions, and use our grading tools to provide feedback and scores.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Support -->
                    <div class="p-6 mt-6 bg-white rounded-lg shadow-sm">
                        <div class="text-center">
                            <h2 class="mb-2 text-xl font-semibold text-gray-900">Need More Help?</h2>
                            <p class="mb-4 text-gray-600">Our support team is available 24/7 to assist you</p>
                            <div class="flex justify-center space-x-4">
                                <a href="#" class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                    <i class="mr-2 fas fa-headset"></i>Contact Support
                                </a>
                                <a href="#" class="px-6 py-2 text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50">
                                    <i class="mr-2 fas fa-video"></i>Video Tutorials
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // FAQ Accordion functionality
        document.querySelectorAll('.faq button').forEach(button => {
            button.addEventListener('click', () => {
                const content = button.nextElementSibling;
                content.style.display = content.style.display === 'none' ? 'block' : 'none';
                button.querySelector('i').classList.toggle('fa-chevron-up');
                button.querySelector('i').classList.toggle('fa-chevron-down');
            });
        });
    </script>
</body>
</html>