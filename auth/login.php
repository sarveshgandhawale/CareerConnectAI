<?php
require_once __DIR__ . '/../config/db.php';

if (isLoggedIn()) {
    header("Location: " . url('dashboard/dashboard.php'));
    exit();
}

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | CareerConnect AI</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/login.css'); ?>">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100 py-5">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="row g-0">

                    <!-- LEFT PANEL -->
                    <div class="col-lg-5 left-panel p-5 d-flex flex-column justify-content-between" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: #fff;">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="bg-white text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fa-solid fa-user-graduate fs-5"></i>
                                </div>
                                <h3 class="fw-bold mb-0 text-white">CareerConnect AI</h3>
                            </div>
                            <p class="mt-3 text-white-50">
                                Login to access your AI Student Career Portal, track mock interviews, and build ATS-ready resumes.
                            </p>

                            <div class="left-features mt-4">
                                <div class="left-feature mb-3 d-flex align-items-center gap-3">
                                    <i class="fa-solid fa-list-check fs-5 text-warning"></i>
                                    <span>Skill &amp; Aptitude Assessment</span>
                                </div>
                                <div class="left-feature mb-3 d-flex align-items-center gap-3">
                                    <i class="fa-solid fa-compass fs-5 text-info"></i>
                                    <span>AI Career Guidance &amp; Roadmap</span>
                                </div>
                                <div class="left-feature mb-3 d-flex align-items-center gap-3">
                                    <i class="fa-solid fa-microphone fs-5 text-success"></i>
                                    <span>Interactive AI Mock Interviews</span>
                                </div>
                                <div class="left-feature mb-3 d-flex align-items-center gap-3">
                                    <i class="fa-solid fa-file-lines fs-5 text-warning"></i>
                                    <span>ATS Resume Builder &amp; Analyzer</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-white-50 small mt-4">
                            &copy; <?php echo date('Y'); ?> CareerConnect AI. All rights reserved.
                        </div>
                    </div>

                    <!-- LOGIN FORM -->
                    <div class="col-lg-7 bg-white p-5">
                        <div class="p-2">
                            <div class="text-center mb-4">
                                <h3 class="fw-bold text-dark">
                                    <i class="fa-solid fa-right-to-bracket text-primary me-2"></i>
                                    Welcome Back
                                </h3>
                                <p class="text-muted">Enter your credentials to access your account</p>
                            </div>

                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                                    <?php echo htmlspecialchars($error); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($success)): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fa-solid fa-circle-check me-2"></i>
                                    <?php echo htmlspecialchars($success); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <form action="login_process.php" method="POST">
                                <!-- EMAIL -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-secondary">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fa-solid fa-envelope text-muted"></i></span>
                                        <input type="email" name="email" class="form-control" placeholder="name@example.com" required autofocus>
                                    </div>
                                </div>

                                <!-- PASSWORD -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-secondary">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-muted"></i></span>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility()">
                                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- FORGOT PASSWORD -->
                                <div class="d-flex justify-content-end mb-4">
                                    <a href="<?php echo url('forgot_password.php'); ?>" class="text-decoration-none small text-primary fw-semibold">
                                        Forgot Password?
                                    </a>
                                </div>

                                <!-- LOGIN BUTTON -->
                                <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold shadow-sm">
                                    <i class="fa-solid fa-right-to-bracket me-2"></i> Sign In
                                </button>
                            </form>

                            <!-- REGISTER LINK -->
                            <div class="text-center mt-4 pt-3 border-top">
                                <p class="text-muted mb-2">
                                    Don't have an account?
                                    <a href="register.php" class="text-primary fw-semibold text-decoration-none">Create Account</a>
                                </p>
                                <div class="d-flex justify-content-center gap-3 mt-2">
                                    <a href="<?php echo url('index.php'); ?>" class="text-secondary small text-decoration-none">
                                        <i class="fa-solid fa-arrow-left me-1"></i> Back to Home
                                    </a>
                                    <span class="text-muted small">&bull;</span>
                                    <a href="<?php echo url('admin/admin_login.php'); ?>" class="text-secondary small text-decoration-none">
                                        <i class="fa-solid fa-shield me-1"></i> Admin Portal
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>