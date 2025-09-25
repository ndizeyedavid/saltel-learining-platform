-- Update course_modules table to match API expectations
-- This migration aligns the database schema with the course content management API

-- Add missing columns to course_modules table
ALTER TABLE `course_modules` 
ADD COLUMN `module_title` varchar(255) NOT NULL DEFAULT '' AFTER `course_id`,
ADD COLUMN `module_description` text DEFAULT NULL AFTER `module_title`,
ADD COLUMN `module_order` int(11) NOT NULL DEFAULT 1 AFTER `module_description`,
ADD COLUMN `duration_minutes` int(11) DEFAULT NULL AFTER `module_order`,
ADD COLUMN `points` int(11) DEFAULT 0 AFTER `duration_minutes`,
ADD COLUMN `is_published` tinyint(1) DEFAULT 0 AFTER `points`,
ADD COLUMN `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() AFTER `created_at`;

-- Copy data from old columns to new columns
UPDATE `course_modules` SET 
    `module_title` = `title`,
    `module_description` = `description`,
    `module_order` = `sort_order`;

-- Add indexes for better performance
ALTER TABLE `course_modules`
ADD KEY `module_order` (`module_order`);

-- Drop old columns (uncomment these lines after verifying data migration)
-- ALTER TABLE `course_modules` DROP COLUMN `title`;
-- ALTER TABLE `course_modules` DROP COLUMN `description`;
-- ALTER TABLE `course_modules` DROP COLUMN `sort_order`;
