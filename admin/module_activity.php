<?php
require_once __DIR__ . '/../config/db.php';
requireAdmin();

$moduleFilter = trim($_GET['module'] ?? 'all');
$userIdFilter = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$search = trim($_GET['search'] ?? '');

// If filtering by a specific student, fetch their name
$filteredStudent = null;
if ($userIdFilter > 0) {
    $uQ = mysqli_query($conn, "SELECT id, name, email, course FROM users WHERE id = $userIdFilter");
    if ($uQ && mysqli_num_rows($uQ) > 0) {
        $filteredStudent = mysqli_fetch_assoc($uQ);
    }
}

// Compute Audit Trail KPI metrics
$totalLogsCount = 0;
$authLogsCount = 0;
$assessmentLogsCount = 0;
$aiLogsCount = 0;

$kpiQ = mysqli_query($conn, "
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN module = 'Authentication' THEN 1 ELSE 0 END) as auth_cnt,
        SUM(CASE WHEN module = 'Assessment' THEN 1 ELSE 0 END) as assess_cnt,
        SUM(CASE WHEN module IN ('Resume', 'Interview') THEN 1 ELSE 0 END) as ai_cnt
    FROM user_activities
");
if ($kpiQ) {
    $kpiRow = mysqli_fetch_assoc($kpiQ);
    $totalLogsCount = (int)($kpiRow['total'] ?? 0);
    $authLogsCount = (int)($kpiRow['auth_cnt'] ?? 0);
    $assessmentLogsCount = (int)($kpiRow['assess_cnt'] ?? 0);
    $aiLogsCount = (int)($kpiRow['ai_cnt'] ?? 0);
}

// Build query
$whereClauses = [];
$params = [];
$types = "";

if ($moduleFilter !== 'all' && !empty($moduleFilter)) {
    $whereClauses[] = "ua.module = ?";
    $params[] = $moduleFilter;
    $types .= "s";
}

if ($userIdFilter > 0) {
    $whereClauses[] = "ua.user_id = ?";
    $params[] = $userIdFilter;
    $types .= "i";
}

if (!empty($search)) {
    $whereClauses[] = "(u.name LIKE ? OR u.email LIKE ? OR ua.action LIKE ? OR ua.details LIKE ? OR ua.ip_address LIKE ?)";
    $like = "%" . $search . "%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= "sssss";
}

$whereSql = !empty($whereClauses) ? " WHERE " . implode(" AND ", $whereClauses) : "";

// CSV EXPORT
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $exportSql = "SELECT ua.*, u.name as user_name, u.email as user_email 
                  FROM user_activities ua 
                  LEFT JOIN users u ON ua.user_id = u.id" . $whereSql . " ORDER BY ua.id DESC LIMIT 1000";

    $stmtExp = mysqli_prepare($conn, $exportSql);
    if ($stmtExp && !empty($params)) {
        mysqli_stmt_bind_param($stmtExp, $types, ...$params);
    }
    if ($stmtExp) {
        mysqli_stmt_execute($stmtExp);
        $expResult = mysqli_stmt_get_result($stmtExp);
    } else {
        $expResult = mysqli_query($conn, $exportSql);
    }

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=audit_trail_export_' . date('Y-m-d_His') . '.csv');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Log ID', 'Timestamp', 'Student ID', 'Student Name', 'Student Email', 'Module', 'Action', 'Details', 'IP Address']);

    if ($expResult) {
        while ($row = mysqli_fetch_assoc($expResult)) {
            fputcsv($out, [
                $row['id'],
                $row['created_at'],
                $row['user_id'] ?? 'System',
                $row['user_name'] ?? 'System / Admin',
                $row['user_email'] ?? 'N/A',
                $row['module'],
                $row['action'],
                $row['details'] ?? '',
                $row['ip_address'] ?? '127.0.0.1'
            ]);
        }
    }
    fclose($out);
    exit();
}

// Fetch logs for display
$sql = "SELECT ua.*, u.name as user_name, u.email as user_email 
        FROM user_activities ua 
        LEFT JOIN users u ON ua.user_id = u.id" . $whereSql . " ORDER BY ua.id DESC LIMIT 150";

