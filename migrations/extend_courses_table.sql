-- Extend courses table with additional trainer-facing fields

ALTER TABLE `courses`
  ADD COLUMN IF NOT EXISTS `status` ENUM('Draft','Published','Archived') DEFAULT 'Draft',
  ADD COLUMN IF NOT EXISTS `visibility` ENUM('Public','Private','Password Protected') DEFAULT 'Public',
  ADD COLUMN IF NOT EXISTS `level` ENUM('Beginner','Intermediate','Advanced') DEFAULT 'Beginner',
  ADD COLUMN IF NOT EXISTS `start_date` DATE NULL,
  ADD COLUMN IF NOT EXISTS `end_date` DATE NULL,
  ADD COLUMN IF NOT EXISTS `max_students` INT(11) NULL;

