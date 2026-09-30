<?php
require_once __DIR__ . '/../config/db.php';
requireLogin();

$userId = (int)$_SESSION['user_id'];
$user = getCurrentUser($conn);

$name = $user['name'] ?? ($_SESSION['name'] ?? '');
$email = $user['email'] ?? ($_SESSION['email'] ?? '');
$mobile = $user['mobile'] ?? '';
$course = $user['course'] ?? ($_SESSION['course'] ?? 'BCA');
$address = $user['address'] ?? 'Mumbai, Maharashtra, India';

// Check if user already has a saved resume
$existingResume = null;
$check = mysqli_prepare($conn, "SELECT * FROM resume WHERE user_id = ? ORDER BY id DESC LIMIT 1");
if ($check) {
    mysqli_stmt_bind_param($check, "i", $userId);
    mysqli_stmt_execute($check);
    $res = mysqli_stmt_get_result($check);
    if ($res && mysqli_num_rows($res) > 0) {
        $existingResume = mysqli_fetch_assoc($res);
    }
    mysqli_stmt_close($check);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ATS Resume Builder | CareerConnect AI</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/resume.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/nav.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/footer.css'); ?>">
</head>
<body class="bg-light">

<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <?php if ($existingResume): ?>
                <div class="alert alert-info d-flex flex-wrap justify-content-between align-items-center shadow-sm rounded-4 mb-4">
                    <div class="mb-2 mb-md-0">
                        <i class="fa-solid fa-file-circle-check me-2"></i>
                        You have an existing saved resume for <strong><?php echo htmlspecialchars($existingResume['name']); ?></strong> (<?php echo htmlspecialchars($existingResume['course']); ?>).
                    </div>
                    <div>
                        <a href="preview_resume.php" class="btn btn-sm btn-primary fw-semibold me-1">
                            <i class="fa-solid fa-eye me-1"></i> Preview / Print
                        </a>
                        <a href="edit_resume.php" class="btn btn-sm btn-outline-secondary fw-semibold">
                            <i class="fa-solid fa-pen me-1"></i> Edit Existing
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card resume-card shadow-lg border-0 rounded-4 overflow-hidden bg-white">
                <div class="card-header bg-primary text-white p-4 text-center" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2">
                        <i class="fa-solid fa-sparkles me-1"></i> Industry Standard ATS Format
                    </span>
                    <h2 class="fw-bold mb-1">
                        <i class="fa-solid fa-file-lines me-2"></i> Professional Resume Builder
                    </h2>
                    <p class="text-white-50 mb-0">Create an ATS-compliant resume structured for top tech company hiring filters</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="save_resume.php" method="POST" id="resumeForm">
                        <div class="row g-3">

                            <div class="col-12">
                                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="fa-solid fa-user text-primary me-2"></i> 1. Contact &amp; Personal Details
                                </h5>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary">Full Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary">Email Address</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary">Mobile Number</label>
                                <input type="tel" name="mobile" class="form-control" value="<?php echo htmlspecialchars($mobile); ?>" maxlength="10" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary">Degree / Academic Program</label>
                                <input type="text" name="course" class="form-control" value="<?php echo htmlspecialchars($course); ?>" required>
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="fa-solid fa-bullseye text-primary me-2"></i> 2. Professional Summary &amp; Objective
                                </h5>
                                <textarea name="objective" class="form-control" rows="3" placeholder="Dedicated and results-driven student..." required>Enthusiastic and motivated <?php echo htmlspecialchars($course); ?> student with a strong foundation in software development, problem solving, and building high-performance web applications. Seeking an opportunity to contribute to real-world projects and accelerate team impact.</textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="fa-solid fa-code text-primary me-2"></i> 3. Technical &amp; Core Skills
                                </h5>
                                <textarea name="skills" class="form-control" rows="3" placeholder="HTML, CSS, JavaScript, PHP, MySQL, Git, Problem Solving" required>PHP 8, MySQL, HTML5, CSS3, JavaScript (ES6+), Bootstrap, RESTful APIs, Git &amp; GitHub, Object-Oriented Programming (OOP), Problem Solving, Agile Collaboration</textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="fa-solid fa-graduation-cap text-primary me-2"></i> 4. Education History
                                </h5>
                                <textarea name="education" class="form-control" rows="3" placeholder="Degree, Institution Name, Year, Percentage/CGPA" required><?php echo htmlspecialchars($course); ?> - Computer Applications (2023 - 2026) | CGPA: 8.6/10&#10;Higher Secondary Certificate (HSC) (2021 - 2023) | 88%&#10;Secondary School Certificate (SSC) | 90%</textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="fa-solid fa-laptop-code text-primary me-2"></i> 5. Projects &amp; Practical Experience
                                </h5>
                                <textarea name="projects" class="form-control" rows="4" placeholder="Project Name, Technologies Used, Description, Key Achievements" required>1. CareerConnect AI Portal: AI-driven student career guidance &amp; mock interview portal built with PHP 8, MySQL, Bootstrap, Chart.js, and Google Gemini API. Reduced career discovery time by 40%.&#10;2. E-Commerce Web Application: Full-stack online store with shopping cart, order processing, and user authentication using PHP &amp; MySQL.&#10;3. Student Management System: CRUD application for college student record tracking and report generation.</textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="fa-solid fa-location-dot text-primary me-2"></i> 6. Residential Location
                                </h5>
                                <textarea name="address" class="form-control" rows="2" placeholder="City, State, Country" required><?php echo htmlspecialchars($address); ?></textarea>
                            </div>

                        </div>

                        <div class="text-center mt-5 pt-3 border-top d-flex flex-wrap justify-content-center gap-3">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3 fw-semibold shadow">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Save &amp; Preview Resume
                            </button>
                            <a href="resume_analysis.php" class="btn btn-outline-success btn-lg px-4 py-3 fw-semibold">
                                <i class="fa-solid fa-magnifying-glass-chart me-2"></i> AI ATS Scanner
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