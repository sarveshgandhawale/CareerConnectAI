<?php
require_once __DIR__ . '/../config/db.php';
requireAdmin();

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Handle Delete Student
if (isset($_GET['delete_id'])) {
    $delId = (int)$_GET['delete_id'];
    
    // Fetch student info for audit logging
    $uQ = mysqli_query($conn, "SELECT name, email FROM users WHERE id = $delId");
    $delUser = $uQ ? mysqli_fetch_assoc($uQ) : null;
    $delUserName = $delUser['name'] ?? "User #{$delId}";

    // Delete student related records
    mysqli_query($conn, "DELETE FROM resume_analysis WHERE user_id = $delId");
    mysqli_query($conn, "DELETE FROM resume WHERE user_id = $delId");
    mysqli_query($conn, "DELETE FROM mock_interviews WHERE user_id = $delId");
    mysqli_query($conn, "DELETE FROM career_results WHERE user_id = $delId");
    mysqli_query($conn, "DELETE FROM assessments WHERE user_id = $delId");
    mysqli_query($conn, "DELETE FROM user_activities WHERE user_id = $delId");

    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $delId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        logActivity($conn, null, 'Delete Student', 'Admin Panel', "Admin deleted student {$delUserName} (ID #{$delId}).");
        header("Location: users.php?success=" . urlencode("Student '{$delUserName}' and all related records deleted successfully."));
        exit();
    }
}

// Search & Filter
$search = trim($_GET['search'] ?? '');
$courseFilter = trim($_GET['course'] ?? 'all');

// Global Stats
$totalStudents = 0;
$totalCourses = 0;
$statQ = mysqli_query($conn, "SELECT COUNT(*) as c, COUNT(DISTINCT course) as courses FROM users");
if ($statQ) {
    $row = mysqli_fetch_assoc($statQ);
    $totalStudents = (int)($row['c'] ?? 0);
    $totalCourses = (int)($row['courses'] ?? 0);
}

// Build query
$sql = "SELECT * FROM users WHERE 1=1";
$params = [];
$types = "";

if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR email LIKE ? OR mobile LIKE ? OR address LIKE ?)";
    $like = "%" . $search . "%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= "ssss";
}

if ($courseFilter !== 'all') {
    $sql .= " AND course = ?";
    $params[] = $courseFilter;
    $types .= "s";
}

$sql .= " ORDER BY id DESC";

$stmt = mysqli_prepare($conn, $sql);
if ($stmt && !empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
if ($stmt) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
}

$filteredCount = $result ? mysqli_num_rows($result) : 0;
$isFiltered = !empty($search) || ($courseFilter !== 'all');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Students | Admin Console</title>
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
        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.875rem;
            color: #1e3c72;
            background: #e0e7ff;
            flex-shrink: 0;
        }
        .stat-badge-card {
            border-radius: 14px;
            transition: all 0.2s ease;
        }
        .stat-badge-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }
    </style>
</head>
<body class="bg-light">

