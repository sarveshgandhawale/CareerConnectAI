<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<link rel="stylesheet" href="assets/css/profile.css">

</head>

<body>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card profile-card">

<div class="card-header">

<h3>

<i class="fa-solid fa-user"></i>

My Profile

</h3>

</div>

<div class="card-body">

<div class="text-center">

<img src="assets/images/default.png" class="profile-img">

<h3 class="mt-3">

<?php echo $user['name']; ?>

</h3>

<p class="text-muted">

<?php echo $user['course']; ?>

</p>

</div>

<hr>

<div class="row">

<div class="col-md-6 mb-3">

<label>Email</label>

<input type="text" class="form-control"
value="<?php echo $user['email']; ?>" readonly>

</div>

<div class="col-md-6 mb-3">

<label>Mobile</label>

<input type="text" class="form-control"
value="<?php echo $user['mobile']; ?>" readonly>

</div>

<div class="col-md-6 mb-3">

<label>Gender</label>

<input type="text" class="form-control"
value="<?php echo $user['gender']; ?>" readonly>

</div>

<div class="col-md-6 mb-3">

<label>Course</label>

<input type="text" class="form-control"
value="<?php echo $user['course']; ?>" readonly>

</div>

<div class="col-12 mb-3">

<label>Address</label>

<textarea class="form-control" rows="3" readonly><?php echo $user['address']; ?></textarea>

</div>

</div>

<div class="text-center">

<a href="dashboard.php" class="btn btn-primary">

<i class="fa-solid fa-house"></i>

Dashboard

</a>

<a href="update_profile.php" class="btn btn-success">
    <i class="fa-solid fa-pen"></i>
    Edit Profile
</a>

<a href="logout.php" class="btn btn-danger">

<i class="fa-solid fa-right-from-bracket"></i>

Logout

</a>

</div>

</div>

</div>

</div>

</div>

</div>

<script src="assets/js/profile.js"></script>

</body>

</html>