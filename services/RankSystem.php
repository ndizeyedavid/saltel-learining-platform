<?php
class RankSystem {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
        $this->initializeTables();
    }
    
    private function initializeTables() {
        // Run the migration to create tables if they don't exist
        $migrationFile = __DIR__ . '/../db/create_xp_ranks_system.sql';
        if (file_exists($migrationFile)) {
            $sql = file_get_contents($migrationFile);
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    try {
                        $this->conn->query($statement);
                    } catch (Exception $e) {
                        // Continue if table already exists or other non-critical errors
                        error_log("RankSystem migration warning: " . $e->getMessage());
                    }
                }
            }
        }
    }
    
    public function checkAndUpdateUserRank($userId) {
        try {
            // Get user's current XP and level from existing table structure
            $stmt = $this->conn->prepare("
                SELECT total_xp, current_level, xp_to_next_level
                FROM user_xp
                WHERE user_id = ?
            ");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $userXP = $result->fetch_assoc();
            
            if (!$userXP) {
                // Initialize user XP if not exists
                $this->initializeUserXP($userId);
                return $this->checkAndUpdateUserRank($userId);
            }
            
            $currentXP = $userXP['total_xp'];
            $currentLevel = $userXP['current_level'];
            
            // Find the appropriate rank for current XP
            $stmt = $this->conn->prepare("
                SELECT rank_id, rank_name, rank_title, min_xp, max_xp, rank_color, rank_icon, rank_order
                FROM xp_ranks 
                WHERE ? >= min_xp AND ? <= max_xp
                ORDER BY rank_order ASC
                LIMIT 1
            ");
            $stmt->bind_param("ii", $currentXP, $currentXP);
            $stmt->execute();
            $result = $stmt->get_result();
            $correctRank = $result->fetch_assoc();
            
            if (!$correctRank) {
                // If XP exceeds highest rank, get the highest rank
                $stmt = $this->conn->prepare("
                    SELECT rank_id, rank_name, rank_title, min_xp, max_xp, rank_color, rank_icon, rank_order
                    FROM xp_ranks 
                    ORDER BY rank_order DESC
                    LIMIT 1
                ");
                $stmt->execute();
                $result = $stmt->get_result();
                $correctRank = $result->fetch_assoc();
            }
            
            $newRankOrder = $correctRank['rank_order'];
            $rankChanged = false;
            
            // Check if rank needs to be updated (compare with current_level)
            if ($currentLevel != $newRankOrder) {
                $rankChanged = true;
                
                // Get old rank for logging
                $oldRankId = null;
                if ($currentLevel > 0) {
                    $stmt = $this->conn->prepare("SELECT rank_id FROM xp_ranks WHERE rank_order = ?");
                    $stmt->bind_param("i", $currentLevel);
                    $stmt->execute();
                    $oldResult = $stmt->get_result();
                    $oldRank = $oldResult->fetch_assoc();
                    $oldRankId = $oldRank ? $oldRank['rank_id'] : null;
                }
                
                // Log rank progression
                $stmt = $this->conn->prepare("
                    INSERT INTO rank_progressions (user_id, old_rank_id, new_rank_id, total_xp)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->bind_param("iiii", $userId, $oldRankId, $correctRank['rank_id'], $currentXP);
                $stmt->execute();
            }
            
            // Calculate XP to next rank
            $nextRank = $this->getNextRank($correctRank['rank_order']);
            $xpToNext = 0;
            
            if ($nextRank) {
                $xpToNext = max(0, $nextRank['min_xp'] - $currentXP);
            }
            
            // Update user_xp table with existing column structure
            $stmt = $this->conn->prepare("
                UPDATE user_xp 
                SET current_level = ?, 
                    xp_to_next_level = ?,
                    updated_at = NOW()
                WHERE user_id = ?
            ");
            $stmt->bind_param("iii", $newRankOrder, $xpToNext, $userId);
            $stmt->execute();
            
            return [
                'rank_changed' => $rankChanged,
                'current_rank' => $correctRank,
                'next_rank' => $nextRank,
                'xp_to_next' => $xpToNext,
                'total_xp' => $currentXP
            ];
            
        } catch (Exception $e) {
            error_log("RankSystem error: " . $e->getMessage());
            return null;
        }
    }
    
    private function initializeUserXP($userId) {
        $stmt = $this->conn->prepare("
            INSERT INTO user_xp (user_id, total_xp, current_level, xp_to_next_level, study_streak, last_activity_date, created_at, updated_at)
            VALUES (?, 0, 1, 100, 0, NOW(), NOW(), NOW())
            ON DUPLICATE KEY UPDATE
            current_level = COALESCE(current_level, 1),
            xp_to_next_level = COALESCE(xp_to_next_level, 100),
            updated_at = NOW()
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }
    
    private function getNextRank($currentRankOrder) {
        $stmt = $this->conn->prepare("
            SELECT rank_id, rank_name, rank_title, min_xp, max_xp, rank_color, rank_icon, rank_order
            FROM xp_ranks 
            WHERE rank_order = ?
            LIMIT 1
        ");
        $nextOrder = $currentRankOrder + 1;
        $stmt->bind_param("i", $nextOrder);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function getUserRankInfo($userId) {
        $stmt = $this->conn->prepare("
            SELECT ux.total_xp, ux.current_level, ux.xp_to_next_level,
                   r.rank_name, r.rank_title, r.min_xp, r.max_xp, r.rank_color, r.rank_icon, r.rank_order
            FROM user_xp ux
            LEFT JOIN xp_ranks r ON ux.current_level = r.rank_order
            WHERE ux.user_id = ?
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function getAllRanks() {
        $stmt = $this->conn->prepare("
            SELECT rank_id, rank_name, rank_title, min_xp, max_xp, rank_color, rank_icon, rank_order
            FROM xp_ranks 
            ORDER BY rank_order ASC
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getRecentRankProgressions($userId, $limit = 5) {
        $stmt = $this->conn->prepare("
            SELECT rp.*, 
                   old_r.rank_name as old_rank_name, old_r.rank_title as old_rank_title,
                   new_r.rank_name as new_rank_name, new_r.rank_title as new_rank_title, 
                   new_r.rank_color as new_rank_color, new_r.rank_icon as new_rank_icon
            FROM rank_progressions rp
            LEFT JOIN xp_ranks old_r ON rp.old_rank_id = old_r.rank_id
            LEFT JOIN xp_ranks new_r ON rp.new_rank_id = new_r.rank_id
            WHERE rp.user_id = ?
            ORDER BY rp.progression_date DESC
            LIMIT ?
        ");
        $stmt->bind_param("ii", $userId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
