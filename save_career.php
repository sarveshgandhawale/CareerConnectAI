<?php
require_once __DIR__ . '/config/db.php';
requireLogin();

$userId = (int)$_SESSION['user_id'];
$name = trim($_POST['student_name'] ?? $_SESSION['name'] ?? 'Student');
$career = trim($_POST['career'] ?? 'Software Developer');
$score = (int)($_POST['score'] ?? 85);
$skills = trim($_POST['skills'] ?? 'General Coding');
$missing = trim($_POST['missing_skills'] ?? 'Advanced System Design');
$roadmap = trim($_POST['roadmap'] ?? 'Learn fundamentals -> Build projects');
$aiAdvice = trim($_POST['ai_advice'] ?? 'Keep building and practicing.');

$stmt = mysqli_prepare($conn, "INSERT INTO career_results (user_id, student_name, career, score, skills, missing_skills, roadmap, ai_advice) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ississss", $userId, $name, $career, $score, $skills, $missing, $roadmap, $aiAdvice);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        logActivity($conn, $userId, 'Save Career', 'Career', "Saved career assessment for {$career}.");
        header("Location: " . url('view_career_history.php?success=' . urlencode("Career assessment saved successfully!")));
        exit();
    }
    mysqli_stmt_close($stmt);
}

header("Location: " . url('view_career_history.php?error=' . urlencode("Could not save assessment.")));
exit();
?>