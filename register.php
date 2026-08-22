<?php
include("db.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Registration</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<link rel="stylesheet" href="assets/css/register.css">

</head>

<body>

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-10">

<div class="card shadow-lg">

<div class="row">

<div class="col-lg-5 left-panel">

<h2>AI Student Portal</h2>

<p>Create your account to continue.</p>

</div>

<div class="col-lg-7">

<div class="p-4">

<h3 class="text-center mb-4">

<i class="fa-solid fa-user-plus"></i>

Register

</h3>

<form action="register_process.php" method="POST" onsubmit="return validateForm();">

<input class="form-control mb-3" type="text" name="name" id="name" placeholder="Full Name" required>

<input class="form-control mb-3" type="email" name="email" id="email" placeholder="Email" required>

<input class="form-control mb-3" type="text" name="mobile" id="mobile" placeholder="Mobile Number" maxlength="10" required>

<select class="form-select mb-3" name="gender">

<option>Male</option>

<option>Female</option>

<option>Other</option>

</select>

<select class="form-select mb-3" name="course">

<option>BCA</option>

<option>MCA</option>

<option>BBA</option>

<option>MBA</option>

</select>

<div class="input-group mb-3">

<input type="password" class="form-control" name="password" id="password" placeholder="Password" required>

<button type="button" class="btn btn-outline-secondary" onclick="showPassword()">

<i class="fa-solid fa-eye"></i>

</button>

</div>

<input type="password" class="form-control mb-3" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>

<textarea class="form-control mb-3" rows="3" name="address" placeholder="Address" required></textarea>

<button class="btn btn-primary w-100">

Register

</button>

<div class="text-center mt-3">

Already have an account?

<a href="login.php">Login</a>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

</div>

</div>

<script src="assets/js/register.js"></script>

</body>

</html>