<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../php/connect.php';
require_once '../../php/xp_system.php';
require_once '../../services/RankSystem.php';

$xp_system = new XPSystem($conn);
$rank_system = new RankSystem($conn);

$user_stats = $xp_system->getUserStats($_SESSION['user_id']);
$user_badges = $xp_system->getUserBadges($_SESSION['user_id']);
$recent_transactions = $xp_system->getRecentTransactions($_SESSION['user_id'], 5);

// Award daily login XP
$xp_system->awardXP($_SESSION['user_id'], 'login', null, 'Daily login bonus');
$xp_system->updateStudyStreak($_SESSION['user_id']);

// Refresh stats after daily bonus
$user_stats = $xp_system->getUserStats($_SESSION['user_id']);

// Check and update user rank based on current XP
$rank_info = $rank_system->checkAndUpdateUserRank($_SESSION['user_id']);
$current_rank = $rank_info['current_rank'] ?? null;
$next_rank = $rank_info['next_rank'] ?? null;
$xp_to_next = $rank_info['xp_to_next'] ?? 0;
$rank_changed = $rank_info['rank_changed'] ?? false;

// Store rank change notification for display
if ($rank_changed && $current_rank) {
    $_SESSION['rank_up_notification'] = [
        'new_rank' => $current_rank,
        'timestamp' => time()
    ];
}

