<?php
/**
 * CareerConnect AI - Database Connection & Global Core Helpers
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

// Suppress default mysqli exception in PHP 8.1+ to handle gracefully
mysqli_report(MYSQLI_REPORT_OFF);

// Attempt database connection
$conn = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    // If database doesn't exist yet, connect to server and create it
    $serverConn = @mysqli_connect($db_host, $db_user, $db_pass);
    if ($serverConn) {
        mysqli_query($serverConn, "CREATE DATABASE IF NOT EXISTS `{$db_name}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        mysqli_close($serverConn);
        $conn = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    }
}

if (!$conn) {
    die("<div style='font-family:sans-serif;padding:30px;background:#fff3f3;border:1px solid #ffcaca;margin:40px auto;max-width:600px;border-radius:10px;'>
        <h3 style='color:#d9534f;margin-top:0;'>Database Connection Error</h3>
        <p>Could not connect to MySQL server on <strong>" . htmlspecialchars($db_host) . "</strong>.</p>
        <p style='color:#666;'>Details: " . htmlspecialchars(mysqli_connect_error()) . "</p>
        <p>Please ensure MySQL is running in your XAMPP Control Panel and check database credentials.</p>
    </div>");
}

mysqli_set_charset($conn, "utf8mb4");

// Auto-initialize required database tables if missing
function initializeDatabaseTables($conn) {
    $tables = [
        "users" => "CREATE TABLE IF NOT EXISTS `users` (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

        "admins" => "CREATE TABLE IF NOT EXISTS `admins` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(150) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `role` VARCHAR(50) DEFAULT 'Super Admin',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

        "resume" => "CREATE TABLE IF NOT EXISTS `resume` (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

        "resume_analysis" => "CREATE TABLE IF NOT EXISTS `resume_analysis` (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

        "career_results" => "CREATE TABLE IF NOT EXISTS `career_results` (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

        "mock_interviews" => "CREATE TABLE IF NOT EXISTS `mock_interviews` (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

        "assessments" => "CREATE TABLE IF NOT EXISTS `assessments` (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

        "feedback" => "CREATE TABLE IF NOT EXISTS `feedback` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NULL,
            `name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(150) NOT NULL,
            `module_name` VARCHAR(100) NOT NULL DEFAULT 'General Portal',
            `rating` INT NOT NULL DEFAULT 5,
            `comments` TEXT NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            KEY `idx_feedback_user` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

        "contact_messages" => "CREATE TABLE IF NOT EXISTS `contact_messages` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(150) NOT NULL,
            `mobile` VARCHAR(30) NULL,
            `phone` VARCHAR(30) NULL,
            `subject` VARCHAR(200) NULL,
            `message` TEXT NOT NULL,
            `status` ENUM('unread', 'read', 'replied', 'Resolved', 'In Progress') DEFAULT 'unread',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

        "chat_messages" => "CREATE TABLE IF NOT EXISTS `chat_messages` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NULL,
            `session_id` VARCHAR(100) NULL,
            `message` TEXT NOT NULL,
            `reply` TEXT NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            KEY `idx_chat_user` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

        "user_activities" => "CREATE TABLE IF NOT EXISTS `user_activities` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `action` VARCHAR(100) NOT NULL,
            `module` VARCHAR(100) NOT NULL,
            `details` TEXT NULL,
            `ip_address` VARCHAR(45) NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            KEY `idx_activity_user` (`user_id`),
            CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    ];

    foreach ($tables as $query) {
        @mysqli_query($conn, $query);
    }

    // Auto-migration check: ensure columns exist in previously created tables
    $migrations = [
        "ALTER TABLE `mock_interviews` ADD COLUMN `overall_score` INT NOT NULL DEFAULT 75 AFTER `user_answer`",
        "ALTER TABLE `mock_interviews` ADD COLUMN `score` INT NOT NULL DEFAULT 75 AFTER `overall_score`",
        "ALTER TABLE `feedback` ADD COLUMN `name` VARCHAR(100) NOT NULL AFTER `user_id`",
        "ALTER TABLE `feedback` ADD COLUMN `comments` TEXT NOT NULL AFTER `rating`",
        "ALTER TABLE `contact_messages` ADD COLUMN `mobile` VARCHAR(30) NULL AFTER `email`"
    ];
    foreach ($migrations as $migSql) {
        @mysqli_query($conn, $migSql);
    }

    // Seed default admin if table is empty
    $chkAdmin = @mysqli_query($conn, "SELECT COUNT(*) as c FROM admins");
    if ($chkAdmin) {
        $r = mysqli_fetch_assoc($chkAdmin);
        if (($r['c'] ?? 0) == 0) {
            $hashed = password_hash('Admin@123', PASSWORD_DEFAULT);
            $seedStmt = mysqli_prepare($conn, "INSERT INTO admins (name, email, password, role) VALUES ('System Administrator', 'admin@careerconnect.ai', ?, 'Super Admin')");
            if ($seedStmt) {
                mysqli_stmt_bind_param($seedStmt, "s", $hashed);
                mysqli_stmt_execute($seedStmt);
                mysqli_stmt_close($seedStmt);
            }
        }
    }
}

initializeDatabaseTables($conn);

// ==========================================
// Core Authentication & Session Helpers
// ==========================================

if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
}

if (!function_exists('requireLogin')) {
    function requireLogin($redirect = null) {
        if (!isLoggedIn()) {
            if ($redirect === null) {
                $redirect = url('auth/login.php');
            }
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'] ?? url('dashboard/dashboard.php');
            header("Location: $redirect");
            exit();
        }
    }
}

if (!function_exists('isAdminLoggedIn')) {
    function isAdminLoggedIn() {
        return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
    }
}

if (!function_exists('requireAdmin')) {
    function requireAdmin($redirect = null) {
        if (!isAdminLoggedIn()) {
            if ($redirect === null) {
                $redirect = url('admin/admin_login.php');
            }
            header("Location: $redirect");
            exit();
        }
    }
}

if (!function_exists('getCurrentUser')) {
    function getCurrentUser($conn) {
        if (!isLoggedIn()) {
            return null;
        }
        $userId = (int)$_SESSION['user_id'];
        $stmt = mysqli_prepare($conn, "SELECT id, name, email, mobile, gender, course, address, profile_image, role, created_at FROM users WHERE id = ? LIMIT 1");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $userId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            return $user;
        }
        return null;
    }
}

if (!function_exists('sanitizeInput')) {
    function sanitizeInput($data) {
        if (is_array($data)) {
            return array_map('sanitizeInput', $data);
        }
        return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('logActivity')) {
    function logActivity($conn, $userId, $action, $module, $details = '') {
        if (!$conn || empty($userId)) return;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $stmt = mysqli_prepare($conn, "INSERT INTO user_activities (user_id, action, module, details, ip_address) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "issss", $userId, $action, $module, $details, $ip);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}
?>