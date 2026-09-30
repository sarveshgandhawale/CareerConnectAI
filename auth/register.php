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
    <title>Create Account | CareerConnect AI</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/register.css'); ?>">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center align-items-center min-vh-100">
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
                                Join thousands of students accelerating their tech careers with AI-powered career roadmaps, mock interviews, and ATS resume optimization.
                            </p>

                            <div class="left-features mt-4">
                                <div class="left-feature mb-3 d-flex align-items-center gap-3">
                                    <i class="fa-solid fa-wand-magic-sparkles fs-5 text-warning"></i>
                                    <span>Personalized Career Guidance</span>
                                </div>
                                <div class="left-feature mb-3 d-flex align-items-center gap-3">
                                    <i class="fa-solid fa-chart-line fs-5 text-info"></i>
                                    <span>AI ATS Resume Scoring</span>
                                </div>
                                <div class="left-feature mb-3 d-flex align-items-center gap-3">
                                    <i class="fa-solid fa-microphone fs-5 text-success"></i>
                                    <span>Real-time Mock Interview Feedback</span>
                                </div>
                                <div class="left-feature mb-3 d-flex align-items-center gap-3">
                                    <i class="fa-solid fa-list-check fs-5 text-warning"></i>
                                    <span>Domain Skill Assessments</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-white-50 small mt-4">
                            &copy; <?php echo date('Y'); ?> CareerConnect AI. All rights reserved.
                        </div>
                    </div>

                    <!-- REGISTRATION FORM -->
                    <div class="col-lg-7 bg-white p-5">
                        <div class="p-2">
                            <div class="text-center mb-4">
                                <h3 class="fw-bold text-dark">
                                    <i class="fa-solid fa-user-plus text-primary me-2"></i>
                                    Create Student Account
                                </h3>
                                <p class="text-muted">Start your career journey in seconds</p>
                            </div>

                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                                    <?php echo htmlspecialchars($error); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <form action="register_process.php" method="POST" id="regForm" onsubmit="return validateRegisterForm();">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-semibold text-secondary">Full Name</label>
                                        <input class="form-control" type="text" name="name" id="name" placeholder="John Doe" required autofocus>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-secondary">Email Address</label>
                                        <input class="form-control" type="email" name="email" id="email" placeholder="name@example.com" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-secondary">Mobile Number</label>
                                        <input class="form-control" type="tel" name="mobile" id="mobile" placeholder="10-digit mobile" maxlength="10" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-secondary">Gender</label>
                                        <select class="form-select" name="gender">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-secondary">Current Degree / Course</label>
                                        <select class="form-select" name="course" required>
                                            <option value="BCA" selected>BCA (Bachelor of Computer Applications)</option>
                                            <option value="MCA">MCA (Master of Computer Applications)</option>
                                            <option value="B.Tech / BE">B.Tech / B.E. (Computer Science / IT)</option>
                                            <option value="BSc IT / CS">B.Sc (IT / Computer Science)</option>
                                            <option value="BBA">BBA</option>
                                            <option value="MBA">MBA</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-secondary">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="password" id="password" placeholder="Min. 6 characters" required>
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('password')">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-secondary">Confirm Password</label>
                                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Repeat password" required>
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <label class="form-label fw-semibold text-secondary">Address / Location</label>
                                        <textarea class="form-control" rows="2" name="address" placeholder="City, State, Country" required>Mumbai, Maharashtra, India</textarea>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold shadow-sm">
                                    <i class="fa-solid fa-user-plus me-2"></i> Register Account
                                </button>
                            </form>

                            <div class="text-center mt-4 pt-3 border-top">
                                <p class="text-muted mb-2">
                                    Already have an account?
                                    <a href="login.php" class="text-primary fw-semibold text-decoration-none">Sign In</a>
                                </p>
                                <a href="<?php echo url('index.php'); ?>" class="text-secondary small text-decoration-none">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Home
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(id) {
    const el = document.getElementById(id);
    if (el) {
        el.type = el.type === 'password' ? 'text' : 'password';
    }
}

function validateRegisterForm() {
    const p1 = document.getElementById('password').value;
    const p2 = document.getElementById('confirm_password').value;
    const mobile = document.getElementById('mobile').value;

    if (p1 !== p2) {
        alert('Passwords do not match. Please re-enter.');
        return false;
    }
    if (p1.length < 6) {
        alert('Password must be at least 6 characters long.');
        return false;
    }
    if (!/^\d{10}$/.test(mobile)) {
        alert('Please enter a valid 10-digit mobile number.');
        return false;
    }
    return true;
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>