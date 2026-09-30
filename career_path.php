<?php
require_once __DIR__ . '/config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Paths | CareerConnect AI</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/nav.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/footer.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/career_path.css'); ?>">
</head>
<body>

<?php include __DIR__ . '/components/navbar.php'; ?>

<!-- HERO -->
<section class="career-hero">
    <div class="career-badge">
        <i class="fa-solid fa-compass"></i> EXPLORE YOUR FUTURE
    </div>
    <h1>Find the Right <span>Career Path.</span></h1>
    <p>
        Explore top career tracks in technology, discover required skills, follow personalized 6-month roadmaps, and become job-ready with CareerConnect AI.
    </p>

    <div class="career-search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="careerSearch" placeholder="Search career e.g. Web Developer, AI, Cloud...">
    </div>
</section>

<!-- FILTER & GRID -->
<section class="career-section">
    <div class="career-header">
        <div>
            <span class="section-label">CAREER EXPLORER</span>
            <h2>Explore Tech Career Paths</h2>
        </div>

        <div class="career-filters">
            <button class="filter-btn active" data-category="all">All</button>
            <button class="filter-btn" data-category="development">Development</button>
            <button class="filter-btn" data-category="data">Data &amp; AI</button>
            <button class="filter-btn" data-category="cloud">Cloud &amp; DevOps</button>
            <button class="filter-btn" data-category="security">Security</button>
        </div>
    </div>

    <div class="career-grid">
        <!-- SOFTWARE DEVELOPER -->
        <div class="career-card" data-category="development" data-name="full stack web developer php javascript">
            <div class="career-card-top">
                <div class="career-icon orange"><i class="fa-solid fa-code"></i></div>
                <span class="career-level">High Demand</span>
            </div>
            <h3>Full Stack Web Developer</h3>
            <p>Build scalable dynamic web applications, secure REST APIs, and database-driven solutions.</p>
            <div class="career-skills">
                <span>PHP 8</span><span>MySQL</span><span>JavaScript</span><span>React</span><span>Git</span>
            </div>
            <div class="career-card-bottom">
                <span><i class="fa-regular fa-clock"></i> 6–9 Months</span>
                <a href="<?php echo url('career/career_guidance.php'); ?>">Explore Roadmap <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>

        <!-- AI / ML ENGINEER -->
        <div class="career-card" data-category="data" data-name="ai machine learning engineer data scientist python">
            <div class="career-card-top">
                <div class="career-icon purple"><i class="fa-solid fa-robot"></i></div>
                <span class="career-level">Advanced</span>
            </div>
            <h3>AI / Machine Learning Engineer</h3>
            <p>Design neural networks, computer vision, natural language processing, and LLM applications.</p>
            <div class="career-skills">
                <span>Python</span><span>PyTorch</span><span>TensorFlow</span><span>LangChain</span>
            </div>
            <div class="career-card-bottom">
                <span><i class="fa-regular fa-clock"></i> 8–12 Months</span>
                <a href="<?php echo url('career/career_guidance.php'); ?>">Explore Roadmap <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>

        <!-- DATA SCIENTIST -->
        <div class="career-card" data-category="data" data-name="data scientist analytics sql tableau">
            <div class="career-card-top">
                <div class="career-icon blue"><i class="fa-solid fa-chart-column"></i></div>
                <span class="career-level">Intermediate</span>
            </div>
            <h3>Data Scientist &amp; Analyst</h3>
            <p>Extract predictive insights, build statistical models, and design business intelligence dashboards.</p>
            <div class="career-skills">
                <span>SQL</span><span>Python (Pandas)</span><span>Tableau</span><span>Statistics</span>
            </div>
            <div class="career-card-bottom">
                <span><i class="fa-regular fa-clock"></i> 6–10 Months</span>
                <a href="<?php echo url('career/career_guidance.php'); ?>">Explore Roadmap <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>

        <!-- CLOUD & DEVOPS -->
        <div class="career-card" data-category="cloud" data-name="cloud solutions architect devops engineer docker aws">
            <div class="career-card-top">
                <div class="career-icon green"><i class="fa-solid fa-cloud"></i></div>
                <span class="career-level">High Demand</span>
            </div>
            <h3>Cloud &amp; DevOps Engineer</h3>
            <p>Deploy resilient infrastructure, configure automated CI/CD pipelines, and manage containers.</p>
            <div class="career-skills">
                <span>AWS</span><span>Docker</span><span>Kubernetes</span><span>Linux</span><span>Terraform</span>
            </div>
            <div class="career-card-bottom">
                <span><i class="fa-regular fa-clock"></i> 6–12 Months</span>
                <a href="<?php echo url('career/career_guidance.php'); ?>">Explore Roadmap <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>

        <!-- CYBER SECURITY -->
        <div class="career-card" data-category="security" data-name="cyber security analyst ethical hacking linux">
            <div class="career-card-top">
                <div class="career-icon red"><i class="fa-solid fa-shield-halved"></i></div>
                <span class="career-level">Critical Need</span>
            </div>
            <h3>Cyber Security Analyst</h3>
            <p>Audit system vulnerabilities, prevent security threats, and perform penetration testing.</p>
            <div class="career-skills">
                <span>Networking</span><span>Linux</span><span>SIEM</span><span>OWASP Top 10</span>
            </div>
            <div class="career-card-bottom">
                <span><i class="fa-regular fa-clock"></i> 6–12 Months</span>
                <a href="<?php echo url('career/career_guidance.php'); ?>">Explore Roadmap <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>

        <!-- MOBILE APP DEVELOPER -->
        <div class="career-card" data-category="development" data-name="mobile app developer flutter react native">
            <div class="career-card-top">
                <div class="career-icon pink"><i class="fa-solid fa-mobile-screen-button"></i></div>
                <span class="career-level">Popular</span>
            </div>
            <h3>Mobile Application Developer</h3>
            <p>Build fluid cross-platform iOS and Android apps with beautiful user experiences.</p>
            <div class="career-skills">
                <span>Flutter (Dart)</span><span>React Native</span><span>REST APIs</span><span>Firebase</span>
            </div>
            <div class="career-card-bottom">
                <span><i class="fa-regular fa-clock"></i> 4–8 Months</span>
                <a href="<?php echo url('career/career_guidance.php'); ?>">Explore Roadmap <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

<!-- AI CTA -->
<section class="career-ai">
    <div class="ai-icon">
        <i class="fa-solid fa-wand-magic-sparkles"></i>
    </div>
    <div class="ai-content">
        <span>AI CAREER DISCOVERY</span>
        <h2>Not sure which career is right for you?</h2>
        <p>
            Take our AI skill assessment and get personalized career match scores, missing skills analysis, and a 6-month roadmap.
        </p>
    </div>
    <a href="<?php echo url('assessment/assessment.php'); ?>" class="ai-button">
        Start Diagnostic Test <i class="fa-solid fa-arrow-right ms-1"></i>
    </a>
</section>

<?php include __DIR__ . '/components/footer.php'; ?>

<script src="<?php echo asset('js/career_path.js'); ?>"></script>
</body>
</html>