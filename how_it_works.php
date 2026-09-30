<?php
require_once __DIR__ . '/config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How It Works | CareerConnect AI</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/nav.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/footer.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/how_it_works.css'); ?>">
</head>
<body>

<?php include __DIR__ . '/components/navbar.php'; ?>

<!-- HERO -->
<section class="how-hero">
    <div class="hero-content">
        <div class="hero-badge">
            <i class="fa-solid fa-sparkles"></i> AI-Powered Career Journey
        </div>
        <h1>Your Career Journey, <span>Made Smarter.</span></h1>
        <p>
            CareerConnect AI helps students discover the right tech track, improve their resume ATS score, practice mock interviews, and build in-demand skills — all in one unified portal.
        </p>
        <a href="#journey" class="hero-btn">
            Explore How It Works <i class="fa-solid fa-arrow-down"></i>
        </a>
    </div>
</section>

<!-- CAREER JOURNEY 5-STEPS -->
<section class="journey-section" id="journey">
    <div class="section-heading">
        <span class="section-label">SIMPLE 5-STEP PROCESS</span>
        <h2>From Student To <span>Career Ready</span></h2>
        <p>
            Follow a personalized journey designed to help you discover your strengths, build high-impact projects, and ace campus placements.
        </p>
    </div>

    <div class="journey-container">
        <!-- STEP 01 -->
        <div class="journey-card">
            <div class="step-number">01</div>
            <div class="step-icon"><i class="fa-solid fa-user-plus"></i></div>
            <div class="step-content">
                <span class="step-label">GET STARTED</span>
                <h3>Create Your Profile</h3>
                <p>Register on CareerConnect AI and specify your degree (BCA/MCA/B.Tech), coding experience, and tech interests.</p>
                <div class="feature-list">
                    <span><i class="fa-solid fa-check"></i> Student Profile</span>
                    <span><i class="fa-solid fa-check"></i> Degree Program</span>
                    <span><i class="fa-solid fa-check"></i> Coding Preferences</span>
                </div>
            </div>
        </div>

        <!-- STEP 02 -->
        <div class="journey-card">
            <div class="step-number">02</div>
            <div class="step-icon blue"><i class="fa-solid fa-list-check"></i></div>
            <div class="step-content">
                <span class="step-label">DIAGNOSTIC</span>
                <h3>Take Skill &amp; Aptitude Assessment</h3>
                <p>Answer 10 diagnostic questions across Technical Core, Algorithmic Logic, and Agile Soft Skills.</p>
                <div class="feature-list">
                    <span><i class="fa-solid fa-check"></i> 15-Minute Timer</span>
                    <span><i class="fa-solid fa-check"></i> Multi-Pillar Scoring</span>
                    <span><i class="fa-solid fa-check"></i> Strengths Identification</span>
                </div>
            </div>
        </div>

        <!-- STEP 03 -->
        <div class="journey-card">
            <div class="step-number">03</div>
            <div class="step-icon purple"><i class="fa-solid fa-compass"></i></div>
            <div class="step-content">
                <span class="step-label">AI ROADMAP</span>
                <h3>Get Gemini AI Career Guidance</h3>
                <p>Gemini AI evaluates your diagnostic results to recommend the best job roles, missing skills, and a 6-month roadmap.</p>
                <div class="feature-list">
                    <span><i class="fa-solid fa-check"></i> Career Match Score</span>
                    <span><i class="fa-solid fa-check"></i> Missing Skills List</span>
                    <span><i class="fa-solid fa-check"></i> 6-Month Action Milestones</span>
                </div>
            </div>
        </div>

        <!-- STEP 04 -->
        <div class="journey-card">
            <div class="step-number">04</div>
            <div class="step-icon green"><i class="fa-solid fa-file-circle-check"></i></div>
            <div class="step-content">
                <span class="step-label">ATS SCAN</span>
                <h3>Build &amp; Optimize ATS Resume</h3>
                <p>Use the ATS builder to generate recruiter-ready resumes and scan ATS compatibility scores for your target role.</p>
                <div class="feature-list">
                    <span><i class="fa-solid fa-check"></i> ATS Compatibility Score</span>
                    <span><i class="fa-solid fa-check"></i> Missing Keyword Cloud</span>
                    <span><i class="fa-solid fa-check"></i> PDF Print Format</span>
                </div>
            </div>
        </div>

        <!-- STEP 05 -->
        <div class="journey-card">
            <div class="step-number">05</div>
            <div class="step-icon dark"><i class="fa-solid fa-microphone-lines"></i></div>
            <div class="step-content">
                <span class="step-label">INTERVIEW COACH</span>
                <h3>Practice with AI Mock Interview</h3>
                <p>Speak your answers into your microphone or type them to get instant 4-dimension scoring and ideal model answers.</p>
                <div class="feature-list">
                    <span><i class="fa-solid fa-check"></i> Voice Speech-to-Text</span>
                    <span><i class="fa-solid fa-check"></i> Hiring Manager Feedback</span>
                    <span><i class="fa-solid fa-check"></i> Ideal Model Answers</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- AI SECTION -->
