<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

session_start();

include "includes/db.php";

?>

<!DOCTYPE html>
<html>

<head>

<title>Career Result - CareerConnect AI</title>

<link rel="stylesheet" href="css/career_result.css">

</head>


<body>


<div class="container">


<div class="card header">

<h1>
Career Analysis Result
</h1>

<p>
AI Powered Career Recommendation
</p>

</div>




<div class="card">


<h2>
Welcome <?php echo $name; ?>
</h2>


<p>
Recommended Career:
</p>


<h2 class="career">

<?php echo $career; ?>

</h2>


</div>





<div class="card">


<h2>
Career Match Score
</h2>


<h1 class="score">

<?php echo $score; ?>%

</h1>



<div class="progress">

<div class="progress-bar"

style="width:<?php echo $score; ?>%">

</div>

</div>


</div>







<div class="card">


<h2>
Skill Analysis
</h2>


<ul>


<?php

foreach($skills as $skill=>$level)

{

?>

<li>

<b>
<?php echo $skill; ?>
</b>

:

<?php echo $level; ?>

</li>


<?php

}

?>


</ul>


</div>







<div class="card">


<h2>
Skills To Improve
</h2>


<ul>


<?php

foreach($missing as $skill)

{

?>

<li>

<?php echo $skill; ?>

</li>


<?php

}

?>


</ul>


</div>







<div class="card">


<h2>
Learning Roadmap
</h2>


<ol>


<?php

foreach($roadmap as $step)

{

?>

<li>

<?php echo $step; ?>

</li>


<?php

}

?>


</ol>


</div>







<div class="card button-box">


<a href="interview_coach.php" class="btn">

Start Interview Practice

</a>


</div>



</div>


</body>

</html>