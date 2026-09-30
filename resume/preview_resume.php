<?php
require_once __DIR__ . '/../config/db.php';
requireLogin();

$userId = (int)$_SESSION['user_id'];
$resumeId = (int)($_GET['id'] ?? 0);

if ($resumeId > 0) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM resume WHERE id = ? AND user_id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "ii", $resumeId, $userId);
} else {
    $stmt = mysqli_prepare($conn, "SELECT * FROM resume WHERE user_id = ? ORDER BY id DESC LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $userId);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $resume = mysqli_fetch_assoc($result);
} else {
    mysqli_stmt_close($stmt);
    header("Location: resume.php?error=" . urlencode("No resume found. Please create your resume first."));
    exit();
}
mysqli_stmt_close($stmt);

$success = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Preview Resume | CareerConnect AI</title>
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
    <style>
        .resume-paper {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            padding: 45px 50px;
        }
        .resume-header {
            border-bottom: 2px solid #1e3c72;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .resume-section-title {
            color: #1e3c72;
            text-transform: uppercase;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
            margin-top: 25px;
            margin-bottom: 12px;
        }
        @media print {
            .navbar, .modern-footer, .action-bar, .alert {
                display: none !important;
            }
            body, .container, .resume-paper {
                background: #fff !important;
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</head>
<body class="bg-light">

<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-5">

    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show action-bar" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- ACTION HEADER -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 action-bar">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-file-lines text-primary me-2"></i> Resume Preview
            </h3>
            <p class="text-muted mb-0">Professional ATS compliant candidate profile</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex flex-wrap gap-2">
            <button onclick="window.print()" class="btn btn-success fw-semibold shadow-sm">
                <i class="fa-solid fa-print me-1"></i> Print / Download PDF
            </button>
            <a href="edit_resume.php?id=<?php echo (int)$resume['id']; ?>" class="btn btn-outline-primary fw-semibold">
                <i class="fa-solid fa-pen-to-square me-1"></i> Edit Resume
            </a>
            <a href="resume_analysis.php" class="btn btn-warning fw-semibold text-dark shadow-sm">
                <i class="fa-solid fa-wand-magic-sparkles me-1"></i> AI ATS Scanner
            </a>
            <a href="<?php echo url('dashboard/dashboard.php'); ?>" class="btn btn-outline-secondary fw-semibold">
                <i class="fa-solid fa-house me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- RESUME PAPER PREVIEW -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="resume-paper bg-white">
                
                <!-- HEADER -->
                <div class="resume-header text-center">
                    <h1 class="fw-bold text-dark mb-1" style="font-size: 32px; letter-spacing: -0.5px;">
                        <?php echo htmlspecialchars($resume['name']); ?>
                    </h1>
                    <p class="text-primary fw-semibold mb-2" style="font-size: 16px;">
                        <?php echo htmlspecialchars($resume['course']); ?> Candidate
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3 text-muted small">
                        <span><i class="fa-solid fa-envelope text-primary me-1"></i> <?php echo htmlspecialchars($resume['email']); ?></span>
                        <span>&bull;</span>
                        <span><i class="fa-solid fa-phone text-primary me-1"></i> <?php echo htmlspecialchars($resume['mobile']); ?></span>
                        <span>&bull;</span>
                        <span><i class="fa-solid fa-location-dot text-primary me-1"></i> <?php echo htmlspecialchars($resume['address']); ?></span>
                    </div>
                </div>

                <!-- OBJECTIVE -->
                <div class="resume-section">
                    <h5 class="resume-section-title"><i class="fa-solid fa-bullseye me-2"></i> Professional Summary</h5>
                    <p class="text-secondary" style="line-height: 1.8;">
                        <?php echo nl2br(htmlspecialchars($resume['objective'])); ?>
                    </p>
                </div>

                <!-- SKILLS -->
                <div class="resume-section">
                    <h5 class="resume-section-title"><i class="fa-solid fa-code me-2"></i> Technical &amp; Core Skills</h5>
                    <p class="text-secondary" style="line-height: 1.8;">
                        <?php echo nl2br(htmlspecialchars($resume['skills'])); ?>
                    </p>
                </div>

                <!-- EDUCATION -->
                <div class="resume-section">
                    <h5 class="resume-section-title"><i class="fa-solid fa-graduation-cap me-2"></i> Education History</h5>
                    <div class="text-secondary" style="line-height: 1.8;">
                        <?php echo nl2br(htmlspecialchars($resume['education'])); ?>
                    </div>
                </div>

                <!-- PROJECTS -->
                <div class="resume-section">
                    <h5 class="resume-section-title"><i class="fa-solid fa-laptop-code me-2"></i> Projects &amp; Practical Experience</h5>
                    <div class="text-secondary" style="line-height: 1.8;">
                        <?php echo nl2br(htmlspecialchars($resume['projects'])); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
