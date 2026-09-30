<?php
require_once __DIR__ . '/../config/db.php';

if (isAdminLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = "Please enter both admin email and password.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, name, email, password FROM admins WHERE email = ? LIMIT 1");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);

            if ($res && mysqli_num_rows($res) === 1) {
                $admin = mysqli_fetch_assoc($res);
                if (password_verify($password, $admin['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_name'] = $admin['name'];
                    $_SESSION['admin_email'] = $admin['email'];
                    $_SESSION['role'] = 'admin';

                    logActivity($conn, null, 'Admin Login', 'Admin Panel', "Admin {$admin['name']} signed in.");

                    mysqli_stmt_close($stmt);
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Invalid password. Try Admin@123";
                }
            } else {
                $error = "No admin account found with that email.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Portal Login | CareerConnect AI</title>
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
<body class="bg-dark text-light min-vh-100 d-flex flex-column justify-content-between">

<div class="container py-5 my-auto">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden bg-white text-dark">
                <div class="card-header p-4 text-center text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                    <div class="bg-warning text-dark rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                        <i class="fa-solid fa-shield-halved fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-0 text-white">Admin Management</h3>
                    <small class="text-white-50">CareerConnect AI Administrative Gateway</small>
                </div>

                <div class="card-body p-4 p-md-5">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-circle-exclamation me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="alert alert-info py-2 px-3 small rounded-3 mb-4">
                        <i class="fa-solid fa-key me-1"></i> Default Master: <code>admin@careerconnect.ai</code> / <code>Admin@123</code>
                    </div>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary">Admin Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="admin@careerconnect.ai" value="admin@careerconnect.ai" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary">Master Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Admin@123" value="Admin@123" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-3 fw-semibold shadow-sm mb-3">
                            <i class="fa-solid fa-right-to-bracket me-2"></i> Access Admin Console
                        </button>

                        <div class="text-center pt-2 border-top">
                            <a href="<?php echo url('index.php'); ?>" class="text-secondary small text-decoration-none">
                                <i class="fa-solid fa-arrow-left me-1"></i> Return to Public Site
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
