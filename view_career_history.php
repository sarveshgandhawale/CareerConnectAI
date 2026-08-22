<?php

session_start();

include "config.php";


$name = $_SESSION['name'] ?? "Student";


// Fetch career history

$sql = "SELECT * FROM career_results 
        WHERE student_name='$name'
        ORDER BY id DESC";


$result = mysqli_query($conn,$sql);


?>


<!DOCTYPE html>
<html>

<head>

<title>Career History</title>


<style>

body{

    font-family:Arial;
    background:#f5f5f5;

}


.container{

    width:85%;
    margin:30px auto;

}


.card{

    background:white;
    padding:20px;
    margin-bottom:20px;
    border-radius:10px;
    box-shadow:0px 5px 10px #ccc;

}


h1{

    color:#ff7a00;
    text-align:center;

}


.score{

    color:#ff7a00;
    font-size:25px;
    font-weight:bold;

}


.btn{

    background:#ff7a00;
    color:white;
    padding:10px 20px;
    text-decoration:none;
    border-radius:5px;

}


</style>


</head>



<body>



<div class="container">


<h1>
My Career History
</h1>



<?php


if(mysqli_num_rows($result)>0)

{


while($row=mysqli_fetch_assoc($result))

{


?>


<div class="card">


<h2>
<?php echo $row['career']; ?>
</h2>


<p>
<b>Student:</b>

<?php echo $row['student_name']; ?>

</p>



<p>
<b>Career Match Score:</b>

<span class="score">

<?php echo $row['score']; ?>%

</span>

</p>




<p>

<b>Skills:</b>

<?php echo $row['skills']; ?>

</p>




<p>

<b>Skills To Improve:</b>

<?php echo $row['missing_skills']; ?>

</p>




<p>

<b>Learning Roadmap:</b>

<?php echo $row['roadmap']; ?>

</p>




<p>

<b>Date:</b>

<?php echo $row['created_at']; ?>

</p>



</div>



<?php


}


}

else

{


echo "

<div class='card'>

<h3>
No Career History Found
</h3>

</div>

";


}


?>




<a href="career_result.php" class="btn">

Back To Result

</a>



</div>


</body>

</html>