<?php
require_once __DIR__ . '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Check empty fields
    if (empty($email) || empty($password)) {
        header("Location: login.php?error=" . urlencode("Please enter both email and password."));
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: login.php?error=" . urlencode("Please enter a valid email address."));
        exit();
    }

    // Check user by email using Prepared Statement
    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, name, email, password, course, profile_image, role 
         FROM users 
         WHERE email = ? 
         LIMIT 1"
    );

    if (!$stmt) {
        header("Location: login.php?error=" . urlencode("Database query error."));
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {

        $user = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $user['password'])) {

            // Prevent Session Fixation
            session_regenerate_id(true);

            // Set Session Variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['course'] = $user['course'];
            $_SESSION['profile_image'] = $user['profile_image'] ?? 'default.png';
            $_SESSION['role'] = $user['role'] ?? 'student';

            // Log login activity
            logActivity($conn, $user['id'], 'Login', 'Authentication', 'User logged in successfully.');

            mysqli_stmt_close($stmt);

            // Determine redirect target
            $redirect = $_SESSION['redirect_url'] ?? url('dashboard/dashboard.php');
            unset($_SESSION['redirect_url']);

            header("Location: " . $redirect);
            exit();

        } else {
            mysqli_stmt_close($stmt);
            header("Location: login.php?error=" . urlencode("Invalid email or password."));
            exit();
        }

    } else {
        mysqli_stmt_close($stmt);
        header("Location: login.php?error=" . urlencode("No account found with this email."));
        exit();
    }

} else {
    header("Location: login.php");
    exit();
}
?>