<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../ai/gemini.php';
requireLogin();

$userId = (int)$_SESSION['user_id'];
$error = '';

// Check if user has an existing saved resume
$existingResumes = [];
$resStmt = mysqli_prepare($conn, "SELECT id, name, course, created_at FROM resume WHERE user_id = ? ORDER BY id DESC");
if ($resStmt) {
    mysqli_stmt_bind_param($resStmt, "i", $userId);
    mysqli_stmt_execute($resStmt);
    $rRes = mysqli_stmt_get_result($resStmt);
    while ($r = mysqli_fetch_assoc($rRes)) {
        $existingResumes[] = $r;
    }
    mysqli_stmt_close($resStmt);
}

// Handle Form Submission / File Upload
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $targetRole = trim($_POST['target_role'] ?? 'Full Stack Web Developer');
    $resumeSource = $_POST['resume_source'] ?? 'built_in';
    $resumeContent = '';
    $resumeId = null;

    if ($resumeSource === 'built_in') {
        $resumeId = (int)($_POST['resume_id'] ?? 0);
        if ($resumeId > 0) {
            $stmt = mysqli_prepare($conn, "SELECT * FROM resume WHERE id = ? AND user_id = ? LIMIT 1");
            mysqli_stmt_bind_param($stmt, "ii", $resumeId, $userId);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            if ($r = mysqli_fetch_assoc($res)) {
                $resumeContent = "Name: " . $r['name'] . "\n"
                               . "Course: " . $r['course'] . "\n"
                               . "Summary Objective: " . $r['objective'] . "\n"
                               . "Skills: " . $r['skills'] . "\n"
                               . "Education History: " . $r['education'] . "\n"
                               . "Projects & Work: " . $r['projects'] . "\n"
                               . "Address: " . $r['address'];
            }
            mysqli_stmt_close($stmt);
        }
    } elseif ($resumeSource === 'text') {
        $resumeContent = trim($_POST['resume_text'] ?? '');
    } elseif ($resumeSource === 'file' && isset($_FILES['resume_file']) && $_FILES['resume_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['resume_file'];
        $fileName = $file['name'];
        $fileTmp = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowed = ['pdf', 'txt', 'doc', 'docx'];
        if (!in_array($fileExt, $allowed)) {
            $error = "Only PDF, TXT, DOC, and DOCX files are supported.";
        } elseif ($fileSize > 5 * 1024 * 1024) {
            $error = "File size must be under 5MB.";
        } else {
            if ($fileExt === 'txt') {
                $resumeContent = file_get_contents($fileTmp);
            } else {
                $resumeContent = "Uploaded Document: " . $fileName . "\n" . substr(strip_tags(file_get_contents($fileTmp)), 0, 4000);
            }
            if (empty($resumeContent)) {
                $resumeContent = "Resume File: " . htmlspecialchars($fileName) . " for " . htmlspecialchars($_SESSION['name']);
            }
        }
    }

    if (empty($error)) {
        if (empty($resumeContent)) {
            $error = "Please provide resume content or select an existing resume to scan.";
        } else {
            // Run Gemini AI Analysis
            $analysis = analyzeResumeText($resumeContent, $targetRole);
            $atsScore = (int)$analysis['ats_score'];
            $strengths = $analysis['strengths'];
            $improvements = $analysis['improvements'];
            $missingKeywords = $analysis['missing_keywords'];
            $aiFeedback = $analysis['ai_feedback'];

            // Insert into resume_analysis table
            $stmt = mysqli_prepare($conn, "INSERT INTO resume_analysis (user_id, resume_id, target_role, ats_score, strengths, improvements, missing_keywords, ai_feedback) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "iisissss", $userId, $resumeId, $targetRole, $atsScore, $strengths, $improvements, $missingKeywords, $aiFeedback);
                if (mysqli_stmt_execute($stmt)) {
                    $analysisId = mysqli_insert_id($conn);
                    mysqli_stmt_close($stmt);

                    // Log activity
                    logActivity($conn, $userId, 'ATS Resume Scan', 'Resume', "Scanned resume for {$targetRole} (Score: {$atsScore}%).");

                    header("Location: resume_result.php?id=" . $analysisId);
                    exit();
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload &amp; Scan Resume | CareerConnect AI</title>
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
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden bg-white">
                <div class="card-header bg-primary text-white p-4 text-center" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2">
                        <i class="fa-solid fa-sparkles me-1"></i> Powered by Gemini AI ATS Engine
                    </span>
                    <h2 class="fw-bold mb-1">
                        <i class="fa-solid fa-file-arrow-up me-2"></i> AI ATS Resume Scanner
                    </h2>
                    <p class="text-white-50 mb-0">Check your resume's ATS compatibility score, missing keywords &amp; recruiter feedback</p>
                </div>

                <div class="card-body p-4 p-md-5">

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-circle-exclamation me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <!-- TARGET ROLE -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary">Target Job Role</label>
                            <select name="target_role" class="form-select form-select-lg" required>
                                <option value="Full Stack Web Developer" selected>Full Stack Web Developer (PHP / JavaScript / React)</option>
                                <option value="Frontend Developer">Frontend Developer (React / Next.js / CSS)</option>
                                <option value="Backend Developer">Backend Developer (PHP / Node.js / MySQL / APIs)</option>
                                <option value="AI / Machine Learning Engineer">AI / Machine Learning Engineer (Python / PyTorch)</option>
                                <option value="Data Scientist">Data Scientist / Data Analyst (SQL / Pandas / BI)</option>
                                <option value="Cloud & DevOps Engineer">Cloud &amp; DevOps Engineer (AWS / Docker / CI/CD)</option>
                                <option value="Cyber Security Analyst">Cyber Security Analyst (OWASP / Linux / SIEM)</option>
                                <option value="Mobile App Developer">Mobile App Developer (Flutter / React Native)</option>
                            </select>
                            <small class="text-muted">The AI engine will score your resume specifically for this position.</small>
                        </div>

                        <!-- RESUME SOURCE SELECTOR -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary mb-2">Choose Resume Source</label>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <input type="radio" class="btn-check" name="resume_source" id="src_built" value="built_in" checked autocomplete="off" onchange="toggleSource('built')">
                                    <label class="btn btn-outline-primary w-100 py-3 rounded-3 fw-semibold text-center" for="src_built">
                                        <i class="fa-solid fa-file-lines d-block mb-1 fs-5"></i>
                                        My Saved Resume
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" class="btn-check" name="resume_source" id="src_file" value="file" autocomplete="off" onchange="toggleSource('file')">
                                    <label class="btn btn-outline-primary w-100 py-3 rounded-3 fw-semibold text-center" for="src_file">
                                        <i class="fa-solid fa-cloud-arrow-up d-block mb-1 fs-5"></i>
                                        Upload PDF / DOC
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" class="btn-check" name="resume_source" id="src_text" value="text" autocomplete="off" onchange="toggleSource('text')">
                                    <label class="btn btn-outline-primary w-100 py-3 rounded-3 fw-semibold text-center" for="src_text">
                                        <i class="fa-solid fa-align-left d-block mb-1 fs-5"></i>
                                        Paste Text
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 1: SAVED RESUME -->
                        <div id="sec_built" class="mb-4 p-3 bg-light rounded-3 border">
                            <label class="form-label fw-semibold text-secondary">Select Saved Resume</label>
                            <?php if (!empty($existingResumes)): ?>
                                <select name="resume_id" class="form-select">
                                    <?php foreach ($existingResumes as $r): ?>
                                        <option value="<?php echo (int)$r['id']; ?>">
                                            <?php echo htmlspecialchars($r['name']); ?> (<?php echo htmlspecialchars($r['course']); ?>) - Created <?php echo date('M d, Y', strtotime($r['created_at'])); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <p class="text-muted small mb-2">No built resume found in your account.</p>
                                <a href="resume.php" class="btn btn-sm btn-primary">Create a Resume Now</a>
                            <?php endif; ?>
                        </div>

                        <!-- SECTION 2: FILE UPLOAD -->
                        <div id="sec_file" class="mb-4 p-3 bg-light rounded-3 border d-none">
                            <label class="form-label fw-semibold text-secondary">Upload Resume File (PDF, DOCX, TXT - Max 5MB)</label>
                            <input type="file" name="resume_file" class="form-control" accept=".pdf,.doc,.docx,.txt">
                        </div>

                        <!-- SECTION 3: PASTE TEXT -->
                        <div id="sec_text" class="mb-4 p-3 bg-light rounded-3 border d-none">
                            <label class="form-label fw-semibold text-secondary">Paste Resume Content</label>
                            <textarea name="resume_text" class="form-control" rows="6" placeholder="Paste your full resume text here..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-semibold shadow">
                            <i class="fa-solid fa-magnifying-glass-chart me-2"></i> Run AI ATS Compatibility Scan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script>
function toggleSource(type) {
    document.getElementById('sec_built').classList.add('d-none');
    document.getElementById('sec_file').classList.add('d-none');
    document.getElementById('sec_text').classList.add('d-none');

    if (type === 'built') document.getElementById('sec_built').classList.remove('d-none');
    if (type === 'file') document.getElementById('sec_file').classList.remove('d-none');
    if (type === 'text') document.getElementById('sec_text').classList.remove('d-none');
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
