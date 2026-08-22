<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$name = isset($_SESSION['name']) ? $_SESSION['name'] : "Student";
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<link rel="stylesheet" href="assets/css/dashboard.css">

</head>

<body>

<nav class="navbar navbar-expand-lg">

<div class="container">

<a class="navbar-brand" href="#">

<i class="fa-solid fa-graduation-cap"></i>

AI Student Portal

</a>

<div>

<a href="profile.php" class="btn btn-light btn-sm">

Profile

</a>

<a href="logout.php" class="btn btn-danger btn-sm">

Logout

</a>

</div>

</div>

</nav>

<div class="container mt-4">

<div class="welcome-box">

<h2>

Welcome,

<?php echo $name; ?>

👋

</h2>

<p>

Manage your Student Portal Dashboard.

</p>

</div>

<div class="row mt-4">

<div class="col-md-3 mb-4">

<div class="dashboard-card">

<i class="fa-solid fa-user icon"></i>

<h4>Profile</h4>

<p>Manage Profile</p>

<a href="profile.php" class="btn btn-primary">

Open

</a>

</div>

</div>

<div class="col-md-3 mb-4">

<div class="dashboard-card">

<i class="fa-solid fa-file-lines icon"></i>

<h4>Resume</h4>

<p>Create Resume</p>

<a href="resume.php" class="btn btn-primary">

Open

</a>
</div>

</div>

<div class="col-md-3 mb-4">

<div class="dashboard-card">

<i class="fa-solid fa-robot icon"></i>

<h4>AI Career</h4>

<p>Career Guidance</p>

<a href="career_guidance.php" class="btn btn-warning">

Open

</a>

</div>

</div>

<div class="col-md-3 mb-4">

<div class="dashboard-card">

<i class="fa-solid fa-comments icon"></i>

<h4>Interview</h4>

<p>Mock Interview</p>

<button class="btn btn-info text-white">

Open

</button>

</div>

</div>

</div>

<div class="card shadow p-4">

<h3>Recent Activities</h3>

<ul class="list-group">

<li class="list-group-item">

Registration Completed

</li>

<li class="list-group-item">

Resume Module Ready

</li>

<li class="list-group-item">

AI Career Guidance Available

</li>

<li class="list-group-item">

Interview Practice Coming Soon

</li>

</ul>

</div>

</div>

<script src="assets/js/dashboard.js"></script>

</body>

</html>