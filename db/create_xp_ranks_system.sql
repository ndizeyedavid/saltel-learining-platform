-- Migration: Create XP Ranks System
-- This migration creates the ranks table and updates user_xp table to support rank progression

-- Create ranks table with predefined XP levels
CREATE TABLE IF NOT EXISTS xp_ranks (
    rank_id INT AUTO_INCREMENT PRIMARY KEY,
    rank_name VARCHAR(50) NOT NULL UNIQUE,
    rank_title VARCHAR(100) NOT NULL,
    min_xp INT NOT NULL,
    max_xp INT NOT NULL,
    rank_color VARCHAR(7) DEFAULT '#6B7280',
    rank_icon VARCHAR(50) DEFAULT 'fas fa-star',
    rank_order INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_xp_range (min_xp, max_xp),
    INDEX idx_rank_order (rank_order)
);

-- Insert predefined ranks with XP thresholds
INSERT INTO xp_ranks (rank_name, rank_title, min_xp, max_xp, rank_color, rank_icon, rank_order) VALUES
('novice', 'Novice Learner', 0, 99, '#9CA3AF', 'fas fa-seedling', 1),
('apprentice', 'Apprentice Scholar', 100, 249, '#10B981', 'fas fa-book-open', 2),
('student', 'Dedicated Student', 250, 499, '#3B82F6', 'fas fa-graduation-cap', 3),
('scholar', 'Advanced Scholar', 500, 999, '#8B5CF6', 'fas fa-user-graduate', 4),
('expert', 'Subject Expert', 1000, 1999, '#F59E0B', 'fas fa-medal', 5),
('master', 'Knowledge Master', 2000, 3999, '#EF4444', 'fas fa-crown', 6),
('grandmaster', 'Grand Master', 4000, 7999, '#DC2626', 'fas fa-trophy', 7),
('legend', 'Learning Legend', 8000, 15999, '#7C2D12', 'fas fa-fire', 8),
('mythic', 'Mythic Scholar', 16000, 31999, '#581C87', 'fas fa-gem', 9),
('immortal', 'Immortal Sage', 32000, 999999, '#1F2937', 'fas fa-infinity', 10);

-- Create rank progression log table
CREATE TABLE IF NOT EXISTS rank_progressions (
    progression_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    old_rank_id INT,
    new_rank_id INT NOT NULL,
    total_xp INT NOT NULL,
    progression_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (old_rank_id) REFERENCES xp_ranks(rank_id),
    FOREIGN KEY (new_rank_id) REFERENCES xp_ranks(rank_id),
    INDEX idx_user_progression (user_id, progression_date)
);

-- Note: This migration works with existing user_xp table structure:
-- Columns: xp_id, user_id, total_xp, current_level, xp_to_next_level, study_streak, last_activity_date, created_at, updated_at
-- The current_level column will be used to map to rank_order in xp_ranks table
