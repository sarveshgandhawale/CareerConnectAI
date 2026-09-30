<?php
require_once __DIR__ . '/../config/db.php';
requireLogin();

$userId = (int)$_SESSION['user_id'];
$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM resume_analysis WHERE id = ? AND user_id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "ii", $id, $userId);
} else {
    $stmt = mysqli_prepare($conn, "SELECT * FROM resume_analysis WHERE user_id = ? ORDER BY id DESC LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $userId);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $analysis = mysqli_fetch_assoc($result);
} else {
    mysqli_stmt_close($stmt);
    header("Location: resume_analysis.php");
    exit();
}
mysqli_stmt_close($stmt);

$score = (int)$analysis['ats_score'];
$role = htmlspecialchars($analysis['target_role']);

// Extract keywords as an array
$keywords = array_filter(array_map('trim', explode(',', $analysis['missing_keywords'] ?? '')));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ATS Resume Analysis Results | CareerConnect AI</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/resume_result.css'); ?>">
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
                    <i class="fa-solid fa-sparkles me-1"></i> ATS Compatibility Diagnostic
                </span>
                <h1 class="fw-bold display-6 text-dark mb-2">
                    Target Role: <span class="text-primary"><?php echo $role; ?></span>
                </h1>
                <p class="lead text-muted mb-3">
                    Scanned on <?php echo date('F d, Y - h:i A', strtotime($analysis['created_at'])); ?> by CareerConnect AI ATS Engine.
                </p>

                <div>
                    <?php if ($score >= 80): ?>
                        <span class="badge bg-success px-3 py-2 fs-6"><i class="fa-solid fa-circle-check me-1"></i> Excellent ATS Compatibility - High Interview Probability</span>
                    <?php elseif ($score >= 60): ?>
                        <span class="badge bg-primary px-3 py-2 fs-6"><i class="fa-solid fa-thumbs-up me-1"></i> Good Foundation - Add Recommended Missing Keywords to Boost Score</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark px-3 py-2 fs-6"><i class="fa-solid fa-triangle-exclamation me-1"></i> Needs Optimization - Revise Formatting and Keywords</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-4 text-center mt-4 mt-lg-0">
                <div class="p-4 rounded-4 border bg-light shadow-sm d-inline-block w-100">
                    <span class="text-muted fw-semibold small text-uppercase">ATS Compatibility Score</span>
                    <h1 class="display-3 fw-bold <?php echo $score >= 75 ? 'text-success' : ($score >= 60 ? 'text-primary' : 'text-warning'); ?> my-2">
                        <?php echo $score; ?>%
                    </h1>
                    <div class="progress mb-2" style="height: 10px;">
                        <div class="progress-bar <?php echo $score >= 75 ? 'bg-success' : ($score >= 60 ? 'bg-primary' : 'bg-warning'); ?> progress-bar-striped progress-bar-animated" style="width: <?php echo $score; ?>%"></div>
                    </div>
                    <small class="text-muted">Algorithm parsed 25+ ATS parameters</small>
                </div>
            </div>
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
                        <h4 class="fw-bold mb-0">ATS Strengths</h4>
                        <small class="text-muted">High-performing sections</small>
                    </div>
                </div>
                <div class="text-secondary" style="line-height: 1.8;">
                    <?php echo nl2br(htmlspecialchars($analysis['strengths'])); ?>
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
                        <h4 class="fw-bold mb-0">Actionable Improvements</h4>
                        <small class="text-muted">Changes to increase callbacks</small>
                    </div>
                </div>
                <div class="text-secondary" style="line-height: 1.8;">
                    <?php echo nl2br(htmlspecialchars($analysis['improvements'])); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- MISSING KEYWORDS TAG CLOUD -->
    <div class="card shadow-sm border-0 rounded-4 p-4 bg-white mb-4">
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="p-3 bg-primary-subtle text-primary rounded-3">
                <i class="fa-solid fa-key fa-2x"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Recommended ATS Keywords for <?php echo $role; ?></h4>
                <small class="text-muted">Incorporate these terms into your skills and project descriptions</small>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 pt-2">
            <?php if (!empty($keywords)): ?>
                <?php foreach ($keywords as $kw): ?>
                    <span class="badge bg-light text-primary border border-primary-subtle px-3 py-2 fs-6 fw-semibold rounded-pill">
                        <i class="fa-solid fa-plus me-1 text-primary"></i> <?php echo htmlspecialchars($kw); ?>
                    </span>
                <?php endforeach; ?>
            <?php else: ?>
                <span class="text-muted">No missing critical keywords detected.</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- RECRUITER AI SUMMARY -->
    <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 bg-white mb-4">
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="p-3 bg-primary text-white rounded-3">
                <i class="fa-solid fa-robot fa-2x"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Gemini AI Recruiter Perspective</h4>
                <small class="text-muted">Holistic review for hiring managers</small>
            </div>
        </div>
        <div class="p-4 bg-light rounded-4 text-dark" style="line-height: 1.9; font-size: 16px;">
            <?php echo nl2br(htmlspecialchars($analysis['ai_feedback'])); ?>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="resume_analysis.php" class="btn btn-primary px-4 py-2 fw-semibold">
            <i class="fa-solid fa-rotate-right me-1"></i> Scan Another Resume
        </a>
        <a href="edit_resume.php" class="btn btn-success px-4 py-2 fw-semibold">
            <i class="fa-solid fa-pen-to-square me-1"></i> Edit Resume with Keywords
        </a>
        <a href="preview_resume.php" class="btn btn-outline-primary px-4 py-2 fw-semibold">
            <i class="fa-solid fa-eye me-1"></i> Preview Resume
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
