<?php
require_once __DIR__ . '/config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareerConnect AI | Smart Student Career &amp; Interview Portal</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/nav.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/footer.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/index.css'); ?>">
</head>
<body>

<?php include __DIR__ . '/components/navbar.php'; ?>

<!-- ================= HERO ================= -->
<section class="hero">
    <div class="hero-content">

        <!-- LEFT SIDE -->
        <div class="hero-left">
            <div class="career-badge mb-3">
                <i class="fa-solid fa-wand-magic-sparkles"></i> AI POWERED STUDENT CAREER PLATFORM
            </div>
            <h1>
                Your Career.<br>
                Our Guidance.<br>
                <span>Your Success.</span>
            </h1>

            <p class="hero-description">
                CareerConnect AI empowers students to discover top tech career tracks, build ATS-friendly resumes, and master real mock interviews with Google Gemini AI.
            </p>

            <div class="hero-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo url('dashboard/dashboard.php'); ?>" class="primary-btn">
                        <span>Go to My Dashboard</span> <i class="fa-solid fa-arrow-right"></i>
                    </a>
                <?php else: ?>
                    <a href="<?php echo url('auth/register.php'); ?>" class="primary-btn">
                        <span>Get Started </span> <i class="fa-solid fa-arrow-right"></i>
                    </a>
                <?php endif; ?>

                <a href="#features" class="secondary-btn">
                    <i class="fa-solid fa-grip"></i> <span>Explore Features</span>
                </a>
            </div>

            <!-- TRUST / SOCIAL PROOF -->
            <div class="hero-trust">
                <div class="trust-stars">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <span class="trust-rating"><strong>4.9 / 5</strong> Student Rating</span>
                <span class="trust-divider">•</span>
                <span class="trust-students">10,000+ Students Guided</span>
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="hero-right">
            <!-- STUDENT IMAGE -->
            <div class="student-image">
                <div class="student-image-backdrop"></div>
                <img src="<?php echo asset('images/boy1.png'); ?>" alt="CareerConnect AI Student" loading="eager">
            </div>

            <!-- FLOATING HIGHLIGHT CARDS -->
            <div class="hero-cards-container">
                <!-- AI CAREER CARD (TOP LEFT) -->
                <div class="floating-card career-card">
                    <div class="feature-icon purple">
                        <i class="fa-solid fa-compass"></i>
                    </div>
                    <div class="card-text">
                        <h4>AI Career Guidance</h4>
                        <p>Find your ideal career &amp; roadmap</p>
                    </div>
                </div>

                <!-- AI INTERVIEW CARD (TOP RIGHT) -->
                <div class="floating-card interview-card">
                    <div class="feature-icon green">
                        <i class="fa-solid fa-microphone-lines"></i>
                    </div>
                    <div class="card-text">
                        <h4>AI Mock Interview</h4>
                        <p>Voice practice &amp; instant score</p>
                    </div>
                </div>

                <!-- RESUME CARD (BOTTOM LEFT) -->
                <div class="floating-card resume-card">
                    <div class="feature-icon blue">
                        <i class="fa-solid fa-file-shield"></i>
                    </div>
                    <div class="card-text">
                        <h4>ATS Resume Builder</h4>
                        <p>Create &amp; scan for 90%+ ATS match</p>
                    </div>
                </div>

                <!-- SKILL CARD (BOTTOM RIGHT) -->
                <div class="floating-card skill-card">
                    <div class="feature-icon orange">
                        <i class="fa-solid fa-chart-simple"></i>
                    </div>
                    <div class="card-text">
                        <h4>Skill Diagnostic</h4>
                        <p>Adaptive test &amp; skill ratings</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ================= STATISTICS ================= -->
    <div class="statistics">
        <div class="stat">
            <div class="stat-icon orange-bg"><i class="fa-solid fa-user-graduate"></i></div>
            <div>
                <h3>10,000+</h3>
                <p>Students Mentored</p>
            </div>
        </div>

        <div class="stat">
            <div class="stat-icon green-bg"><i class="fa-solid fa-route"></i></div>
            <div>
                <h3>50+</h3>
                <p>Tech Career Tracks</p>
            </div>
        </div>

        <div class="stat">
            <div class="stat-icon purple-bg"><i class="fa-solid fa-file-circle-check"></i></div>
            <div>
                <h3>15,000+</h3>
                <p>Resumes Analyzed</p>
            </div>
        </div>

        <div class="stat">
            <div class="stat-icon blue-bg"><i class="fa-solid fa-award"></i></div>            <div>
                <h3>25,000+</h3>
                <p>Interviews Practiced</p>
            </div>
        </div>
    </div>
