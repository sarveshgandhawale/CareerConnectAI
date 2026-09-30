<?php
require_once __DIR__ . '/../config/db.php';
requireLogin();

$userName = htmlspecialchars($_SESSION['name'] ?? 'Student');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AI Mock Interview Coach | CareerConnect AI</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/interview_coach.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/nav.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/footer.css'); ?>">
</head>
<body class="bg-light">

<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-5">

    <!-- HEADER HERO -->
    <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5 bg-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2">
                    <i class="fa-solid fa-sparkles me-1"></i> Interactive AI Coach
                </span>
                <h1 class="fw-bold display-6 text-dark mb-2">
                    <i class="fa-solid fa-microphone-lines text-primary me-2"></i> AI Mock Interview Coach
                </h1>
                <p class="lead text-muted mb-0">
                    Practice real interview questions and receive instant multi-dimensional scoring, strengths, and ideal answers powered by Gemini AI.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="interview_history.php" class="btn btn-outline-primary btn-lg px-4 py-2 fw-semibold">
                    <i class="fa-solid fa-clock-rotate-left me-1"></i> My Scorecards
                </a>
            </div>
        </div>
    </div>

    <!-- MAIN PRACTICE INTERFACE -->
    <div class="row g-4">
        <!-- LEFT: QUESTION SELECTOR -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <h5 class="fw-bold text-dark mb-3">
                    <i class="fa-solid fa-sliders text-primary me-2"></i> 1. Select Interview Domain
                </h5>

                <div class="mb-4">
                    <label class="form-label fw-semibold text-secondary">Interview Category</label>
                    <select id="interviewTypeSelect" class="form-select form-select-lg" onchange="updateQuestionBank()">
                        <option value="HR" selected>HR &amp; Cultural Fit Interview</option>
                        <option value="Technical">Technical (Full Stack / PHP / Data)</option>
                        <option value="Behavioral">Behavioral (STAR Method Scenarios)</option>
                    </select>
                </div>

                <h6 class="fw-bold text-secondary mb-2">Select a Question to Practice:</h6>
                <div class="list-group" id="questionList">
                    <!-- Dynamic questions inserted here via JS -->
                </div>

                <div class="mt-4 p-3 bg-light rounded-3">
                    <h6 class="fw-bold text-dark mb-1"><i class="fa-solid fa-lightbulb text-warning me-1"></i> Pro Tip</h6>
                    <small class="text-muted">
                        Aim for 50-150 words per answer. Use specific technical terms, frameworks, and metrics (e.g. "improved load time by 30%").
                    </small>
                </div>
            </div>
        </div>

        <!-- RIGHT: ANSWER PAD -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 bg-white h-100">
                <h5 class="fw-bold text-dark mb-3">
                    <i class="fa-solid fa-pen-nib text-primary me-2"></i> 2. Formulate Your Response
                </h5>

                <form action="interview_process.php" method="POST" id="interviewForm">
                    <input type="hidden" name="interview_type" id="hiddenType" value="HR">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">Target Question</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-circle-question text-primary"></i></span>
                            <input type="text" name="question" id="activeQuestionInput" class="form-control fw-semibold" value="Tell me about yourself and your career aspirations in software development." required>
                        </div>
                        <small class="text-muted">You can edit this question or type your own custom interview question above.</small>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-semibold text-secondary mb-0">Your Answer</label>
                            <small class="text-muted" id="wordCount">0 words</small>
                        </div>
                        <textarea name="answer" id="answerTextarea" class="form-control" rows="8" placeholder="Type your full interview response here..." required style="font-size: 15px; line-height: 1.6;"></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg py-3 fw-semibold shadow" id="submitBtn">
                            <i class="fa-solid fa-paper-plane me-2"></i> Submit Answer for AI Evaluation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script>
const questionBank = {
    "HR": [
        "Tell me about yourself and your career aspirations in software development.",
        "Why are you interested in joining our tech company as a fresher?",
        "What are your greatest technical strengths and one area you are working to improve?",
        "Where do you see yourself professionally in the next 3 to 5 years?",
        "How do you handle project deadlines when priorities suddenly change?"
    ],
    "Technical": [
        "Explain the core principles of Object-Oriented Programming (OOP) with examples.",
        "What is the difference between GET and POST HTTP methods, and when would you use each?",
        "Explain database normalization (1NF, 2NF, 3NF) and why it is important.",
        "How do you optimize SQL queries and database indexes in web applications?",
        "Explain the architecture of a full-stack web application with frontend, backend API, and database."
    ],
    "Behavioral": [
        "Describe a challenging bug or technical problem you faced in a project and how you solved it.",
        "Tell me about a time you had a disagreement with a team member during a project. How did you resolve it?",
        "Describe a situation where you had to learn a completely new technology or language on short notice.",
        "Give an example of a time you failed or made a mistake in code. What did you learn?",
        "How do you manage your time when balancing multiple college projects and assignments?"
    ]
};

function updateQuestionBank() {
    const type = document.getElementById('interviewTypeSelect').value;
    document.getElementById('hiddenType').value = type;
    const list = document.getElementById('questionList');
    list.innerHTML = '';

    const questions = questionBank[type] || questionBank["HR"];
    questions.forEach((q, idx) => {
        const a = document.createElement('a');
        a.href = 'javascript:void(0)';
        a.className = 'list-group-item list-group-item-action py-3 ' + (idx === 0 ? 'active' : '');
        a.innerHTML = `<i class="fa-solid fa-chevron-right me-2 small"></i> ${q}`;
        a.onclick = function() {
            document.querySelectorAll('#questionList a').forEach(el => el.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('activeQuestionInput').value = q;
            document.getElementById('answerTextarea').focus();
        };
        list.appendChild(a);
    });

    document.getElementById('activeQuestionInput').value = questions[0];
}

document.addEventListener('DOMContentLoaded', function() {
    updateQuestionBank();

    const textarea = document.getElementById('answerTextarea');
    const wordCounter = document.getElementById('wordCount');
    textarea.addEventListener('input', function() {
        const text = this.value.trim();
        const words = text === '' ? 0 : text.split(/\s+/).length;
        wordCounter.textContent = `${words} words`;
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>