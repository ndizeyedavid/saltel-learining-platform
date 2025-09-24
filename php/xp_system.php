<?php
// XP and Gamification System Functions
require_once 'connect.php';

class XPSystem {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    /**
     * Initialize user XP record when they first register
     */
    public function initializeUserXP($user_id) {
        $query = "INSERT IGNORE INTO user_xp (user_id, total_xp, current_level, xp_to_next_level, study_streak) 
                  VALUES (?, 0, 1, 100, 0)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }
    
    /**
     * Award XP to user for specific activity
     */
    public function awardXP($user_id, $activity_name, $custom_xp = null, $description = null) {
        // Get activity details
        $activity_query = "SELECT activity_id, xp_reward, max_daily_earnings FROM xp_activities WHERE activity_name = ? AND is_active = 1";
        $activity_stmt = $this->conn->prepare($activity_query);
        $activity_stmt->bind_param("s", $activity_name);
        $activity_stmt->execute();
        $activity_result = $activity_stmt->get_result();
        
        if ($activity_result->num_rows === 0) {
            return false; // Activity not found
        }
        
        $activity = $activity_result->fetch_assoc();
        $xp_to_award = $custom_xp ?? $activity['xp_reward'];
        
        // Check daily limit if applicable
        if ($activity['max_daily_earnings']) {
            $today = date('Y-m-d');
            $daily_check = "SELECT SUM(xp_earned) as daily_total FROM xp_transactions 
                           WHERE user_id = ? AND activity_id = ? AND DATE(earned_at) = ?";
            $daily_stmt = $this->conn->prepare($daily_check);
            $daily_stmt->bind_param("iis", $user_id, $activity['activity_id'], $today);
            $daily_stmt->execute();
            $daily_result = $daily_stmt->get_result();
            $daily_total = $daily_result->fetch_assoc()['daily_total'] ?? 0;
            
            if ($daily_total >= $activity['max_daily_earnings']) {
                return false; // Daily limit reached
            }
            
            // Adjust XP if it would exceed daily limit
            $remaining_daily = $activity['max_daily_earnings'] - $daily_total;
            $xp_to_award = min($xp_to_award, $remaining_daily);
        }
        
        // Initialize user XP if not exists
        $this->initializeUserXP($user_id);
        
        // Record XP transaction
        $transaction_query = "INSERT INTO xp_transactions (user_id, activity_id, xp_earned, description) 
                             VALUES (?, ?, ?, ?)";
        $transaction_stmt = $this->conn->prepare($transaction_query);
        $transaction_stmt->bind_param("iiis", $user_id, $activity['activity_id'], $xp_to_award, $description);
        $transaction_stmt->execute();
        
        // Update user's total XP and level
        $this->updateUserXP($user_id, $xp_to_award);
        
        // Check for new badges
        $this->checkBadgeUnlocks($user_id);
        
        return $xp_to_award;
    }
    
    /**
     * Update user's total XP and calculate new level
     */
    private function updateUserXP($user_id, $xp_gained) {
        // Get current XP data
        $current_query = "SELECT total_xp, current_level FROM user_xp WHERE user_id = ?";
        $current_stmt = $this->conn->prepare($current_query);
        $current_stmt->bind_param("i", $user_id);
        $current_stmt->execute();
        $current_result = $current_stmt->get_result();
        $current_data = $current_result->fetch_assoc();
        
        $new_total_xp = $current_data['total_xp'] + $xp_gained;
        $current_level = $current_data['current_level'];
        
        // Calculate new level (every 100 XP = 1 level, with increasing requirements)
        $new_level = $this->calculateLevel($new_total_xp);
        $xp_to_next = $this->getXPToNextLevel($new_level, $new_total_xp);
        
        // Update user XP record
        $update_query = "UPDATE user_xp SET total_xp = ?, current_level = ?, xp_to_next_level = ?, updated_at = NOW() 
                        WHERE user_id = ?";
        $update_stmt = $this->conn->prepare($update_query);
        $update_stmt->bind_param("iiii", $new_total_xp, $new_level, $xp_to_next, $user_id);
        $update_stmt->execute();
        
        // Check if user leveled up
        if ($new_level > $current_level) {
            $this->handleLevelUp($user_id, $new_level);
        }
    }
    
