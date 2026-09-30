<?php
require_once __DIR__ . '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $subject = trim($_POST['subject'] ?? 'General Inquiry');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        header("Location: contact.php?error=" . urlencode("Please fill in all required fields."));
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: contact.php?error=" . urlencode("Please enter a valid email address."));
        exit();
    }

    // Insert into contact_messages table
    $stmt = mysqli_prepare($conn, "INSERT INTO contact_messages (name, email, mobile, subject, message) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $mobile, $subject, $message);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);

            if (isLoggedIn()) {
                logActivity($conn, $_SESSION['user_id'], 'Contact Support', 'Contact', "Submitted contact inquiry regarding '{$subject}'.");
            }

            header("Location: contact.php?success=" . urlencode("Your message has been sent successfully! Our team will get back to you shortly."));
            exit();
        } else {
            $err = mysqli_error($conn);
            mysqli_stmt_close($stmt);
            header("Location: contact.php?error=" . urlencode("Database error: " . $err));
            exit();
        }
    } else {
        header("Location: contact.php?error=" . urlencode("Database prepare error."));
        exit();
    }

} else {
    header("Location: contact.php");
    exit();
}
?>
