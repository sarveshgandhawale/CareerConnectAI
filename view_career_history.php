<?php
require_once __DIR__ . '/config/db.php';
requireLogin();

$userId = (int)$_SESSION['user_id'];
$name = htmlspecialchars($_SESSION['name'] ?? 'Student');

// Handle Deletion
if (isset($_GET['delete_id'])) {
    $delId = (int)$_GET['delete_id'];
    $delStmt = mysqli_prepare($conn, "DELETE FROM career_results WHERE id = ? AND user_id = ?");
    if ($delStmt) {
        mysqli_stmt_bind_param($delStmt, "ii", $delId, $userId);
        mysqli_stmt_execute($delStmt);
        mysqli_stmt_close($delStmt);
        logActivity($conn, $userId, 'Delete Career History', 'Career', "Deleted career history record ID {$delId}.");
        header("Location: " . url('view_career_history.php?success=' . urlencode("Record deleted successfully.")));
        exit();
    }
}

// Fetch career history for this user
$stmt = mysqli_prepare($conn, "SELECT * FROM career_results WHERE user_id = ? ORDER BY id DESC");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Career Assessment History | CareerConnect AI</title>
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

<?php include __DIR__ . '/components/navbar.php'; ?>

<div class="container py-5">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-clock-rotate-left text-primary me-2"></i> My Career Assessment History
            </h2>
            <p class="text-muted mb-0">Past evaluations, match scores, and AI recommendations</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="<?php echo url('career/career_guidance.php'); ?>" class="btn btn-primary fw-semibold">
                <i class="fa-solid fa-plus me-1"></i> New Assessment
            </a>
            <a href="<?php echo url('dashboard/dashboard.php'); ?>" class="btn btn-outline-secondary fw-semibold ms-2">
                <i class="fa-solid fa-house me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> <?php echo htmlspecialchars($_GET['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i> <?php echo htmlspecialchars($_GET['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <div class="row g-4">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4 p-4 bg-white">
                        <div class="row align-items-start">
                            <div class="col-lg-9">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold">
                                        <?php echo htmlspecialchars($row['course'] ?? 'Computer Applications'); ?>
                                    </span>
                                    <small class="text-muted">
                                        <i class="fa-regular fa-calendar me-1"></i>
                                        <?php echo date('F d, Y - h:i A', strtotime($row['created_at'])); ?>
                                    </small>
                                </div>
                                <h3 class="fw-bold text-dark mb-3">
                                    <?php echo htmlspecialchars($row['career']); ?>
                                </h3>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3">
                                            <strong class="text-secondary small d-block mb-1">
                                                <i class="fa-solid fa-check text-success me-1"></i> Key Strengths
                                            </strong>
                                            <span><?php echo htmlspecialchars($row['skills']); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded-3">
                                            <strong class="text-secondary small d-block mb-1">
                                                <i class="fa-solid fa-arrow-trend-up text-warning me-1"></i> Areas to Improve
                                            </strong>
                                            <span><?php echo htmlspecialchars($row['missing_skills']); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-3 bg-light rounded-3 mb-3">
                                    <strong class="text-secondary small d-block mb-1">
                                        <i class="fa-solid fa-map text-primary me-1"></i> Roadmap Summary
                                    </strong>
                                    <p class="mb-0 text-dark"><?php echo nl2br(htmlspecialchars($row['roadmap'])); ?></p>
                                </div>

                                <?php if (!empty($row['ai_advice'])): ?>
                                    <details class="mb-2">
                                        <summary class="text-primary fw-semibold" style="cursor: pointer;">
                                            <i class="fa-solid fa-robot me-1"></i> View Gemini AI Advice Details
                                        </summary>
                                        <div class="mt-3 p-3 border rounded-3 bg-white text-secondary" style="line-height: 1.8;">
                                            <?php echo nl2br(htmlspecialchars($row['ai_advice'])); ?>
                                        </div>
                                    </details>
                                <?php endif; ?>
                            </div>

                            <div class="col-lg-3 text-center text-lg-end mt-3 mt-lg-0 d-flex flex-column justify-content-between h-100">
                                <div>
                                    <div class="d-inline-block text-center p-3 bg-light rounded-4 border w-100 mb-3">
                                        <small class="text-muted fw-semibold">MATCH SCORE</small>
                                        <h2 class="display-5 fw-bold text-primary my-1"><?php echo (int)$row['score']; ?>%</h2>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-primary" style="width: <?php echo (int)$row['score']; ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?php echo url('resume/resume.php'); ?>" class="btn btn-success btn-sm">
                                        <i class="fa-solid fa-file-lines me-1"></i> Resume
                                    </a>
                                    <a href="view_career_history.php?delete_id=<?php echo (int)$row['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this career record?');">
                                        <i class="fa-solid fa-trash me-1"></i> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="card shadow-sm border-0 rounded-4 p-5 text-center bg-white">
            <div class="text-muted mb-3">
                <i class="fa-solid fa-compass fa-4x"></i>
            </div>
            <h4 class="fw-bold">No Career Assessments Found</h4>
            <p class="text-muted">You haven't completed any career assessments yet. Take your first AI assessment today!</p>
            <div class="mt-3">
                <a href="<?php echo url('career/career_guidance.php'); ?>" class="btn btn-primary px-4 py-2 fw-semibold">
                    <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Start Assessment
                </a>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php mysqli_stmt_close($stmt); ?>