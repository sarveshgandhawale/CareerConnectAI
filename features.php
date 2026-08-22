<?php
include("db.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Features | CareerConnect AI</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet"
          href="assets/css/features.css">

</head>

<body>


<!-- ============================
     NAVBAR
============================= -->

<nav class="navbar navbar-expand-lg">

    <div class="container">

        <a class="navbar-brand" href="index.php">

            <i class="fa-solid fa-user-graduate"></i>

            CareerConnect AI

        </a>


        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav">

            <i class="fa-solid fa-bars"></i>

        </button>


        <div class="collapse navbar-collapse"
             id="navbarNav">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a href="index.php"
                       class="nav-link">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a href="about.php"
                       class="nav-link">
                        About Us
                    </a>
                </li>

                <li class="nav-item">
                    <a href="features.php"
                       class="nav-link active">
                        Features
                    </a>
                </li>

                <li class="nav-item">
                    <a href="how-it-work.php"
                       class="nav-link">
                        How It Works
                    </a>
                </li>

                <li class="nav-item">
                    <a href="contact.php"
                       class="nav-link">
                        Contact
                    </a>
                </li>

                <li class="nav-item ms-lg-3">
                    <a href="login.php"
                       class="btn login-btn">
                        Login
                    </a>
                </li>

            </ul>

        </div>

    </div>

</nav>



<!-- ============================
     HERO
============================= -->

<section class="feature-hero">

    <div class="container">

        <div class="hero-content">

            <span class="badge-custom">
                <i class="fa-solid fa-sparkles"></i>
                SMART CAREER PLATFORM
            </span>

            <h1>
                Everything You Need
                <br>
                <span>To Build Your Career</span>
            </h1>

            <p>
                CareerConnect AI brings career guidance,
                skill development, resume building and
                interview preparation together in one
                student-friendly platform.
            </p>

        </div>

    </div>

</section>



<!-- ============================
     FEATURES
============================= -->

<section class="features-section">

    <div class="container">


        <div class="section-heading">

            <span>
                OUR FEATURES
            </span>

            <h2>
                One Platform. Multiple Career Tools.
            </h2>

            <p>
                Explore powerful tools designed to help
                students discover, prepare and grow.
            </p>

        </div>



        <div class="row g-4">


            <!-- FEATURE 1 -->

            <div class="col-lg-4 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon orange">

                        <i class="fa-solid fa-compass"></i>

                    </div>

                    <h3>
                        AI Career Guidance
                    </h3>

                    <p>
                        Discover suitable career options
                        based on your skills, interests,
                        education and goals.
                    </p>

                    <a href="career_guidance.php">
                        Explore Career Guidance
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>

            </div>



            <!-- FEATURE 2 -->

            <div class="col-lg-4 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon blue">

                        <i class="fa-solid fa-file-lines"></i>

                    </div>

                    <h3>
                        AI Resume Builder
                    </h3>

                    <p>
                        Create a professional resume using
                        structured templates and improve
                        your resume content.
                    </p>

                    <a href="resume.php">
                        Build Your Resume
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>

            </div>



            <!-- FEATURE 3 -->

            <div class="col-lg-4 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon purple">

                        <i class="fa-solid fa-microphone"></i>

                    </div>

                    <h3>
                        AI Interview Coach
                    </h3>

                    <p>
                        Practice HR, technical and
                        behavioural interviews and receive
                        instant feedback.
                    </p>

                    <a href="interview.php">
                        Practice Interview
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>

            </div>



            <!-- FEATURE 4 -->

            <div class="col-lg-4 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon green">

                        <i class="fa-solid fa-users"></i>

                    </div>

                    <h3>
                        Student Skill Exchange
                    </h3>

                    <p>
                        Connect with classmates to teach,
                        learn and exchange technical and
                        professional skills.
                    </p>

                    <a href="skill_exchange.php">
                        Explore Skills
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>

            </div>



            <!-- FEATURE 5 -->

            <div class="col-lg-4 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon pink">

                        <i class="fa-solid fa-chart-line"></i>

                    </div>

                    <h3>
                        Career Progress Tracking
                    </h3>

                    <p>
                        Track your learning progress,
                        interview performance, skills and
                        career development.
                    </p>

                    <a href="dashboard.php">
                        View Progress
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>

            </div>



            <!-- FEATURE 6 -->

            <div class="col-lg-4 col-md-6">

                <div class="feature-card">

                    <div class="feature-icon yellow">

                        <i class="fa-solid fa-book-open"></i>

                    </div>

                    <h3>
                        Learning Roadmap
                    </h3>

                    <p>
                        Get a personalized learning roadmap
                        with recommended skills, topics and
                        career preparation steps.
                    </p>

                    <a href="roadmap.php">
                        View Roadmap
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- ============================
     CTA
============================= -->

<section class="feature-cta">

    <div class="container">

        <div class="cta-box">

            <div>

                <h2>
                    Ready to Build Your Career?
                </h2>

                <p>
                    Create your account and start your
                    personalized career journey today.
                </p>

            </div>

            <a href="register.php"
               class="btn cta-btn">

                Get Started

                <i class="fa-solid fa-arrow-right"></i>

            </a>

        </div>

    </div>

</section>



<!-- Bootstrap JS -->

<script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>