-- Create comprehensive course content structure
-- This migration adds support for course modules, lessons, and content resources

-- Course Modules Table
CREATE TABLE IF NOT EXISTS `course_modules` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `module_title` varchar(255) NOT NULL,
  `module_description` text,
  `module_order` int(11) NOT NULL DEFAULT 1,
  `duration_minutes` int(11) DEFAULT NULL,
  `points` int(11) DEFAULT 0,
  `is_published` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`module_id`),
  KEY `course_id` (`course_id`),
  KEY `module_order` (`module_order`),
  CONSTRAINT `course_modules_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Course Lessons Table
CREATE TABLE IF NOT EXISTS `course_lessons` (
  `lesson_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `lesson_title` varchar(255) NOT NULL,
  `lesson_content` longtext,
  `lesson_order` int(11) NOT NULL DEFAULT 1,
  `duration_minutes` int(11) DEFAULT NULL,
  `points` int(11) DEFAULT 0,
  `is_published` tinyint(1) DEFAULT 0,
  `lesson_type` enum('text','video','quiz','assignment') DEFAULT 'text',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`lesson_id`),
  KEY `module_id` (`module_id`),
  KEY `lesson_order` (`lesson_order`),
  CONSTRAINT `course_lessons_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `course_modules` (`module_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Course Resources Table (replaces the basic course_content table)
CREATE TABLE IF NOT EXISTS `course_resources` (
  `resource_id` int(11) NOT NULL AUTO_INCREMENT,
  `lesson_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `resource_type` enum('video','document','image','audio','link') NOT NULL,
  `resource_name` varchar(255) NOT NULL,
  `resource_url` varchar(500) NOT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `is_downloadable` tinyint(1) DEFAULT 1,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`resource_id`),
  KEY `lesson_id` (`lesson_id`),
  KEY `module_id` (`module_id`),
  CONSTRAINT `course_resources_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `course_lessons` (`lesson_id`) ON DELETE CASCADE,
  CONSTRAINT `course_resources_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `course_modules` (`module_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Course Prerequisites Table
CREATE TABLE IF NOT EXISTS `course_prerequisites` (
  `prerequisite_id` int(11) NOT NULL AUTO_INCREMENT,
  `lesson_id` int(11) NOT NULL,
  `prerequisite_type` enum('module_completion','lesson_completion','quiz_score','assignment_submission') NOT NULL,
  `prerequisite_value` varchar(255) DEFAULT NULL,
  `required_score` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`prerequisite_id`),
  KEY `lesson_id` (`lesson_id`),
  CONSTRAINT `course_prerequisites_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `course_lessons` (`lesson_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Quiz Questions Table (for knowledge checks)
CREATE TABLE IF NOT EXISTS `quiz_questions` (
  `question_id` int(11) NOT NULL AUTO_INCREMENT,
  `lesson_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('multiple_choice','true_false','short_answer','essay') DEFAULT 'multiple_choice',
  `points` int(11) DEFAULT 1,
  `question_order` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`question_id`),
  KEY `lesson_id` (`lesson_id`),
  KEY `question_order` (`question_order`),
  CONSTRAINT `quiz_questions_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `course_lessons` (`lesson_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Quiz Answer Options Table
CREATE TABLE IF NOT EXISTS `quiz_answer_options` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `option_text` text NOT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  `option_order` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`option_id`),
  KEY `question_id` (`question_id`),
  KEY `option_order` (`option_order`),
  CONSTRAINT `quiz_answer_options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`question_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
