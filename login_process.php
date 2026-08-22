<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check empty fields
    if (empty($email) || empty($password)) {
        echo "<script>
                alert('Please enter Email and Password.');
                window.location='login.php';
              </script>";
        exit();
    }

    // Check user by email
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $user['password'])) {

            // Create Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];

            echo "<script>
                    alert('Login Successful');
                    window.location='dashboard.php';
                  </script>";

        } else {

            echo "<script>
                    alert('Invalid Password');
                    window.location='login.php';
                  </script>";

        }

    } else {

        echo "<script>
                alert('Email Not Registered');
                window.location='login.php';
              </script>";

    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>