$stmt = mysqli_prepare($conn, $sql);
if ($stmt && !empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
if ($stmt) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($conn, $sql);
}

$filteredLogsCount = $result ? mysqli_num_rows($result) : 0;
$isFiltered = ($moduleFilter !== 'all') || ($userIdFilter > 0) || !empty($search);

// Helper for export URL preserving current filters
$exportUrl = "module_activity.php?export=csv";
if ($moduleFilter !== 'all') $exportUrl .= "&module=" . urlencode($moduleFilter);
if ($userIdFilter > 0) $exportUrl .= "&user_id=" . urlencode($userIdFilter);
if (!empty($search)) $exportUrl .= "&search=" . urlencode($search);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Audit Trail &amp; System Activity Logs | Admin Console</title>
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
        .audit-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            color: #1e3c72;
            background: #e2e8f0;
            flex-shrink: 0;
        }
        .filter-chip {
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.85rem;
        }
        .stat-card {
            border-radius: 14px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
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
                    <i class="fa-solid fa-shield-halved me-1"></i> Security &amp; Compliance
                </span>
                <span class="badge bg-secondary-subtle text-secondary fw-semibold rounded-pill">
                    <?php echo $totalLogsCount; ?> Total Logged Events
                </span>
            </div>
            <h2 class="fw-bold text-dark mb-1">
                System Audit Trail &amp; Activity Logs
            </h2>
            <p class="text-muted mb-0">Chronological audit trail of all student authentications, AI assessments, resume scans, and platform operations</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?php echo $exportUrl; ?>" class="btn btn-outline-success fw-semibold px-3 py-2 d-inline-flex align-items-center gap-2" title="Export audit trail to CSV file">
                <i class="fa-solid fa-file-arrow-down"></i>
                <span>Export CSV</span>
            </a>
            <a href="users.php" class="btn btn-outline-primary fw-semibold px-3 py-2 d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-users"></i>
                <span>Manage Students</span>
            </a>
            <a href="dashboard.php" class="btn btn-outline-secondary fw-semibold px-3 py-2 d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-gauge"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- STUDENT SPECIFIC FILTER BANNER -->
    <?php if ($filteredStudent): ?>
        <div class="alert alert-info border-0 shadow-sm rounded-4 d-flex flex-wrap justify-content-between align-items-center mb-4 p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="audit-avatar bg-info text-white shadow-sm" style="width: 42px; height: 42px; font-size: 1.1rem;">
                    <?php echo strtoupper(substr($filteredStudent['name'], 0, 1)); ?>
                </div>
                <div>
                    <span class="badge bg-info text-dark fw-bold mb-1">Filtered by Student</span>
                    <h6 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($filteredStudent['name']); ?> (<?php echo htmlspecialchars($filteredStudent['email']); ?>)</h6>
                    <small class="text-muted">Course: <?php echo htmlspecialchars($filteredStudent['course'] ?: 'Not set'); ?> | Student ID: #<?php echo (int)$filteredStudent['id']; ?></small>
                </div>
            </div>
            <a href="module_activity.php<?php echo $moduleFilter !== 'all' ? '?module=' . urlencode($moduleFilter) : ''; ?>" class="btn btn-outline-dark btn-sm fw-semibold mt-2 mt-sm-0">
                <i class="fa-solid fa-xmark me-1"></i> Clear Student Filter
            </a>
        </div>
    <?php endif; ?>

    <!-- KPI STAT CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm border-0 p-3 bg-white d-flex flex-row align-items-center gap-3">
                <div class="p-3 bg-primary-subtle text-primary rounded-4">
                    <i class="fa-solid fa-list-check fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0"><?php echo $filteredLogsCount; ?></h3>
                    <small class="text-muted fw-semibold">Displayed Logs</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm border-0 p-3 bg-white d-flex flex-row align-items-center gap-3">
                <div class="p-3 bg-info-subtle text-info rounded-4">
                    <i class="fa-solid fa-right-to-bracket fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0"><?php echo $authLogsCount; ?></h3>
                    <small class="text-muted fw-semibold">Auth &amp; Login Events</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm border-0 p-3 bg-white d-flex flex-row align-items-center gap-3">
                <div class="p-3 bg-success-subtle text-success rounded-4">
                    <i class="fa-solid fa-clipboard-check fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0"><?php echo $assessmentLogsCount; ?></h3>
                    <small class="text-muted fw-semibold">Skill Assessments</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm border-0 p-3 bg-white d-flex flex-row align-items-center gap-3">
                <div class="p-3 bg-warning-subtle text-warning rounded-4">
                    <i class="fa-solid fa-brain fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0"><?php echo $aiLogsCount; ?></h3>
                    <small class="text-muted fw-semibold">Resumes &amp; Interviews</small>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="card shadow-sm border-0 rounded-4 p-3 bg-white mb-4">
        <form method="GET" class="row g-2 align-items-center">
            <?php if ($userIdFilter > 0): ?>
                <input type="hidden" name="user_id" value="<?php echo $userIdFilter; ?>">
            <?php endif; ?>
            
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search by student, action, details or IP..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
            </div>

            <div class="col-md-4">
                <select name="module" class="form-select">
                    <option value="all" <?php if ($moduleFilter === 'all') echo 'selected'; ?>>All Platform Modules</option>
                    <option value="Assessment" <?php if ($moduleFilter === 'Assessment') echo 'selected'; ?>>Assessments &amp; Tests</option>
                    <option value="Career" <?php if ($moduleFilter === 'Career') echo 'selected'; ?>>Career Guidance</option>
                    <option value="Resume" <?php if ($moduleFilter === 'Resume') echo 'selected'; ?>>Resume &amp; ATS Scans</option>
                    <option value="Interview" <?php if ($moduleFilter === 'Interview') echo 'selected'; ?>>Mock Interviews</option>
                    <option value="Authentication" <?php if ($moduleFilter === 'Authentication') echo 'selected'; ?>>Auth &amp; Logins</option>
                    <option value="Admin Panel" <?php if ($moduleFilter === 'Admin Panel') echo 'selected'; ?>>Admin Panel Operations</option>
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-semibold d-inline-flex align-items-center justify-content-center gap-1">
                    <i class="fa-solid fa-filter"></i> Apply Filter
                </button>
                <?php if ($isFiltered): ?>
                    <a href="module_activity.php" class="btn btn-outline-secondary fw-semibold" title="Reset all filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>

        <!-- MODULE QUICK FILTER PILLS -->
        <hr class="my-2 text-muted opacity-25">
        <div class="d-flex flex-wrap gap-1.5 align-items-center pt-1">
            <span class="text-muted small fw-semibold me-2"><i class="fa-solid fa-tags me-1"></i> Quick Filters:</span>
            <?php 
            $modules = [
                'all' => 'All',
                'Assessment' => 'Assessments',
                'Career' => 'Career Guidance',
                'Resume' => 'Resume & ATS',
                'Interview' => 'Interviews',
                'Authentication' => 'Logins',
                'Admin Panel' => 'Admin'
            ];
            foreach ($modules as $modKey => $modLabel):
                $active = ($moduleFilter === $modKey);
                $link = "module_activity.php?module=" . urlencode($modKey);
                if ($userIdFilter > 0) $link .= "&user_id=" . urlencode($userIdFilter);
                if (!empty($search)) $link .= "&search=" . urlencode($search);
            ?>
                <a href="<?php echo $link; ?>" class="btn btn-sm filter-chip <?php echo $active ? 'btn-primary' : 'btn-outline-secondary border-0 bg-light text-dark'; ?> rounded-pill px-3 py-1">
                    <?php echo $modLabel; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- AUDIT TRAIL LOGS TABLE -->
    <div class="card shadow-sm border-0 rounded-4 bg-white overflow-hidden">
        <div class="card-header bg-white py-3 px-4 border-0 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-primary"></i>
                <span>Audit Activity Log</span>
            </h5>
            <span class="text-muted small">
                Showing latest <strong><?php echo $filteredLogsCount; ?></strong> entries
            </span>
        </div>
        <div class="card-body p-0">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">Log ID</th>
                                <th>Timestamp</th>
                                <th>Actor / Student</th>
                                <th>Module</th>
                                <th>Action Performed</th>
                                <th>Activity Details</th>
                                <th class="pe-4">IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($log = mysqli_fetch_assoc($result)): 
                                $uName = $log['user_name'] ?? '';
                                $uEmail = $log['user_email'] ?? '';
                                $uId = (int)($log['user_id'] ?? 0);
                                $initial = !empty($uName) ? strtoupper(substr($uName, 0, 1)) : 'S';

                                $modBadgeClass = match($log['module'] ?? '') {
                                    'Assessment' => 'bg-info-subtle text-info',
                                    'Career' => 'bg-warning-subtle text-dark',
                                    'Resume' => 'bg-success-subtle text-success',
                                    'Interview' => 'bg-danger-subtle text-danger',
                                    'Authentication' => 'bg-primary-subtle text-primary',
                                    'Admin Panel' => 'bg-dark text-white',
                                    default => 'bg-secondary-subtle text-secondary'
                                };

                                $actBadgeClass = 'badge bg-light text-dark border';
                                if (stripos($log['action'], 'delete') !== false) {
                                    $actBadgeClass = 'badge bg-danger-subtle text-danger';
                                } elseif (stripos($log['action'], 'login') !== false || stripos($log['action'], 'auth') !== false) {
                                    $actBadgeClass = 'badge bg-primary-subtle text-primary';
                                } elseif (stripos($log['action'], 'complete') !== false || stripos($log['action'], 'submit') !== false) {
                                    $actBadgeClass = 'badge bg-success-subtle text-success';
                                }
                            ?>
                                <tr>
                                    <td class="ps-4 text-muted small fw-semibold">#<?php echo (int)$log['id']; ?></td>
                                    <td>
                                        <div class="small text-dark fw-semibold">
                                            <i class="fa-regular fa-clock text-muted me-1 small"></i>
                                            <?php echo date('M d, Y', strtotime($log['created_at'])); ?>
                                        </div>
                                        <small class="text-muted"><?php echo date('h:i:s A', strtotime($log['created_at'])); ?></small>
                                    </td>
                                    <td>
                                        <?php if (!empty($uName)): ?>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="audit-avatar shadow-sm">
                                                    <?php echo htmlspecialchars($initial); ?>
                                                </div>
                                                <div>
                                                    <a href="module_activity.php?user_id=<?php echo $uId; ?>" class="text-decoration-none text-dark fw-bold d-block" title="Filter by this student">
                                                        <?php echo htmlspecialchars($uName); ?>
                                                    </a>
                                                    <small class="text-muted"><?php echo htmlspecialchars($uEmail); ?></small>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="badge bg-dark px-2.5 py-1.5 rounded-pill">
                                                <i class="fa-solid fa-shield-halved me-1"></i> System / Admin
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $modBadgeClass; ?> px-2.5 py-1 fw-semibold rounded-pill">
                                            <?php echo htmlspecialchars($log['module']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="<?php echo $actBadgeClass; ?> px-2.5 py-1">
                                            <?php echo htmlspecialchars($log['action']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark small d-inline-block" style="max-width: 320px; line-height: 1.4;">
                                            <?php echo htmlspecialchars($log['details'] ?? $log['description'] ?? '—'); ?>
                                        </span>
                                    </td>
                                    <td class="pe-4">
                                        <span class="badge bg-light text-muted border font-monospace small">
                                            <i class="fa-solid fa-network-wired me-1 small"></i>
                                            <?php echo htmlspecialchars($log['ip_address'] ?? '127.0.0.1'); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="p-3 bg-light rounded-circle d-inline-block mb-3">
                        <i class="fa-solid fa-clock-rotate-left fa-3x text-muted"></i>
                    </div>
                    <h5 class="fw-bold text-dark">No audit trail records found</h5>
                    <p class="text-muted mb-3">No activity entries matched your specified filters.</p>
                    <a href="module_activity.php" class="btn btn-primary btn-sm fw-semibold">Reset Filters</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php if ($stmt) mysqli_stmt_close($stmt); ?>
