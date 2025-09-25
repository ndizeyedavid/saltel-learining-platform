-- Create gamification system tables

-- User XP and Level tracking
CREATE TABLE user_xp (
    xp_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_xp INT DEFAULT 0,
    current_level INT DEFAULT 1,
    xp_to_next_level INT DEFAULT 100,
    study_streak INT DEFAULT 0,
    last_activity_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_xp (user_id)
);

-- XP Activities and earning rules
CREATE TABLE xp_activities (
    activity_id INT PRIMARY KEY AUTO_INCREMENT,
    activity_name VARCHAR(100) NOT NULL,
    activity_description TEXT,
    xp_reward INT NOT NULL,
    max_daily_earnings INT DEFAULT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- XP Transaction log
CREATE TABLE xp_transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    activity_id INT NOT NULL,
    xp_earned INT NOT NULL,
    description VARCHAR(255),
    earned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (activity_id) REFERENCES xp_activities(activity_id)
);

-- Badges/Skills system
CREATE TABLE badges (
    badge_id INT PRIMARY KEY AUTO_INCREMENT,
    badge_name VARCHAR(100) NOT NULL,
    badge_description TEXT,
    badge_icon VARCHAR(100),
    badge_color VARCHAR(50) DEFAULT 'blue',
    unlock_condition VARCHAR(255), -- JSON or text describing unlock condition
    xp_requirement INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- User earned badges
CREATE TABLE user_badges (
    user_badge_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    badge_id INT NOT NULL,
    earned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (badge_id) REFERENCES badges(badge_id),
    UNIQUE KEY unique_user_badge (user_id, badge_id)
);

-- Study sessions tracking
CREATE TABLE study_sessions (
    session_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    course_id INT,
    session_duration INT, -- in minutes
    xp_earned INT DEFAULT 0,
    session_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(course_id)
);

-- Insert default XP activities
INSERT INTO xp_activities (activity_name, activity_description, xp_reward, max_daily_earnings) VALUES
('login', 'Daily login bonus', 10, 10),
('complete_lesson', 'Complete a lesson', 25, NULL),
('complete_assignment', 'Submit an assignment', 50, NULL),
('study_session_15min', 'Study for 15 minutes', 15, 120),
('first_login', 'First time login bonus', 100, 100),
('course_completion', 'Complete entire course', 200, NULL),
('perfect_assignment', 'Get 100% on assignment', 75, NULL),
('study_streak_7', '7-day study streak bonus', 150, NULL),
('study_streak_30', '30-day study streak bonus', 500, NULL);

-- Insert default badges
INSERT INTO badges (badge_name, badge_description, badge_icon, badge_color, unlock_condition, xp_requirement) VALUES
('Welcome Rookie', 'Complete your first lesson', 'fas fa-graduation-cap', 'green', 'complete_first_lesson', 25),
('Study Warrior', 'Study for 5 hours total', 'fas fa-sword', 'blue', 'study_5_hours', 300),
('Assignment Master', 'Complete 10 assignments', 'fas fa-trophy', 'gold', 'complete_10_assignments', 500),
('Streak Champion', 'Maintain 7-day study streak', 'fas fa-fire', 'red', 'study_streak_7', 150),
('Knowledge Seeker', 'Earn 1000 XP', 'fas fa-star', 'purple', 'earn_1000_xp', 1000),
('Course Conqueror', 'Complete your first course', 'fas fa-crown', 'orange', 'complete_first_course', 200),
('Perfect Student', 'Get 100% on 5 assignments', 'fas fa-medal', 'platinum', 'perfect_5_assignments', 375),
('Learning Legend', 'Reach Level 10', 'fas fa-dragon', 'legendary', 'reach_level_10', 5000);

-- Create indexes for better performance
CREATE INDEX idx_user_xp_user_id ON user_xp(user_id);
CREATE INDEX idx_xp_transactions_user_id ON xp_transactions(user_id);
CREATE INDEX idx_xp_transactions_date ON xp_transactions(earned_at);
CREATE INDEX idx_user_badges_user_id ON user_badges(user_id);
CREATE INDEX idx_study_sessions_user_date ON study_sessions(user_id, session_date);
