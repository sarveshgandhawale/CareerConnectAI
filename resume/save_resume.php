<?php
require_once __DIR__ . '/../config/db.php';
requireLogin();

$userId = (int)$_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $objective = trim($_POST['objective'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $education = trim($_POST['education'] ?? '');
    $projects = trim($_POST['projects'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (empty($name) || empty($email) || empty($mobile)) {
        header("Location: resume.php?error=" . urlencode("Please fill in all required fields."));
        exit();
    }

    // Insert new resume record
    $stmt = mysqli_prepare($conn, "INSERT INTO resume (user_id, name, email, mobile, course, objective, skills, education, projects, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            "isssssssss",
            $userId,
            $name,
            $email,
            $mobile,
            $course,
            $objective,
            $skills,
            $education,
            $projects,
            $address
        );

        if (mysqli_stmt_execute($stmt)) {
            $resumeId = mysqli_insert_id($conn);
            mysqli_stmt_close($stmt);

            // Log activity
            logActivity($conn, $userId, 'Build Resume', 'Resume', "Created and saved resume for {$name} ({$course}).");

            header("Location: preview_resume.php?success=" . urlencode("Resume created and saved successfully!"));
            exit();
        } else {
            $err = mysqli_error($conn);
            mysqli_stmt_close($stmt);
            header("Location: resume.php?error=" . urlencode("Database error: " . $err));
            exit();
        }
    } else {
        header("Location: resume.php?error=" . urlencode("Database prepare error."));
        exit();
    }
} else {
    header("Location: resume.php");
    exit();
}
?>
