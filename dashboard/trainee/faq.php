<?php

// FAQ data with categories
$faq_categories = [
    'Getting Started' => [
        [
            'question' => 'How do I enroll in a course?',
            'answer' => 'To enroll in a course, navigate to the Courses page, browse available courses, and click the "Enroll Now" button on any course that interests you. Some courses may have prerequisites that need to be completed first.'
        ],
        [
            'question' => 'How do I access my enrolled courses?',
            'answer' => 'Once enrolled, you can access your courses from the Dashboard or by clicking on "My Courses" in the sidebar. Your active courses will be displayed with progress indicators.'
        ],
        [
            'question' => 'Can I preview a course before enrolling?',
            'answer' => 'Yes! Most courses offer a preview option where you can watch the first lesson or view the course outline before making a commitment to enroll.'
        ]
    ],
    'Learning & Progress' => [
        [
            'question' => 'How is my progress tracked?',
            'answer' => 'Your progress is automatically tracked as you complete lessons, quizzes, and assignments. You can view detailed progress reports on your Dashboard and Progress pages.'
        ],
        [
            'question' => 'What happens if I fail a quiz?',
            'answer' => 'Don\'t worry! You can retake quizzes multiple times. We recommend reviewing the lesson material before attempting again. Your highest score will be recorded.'
        ],
        [
            'question' => 'How do I download my certificates?',
            'answer' => 'Once you complete a course, your certificate will be available in the Certificates section. You can download it as a PDF or share it directly on social media.'
        ]
    ],
    'Technical Support' => [
        [
            'question' => 'I\'m having trouble playing videos. What should I do?',
            'answer' => 'First, check your internet connection. If the problem persists, try refreshing the page or clearing your browser cache. For persistent issues, contact our technical support team.'
        ],
        [
            'question' => 'Can I access courses on mobile devices?',
            'answer' => 'Absolutely! Our platform is fully responsive and works on all devices. You can also download our mobile app for the best mobile learning experience.'
        ],
        [
            'question' => 'What browsers are supported?',
            'answer' => 'We support all modern browsers including Chrome, Firefox, Safari, and Edge. For the best experience, we recommend using the latest version of your preferred browser.'
        ]
    ],
    'Account & Billing' => [
        [
            'question' => 'How do I change my password?',
            'answer' => 'Go to Settings > Account Settings and click on "Change Password". You\'ll need to enter your current password and then your new password twice for confirmation.'
        ],
        [
            'question' => 'Can I get a refund for a course?',
            'answer' => 'Yes, we offer a 30-day money-back guarantee for all paid courses. If you\'re not satisfied, contact our support team within 30 days of purchase for a full refund.'
        ],
        [
            'question' => 'How do I update my billing information?',
            'answer' => 'You can update your billing information in the Settings section under "Billing & Payments". All changes are secured with industry-standard encryption.'
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Saltel Learning Platform</title>
    <?php include '../../include/imports.php'; ?>
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
                <div class="max-w-4xl mx-auto">
                    <!-- Header -->
                    <div class="mb-12 text-center">
                        <h1 class="mb-4 text-4xl font-bold text-gray-900">Frequently Asked Questions</h1>
                        <p class="text-xl text-gray-600">Find answers to common questions about our learning platform</p>
                    </div>

                    <!-- Search Bar -->
                    <div class="mb-8">
                        <div class="relative">
                            <input type="text" id="faqSearch" placeholder="Search for answers..." class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <i class="absolute text-gray-400 transform -translate-y-1/2 fas fa-search left-4 top-1/2"></i>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="grid grid-cols-2 gap-4 mb-12 md:grid-cols-4">
                        <?php foreach (array_keys($faq_categories) as $index => $category): ?>
                            <button class="p-4 text-center transition-shadow bg-white shadow-sm category-filter rounded-xl hover:shadow-md" data-category="<?php echo strtolower(str_replace([' ', '&'], ['-', 'and'], $category)); ?>">
                                <div class="mb-2 text-2xl">
                                    <?php
                                    $icons = ['fas fa-rocket', 'fas fa-chart-line', 'fas fa-tools', 'fas fa-credit-card'];
                                    echo '<i class="' . $icons[$index] . ' text-blue-600"></i>';
                                    ?>
                                </div>
                                <h3 class="font-medium text-gray-900"><?php echo $category; ?></h3>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <!-- FAQ Categories -->
                    <?php foreach ($faq_categories as $category => $questions): ?>
                        <div class="mb-8 faq-category" data-category="<?php echo strtolower(str_replace([' ', '&'], ['-', 'and'], $category)); ?>">
                            <h2 class="flex items-center mb-6 text-2xl font-bold text-gray-900">
                                <?php
                                $category_icons = [
                                    'Getting Started' => 'fas fa-rocket',
                                    'Learning & Progress' => 'fas fa-chart-line',
                                    'Technical Support' => 'fas fa-tools',
                                    'Account & Billing' => 'fas fa-credit-card'
                                ];
                                ?>
                                <i class="<?php echo $category_icons[$category]; ?> text-blue-600 mr-3"></i>
                                <?php echo $category; ?>
                            </h2>

                            <div class="space-y-4">
                                <?php foreach ($questions as $index => $faq): ?>
                                    <div class="overflow-hidden bg-white shadow-sm faq-item rounded-xl">
                                        <button class="flex items-center justify-between w-full px-6 py-4 text-left transition-colors faq-question hover:bg-gray-50" data-target="faq-<?php echo strtolower(str_replace([' ', '&'], ['-', 'and'], $category)) . '-' . $index; ?>">
                                            <span class="pr-4 font-medium text-gray-900"><?php echo $faq['question']; ?></span>
                                            <i class="text-gray-400 transition-transform transform fas fa-chevron-down"></i>
                                        </button>
                                        <div id="faq-<?php echo strtolower(str_replace([' ', '&'], ['-', 'and'], $category)) . '-' . $index; ?>" class="hidden px-6 pb-4 faq-answer">
                                            <p class="leading-relaxed text-gray-600"><?php echo $faq['answer']; ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Contact Support -->
                    <div class="p-8 mt-12 text-center text-white bg-blue-500 rounded-xl">
                        <h2 class="mb-4 text-2xl font-bold">Still have questions?</h2>
                        <p class="mb-6 text-blue-100">Our support team is here to help you succeed in your learning journey.</p>
                        <div class="flex flex-col justify-center gap-4 sm:flex-row">
                            <button class="px-6 py-3 font-medium text-blue-600 transition-colors bg-white rounded-lg hover:bg-gray-100">
                                <i class="mr-2 fas fa-envelope"></i>Email Support
                            </button>
                            <button class="px-6 py-3 font-medium text-white transition-colors bg-blue-700 rounded-lg hover:bg-blue-800">
                                <i class="mr-2 fas fa-comments"></i>Live Chat
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // FAQ accordion functionality
                const faqQuestions = document.querySelectorAll('.faq-question');

                faqQuestions.forEach(question => {
                    question.addEventListener('click', function() {
                        const targetId = this.dataset.target;
                        const answer = document.getElementById(targetId);
                        const icon = this.querySelector('i');

                        // Toggle answer visibility
                        answer.classList.toggle('hidden');

                        // Rotate icon
                        if (answer.classList.contains('hidden')) {
                            icon.style.transform = 'rotate(0deg)';
                        } else {
                            icon.style.transform = 'rotate(180deg)';
                        }
                    });
                });

                // Search functionality
                const searchInput = document.getElementById('faqSearch');
                const faqItems = document.querySelectorAll('.faq-item');

                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();

                    faqItems.forEach(item => {
                        const question = item.querySelector('.faq-question span').textContent.toLowerCase();
                        const answer = item.querySelector('.faq-answer p').textContent.toLowerCase();

                        if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });

                // Category filter functionality
                const categoryFilters = document.querySelectorAll('.category-filter');
                const faqCategories = document.querySelectorAll('.faq-category');

                categoryFilters.forEach(filter => {
                    filter.addEventListener('click', function() {
                        const targetCategory = this.dataset.category;

                        // Remove active state from all filters
                        categoryFilters.forEach(f => {
                            f.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
                        });

                        // Add active state to clicked filter
                        this.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');

                        // Show/hide categories
                        faqCategories.forEach(category => {
                            if (category.dataset.category === targetCategory) {
                                category.style.display = 'block';
                                category.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });
                            } else {
                                category.style.display = 'none';
                            }
                        });
                    });
                });


            });
        </script>
        <script src="../../assets/js/app.js"></script>
</body>

</html>