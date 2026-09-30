<?php
require_once __DIR__ . '/../config/db.php';
requireLogin();

$userId = (int)$_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $studentName = trim($_POST['student_name'] ?? $_SESSION['name'] ?? 'Student');
    $domain = trim($_POST['domain'] ?? 'Web Development');

    // Answer Key
    $correctAnswers = [
        'q1' => 'GET',
        'q2' => 'HAVING',
        'q3' => 'SQL Injection',
        'q4' => 'Encapsulation',
        'q5' => 'O(log N)',
        'q6' => '8 seconds',
        'q7' => 'Server Logs',
        'q8' => 'Situation Task Action Result',
        'q9' => 'Sync Progress',
        'q10' => 'Proactive Communication'
    ];

    $totalQuestions = count($correctAnswers);
    $correctCount = 0;

    $techCorrect = 0;
    $logicCorrect = 0;
    $softCorrect = 0;

    // Technical (Q1-Q4)
    for ($i = 1; $i <= 4; $i++) {
        $ans = trim($_POST["q{$i}"] ?? '');
        if ($ans === $correctAnswers["q{$i}"]) {
            $correctCount++;
            $techCorrect++;
        }
    }

    // Problem Solving / Logic (Q5-Q7)
    for ($i = 5; $i <= 7; $i++) {
        $ans = trim($_POST["q{$i}"] ?? '');
        if ($ans === $correctAnswers["q{$i}"]) {
            $correctCount++;
            $logicCorrect++;
        }
    }

    // Soft Skills & Communication (Q8-Q10)
    for ($i = 8; $i <= 10; $i++) {
        $ans = trim($_POST["q{$i}"] ?? '');
        if ($ans === $correctAnswers["q{$i}"]) {
            $correctCount++;
            $softCorrect++;
        }
    }

    $scorePercentage = (int)round(($correctCount / $totalQuestions) * 100);
    $technicalScore = (int)round(($techCorrect / 4) * 100);
    $logicalScore = (int)round(($logicCorrect / 3) * 100);
    $softskillsScore = (int)round(($softCorrect / 3) * 100);

    // AI Assessment Summary
    $summary = "Student scored {$scorePercentage}% overall in {$domain} diagnostic test. Technical capability: {$technicalScore}%, Logical reasoning: {$logicalScore}%, Agile & Communication: {$softskillsScore}%.";

    // Insert into assessments table
    $stmt = mysqli_prepare($conn, "INSERT INTO assessments (user_id, student_name, domain, total_questions, correct_answers, score_percentage, technical_score, logical_score, softskills_score, ai_summary) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            "issiiiiiss",
            $userId,
            $studentName,
            $domain,
            $totalQuestions,
            $correctCount,
            $scorePercentage,
            $technicalScore,
            $logicalScore,
            $softskillsScore,
            $summary
        );

        if (mysqli_stmt_execute($stmt)) {
            $assessmentId = mysqli_insert_id($conn);
            mysqli_stmt_close($stmt);

            // Log activity
            logActivity($conn, $userId, 'Skill Assessment', 'Assessment', "Completed {$domain} assessment with score {$scorePercentage}%.");

            header("Location: result.php?id=" . $assessmentId);
            exit();
        } else {
            echo "Database error: " . mysqli_error($conn);
            mysqli_stmt_close($stmt);
        }
    } else {
        echo "Database query error: " . mysqli_error($conn);
    }

} else {
    header("Location: assessment.php");
    exit();
}
?>
