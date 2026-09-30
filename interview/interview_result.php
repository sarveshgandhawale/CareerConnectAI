<?php
require_once __DIR__ . '/../config/db.php';
requireLogin();

$userId = (int)$_SESSION['user_id'];
$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM mock_interviews WHERE id = ? AND user_id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "ii", $id, $userId);
} else {
    $stmt = mysqli_prepare($conn, "SELECT * FROM mock_interviews WHERE user_id = ? ORDER BY id DESC LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $userId);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $interview = mysqli_fetch_assoc($result);
} else {
    mysqli_stmt_close($stmt);
    header("Location: mock_interview.php");
    exit();
}
mysqli_stmt_close($stmt);

$overall = (int)$interview['overall_score'];
$clarity = (int)$interview['clarity_score'];
$tech = (int)$interview['technical_score'];
$comm = (int)$interview['communication_score'];
$type = htmlspecialchars($interview['interview_type']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AI Interview Performance Scorecard | CareerConnect AI</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/interview_result.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/nav.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/footer.css'); ?>">
</head>
<body class="bg-light">

<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-5">

    <!-- HERO SCORECARD -->
    <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5 bg-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2">
                    <i class="fa-solid fa-sparkles me-1"></i> Gemini AI Hiring Evaluation
                </span>
                <h1 class="fw-bold display-6 text-dark mb-2">
                    <?php echo $type; ?> Interview Scorecard
                </h1>
                <p class="lead text-muted mb-3">
                    Completed on <?php echo date('F d, Y - h:i A', strtotime($interview['created_at'])); ?> by CareerConnect AI Evaluator.
                </p>

                <div>
                    <?php if ($overall >= 80): ?>
                        <span class="badge bg-success px-3 py-2 fs-6"><i class="fa-solid fa-award me-1"></i> Strong Candidate Answer - Ready for High-Stakes Tech Rounds</span>
                    <?php elseif ($overall >= 60): ?>
                        <span class="badge bg-primary px-3 py-2 fs-6"><i class="fa-solid fa-thumbs-up me-1"></i> Good Response - Incorporate STAR Framework for Maximum Impact</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark px-3 py-2 fs-6"><i class="fa-solid fa-book-open me-1"></i> Developing - Review Ideal Model Answer Below</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-4 text-center mt-4 mt-lg-0">
                <div class="p-4 rounded-4 border bg-light shadow-sm d-inline-block w-100">
                    <span class="text-muted fw-semibold small text-uppercase">Overall Performance</span>
                    <h1 class="display-3 fw-bold <?php echo $overall >= 75 ? 'text-success' : ($overall >= 60 ? 'text-primary' : 'text-warning'); ?> my-2">
                        <?php echo $overall; ?>%
                    </h1>
                    <div class="progress mb-2" style="height: 10px;">
                        <div class="progress-bar <?php echo $overall >= 75 ? 'bg-success' : ($overall >= 60 ? 'bg-primary' : 'bg-warning'); ?> progress-bar-striped progress-bar-animated" style="width: <?php echo $overall; ?>%"></div>
                    </div>
                    <small class="text-muted">Multi-factor evaluation</small>
                </div>
            </div>
        </div>
    </div>

    <!-- 3-DIMENSION SCORE METRICS -->
    <div class="row g-4 mb-4">
        <!-- CLARITY -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white h-100 text-center">
                <div class="p-3 bg-primary-subtle text-primary rounded-circle d-inline-block mx-auto mb-2" style="width: 55px; height: 55px;">
                    <i class="fa-solid fa-bullseye fs-4"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Clarity &amp; Structure</h6>
                <h3 class="fw-bold text-primary my-2"><?php echo $clarity; ?>%</h3>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-primary" style="width: <?php echo $clarity; ?>%"></div>
                </div>
            </div>
        </div>

        <!-- TECHNICAL DEPTH -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white h-100 text-center">
                <div class="p-3 bg-success-subtle text-success rounded-circle d-inline-block mx-auto mb-2" style="width: 55px; height: 55px;">
                    <i class="fa-solid fa-code fs-4"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Technical Depth &amp; Accuracy</h6>
                <h3 class="fw-bold text-success my-2"><?php echo $tech; ?>%</h3>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-success" style="width: <?php echo $tech; ?>%"></div>
                </div>
            </div>
        </div>

        <!-- COMMUNICATION -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white h-100 text-center">
                <div class="p-3 bg-warning-subtle text-warning rounded-circle d-inline-block mx-auto mb-2" style="width: 55px; height: 55px;">
                    <i class="fa-solid fa-comments fs-4"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Communication Tone</h6>
                <h3 class="fw-bold text-warning my-2"><?php echo $comm; ?>%</h3>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-warning" style="width: <?php echo $comm; ?>%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- QUESTION & CANDIDATE ANSWER -->
    <div class="card shadow-sm border-0 rounded-4 p-4 bg-white mb-4">
        <h5 class="fw-bold text-dark mb-3">
            <i class="fa-solid fa-circle-question text-primary me-2"></i> Interview Question &amp; Your Response
        </h5>
        <div class="p-3 bg-light rounded-3 mb-3 border-start border-4 border-primary">
            <strong class="text-secondary small d-block mb-1">Question:</strong>
            <p class="fw-bold text-dark mb-0 fs-6"><?php echo htmlspecialchars($interview['question']); ?></p>
        </div>
        <div class="p-3 bg-light rounded-3 border-start border-4 border-secondary">
            <strong class="text-secondary small d-block mb-1">Your Submitted Answer:</strong>
            <p class="text-dark mb-0" style="line-height: 1.8;"><?php echo nl2br(htmlspecialchars($interview['user_answer'])); ?></p>
        </div>
    </div>

    <!-- STRENGTHS & IMPROVEMENTS -->
    <div class="row g-4 mb-4">
        <!-- STRENGTHS -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="p-3 bg-success-subtle text-success rounded-3">
                        <i class="fa-solid fa-circle-check fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">What You Did Well</h5>
                        <small class="text-muted">Key strengths observed</small>
                    </div>
                </div>
                <div class="text-secondary" style="line-height: 1.8;">
                    <?php echo nl2br(htmlspecialchars($interview['strengths'])); ?>
                </div>
            </div>
        </div>

        <!-- IMPROVEMENTS -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="p-3 bg-warning-subtle text-warning rounded-3">
                        <i class="fa-solid fa-triangle-exclamation fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Actionable Improvements</h5>
                        <small class="text-muted">Advice for next time</small>
                    </div>
                </div>
                <div class="text-secondary" style="line-height: 1.8;">
                    <?php echo nl2br(htmlspecialchars($interview['improvements'])); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- IDEAL MODEL ANSWER -->
    <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 bg-white mb-4 border-start border-5 border-success">
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="p-3 bg-success text-white rounded-3">
                <i class="fa-solid fa-star fa-2x"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0 text-success">Recommended Model Answer</h4>
                <small class="text-muted">How high-scoring candidates articulate this response</small>
            </div>
        </div>
        <div class="p-4 bg-light rounded-4 text-dark" style="line-height: 1.9; font-size: 16px;">
            <?php echo nl2br(htmlspecialchars($interview['ideal_answer'])); ?>
        </div>
    </div>

    <!-- RECRUITER AI SUMMARY -->
    <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 bg-white mb-4">
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="p-3 bg-primary text-white rounded-3">
                <i class="fa-solid fa-robot fa-2x"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Gemini Hiring Manager Coaching Notes</h4>
                <small class="text-muted">Detailed technical rationale</small>
            </div>
        </div>
        <div class="p-4 bg-light rounded-4 text-dark" style="line-height: 1.9; font-size: 16px;">
            <?php echo nl2br(htmlspecialchars($interview['ai_feedback'])); ?>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="mock_interview.php" class="btn btn-primary px-4 py-2 fw-semibold">
            <i class="fa-solid fa-microphone-lines me-1"></i> Practice Another Question
        </a>
        <a href="interview_history.php" class="btn btn-outline-primary px-4 py-2 fw-semibold">
            <i class="fa-solid fa-clock-rotate-left me-1"></i> Interview Scorecards History
        </a>
        <a href="<?php echo url('dashboard/dashboard.php'); ?>" class="btn btn-outline-secondary px-4 py-2 fw-semibold">
            <i class="fa-solid fa-house me-1"></i> Dashboard
        </a>
    </div>

</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