    /**
     * Calculate level based on total XP
     */
    private function calculateLevel($total_xp) {
        // Level formula: Level = floor(sqrt(total_xp / 50)) + 1
        // This creates a curve where higher levels require more XP
        return floor(sqrt($total_xp / 50)) + 1;
    }
    
    /**
     * Calculate XP needed for next level
     */
    private function getXPToNextLevel($current_level, $current_xp) {
        $next_level_requirement = pow($current_level, 2) * 50;
        return max(0, $next_level_requirement - $current_xp);
    }
    
    /**
     * Handle level up rewards and notifications
     */
    private function handleLevelUp($user_id, $new_level) {
        // Award bonus XP for leveling up
        $bonus_xp = $new_level * 10;
        
        // Record level up transaction
        $level_activity = "SELECT activity_id FROM xp_activities WHERE activity_name = 'level_up'";
        $level_result = $this->conn->query($level_activity);
        
        if ($level_result && $level_result->num_rows > 0) {
            $activity_id = $level_result->fetch_assoc()['activity_id'];
            $transaction_query = "INSERT INTO xp_transactions (user_id, activity_id, xp_earned, description) 
                                 VALUES (?, ?, ?, ?)";
            $transaction_stmt = $this->conn->prepare($transaction_query);
            $description = "Level up bonus - reached level $new_level";
            $transaction_stmt->bind_param("iiis", $user_id, $activity_id, $bonus_xp, $description);
            $transaction_stmt->execute();
        }
    }
    
    /**
     * Update study streak
     */
    public function updateStudyStreak($user_id) {
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        
        // Get current streak data
        $streak_query = "SELECT study_streak, last_activity_date FROM user_xp WHERE user_id = ?";
        $streak_stmt = $this->conn->prepare($streak_query);
        $streak_stmt->bind_param("i", $user_id);
        $streak_stmt->execute();
        $streak_result = $streak_stmt->get_result();
        $streak_data = $streak_result->fetch_assoc();
        
        $current_streak = $streak_data['study_streak'] ?? 0;
        $last_activity = $streak_data['last_activity_date'];
        
        if ($last_activity === $yesterday) {
            // Continue streak
            $new_streak = $current_streak + 1;
        } elseif ($last_activity === $today) {
            // Already studied today
            return $current_streak;
        } else {
            // Streak broken, start over
            $new_streak = 1;
        }
        
        // Update streak
        $update_query = "UPDATE user_xp SET study_streak = ?, last_activity_date = ? WHERE user_id = ?";
        $update_stmt = $this->conn->prepare($update_query);
        $update_stmt->bind_param("isi", $new_streak, $today, $user_id);
        $update_stmt->execute();
        
        // Award streak bonuses
        if ($new_streak === 7) {
            $this->awardXP($user_id, 'study_streak_7', null, '7-day study streak achieved!');
        } elseif ($new_streak === 30) {
            $this->awardXP($user_id, 'study_streak_30', null, '30-day study streak achieved!');
        }
        
        return $new_streak;
    }
    
    /**
     * Check and unlock badges for user
     */
    public function checkBadgeUnlocks($user_id) {
        // Get user's current stats
        $stats = $this->getUserStats($user_id);
        
        // Get all badges user hasn't earned yet
        $badge_query = "SELECT b.* FROM badges b 
                       WHERE b.is_active = 1 
                       AND b.badge_id NOT IN (SELECT badge_id FROM user_badges WHERE user_id = ?)
                       AND b.xp_requirement <= ?";
        $badge_stmt = $this->conn->prepare($badge_query);
        $badge_stmt->bind_param("ii", $user_id, $stats['total_xp']);
        $badge_stmt->execute();
        $badge_result = $badge_stmt->get_result();
        
        while ($badge = $badge_result->fetch_assoc()) {
            if ($this->checkBadgeCondition($user_id, $badge['unlock_condition'], $stats)) {
                $this->awardBadge($user_id, $badge['badge_id']);
            }
        }
    }
    
