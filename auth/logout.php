<?php
require_once __DIR__ . '/../config/db.php';

// If user or admin is logged in, log the logout activity
if (isLoggedIn()) {
    logActivity($conn, $_SESSION['user_id'], 'Logout', 'Authentication', 'User logged out.');
}

// Handle immediate logout if query param ?action=logout is present or on POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' || (isset($_GET['action']) && $_GET['action'] === 'logout')) {
    $_SESSION = array();

    // Delete session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();
    header("Location: " . url('auth/login.php?success=' . urlencode("You have been signed out successfully.")));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Logout | CareerConnect AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo asset('css/logout.css'); ?>">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4 p-4 text-center bg-white">
                <div class="card-body">
                    <div class="mb-4 text-danger">
                        <i class="fa-solid fa-arrow-right-from-bracket fa-3x"></i>
                    </div>
                    <h3 class="fw-bold mb-2 text-dark">Sign Out?</h3>
                    <p class="text-muted mb-4">
                        Are you sure you want to end your current session? You will need to log in again to access your career dashboard.
                    </p>
                    <form method="POST">
                        <button type="submit" class="btn btn-danger w-100 py-2 fw-semibold mb-3 shadow-sm">
                            <i class="fa-solid fa-right-from-bracket me-2"></i> Yes, Log Me Out
                        </button>
                        <a href="<?php echo url('dashboard/dashboard.php'); ?>" class="btn btn-outline-secondary w-100 py-2 fw-semibold">
                            <i class="fa-solid fa-house me-2"></i> Back to Dashboard
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>