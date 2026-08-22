<?php
include("db.php");
session_start();

$user_id = $_SESSION['user_id'];

$name = $_POST['name'];
$email = $_POST['email'];
$mobile = $_POST['mobile'];
$course = $_POST['course'];
$objective = $_POST['objective'];
$skills = $_POST['skills'];
$education = $_POST['education'];
$projects = $_POST['projects'];
$address = $_POST['address'];

$sql = "INSERT INTO resume
(user_id,name,email,mobile,course,objective,skills,education,projects,address)
VALUES
('$user_id','$name','$email','$mobile','$course','$objective','$skills','$education','$projects','$address')";

if(mysqli_query($conn,$sql))
{
    header("Location: preview_resume.php");
}
else
{
    echo "Error : ".mysqli_error($conn);
}
?>