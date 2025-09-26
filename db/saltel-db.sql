-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 26, 2025 at 04:49 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `saltel`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `assignment_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`assignment_id`, `course_id`, `title`, `description`, `due_date`) VALUES
(2, 2, 'sadas', 'sadasdasd', '2025-09-30');

-- --------------------------------------------------------

--
-- Table structure for table `assignment_options`
--

CREATE TABLE `assignment_options` (
  `option_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_text` varchar(500) NOT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignment_options`
--

INSERT INTO `assignment_options` (`option_id`, `question_id`, `option_text`, `is_correct`, `sort_order`) VALUES
(1, 1, 'sadasd', 1, 0),
(2, 1, 'asdasdasdasd', 0, 1),
(3, 2, 'sadfdfs', 1, 0),
(4, 2, 'sdffsf', 0, 1),
(5, 2, 'sdafdas', 0, 2),
(6, 3, 'asdasd', 1, 0),
(7, 3, 'sadasdasa', 0, 1),
(8, 4, 'asdas', 1, 0),
(9, 4, 'asdasd', 0, 1),
(10, 6, 'Yes', 1, 0),
(11, 6, 'No', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `assignment_questions`
--

CREATE TABLE `assignment_questions` (
  `question_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `points` int(11) DEFAULT 1,
  `explanation` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignment_questions`
--

INSERT INTO `assignment_questions` (`question_id`, `assignment_id`, `question_text`, `points`, `explanation`, `sort_order`) VALUES
(1, 3, 'sadasdasdsadasdas', 1, 'asdasdasdasdsadsadasdas', 1),
(2, 3, 'sadfgffsdfdsdaasd', 10, '', 1),
(3, 2, 'sdsfddsdds', 1, 'sadasdas', 0),
(4, 4, 'asdasdas', 1, '', 1),
(5, 3, 'mellow chicken', 10, '', 5),
(6, 2, 'Please work', 1, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `badge_id` int(11) NOT NULL,
  `badge_name` varchar(100) NOT NULL,
  `badge_description` text DEFAULT NULL,
  `badge_icon` varchar(100) DEFAULT NULL,
  `badge_color` varchar(50) DEFAULT 'blue',
  `unlock_condition` varchar(255) DEFAULT NULL,
  `xp_requirement` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `badges`
--

INSERT INTO `badges` (`badge_id`, `badge_name`, `badge_description`, `badge_icon`, `badge_color`, `unlock_condition`, `xp_requirement`, `is_active`, `created_at`) VALUES
(1, 'Welcome Rookie', 'Complete your first lesson', 'fas fa-graduation-cap', 'green', 'complete_first_lesson', 25, 1, '2025-09-24 14:37:13'),
(2, 'Study Warrior', 'Study for 5 hours total', 'fas fa-sword', 'blue', 'study_5_hours', 300, 1, '2025-09-24 14:37:13'),
(3, 'Assignment Master', 'Complete 10 assignments', 'fas fa-trophy', 'gold', 'complete_10_assignments', 500, 1, '2025-09-24 14:37:13'),
(4, 'Streak Champion', 'Maintain 7-day study streak', 'fas fa-fire', 'red', 'study_streak_7', 150, 1, '2025-09-24 14:37:13'),
(5, 'Knowledge Seeker', 'Earn 1000 XP', 'fas fa-star', 'purple', 'earn_1000_xp', 1000, 1, '2025-09-24 14:37:13'),
(6, 'Course Conqueror', 'Complete your first course', 'fas fa-crown', 'orange', 'complete_first_course', 200, 1, '2025-09-24 14:37:13'),
(7, 'Perfect Student', 'Get 100% on 5 assignments', 'fas fa-medal', 'platinum', 'perfect_5_assignments', 375, 1, '2025-09-24 14:37:13'),
(8, 'Learning Legend', 'Reach Level 10', 'fas fa-dragon', 'legendary', 'reach_level_10', 5000, 1, '2025-09-24 14:37:13');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `certificate_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `certificate_url` varchar(255) DEFAULT NULL,
  `certificate_code` varchar(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`certificate_id`, `student_id`, `course_id`, `issued_at`, `certificate_url`, `certificate_code`) VALUES
(1, 1, 3, '2025-09-26 00:37:10', NULL, 'CERT-FFCC0922'),
(2, 1, 2, '2025-09-26 01:27:04', NULL, 'CERT-7A30BF65');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `category` varchar(150) NOT NULL,
  `course_title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Draft','Published','Archived') DEFAULT 'Draft',
  `visibility` enum('Public','Private','Password Protected') DEFAULT 'Public',
  `level` enum('Beginner','Intermediate','Advanced') DEFAULT 'Beginner',
  `image_url` longtext NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `max_students` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `teacher_id`, `category`, `course_title`, `description`, `price`, `created_at`, `status`, `visibility`, `level`, `image_url`, `start_date`, `end_date`, `max_students`) VALUES
(2, 18, 'Data Science', 'Non molestiae provident suscipit quis enim natus ut enim officia rerum qui sequi ex', 'Explicabo Quisquam ut laboris dolorum deserunt officia', 0.00, '2025-09-24 22:40:00', 'Published', 'Public', 'Intermediate', 'uploads/courses/course_1758753600_68d473409cf6a.jpg', '1994-02-11', '1997-01-03', 15),
(3, 18, 'Design', 'Minim exercitationem dolor reprehenderit enim vero quae amet laboriosam praesentium fugiat fugiat et aliquip et perspiciatis', 'Numquam consequatur Maiores iure molestiae quod dolorem qui odio autem tenetur ipsam dolor recusandae In sint reiciendis', 0.00, '2025-09-25 11:00:00', 'Published', 'Public', 'Beginner', 'uploads/courses/course_1758798000_68d520b0c1e36.jpg', '2001-07-09', '2014-03-12', 41);

-- --------------------------------------------------------

--
-- Table structure for table `course_content`
--

CREATE TABLE `course_content` (
  `content_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `content_type` enum('PDF','Video','Text') NOT NULL,
  `content_url` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_lessons`
--

CREATE TABLE `course_lessons` (
  `lesson_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `module_id` int(11) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `lesson_type` enum('video','text','quiz','document') NOT NULL DEFAULT 'text',
  `content` longtext DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_lessons`
--

INSERT INTO `course_lessons` (`lesson_id`, `course_id`, `module_id`, `title`, `lesson_type`, `content`, `sort_order`, `created_at`) VALUES
(1, 2, 1, 'Nostrud veritatis dolor excepturi quaerat nihil sapiente aut qui eaque minima est sint necessitatibus culpa', 'text', '<h1>juijoijiojjiojo</h1><pre class=\"ql-syntax\" spellcheck=\"false\">Obcaecati et commodo nihil alias eum molestiae placeat, nisi cum vitae exercitation odio maxime voluptas nisi eum dolorem non et labore fugiat, veritatis rerum est, fugiat optio.\n</pre>', 1, '2025-09-24 23:20:41'),
(2, 2, 3, 'Animi occaecat cum et similique quidem mollit error labore tenetur maiores dolore est tempore voluptatum quis', 'text', '<p>Consequatur? Adipisci dolores culpa, dignissimos tenetur incidunt, quo ad earum dignissimos nisi dolor quis nisi qui quas ut deleniti repellendus. Voluptatem et non illum, id consectetur, irure fugiat, explicabo. Nisi consequatur, in dolorem minim id consectetur, modi Nam error labore consequatur exercitationem soluta eius enim ea dolore animi, minus illum, doloremque soluta aliqua. Laborum do error ea dolore molestiae aliquip consequatur qui autem non ex sint, et dolorem.</p>', 1, '2025-09-24 23:21:23'),
(3, 2, 4, 'Est fuga Cupidatat quidem voluptas fugiat qui veritatis quae voluptas ad qui dolores id qui quia et consectetur aut', 'text', '<p>Unde consequatur? Dolorem optio, veniam, nesciunt, asperiores perspiciatis, quia non consequat. Voluptas sed non non labore enim beatae eum dolor quasi hic ad ullamco consequatur, et dolor sunt, et ipsam repudiandae ab eos, nisi qui quasi aliquid proident, ex laboriosam, saepe lorem quisquam occaecat consectetur, mollit totam et eveniet, et culpa omnis temporibus qui consequatur, laborum reiciendis totam a laudantium, eaque ea eu.</p>', 1, '2025-09-24 23:21:48'),
(4, 2, 5, 'Adipisci architecto consequatur ad cupiditate ex consectetur et quisquam illo molestias harum aliqua Ea modi voluptas illo aut illo eum', 'text', '<p>Aute amet, quo blanditiis qui sed sunt a voluptate itaque ut reiciendis quo reprehenderit, sit non voluptatem tempor est et ad voluptatem quidem est elit, dolores aliquam qui nihil porro quo in atque necessitatibus beatae totam id iste sint, omnis laborum.</p>', 1, '2025-09-24 23:26:26'),
(5, 3, 6, 'Odio enim est fugiat sequi dolore fugiat tempor velit quam illum ut ut dicta est duis harum qui totam', 'text', '<p>Eos quia tenetur animi, optio, voluptas aut deleniti sed aute aliqua. Eius a sed aute dolore voluptates eligendi culpa dignissimos sit et in rerum nobis eaque ut lorem fugiat.</p>', 1, '2025-09-25 11:00:27');

-- --------------------------------------------------------

--
-- Table structure for table `course_modules`
--

CREATE TABLE `course_modules` (
  `module_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `duration_minutes` int(11) DEFAULT NULL,
  `points` int(11) DEFAULT 0,
  `is_published` tinyint(1) DEFAULT 0,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_modules`
--

INSERT INTO `course_modules` (`module_id`, `course_id`, `duration_minutes`, `points`, `is_published`, `title`, `description`, `sort_order`, `created_at`, `updated_at`) VALUES
(3, 2, NULL, 0, 0, 'sdsaasdas', 'ssadasdasd', 3, '2025-09-24 23:06:32', '2025-09-24 23:06:32'),
(4, 2, NULL, 0, 0, 'mellowasd', 'sakdjaldjaklsdjaksl', 4, '2025-09-24 23:06:40', '2025-09-24 23:06:40'),
(5, 2, NULL, 0, 0, 'sampling again', 'this is also lit', 5, '2025-09-24 23:26:08', '2025-09-24 23:26:08'),
(6, 3, NULL, 0, 0, 'asdasd', 'asdasd', 1, '2025-09-25 11:00:21', '2025-09-25 11:00:21');

-- --------------------------------------------------------

--
-- Table structure for table `course_prerequisites`
--

CREATE TABLE `course_prerequisites` (
  `prerequisite_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `prerequisite_type` enum('module_completion','lesson_completion','quiz_score','assignment_submission') NOT NULL,
  `prerequisite_value` varchar(255) DEFAULT NULL,
  `required_score` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_resources`
--

CREATE TABLE `course_resources` (
  `resource_id` int(11) NOT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `resource_type` enum('video','document','image','audio','link') NOT NULL,
  `resource_name` varchar(255) NOT NULL,
  `resource_url` varchar(500) NOT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `is_downloadable` tinyint(1) DEFAULT 1,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_resources`
--

INSERT INTO `course_resources` (`resource_id`, `lesson_id`, `module_id`, `resource_type`, `resource_name`, `resource_url`, `file_size`, `mime_type`, `is_downloadable`, `uploaded_at`) VALUES
(1, 4, 5, 'document', 'david (1).pdf', 'uploads/course_resources/68d47e2ef0afb_1758756398.pdf', 98033, '0', 1, '2025-09-24 23:26:38'),
(2, 4, 5, 'video', 'VID_20250314_152331_895.mp4', 'uploads/course_resources/68d47e3a38dec_1758756410.mp4', 620487, '0', 1, '2025-09-24 23:26:50');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `enrollment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `payment_status` enum('Pending','Paid') DEFAULT 'Pending',
  `progress_percentage` int(11) NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`enrollment_id`, `student_id`, `course_id`, `payment_status`, `progress_percentage`, `enrolled_at`) VALUES
(3, 1, 3, 'Paid', 100, '2025-09-25 20:59:21'),
(4, 1, 2, 'Paid', 100, '2025-09-25 23:30:14');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_completions`
--

CREATE TABLE `lesson_completions` (
  `completion_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tracks lesson completion status and progress for users';

--
-- Dumping data for table `lesson_completions`
--

INSERT INTO `lesson_completions` (`completion_id`, `user_id`, `lesson_id`, `completed_at`, `created_at`, `updated_at`) VALUES
(7, 16, 2, '2025-09-26 00:31:40', '2025-09-26 00:31:40', '2025-09-26 00:31:40'),
(8, 16, 3, '2025-09-26 00:31:52', '2025-09-26 00:31:52', '2025-09-26 00:31:52'),
(15, 16, 5, '2025-09-26 00:37:10', '2025-09-26 00:37:10', '2025-09-26 00:37:10'),
(16, 16, 4, '2025-09-26 01:27:04', '2025-09-26 01:27:04', '2025-09-26 01:27:04');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_progress`
--

CREATE TABLE `lesson_progress` (
  `progress_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `status` enum('not_started','in_progress','completed') DEFAULT 'not_started',
  `video_progress` int(11) DEFAULT 0,
  `last_position` int(11) DEFAULT 0,
  `completion_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `module_prerequisites`
--

CREATE TABLE `module_prerequisites` (
  `prerequisite_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `required_module_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_answer_options`
--

CREATE TABLE `quiz_answer_options` (
  `option_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_text` text NOT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  `option_order` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_answer_options`
--

INSERT INTO `quiz_answer_options` (`option_id`, `question_id`, `option_text`, `is_correct`, `option_order`, `created_at`) VALUES
(1, 10, 'sadsdssssads', 0, 1, '2025-09-26 01:02:05'),
(2, 10, 'poiuhgvc', 1, 2, '2025-09-26 01:02:24');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `question_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('multiple_choice','true_false','short_answer','essay') DEFAULT 'multiple_choice',
  `points` int(11) DEFAULT 1,
  `question_order` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_questions`
--

INSERT INTO `quiz_questions` (`question_id`, `lesson_id`, `question_text`, `question_type`, `points`, `question_order`, `created_at`) VALUES
(10, 4, 'Dolores anim cillum sequi Nam officia pariatur Aut est aliquam sit dolore tempor vitae voluptates sit ab', 'multiple_choice', 1, 1, '2025-09-25 00:13:30'),
(11, 4, 'sampleing', 'true_false', 40, 2, '2025-09-25 00:13:56'),
(12, 1, 'Eveniet cupidatat nulla magna obcaecati cupiditate deserunt eveniet id et esse velit commodi nobis', 'multiple_choice', 1, 1, '2025-09-25 07:17:29');

-- --------------------------------------------------------

--
-- Table structure for table `resource_progress`
--

CREATE TABLE `resource_progress` (
  `progress_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `status` enum('not_started','in_progress','completed') DEFAULT 'not_started',
  `download_count` int(11) DEFAULT 0,
  `last_accessed` timestamp NULL DEFAULT NULL,
  `completion_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `institution` varchar(150) DEFAULT NULL,
  `level_year` varchar(50) DEFAULT NULL,
  `program` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `user_id`, `institution`, `level_year`, `program`) VALUES
(1, 16, 'ESSA Nyararugunga', 'L4', 'Software Development');

-- --------------------------------------------------------

--
-- Table structure for table `student_todos`
--

CREATE TABLE `student_todos` (
  `todo_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `completed` tinyint(1) DEFAULT 0,
  `priority` enum('low','medium','high') DEFAULT 'medium',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `study_sessions`
--

CREATE TABLE `study_sessions` (
  `session_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `session_duration` int(11) DEFAULT NULL,
  `xp_earned` int(11) DEFAULT 0,
  `session_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `submission_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `file_url` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `grade` decimal(5,2) DEFAULT NULL,
  `feedback` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_profiles`
--

CREATE TABLE `teacher_profiles` (
  `user_id` int(11) NOT NULL,
  `expertise` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_profiles`
--

INSERT INTO `teacher_profiles` (`user_id`, `expertise`, `bio`, `linkedin_url`, `website_url`, `created_at`, `updated_at`) VALUES
(18, 'Mathematics and engineering', 'I like chickens', '', 'https://ndizeye.netlify.app', '2025-09-25 10:10:47', '2025-09-25 10:10:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `role` enum('SuperAdmin','Teacher','Student') NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `otp` varchar(6) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `remember_token` varchar(64) DEFAULT NULL,
  `remember_expires` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `profile_image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `middle_name`, `last_name`, `gender`, `email`, `phone`, `role`, `password`, `created_at`, `otp`, `otp_expires_at`, `is_verified`, `remember_token`, `remember_expires`, `last_login`, `profile_image_url`) VALUES
(16, 'Dylan', 'Cheryl Rice', 'Nash', 'Female', 'esting228@gmail.com', '(147) 489-5700', 'Student', '$2y$10$1L5FdGGEABwYVsO7Fe9XCu9/iRK6ZUky/Q9gvAAiwyX7wQ4ifXrtS', '2025-09-24 13:02:17', NULL, NULL, 1, 'a447c8bc4042956f3330e0ed9441acf1baecd5147b22ba445f1aa1223b131022', '2025-10-25 20:33:53', '2025-09-25 22:50:36', NULL),
(18, 'Peter', 'Candace Pugh', 'DeVan', 'Female', 'davidndizeye101@gmail.com', '(151) 156-8553', 'Teacher', '$2y$10$ZvYQDYsidQjmRLLmKCcbquCzdHWiL2I19e8DBk.xdsdADzGAZi142', '2025-09-24 15:57:08', NULL, NULL, 1, NULL, NULL, '2025-09-26 02:12:04', 'uploads/profile/user_18_1758796871.jpg'),
(19, 'Sample', NULL, 'User', 'Male', 'utesting228@gmail.com', '(079) 614-0857', 'Student', '$2y$10$u41zzx9hijODsGajATJI5.llCw05haj3uKk1zRPcoFfIwqBRow0s6', '2025-09-24 15:59:24', NULL, NULL, 1, '6e4dab91d9c73efc3b3c412fa57f1a544f0a4a09033a23a7788f787b9dd6381b', '2025-10-24 16:00:00', '2025-09-24 16:00:00', NULL),
(20, 'Isabella', NULL, 'Foster', 'Male', 'koseje9211@auslank.com', '(190) 468-7256', 'Teacher', '$2y$10$tAnVe.MEhQgF8Q1LrrFXjOlHJV3xNoFYWQek105R/6nwDy6ULJh56', '2025-09-25 14:28:11', '305289', '2025-09-25 14:38:11', 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_badges`
--

CREATE TABLE `user_badges` (
  `user_badge_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `badge_id` int(11) NOT NULL,
  `earned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_badges`
--

INSERT INTO `user_badges` (`user_badge_id`, `user_id`, `badge_id`, `earned_at`) VALUES
(2, 16, 1, '2025-09-25 22:10:21');

-- --------------------------------------------------------

--
-- Table structure for table `user_xp`
--

CREATE TABLE `user_xp` (
  `xp_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_xp` int(11) DEFAULT 0,
  `current_level` int(11) DEFAULT 1,
  `xp_to_next_level` int(11) DEFAULT 100,
  `study_streak` int(11) DEFAULT 0,
  `last_activity_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_xp`
--

INSERT INTO `user_xp` (`xp_id`, `user_id`, `total_xp`, `current_level`, `xp_to_next_level`, `study_streak`, `last_activity_date`, `created_at`, `updated_at`) VALUES
(1, 16, 930, 1, 20, 3, '2025-09-26', '2025-09-24 14:56:50', '2025-09-26 01:29:20'),
(211, 19, 10, 1, 40, 1, '2025-09-24', '2025-09-24 16:00:00', '2025-09-24 16:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `xp_activities`
--

CREATE TABLE `xp_activities` (
  `activity_id` int(11) NOT NULL,
  `activity_name` varchar(100) NOT NULL,
  `activity_description` text DEFAULT NULL,
  `xp_reward` int(11) NOT NULL,
  `max_daily_earnings` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `xp_activities`
--

INSERT INTO `xp_activities` (`activity_id`, `activity_name`, `activity_description`, `xp_reward`, `max_daily_earnings`, `is_active`, `created_at`) VALUES
(1, 'login', 'Daily login bonus', 10, 10, 1, '2025-09-24 14:37:13'),
(2, 'complete_lesson', 'Complete a lesson', 25, NULL, 1, '2025-09-24 14:37:13'),
(3, 'complete_assignment', 'Submit an assignment', 50, NULL, 1, '2025-09-24 14:37:13'),
(4, 'study_session_15min', 'Study for 15 minutes', 15, 120, 1, '2025-09-24 14:37:13'),
(5, 'first_login', 'First time login bonus', 100, 100, 1, '2025-09-24 14:37:13'),
(6, 'course_completion', 'Complete entire course', 200, NULL, 1, '2025-09-24 14:37:13'),
(7, 'perfect_assignment', 'Get 100% on assignment', 75, NULL, 1, '2025-09-24 14:37:13'),
(8, 'study_streak_7', '7-day study streak bonus', 150, NULL, 1, '2025-09-24 14:37:13'),
(9, 'study_streak_30', '30-day study streak bonus', 500, NULL, 1, '2025-09-24 14:37:13');

-- --------------------------------------------------------

--
-- Table structure for table `xp_rules`
--

CREATE TABLE `xp_rules` (
  `rule_id` int(11) NOT NULL,
  `action_type` enum('lesson_completion','module_completion','course_completion','quiz_completion','resource_completion','daily_login','streak_bonus') NOT NULL,
  `xp_amount` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `xp_rules`
--

INSERT INTO `xp_rules` (`rule_id`, `action_type`, `xp_amount`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'lesson_completion', 50, 'Complete a lesson', 1, '2025-09-25 22:39:41', '2025-09-25 22:39:41'),
(2, 'module_completion', 200, 'Complete a module', 1, '2025-09-25 22:39:41', '2025-09-25 22:39:41'),
(3, 'course_completion', 1000, 'Complete a course', 1, '2025-09-25 22:39:41', '2025-09-25 22:39:41'),
(4, 'quiz_completion', 100, 'Complete a quiz', 1, '2025-09-25 22:39:41', '2025-09-25 22:39:41'),
(5, 'resource_completion', 25, 'Complete a resource', 1, '2025-09-25 22:39:41', '2025-09-25 22:39:41'),
(6, 'daily_login', 10, 'Daily login bonus', 1, '2025-09-25 22:39:41', '2025-09-25 22:39:41'),
(7, 'streak_bonus', 50, 'Learning streak bonus', 1, '2025-09-25 22:39:41', '2025-09-25 22:39:41');

-- --------------------------------------------------------

--
-- Table structure for table `xp_transactions`
--

CREATE TABLE `xp_transactions` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `xp_earned` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `earned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `xp_transactions`
--

INSERT INTO `xp_transactions` (`transaction_id`, `user_id`, `activity_id`, `xp_earned`, `description`, `earned_at`) VALUES
(1, 16, 1, 10, 'Daily login bonus', '2025-09-24 14:56:50'),
(3, 19, 1, 10, 'Daily login bonus', '2025-09-24 16:00:00'),
(4, 16, 1, 10, 'Daily login bonus', '2025-09-25 15:52:24'),
(5, 16, 1, 10, 'Daily login bonus', '2025-09-25 22:10:21'),
(10, 16, 2, 25, 'Lesson completion', '2025-09-26 00:31:40'),
(11, 16, 2, 25, 'Lesson completion', '2025-09-26 00:31:52'),
(24, 16, 2, 25, 'Lesson completion', '2025-09-26 00:37:10'),
(25, 16, 6, 200, 'Course completion', '2025-09-26 00:37:10'),
(26, 16, 6, 200, 'Course completion', '2025-09-26 00:56:39'),
(27, 16, 2, 25, 'Lesson completion', '2025-09-26 01:27:04'),
(28, 16, 6, 200, 'Course completion', '2025-09-26 01:27:04'),
(29, 16, 6, 200, 'Course completion', '2025-09-26 01:29:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `assignment_options`
--
ALTER TABLE `assignment_options`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `assignment_questions`
--
ALTER TABLE `assignment_questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `assignment_id` (`assignment_id`);

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`badge_id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`certificate_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `course_content`
--
ALTER TABLE `course_content`
  ADD PRIMARY KEY (`content_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `course_lessons`
--
ALTER TABLE `course_lessons`
  ADD PRIMARY KEY (`lesson_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `course_modules`
--
ALTER TABLE `course_modules`
  ADD PRIMARY KEY (`module_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `course_prerequisites`
--
ALTER TABLE `course_prerequisites`
  ADD PRIMARY KEY (`prerequisite_id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Indexes for table `course_resources`
--
ALTER TABLE `course_resources`
  ADD PRIMARY KEY (`resource_id`),
  ADD KEY `lesson_id` (`lesson_id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`enrollment_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `lesson_completions`
--
ALTER TABLE `lesson_completions`
  ADD PRIMARY KEY (`completion_id`),
  ADD UNIQUE KEY `unique_user_lesson` (`user_id`,`lesson_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_lesson_id` (`lesson_id`),
  ADD KEY `idx_completed_at` (`completed_at`),
  ADD KEY `idx_lesson_completions_user_progress` (`user_id`,`completed_at`);

--
-- Indexes for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  ADD PRIMARY KEY (`progress_id`),
  ADD UNIQUE KEY `unique_lesson_student` (`student_id`,`lesson_id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Indexes for table `module_prerequisites`
--
ALTER TABLE `module_prerequisites`
  ADD PRIMARY KEY (`prerequisite_id`),
  ADD UNIQUE KEY `unique_prerequisite` (`module_id`,`required_module_id`),
  ADD KEY `required_module_id` (`required_module_id`);

--
-- Indexes for table `quiz_answer_options`
--
ALTER TABLE `quiz_answer_options`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `option_order` (`option_order`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `lesson_id` (`lesson_id`),
  ADD KEY `question_order` (`question_order`);

--
-- Indexes for table `resource_progress`
--
ALTER TABLE `resource_progress`
  ADD PRIMARY KEY (`progress_id`),
  ADD UNIQUE KEY `unique_student_resource` (`student_id`,`resource_id`),
  ADD KEY `resource_id` (`resource_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `student_todos`
--
ALTER TABLE `student_todos`
  ADD PRIMARY KEY (`todo_id`),
  ADD KEY `idx_student_id` (`student_id`),
  ADD KEY `idx_due_date` (`due_date`),
  ADD KEY `idx_completed` (`completed`);

--
-- Indexes for table `study_sessions`
--
ALTER TABLE `study_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `idx_study_sessions_user_date` (`user_id`,`session_date`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`submission_id`),
  ADD KEY `assignment_id` (`assignment_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `teacher_profiles`
--
ALTER TABLE `teacher_profiles`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `idx_users_otp` (`otp`),
  ADD KEY `idx_users_email_otp` (`email`,`otp`);

--
-- Indexes for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD PRIMARY KEY (`user_badge_id`),
  ADD UNIQUE KEY `unique_user_badge` (`user_id`,`badge_id`),
  ADD KEY `badge_id` (`badge_id`),
  ADD KEY `idx_user_badges_user_id` (`user_id`);

--
-- Indexes for table `user_xp`
--
ALTER TABLE `user_xp`
  ADD PRIMARY KEY (`xp_id`),
  ADD UNIQUE KEY `unique_user_xp` (`user_id`),
  ADD KEY `idx_user_xp_user_id` (`user_id`);

--
-- Indexes for table `xp_activities`
--
ALTER TABLE `xp_activities`
  ADD PRIMARY KEY (`activity_id`);

--
-- Indexes for table `xp_rules`
--
ALTER TABLE `xp_rules`
  ADD PRIMARY KEY (`rule_id`);

--
-- Indexes for table `xp_transactions`
--
ALTER TABLE `xp_transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `activity_id` (`activity_id`),
  ADD KEY `idx_xp_transactions_user_id` (`user_id`),
  ADD KEY `idx_xp_transactions_date` (`earned_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `assignment_options`
--
ALTER TABLE `assignment_options`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `assignment_questions`
--
ALTER TABLE `assignment_questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `badge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `certificate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `course_content`
--
ALTER TABLE `course_content`
  MODIFY `content_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course_lessons`
--
ALTER TABLE `course_lessons`
  MODIFY `lesson_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `course_modules`
--
ALTER TABLE `course_modules`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `course_prerequisites`
--
ALTER TABLE `course_prerequisites`
  MODIFY `prerequisite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `course_resources`
--
ALTER TABLE `course_resources`
  MODIFY `resource_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `enrollment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lesson_completions`
--
ALTER TABLE `lesson_completions`
  MODIFY `completion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  MODIFY `progress_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `module_prerequisites`
--
ALTER TABLE `module_prerequisites`
  MODIFY `prerequisite_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_answer_options`
--
ALTER TABLE `quiz_answer_options`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `resource_progress`
--
ALTER TABLE `resource_progress`
  MODIFY `progress_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student_todos`
--
ALTER TABLE `student_todos`
  MODIFY `todo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `study_sessions`
--
ALTER TABLE `study_sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `submission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user_badges`
--
ALTER TABLE `user_badges`
  MODIFY `user_badge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_xp`
--
ALTER TABLE `user_xp`
  MODIFY `xp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=585;

--
-- AUTO_INCREMENT for table `xp_activities`
--
ALTER TABLE `xp_activities`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `xp_rules`
--
ALTER TABLE `xp_rules`
  MODIFY `rule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `xp_transactions`
--
ALTER TABLE `xp_transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `certificates_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `course_content`
--
ALTER TABLE `course_content`
  ADD CONSTRAINT `course_content_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `course_prerequisites`
--
ALTER TABLE `course_prerequisites`
  ADD CONSTRAINT `course_prerequisites_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `course_lessons` (`lesson_id`) ON DELETE CASCADE;

--
-- Constraints for table `course_resources`
--
ALTER TABLE `course_resources`
  ADD CONSTRAINT `course_resources_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `course_lessons` (`lesson_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_resources_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `course_modules` (`module_id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `lesson_completions`
--
ALTER TABLE `lesson_completions`
  ADD CONSTRAINT `lesson_completions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lesson_completions_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `course_lessons` (`lesson_id`) ON DELETE CASCADE;

--
-- Constraints for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  ADD CONSTRAINT `fk_lesson_progress_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `course_lessons` (`lesson_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lesson_progress_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `module_prerequisites`
--
ALTER TABLE `module_prerequisites`
  ADD CONSTRAINT `module_prerequisites_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `course_modules` (`module_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `module_prerequisites_ibfk_2` FOREIGN KEY (`required_module_id`) REFERENCES `course_modules` (`module_id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_answer_options`
--
ALTER TABLE `quiz_answer_options`
  ADD CONSTRAINT `quiz_answer_options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `course_lessons` (`lesson_id`) ON DELETE CASCADE;

--
-- Constraints for table `resource_progress`
--
ALTER TABLE `resource_progress`
  ADD CONSTRAINT `resource_progress_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `resource_progress_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `course_resources` (`resource_id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `student_todos`
--
ALTER TABLE `student_todos`
  ADD CONSTRAINT `student_todos_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `study_sessions`
--
ALTER TABLE `study_sessions`
  ADD CONSTRAINT `study_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `study_sessions_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`);

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`assignment_id`),
  ADD CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`);

--
-- Constraints for table `teacher_profiles`
--
ALTER TABLE `teacher_profiles`
  ADD CONSTRAINT `fk_teacher_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD CONSTRAINT `user_badges_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_badges_ibfk_2` FOREIGN KEY (`badge_id`) REFERENCES `badges` (`badge_id`);

--
-- Constraints for table `user_xp`
--
ALTER TABLE `user_xp`
  ADD CONSTRAINT `user_xp_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `xp_transactions`
--
ALTER TABLE `xp_transactions`
  ADD CONSTRAINT `xp_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `xp_transactions_ibfk_2` FOREIGN KEY (`activity_id`) REFERENCES `xp_activities` (`activity_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
