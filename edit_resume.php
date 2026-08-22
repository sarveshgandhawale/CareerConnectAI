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
            alert('Resume Not Found');
            window.location='resume.php';
          </script>";
    exit();
}

// Update Resume
if (isset($_POST['update'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $course = $_POST['course'];
    $objective = $_POST['objective'];
    $skills = $_POST['skills'];
    $education = $_POST['education'];
    $projects = $_POST['projects'];
    $address = $_POST['address'];

    $id = $resume['id'];

    $update = "UPDATE resume SET

    name='$name',
    email='$email',
    mobile='$mobile',
    course='$course',
    objective='$objective',
    skills='$skills',
    education='$education',
    projects='$projects',
    address='$address'

    WHERE id='$id'";

    if (mysqli_query($conn, $update)) {

        echo "<script>
                alert('Resume Updated Successfully');
                window.location='preview_resume.php';
              </script>";

        exit();

    } else {

        echo "<script>
                alert('Update Failed');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Edit Resume</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<link rel="stylesheet" href="assets/css/edit_resume.css">

</head>

<body>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card shadow">

<div class="card-header bg-success text-white text-center">

<h3>

<i class="fa-solid fa-pen"></i>

Edit Resume

</h3>

</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label>Full Name</label>

<input type="text"
name="name"
class="form-control"
value="<?php echo $resume['name']; ?>"
required>

</div>

<div class="mb-3">

<label>Email</label>

<input type="email"
name="email"
class="form-control"
value="<?php echo $resume['email']; ?>"
required>

</div>

<div class="mb-3">

<label>Mobile</label>

<input type="text"
name="mobile"
class="form-control"
value="<?php echo $resume['mobile']; ?>"
required>

</div>

<div class="mb-3">

<label>Course</label>

<input type="text"
name="course"
class="form-control"
value="<?php echo $resume['course']; ?>">

</div>

<div class="mb-3">

<label>Career Objective</label>

<textarea
name="objective"
class="form-control"
rows="3"><?php echo $resume['objective']; ?></textarea>

</div>

<div class="mb-3">

<label>Skills</label>

<textarea
name="skills"
class="form-control"
rows="3"><?php echo $resume['skills']; ?></textarea>

</div>

<div class="mb-3">

<label>Education</label>

<textarea
name="education"
class="form-control"
rows="3"><?php echo $resume['education']; ?></textarea>

</div>

<div class="mb-3">

<label>Projects</label>

<textarea
name="projects"
class="form-control"
rows="3"><?php echo $resume['projects']; ?></textarea>

</div>

<div class="mb-3">

<label>Address</label>

<textarea
name="address"
class="form-control"
rows="3"><?php echo $resume['address']; ?></textarea>

</div>

<div class="text-center">

<button
type="submit"
name="update"
class="btn btn-success">

<i class="fa-solid fa-floppy-disk"></i>

Update Resume

</button>

<a href="preview_resume.php"
class="btn btn-primary">

<i class="fa-solid fa-eye"></i>

Preview

</a>

<a href="dashboard.php"
class="btn btn-secondary">

<i class="fa-solid fa-house"></i>

Dashboard

</a>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

<script src="assets/js/edit_resume.js"></script>

</body>

</html>