<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-5">

    <!-- HEADER SECTION -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1 rounded-pill">
                    <i class="fa-solid fa-users-gear me-1"></i> Admin Directory
                </span>
                <span class="badge bg-secondary-subtle text-secondary fw-semibold rounded-pill">
                    <?php echo $totalStudents; ?> Total Students
                </span>
            </div>
            <h2 class="fw-bold text-dark mb-1">
                Manage Students
            </h2>
            <p class="text-muted mb-0">View student profiles, monitor degrees, inspect activities, and manage student accounts</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="module_activity.php" class="btn btn-outline-primary fw-semibold px-3 py-2 d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Audit Trail</span>
            </a>
            <a href="dashboard.php" class="btn btn-outline-secondary fw-semibold px-3 py-2 d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-gauge"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- NOTIFICATION ALERTS -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center" role="alert">
            <i class="fa-solid fa-circle-check text-success fs-5 me-3"></i>
            <div><?php echo htmlspecialchars($success); ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center" role="alert">
            <i class="fa-solid fa-circle-exclamation text-danger fs-5 me-3"></i>
            <div><?php echo htmlspecialchars($error); ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- KPI CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-4">
            <div class="card stat-badge-card shadow-sm border-0 p-3 bg-white d-flex flex-row align-items-center gap-3">
                <div class="p-3 bg-primary-subtle text-primary rounded-4">
                    <i class="fa-solid fa-users fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0"><?php echo $totalStudents; ?></h3>
                    <small class="text-muted fw-semibold">Total Registered Students</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="card stat-badge-card shadow-sm border-0 p-3 bg-white d-flex flex-row align-items-center gap-3">
                <div class="p-3 bg-success-subtle text-success rounded-4">
                    <i class="fa-solid fa-filter fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0"><?php echo $filteredCount; ?></h3>
                    <small class="text-muted fw-semibold"><?php echo $isFiltered ? 'Filtered Results' : 'Active Students Displayed'; ?></small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="card stat-badge-card shadow-sm border-0 p-3 bg-white d-flex flex-row align-items-center gap-3">
                <div class="p-3 bg-warning-subtle text-warning rounded-4">
                    <i class="fa-solid fa-graduation-cap fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0"><?php echo $totalCourses; ?></h3>
                    <small class="text-muted fw-semibold">Academic Disciplines / Courses</small>
                </div>
            </div>
        </div>
    </div>

    <!-- SEARCH & FILTER FORM -->
    <div class="card shadow-sm border-0 rounded-4 p-3 bg-white mb-4">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, email, mobile or city..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
            </div>
            <div class="col-md-4">
                <select name="course" class="form-select">
                    <option value="all" <?php if ($courseFilter === 'all') echo 'selected'; ?>>All Courses / Degrees</option>
                    <option value="BCA" <?php if ($courseFilter === 'BCA') echo 'selected'; ?>>BCA</option>
                    <option value="MCA" <?php if ($courseFilter === 'MCA') echo 'selected'; ?>>MCA</option>
                    <option value="B.Tech / BE" <?php if ($courseFilter === 'B.Tech / BE') echo 'selected'; ?>>B.Tech / B.E.</option>
                    <option value="BSc IT / CS" <?php if ($courseFilter === 'BSc IT / CS') echo 'selected'; ?>>B.Sc IT / CS</option>
                    <option value="BBA" <?php if ($courseFilter === 'BBA') echo 'selected'; ?>>BBA</option>
                    <option value="MBA" <?php if ($courseFilter === 'MBA') echo 'selected'; ?>>MBA</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-semibold d-inline-flex align-items-center justify-content-center gap-1">
                    <i class="fa-solid fa-filter"></i> Apply Filter
                </button>
                <?php if ($isFiltered): ?>
                    <a href="users.php" class="btn btn-outline-secondary fw-semibold" title="Reset Search & Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- STUDENTS TABLE CARD -->
    <div class="card shadow-sm border-0 rounded-4 bg-white overflow-hidden">
        <div class="card-header bg-white py-3 px-4 border-0 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="fa-solid fa-list text-primary"></i>
                <span>Registered Students Directory</span>
            </h5>
            <span class="text-muted small">
                Showing <strong><?php echo $filteredCount; ?></strong> of <strong><?php echo $totalStudents; ?></strong> students
            </span>
        </div>
        <div class="card-body p-0">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Student</th>
                                <th>Contact Info</th>
                                <th>Course</th>
                                <th>Gender</th>
                                <th>Location</th>
                                <th>Joined</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($st = mysqli_fetch_assoc($result)): 
                                $initials = strtoupper(substr($st['name'], 0, 1));
                                $courseBadgeClass = match($st['course'] ?? '') {
                                    'BCA' => 'bg-primary-subtle text-primary',
                                    'MCA' => 'bg-info-subtle text-info',
                                    'B.Tech / BE' => 'bg-success-subtle text-success',
                                    'BSc IT / CS' => 'bg-warning-subtle text-dark',
                                    'MBA' => 'bg-danger-subtle text-danger',
                                    default => 'bg-secondary-subtle text-secondary'
                                };
                            ?>
                                <tr>
                                    <td class="ps-4 text-muted small fw-semibold">#<?php echo (int)$st['id']; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="student-avatar shadow-sm">
                                                <?php echo htmlspecialchars($initials); ?>
                                            </div>
                                            <div>
                                                <strong class="text-dark d-block"><?php echo htmlspecialchars($st['name']); ?></strong>
                                                <small class="text-muted"><?php echo htmlspecialchars($st['email']); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-dark small d-block">
                                            <i class="fa-solid fa-phone text-muted me-1 small"></i>
                                            <?php echo htmlspecialchars($st['mobile'] ?: 'Not Provided'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $courseBadgeClass; ?> px-2.5 py-1 fw-semibold rounded-pill">
                                            <?php echo htmlspecialchars($st['course'] ?: 'N/A'); ?>
                                        </span>
                                    </td>
                                    <td><span class="text-muted small"><?php echo htmlspecialchars($st['gender'] ?: '—'); ?></span></td>
                                    <td>
                                        <span class="text-muted small text-truncate d-inline-block" style="max-width: 140px;" title="<?php echo htmlspecialchars($st['address']); ?>">
                                            <?php echo htmlspecialchars($st['address'] ?: '—'); ?>
                                        </span>
                                    </td>
                                    <td><span class="text-muted small"><?php echo date('M d, Y', strtotime($st['created_at'])); ?></span></td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group" role="group">
                                            <!-- View Details Modal Trigger -->
                                            <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#studentModal"
                                                    data-id="<?php echo (int)$st['id']; ?>"
                                                    data-name="<?php echo htmlspecialchars($st['name']); ?>"
                                                    data-email="<?php echo htmlspecialchars($st['email']); ?>"
                                                    data-mobile="<?php echo htmlspecialchars($st['mobile'] ?: 'Not Provided'); ?>"
                                                    data-course="<?php echo htmlspecialchars($st['course'] ?: 'Not Specified'); ?>"
                                                    data-gender="<?php echo htmlspecialchars($st['gender'] ?: 'Not Specified'); ?>"
                                                    data-address="<?php echo htmlspecialchars($st['address'] ?: 'Not Provided'); ?>"
                                                    data-joined="<?php echo date('M d, Y - h:i A', strtotime($st['created_at'])); ?>"
                                                    title="View Full Profile">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>

                                            <!-- Direct Audit Trail link -->
                                            <a href="module_activity.php?user_id=<?php echo (int)$st['id']; ?>" class="btn btn-outline-primary btn-sm" title="View Audit Trail for this Student">
                                                <i class="fa-solid fa-clock-rotate-left"></i>
                                            </a>

                                            <!-- Delete Student -->
                                            <a href="users.php?delete_id=<?php echo (int)$st['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars(addslashes($st['name'])); ?>? This will also remove all their assessment, resume, and interview history.');" title="Delete Student">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="p-3 bg-light rounded-circle d-inline-block mb-3">
                        <i class="fa-solid fa-user-slash fa-3x text-muted"></i>
                    </div>
                    <h5 class="fw-bold text-dark">No students found</h5>
                    <p class="text-muted mb-3">No student records matched your current filter criteria.</p>
                    <a href="users.php" class="btn btn-primary btn-sm fw-semibold">Clear Filters</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- STUDENT DETAILS MODAL -->
<div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="studentModalLabel">
                    <i class="fa-solid fa-id-card text-primary me-2"></i> Student Profile
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="text-center mb-4">
                    <div id="mAvatar" class="student-avatar mx-auto mb-2 shadow" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        U
                    </div>
                    <h4 class="fw-bold text-dark mb-0" id="mName">Student Name</h4>
                    <span class="text-muted small" id="mEmail">student@example.com</span>
                </div>

                <div class="list-group list-group-flush rounded-3 border">
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2.5">
                        <span class="text-muted small fw-semibold"><i class="fa-solid fa-hashtag me-2 text-secondary"></i> Student ID</span>
                        <span class="fw-bold text-dark" id="mId">#1</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2.5">
                        <span class="text-muted small fw-semibold"><i class="fa-solid fa-graduation-cap me-2 text-secondary"></i> Course</span>
                        <span class="badge bg-primary-subtle text-primary fw-bold" id="mCourse">BCA</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2.5">
                        <span class="text-muted small fw-semibold"><i class="fa-solid fa-phone me-2 text-secondary"></i> Phone</span>
                        <span class="fw-semibold text-dark" id="mMobile">9876543210</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2.5">
                        <span class="text-muted small fw-semibold"><i class="fa-solid fa-venus-mars me-2 text-secondary"></i> Gender</span>
                        <span class="fw-semibold text-dark" id="mGender">Male</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2.5">
                        <span class="text-muted small fw-semibold"><i class="fa-solid fa-location-dot me-2 text-secondary"></i> Address</span>
                        <span class="fw-semibold text-dark text-end" id="mAddress">Mumbai</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2.5">
                        <span class="text-muted small fw-semibold"><i class="fa-solid fa-calendar me-2 text-secondary"></i> Registered On</span>
                        <span class="fw-semibold text-dark" id="mJoined">Jan 01, 2025</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 d-flex justify-content-between">
                <a id="mAuditLink" href="#" class="btn btn-outline-primary fw-semibold d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left"></i> View Student Audit Trail
                </a>
                <button type="button" class="btn btn-secondary fw-semibold" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentModal = document.getElementById('studentModal');
    if (studentModal) {
        studentModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const email = button.getAttribute('data-email');
            const mobile = button.getAttribute('data-mobile');
            const course = button.getAttribute('data-course');
            const gender = button.getAttribute('data-gender');
            const address = button.getAttribute('data-address');
            const joined = button.getAttribute('data-joined');

            document.getElementById('mId').textContent = '#' + id;
            document.getElementById('mName').textContent = name;
            document.getElementById('mEmail').textContent = email;
            document.getElementById('mMobile').textContent = mobile;
            document.getElementById('mCourse').textContent = course;
            document.getElementById('mGender').textContent = gender;
            document.getElementById('mAddress').textContent = address;
            document.getElementById('mJoined').textContent = joined;
            document.getElementById('mAvatar').textContent = name.charAt(0).toUpperCase();

            document.getElementById('mAuditLink').href = 'module_activity.php?user_id=' + encodeURIComponent(id);
        });
    }
});
</script>
</body>
</html>
<?php if ($stmt) mysqli_stmt_close($stmt); ?>
