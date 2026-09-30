<?php
require_once __DIR__ . '/../config/db.php';
requireLogin();

$userName = htmlspecialchars($_SESSION['name'] ?? 'Student');
$userCourse = htmlspecialchars($_SESSION['course'] ?? 'BCA');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AI Career Guidance &amp; Assessment | CareerConnect AI</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/career.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/nav.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/footer.css'); ?>">
</head>
<body class="bg-light">

<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden bg-white">
                
                <!-- HEADER -->
                <div class="card-header bg-primary text-white text-center p-4" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2">
                        <i class="fa-solid fa-sparkles me-1"></i> Powered by Google Gemini AI
                    </span>
                    <h2 class="fw-bold mb-1">
                        <i class="fa-solid fa-robot me-2"></i> AI Career Guidance &amp; Roadmap
                    </h2>
                    <p class="text-white-50 mb-0">
                        Answer 6 quick questions to discover your ideal career path, match score, and 6-month roadmap.
                    </p>
                </div>

                <!-- FORM -->
                <div class="card-body p-4 p-md-5">
                    <form action="career_result.php" method="POST" id="careerForm">

                        <div class="row g-3">
                            <!-- FULL NAME -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fa-solid fa-user text-primary me-1"></i> Full Name
                                </label>
                                <input type="text" class="form-control" name="name" value="<?php echo $userName; ?>" required>
                            </div>

                            <!-- COURSE -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fa-solid fa-graduation-cap text-primary me-1"></i> Academic Degree / Course
                                </label>
                                <select class="form-select" name="course" required>
                                    <option value="BCA" <?php if ($userCourse === "BCA") echo "selected"; ?>>BCA (Computer Applications)</option>
                                    <option value="MCA" <?php if ($userCourse === "MCA") echo "selected"; ?>>MCA (Master of Computer Applications)</option>
                                    <option value="B.Tech / BE" <?php if ($userCourse === "B.Tech / BE") echo "selected"; ?>>B.Tech / B.E. (Computer Science / IT)</option>
                                    <option value="BSc IT / CS" <?php if ($userCourse === "BSc IT / CS") echo "selected"; ?>>B.Sc (IT / Computer Science)</option>
                                    <option value="BBA" <?php if ($userCourse === "BBA") echo "selected"; ?>>BBA</option>
                                    <option value="MBA" <?php if ($userCourse === "MBA") echo "selected"; ?>>MBA</option>
                                    <option value="Other" <?php if ($userCourse === "Other") echo "selected"; ?>>Other</option>
                                </select>
                            </div>

                            <!-- PROGRAMMING LEVEL -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fa-solid fa-code text-primary me-1"></i> Current Programming Skill Level
                                </label>
                                <select class="form-select" name="programming" required>
                                    <option value="Beginner">Beginner (Basic syntax & loops)</option>
                                    <option value="Intermediate" selected>Intermediate (OOP, Functions, Databases)</option>
                                    <option value="Advanced">Advanced (Data Structures, Architecture, Frameworks)</option>
                                </select>
                            </div>

                            <!-- FAVORITE FIELD -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fa-solid fa-layer-group text-primary me-1"></i> Preferred Field / Interest
                                </label>
                                <select class="form-select" name="interest" required>
                                    <option value="Web Development" selected>Full Stack Web Development</option>
                                    <option value="Artificial Intelligence">Artificial Intelligence &amp; Machine Learning</option>
                                    <option value="Data Science">Data Science &amp; Analytics</option>
                                    <option value="Cyber Security">Cyber Security &amp; Ethical Hacking</option>
                                    <option value="Cloud Computing">Cloud Computing &amp; DevOps</option>
                                    <option value="Mobile App Development">Mobile App Development</option>
                                </select>
                            </div>

                            <!-- COMMUNICATION SKILL -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fa-solid fa-comments text-primary me-1"></i> Communication &amp; Soft Skills
                                </label>
                                <select class="form-select" name="communication" required>
                                    <option value="Beginner">Developing (Prefer written communication)</option>
                                    <option value="Intermediate" selected>Good (Confident in group discussions)</option>
                                    <option value="Excellent">Excellent (Strong presentation &amp; leadership)</option>
                                </select>
                            </div>

                            <!-- PROBLEM SOLVING -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary">
                                    <i class="fa-solid fa-brain text-primary me-1"></i> Problem Solving &amp; Logic
                                </label>
                                <select class="form-select" name="problem" required>
                                    <option value="Low">Low (Need step-by-step guidance)</option>
                                    <option value="Medium" selected>Medium (Can debug issues with research)</option>
                                    <option value="High">High (Enjoy complex algorithmic puzzles)</option>
                                </select>
                            </div>
                        </div>

                        <div class="text-center mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3 fw-semibold shadow" id="submitBtn">
                                <i class="fa-solid fa-wand-magic-sparkles me-2"></i> Generate AI Recommendation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo asset('js/career.js'); ?>"></script>
</body>
</html>