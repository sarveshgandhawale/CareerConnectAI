<?php

session_start();

include "config.php";


// Get career result data

$name = $_SESSION['name'] ?? "Student";

$career = "Full Stack Developer";

$score = 85;


$skills = "HTML, CSS, JavaScript, PHP, MySQL";


$missing = "React.js, Node.js, Git, Cloud";


$roadmap = "Learn JavaScript, Learn React, Build Projects, Prepare Interview";




// Insert data

$sql = "INSERT INTO career_results
(student_name, career, score, skills, missing_skills, roadmap)

VALUES

('$name',
'$career',
'$score',
'$skills',
'$missing',
'$roadmap')";


$result = mysqli_query($conn,$sql);



if($result)
{

echo "

<script>

alert('Career Result Saved Successfully');

window.location='career_result.php';

</script>

";

}

else
{

echo "Error : ".mysqli_error($conn);

}


?>