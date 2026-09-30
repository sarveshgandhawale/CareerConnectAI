<?php
require_once __DIR__ . '/../config/db.php';
requireLogin();

$userId = (int)$_SESSION['user_id'];
$user = getCurrentUser($conn);

if (!$user) {
    header("Location: " . url('auth/logout.php?action=logout'));
    exit();
}

$userName = htmlspecialchars($user['name'] ?? 'Student');
$userCourse = htmlspecialchars($user['course'] ?? 'BCA');

// 1. Career Assessments Metrics
$careerCount = 0;
$latestCareer = "Not Taken Yet";
$latestCareerScore = 0;
$cQ = mysqli_query($conn, "SELECT career, score FROM career_results WHERE user_id = $userId ORDER BY id DESC LIMIT 1");
if ($cQ && mysqli_num_rows($cQ) > 0) {
    $cRow = mysqli_fetch_assoc($cQ);
    $latestCareer = $cRow['career'];
    $latestCareerScore = (int)$cRow['score'];
}
$cCountQ = mysqli_query($conn, "SELECT COUNT(*) as c FROM career_results WHERE user_id = $userId");
if ($cCountQ) $careerCount = (int)(mysqli_fetch_assoc($cCountQ)['c'] ?? 0);

// 2. Resume & ATS Metrics
$resumeCount = 0;
$latestAtsScore = 0;
$rCountQ = mysqli_query($conn, "SELECT COUNT(*) as c FROM resume WHERE user_id = $userId");
if ($rCountQ) $resumeCount = (int)(mysqli_fetch_assoc($rCountQ)['c'] ?? 0);

$atsQ = mysqli_query($conn, "SELECT ats_score FROM resume_analysis WHERE user_id = $userId ORDER BY id DESC LIMIT 1");
if ($atsQ && mysqli_num_rows($atsQ) > 0) {
    $latestAtsScore = (int)(mysqli_fetch_assoc($atsQ)['ats_score'] ?? 0);
}

// 3. Mock Interview Metrics
$interviewCount = 0;
$avgInterviewScore = 0;
$iCountQ = mysqli_query($conn, "SELECT COUNT(*) as c, AVG(overall_score) as avg_score FROM mock_interviews WHERE user_id = $userId");
if ($iCountQ && mysqli_num_rows($iCountQ) > 0) {
    $iRow = mysqli_fetch_assoc($iCountQ);
    $interviewCount = (int)($iRow['c'] ?? 0);
    $avgInterviewScore = (int)round($iRow['avg_score'] ?? 0);
}

// 4. Skill Assessment Metrics
$assessmentCount = 0;
$latestAssessmentScore = 0;
$aCountQ = mysqli_query($conn, "SELECT COUNT(*) as c FROM assessments WHERE user_id = $userId");
if ($aCountQ) $assessmentCount = (int)(mysqli_fetch_assoc($aCountQ)['c'] ?? 0);

$aQ = mysqli_query($conn, "SELECT score_percentage FROM assessments WHERE user_id = $userId ORDER BY id DESC LIMIT 1");
if ($aQ && mysqli_num_rows($aQ) > 0) {
    $latestAssessmentScore = (int)(mysqli_fetch_assoc($aQ)['score_percentage'] ?? 0);
}

