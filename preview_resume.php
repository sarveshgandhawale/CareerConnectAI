<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch latest resume
$sql = "SELECT * FROM resume WHERE user_id='$user_id' ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $resume = mysqli_fetch_assoc($result);
} else {
    echo "<script>
            alert('No Resume Found!');
            window.location='resume.php';
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Resume Preview</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
}

.card{
    margin-top:40px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,.2);
}

.card-header{
    background:#0d6efd;
    color:white;
    text-align:center;
}

h5{
    color:#0d6efd;
    margin-top:20px;
}

</style>

</head>

<body>

<div class="container">

<div class="card">

<div class="card-header">

<h2>My Resume</h2>

</div>

<div class="card-body">

<h3><?php echo $resume['name']; ?></h3>

<p><strong>Email :</strong> <?php echo $resume['email']; ?></p>

<p><strong>Mobile :</strong> <?php echo $resume['mobile']; ?></p>

<p><strong>Course :</strong> <?php echo $resume['course']; ?></p>

<hr>

<h5>Career Objective</h5>

<p><?php echo $resume['objective']; ?></p>

<h5>Skills</h5>

<p><?php echo nl2br($resume['skills']); ?></p>

<h5>Education</h5>

<p><?php echo nl2br($resume['education']); ?></p>

<h5>Projects</h5>

<p><?php echo nl2br($resume['projects']); ?></p>

<h5>Address</h5>

<p><?php echo nl2br($resume['address']); ?></p>

<hr>

<div class="text-center">

<a href="edit_resume.php" class="btn btn-warning">
Edit Resume
</a>

<a href="download_resume.php" class="btn btn-success">
Download PDF
</a>

<a href="dashboard.php" class="btn btn-primary">
Dashboard
</a>

</div>

</div>

</div>

</div>

</body>

</html>