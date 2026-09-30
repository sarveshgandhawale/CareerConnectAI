<?php
require_once __DIR__ . '/config/db.php';

$message = '';
$error = '';
$step = 1;
$email = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['find_account'])) {
        $email = trim($_POST['email'] ?? '');
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email address.";
        } else {
            $stmt = mysqli_prepare($conn, "SELECT id, name FROM users WHERE email = ? LIMIT 1");
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            if ($res && mysqli_num_rows($res) === 1) {
                $user = mysqli_fetch_assoc($res);
                $_SESSION['reset_user_id'] = $user['id'];
                $step = 2;
                $message = "Account verified for " . htmlspecialchars($user['name']) . ". Enter your new password below.";
            } else {
                $error = "No registered account found with that email.";
            }
            mysqli_stmt_close($stmt);
        }
    } elseif (isset($_POST['reset_password'])) {
        $userId = $_SESSION['reset_user_id'] ?? null;
        $newPass = trim($_POST['new_password'] ?? '');
        $confirmPass = trim($_POST['confirm_password'] ?? '');

        if (!$userId) {
            $error = "Session expired. Please search for your account again.";
            $step = 1;
        } elseif (strlen($newPass) < 6) {
            $error = "Password must be at least 6 characters.";
            $step = 2;
        } elseif ($newPass !== $confirmPass) {
            $error = "Passwords do not match.";
            $step = 2;
        } else {
            $hashed = password_hash($newPass, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "si", $hashed, $userId);
            if (mysqli_stmt_execute($stmt)) {
                logActivity($conn, $userId, 'Password Reset', 'Authentication', 'User reset their password.');
                unset($_SESSION['reset_user_id']);
                mysqli_stmt_close($stmt);
                header("Location: " . url('auth/login.php?success=' . urlencode("Password reset successful! You can now log in.")));
                exit();
            } else {
                $error = "Could not update password. Please try again.";
                $step = 2;
            }
            if ($stmt) mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password | CareerConnect AI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo asset('css/login.css'); ?>">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100 py-5">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4 p-4 bg-white">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="mb-3 text-primary">
                            <i class="fa-solid fa-key fa-3x"></i>
                        </div>
                        <h3 class="fw-bold">Reset Password</h3>
                        <p class="text-muted small">Recover access to your CareerConnect AI student account</p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-circle-exclamation me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($message)): ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-circle-info me-2"></i>
                            <?php echo htmlspecialchars($message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($step === 1): ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">Registered Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-envelope text-muted"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="name@example.com" value="<?php echo htmlspecialchars($email); ?>" required autofocus>
                                </div>
                            </div>
                            <button type="submit" name="find_account" class="btn btn-primary w-100 py-2 fw-semibold mb-3 shadow-sm">
                                Verify Account
                            </button>
                        </form>
                    <?php else: ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-muted"></i></span>
                                    <input type="password" name="new_password" class="form-control" placeholder="Enter at least 6 characters" required autofocus>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-secondary">Confirm New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-muted"></i></span>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm your password" required>
                                </div>
                            </div>
                            <button type="submit" name="reset_password" class="btn btn-success w-100 py-2 fw-semibold mb-3 shadow-sm">
                                Update Password
                            </button>
                        </form>
                    <?php endif; ?>

                    <div class="text-center pt-3 border-top">
                        <a href="<?php echo url('auth/login.php'); ?>" class="text-decoration-none text-secondary small">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