// 5. Recent Activity Logs
$recentActivities = [];
$actQ = mysqli_query($conn, "SELECT * FROM user_activities WHERE user_id = $userId ORDER BY id DESC LIMIT 6");
if ($actQ) {
    while ($act = mysqli_fetch_assoc($actQ)) {
        $recentActivities[] = $act;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Dashboard | CareerConnect AI</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/dashboard.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/nav.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/footer.css'); ?>">
</head>
<body class="bg-light">

<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-5">

    <!-- WELCOME HERO -->
    <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5 bg-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold mb-2">
                    <i class="fa-solid fa-graduation-cap me-1"></i> Student Career Hub
                </span>
                <h1 class="fw-bold display-6 text-dark mb-1">
                    Welcome back, <span class="text-primary"><?php echo $userName; ?></span>! 👋
                </h1>
                <p class="lead text-muted mb-0">
                    Track your career readiness, ATS resume optimization, diagnostic tests, and interview scorecards in real-time.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="<?php echo url('assessment/assessment.php'); ?>" class="btn btn-primary btn-lg px-4 py-3 fw-semibold shadow">
                    <i class="fa-solid fa-list-check me-2"></i> Take Skill Assessment
                </a>
            </div>
        </div>
    </div>

    <!-- 4 KPI SUMMARY CARDS -->
    <div class="row g-4 mb-4">
        <!-- 1. CAREER GUIDANCE -->
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="stat-icon bg-primary-subtle text-primary rounded-3 p-3">
                        <i class="fa-solid fa-compass fs-4"></i>
                    </div>
                    <span class="badge bg-light text-muted border"><?php echo $careerCount; ?> Taken</span>
                </div>
                <h3 class="fw-bold text-dark mb-1"><?php echo $latestCareerScore > 0 ? $latestCareerScore . '%' : 'N/A'; ?></h3>
                <h6 class="text-muted mb-2">Career Match Score</h6>
                <small class="text-truncate text-secondary d-block fw-semibold" title="<?php echo htmlspecialchars($latestCareer); ?>">
                    <?php echo htmlspecialchars($latestCareer); ?>
                </small>
                <div class="mt-3 pt-2 border-top">
                    <a href="<?php echo url('career/career_guidance.php'); ?>" class="text-primary small fw-semibold text-decoration-none">
                        AI Guidance <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. ATS RESUME -->
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="stat-icon bg-success-subtle text-success rounded-3 p-3">
                        <i class="fa-solid fa-file-circle-check fs-4"></i>
                    </div>
                    <span class="badge bg-light text-muted border"><?php echo $resumeCount; ?> Built</span>
                </div>
                <h3 class="fw-bold text-dark mb-1"><?php echo $latestAtsScore > 0 ? $latestAtsScore . '%' : 'N/A'; ?></h3>
                <h6 class="text-muted mb-2">ATS Resume Score</h6>
                <small class="text-muted d-block">
                    <?php echo $latestAtsScore >= 75 ? 'Optimal Compatibility' : ($latestAtsScore > 0 ? 'Needs Keyword Polish' : 'No Resumes Scanned'); ?>
                </small>
                <div class="mt-3 pt-2 border-top">
                    <a href="<?php echo url('resume/resume_analysis.php'); ?>" class="text-success small fw-semibold text-decoration-none">
                        Scan Resume <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- 3. MOCK INTERVIEWS -->
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="stat-icon bg-warning-subtle text-warning rounded-3 p-3">
                        <i class="fa-solid fa-microphone-lines fs-4"></i>
                    </div>
                    <span class="badge bg-light text-muted border"><?php echo $interviewCount; ?> Completed</span>
                </div>
                <h3 class="fw-bold text-dark mb-1"><?php echo $avgInterviewScore > 0 ? $avgInterviewScore . '%' : 'N/A'; ?></h3>
                <h6 class="text-muted mb-2">Avg Interview Score</h6>
                <small class="text-muted d-block">
                    <?php echo $interviewCount > 0 ? $interviewCount . ' practice questions' : 'Ready to practice'; ?>
                </small>
                <div class="mt-3 pt-2 border-top">
                    <a href="<?php echo url('interview/mock_interview.php'); ?>" class="text-warning small fw-semibold text-decoration-none">
                        Practice Coach <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- 4. SKILL ASSESSMENT -->
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="stat-icon bg-info-subtle text-info rounded-3 p-3">
                        <i class="fa-solid fa-list-check fs-4"></i>
                    </div>
                    <span class="badge bg-light text-muted border"><?php echo $assessmentCount; ?> Tests</span>
                </div>
                <h3 class="fw-bold text-dark mb-1"><?php echo $latestAssessmentScore > 0 ? $latestAssessmentScore . '%' : 'N/A'; ?></h3>
                <h6 class="text-muted mb-2">Diagnostic Score</h6>
                <small class="text-muted d-block">
                    <?php echo $assessmentCount > 0 ? 'Technical & Soft Skills' : 'Pending First Test'; ?>
                </small>
                <div class="mt-3 pt-2 border-top">
                    <a href="<?php echo url('assessment/assessment.php'); ?>" class="text-info small fw-semibold text-decoration-none">
                        Start Test <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- CHARTS ROW -->
    <div class="row g-4 mb-4">
        <!-- 1. MULTI-DIMENSIONAL RADAR/BAR CHART -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-chart-simple text-primary me-2"></i> Performance Dimensions
                    </h5>
                    <span class="badge bg-light text-muted border">Live Metrics</span>
                </div>
                <div style="position: relative; height: 280px;">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 2. QUICK ACTIONS & TOOLS -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <h5 class="fw-bold text-dark mb-3">
                    <i class="fa-solid fa-wand-magic-sparkles text-primary me-2"></i> Career Acceleration Tools
                </h5>
                <div class="list-group list-group-flush">
                    <a href="<?php echo url('career/career_guidance.php'); ?>" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3 border-0 rounded-3 mb-1 bg-light">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-compass text-primary fs-5"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">AI Career Guidance</h6>
                                <small class="text-muted">Personalized roadmaps &amp; roles</small>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-muted small"></i>
                    </a>

                    <a href="<?php echo url('resume/resume.php'); ?>" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3 border-0 rounded-3 mb-1 bg-light">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-file-lines text-success fs-5"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">ATS Resume Builder</h6>
                                <small class="text-muted">Create recruiter-optimized resumes</small>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-muted small"></i>
                    </a>

                    <a href="<?php echo url('interview/mock_interview.php'); ?>" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3 border-0 rounded-3 mb-1 bg-light">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-microphone-lines text-warning fs-5"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">AI Mock Interview Practice</h6>
                                <small class="text-muted">Voice &amp; speech coaching with model answers</small>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-muted small"></i>
                    </a>

                    <a href="<?php echo url('questions/question_bank.php'); ?>" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-3 border-0 rounded-3 mb-1 bg-light">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-circle-question text-info fs-5"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Technical Question Bank</h6>
                                <small class="text-muted">Curated technical and HR questions</small>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-muted small"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- RECENT ACTIVITY LOGS -->
    <div class="card shadow-sm border-0 rounded-4 p-4 bg-white">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark mb-0">
                <i class="fa-solid fa-clock-rotate-left text-primary me-2"></i> Recent Activity Log
            </h5>
            <a href="<?php echo url('activity/module_activity.php'); ?>" class="btn btn-sm btn-outline-primary fw-semibold">
                View Full Audit Log
            </a>
        </div>

        <?php if (!empty($recentActivities)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Action</th>
                            <th>Module</th>
                            <th>Description</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentActivities as $act): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary fw-semibold px-2 py-1">
                                        <?php echo htmlspecialchars($act['action']); ?>
                                    </span>
                                </td>
                                <td><span class="text-muted small fw-semibold"><?php echo htmlspecialchars($act['module']); ?></span></td>
                                <td><span class="text-dark small"><?php echo htmlspecialchars($act['details'] ?? $act['description'] ?? ''); ?></span></td>
                                <td><span class="text-muted small"><?php echo date('M d, Y - h:i A', strtotime($act['created_at'])); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted text-center py-4 mb-0">No activity logged yet. Start exploring CareerConnect AI tools!</p>
        <?php endif; ?>
    </div>

</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script>
// Performance Overview Bar Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('performanceChart').getContext('2d');
    
    const careerScore = <?php echo $latestCareerScore > 0 ? $latestCareerScore : 75; ?>;
    const atsScore = <?php echo $latestAtsScore > 0 ? $latestAtsScore : 70; ?>;
    const interviewScore = <?php echo $avgInterviewScore > 0 ? $avgInterviewScore : 72; ?>;
    const testScore = <?php echo $latestAssessmentScore > 0 ? $latestAssessmentScore : 80; ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Career Fit', 'ATS Resume', 'Interview Readiness', 'Skill Diagnostic'],
            datasets: [{
                label: 'Proficiency Score (%)',
                data: [careerScore, atsScore, interviewScore, testScore],
                backgroundColor: [
                    'rgba(30, 60, 114, 0.85)',
                    'rgba(40, 167, 69, 0.85)',
                    'rgba(255, 193, 7, 0.85)',
                    'rgba(23, 162, 184, 0.85)'
                ],
                borderRadius: 8,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) { return value + "%"; }
                    }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>