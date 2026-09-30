<?php
require_once __DIR__ . '/../config/db.php';
requireLogin();

$userId = (int)$_SESSION['user_id'];
$moduleFilter = trim($_GET['module'] ?? 'all');

$sql = "SELECT * FROM user_activities WHERE user_id = ?";
if ($moduleFilter !== 'all') {
    $sql .= " AND module = ?";
    $stmt = mysqli_prepare($conn, $sql . " ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, "is", $userId, $moduleFilter);
} else {
    $stmt = mysqli_prepare($conn, $sql . " ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, "i", $userId);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Activity Audit Log | CareerConnect AI</title>
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
                <i class="fa-solid fa-clock-rotate-left text-primary me-2"></i> My Portal Activity Log
            </h2>
            <p class="text-muted mb-0">Detailed chronological audit trail of all your assessments, resumes, and interview activities</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="<?php echo url('dashboard/dashboard.php'); ?>" class="btn btn-outline-secondary fw-semibold">
                <i class="fa-solid fa-house me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- FILTER BUTTONS -->
    <div class="card shadow-sm border-0 rounded-4 p-3 bg-white mb-4">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="text-muted small fw-semibold me-2">Filter by Module:</span>
            <a href="module_activity.php?module=all" class="btn btn-sm <?php echo $moduleFilter === 'all' ? 'btn-primary' : 'btn-outline-secondary'; ?>">All</a>
            <a href="module_activity.php?module=Assessment" class="btn btn-sm <?php echo $moduleFilter === 'Assessment' ? 'btn-primary' : 'btn-outline-secondary'; ?>">Assessments</a>
            <a href="module_activity.php?module=Career" class="btn btn-sm <?php echo $moduleFilter === 'Career' ? 'btn-primary' : 'btn-outline-secondary'; ?>">Career Guidance</a>
            <a href="module_activity.php?module=Resume" class="btn btn-sm <?php echo $moduleFilter === 'Resume' ? 'btn-primary' : 'btn-outline-secondary'; ?>">Resume &amp; ATS</a>
            <a href="module_activity.php?module=Interview" class="btn btn-sm <?php echo $moduleFilter === 'Interview' ? 'btn-primary' : 'btn-outline-secondary'; ?>">Mock Interviews</a>
            <a href="module_activity.php?module=Authentication" class="btn btn-sm <?php echo $moduleFilter === 'Authentication' ? 'btn-primary' : 'btn-outline-secondary'; ?>">Auth &amp; Login</a>
            <a href="module_activity.php?module=Profile" class="btn btn-sm <?php echo $moduleFilter === 'Profile' ? 'btn-primary' : 'btn-outline-secondary'; ?>">Profile</a>
        </div>
    </div>

    <!-- ACTIVITIES TABLE -->
    <div class="card shadow-sm border-0 rounded-4 bg-white overflow-hidden">
        <div class="card-body p-0">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Action</th>
                                <th>Module</th>
                                <th>Details / Description</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td class="ps-4 text-muted small">#<?php echo (int)$row['id']; ?></td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-1">
                                            <?php echo htmlspecialchars($row['action']); ?>
                                        </span>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['module']); ?></span></td>
                                    <td><span class="text-dark"><?php echo htmlspecialchars($row['details'] ?? $row['description'] ?? ''); ?></span></td>
                                    <td><span class="text-muted small"><?php echo date('M d, Y - h:i A', strtotime($row['created_at'])); ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fa-solid fa-folder-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No activity records found for this filter.</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php mysqli_stmt_close($stmt); ?>
