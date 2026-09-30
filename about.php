<?php
require_once __DIR__ . '/config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | CareerConnect AI</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/nav.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/footer.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/about.css'); ?>">
</head>
<body>

<?php include __DIR__ . '/components/navbar.php'; ?>

<!-- HERO -->
<section class="about-hero">
    <div class="hero-left">
        <div class="badge">
            <i class="fa-solid fa-sparkles"></i> ABOUT CAREERCONNECT AI
        </div>
        <h1>Helping Students <span>Build Their Future.</span></h1>
        <p>
            CareerConnect AI is a smart student career platform designed to help students discover their strengths, choose the right tech career path, optimize ATS resumes, and master high-stakes technical interviews with Google Gemini AI.
        </p>
        <div class="hero-buttons">
            <a href="<?php echo url('auth/register.php'); ?>" class="primary-btn">
                Start Your Journey <i class="fa-solid fa-arrow-right"></i>
            </a>
            <a href="<?php echo url('how_it_works.php'); ?>" class="secondary-btn">
                How It Works
            </a>
        </div>
    </div>

    <!-- RIGHT VISUAL -->
    <div class="hero-right">
        <div class="visual-circle">
            <div class="ai-orbit orbit-one"></div>
            <div class="ai-orbit orbit-two"></div>
            <div class="ai-center">
                <i class="fa-solid fa-brain"></i>
                <span>AI</span>
            </div>
            <div class="floating-card card-one">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>Skills</span>
            </div>
            <div class="floating-card card-two">
                <i class="fa-solid fa-bullseye"></i>
                <span>Career</span>
            </div>
            <div class="floating-card card-three">
                <i class="fa-solid fa-file-lines"></i>
                <span>Resume</span>
            </div>
            <div class="floating-card card-four">
                <i class="fa-solid fa-microphone"></i>
                <span>Interview</span>
            </div>
        </div>
    </div>
</section>

<!-- ABOUT CONTENT -->
<section class="about-content">
    <div class="section-title">
        <span>WHO WE ARE</span>
        <h2>One Platform. Multiple Career Solutions.</h2>
        <p>
            CareerConnect AI brings important career preparation tools together into one cohesive, accessible platform.
        </p>
    </div>

    <div class="about-grid">
        <!-- OUR MISSION -->
        <div class="info-card mission">
            <div class="card-icon"><i class="fa-solid fa-flag"></i></div>
            <h3>Our Mission</h3>
            <p>
                Our mission is to make intelligent career guidance, ATS resume optimization, and mock interview coaching accessible and practical for every computer applications and engineering student.
            </p>
        </div>

        <!-- OUR VISION -->
        <div class="info-card vision">
            <div class="card-icon"><i class="fa-solid fa-eye"></i></div>
            <h3>Our Vision</h3>
            <p>
                We envision a future where every student can confidently navigate industry expectations, master required technical skills, and land rewarding tech jobs at top companies.
            </p>
        </div>

        <!-- OUR APPROACH -->
        <div class="info-card approach">
            <div class="card-icon"><i class="fa-solid fa-lightbulb"></i></div>
            <h3>Our Approach</h3>
            <p>
                We combine structured diagnostics, Gemini AI evaluations, speech recognition, ATS keyword filters, and actionable 6-month roadmaps to create a transformative career accelerator.
            </p>
        </div>
    </div>
</section>

<!-- WHAT WE OFFER -->
<section class="what-we-offer">
    <div class="section-title">
        <span>WHAT WE OFFER</span>
        <h2>Everything Students Need for Career Success</h2>
        <p>Comprehensive career preparation tools structured for modern campus placements.</p>
    </div>

    <div class="offer-grid">
        <div class="offer-card">
            <div class="offer-icon purple"><i class="fa-solid fa-compass"></i></div>
            <h3>AI Career Guidance</h3>
            <p>Get career suggestions, match scores, and 6-month milestones based on your skills and interests.</p>
        </div>

        <div class="offer-card">
            <div class="offer-icon orange"><i class="fa-solid fa-chart-simple"></i></div>
            <h3>Skill Assessment</h3>
            <p>Benchmark your technical foundations, problem solving logic, and agile soft skills.</p>
        </div>

        <div class="offer-card">
            <div class="offer-icon blue"><i class="fa-solid fa-road"></i></div>
            <h3>Career Roadmaps</h3>
            <p>Follow structured learning roadmaps with recommended tools, libraries, and portfolio projects.</p>
        </div>

        <div class="offer-card">
            <div class="offer-icon green"><i class="fa-solid fa-file-circle-check"></i></div>
            <h3>ATS Resume Scanner</h3>
            <p>Build recruiter-ready resumes and scan ATS compatibility scores for specific tech roles.</p>
        </div>

        <div class="offer-card">
            <div class="offer-icon pink"><i class="fa-solid fa-comments"></i></div>
            <h3>AI Mock Interview Coach</h3>
            <p>Practice voice and text answers with instant multi-dimensional scoring and model answers.</p>
        </div>

        <div class="offer-card">
            <div class="offer-icon yellow"><i class="fa-solid fa-circle-question"></i></div>
            <h3>Question Bank</h3>
            <p>Explore hundreds of technical and HR interview questions with expert solution guides.</p>
        </div>
    </div>
</section>

<!-- STATS -->
<section class="stats-section">
    <div class="stat">
        <div class="stat-icon"><i class="fa-solid fa-user-graduate"></i></div>
        <h2>6+</h2>
        <p>Career Modules</p>
    </div>

    <div class="stat">
        <div class="stat-icon"><i class="fa-solid fa-robot"></i></div>
        <h2>AI</h2>
        <p>Gemini Powered</p>
    </div>

    <div class="stat">
        <div class="stat-icon"><i class="fa-solid fa-file-lines"></i></div>
        <h2>1</h2>
        <p>Unified Portal</p>
    </div>

    <div class="stat">
        <div class="stat-icon"><i class="fa-solid fa-rocket"></i></div>
        <h2>100%</h2>
        <p>Student Focused</p>
    </div>
</section>

<!-- CTA -->
<section class="about-cta">
    <div class="cta-content">
        <span>YOUR FUTURE STARTS TODAY</span>
        <h2>Take Control of Your Tech Career.</h2>
        <p>
            Discover your strengths, build your skills, prepare for interviews, and move closer to your dream software development job.
        </p>
        <a href="<?php echo url('auth/register.php'); ?>">
            Create Your Free Account <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="cta-shape"><i class="fa-solid fa-rocket"></i></div>
</section>

<?php include __DIR__ . '/components/footer.php'; ?>
</body>
</html>