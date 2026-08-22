<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Contact Us | CareerConnect AI</title>

    <!-- Google Font -->
    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet"
          href="assets/css/contact.css">

</head>

<body>


<!-- =====================================================
     NAVBAR
===================================================== -->

<header class="navbar">


    <!-- LOGO -->

    <a href="index.php" class="logo">

        <div class="logo-symbol">

            <i class="fa-solid fa-user-graduate"></i>

        </div>

        <div class="logo-text">

            <h2>
                Career<span>Connect</span> AI
            </h2>

            <p>
                Your Career, Our Guidance
            </p>

        </div>

    </a>



    <!-- NAVIGATION -->

    <nav>

        <a href="index.php">
            Home
        </a>

        <a href="index.php#features">
            Features
        </a>

        <a href="how-it-work.php">
            How It Works
        </a>

        <a href="about.php">
            About Us
        </a>

        <a href="contact.php" class="active">
            Contact
        </a>

    </nav>



    <!-- BUTTONS -->

    <div class="nav-buttons">

        <a href="login.php" class="login">
            Login
        </a>

        <a href="register.php" class="register">
            Register
        </a>

    </div>

</header>



<!-- =====================================================
     HERO
===================================================== -->

<section class="contact-hero">


    <div class="contact-badge">

        <i class="fa-solid fa-paper-plane"></i>

        GET IN TOUCH

    </div>


    <h1>

        Let's Start a
        <span>Conversation.</span>

    </h1>


    <p>

        Have a question, suggestion, or need help?
        Our team is here to support you on your
        career journey.

    </p>


</section>



<!-- =====================================================
     CONTACT AREA
===================================================== -->

<section class="contact-section">


    <div class="contact-wrapper">


        <!-- =================================================
             LEFT SIDE
        ================================================= -->

        <div class="contact-info">


            <span class="section-label">
                CONTACT INFORMATION
            </span>


            <h2>
                We'd Love to Hear From You
            </h2>


            <p class="info-description">

                Whether you have questions about CareerConnect AI,
                need technical support, or want to share your
                feedback, feel free to contact us.

            </p>



            <!-- EMAIL -->

            <div class="info-item">

                <div class="info-icon purple">

                    <i class="fa-solid fa-envelope"></i>

                </div>

                <div>

                    <h3>
                        Email Us
                    </h3>

                    <p>
                        support@careerconnectai.com
                    </p>

                </div>

            </div>



            <!-- PHONE -->

            <div class="info-item">

                <div class="info-icon orange">

                    <i class="fa-solid fa-phone"></i>

                </div>

                <div>

                    <h3>
                        Call Us
                    </h3>

                    <p>
                        +91 98765 43210
                    </p>

                </div>

            </div>



            <!-- LOCATION -->

            <div class="info-item">

                <div class="info-icon blue">

                    <i class="fa-solid fa-location-dot"></i>

                </div>

                <div>

                    <h3>
                        Location
                    </h3>

                    <p>
                        India
                    </p>

                </div>

            </div>



            <!-- WORKING HOURS -->

            <div class="info-item">

                <div class="info-icon green">

                    <i class="fa-solid fa-clock"></i>

                </div>

                <div>

                    <h3>
                        Working Hours
                    </h3>

                    <p>
                        Monday - Friday | 9:00 AM - 6:00 PM
                    </p>

                </div>

            </div>



            <!-- SOCIAL -->

            <div class="social-section">

                <h3>
                    Follow Us
                </h3>

                <div class="social-links">

                    <a href="#">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </a>

                    <a href="#">
                        <i class="fa-brands fa-instagram"></i>
                    </a>

                    <a href="#">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>

                    <a href="#">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>

                </div>

            </div>


        </div>



        <!-- =================================================
             RIGHT SIDE FORM
        ================================================= -->

        <div class="contact-form-card">


            <div class="form-heading">

                <span>
                    SEND MESSAGE
                </span>

                <h2>
                    How Can We Help?
                </h2>

                <p>
                    Fill out the form and we'll get back to you.
                </p>

            </div>



            <form action="contact_process.php"
                  method="POST">


                <!-- NAME -->

                <div class="form-group">

                    <label for="name">
                        Full Name
                    </label>

                    <div class="input-box">

                        <i class="fa-solid fa-user"></i>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            placeholder="Enter your full name"
                            required>

                    </div>

                </div>



                <!-- EMAIL -->

                <div class="form-group">

                    <label for="email">
                        Email Address
                    </label>

                    <div class="input-box">

                        <i class="fa-solid fa-envelope"></i>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Enter your email"
                            required>

                    </div>

                </div>



                <!-- SUBJECT -->

                <div class="form-group">

                    <label for="subject">
                        Subject
                    </label>

                    <div class="input-box">

                        <i class="fa-solid fa-tag"></i>

                        <select id="subject"
                                name="subject"
                                required>

                            <option value="">
                                Select a subject
                            </option>

                            <option value="Career Guidance">
                                Career Guidance
                            </option>

                            <option value="Resume">
                                Resume Support
                            </option>

                            <option value="Interview">
                                Interview Coach
                            </option>

                            <option value="Technical Support">
                                Technical Support
                            </option>

                            <option value="Feedback">
                                Feedback
                            </option>

                            <option value="Other">
                                Other
                            </option>

                        </select>

                    </div>

                </div>



                <!-- MESSAGE -->

                <div class="form-group">

                    <label for="message">
                        Message
                    </label>

                    <div class="textarea-box">

                        <i class="fa-solid fa-message"></i>

                        <textarea
                            id="message"
                            name="message"
                            rows="5"
                            placeholder="Write your message..."
                            required></textarea>

                    </div>

                </div>



                <!-- SUBMIT -->

                <button type="submit"
                        class="send-button">

                    Send Message

                    <i class="fa-solid fa-paper-plane"></i>

                </button>


            </form>

        </div>


    </div>

