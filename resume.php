<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Resume Builder</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<link rel="stylesheet" href="assets/css/resume.css">

</head>

<body>

<div class="container py-5">

<div class="card resume-card">

<div class="card-header text-center">

<h2>

<i class="fa-solid fa-file-lines"></i>

Resume Builder

</h2>

</div>

<div class="card-body">

<form action="save_resume.php" method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label>Full Name</label>

<input type="text" name="name" class="form-control" required>

</div>

<div class="col-md-6 mb-3">

<label>Email</label>

<input type="email" name="email" class="form-control" required>

</div>

<div class="col-md-6 mb-3">

<label>Mobile</label>

<input type="text" name="mobile" class="form-control" required>

</div>

<div class="col-md-6 mb-3">

<label>Course</label>

<input type="text" name="course" class="form-control">

</div>

<div class="col-12 mb-3">

<label>Career Objective</label>

<textarea name="objective" class="form-control" rows="3"></textarea>

</div>

<div class="col-12 mb-3">

<label>Skills</label>

<textarea name="skills" class="form-control" rows="3"></textarea>

</div>

<div class="col-12 mb-3">

<label>Education</label>

<textarea name="education" class="form-control" rows="3"></textarea>

</div>

<div class="col-12 mb-3">

<label>Projects</label>

<textarea name="projects" class="form-control" rows="3"></textarea>

</div>

<div class="col-12 mb-3">

<label>Address</label>

<textarea name="address" class="form-control" rows="3"></textarea>

</div>

<div class="text-center">

<button class="btn btn-primary">

<i class="fa-solid fa-floppy-disk"></i>

Save Resume

</button>

<button type="reset" class="btn btn-secondary">

Reset

</button>

</div>

</div>

</form>

</div>

</div>

</div>

<script src="assets/js/resume.js"></script>

</body>

</html>