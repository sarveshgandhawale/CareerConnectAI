<?php
require_once __DIR__ . '/../config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tech Interview Question Bank | CareerConnect AI</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/nav.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/footer.css'); ?>">
    <style>
        .q-card {
            border-left: 4px solid #1e3c72;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .q-card:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.07);
            transform: translateY(-2px);
        }
        .filter-pill.active {
            background-color: #1e3c72 !important;
            color: #fff !important;
        }
    </style>
</head>
<body class="bg-light">

<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-5">

    <!-- HERO -->
    <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5 bg-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2">
                    <i class="fa-solid fa-book-bookmark me-1"></i> Comprehensive Tech Repository
                </span>
                <h1 class="fw-bold display-6 text-dark mb-2">
                    <i class="fa-solid fa-circle-question text-primary me-2"></i> Technical &amp; HR Interview Question Bank
                </h1>
                <p class="lead text-muted mb-0">
                    Master essential interview questions for software developers, data scientists, cloud engineers, and freshers with expert ideal answers.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="<?php echo url('interview/mock_interview.php'); ?>" class="btn btn-primary btn-lg px-4 py-3 fw-semibold shadow-sm">
                    <i class="fa-solid fa-microphone-lines me-2"></i> Practice AI Mock Interview
                </a>
            </div>
        </div>
    </div>

    <!-- SEARCH & FILTERS -->
    <div class="card shadow-sm border-0 rounded-4 p-4 bg-white mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" id="searchInput" class="form-control bg-light border-start-0" placeholder="Search keywords e.g. OOP, REST, MySQL, STAR..." oninput="filterQuestions()">
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex flex-wrap gap-2 justify-content-md-end" id="categoryFilters">
                    <button class="btn btn-outline-primary btn-sm filter-pill active" onclick="setCategory('all', this)">All</button>
                    <button class="btn btn-outline-primary btn-sm filter-pill" onclick="setCategory('hr', this)">HR &amp; Behavioral</button>
                    <button class="btn btn-outline-primary btn-sm filter-pill" onclick="setCategory('web', this)">Web &amp; PHP / MySQL</button>
                    <button class="btn btn-outline-primary btn-sm filter-pill" onclick="setCategory('ai', this)">Python &amp; AI / ML</button>
                    <button class="btn btn-outline-primary btn-sm filter-pill" onclick="setCategory('dsa', this)">DSA &amp; OOP</button>
                    <button class="btn btn-outline-primary btn-sm filter-pill" onclick="setCategory('cloud', this)">Cloud &amp; DevOps</button>
                </div>
            </div>
        </div>
    </div>

    <!-- QUESTIONS GRID -->
    <div class="row g-4" id="questionsGrid">

        <!-- Q1: HR -->
        <div class="col-md-6 question-item" data-cat="hr" data-keywords="tell me about yourself introduction hr fresher career">
            <div class="card q-card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill fw-semibold">HR Round</span>
                    <span class="badge bg-success-subtle text-success">Beginner Friendly</span>
                </div>
                <h5 class="fw-bold text-dark mb-2">Tell me about yourself and why you chose a career in software development.</h5>
                <p class="text-muted small mb-3">Core question to assess communication, career motivation, and academic background.</p>
                <details class="mb-3">
                    <summary class="text-primary fw-semibold" style="cursor: pointer;">
                        <i class="fa-solid fa-lightbulb me-1"></i> View Ideal Answer &amp; Key Points
                    </summary>
                    <div class="p-3 bg-light rounded-3 mt-2 small text-secondary" style="line-height: 1.7;">
                        <strong>Structure:</strong> Present -> Past (Academics & Projects) -> Future (Company Goals).<br>
                        <strong>Key points:</strong> Mention your degree, 1-2 prominent projects built (like CareerConnect AI), core technical proficiencies, and passion for continuous learning.
                    </div>
                </details>
                <div class="mt-auto pt-2 border-top">
                    <a href="<?php echo url('interview/mock_interview.php'); ?>" class="btn btn-sm btn-outline-primary w-100 fw-semibold">
                        <i class="fa-solid fa-microphone me-1"></i> Practice This in Mock Interview
                    </a>
                </div>
            </div>
        </div>

        <!-- Q2: WEB / PHP -->
        <div class="col-md-6 question-item" data-cat="web" data-keywords="php sql prepared statements injection security get post web">
            <div class="card q-card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-info-subtle text-info px-3 py-1 rounded-pill fw-semibold">Web &amp; Backend</span>
                    <span class="badge bg-warning-subtle text-warning">High Frequency</span>
                </div>
                <h5 class="fw-bold text-dark mb-2">Why should developers always use Prepared Statements with parameterized queries in MySQL / PHP?</h5>
                <p class="text-muted small mb-3">Tests security awareness regarding database operations and SQL Injection vulnerabilities.</p>
                <details class="mb-3">
                    <summary class="text-primary fw-semibold" style="cursor: pointer;">
                        <i class="fa-solid fa-lightbulb me-1"></i> View Ideal Answer &amp; Key Points
                    </summary>
                    <div class="p-3 bg-light rounded-3 mt-2 small text-secondary" style="line-height: 1.7;">
                        <strong>Answer:</strong> Prepared statements separate the SQL code structure from user-supplied data inputs. The database parses and compiles the query template first before binding input parameters as raw literal data types, eliminating SQL Injection entirely.
                    </div>
                </details>
                <div class="mt-auto pt-2 border-top">
                    <a href="<?php echo url('interview/mock_interview.php'); ?>" class="btn btn-sm btn-outline-primary w-100 fw-semibold">
                        <i class="fa-solid fa-microphone me-1"></i> Practice This in Mock Interview
                    </a>
                </div>
            </div>
        </div>

        <!-- Q3: DSA -->
        <div class="col-md-6 question-item" data-cat="dsa" data-keywords="oop encapsulation polymorphism inheritance abstraction dsa">
            <div class="card q-card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill fw-semibold">DSA &amp; OOP</span>
                    <span class="badge bg-danger-subtle text-danger">Core Fundamental</span>
                </div>
                <h5 class="fw-bold text-dark mb-2">Explain the Four Pillars of OOP with simple real-world examples.</h5>
                <p class="text-muted small mb-3">Evaluates structural programming knowledge and architectural reasoning.</p>
                <details class="mb-3">
                    <summary class="text-primary fw-semibold" style="cursor: pointer;">
                        <i class="fa-solid fa-lightbulb me-1"></i> View Ideal Answer &amp; Key Points
                    </summary>
                    <div class="p-3 bg-light rounded-3 mt-2 small text-secondary" style="line-height: 1.7;">
                        1. <strong>Encapsulation:</strong> Data hiding using access modifiers (private properties with getters/setters).<br>
                        2. <strong>Abstraction:</strong> Hiding internal implementation complexity (e.g. driving a car without knowing internal engine combustion).<br>
                        3. <strong>Inheritance:</strong> Code reuse through parent-child classes.<br>
                        4. <strong>Polymorphism:</strong> Same interface with different underlying forms (method overriding & overloading).
                    </div>
                </details>
                <div class="mt-auto pt-2 border-top">
                    <a href="<?php echo url('interview/mock_interview.php'); ?>" class="btn btn-sm btn-outline-primary w-100 fw-semibold">
                        <i class="fa-solid fa-microphone me-1"></i> Practice This in Mock Interview
                    </a>
                </div>
            </div>
        </div>

        <!-- Q4: AI / ML -->
        <div class="col-md-6 question-item" data-cat="ai" data-keywords="machine learning overfitting underfitting regularization ai python">
            <div class="card q-card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-purple-subtle text-primary px-3 py-1 rounded-pill fw-semibold">AI &amp; Data Science</span>
                    <span class="badge bg-info-subtle text-info">Intermediate</span>
                </div>
                <h5 class="fw-bold text-dark mb-2">What is Overfitting in Machine Learning models and what techniques prevent it?</h5>
                <p class="text-muted small mb-3">Assesses model generalizability and validation engineering.</p>
                <details class="mb-3">
                    <summary class="text-primary fw-semibold" style="cursor: pointer;">
                        <i class="fa-solid fa-lightbulb me-1"></i> View Ideal Answer &amp; Key Points
                    </summary>
                    <div class="p-3 bg-light rounded-3 mt-2 small text-secondary" style="line-height: 1.7;">
                        <strong>Answer:</strong> Overfitting occurs when a model memorizes training noise instead of learning underlying patterns, leading to high training accuracy but poor validation performance.<br>
                        <strong>Remedies:</strong> Cross-validation (K-fold), Regularization (L1 Lasso, L2 Ridge), Dropout layers in neural nets, pruning, and collecting more diverse training data.
                    </div>
                </details>
                <div class="mt-auto pt-2 border-top">
                    <a href="<?php echo url('interview/mock_interview.php'); ?>" class="btn btn-sm btn-outline-primary w-100 fw-semibold">
                        <i class="fa-solid fa-microphone me-1"></i> Practice This in Mock Interview
                    </a>
                </div>
            </div>
        </div>

        <!-- Q5: BEHAVIORAL -->
        <div class="col-md-6 question-item" data-cat="hr" data-keywords="star behavioral deadline conflict teamwork problem solving">
            <div class="card q-card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-warning-subtle text-dark px-3 py-1 rounded-pill fw-semibold">Behavioral Round</span>
                    <span class="badge bg-success-subtle text-success">STAR Method</span>
                </div>
                <h5 class="fw-bold text-dark mb-2">Tell me about a challenging bug or deadline blocker you faced in a project and how you solved it.</h5>
                <p class="text-muted small mb-3">Tests resilience, analytical debugging, and teamwork under pressure.</p>
                <details class="mb-3">
                    <summary class="text-primary fw-semibold" style="cursor: pointer;">
                        <i class="fa-solid fa-lightbulb me-1"></i> View Ideal Answer &amp; Key Points
                    </summary>
                    <div class="p-3 bg-light rounded-3 mt-2 small text-secondary" style="line-height: 1.7;">
                        <strong>Use STAR:</strong><br>
                        - <strong>Situation:</strong> Describe the college or live project context.<br>
                        - <strong>Task:</strong> State the specific blocker or performance issue.<br>
                        - <strong>Action:</strong> Explain how you isolated root causes with logs and testing.<br>
                        - <strong>Result:</strong> State the measurable improvement (e.g. 0 error rate, on-time delivery).
                    </div>
                </details>
                <div class="mt-auto pt-2 border-top">
                    <a href="<?php echo url('interview/mock_interview.php'); ?>" class="btn btn-sm btn-outline-primary w-100 fw-semibold">
                        <i class="fa-solid fa-microphone me-1"></i> Practice This in Mock Interview
                    </a>
                </div>
            </div>
        </div>

        <!-- Q6: CLOUD & DEVOPS -->
        <div class="col-md-6 question-item" data-cat="cloud" data-keywords="docker containers virtual machines devops cloud ci cd">
            <div class="card q-card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill fw-semibold">Cloud &amp; DevOps</span>
                    <span class="badge bg-primary-subtle text-primary">Industry Essential</span>
                </div>
                <h5 class="fw-bold text-dark mb-2">What is the key architectural difference between Docker containers and Virtual Machines (VMs)?</h5>
                <p class="text-muted small mb-3">Tests containerization knowledge, resource utilization, and virtualization models.</p>
                <details class="mb-3">
                    <summary class="text-primary fw-semibold" style="cursor: pointer;">
                        <i class="fa-solid fa-lightbulb me-1"></i> View Ideal Answer &amp; Key Points
                    </summary>
                    <div class="p-3 bg-light rounded-3 mt-2 small text-secondary" style="line-height: 1.7;">
                        <strong>Answer:</strong> Virtual Machines run full guest OS instances on top of a hypervisor, consuming high memory and taking minutes to boot. Docker containers share the host operating system kernel, packaging only app dependencies and binaries, making them lightweight (MBs) and booting in milliseconds.
                    </div>
                </details>
                <div class="mt-auto pt-2 border-top">
                    <a href="<?php echo url('interview/mock_interview.php'); ?>" class="btn btn-sm btn-outline-primary w-100 fw-semibold">
                        <i class="fa-solid fa-microphone me-1"></i> Practice This in Mock Interview
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script>
let currentCategory = 'all';

function setCategory(cat, btn) {
    currentCategory = cat;
    document.querySelectorAll('#categoryFilters .filter-pill').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    filterQuestions();
}

function filterQuestions() {
    const query = document.getElementById('searchInput').value.toLowerCase().trim();
    const items = document.querySelectorAll('.question-item');

    items.forEach(item => {
        const itemCat = item.getAttribute('data-cat');
        const keywords = item.getAttribute('data-keywords').toLowerCase();
        const text = item.textContent.toLowerCase();

        const matchCat = (currentCategory === 'all' || itemCat === currentCategory);
        const matchQuery = (query === '' || keywords.includes(query) || text.includes(query));

        if (matchCat && matchQuery) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