</section>

<!-- ================= FEATURES ================= -->
<section class="features-section" id="features">
    <div class="section-title">
        <span class="section-label"><i class="fa-solid fa-sparkles me-1"></i> Core AI Modules</span>
        <h2>Explore Portal Modules</h2>
        <div class="title-line"></div>
        <p>
            Comprehensive AI-powered tools designed specifically for engineering, BCA/MCA, and tech students to crack top placements.
        </p>
    </div>

    <div class="features-grid">
        <!-- FEATURE 1: CAREER GUIDANCE -->
        <div class="feature-card feature-purple">
            <div class="card-badge-wrap">
                <span class="module-badge purple-badge"><i class="fa-solid fa-sparkles"></i> AI Roadmaps</span>
            </div>
            <div class="large-icon purple-icon"><i class="fa-solid fa-compass"></i></div>
            <h3>AI Career Guidance</h3>
            <p>Discover optimal career paths matching your strengths, complete with milestone roadmaps &amp; salary insights.</p>
            
            <div class="card-highlights">
                <span><i class="fa-solid fa-check"></i> Role Roadmaps</span>
                <span><i class="fa-solid fa-check"></i> Skill Gap Analysis</span>
                <span><i class="fa-solid fa-check"></i> Tech Stack Trends</span>
            </div>

            <a href="<?php echo url('career/career_guidance.php'); ?>" class="feature-btn">
                <span>Launch Guidance</span> <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- FEATURE 2: RESUME BUILDER -->
        <div class="feature-card feature-blue">
            <div class="card-badge-wrap">
                <span class="module-badge blue-badge"><i class="fa-solid fa-file-shield"></i> 95%+ ATS Score</span>
            </div>
            <div class="large-icon blue-icon"><i class="fa-solid fa-file-lines"></i></div>
            <h3>ATS Resume Builder</h3>
            <p>Build recruiter-ready resumes from professional templates and scan keyword compatibility against target jobs.</p>
            
            <div class="card-highlights">
                <span><i class="fa-solid fa-check"></i> Instant ATS Scan</span>
                <span><i class="fa-solid fa-check"></i> Keyword Matcher</span>
                <span><i class="fa-solid fa-check"></i> 1-Click PDF Export</span>
            </div>

            <a href="<?php echo url('resume/resume.php'); ?>" class="feature-btn">
                <span>Build Resume</span> <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- FEATURE 3: MOCK INTERVIEW -->
        <div class="feature-card feature-green">
            <div class="card-badge-wrap">
                <span class="module-badge green-badge"><i class="fa-solid fa-microphone-lines"></i> Voice AI Coach</span>
            </div>
            <div class="large-icon green-icon"><i class="fa-solid fa-microphone-lines"></i></div>
            <h3>AI Mock Interview</h3>
            <p>Practice voice and text answers with instant 4-dimension AI scoring, strengths, and STAR model answers.</p>
            
            <div class="card-highlights">
                <span><i class="fa-solid fa-check"></i> Voice Input</span>
                <span><i class="fa-solid fa-check"></i> HR &amp; Tech Rounds</span>
                <span><i class="fa-solid fa-check"></i> Instant 100-Pt Score</span>
            </div>

            <a href="<?php echo url('interview/mock_interview.php'); ?>" class="feature-btn">
                <span>Practice Coach</span> <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- FEATURE 4: ASSESSMENT -->
        <div class="feature-card feature-orange">
            <div class="card-badge-wrap">
                <span class="module-badge orange-badge"><i class="fa-solid fa-bolt"></i> 15-Min Test</span>
            </div>
            <div class="large-icon orange-icon"><i class="fa-solid fa-list-check"></i></div>
            <h3>Diagnostic Assessment</h3>
            <p>Benchmark your technical core, problem-solving, and aptitude with timer-backed evaluation.</p>
            
            <div class="card-highlights">
                <span><i class="fa-solid fa-check"></i> Core CS &amp; Coding</span>
                <span><i class="fa-solid fa-check"></i> Real-time Timer</span>
                <span><i class="fa-solid fa-check"></i> Detailed Breakdown</span>
            </div>

            <a href="<?php echo url('assessment/assessment.php'); ?>" class="feature-btn">
                <span>Take Test</span> <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- ================= STUDENT SUCCESS JOURNEY (3 STEPS) ================= -->
    <div class="journey-wrapper">
        <div class="journey-header">
            <span class="journey-pill"><i class="fa-solid fa-route me-1"></i> STUDENT SUCCESS JOURNEY</span>
            <h3>How CareerConnect AI Prepares You for Placements</h3>
            <p>A proven, structured 3-step roadmap taking you from student preparation to high-package tech placement.</p>
        </div>

        <div class="journey-grid">
            <div class="journey-card">
                <div class="journey-card-top">
                    <span class="step-badge">STEP 01</span>
                    <div class="step-icon step-purple"><i class="fa-solid fa-chart-pie"></i></div>
                </div>
                <h4>Assess &amp; Discover Path</h4>
                <p>Take the 15-minute diagnostic quiz to identify your current technical level and unlock an exact tech career roadmap.</p>
                <div class="step-metric">
                    <i class="fa-solid fa-circle-check text-success"></i> Personalized Roadmaps &amp; Target Roles
                </div>
            </div>

            <div class="journey-card">
                <div class="journey-card-top">
                    <span class="step-badge">STEP 02</span>
                    <div class="step-icon step-blue"><i class="fa-solid fa-file-circle-check"></i></div>
                </div>
                <h4>Craft ATS-Optimized Resume</h4>
                <p>Use our structured builder and AI ATS scanner to format your projects, skills, and pass 90%+ recruiter filters.</p>
                <div class="step-metric">
                    <i class="fa-solid fa-circle-check text-success"></i> Instant ATS Score &amp; Clean PDF Export
                </div>
            </div>

            <div class="journey-card">
                <div class="journey-card-top">
                    <span class="step-badge">STEP 03</span>
                    <div class="step-icon step-green"><i class="fa-solid fa-award"></i></div>
                </div>
                <h4>Practice &amp; Crack Interviews</h4>
                <p>Rehearse real technical &amp; HR questions using your microphone, receive AI feedback, and ace campus placements.</p>
                <div class="step-metric">
                    <i class="fa-solid fa-circle-check text-success"></i> 4-Dimension AI Scoring &amp; STAR Answers
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= CTA ================= -->
<section class="cta-section">
    <div class="quote-icon"><i class="fa-solid fa-quote-left"></i></div>
    <p>
        Accelerate your tech career with intelligent guidance, ATS optimization, and mock interview coaching.
    </p>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?php echo url('dashboard/dashboard.php'); ?>" class="cta-button">
            Go to My Student Dashboard <i class="fa-solid fa-arrow-right"></i>
        </a>
    <?php else: ?>
        <a href="<?php echo url('auth/register.php'); ?>" class="cta-button">
            Join Now  <i class="fa-solid fa-arrow-right"></i>
        </a>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/components/footer.php'; ?>
</body>
</html>