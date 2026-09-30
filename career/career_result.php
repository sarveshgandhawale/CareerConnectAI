<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../ai/gemini.php';
requireLogin();

$userId = (int)$_SESSION['user_id'];
$savedMessage = '';

// Read inputs from POST or fallback
$studentName = trim($_POST['name'] ?? $_SESSION['name'] ?? 'Student');
$course = trim($_POST['course'] ?? $_SESSION['course'] ?? 'BCA');
$programming = trim($_POST['programming'] ?? 'Intermediate');
$interest = trim($_POST['interest'] ?? 'Web Development');
$communication = trim($_POST['communication'] ?? 'Intermediate');
$problem = trim($_POST['problem'] ?? 'Medium');

$assessmentData = [
    'name' => $studentName,
    'course' => $course,
    'programming' => $programming,
    'interest' => $interest,
    'communication' => $communication,
    'problem' => $problem
];

// Generate AI Recommendation via Gemini
$analysis = analyzeCareerAssessment($assessmentData);
$career = $analysis['career'];
$score = (int)$analysis['score'];
$skills = $analysis['skills'];
$missing = $analysis['missing_skills'];
$roadmap = $analysis['roadmap'];
$aiAdvice = $analysis['ai_advice'];

// Auto-save assessment to database for logged-in user
if ($userId > 0) {
    $insertStmt = mysqli_prepare($conn, "INSERT INTO career_results (user_id, student_name, course, programming_skill, interest_field, communication_skill, problem_solving, career, score, skills, missing_skills, roadmap, ai_advice) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($insertStmt) {
        mysqli_stmt_bind_param(
            $insertStmt,
            "isssssssissss",
            $userId,
            $studentName,
            $course,
            $programming,
            $interest,
            $communication,
            $problem,
            $career,
            $score,
            $skills,
            $missing,
            $roadmap,
            $aiAdvice
        );
        if (mysqli_stmt_execute($insertStmt)) {
            $savedMessage = "Assessment saved to your career history.";
            logActivity($conn, $userId, 'Career Guidance', 'Career', "Generated career roadmap for {$career} (Score: {$score}%).");
        }
        mysqli_stmt_close($insertStmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AI Career Recommendation | CareerConnect AI</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/career_result.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/nav.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/footer.css'); ?>">
</head>
<body class="bg-light">

<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-5">

    <!-- RESULT HERO -->
    <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5 bg-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2">
                    <i class="fa-solid fa-sparkles me-1"></i> Gemini AI Analysis Complete
                </span>
                <h1 class="fw-bold display-6 text-dark mb-2">
                    Recommended Career: <span class="text-primary"><?php echo htmlspecialchars($career); ?></span>
                </h1>
                <p class="lead text-muted mb-3">
                    Personalized evaluation for <strong><?php echo htmlspecialchars($studentName); ?></strong> (<?php echo htmlspecialchars($course); ?>).
                </p>

                <?php if (!empty($savedMessage)): ?>
                    <div class="badge bg-success-subtle text-success p-2 rounded-3 mb-2">
                        <i class="fa-solid fa-circle-check me-1"></i> <?php echo $savedMessage; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4 text-center">
                <div class="p-4 rounded-4 border bg-light shadow-sm d-inline-block w-100">
                    <span class="text-muted fw-semibold small text-uppercase">Career Match Score</span>
                    <h1 class="display-3 fw-bold text-primary my-2"><?php echo $score; ?>%</h1>
                    <div class="progress mb-2" style="height: 10px;">
                        <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" style="width: <?php echo $score; ?>%"></div>
                    </div>
                    <small class="text-muted">Calculated by CareerConnect AI Engine</small>
                </div>
            </div>
        </div>
    </div>

    <!-- DETAIL CARDS -->
    <div class="row g-4 mb-4">
        <!-- CURRENT STRENGTHS -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="p-3 bg-success-subtle text-success rounded-3">
                        <i class="fa-solid fa-circle-check fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">Identified Strengths</h4>
                        <small class="text-muted">Skills aligned with this role</small>
                    </div>
                </div>
                <p class="text-secondary" style="line-height: 1.8;"><?php echo nl2br(htmlspecialchars($skills)); ?></p>
            </div>
        </div>

        <!-- SKILLS TO IMPROVE -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="p-3 bg-warning-subtle text-warning rounded-3">
                        <i class="fa-solid fa-triangle-exclamation fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">Skills To Build</h4>
                        <small class="text-muted">Recommended for competitive edge</small>
                    </div>
                </div>
                <p class="text-secondary" style="line-height: 1.8;"><?php echo nl2br(htmlspecialchars($missing)); ?></p>
            </div>
        </div>
    </div>

    <!-- 6-MONTH ROADMAP -->
    <div class="card shadow-sm border-0 rounded-4 p-4 bg-white mb-4">
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="p-3 bg-primary-subtle text-primary rounded-3">
                <i class="fa-solid fa-map-location-dot fa-2x"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Recommended Career Roadmap</h4>
                <small class="text-muted">Structured milestone plan</small>
            </div>
        </div>
        <div class="p-3 bg-light rounded-3">
            <p class="mb-0 text-dark" style="line-height: 1.8;">
                <?php echo nl2br(htmlspecialchars($roadmap)); ?>
            </p>
        </div>
    </div>

    <!-- AI ADVISOR CARD -->
    <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 bg-white mb-4">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="p-3 bg-primary text-white rounded-3">
                <i class="fa-solid fa-robot fa-2x"></i>
            </div>
            <div>
                <h3 class="fw-bold mb-1">Gemini AI Career Mentor Guidance</h3>
                <p class="text-muted mb-0">Detailed recruiter perspective and portfolio project ideas</p>
            </div>
        </div>

        <div class="p-4 rounded-4 bg-light text-dark" style="line-height: 1.9; font-size: 16px;">
            <?php echo nl2br(htmlspecialchars($aiAdvice)); ?>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="career_guidance.php" class="btn btn-primary px-4 py-2 fw-semibold">
            <i class="fa-solid fa-rotate-right me-1"></i> Retake Assessment
        </a>
        <a href="<?php echo url('resume/resume.php'); ?>" class="btn btn-success px-4 py-2 fw-semibold">
            <i class="fa-solid fa-file-lines me-1"></i> Build Resume for <?php echo htmlspecialchars($career); ?>
        </a>
        <a href="<?php echo url('interview/mock_interview.php'); ?>" class="btn btn-warning px-4 py-2 fw-semibold text-dark">
            <i class="fa-solid fa-microphone-lines me-1"></i> Practice Mock Interview
        </a>
        <a href="<?php echo url('view_career_history.php'); ?>" class="btn btn-outline-primary px-4 py-2 fw-semibold">
            <i class="fa-solid fa-clock-rotate-left me-1"></i> Career History
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