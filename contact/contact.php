<?php
require_once __DIR__ . '/../config/config.php';

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

$prefillName = $_SESSION['name'] ?? '';
$prefillEmail = $_SESSION['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact &amp; Support | CareerConnect AI</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/contact.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/nav.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/footer.css'); ?>">
</head>
<body class="bg-light">

<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-5">

    <!-- HERO -->
    <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5 bg-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold mb-2">
                    <i class="fa-solid fa-headset me-1"></i> Student &amp; College Support
                </span>
                <h1 class="fw-bold display-6 text-dark mb-2">
                    <i class="fa-solid fa-envelope-open-text text-primary me-2"></i> Get in Touch With Our Team
                </h1>
                <p class="lead text-muted mb-0">
                    Have questions about CareerConnect AI features, need guidance, or want to integrate the portal at your institution? We are here to help!
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="p-3 bg-light rounded-4 border d-inline-block text-center shadow-sm">
                    <span class="text-muted small fw-semibold d-block">RESPONSE TIME</span>
                    <h4 class="fw-bold text-success mb-0"><i class="fa-solid fa-bolt me-1"></i> Within 24 Hours</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- CONTACT INFO -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <h4 class="fw-bold text-dark mb-4">
                    <i class="fa-solid fa-circle-info text-primary me-2"></i> Contact Details
                </h4>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="p-3 bg-primary-subtle text-primary rounded-circle">
                        <i class="fa-solid fa-envelope fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Email Inquiries</h6>
                        <span class="text-muted small">support@careerconnectai.com</span>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="p-3 bg-success-subtle text-success rounded-circle">
                        <i class="fa-solid fa-phone fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Helpline</h6>
                        <span class="text-muted small">+91 98765 43210 (Mon - Sat)</span>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="p-3 bg-warning-subtle text-warning rounded-circle">
                        <i class="fa-solid fa-location-dot fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Headquarters</h6>
                        <span class="text-muted small">Mumbai, Maharashtra, India</span>
                    </div>
                </div>

                <hr>

                <div class="p-3 bg-light rounded-3">
                    <h6 class="fw-bold text-dark mb-1">
                        <i class="fa-solid fa-sparkles text-primary me-1"></i> Instant AI Guidance
                    </h6>
                    <small class="text-muted">
                        Need quick career guidance or resume tips? Explore our AI Guidance, Resume Analyzer, and Mock Interview Coach!
                    </small>
                </div>
            </div>
        </div>

        <!-- CONTACT FORM -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 bg-white h-100">
                <h4 class="fw-bold text-dark mb-3">
                    <i class="fa-solid fa-paper-plane text-primary me-2"></i> Send Us a Message
                </h4>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> <?php echo htmlspecialchars($success); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-exclamation me-2"></i> <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="contact_process.php" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($prefillName); ?>" placeholder="John Doe" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Email Address</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($prefillEmail); ?>" placeholder="name@example.com" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Mobile Number (Optional)</label>
                            <input type="tel" name="mobile" class="form-control" placeholder="10-digit mobile" maxlength="10">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Inquiry Category</label>
                            <select name="subject" class="form-select">
                                <option value="Student Support" selected>Student Career Guidance Support</option>
                                <option value="Resume ATS Query">Resume Builder &amp; ATS Scanner</option>
                                <option value="Mock Interview Issue">AI Mock Interview Practice</option>
                                <option value="College Partnership">College / Campus Placement Inquiry</option>
                                <option value="Other">Other Query</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary">Message</label>
                            <textarea name="message" class="form-control" rows="5" placeholder="Please describe how we can help you..." required></textarea>
                        </div>
                    </div>

                    <div class="mt-4 pt-2">
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-semibold shadow">
                            <i class="fa-solid fa-paper-plane me-2"></i> Submit Inquiry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>