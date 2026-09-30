<?php
require_once __DIR__ . '/../config/db.php';
requireAdmin();

$adminName = htmlspecialchars($_SESSION['admin_name'] ?? 'Administrator');

// Compute System Stats
$userCount = 0;
$resumeCount = 0;
$careerCount = 0;
$interviewCount = 0;
$assessmentCount = 0;
$feedbackCount = 0;
$contactCount = 0;

$q = mysqli_query($conn, "SELECT COUNT(*) as c FROM users");
if ($q) $userCount = (int)(mysqli_fetch_assoc($q)['c'] ?? 0);

$q = mysqli_query($conn, "SELECT COUNT(*) as c FROM resume");
if ($q) $resumeCount = (int)(mysqli_fetch_assoc($q)['c'] ?? 0);

$q = mysqli_query($conn, "SELECT COUNT(*) as c FROM career_results");
if ($q) $careerCount = (int)(mysqli_fetch_assoc($q)['c'] ?? 0);

$q = mysqli_query($conn, "SELECT COUNT(*) as c FROM mock_interviews");
if ($q) $interviewCount = (int)(mysqli_fetch_assoc($q)['c'] ?? 0);

$q = mysqli_query($conn, "SELECT COUNT(*) as c FROM assessments");
if ($q) $assessmentCount = (int)(mysqli_fetch_assoc($q)['c'] ?? 0);

$q = mysqli_query($conn, "SELECT COUNT(*) as c FROM feedback");
if ($q) $feedbackCount = (int)(mysqli_fetch_assoc($q)['c'] ?? 0);

$q = mysqli_query($conn, "SELECT COUNT(*) as c FROM contact_messages");
if ($q) $contactCount = (int)(mysqli_fetch_assoc($q)['c'] ?? 0);

// Course Breakdown for Chart
$courseStats = [];
$cQ = mysqli_query($conn, "SELECT course, COUNT(*) as c FROM users GROUP BY course");
if ($cQ) {
    while ($r = mysqli_fetch_assoc($cQ)) {
        $courseStats[$r['course']] = (int)$r['c'];
    }
}