// Load profile image and compute initials
$profileImageUrl = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT profile_image_url FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $profileImageUrl = $row['profile_image_url'] ?? null;
    }
}
$fullName = trim($_SESSION['user_name'] ?? '');
$parts = preg_split('/\s+/', $fullName);
$firstInitial = strtoupper(substr($parts[0] ?? 'U', 0, 1));
$secondInitial = strtoupper(substr($parts[1] ?? '', 0, 1));
$initials = $firstInitial . $secondInitial;
?>
<!-- Gamified Header for Saltel Learning Platform -->
<header class="px-6 py-4 bg-white border-b border-gray-200 shadow-sm" style="z-index: 10; position: relative;">
    <div class="flex items-center justify-between">
        <!-- Gamified Progress & Quest Panel -->
        <div class="flex-1 max-w-2xl">
            <div class="flex items-center">
                <!-- Rank & XP Display -->
                <div class="flex items-center space-x-4">
                    <!-- Current Rank Display -->
                    <?php if ($current_rank): ?>
                        <div class="flex items-center px-3 py-2 space-x-2 text-white transition-all duration-300 transform shadow-lg rounded-xl hover:shadow-xl" style="background-color: <?php echo $current_rank['rank_color']; ?>">
                            <i class="<?php echo $current_rank['rank_icon']; ?>"></i>
                            <div class="text-left">
                                <p class="text-xs font-medium opacity-90"><?php echo htmlspecialchars($current_rank['rank_title']); ?></p>
                                <p class="text-sm font-bold"><?php echo htmlspecialchars($current_rank['rank_name']); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- XP & Level Display -->
                    <div class="flex items-center px-4 py-2 space-x-3 text-white transition-all duration-300 transform shadow-lg bg-saltel rounded-xl hover:shadow-xl">
                        <div class="flex items-center space-x-2">
                            <i class="text-yellow-400 fas fa-star"></i>
                            <div class="text-left">
                                <p class="text-xs font-medium opacity-90">Level <?php echo $user_stats['current_level']; ?></p>
                                <p class="text-sm font-bold"><?php echo number_format($user_stats['total_xp']); ?> XP</p>
                            </div>
                        </div>
                        <div class="w-16 h-2 bg-white rounded-full bg-opacity-30">
                            <div class="h-2 transition-all duration-500 bg-yellow-400 rounded-full" style="width: <?php echo $user_stats['total_xp'] * 100 / ($user_stats['total_xp'] + $user_stats['xp_to_next_level']); ?>%"></div>
                        </div>
                        <span class="text-xs opacity-90"><?php echo $user_stats['xp_to_next_level']; ?> to Level <?php echo $user_stats['current_level'] + 1; ?></span>
                    </div>

                    <!-- Next Rank Progress -->
                    <?php if ($next_rank && $xp_to_next > 0): ?>
                        <div class="flex items-center px-3 py-2 space-x-2 text-gray-700 transition-all duration-300 transform bg-white border-2 border-gray-200 shadow-lg rounded-xl hover:shadow-xl hover:border-gray-300">
                            <i class="<?php echo $next_rank['rank_icon']; ?>" style="color: <?php echo $next_rank['rank_color']; ?>"></i>
                            <div class="text-left">
                                <p class="text-xs font-medium text-gray-600">Next: <?php echo htmlspecialchars($next_rank['rank_name']); ?></p>
                                <p class="text-sm font-bold"><?php echo number_format($xp_to_next); ?> XP to go</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Section: Notifications & User Menu -->
        <div class="flex items-center space-x-4">
            <!-- Enhanced Notifications -->
            <div class="relative">
                <button id="notificationBtn" class="relative text-gray-600 transition-all duration-300 transform bg-white border-2 border-transparent rounded-full shadow-lg size-12 notification-btn hover:text-blue-600 hover:shadow-xl hover:border-blue-200">
                    <i class="text-xl fas fa-bell"></i>
                    <span class="absolute flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 rounded-full notification-badge -top-1 -right-1">4</span>
                </button>

                <!-- Notification Dropdown -->
                <div id="notificationDropdown" class="absolute right-0 mt-2 transition-all duration-300 transform translate-y-2 bg-white border border-gray-200 shadow-2xl opacity-0 pointer-events-none top-full w-96 rounded-xl" style="z-index: 99999; position: fixed;">
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Notifications</h3>
                            <button class="text-sm font-medium text-blue-600 hover:text-blue-800">Mark all read</button>
                        </div>

                        <div class="space-y-3 overflow-y-auto max-h-80">
                            <!-- Assignment Due Notification -->
                            <div class="flex items-start p-3 space-x-3 transition-all duration-300 border border-gray-200 rounded-lg notification-item bg-gray-50 hover:bg-gray-100">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-10 h-10 bg-red-500 rounded-full">
                                        <i class="text-white fas fa-exclamation-triangle"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">Assignment Due Soon!</p>
                                    <p class="text-xs text-gray-600">Data Science Quiz due today at 11:59 PM</p>
                                    <p class="mt-1 text-xs text-gray-500">2 hours remaining</p>
                                </div>
                                <button class="text-red-600 hover:text-red-800">
                                    <i class="text-sm fas fa-external-link-alt"></i>
                                </button>
                            </div>

                            <!-- New Badge Earned -->
                            <div class="flex items-start p-3 space-x-3 transition-all duration-300 border border-gray-200 rounded-lg notification-item bg-gray-50 hover:bg-gray-100">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-10 h-10 bg-yellow-500 rounded-full">
                                        <i class="text-white fas fa-medal"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">New Badge Earned! 🏆</p>
                                    <p class="text-xs text-gray-600">"Quick Learner" badge unlocked</p>
                                    <p class="mt-1 text-xs text-gray-500">+50 XP bonus • 15 minutes ago</p>
                                </div>
                            </div>

                            <!-- Course Progress -->
                            <div class="flex items-start p-3 space-x-3 transition-all duration-300 border border-gray-200 rounded-lg notification-item bg-gray-50 hover:bg-gray-100">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-10 h-10 bg-green-500 rounded-full">
                                        <i class="text-white fas fa-chart-line"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">Course Progress Update</p>
                                    <p class="text-xs text-gray-600">You're now 75% through Machine Learning Basics</p>
                                    <p class="mt-1 text-xs text-gray-500">1 hour ago</p>
                                </div>
                            </div>

                            <!-- New Content Unlocked -->
                            <div class="flex items-start p-3 space-x-3 transition-all duration-300 border border-gray-200 rounded-lg notification-item bg-gray-50 hover:bg-gray-100">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center w-10 h-10 bg-purple-500 rounded-full">
                                        <i class="text-white fas fa-unlock"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">New Content Unlocked! 🔓</p>
                                    <p class="text-xs text-gray-600">Advanced Analytics module is now available</p>
                                    <p class="mt-1 text-xs text-gray-500">3 hours ago</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 mt-4 border-t border-gray-200">
                            <button class="w-full px-4 py-2 text-sm text-gray-600 transition-colors rounded-lg hover:text-gray-800 hover:bg-gray-50">
                                View All Notifications
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gamified User Avatar & Dropdown -->
            <div class="relative">
                <button id="userMenuBtn" class="flex items-center p-1 space-x-3 transition-all duration-300 transform bg-white border-2 border-transparent shadow-lg user-menu-btn rounded-xl hover:shadow-xl hover:border-purple-200">
                    <div class="relative">
                        <!-- Avatar with Level Ring -->
                        <div class="size-10 rounded-full bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 p-0.5">
                            <div class="flex items-center justify-center w-full h-full overflow-hidden bg-white rounded-full">
                                <?php if (!empty($profileImageUrl)) { ?>
                                    <img src="<?php echo '../../' . htmlspecialchars($profileImageUrl); ?>" alt="Avatar" class="object-cover w-full h-full rounded-full" />
                                <?php } else { ?>
                                    <span class="text-lg font-bold text-transparent bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text"><?php echo htmlspecialchars($initials); ?></span>
                                <?php } ?>
                            </div>
                        </div>
                        <!-- Level Badge -->
                        <div class="absolute flex items-center justify-center border-2 border-white rounded-full size-5 -bottom-1 -right-1 bg-gradient-to-r from-yellow-400 to-orange-500">
                            <span class="text-[9px] font-bold text-white"><?php echo $user_stats['current_level']; ?></span>
                        </div>
                    </div>
                    <div class="hidden text-left md:block">
                        <p class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($parts[0][0] ?? 'U') . '. ' . htmlspecialchars($parts[1] ?? ($parts[0] ?? 'User')); ?></p>
                        <p class="text-xs text-gray-600">Level <?php echo $user_stats['current_level']; ?> • <?php echo number_format($user_stats['total_xp']); ?> XP</p>
                    </div>
                    <i class="text-gray-400 transition-transform duration-300 fas fa-chevron-down" id="userChevron"></i>
                </button>

                <!-- User Dropdown Menu -->
                <div id="userDropdown" class="absolute right-0 mt-2 transition-all duration-300 transform translate-y-2 bg-white border border-gray-200 shadow-2xl opacity-0 pointer-events-none top-full w-80 rounded-xl" style="z-index: 99999; position: fixed;">
                    <div class="p-4">
                        <!-- User Info Header -->
                        <div class="flex items-center pb-4 space-x-3 border-b border-gray-200">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 p-0.5">
                                <div class="flex items-center justify-center w-full h-full overflow-hidden bg-white rounded-full">
                                    <?php if (!empty($profileImageUrl)) { ?>
                                        <img src="<?php echo '../../' . htmlspecialchars($profileImageUrl); ?>" alt="Avatar" class="object-cover w-full h-full rounded-full" />
                                    <?php } else { ?>
                                        <span class="text-xl font-bold text-transparent bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text"><?php echo htmlspecialchars($initials); ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900"><?php echo $_SESSION['user_name']; ?></h3>
                                <p class="text-sm text-gray-600"><?php echo $_SESSION['user_email']; ?></p>
                                <div class="flex items-center mt-1 space-x-2">
                                    <?php if ($current_rank): ?>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full" style="background-color: <?php echo $current_rank['rank_color']; ?>20; color: <?php echo $current_rank['rank_color']; ?>">
                                            <i class="<?php echo $current_rank['rank_icon']; ?> mr-1"></i><?php echo htmlspecialchars($current_rank['rank_name']); ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="px-2 py-1 text-xs font-medium text-purple-800 bg-purple-100 rounded-full">Level <?php echo $user_stats['current_level']; ?></span>
                                    <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full"><?php echo number_format($user_stats['total_xp']); ?> XP</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="grid grid-cols-3 gap-3 py-4 border-b border-gray-200">
                            <div class="text-center">
                                <div class="text-lg font-bold text-blue-600"><?php echo count($user_badges); ?></div>
                                <div class="text-xs text-gray-500">Courses</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-green-600"><?php echo count($user_badges); ?></div>
                                <div class="text-xs text-gray-500">Certificates</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-purple-600"><?php echo count($user_badges); ?></div>
                                <div class="text-xs text-gray-500">Badges</div>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-2">
                            <a href="profile.php" class="flex items-center w-full px-3 py-2 space-x-3 transition-colors rounded-lg hover:bg-gray-50 hover:text-gray-600">
                                <i class="w-5 fas fa-user"></i>
                                <span class="font-medium">Profile</span>
                            </a>
                            <a href="settings.php" class="flex items-center w-full px-3 py-2 space-x-3 transition-colors rounded-lg hover:bg-gray-50 hover:text-gray-600">
                                <i class="w-5 fas fa-cog"></i>
                                <span class="font-medium">Settings</span>
                            </a>
                            <a href="faq.php" class="flex items-center w-full px-3 py-2 space-x-3 transition-colors rounded-lg hover:bg-blue-50 hover:text-blue-600">
                                <i class="w-5 fas fa-question-circle"></i>
                                <span class="font-medium">FAQ</span>
                            </a>
                            <button id="themeToggle" class="flex items-center w-full px-3 py-2 space-x-3 transition-colors rounded-lg hover:bg-blue-50 hover:text-blue-600">
                                <i class="w-5 fas fa-moon"></i>
                                <span class="font-medium">Dark Mode</span>
                            </button>
                        </div>

                        <!-- Bottom Actions -->
                        <div class="pt-2 border-t border-gray-200">
                            <button class="flex items-center w-full px-3 py-2 space-x-3 transition-colors rounded-lg hover:bg-red-50 hover:text-red-600">
                                <i class="w-5 fas fa-sign-out-alt"></i>
                                <span class="font-medium">Sign Out</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Rank Up Notification Modal -->
<?php if (isset($_SESSION['rank_up_notification']) && (time() - $_SESSION['rank_up_notification']['timestamp']) < 30): ?>
    <div id="rankUpModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="relative max-w-md p-8 mx-4 text-center bg-white shadow-2xl rounded-2xl animate-bounce">
            <div class="absolute transform -translate-x-1/2 -top-4 left-1/2">
                <div class="flex items-center justify-center w-16 h-16 rounded-full shadow-lg" style="background-color: <?php echo $_SESSION['rank_up_notification']['new_rank']['rank_color']; ?>">
                    <i class="text-2xl text-white <?php echo $_SESSION['rank_up_notification']['new_rank']['rank_icon']; ?>"></i>
                </div>
            </div>

            <div class="mt-8">
                <h2 class="mb-2 text-2xl font-bold text-gray-900">🎉 Rank Up!</h2>
                <p class="mb-4 text-gray-600">Congratulations! You've achieved a new rank:</p>

                <div class="p-4 mb-6 rounded-lg" style="background-color: <?php echo $_SESSION['rank_up_notification']['new_rank']['rank_color']; ?>20;">
                    <h3 class="text-xl font-bold" style="color: <?php echo $_SESSION['rank_up_notification']['new_rank']['rank_color']; ?>">
                        <?php echo htmlspecialchars($_SESSION['rank_up_notification']['new_rank']['rank_title']); ?>
                    </h3>
                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($_SESSION['rank_up_notification']['new_rank']['rank_name']); ?></p>
                </div>

                <button onclick="closeRankUpModal()" class="px-6 py-2 text-white transition-colors rounded-lg hover:opacity-90" style="background-color: <?php echo $_SESSION['rank_up_notification']['new_rank']['rank_color']; ?>">
                    Continue Learning!
                </button>
            </div>
        </div>
    </div>

    <script>
        function closeRankUpModal() {
            document.getElementById('rankUpModal').style.display = 'none';
            // Clear the notification from session
            fetch('clear-rank-notification.php', {
                method: 'POST'
            });
        }

        // Auto-close after 10 seconds
        setTimeout(closeRankUpModal, 10000);
    </script>

    <?php
    // Clear the notification after displaying
    unset($_SESSION['rank_up_notification']);
    ?>
<?php endif; ?>

<!-- JavaScript for Interactive Elements -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quest Panel Toggle
        const questBtn = document.getElementById('questPanelBtn');
        const questDropdown = document.getElementById('questDropdown');

        if (questBtn && questDropdown) {
            questBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleDropdown(questDropdown);
            });
        }

        // Notification Toggle
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationDropdown = document.getElementById('notificationDropdown');

        if (notificationBtn && notificationDropdown) {
            notificationBtn.addEventListener('click', function(e) {
                e.stopPropagation();

                // Calculate position for fixed dropdown
                const rect = notificationBtn.getBoundingClientRect();
                notificationDropdown.style.top = (rect.bottom + 8) + 'px';
                notificationDropdown.style.right = (window.innerWidth - rect.right) + 'px';

                toggleDropdown(notificationDropdown);
            });
        }

        // User Menu Toggle
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');
        const userChevron = document.getElementById('userChevron');

        if (userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();

                // Calculate position for fixed dropdown
                const rect = userMenuBtn.getBoundingClientRect();
                userDropdown.style.top = (rect.bottom + 8) + 'px';
                userDropdown.style.right = (window.innerWidth - rect.right) + 'px';

                toggleDropdown(userDropdown);
                if (userChevron) {
                    userChevron.classList.toggle('rotate-180');
                }
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function() {
            closeAllDropdowns();
        });

        function toggleDropdown(dropdown) {
            const isOpen = !dropdown.classList.contains('opacity-0');

            // Close all dropdowns first
            closeAllDropdowns();

            // Open the clicked dropdown if it wasn't already open
            if (!isOpen) {
                dropdown.classList.remove('opacity-0', 'translate-y-2', 'pointer-events-none');
                dropdown.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
            }
        }

        function closeAllDropdowns() {
            const dropdowns = [questDropdown, notificationDropdown, userDropdown];
            dropdowns.forEach(dropdown => {
                if (dropdown) {
                    dropdown.classList.add('opacity-0', 'translate-y-2', 'pointer-events-none');
                    dropdown.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
                }
            });

            if (userChevron) {
                userChevron.classList.remove('rotate-180');
            }
        }

        // Animate XP bar on load
        setTimeout(() => {
            const xpBar = document.querySelector('.bg-yellow-400');
            if (xpBar) {
                xpBar.style.width = '<?php echo $user_stats['total_xp'] * 100 / ($user_stats['total_xp'] + $user_stats['xp_to_next_level']); ?>%';
                setTimeout(() => {
                    xpBar.style.width = '<?php echo $user_stats['total_xp'] * 100 / ($user_stats['total_xp'] + $user_stats['xp_to_next_level']); ?>%';
                }, 500);
            }
        }, 100);
    });
</script>