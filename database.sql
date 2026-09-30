-- ==========================================================
-- Database Schema for CareerConnect AI Portal
-- Database: careerconnect
-- Compatible with MySQL 5.7+ / MySQL 8.0+ / MariaDB on XAMPP
-- ==========================================================

CREATE DATABASE IF NOT EXISTS `careerconnect` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `careerconnect`;

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `mobile` VARCHAR(20) NOT NULL,
    `gender` ENUM('Male', 'Female', 'Other') DEFAULT 'Male',
    `course` VARCHAR(100) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `address` TEXT NULL,
    `profile_image` VARCHAR(255) DEFAULT 'default.png',
    `role` ENUM('student', 'admin') DEFAULT 'student',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: admins
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(50) DEFAULT 'Super Admin',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin if not exists (Password: Admin@123)
INSERT INTO `admins` (`id`, `name`, `email`, `password`, `role`)
VALUES (1, 'System Administrator', 'admin@careerconnect.ai', '$2y$10$Q1F3zSj0qYQ.1oO5k5W2g.9w8mH7xI2J3K4L5M6N7O8P9Q0R1S2T3', 'Super Admin')
ON DUPLICATE KEY UPDATE `email`=`email`;

-- --------------------------------------------------------
-- Table: resume
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `resume` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `mobile` VARCHAR(20) NOT NULL,
    `course` VARCHAR(100) NULL,
    `objective` TEXT NULL,
    `skills` TEXT NULL,
    `education` TEXT NULL,
    `projects` TEXT NULL,
    `address` TEXT NULL,
    `file_path` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `idx_resume_user` (`user_id`),
    CONSTRAINT `fk_resume_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: resume_analysis
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `resume_analysis` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `resume_id` INT NULL,
    `target_role` VARCHAR(100) NOT NULL,
    `ats_score` INT NOT NULL DEFAULT 70,
    `strengths` TEXT NULL,
    `improvements` TEXT NULL,
    `missing_keywords` TEXT NULL,
    `ai_feedback` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY `idx_analysis_user` (`user_id`),
    CONSTRAINT `fk_analysis_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: career_results
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `career_results` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `student_name` VARCHAR(100) NOT NULL,
    `course` VARCHAR(100) NULL,
    `programming_skill` VARCHAR(50) NULL,
    `interest_field` VARCHAR(100) NULL,
    `communication_skill` VARCHAR(50) NULL,
    `problem_solving` VARCHAR(50) NULL,
    `career` VARCHAR(100) NOT NULL,
    `score` INT NOT NULL DEFAULT 80,
    `skills` TEXT NULL,
    `missing_skills` TEXT NULL,
    `roadmap` TEXT NULL,
    `ai_advice` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY `idx_career_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: mock_interviews
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mock_interviews` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `interview_type` VARCHAR(50) NOT NULL,
    `question` TEXT NOT NULL,
    `user_answer` TEXT NOT NULL,
    `overall_score` INT NOT NULL DEFAULT 75,
    `score` INT NOT NULL DEFAULT 75,
    `clarity_score` INT NOT NULL DEFAULT 75,
    `technical_score` INT NOT NULL DEFAULT 75,
    `communication_score` INT NOT NULL DEFAULT 75,
    `ai_feedback` TEXT NULL,
    `ideal_answer` TEXT NULL,
    `strengths` TEXT NULL,
    `improvements` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY `idx_interview_user` (`user_id`),
    CONSTRAINT `fk_interview_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: assessments
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `assessments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `student_name` VARCHAR(100) NOT NULL,
    `domain` VARCHAR(100) NOT NULL,
    `total_questions` INT NOT NULL DEFAULT 10,
    `correct_answers` INT NOT NULL DEFAULT 0,
    `score_percentage` INT NOT NULL DEFAULT 0,
    `technical_score` INT NOT NULL DEFAULT 0,
    `logical_score` INT NOT NULL DEFAULT 0,
    `softskills_score` INT NOT NULL DEFAULT 0,
    `ai_summary` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY `idx_assessment_user` (`user_id`),
    CONSTRAINT `fk_assessment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: feedback
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `feedback` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `module_name` VARCHAR(100) NOT NULL DEFAULT 'General Portal',
    `rating` INT NOT NULL DEFAULT 5,
    `comments` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY `idx_feedback_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: contact_messages
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `mobile` VARCHAR(30) NULL,
    `phone` VARCHAR(30) NULL,
    `subject` VARCHAR(200) NULL,
    `message` TEXT NOT NULL,
    `status` ENUM('unread', 'read', 'replied', 'Resolved', 'In Progress') DEFAULT 'unread',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: chat_messages
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `chat_messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `session_id` VARCHAR(100) NULL,
    `message` TEXT NOT NULL,
    `reply` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY `idx_chat_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: user_activities
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_activities` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `action` VARCHAR(100) NOT NULL,
    `module` VARCHAR(100) NOT NULL,
    `details` TEXT NULL,
    `ip_address` VARCHAR(45) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY `idx_activity_user` (`user_id`),
    CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
