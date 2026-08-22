<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Update profile
if (isset($_POST['update'])) {

    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $gender = $_POST['gender'];
    $course = $_POST['course'];
    $address = $_POST['address'];

    $update = mysqli_prepare($conn,
        "UPDATE users
         SET name=?, mobile=?, gender=?, course=?, address=?
         WHERE id=?");

    mysqli_stmt_bind_param(
        $update,
        "sssssi",
        $name,
        $mobile,
        $gender,
        $course,
        $address,
        $user_id
    );

    if (mysqli_stmt_execute($update)) {

        $_SESSION['name'] = $name;

        echo "<script>
                alert('Profile Updated Successfully');
                window.location='profile.php';
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
<html>

<head>

<title>Edit Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body style="background:#f5f5f5;">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-lg-7">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>Edit Profile</h3>

</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label>Name</label>

<input
type="text"
name="name"
class="form-control"
value="<?php echo htmlspecialchars($user['name']); ?>"
required>

</div>

<div class="mb-3">

<label>Email</label>

<input
type="email"
class="form-control"
value="<?php echo htmlspecialchars($user['email']); ?>"
readonly>

</div>

<div class="mb-3">

<label>Mobile</label>

<input
type="text"
name="mobile"
class="form-control"
value="<?php echo htmlspecialchars($user['mobile']); ?>"
required>

</div>

<div class="mb-3">

<label>Gender</label>

<select name="gender" class="form-select">

<option value="Male" <?php if($user['gender']=="Male") echo "selected"; ?>>Male</option>

<option value="Female" <?php if($user['gender']=="Female") echo "selected"; ?>>Female</option>

<option value="Other" <?php if($user['gender']=="Other") echo "selected"; ?>>Other</option>

</select>

</div>

<div class="mb-3">

<label>Course</label>

<input
type="text"
name="course"
class="form-control"
value="<?php echo htmlspecialchars($user['course']); ?>"
required>

</div>

<div class="mb-3">

<label>Address</label>

<textarea
name="address"
class="form-control"
rows="3"
required><?php echo htmlspecialchars($user['address']); ?></textarea>

</div>

<button
type="submit"
name="update"
class="btn btn-success">

<i class="fa-solid fa-floppy-disk"></i>

Update Profile

</button>

<a href="profile.php" class="btn btn-secondary">

Back

</a>

</form>

</div>

</div>

</div>

</div>

</div>

</body>

</html>