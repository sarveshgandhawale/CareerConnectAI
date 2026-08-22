<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>AI Career Guidance</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<link rel="stylesheet" href="assets/css/career.css">

</head>

<body>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card shadow-lg">

<div class="card-header text-center bg-primary text-white">

<h2>

<i class="fa-solid fa-robot"></i>

AI Career Guidance

</h2>

<p>Find the Best Career Based on Your Skills</p>

</div>

<div class="card-body">

<form action="career_result.php" method="POST">

<div class="mb-3">

<label>Full Name</label>

<input type="text" class="form-control" name="name" required>

</div>

<div class="mb-3">

<label>Course</label>

<select class="form-select" name="course">

<option>BCA</option>

<option>MCA</option>

<option>BBA</option>

<option>MBA</option>

</select>

</div>

<div class="mb-3">

<label>Programming Skill</label>

<select class="form-select" name="programming">

<option>Beginner</option>

<option>Intermediate</option>

<option>Advanced</option>

</select>

</div>

<div class="mb-3">

<label>Favorite Field</label>

<select class="form-select" name="interest">

<option>Web Development</option>

<option>Artificial Intelligence</option>

<option>Data Science</option>

<option>Cyber Security</option>

<option>Cloud Computing</option>

<option>Mobile App Development</option>

</select>

</div>

<div class="mb-3">

<label>Communication Skill</label>

<select class="form-select" name="communication">

<option>Beginner</option>

<option>Intermediate</option>

<option>Excellent</option>

</select>

</div>

<div class="mb-3">

<label>Problem Solving</label>

<select class="form-select" name="problem">

<option>Low</option>

<option>Medium</option>

<option>High</option>

</select>

</div>

<div class="text-center">

<button class="btn btn-primary">

<i class="fa-solid fa-wand-magic-sparkles"></i>

Get Career Suggestion

</button>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

<script src="assets/js/career.js"></script>

</body>

</html>