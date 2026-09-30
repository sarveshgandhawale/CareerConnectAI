<?php
require_once __DIR__ . '/../config/db.php';
requireLogin();

$userId = (int)$_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $gender = trim($_POST['gender'] ?? 'Male');
    $course = trim($_POST['course'] ?? 'BCA');
    $address = trim($_POST['address'] ?? '');

    if (empty($name) || empty($mobile) || empty($gender) || empty($course) || empty($address)) {
        header("Location: profile.php?error=" . urlencode("Please fill in all required fields."));
        exit();
    }

    if (!preg_match('/^[0-9]{10}$/', $mobile)) {
        header("Location: profile.php?error=" . urlencode("Please enter a valid 10-digit mobile number."));
        exit();
    }

    $updateStmt = mysqli_prepare($conn, "UPDATE users SET name=?, mobile=?, gender=?, course=?, address=? WHERE id=?");

    if ($updateStmt) {
        mysqli_stmt_bind_param(
            $updateStmt,
            "sssssi",
            $name,
            $mobile,
            $gender,
            $course,
            $address,
            $userId
        );

        if (mysqli_stmt_execute($updateStmt)) {
            $_SESSION['name'] = $name;
            $_SESSION['course'] = $course;
            mysqli_stmt_close($updateStmt);

            // Log activity
            logActivity($conn, $userId, 'Update Profile', 'Profile', "Updated profile: name={$name}, course={$course}");

            header("Location: profile.php?success=" . urlencode("Profile updated successfully!"));
            exit();
        } else {
            mysqli_stmt_close($updateStmt);
            header("Location: profile.php?error=" . urlencode("Failed to update profile. Please try again."));
            exit();
        }
    } else {
        header("Location: profile.php?error=" . urlencode("Database query error."));
        exit();
    }

} else {
    header("Location: profile.php");
    exit();
}
?>
