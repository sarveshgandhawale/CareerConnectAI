<?php
require_once __DIR__ . '/../config/config.php';
?>
<!-- =====================================================
     CAREERCONNECT AI FOOTER
===================================================== -->
<footer class="modern-footer">
    <div class="footer-container">

        <!-- BRAND -->
        <div class="footer-brand">
            <a href="<?php echo url('index.php'); ?>" class="brand-logo">
                <div class="logo-icon">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <h2>CareerConnect <span>AI</span></h2>
            </a>
            <p>
                CareerConnect AI is a smart student career &amp; interview portal designed to help students discover their skills, improve their career trajectory, build ATS-friendly resumes, and master mock interviews.
            </p>
            <a href="<?php echo url('auth/register.php'); ?>" class="footer-btn">
                Get Started Free <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>

        <!-- QUICK LINKS -->
        <div class="footer-column">
            <h4>Quick Links</h4>
            <a href="<?php echo url('index.php'); ?>">Home</a>
            <a href="<?php echo url('about.php'); ?>">About Us</a>
            <a href="<?php echo url('features.php'); ?>">Features</a>
            <a href="<?php echo url('how_it_works.php'); ?>">How It Works</a>
            <a href="<?php echo url('questions/question_bank.php'); ?>">Question Bank</a>
            <a href="<?php echo url('contact/contact.php'); ?>">Contact</a>
        </div>

        <!-- CAREER TOOLS -->
        <div class="footer-column">
            <h4>Career Tools</h4>
            <a href="<?php echo url('assessment/assessment.php'); ?>">Skill Assessment</a>
            <a href="<?php echo url('career/career_guidance.php'); ?>">AI Career Guidance</a>
            <a href="<?php echo url('career_path.php'); ?>">Career Paths</a>
            <a href="<?php echo url('resume/resume.php'); ?>">Resume Builder</a>
            <a href="<?php echo url('resume/resume_analysis.php'); ?>">AI ATS Scanner</a>
            <a href="<?php echo url('interview/mock_interview.php'); ?>">AI Mock Interview</a>
        </div>

        <!-- CONTACT -->
        <div class="footer-column contact-column">
            <h4>Contact Us</h4>
            <p><i class="fa-solid fa-envelope me-2"></i> support@careerconnectai.com</p>
            <p><i class="fa-solid fa-phone me-2"></i> +91 98765 43210</p>
            <p><i class="fa-solid fa-location-dot me-2"></i> Mumbai, Maharashtra, India</p>

            <div class="footer-social mt-3">
                <a href="https://linkedin.com" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="https://instagram.com" target="_blank" rel="noopener" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="https://github.com" target="_blank" rel="noopener" aria-label="GitHub"><i class="fa-brands fa-github"></i></a>
                <a href="https://twitter.com" target="_blank" rel="noopener" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
            </div>
        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> CareerConnect AI. All Rights Reserved.</p>
        <div class="footer-policy-links">
            <a href="<?php echo url('admin/admin_login.php'); ?>"><i class="fa-solid fa-lock me-1"></i> Admin Login</a>
            <a href="<?php echo url('about.php'); ?>">About Project</a>
            <a href="<?php echo url('contact/contact.php'); ?>">Support</a>
        </div>
    </div>
</footer>