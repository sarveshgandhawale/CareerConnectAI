<?php
require_once __DIR__ . '/../config/db.php';
requireLogin();

$userId = (int)$_SESSION['user_id'];
$user = getCurrentUser($conn);

if (!$user) {
    header("Location: " . url('auth/logout.php?action=logout'));
    exit();
}

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';

// Fetch stats for profile
$careerCount = 0;
$resumeCount = 0;
$interviewCount = 0;
$assessmentCount = 0;

$q1 = mysqli_query($conn, "SELECT COUNT(*) as c FROM career_results WHERE user_id = $userId");
if ($q1) $careerCount = mysqli_fetch_assoc($q1)['c'] ?? 0;

$q2 = mysqli_query($conn, "SELECT COUNT(*) as c FROM resume WHERE user_id = $userId");
if ($q2) $resumeCount = mysqli_fetch_assoc($q2)['c'] ?? 0;

$q3 = mysqli_query($conn, "SELECT COUNT(*) as c FROM mock_interviews WHERE user_id = $userId");
if ($q3) $interviewCount = mysqli_fetch_assoc($q3)['c'] ?? 0;

$q4 = mysqli_query($conn, "SELECT COUNT(*) as c FROM assessments WHERE user_id = $userId");
if ($q4) $assessmentCount = mysqli_fetch_assoc($q4)['c'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Student Profile | CareerConnect AI</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/profile.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/nav.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/footer.css'); ?>">
</head>
<body class="bg-light">

<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> <?php echo htmlspecialchars($success); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card profile-card shadow-lg border-0 rounded-4 overflow-hidden bg-white mb-4">
                <div class="card-header bg-primary text-white p-4 text-center" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <h3 class="mb-0 fw-bold">
                        <i class="fa-solid fa-user-gear me-2"></i> My Student Profile
                    </h3>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img src="<?php echo asset('images/default.png'); ?>" alt="Profile Avatar" class="rounded-circle shadow-sm border border-3 border-primary" style="width: 110px; height: 110px; object-fit: cover;">
                        </div>
                        <h3 class="mt-3 fw-bold text-dark mb-1">
                            <?php echo htmlspecialchars($user['name']); ?>
                        </h3>
                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-semibold">
                            <i class="fa-solid fa-graduation-cap me-1"></i> <?php echo htmlspecialchars($user['course']); ?>
                        </span>
                        <p class="text-muted small mt-2 mb-0">
                            Registered on <?php echo date('F d, Y', strtotime($user['created_at'])); ?>
                        </p>
                    </div>

                    <!-- ACTIVITY COUNTERS -->
                    <div class="row text-center mb-4 g-3">
                        <div class="col-6 col-md-3">
                            <div class="p-3 bg-light rounded-3 border">
                                <h3 class="fw-bold text-primary mb-0"><?php echo (int)$assessmentCount; ?></h3>
                                <small class="text-muted">Skill Tests</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 bg-light rounded-3 border">
                                <h3 class="fw-bold text-info mb-0"><?php echo (int)$careerCount; ?></h3>
                                <small class="text-muted">Career Roadmaps</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 bg-light rounded-3 border">
                                <h3 class="fw-bold text-success mb-0"><?php echo (int)$resumeCount; ?></h3>
                                <small class="text-muted">Resumes</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 bg-light rounded-3 border">
                                <h3 class="fw-bold text-warning mb-0"><?php echo (int)$interviewCount; ?></h3>
                                <small class="text-muted">Mock Interviews</small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- EDIT PROFILE FORM -->
                    <form action="profile_process.php" method="POST">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="fa-solid fa-id-card text-primary me-2"></i> Account &amp; Academic Information
                        </h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-semibold">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-user text-muted"></i></span>
                                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-semibold">Email Address (Read-only)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-envelope text-muted"></i></span>
                                    <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-semibold">Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-phone text-muted"></i></span>
                                    <input type="tel" name="mobile" class="form-control" value="<?php echo htmlspecialchars($user['mobile']); ?>" maxlength="10" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-semibold">Gender</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-venus-mars text-muted"></i></span>
                                    <select name="gender" class="form-select">
                                        <option value="Male" <?php if ($user['gender'] === 'Male') echo 'selected'; ?>>Male</option>
                                        <option value="Female" <?php if ($user['gender'] === 'Female') echo 'selected'; ?>>Female</option>
                                        <option value="Other" <?php if ($user['gender'] === 'Other') echo 'selected'; ?>>Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-semibold">Course / Degree</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-graduation-cap text-muted"></i></span>
                                    <select name="course" class="form-select">
                                        <option value="BCA" <?php if ($user['course'] === 'BCA') echo 'selected'; ?>>BCA (Bachelor of Computer Applications)</option>
                                        <option value="MCA" <?php if ($user['course'] === 'MCA') echo 'selected'; ?>>MCA (Master of Computer Applications)</option>
                                        <option value="B.Tech / BE" <?php if ($user['course'] === 'B.Tech / BE') echo 'selected'; ?>>B.Tech / B.E. (Computer Science / IT)</option>
                                        <option value="BSc IT / CS" <?php if ($user['course'] === 'BSc IT / CS') echo 'selected'; ?>>B.Sc (IT / Computer Science)</option>
                                        <option value="BBA" <?php if ($user['course'] === 'BBA') echo 'selected'; ?>>BBA</option>
                                        <option value="MBA" <?php if ($user['course'] === 'MBA') echo 'selected'; ?>>MBA</option>
                                        <option value="Other" <?php if ($user['course'] === 'Other') echo 'selected'; ?>>Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-semibold">Account Role</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-shield text-muted"></i></span>
                                    <input type="text" class="form-control bg-light" value="<?php echo ucfirst(htmlspecialchars($user['role'] ?? 'student')); ?>" readonly>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label text-muted small fw-semibold">Address / Location</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-location-dot text-muted"></i></span>
                                    <textarea name="address" class="form-control" rows="2" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-center gap-3 mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-success px-4 py-2 fw-semibold shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Profile Changes
                            </button>
                            <a href="<?php echo url('dashboard/dashboard.php'); ?>" class="btn btn-outline-primary px-4 py-2 fw-semibold">
                                <i class="fa-solid fa-house me-1"></i> Dashboard
                            </a>
                            <a href="<?php echo url('activity/module_activity.php'); ?>" class="btn btn-outline-secondary px-4 py-2 fw-semibold">
                                <i class="fa-solid fa-clock-rotate-left me-1"></i> Activity Log
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