-- Rollback Migration: Drop lesson_completions table
-- Use this script to rollback the lesson_completions table creation

-- Drop foreign key constraints first
ALTER TABLE `lesson_completions` 
  DROP FOREIGN KEY `lesson_completions_ibfk_1`,
  DROP FOREIGN KEY `lesson_completions_ibfk_2`,
  DROP FOREIGN KEY `lesson_completions_ibfk_3`;

-- Drop indexes
DROP INDEX `idx_lesson_completions_user_progress` ON `lesson_completions`;
DROP INDEX `idx_lesson_completions_course_stats` ON `lesson_completions`;

-- Drop the table
DROP TABLE IF EXISTS `lesson_completions`;

-- Confirmation message
SELECT 'lesson_completions table has been dropped successfully' as rollback_status;