// Recent Students
$recentStudents = [];
$sQ = mysqli_query($conn, "SELECT id, name, email, course, mobile, created_at FROM users ORDER BY id DESC LIMIT 5");
if ($sQ) {
    while ($s = mysqli_fetch_assoc($sQ)) {
        $recentStudents[] = $s;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Console Dashboard | CareerConnect AI</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/nav.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/footer.css'); ?>">
    <style>
        .admin-kpi {
            border-radius: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .admin-kpi:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body class="bg-light">

<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-5">

    <!-- ADMIN HERO -->
    <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5 text-white mb-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2">
                    <i class="fa-solid fa-shield-halved me-1"></i> System Administration
                </span>
                <h1 class="fw-bold display-6 text-white mb-2">
                    Welcome back, <span class="text-warning"><?php echo $adminName; ?></span>
                </h1>
                <p class="lead text-white-50 mb-0">
                    Comprehensive overview of student activity, resume scans, mock interview sessions, and feedback.
                </p>
            </div>
            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-inline-flex flex-wrap justify-content-lg-end gap-2">
                    <a href="users.php" class="btn btn-warning fw-semibold px-3 py-2 text-dark shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-users"></i>
                        <span>Manage Students</span>
                    </a>
                    <a href="module_activity.php" class="btn btn-outline-light fw-semibold px-3 py-2 shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Audit Trail</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 6 KPI SUMMARY CARDS -->
    <div class="row g-3 mb-4">
        <!-- 1. STUDENTS -->
        <div class="col-6 col-lg-2">
            <div class="card admin-kpi shadow-sm border-0 p-3 bg-white text-center">
                <div class="p-2 bg-primary-subtle text-primary rounded-circle d-inline-block mx-auto mb-2" style="width: 45px; height: 45px;">
                    <i class="fa-solid fa-users fs-5"></i>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?php echo $userCount; ?></h3>
                <small class="text-muted fw-semibold">Students</small>
            </div>
        </div>

        <!-- 2. ASSESSMENTS -->
        <div class="col-6 col-lg-2">
            <div class="card admin-kpi shadow-sm border-0 p-3 bg-white text-center">
                <div class="p-2 bg-info-subtle text-info rounded-circle d-inline-block mx-auto mb-2" style="width: 45px; height: 45px;">
                    <i class="fa-solid fa-list-check fs-5"></i>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?php echo $assessmentCount; ?></h3>
                <small class="text-muted fw-semibold">Skill Tests</small>
            </div>
        </div>

        <!-- 3. CAREER GUIDANCE -->
        <div class="col-6 col-lg-2">
            <div class="card admin-kpi shadow-sm border-0 p-3 bg-white text-center">
                <div class="p-2 bg-warning-subtle text-warning rounded-circle d-inline-block mx-auto mb-2" style="width: 45px; height: 45px;">
                    <i class="fa-solid fa-compass fs-5"></i>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?php echo $careerCount; ?></h3>
                <small class="text-muted fw-semibold">Career Plans</small>
            </div>
        </div>

        <!-- 4. RESUMES -->
        <div class="col-6 col-lg-2">
            <div class="card admin-kpi shadow-sm border-0 p-3 bg-white text-center">
                <div class="p-2 bg-success-subtle text-success rounded-circle d-inline-block mx-auto mb-2" style="width: 45px; height: 45px;">
                    <i class="fa-solid fa-file-lines fs-5"></i>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?php echo $resumeCount; ?></h3>
                <small class="text-muted fw-semibold">Resumes</small>
            </div>
        </div>

        <!-- 5. INTERVIEWS -->
        <div class="col-6 col-lg-2">
            <div class="card admin-kpi shadow-sm border-0 p-3 bg-white text-center">
                <div class="p-2 bg-danger-subtle text-danger rounded-circle d-inline-block mx-auto mb-2" style="width: 45px; height: 45px;">
                    <i class="fa-solid fa-microphone fs-5"></i>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?php echo $interviewCount; ?></h3>
                <small class="text-muted fw-semibold">Interviews</small>
            </div>
        </div>

        <!-- 6. FEEDBACK / INQUIRIES -->
        <div class="col-6 col-lg-2">
            <div class="card admin-kpi shadow-sm border-0 p-3 bg-white text-center">
                <div class="p-2 bg-secondary-subtle text-dark rounded-circle d-inline-block mx-auto mb-2" style="width: 45px; height: 45px;">
                    <i class="fa-solid fa-star fs-5 text-warning"></i>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?php echo $feedbackCount + $contactCount; ?></h3>
                <small class="text-muted fw-semibold">Feedback &amp; Inq</small>
            </div>
        </div>
    </div>

    <!-- CHARTS & QUICK MODULE ACTIONS -->
    <div class="row g-4 mb-4">
        <!-- COURSE DISTRIBUTION CHART -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <h5 class="fw-bold text-dark mb-3">
                    <i class="fa-solid fa-chart-pie text-primary me-2"></i> Student Course Distribution
                </h5>
                <div style="position: relative; height: 260px;">
                    <canvas id="courseChart"></canvas>
                </div>
            </div>
        </div>

        <!-- ADMIN MODULE NAVIGATION -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <h5 class="fw-bold text-dark mb-3">
                    <i class="fa-solid fa-screwdriver-wrench text-primary me-2"></i> Administrative Operations
                </h5>

                <div class="row g-3">
                    <div class="col-6">
                        <a href="users.php" class="p-3 bg-light rounded-3 text-decoration-none d-block border hover-shadow">
                            <i class="fa-solid fa-users text-primary fs-4 mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1">Student Directory</h6>
                            <small class="text-muted">View, search, edit &amp; delete</small>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="feedback.php" class="p-3 bg-light rounded-3 text-decoration-none d-block border hover-shadow">
                            <i class="fa-solid fa-star text-warning fs-4 mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1">Student Reviews</h6>
                            <small class="text-muted">Manage ratings &amp; comments</small>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="contact.php" class="p-3 bg-light rounded-3 text-decoration-none d-block border hover-shadow">
                            <i class="fa-solid fa-envelope text-info fs-4 mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1">Inquiry Inbox</h6>
                            <small class="text-muted">Support tickets &amp; inquiries</small>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="module_activity.php" class="p-3 bg-light rounded-3 text-decoration-none d-block border hover-shadow">
                            <i class="fa-solid fa-clock-rotate-left text-success fs-4 mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1">Global Audit Logs</h6>
                            <small class="text-muted">Real-time system events</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RECENTLY REGISTERED STUDENTS TABLE -->
    <div class="card shadow-sm border-0 rounded-4 p-4 bg-white">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark mb-0">
                <i class="fa-solid fa-user-plus text-primary me-2"></i> Recently Registered Students
            </h5>
            <a href="users.php" class="btn btn-sm btn-outline-primary fw-semibold">
                View All <?php echo $userCount; ?> Students
            </a>
        </div>

        <?php if (!empty($recentStudents)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email Address</th>
                            <th>Mobile</th>
                            <th>Degree / Course</th>
                            <th>Registered On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentStudents as $st): ?>
                            <tr>
                                <td><span class="text-muted small">#<?php echo (int)$st['id']; ?></span></td>
                                <td><strong><?php echo htmlspecialchars($st['name']); ?></strong></td>
                                <td><span class="text-muted"><?php echo htmlspecialchars($st['email']); ?></span></td>
                                <td><span class="text-secondary"><?php echo htmlspecialchars($st['mobile']); ?></span></td>
                                <td><span class="badge bg-primary-subtle text-primary px-3 py-1"><?php echo htmlspecialchars($st['course']); ?></span></td>
                                <td><span class="text-muted small"><?php echo date('M d, Y', strtotime($st['created_at'])); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted text-center py-4 mb-0">No students registered yet.</p>
        <?php endif; ?>
    </div>

</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('courseChart').getContext('2d');
    const courseLabels = <?php echo json_encode(array_keys($courseStats)); ?>;
    const courseData = <?php echo json_encode(array_values($courseStats)); ?>;

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: courseLabels.length > 0 ? courseLabels : ['BCA', 'MCA', 'B.Tech', 'BSc IT'],
            datasets: [{
                data: courseData.length > 0 ? courseData : [4, 3, 2, 1],
                backgroundColor: [
                    '#1e3c72',
                    '#2a5298',
                    '#3b82f6',
                    '#06b6d4',
                    '#10b981',
                    '#f59e0b'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right' }
            }
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
