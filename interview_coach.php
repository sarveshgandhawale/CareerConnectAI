<?php

session_start();

?>

<!DOCTYPE html>
<html>

<head>

<title>AI Interview Coach</title>

<link rel="stylesheet" href="assets/css/interview_coach.css">


<script>

function showQuestions()
{

let type = document.getElementById("interviewType").value;

let questionBox = document.getElementById("questions");


let questions = "";


if(type == "HR")
{

questions = `

<h3>HR Interview Questions</h3>

<ol>

<li>Tell me about yourself.</li>

<li>Why should we hire you?</li>

<li>What are your strengths and weaknesses?</li>

<li>Why do you want to join our company?</li>

<li>Where do you see yourself after 5 years?</li>

</ol>

`;

}



else if(type == "Technical")
{

questions = `

<h3>Technical Interview Questions</h3>

<ol>

<li>Explain Object Oriented Programming concepts.</li>

<li>Difference between Array and Linked List.</li>

<li>Explain your project architecture.</li>

<li>What is database normalization?</li>

<li>Difference between GET and POST method.</li>

</ol>

`;

}



else if(type == "Behavioral")
{

questions = `

<h3>Behavioral Interview Questions</h3>

<ol>

<li>Describe a challenging situation you faced.</li>

<li>How do you handle teamwork problems?</li>

<li>Tell me about a time you failed and learned.</li>

<li>How do you manage pressure?</li>

<li>Describe your leadership experience.</li>

</ol>

`;

}


questionBox.innerHTML = questions;


}


</script>


</head>


<body>


<div class="container">


<div class="header">

<h1>
AI Interview Coach
</h1>

<p>
Practice interviews with AI guidance
</p>

</div>





<div class="card">


<h2>
Select Interview Type
</h2>


<select id="interviewType" onchange="showQuestions()">


<option value="">
-- Select Type --
</option>


<option value="HR">
HR Interview
</option>


<option value="Technical">
Technical Interview
</option>


<option value="Behavioral">
Behavioral Interview
</option>


</select>


</div>






<div class="card" id="questions">


<h3>
Select interview type to see questions
</h3>


</div>







<div class="card">


<h2>
Answer Practice
</h2>

<form action="submit_interview.php" method="POST">


<input type="hidden" name="interview_type" value="HR">


<input type="hidden" name="question" 
value="Tell me about yourself">


<textarea 
name="answer"
placeholder="Write your answer here..."
required>

</textarea>


<br>


<button type="submit">

Submit Answer

</button>


</form>

<br>



</div>





</div>


</body>


</html>