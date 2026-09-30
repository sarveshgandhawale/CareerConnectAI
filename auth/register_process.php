<?php
require_once __DIR__ . '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get & Sanitize Form Data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $gender = trim($_POST['gender'] ?? 'Male');
    $course = trim($_POST['course'] ?? 'BCA');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $address = trim($_POST['address'] ?? '');

    // Validate Required Fields
    if (empty($name) || empty($email) || empty($mobile) || empty($gender) || empty($course) || empty($password) || empty($address)) {
        header("Location: register.php?error=" . urlencode("Please fill in all required fields."));
        exit();
    }

    // Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.php?error=" . urlencode("Invalid email format."));
        exit();
    }

    // Validate Mobile Number (10 digits)
    if (!preg_match('/^[0-9]{10}$/', $mobile)) {
        header("Location: register.php?error=" . urlencode("Please enter a valid 10-digit mobile number."));
        exit();
    }

    // Validate Password Match & Length
    if ($password !== $confirm_password) {
        header("Location: register.php?error=" . urlencode("Passwords do not match."));
        exit();
    }

    if (strlen($password) < 6) {
        header("Location: register.php?error=" . urlencode("Password must be at least 6 characters long."));
        exit();
    }

    // Check If Email Already Exists using Prepared Statement
    $checkStmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? LIMIT 1");
    if (!$checkStmt) {
        header("Location: register.php?error=" . urlencode("Database query error."));
        exit();
    }

    mysqli_stmt_bind_param($checkStmt, "s", $email);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        mysqli_stmt_close($checkStmt);
        header("Location: register.php?error=" . urlencode("An account with this email address already exists."));
        exit();
    }
    mysqli_stmt_close($checkStmt);

    // Hash Password securely
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert New Student User
    $insertSql = "INSERT INTO users (name, email, mobile, gender, course, password, address, profile_image, role) VALUES (?, ?, ?, ?, ?, ?, ?, 'default.png', 'student')";
    $insertStmt = mysqli_prepare($conn, $insertSql);

    if (!$insertStmt) {
        header("Location: register.php?error=" . urlencode("Registration system error. Please try again."));
        exit();
    }

    mysqli_stmt_bind_param(
        $insertStmt,
        "sssssss",
        $name,
        $email,
        $mobile,
        $gender,
        $course,
        $hashedPassword,
        $address
    );

    if (mysqli_stmt_execute($insertStmt)) {
        $newUserId = mysqli_insert_id($conn);
        mysqli_stmt_close($insertStmt);

        // Log registration activity
        logActivity($conn, $newUserId, 'Register', 'Authentication', "Student registered with course {$course}.");

        header("Location: login.php?success=" . urlencode("Account created successfully! Please sign in with your credentials."));
        exit();
    } else {
        mysqli_stmt_close($insertStmt);
        header("Location: register.php?error=" . urlencode("Registration could not be completed. Please try again."));
        exit();
    }

} else {
    header("Location: register.php");
    exit();
}
?>