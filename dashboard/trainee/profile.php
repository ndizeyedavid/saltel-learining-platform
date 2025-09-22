<?php

// Fake user data for design purposes
$user_data = [
    'id' => 1,
    'first_name' => 'Christopher',
    'last_name' => 'David',
    'email' => 'christopherdavid@email.com',
    'phone' => '+250 788 888 888',
    'bio' => 'Passionate data scientist with 3 years of experience in machine learning and analytics. Currently expanding my skills in advanced AI techniques.',
    'location' => 'Kigali, Rwanda',
    'joined_date' => '2023-01-15',
    'avatar' => '../../assets/images/discussions/placeholder.png',
    'cover_image' => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=1200&h=300&fit=crop',
    'social_links' => [
        'linkedin' => 'https://linkedin.com/in/christopherdavid',
        'github' => 'https://github.com/christopherdavid',
        'twitter' => 'https://twitter.com/christopherdavid'
    ],
    'skills' => ['Python', 'Machine Learning', 'Data Analysis', 'SQL', 'Tableau', 'R', 'Statistics', 'Deep Learning'],
    'achievements' => [
        ['title' => 'Data Science Specialist', 'date' => '2024-03-15', 'icon' => 'fas fa-chart-line'],
        ['title' => 'Python Expert', 'date' => '2024-02-20', 'icon' => 'fab fa-python'],
        ['title' => 'ML Practitioner', 'date' => '2024-01-10', 'icon' => 'fas fa-brain'],
        ['title' => 'Analytics Pro', 'date' => '2023-12-05', 'icon' => 'fas fa-chart-bar']
    ],
    'stats' => [
        'courses_completed' => 12,
        'courses_in_progress' => 3,
        'certificates_earned' => 8,
        'study_hours' => 245,
        'streak_days' => 28,
        'badges_earned' => 15
    ]
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Saltel • Trainee Dashboard</title>
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
                <!-- Profile Header -->
                <div class="relative mb-8 overflow-hidden bg-white shadow-sm rounded-xl">
                    <!-- Cover Image -->
                    <div class="h-64 overflow-hidden bg-red-400" style="background: linear-gradient(to bottom, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?php echo $user_data['cover_image']; ?>'); background-size: cover; background-position: center;">
                    </div>

                    <!-- Profile Info -->
                    <div class="relative px-6 pb-6">
                        <!-- Avatar -->
                        <div class="flex items-end justify-between -mt-16">
                            <div class="relative">
                                <img src="../../assets/images/discussions/placeholder.png" alt="Profile" class="border-4 border-white rounded-full shadow-lg size-32">
                            </div>
                            <a href="settings.php" class="px-4 py-2 text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                <i class="mr-2 fas fa-edit"></i>Edit Profile
                            </a>
                        </div>

                        <!-- User Info -->
                        <div class="mt-4">
                            <h1 class="text-3xl font-bold text-gray-900"><?php echo $user_data['first_name'] . ' ' . $user_data['last_name']; ?></h1>
                            <p class="mt-1 text-gray-600"><?php echo $user_data['email']; ?></p>
                            <div class="flex items-center mt-2 text-sm text-gray-500">
                                <i class="mr-1 fas fa-map-marker-alt"></i>
                                <span><?php echo $user_data['location']; ?></span>
                                <span class="mx-2">•</span>
                                <i class="mr-1 fas fa-calendar"></i>
                                <span>Joined <?php echo date('F Y', strtotime($user_data['joined_date'])); ?></span>
                            </div>

                            <!-- Bio -->
                            <p class="mt-4 leading-relaxed text-gray-700"><?php echo $user_data['bio']; ?></p>

                            <!-- Social Links -->
                            <div class="flex mt-4 space-x-4">
                                <?php foreach ($user_data['social_links'] as $platform => $url): ?>
                                    <a href="<?php echo $url; ?>" target="_blank" class="text-gray-400 transition-colors hover:text-blue-600">
                                        <i class="fab fa-<?php echo $platform; ?> text-xl"></i>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3 lg:grid-cols-6">
                    <div class="p-6 text-center bg-white shadow-sm rounded-xl">
                        <div class="text-2xl font-bold text-blue-600"><?php echo $user_data['stats']['courses_completed']; ?></div>
                        <div class="mt-1 text-sm text-gray-500">Completed</div>
                    </div>
                    <div class="p-6 text-center bg-white shadow-sm rounded-xl">
                        <div class="text-2xl font-bold text-orange-600"><?php echo $user_data['stats']['courses_in_progress']; ?></div>
                        <div class="mt-1 text-sm text-gray-500">In Progress</div>
                    </div>
                    <div class="p-6 text-center bg-white shadow-sm rounded-xl">
                        <div class="text-2xl font-bold text-green-600"><?php echo $user_data['stats']['certificates_earned']; ?></div>
                        <div class="mt-1 text-sm text-gray-500">Certificates</div>
                    </div>
                    <div class="p-6 text-center bg-white shadow-sm rounded-xl">
                        <div class="text-2xl font-bold text-purple-600"><?php echo $user_data['stats']['study_hours']; ?></div>
                        <div class="mt-1 text-sm text-gray-500">Study Hours</div>
                    </div>
                    <div class="p-6 text-center bg-white shadow-sm rounded-xl">
                        <div class="text-2xl font-bold text-red-600"><?php echo $user_data['stats']['streak_days']; ?></div>
                        <div class="mt-1 text-sm text-gray-500">Day Streak</div>
                    </div>
                    <div class="p-6 text-center bg-white shadow-sm rounded-xl">
                        <div class="text-2xl font-bold text-yellow-600"><?php echo $user_data['stats']['badges_earned']; ?></div>
                        <div class="mt-1 text-sm text-gray-500">Badges</div>
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <!-- Left Column -->
                    <div class="space-y-8 lg:col-span-2">
                        <!-- Recent Achievements -->
                        <div class="p-6 bg-white shadow-sm rounded-xl">
                            <h2 class="mb-6 text-xl font-semibold text-gray-900">Recent Achievements</h2>
                            <div class="space-y-4">
                                <?php foreach ($user_data['achievements'] as $achievement): ?>
                                    <div class="flex items-center p-4 space-x-4 rounded-lg bg-gray-50">
                                        <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full">
                                            <i class="<?php echo $achievement['icon']; ?> text-blue-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-medium text-gray-900"><?php echo $achievement['title']; ?></h3>
                                            <p class="text-sm text-gray-500">Earned on <?php echo date('M d, Y', strtotime($achievement['date'])); ?></p>
                                        </div>
                                        <div class="text-yellow-500">
                                            <i class="text-xl fas fa-medal"></i>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column -->
                    <div class="space-y-8">
                        <!-- Skills -->
                        <div class="p-6 bg-white shadow-sm rounded-xl">
                            <h2 class="mb-6 text-xl font-semibold text-gray-900">Skills</h2>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($user_data['skills'] as $skill): ?>
                                    <span class="px-3 py-1 text-sm font-medium text-blue-800 bg-blue-100 rounded-full"><?php echo $skill; ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>


                        <!-- Contact Info -->
                        <div class="p-6 bg-white shadow-sm rounded-xl">
                            <h2 class="mb-6 text-xl font-semibold text-gray-900">Contact Information</h2>
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3">
                                    <i class="text-gray-400 fas fa-envelope"></i>
                                    <span class="text-gray-700"><?php echo $user_data['email']; ?></span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <i class="text-gray-400 fas fa-phone"></i>
                                    <span class="text-gray-700"><?php echo $user_data['phone']; ?></span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <i class="text-gray-400 fas fa-map-marker-alt"></i>
                                    <span class="text-gray-700"><?php echo $user_data['location']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <script src="../../assets/js/app.js"></script>
</body>

</html>