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
    <title>Skill &amp; Aptitude Assessment | CareerConnect AI</title>
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
    <style>
        .question-card {
            border-left: 4px solid #1e3c72;
            transition: all 0.2s ease;
        }
        .question-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.06);
        }
        .option-label {
            display: block;
            padding: 12px 18px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .option-label:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
        }
        .form-check-input:checked + .option-text {
            color: #1e3c72;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-light">

<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-5">

    <!-- HERO HEADER -->
    <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5 bg-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2">
                    <i class="fa-solid fa-sparkles me-1"></i> Phase 1: Skill Diagnostic
                </span>
                <h1 class="fw-bold display-6 text-dark mb-2">
                    <i class="fa-solid fa-list-check text-primary me-2"></i> Student Skill &amp; Aptitude Assessment
                </h1>
                <p class="lead text-muted mb-0">
                    Evaluate your technical foundations, logical problem solving, and soft skills to unlock personalized AI career recommendations.
                </p>
            </div>
            <div class="col-lg-4 text-center text-lg-end mt-3 mt-lg-0">
                <div class="p-3 bg-light rounded-4 border d-inline-block text-center shadow-sm">
                    <span class="text-muted small fw-semibold d-block">TIME REMAINING</span>
                    <h2 class="fw-bold text-danger mb-0" id="timerDisplay">15:00</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- ASSESSMENT FORM -->
    <form action="assessment_process.php" method="POST" id="assessmentForm">

        <!-- DOMAIN SELECTION -->
        <div class="card shadow-sm border-0 rounded-4 p-4 bg-white mb-4">
            <div class="row align-items-center">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label fw-bold text-dark">
                        <i class="fa-solid fa-layer-group text-primary me-1"></i> Select Assessment Track
                    </label>
                    <select name="domain" id="domainSelect" class="form-select form-select-lg" onchange="switchDomain(this.value)">
                        <option value="Web Development" selected>Full Stack Web Development &amp; PHP</option>
                        <option value="AI & Machine Learning">Python, Data Science &amp; AI / ML</option>
                        <option value="Core CS & Algorithms">Core Computer Science &amp; DSA</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="form-label fw-semibold text-secondary">Student Name</label>
                    <input type="text" name="student_name" class="form-control" value="<?php echo $userName; ?>" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary">Course</label>
                    <input type="text" name="course" class="form-control" value="<?php echo $userCourse; ?>" readonly>
                </div>
            </div>
        </div>

        <!-- QUESTIONS CONTAINER (10 Questions) -->
        <div id="questionsContainer">

            <!-- SECTION A: TECHNICAL (Q1-Q4) -->
            <div class="d-flex align-items-center gap-2 mb-3 mt-4">
                <span class="badge bg-primary px-3 py-2 fs-6">Section A: Technical Core (4 Questions)</span>
            </div>

            <!-- Q1 -->
            <div class="card question-card shadow-sm border-0 rounded-4 p-4 bg-white mb-3">
                <h5 class="fw-bold text-dark mb-3">1. Which HTTP request method is idempotent and primarily used to fetch data from a server without side effects?</h5>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q1" value="GET" required>
                            <span class="option-text">A) GET</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q1" value="POST">
                            <span class="option-text">B) POST</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q1" value="PATCH">
                            <span class="option-text">C) PATCH</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q1" value="CONNECT">
                            <span class="option-text">D) CONNECT</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Q2 -->
            <div class="card question-card shadow-sm border-0 rounded-4 p-4 bg-white mb-3">
                <h5 class="fw-bold text-dark mb-3">2. In relational databases (MySQL), which SQL clause is used to filter records resulting from a GROUP BY aggregation?</h5>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q2" value="WHERE" required>
                            <span class="option-text">A) WHERE</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q2" value="HAVING">
                            <span class="option-text">B) HAVING</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q2" value="ORDER BY">
                            <span class="option-text">C) ORDER BY</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q2" value="DISTINCT">
                            <span class="option-text">D) DISTINCT</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Q3 -->
            <div class="card question-card shadow-sm border-0 rounded-4 p-4 bg-white mb-3">
                <h5 class="fw-bold text-dark mb-3">3. What is the main security purpose of using Prepared Statements with parameterized queries in PHP / MySQL?</h5>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q3" value="SQL Injection" required>
                            <span class="option-text">A) Complete prevention against SQL Injection attacks</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q3" value="Encrypt DB">
                            <span class="option-text">B) Automatic database file encryption</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q3" value="Faster CSS">
                            <span class="option-text">C) Accelerating frontend CSS loading</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q3" value="Bypass Auth">
                            <span class="option-text">D) Bypassing user authentication checks</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Q4 -->
            <div class="card question-card shadow-sm border-0 rounded-4 p-4 bg-white mb-3">
                <h5 class="fw-bold text-dark mb-3">4. In Object-Oriented Programming (OOP), what is the concept of bundling data and methods that operate on that data into a single unit while restricting direct access?</h5>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q4" value="Encapsulation" required>
                            <span class="option-text">A) Encapsulation</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q4" value="Polymorphism">
                            <span class="option-text">B) Polymorphism</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q4" value="Inheritance">
                            <span class="option-text">C) Inheritance</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q4" value="Compilation">
                            <span class="option-text">D) Compilation</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- SECTION B: PROBLEM SOLVING & LOGIC (Q5-Q7) -->
            <div class="d-flex align-items-center gap-2 mb-3 mt-4">
                <span class="badge bg-success px-3 py-2 fs-6">Section B: Problem Solving &amp; Algorithmic Logic (3 Questions)</span>
            </div>

            <!-- Q5 -->
            <div class="card question-card shadow-sm border-0 rounded-4 p-4 bg-white mb-3">
                <h5 class="fw-bold text-dark mb-3">5. What is the average time complexity of searching an element in a balanced Binary Search Tree (BST) with N elements?</h5>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q5" value="O(log N)" required>
                            <span class="option-text">A) O(log N)</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q5" value="O(N)">
                            <span class="option-text">B) O(N)</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q5" value="O(N^2)">
                            <span class="option-text">C) O(N²)</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q5" value="O(1)">
                            <span class="option-text">D) O(1)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Q6 -->
            <div class="card question-card shadow-sm border-0 rounded-4 p-4 bg-white mb-3">
                <h5 class="fw-bold text-dark mb-3">6. If an algorithm takes 2 seconds to process 1,000 items and has O(N²) quadratic complexity, approximately how long will it take for 2,000 items?</h5>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q6" value="8 seconds" required>
                            <span class="option-text">A) ~8 seconds (4x the time)</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q6" value="4 seconds">
                            <span class="option-text">B) ~4 seconds (2x the time)</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q6" value="16 seconds">
                            <span class="option-text">C) ~16 seconds</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q6" value="2 seconds">
                            <span class="option-text">D) ~2 seconds</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Q7 -->
            <div class="card question-card shadow-sm border-0 rounded-4 p-4 bg-white mb-3">
                <h5 class="fw-bold text-dark mb-3">7. When troubleshooting a 500 Internal Server Error in a web application, what is the best first step?</h5>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q7" value="Server Logs" required>
                            <span class="option-text">A) Inspect server / PHP error logs for the stack trace</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q7" value="Delete DB">
                            <span class="option-text">B) Delete and reinstall MySQL database</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q7" value="Clear Cache">
                            <span class="option-text">C) Restart client browser and clear cookies</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q7" value="Change Port">
                            <span class="option-text">D) Change web server port to 443</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- SECTION C: COMMUNICATION & SOFT SKILLS (Q8-Q10) -->
            <div class="d-flex align-items-center gap-2 mb-3 mt-4">
                <span class="badge bg-warning text-dark px-3 py-2 fs-6">Section C: Professional Soft Skills &amp; Agile (3 Questions)</span>
            </div>

            <!-- Q8 -->
            <div class="card question-card shadow-sm border-0 rounded-4 p-4 bg-white mb-3">
                <h5 class="fw-bold text-dark mb-3">8. What does the "STAR" interview technique stand for when answering behavioral questions?</h5>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q8" value="Situation Task Action Result" required>
                            <span class="option-text">A) Situation, Task, Action, Result</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q8" value="Software Testing And Review">
                            <span class="option-text">B) Software Testing And Review</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q8" value="Strategy Team Analytics Report">
                            <span class="option-text">C) Strategy, Team, Analytics, Report</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q8" value="Speed Target Accuracy Quality">
                            <span class="option-text">D) Speed, Target, Accuracy, Quality</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Q9 -->
            <div class="card question-card shadow-sm border-0 rounded-4 p-4 bg-white mb-3">
                <h5 class="fw-bold text-dark mb-3">9. In an Agile software development team, what is the primary purpose of the Daily Standup meeting?</h5>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q9" value="Sync Progress" required>
                            <span class="option-text">A) Quickly synchronize progress, blockers, and plan for the day</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q9" value="Annual Review">
                            <span class="option-text">B) Conduct annual developer performance evaluations</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q9" value="Write Documentation">
                            <span class="option-text">C) Write exhaustive product requirement manuals</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q9" value="Fix Bugs">
                            <span class="option-text">D) Code review all git commits line by line</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Q10 -->
            <div class="card question-card shadow-sm border-0 rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold text-dark mb-3">10. If you encounter a blocking dependency in a team sprint with a tight deadline, what is the most professional action?</h5>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q10" value="Proactive Communication" required>
                            <span class="option-text">A) Immediately notify the lead/team with proposed alternatives</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q10" value="Wait Silently">
                            <span class="option-text">B) Wait silently until sprint review demo day</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q10" value="Blame Others">
                            <span class="option-text">C) Blame the other developer in public channels</span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="option-label">
                            <input type="radio" class="form-check-input me-2" name="q10" value="Skip Testing">
                            <span class="option-text">D) Skip all testing and push unverified code to production</span>
                        </label>
                    </div>
                </div>
            </div>

        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg px-5 py-3 fw-semibold shadow">
                <i class="fa-solid fa-paper-plane me-2"></i> Submit Assessment for AI Scoring
            </button>
        </div>
    </form>

</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script>
// 15 Minutes Countdown Timer
let timeLeft = 15 * 60;
const timerDisplay = document.getElementById('timerDisplay');

const timerInterval = setInterval(function() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    timerDisplay.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    
    if (timeLeft <= 0) {
        clearInterval(timerInterval);
        alert('Time is up! Your assessment will be submitted automatically.');
        document.getElementById('assessmentForm').submit();
    }
    timeLeft--;
}, 1000);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