</section>



<!-- =====================================================
     FAQ
===================================================== -->

<section class="faq-section">


    <div class="section-title">

        <span>
            QUICK ANSWERS
        </span>

        <h2>
            Frequently Asked Questions
        </h2>

        <p>
            Find quick answers to common questions.
        </p>

    </div>



    <div class="faq-grid">


        <div class="faq-card">

            <div class="faq-icon">
                <i class="fa-solid fa-circle-question"></i>
            </div>

            <div>

                <h3>
                    Is CareerConnect AI free?
                </h3>

                <p>
                    Students can create an account and
                    access the core career guidance features.
                </p>

            </div>

        </div>



        <div class="faq-card">

            <div class="faq-icon">
                <i class="fa-solid fa-robot"></i>
            </div>

            <div>

                <h3>
                    How does AI career guidance work?
                </h3>

                <p>
                    AI analyzes your profile, skills,
                    interests and goals to provide
                    suitable career recommendations.
                </p>

            </div>

        </div>



        <div class="faq-card">

            <div class="faq-icon">
                <i class="fa-solid fa-file-lines"></i>
            </div>

            <div>

                <h3>
                    Can I create a resume?
                </h3>

                <p>
                    Yes. The Resume Builder helps you
                    create and edit a professional resume.
                </p>

            </div>

        </div>



        <div class="faq-card">

            <div class="faq-icon">
                <i class="fa-solid fa-headset"></i>
            </div>

            <div>

                <h3>
                    Can I practice interviews?
                </h3>

                <p>
                    Yes. The Interview Coach provides
                    HR, technical and behavioral practice.
                </p>

            </div>

        </div>


    </div>

</section>



<!-- =====================================================
     CTA
===================================================== -->

<section class="contact-cta">


    <div class="cta-icon">

        <i class="fa-solid fa-comments"></i>

    </div>


    <div class="cta-text">

        <span>
            NEED CAREER GUIDANCE?
        </span>

        <h2>
            Your Career Journey Starts Here.
        </h2>

        <p>
            Create your account and explore
            CareerConnect AI today.
        </p>

    </div>


    <a href="register.php"
       class="cta-button">

        Get Started

        <i class="fa-solid fa-arrow-right"></i>

    </a>


</section>



<!-- =====================================================
     FOOTER
===================================================== -->

<footer>


    <div class="footer-left">

        <h3>
            Career<span>Connect</span> AI
        </h3>

        <p>
            Your Career, Our Guidance.
        </p>

    </div>


    <div class="footer-right">

        © 2026 CareerConnect AI.
        All Rights Reserved.

    </div>


</footer>


</body>

</html>