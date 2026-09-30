<?php
require_once __DIR__ . '/../config/db.php';
requireLogin();

$userId = (int)$_SESSION['user_id'];
$resumeId = (int)($_GET['id'] ?? 0);
$error = '';

// Fetch requested or latest resume
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
    header("Location: resume.php");
    exit();
}
mysqli_stmt_close($stmt);

// Handle Update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $objective = trim($_POST['objective'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $education = trim($_POST['education'] ?? '');
    $projects = trim($_POST['projects'] ?? '');
    $address = trim($_POST['address'] ?? '');

    $targetResumeId = (int)$resume['id'];

    $updateStmt = mysqli_prepare($conn, "UPDATE resume SET name=?, email=?, mobile=?, course=?, objective=?, skills=?, education=?, projects=?, address=? WHERE id=? AND user_id=?");
    if ($updateStmt) {
        mysqli_stmt_bind_param(
            $updateStmt,
            "sssssssssii",
            $name,
            $email,
            $mobile,
            $course,
            $objective,
            $skills,
            $education,
            $projects,
            $address,
            $targetResumeId,
            $userId
        );

        if (mysqli_stmt_execute($updateStmt)) {
            mysqli_stmt_close($updateStmt);
            logActivity($conn, $userId, 'Update Resume', 'Resume', "Updated resume record ID {$targetResumeId}.");
            header("Location: preview_resume.php?id={$targetResumeId}&success=" . urlencode("Resume updated successfully!"));
            exit();
        } else {
            $error = "Update failed: " . mysqli_error($conn);
        }
        mysqli_stmt_close($updateStmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Resume | CareerConnect AI</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/edit_resume.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/nav.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/footer.css'); ?>">
</head>
<body class="bg-light">

<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <div class="card shadow-lg border-0 rounded-4 overflow-hidden bg-white">
                <div class="card-header bg-primary text-white p-4" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <h3 class="mb-0 fw-bold">
                        <i class="fa-solid fa-pen-to-square me-2"></i> Edit Saved Resume
                    </h3>
                </div>

                <div class="card-body p-4 p-md-5">

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-circle-exclamation me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" id="editResumeForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary">Full Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($resume['name']); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary">Email Address</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($resume['email']); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary">Mobile Number</label>
                                <input type="tel" name="mobile" class="form-control" value="<?php echo htmlspecialchars($resume['mobile']); ?>" maxlength="10" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary">Degree / Course</label>
                                <input type="text" name="course" class="form-control" value="<?php echo htmlspecialchars($resume['course']); ?>" required>
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label fw-semibold text-secondary">Professional Objective</label>
                                <textarea name="objective" class="form-control" rows="3" required><?php echo htmlspecialchars($resume['objective']); ?></textarea>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="form-label fw-semibold text-secondary">Skills (Comma-separated)</label>
                                <textarea name="skills" class="form-control" rows="3" required><?php echo htmlspecialchars($resume['skills']); ?></textarea>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="form-label fw-semibold text-secondary">Education History</label>
                                <textarea name="education" class="form-control" rows="3" required><?php echo htmlspecialchars($resume['education']); ?></textarea>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="form-label fw-semibold text-secondary">Projects &amp; Experience</label>
                                <textarea name="projects" class="form-control" rows="4" required><?php echo htmlspecialchars($resume['projects']); ?></textarea>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="form-label fw-semibold text-secondary">Residential Address</label>
                                <textarea name="address" class="form-control" rows="2" required><?php echo htmlspecialchars($resume['address']); ?></textarea>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-5 pt-3 border-top">
                            <button type="submit" name="update" class="btn btn-success flex-grow-1 py-3 fw-semibold shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Update Resume
                            </button>
                            <a href="preview_resume.php?id=<?php echo (int)$resume['id']; ?>" class="btn btn-outline-secondary px-4 py-3 fw-semibold">
                                Cancel
                            </a>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
