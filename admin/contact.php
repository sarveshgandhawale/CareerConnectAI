<?php
require_once __DIR__ . '/../config/db.php';
requireAdmin();

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Handle Status Toggle
if (isset($_GET['toggle_id'])) {
    $toggleId = (int)$_GET['toggle_id'];
    $newStatus = $_GET['status'] === 'Resolved' ? 'Resolved' : 'In Progress';
    $stmt = mysqli_prepare($conn, "UPDATE contact_messages SET status = ? WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $newStatus, $toggleId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: contact.php?success=" . urlencode("Inquiry status updated to {$newStatus}."));
        exit();
    }
}

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delId = (int)$_GET['delete_id'];
    $stmt = mysqli_prepare($conn, "DELETE FROM contact_messages WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $delId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        logActivity($conn, null, 'Delete Message', 'Admin Panel', "Admin deleted contact inquiry ID #{$delId}.");
        header("Location: contact.php?success=" . urlencode("Message deleted."));
        exit();
    }
}

$result = mysqli_query($conn, "SELECT * FROM contact_messages ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inquiries Inbox | Admin Console</title>
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
                <i class="fa-solid fa-envelope-open-text text-primary me-2"></i> Student Support &amp; Inquiries Inbox
            </h2>
            <p class="text-muted mb-0">Review help requests, campus inquiries, and support messages</p>
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

    <!-- INQUIRIES TABLE -->
    <div class="card shadow-sm border-0 rounded-4 bg-white overflow-hidden">
        <div class="card-body p-0">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Sender</th>
                                <th>Email / Mobile</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($msg = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td class="ps-4 text-muted small">#<?php echo (int)$msg['id']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($msg['name']); ?></strong></td>
                                    <td>
                                        <span class="text-muted small d-block"><?php echo htmlspecialchars($msg['email']); ?></span>
                                        <span class="text-secondary small"><?php echo htmlspecialchars($msg['mobile']); ?></span>
                                    </td>
                                    <td><span class="badge bg-primary-subtle text-primary"><?php echo htmlspecialchars($msg['subject']); ?></span></td>
                                    <td><span class="text-dark small" style="max-width: 250px; display: inline-block;"><?php echo htmlspecialchars($msg['message']); ?></span></td>
                                    <td>
                                        <?php $st = $msg['status'] ?? 'New'; ?>
                                        <span class="badge <?php echo $st === 'Resolved' ? 'bg-success' : 'bg-warning text-dark'; ?> px-2 py-1">
                                            <?php echo htmlspecialchars($st); ?>
                                        </span>
                                    </td>
                                    <td><span class="text-muted small"><?php echo date('M d, Y', strtotime($msg['created_at'])); ?></span></td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="contact.php?toggle_id=<?php echo (int)$msg['id']; ?>&status=Resolved" class="btn btn-outline-success" title="Mark as Resolved">
                                                <i class="fa-solid fa-check"></i>
                                            </a>
                                            <a href="contact.php?delete_id=<?php echo (int)$msg['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Delete this message?');" title="Delete Message">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No messages in inbox.</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