    /**
     * Award badge to user
     */
    private function awardBadge($user_id, $badge_id) {
        $award_query = "INSERT IGNORE INTO user_badges (user_id, badge_id) VALUES (?, ?)";
        $award_stmt = $this->conn->prepare($award_query);
        $award_stmt->bind_param("ii", $user_id, $badge_id);
        return $award_stmt->execute();
    }
    
    /**
     * Check if badge condition is met
     */
    private function checkBadgeCondition($user_id, $condition, $stats) {
        switch ($condition) {
            case 'complete_first_lesson':
                return $this->hasCompletedLessons($user_id, 1);
            case 'study_5_hours':
                return $this->getTotalStudyMinutes($user_id) >= 300;
            case 'complete_10_assignments':
                return $this->getCompletedAssignments($user_id) >= 10;
            case 'study_streak_7':
                return $stats['study_streak'] >= 7;
            case 'earn_1000_xp':
                return $stats['total_xp'] >= 1000;
            case 'complete_first_course':
                return $this->hasCompletedCourses($user_id, 1);
            case 'perfect_5_assignments':
                return $this->getPerfectAssignments($user_id) >= 5;
            case 'reach_level_10':
                return $stats['current_level'] >= 10;
            default:
                return false;
        }
    }
    
    /**
     * Get user's complete stats
     */
    public function getUserStats($user_id) {
        $this->initializeUserXP($user_id);
        
        $stats_query = "SELECT ux.*, 
                               (SELECT COUNT(*) FROM user_badges WHERE user_id = ux.user_id) as badges_earned,
                               (SELECT COUNT(DISTINCT course_id) FROM enrollments WHERE student_id = 
                                (SELECT student_id FROM students WHERE user_id = ux.user_id)) as courses_enrolled
                        FROM user_xp ux WHERE ux.user_id = ?";
        $stats_stmt = $this->conn->prepare($stats_query);
        $stats_stmt->bind_param("i", $user_id);
        $stats_stmt->execute();
        $result = $stats_stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return [
            'total_xp' => 0,
            'current_level' => 1,
            'xp_to_next_level' => 100,
            'study_streak' => 0,
            'badges_earned' => 0,
            'courses_enrolled' => 0
        ];
    }
    
    /**
     * Get user's recent XP transactions
     */
    public function getRecentTransactions($user_id, $limit = 10) {
        $query = "SELECT xt.*, xa.activity_name, xa.activity_description 
                  FROM xp_transactions xt 
                  JOIN xp_activities xa ON xt.activity_id = xa.activity_id 
                  WHERE xt.user_id = ? 
                  ORDER BY xt.earned_at DESC 
                  LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Get user's earned badges
     */
    public function getUserBadges($user_id) {
        $query = "SELECT b.*, ub.earned_at 
                  FROM user_badges ub 
                  JOIN badges b ON ub.badge_id = b.badge_id 
                  WHERE ub.user_id = ? 
                  ORDER BY ub.earned_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Helper methods for badge conditions
    private function hasCompletedLessons($user_id, $count) {
        // This would check against your lesson completion system
        return true; // Placeholder
    }
    
    private function getTotalStudyMinutes($user_id) {
        $query = "SELECT SUM(session_duration) as total FROM study_sessions WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] ?? 0;
    }
    
    private function getCompletedAssignments($user_id) {
        $query = "SELECT COUNT(*) as count FROM submissions s 
                  JOIN students st ON s.student_id = st.student_id 
                  WHERE st.user_id = ? AND s.grade IS NOT NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'] ?? 0;
    }
    
    private function hasCompletedCourses($user_id, $count) {
        // This would check against your course completion system
        return false; // Placeholder
    }
    
    private function getPerfectAssignments($user_id) {
        $query = "SELECT COUNT(*) as count FROM submissions s 
                  JOIN students st ON s.student_id = st.student_id 
                  WHERE st.user_id = ? AND s.grade = 100";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'] ?? 0;
    }
}
?>
