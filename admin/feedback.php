<?php
require_once __DIR__ . '/../config/db.php';
requireAdmin();

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delId = (int)$_GET['delete_id'];
    $delStmt = mysqli_prepare($conn, "DELETE FROM feedback WHERE id = ?");
    if ($delStmt) {
        mysqli_stmt_bind_param($delStmt, "i", $delId);
        mysqli_stmt_execute($delStmt);
        mysqli_stmt_close($delStmt);
        logActivity($conn, null, 'Delete Feedback', 'Admin Panel', "Admin deleted feedback ID #{$delId}.");
        header("Location: feedback.php?success=" . urlencode("Feedback entry removed."));
        exit();
    }
}

$ratingFilter = isset($_GET['rating']) ? (int)$_GET['rating'] : 0;

$sql = "SELECT * FROM feedback";
if ($ratingFilter >= 1 && $ratingFilter <= 5) {
    $sql .= " WHERE rating = $ratingFilter";
}
$sql .= " ORDER BY id DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback &amp; Reviews Management | Admin Console</title>
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
                <i class="fa-solid fa-star text-warning me-2"></i> Student Feedback &amp; Reviews
            </h2>
            <p class="text-muted mb-0">Monitor user testimonials, satisfaction ratings, and portal suggestions</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="dashboard.php" class="btn btn-outline-secondary fw-semibold">
                <i class="fa-solid fa-gauge me-1"></i> Admin Dashboard
            </a>
        </div>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- FILTER PILLS -->
    <div class="card shadow-sm border-0 rounded-4 p-3 bg-white mb-4">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="text-muted small fw-semibold me-2">Filter by Rating:</span>
            <a href="feedback.php" class="btn btn-sm <?php echo $ratingFilter === 0 ? 'btn-primary' : 'btn-outline-secondary'; ?>">All Reviews</a>
            <a href="feedback.php?rating=5" class="btn btn-sm <?php echo $ratingFilter === 5 ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary'; ?>">⭐⭐⭐⭐⭐ 5 Stars</a>
            <a href="feedback.php?rating=4" class="btn btn-sm <?php echo $ratingFilter === 4 ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary'; ?>">⭐⭐⭐⭐ 4 Stars</a>
            <a href="feedback.php?rating=3" class="btn btn-sm <?php echo $ratingFilter === 3 ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary'; ?>">⭐⭐⭐ 3 Stars</a>
            <a href="feedback.php?rating=2" class="btn btn-sm <?php echo $ratingFilter === 2 ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary'; ?>">⭐⭐ 2 Stars</a>
            <a href="feedback.php?rating=1" class="btn btn-sm <?php echo $ratingFilter === 1 ? 'btn-warning text-dark fw-bold' : 'btn-outline-secondary'; ?>">⭐ 1 Star</a>
        </div>
    </div>

    <!-- FEEDBACK TABLE -->
    <div class="card shadow-sm border-0 rounded-4 bg-white overflow-hidden">
        <div class="card-body p-0">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Rating</th>
                                <th>Comments / Feedback</th>
                                <th>Date</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($fb = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td class="ps-4 text-muted small">#<?php echo (int)$fb['id']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($fb['name']); ?></strong></td>
                                    <td><span class="text-muted small"><?php echo htmlspecialchars($fb['email']); ?></span></td>
                                    <td>
                                        <div class="text-warning small">
                                            <?php for ($s = 1; $s <= (int)$fb['rating']; $s++): ?>
                                                <i class="fa-solid fa-star"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td><span class="text-dark" style="font-size: 14px;"><?php echo htmlspecialchars($fb['comments']); ?></span></td>
                                    <td><span class="text-muted small"><?php echo date('M d, Y', strtotime($fb['created_at'])); ?></span></td>
                                    <td class="text-end pe-4">
                                        <a href="feedback.php?delete_id=<?php echo (int)$fb['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this feedback review?');" title="Delete Review">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fa-solid fa-star-half-stroke fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No feedback found for this selection.</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
