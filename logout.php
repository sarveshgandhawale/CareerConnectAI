<?php
session_start();

if(isset($_POST['logout']))
{
    session_destroy();

    header("Location: login.php");

    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Logout</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<link rel="stylesheet" href="assets/css/logout.css">

</head>

<body>

<div class="container">

<div class="row justify-content-center align-items-center vh-100">

<div class="col-lg-5">

<div class="card logout-card">

<div class="card-body text-center">

<div class="icon">

<i class="fa-solid fa-right-from-bracket"></i>

</div>

<h2>Logout</h2>

<p>

Are you sure you want to logout from your account?

</p>

<form method="POST">

<button type="submit" name="logout" class="btn btn-danger w-100 mb-3">

<i class="fa-solid fa-right-from-bracket"></i>

Logout

</button>

<a href="dashboard.php" class="btn btn-primary w-100">

<i class="fa-solid fa-house"></i>

Back to Dashboard

</a>

</form>

</div>

</div>

</div>

</div>

</div>

<script src="assets/js/logout.js"></script>

</body>

</html>