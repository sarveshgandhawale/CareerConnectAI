<?php
require_once __DIR__ . '/../config/db.php';
requireLogin();

$userId = (int)$_SESSION['user_id'];

// Handle delete
if (isset($_GET['delete_id'])) {
    $delId = (int)$_GET['delete_id'];
    $stmt = mysqli_prepare($conn, "DELETE FROM mock_interviews WHERE id = ? AND user_id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $delId, $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        logActivity($conn, $userId, 'Delete Interview', 'Interview', "Deleted mock interview session ID {$delId}.");
        header("Location: interview_history.php?success=" . urlencode("Interview record deleted successfully."));
        exit();
    }
}

$stmt = mysqli_prepare($conn, "SELECT * FROM mock_interviews WHERE user_id = ? ORDER BY id DESC");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mock Interview History | CareerConnect AI</title>
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

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-clock-rotate-left text-primary me-2"></i> Mock Interview Scorecards History
            </h2>
            <p class="text-muted mb-0">Review all your previous interview performance evaluations</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="mock_interview.php" class="btn btn-primary fw-semibold">
                <i class="fa-solid fa-plus me-1"></i> New Practice Session
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

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <div class="row g-4">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4 p-4 bg-white">
                        <div class="row align-items-start">
                            <div class="col-lg-9">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold">
                                        <?php echo htmlspecialchars($row['interview_type']); ?> Round
                                    </span>
                                    <small class="text-muted">
                                        <i class="fa-regular fa-calendar me-1"></i>
                                        <?php echo date('F d, Y - h:i A', strtotime($row['created_at'])); ?>
                                    </small>
                                </div>
                                <h5 class="fw-bold text-dark mb-2">
                                    Q: <?php echo htmlspecialchars($row['question']); ?>
                                </h5>
                                <div class="p-3 bg-light rounded-3 mb-3">
                                    <small class="text-muted d-block fw-semibold mb-1">Your Answer:</small>
                                    <p class="text-secondary mb-0" style="line-height: 1.7;"><?php echo nl2br(htmlspecialchars($row['user_answer'])); ?></p>
                                </div>

                                <div class="row g-2 text-center">
                                    <div class="col-4">
                                        <div class="p-2 border rounded-3 bg-light">
                                            <small class="text-muted d-block">Clarity</small>
                                            <span class="fw-bold text-primary"><?php echo (int)($row['clarity_score'] ?? 75); ?>%</span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 border rounded-3 bg-light">
                                            <small class="text-muted d-block">Technical</small>
                                            <span class="fw-bold text-success"><?php echo (int)($row['technical_score'] ?? 75); ?>%</span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 border rounded-3 bg-light">
                                            <small class="text-muted d-block">Communication</small>
                                            <span class="fw-bold text-warning"><?php echo (int)($row['communication_score'] ?? 75); ?>%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 text-center text-lg-end mt-3 mt-lg-0 d-flex flex-column justify-content-between h-100">
                                <?php $ovScore = (int)($row['overall_score'] ?? ($row['score'] ?? 75)); ?>
                                <div class="p-3 bg-light rounded-4 border mb-3">
                                    <small class="text-muted fw-semibold">OVERALL SCORE</small>
                                    <h2 class="display-6 fw-bold text-primary my-1"><?php echo $ovScore; ?>%</h2>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-primary" style="width: <?php echo $ovScore; ?>%"></div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="interview_result.php?id=<?php echo (int)$row['id']; ?>" class="btn btn-primary btn-sm">
                                        <i class="fa-solid fa-eye me-1"></i> Full Scorecard
                                    </a>
                                    <a href="interview_history.php?delete_id=<?php echo (int)$row['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this interview record?');">
                                        <i class="fa-solid fa-trash me-1"></i>
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
                <i class="fa-solid fa-microphone-slash fa-4x"></i>
            </div>
            <h4 class="fw-bold">No Mock Interviews Found</h4>
            <p class="text-muted">You haven't completed any mock interviews yet. Start your practice today!</p>
            <div class="mt-3">
                <a href="mock_interview.php" class="btn btn-primary px-4 py-2 fw-semibold">
                    <i class="fa-solid fa-microphone-lines me-1"></i> Start First Mock Interview
                </a>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php mysqli_stmt_close($stmt); ?>
