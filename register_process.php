<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get Form Data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $gender = trim($_POST['gender']);
    $course = trim($_POST['course']);
    $password = trim($_POST['password']);
    $address = trim($_POST['address']);

    // Check Empty Fields
    if (
        empty($name) || empty($email) || empty($mobile) ||
        empty($gender) || empty($course) ||
        empty($password) || empty($address)
    ) {
        echo "<script>
                alert('Please fill all fields.');
                window.location='register.php';
              </script>";
        exit();
    }

    // Check Email Already Exists
    $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($check, "s", $email);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        echo "<script>
                alert('Email already exists!');
                window.location='register.php';
              </script>";
        exit();
    }

    // Encrypt Password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert Data
    $sql = "INSERT INTO users
            (name, email, mobile, gender, course, password, address)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sssssss",
        $name,
        $email,
        $mobile,
        $gender,
        $course,
        $hashedPassword,
        $address
    );

    if (mysqli_stmt_execute($stmt)) {

        echo "<script>
                alert('Registration Successful');
                window.location='login.php';
              </script>";

    } else {

        echo "<script>
                alert('Registration Failed');
                window.location='register.php';
              </script>";

    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>