<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../ai/gemini.php';
requireLogin();

$userId = (int)$_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $interviewType = trim($_POST['interview_type'] ?? 'HR');
    $question = trim($_POST['question'] ?? 'Tell me about yourself.');
    $userAnswer = trim($_POST['answer'] ?? '');

    if (empty($userAnswer)) {
        header("Location: mock_interview.php?error=" . urlencode("Please provide an answer before submitting."));
        exit();
    }

    // Call Gemini AI Evaluator
    $evaluation = evaluateInterviewAnswer($interviewType, $question, $userAnswer);

    $overallScore = (int)$evaluation['overall_score'];
    $clarityScore = (int)($evaluation['clarity_score'] ?? $overallScore);
    $technicalScore = (int)($evaluation['technical_score'] ?? $overallScore);
    $communicationScore = (int)($evaluation['communication_score'] ?? $overallScore);
    $strengths = $evaluation['strengths'];
    $improvements = $evaluation['improvements'];
    $aiFeedback = $evaluation['ai_feedback'];
    $idealAnswer = $evaluation['ideal_answer'];

    // Insert into mock_interviews table
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO mock_interviews (user_id, interview_type, question, user_answer, overall_score, clarity_score, technical_score, communication_score, strengths, improvements, ai_feedback, ideal_answer) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            "isssiiiissss",
            $userId,
            $interviewType,
            $question,
            $userAnswer,
            $overallScore,
            $clarityScore,
            $technicalScore,
            $communicationScore,
            $strengths,
            $improvements,
            $aiFeedback,
            $idealAnswer
        );

        if (mysqli_stmt_execute($stmt)) {
            $interviewId = mysqli_insert_id($conn);
            mysqli_stmt_close($stmt);

            // Log activity
            logActivity($conn, $userId, 'Mock Interview', 'Interview', "Completed {$interviewType} mock interview question (Score: {$overallScore}%).");

            header("Location: interview_result.php?id=" . $interviewId);
            exit();
        } else {
            $err = mysqli_error($conn);
            mysqli_stmt_close($stmt);
            header("Location: mock_interview.php?error=" . urlencode("Database error: " . $err));
            exit();
        }
    } else {
        header("Location: mock_interview.php?error=" . urlencode("Database query error."));
        exit();
    }

} else {
    header("Location: mock_interview.php");
    exit();
}
?>
