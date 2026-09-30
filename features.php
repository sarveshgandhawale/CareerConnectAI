<?php
require_once __DIR__ . '/config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Features | CareerConnect AI</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/nav.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/footer.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/features.css'); ?>">
</head>
<body>

<?php include __DIR__ . '/components/navbar.php'; ?>

<!-- HERO -->
<section class="features-hero">
    <div class="hero-badge">
        <i class="fa-solid fa-sparkles"></i> POWERFUL CAREER MODULES
    </div>
    <h1>Everything You Need to <span>Build Your Career.</span></h1>
    <p>
        CareerConnect AI brings personalized career guidance, diagnostic assessments, ATS resume scanning, and AI-powered mock interviews into one smart student platform.
    </p>
</section>

<!-- FEATURES GRID -->
<section class="features-section">
    <div class="section-heading">
        <span>OUR FEATURES</span>
        <h2>Smart Tools for Your Placement Journey</h2>
        <p>
            From discovering your core strengths to becoming high-stakes interview ready, CareerConnect AI supports every step of your journey.
        </p>
    </div>

    <div class="features-grid">
        <!-- FEATURE 1 -->
        <div class="feature-card featured-card">
            <div class="feature-icon purple"><i class="fa-solid fa-robot"></i></div>
            <div class="feature-number">01</div>
            <h3>AI Career Guidance</h3>
            <p>Get personalized recommendations, match scores, and 6-month milestones based on your coding proficiency and degree.</p>
            <a href="<?php echo url('career/career_guidance.php'); ?>">
                Explore Guidance <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- FEATURE 2 -->
        <div class="feature-card">
            <div class="feature-icon orange"><i class="fa-solid fa-brain"></i></div>
            <div class="feature-number">02</div>
            <h3>Skill Diagnostic Test</h3>
            <p>Evaluate your technical foundations, algorithmic problem solving, and agile communication readiness.</p>
            <a href="<?php echo url('assessment/assessment.php'); ?>">
                Take Skill Test <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- FEATURE 3 -->
        <div class="feature-card">
            <div class="feature-icon blue"><i class="fa-solid fa-route"></i></div>
            <div class="feature-number">03</div>
            <h3>Personalized Career Path</h3>
            <p>Follow a structured roadmap showing tools, libraries, and portfolio projects to master for your target role.</p>
            <a href="<?php echo url('career_path.php'); ?>">
                View Career Paths <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- FEATURE 4 -->
        <div class="feature-card featured-card">
            <div class="feature-icon red"><i class="fa-solid fa-microphone-lines"></i></div>
            <div class="feature-number">04</div>
            <h3>AI Mock Interview Coach</h3>
            <p>Practice voice and text answers with instant 4-dimension scoring, strengths, improvements, and ideal model answers.</p>
            <a href="<?php echo url('interview/mock_interview.php'); ?>">
                Start Interview <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- FEATURE 5 -->
        <div class="feature-card">
            <div class="feature-icon green"><i class="fa-solid fa-file-circle-check"></i></div>
            <div class="feature-number">05</div>
            <h3>AI ATS Resume Scanner</h3>
            <p>Upload or create your resume and receive AI keyword suggestions, formatting tips, and ATS compatibility scores.</p>
            <a href="<?php echo url('resume/resume_analysis.php'); ?>">
                Scan Resume <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- FEATURE 6 -->
        <div class="feature-card">
            <div class="feature-icon pink"><i class="fa-solid fa-circle-question"></i></div>
            <div class="feature-number">06</div>
            <h3>Tech Question Bank</h3>
            <p>Access hundreds of curated technical and HR questions across full-stack web, Python, DSA, and Cloud engineering.</p>
            <a href="<?php echo url('questions/question_bank.php'); ?>">
                Question Bank <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- FEATURE 7 -->
        <div class="feature-card">
            <div class="feature-icon teal"><i class="fa-solid fa-chart-line"></i></div>
            <div class="feature-number">07</div>
            <h3>Student Dashboard</h3>
            <p>Track test scores, interview performance, ATS resume status, and audit logs from one unified console.</p>
            <a href="<?php echo url('dashboard/dashboard.php'); ?>">
                View Dashboard <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- FEATURE 8 -->
        <div class="feature-card featured-card">
            <div class="feature-icon yellow"><i class="fa-solid fa-bullseye"></i></div>
            <div class="feature-number">08</div>
            <h3>Job Readiness Score</h3>
            <p>Get holistic readiness ratings to see where you stand in technical aptitude before applying for campus placements.</p>
            <a href="<?php echo url('dashboard/dashboard.php'); ?>">
                Check Readiness <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- HOW IT HELPS -->
<section class="feature-cta">
    <div class="cta-content">
        <span>YOUR CAREER. YOUR ROADMAP.</span>
        <h2>Discover Where You Are &amp; Where You Can Go.</h2>
        <p>
            CareerConnect AI combines your skills, interests, resume, and interview performance to give you a clear direction in tech.
        </p>
        <a href="<?php echo url('career/career_guidance.php'); ?>">
            Build My Career Roadmap <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="cta-visual">
        <div class="visual-circle"><i class="fa-solid fa-rocket"></i></div>
        <div class="floating-card card-one"><i class="fa-solid fa-check text-success"></i> Skills Matched</div>
        <div class="floating-card card-two"><i class="fa-solid fa-chart-line text-primary"></i> Career Progress</div>
        <div class="floating-card card-three"><i class="fa-solid fa-star text-warning"></i> AI Recommendation</div>
    </div>
</section>

<?php include __DIR__ . '/components/footer.php'; ?>
</body>
</html>