<section class="ai-section">
    <div class="ai-wrapper">
        <div class="ai-content">
            <span class="section-label">POWERED BY AI</span>
            <h2>One Platform. <span>Complete Placement Ecosystem.</span></h2>
            <p>
                CareerConnect AI combines intelligent Gemini features to support students from freshman year to final placement offers.
            </p>

            <div class="ai-features">
                <div class="ai-feature">
                    <div class="ai-feature-icon"><i class="fa-solid fa-route"></i></div>
                    <div>
                        <h4>Career Guidance</h4>
                        <p>Discover careers that match your skills and interests.</p>
                    </div>
                </div>

                <div class="ai-feature">
                    <div class="ai-feature-icon"><i class="fa-solid fa-file-circle-check"></i></div>
                    <div>
                        <h4>Resume Analysis</h4>
                        <p>Get AI-powered feedback to improve ATS keyword rankings.</p>
                    </div>
                </div>

                <div class="ai-feature">
                    <div class="ai-feature-icon"><i class="fa-solid fa-comments"></i></div>
                    <div>
                        <h4>AI Interview Coach</h4>
                        <p>Practice HR and technical questions with actionable coaching.</p>
                    </div>
                </div>

                <div class="ai-feature">
                    <div class="ai-feature-icon"><i class="fa-solid fa-layer-group"></i></div>
                    <div>
                        <h4>Question Bank</h4>
                        <p>Access curated role-based technical questions and real interview sets.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI VISUAL -->
        <div class="ai-visual">
            <div class="ai-circle">
                <div class="ai-center">
                    <i class="fa-solid fa-brain"></i>
                    <span>AI</span>
                </div>
                <div class="orbit orbit-1"><i class="fa-solid fa-route"></i></div>
                <div class="orbit orbit-2"><i class="fa-solid fa-file"></i></div>
                <div class="orbit orbit-3"><i class="fa-solid fa-microphone"></i></div>
                <div class="orbit orbit-4"><i class="fa-solid fa-comments"></i></div>
            </div>
        </div>
    </div>
</section>

<!-- FINAL CTA -->
<section class="cta-section">
    <div class="cta-content">
        <div class="cta-icon"><i class="fa-solid fa-rocket"></i></div>
        <span class="section-label">YOUR FUTURE STARTS HERE</span>
        <h2>Ready to Build Your <span>Tech Career?</span></h2>
        <p>
            Start your preparation journey today and let CareerConnect AI guide you toward your career goals.
        </p>
        <div class="cta-buttons">
            <a href="<?php echo url('auth/register.php'); ?>" class="cta-primary">
                Get Started <i class="fa-solid fa-arrow-right"></i>
            </a>
            <a href="<?php echo url('auth/login.php'); ?>" class="cta-secondary">
                Login
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/components/footer.php'; ?>
</body>
</html>