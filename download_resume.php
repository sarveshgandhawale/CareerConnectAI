<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM resume WHERE user_id='$user_id' ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result)>0){
    $resume = mysqli_fetch_assoc($result);
}else{
    echo "Resume Not Found";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Download Resume</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/download_resume.css">

</head>

<body>

<div class="container mt-5">

<div class="text-end no-print mb-3">

<button class="btn btn-success" onclick="window.print()">
Print / Download PDF
</button>

<a href="dashboard.php" class="btn btn-primary">
Dashboard
</a>

</div>

<div class="resume">

<h2 class="text-center mb-4">
Resume
</h2>

<hr>

<h3><?php echo $resume['name']; ?></h3>

<p><strong>Email :</strong> <?php echo $resume['email']; ?></p>

<p><strong>Mobile :</strong> <?php echo $resume['mobile']; ?></p>

<p><strong>Course :</strong> <?php echo $resume['course']; ?></p>

<hr>

<h5>Career Objective</h5>

<p><?php echo nl2br($resume['objective']); ?></p>

<h5>Skills</h5>

<p><?php echo nl2br($resume['skills']); ?></p>

<h5>Education</h5>

<p><?php echo nl2br($resume['education']); ?></p>

<h5>Projects</h5>

<p><?php echo nl2br($resume['projects']); ?></p>

<h5>Address</h5>

<p><?php echo nl2br($resume['address']); ?></p>

</div>

</div>

<script src="assets/js/download_resume.js"></script>

</body>

</html>