<?php
require_once __DIR__ . '/../config/db.php';
requireLogin();

$userId = (int)$_SESSION['user_id'];
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    // Fetch latest assessment for this user
    $q = mysqli_query($conn, "SELECT id FROM assessments WHERE user_id = $userId ORDER BY id DESC LIMIT 1");
    if ($q && mysqli_num_rows($q) > 0) {
        $id = (int)mysqli_fetch_assoc($q)['id'];
    } else {
        header("Location: assessment.php");
        exit();
    }
}

$stmt = mysqli_prepare($conn, "SELECT * FROM assessments WHERE id = ? AND user_id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "ii", $id, $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $assessment = mysqli_fetch_assoc($result);
} else {
    header("Location: assessment.php");
    exit();
}
mysqli_stmt_close($stmt);

$score = (int)$assessment['score_percentage'];
$tech = (int)$assessment['technical_score'];
$logic = (int)$assessment['logical_score'];
$soft = (int)$assessment['softskills_score'];
$domain = htmlspecialchars($assessment['domain']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assessment Results | CareerConnect AI</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS -->
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
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold mb-2">
                    <i class="fa-solid fa-square-check me-1"></i> Assessment Completed
                </span>
                <h1 class="fw-bold text-dark mb-2">
                    Skill Diagnostic Scorecard: <span class="text-primary"><?php echo $domain; ?></span>
                </h1>
                <p class="text-muted lead mb-3">
                    Completed by <strong><?php echo htmlspecialchars($assessment['student_name']); ?></strong> on <?php echo date('F d, Y - h:i A', strtotime($assessment['created_at'])); ?>
                </p>
                <div>
                    <?php if ($score >= 80): ?>
                        <span class="badge bg-success px-3 py-2 fs-6"><i class="fa-solid fa-award me-1"></i> Advanced Proficiency - Ready for High-Impact Roles</span>
                    <?php elseif ($score >= 60): ?>
                        <span class="badge bg-primary px-3 py-2 fs-6"><i class="fa-solid fa-check me-1"></i> Good Competence - Strong Foundation with Growth Opportunities</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark px-3 py-2 fs-6"><i class="fa-solid fa-book-open me-1"></i> Foundational Level - Recommended for Skill Upskilling</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-4 text-center mt-4 mt-lg-0">
                <div class="p-4 rounded-4 border bg-light shadow-sm d-inline-block w-100">
                    <span class="text-muted fw-semibold small text-uppercase">Diagnostic Score</span>
                    <h1 class="display-3 fw-bold text-primary my-2"><?php echo $score; ?>%</h1>
                    <div class="progress mb-2" style="height: 10px;">
                        <div class="progress-bar bg-primary" style="width: <?php echo $score; ?>%"></div>
                    </div>
                    <small class="text-muted"><?php echo (int)$assessment['correct_answers']; ?> of <?php echo (int)$assessment['total_questions']; ?> Correct Answers</small>
                </div>
            </div>
        </div>
    </div>

    <!-- 3-PILLAR BREAKDOWN CARDS -->
    <div class="row g-4 mb-4">
        <!-- TECHNICAL -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white h-100 text-center">
                <div class="p-3 bg-primary-subtle text-primary rounded-circle d-inline-block mx-auto mb-3" style="width: 60px; height: 60px;">
                    <i class="fa-solid fa-code fs-3"></i>
                </div>
                <h5 class="fw-bold mb-1">Technical Core</h5>
                <h2 class="display-6 fw-bold text-primary my-2"><?php echo $tech; ?>%</h2>
                <div class="progress mb-3" style="height: 8px;">
                    <div class="progress-bar bg-primary" style="width: <?php echo $tech; ?>%"></div>
                </div>
                <p class="text-muted small mb-0">HTTP methods, SQL indexes, OOP architecture, and web security fundamentals.</p>
            </div>
        </div>

        <!-- PROBLEM SOLVING -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white h-100 text-center">
                <div class="p-3 bg-success-subtle text-success rounded-circle d-inline-block mx-auto mb-3" style="width: 60px; height: 60px;">
                    <i class="fa-solid fa-brain fs-3"></i>
                </div>
                <h5 class="fw-bold mb-1">Problem Solving &amp; Logic</h5>
                <h2 class="display-6 fw-bold text-success my-2"><?php echo $logic; ?>%</h2>
                <div class="progress mb-3" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: <?php echo $logic; ?>%"></div>
                </div>
                <p class="text-muted small mb-0">Time complexities (Big-O), search algorithms, and server debugging workflows.</p>
            </div>
        </div>

        <!-- SOFT SKILLS -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white h-100 text-center">
                <div class="p-3 bg-warning-subtle text-warning rounded-circle d-inline-block mx-auto mb-3" style="width: 60px; height: 60px;">
                    <i class="fa-solid fa-people-group fs-3"></i>
                </div>
                <h5 class="fw-bold mb-1">Agile &amp; Communication</h5>
                <h2 class="display-6 fw-bold text-warning my-2"><?php echo $soft; ?>%</h2>
                <div class="progress mb-3" style="height: 8px;">
                    <div class="progress-bar bg-warning" style="width: <?php echo $soft; ?>%"></div>
                </div>
                <p class="text-muted small mb-0">STAR interview methodology, sprint standups, and cross-functional team collaboration.</p>
            </div>
        </div>
    </div>

    <!-- AI RECOMMENDATION NEXT STEP BANNER -->
    <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 text-white mb-4" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2">
                    <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Recommended Next Step
                </span>
                <h2 class="fw-bold mb-2">Ready to generate your AI Career Roadmap?</h2>
                <p class="lead mb-0 text-white-50">
                    Use your assessment results to get customized career match titles, missing skills analysis, and a 6-month milestones roadmap generated by Gemini AI.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="<?php echo url('career/career_guidance.php'); ?>" class="btn btn-warning btn-lg px-4 py-3 fw-bold text-dark shadow">
                    Generate AI Career Guidance <i class="fa-solid fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="assessment.php" class="btn btn-outline-primary px-4 py-2 fw-semibold">
            <i class="fa-solid fa-rotate-right me-1"></i> Retake Assessment
        </a>
        <a href="<?php echo url('dashboard/dashboard.php'); ?>" class="btn btn-outline-secondary px-4 py-2 fw-semibold">
            <i class="fa-solid fa-house me-1"></i> Return to Dashboard
        </a>
    </div>

</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
