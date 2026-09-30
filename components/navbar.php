<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';

$isUserLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
$isAdminLoggedIn = isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
$navUserName = htmlspecialchars($_SESSION['name'] ?? ($_SESSION['admin_name'] ?? 'User'));
$userInitial = strtoupper(substr(trim($navUserName), 0, 1));
if (empty($userInitial)) $userInitial = 'U';

// Active page detection
$currentScript = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$isHome = (basename($currentScript) === 'index.php' && strpos($currentScript, 'admin') === false);
$isDashboard = strpos($currentScript, 'dashboard.php') !== false && strpos($currentScript, 'admin') === false;
$isAssessment = strpos($currentScript, 'assessment') !== false;
$isCareer = strpos($currentScript, 'career_guidance.php') !== false || strpos($currentScript, 'career_result.php') !== false;
$isResume = strpos($currentScript, 'resume.php') !== false || strpos($currentScript, 'edit_resume') !== false || strpos($currentScript, 'preview_resume') !== false;
$isATS = strpos($currentScript, 'resume_analysis.php') !== false;
$isInterview = strpos($currentScript, 'interview') !== false;
$isQuestions = strpos($currentScript, 'question_bank.php') !== false;
$isFeedback = strpos($currentScript, 'feedback.php') !== false && strpos($currentScript, 'admin') === false;
$isFeatures = strpos($currentScript, 'features.php') !== false;
$isCareerPath = strpos($currentScript, 'career_path.php') !== false;
$isHowItWorks = strpos($currentScript, 'how_it_works.php') !== false;
$isAbout = strpos($currentScript, 'about.php') !== false;
$isContact = strpos($currentScript, 'contact.php') !== false && strpos($currentScript, 'admin') === false;

$isAiToolsActive = $isResume || $isATS || $isInterview;
?>
<!-- Ensure FontAwesome 6 Icons Are Always Available -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous">

<header class="navbar">
    <div class="nav-container">
        <!-- BRAND LOGO -->
        <div class="logo">
            <a href="<?php echo url('index.php'); ?>" class="logo-link">
                <div class="logo-icon">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h2>Career<span>Connect</span> AI</h2>
                    <p>Your Career, Our Guidance</p>
                </div>
            </a>
        </div>

        <!-- HAMBURGER TOGGLE -->
        <button class="menu-toggle" id="navMenuToggle" onclick="toggleMenu()" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars" id="menuToggleIcon"></i>
        </button>

        <!-- NAVIGATION LINKS -->
        <nav id="mobileMenu" class="nav-menu">
            <?php if ($isAdminLoggedIn): ?>
                <a href="<?php echo url('index.php'); ?>" class="nav-item <?php echo $isHome ? 'active' : ''; ?>">
                    <i class="fa-solid fa-house"></i> <span>Home</span>
                </a>
                <a href="<?php echo url('admin/dashboard.php'); ?>" class="nav-item <?php echo strpos($currentScript, 'admin/dashboard') !== false ? 'active' : ''; ?>">
                    <i class="fa-solid fa-gauge-high"></i> <span>Admin Panel</span>
                </a>
                <a href="<?php echo url('admin/users.php'); ?>" class="nav-item <?php echo strpos($currentScript, 'admin/users') !== false ? 'active' : ''; ?>">
                    <i class="fa-solid fa-users"></i> <span>Students</span>
                </a>
                <a href="<?php echo url('admin/feedback.php'); ?>" class="nav-item <?php echo strpos($currentScript, 'admin/feedback') !== false ? 'active' : ''; ?>">
                    <i class="fa-solid fa-star"></i> <span>Feedback</span>
                </a>
                <a href="<?php echo url('admin/contact.php'); ?>" class="nav-item <?php echo strpos($currentScript, 'admin/contact') !== false ? 'active' : ''; ?>">
                    <i class="fa-solid fa-envelope"></i> <span>Inquiries</span>
                </a>
                <a href="<?php echo url('admin/module_activity.php'); ?>" class="nav-item <?php echo strpos($currentScript, 'admin/module_activity') !== false ? 'active' : ''; ?>">
                    <i class="fa-solid fa-clock-rotate-left"></i> <span>Audit Logs</span>
                </a>
            <?php elseif ($isUserLoggedIn): ?>
                <a href="<?php echo url('index.php'); ?>" class="nav-item <?php echo $isHome ? 'active' : ''; ?>">
                    <i class="fa-solid fa-house"></i> <span>Home</span>
                </a>
                <a href="<?php echo url('dashboard/dashboard.php'); ?>" class="nav-item <?php echo $isDashboard ? 'active' : ''; ?>">
                    <i class="fa-solid fa-chart-pie"></i> <span>Dashboard</span>
                </a>
                <a href="<?php echo url('assessment/assessment.php'); ?>" class="nav-item <?php echo $isAssessment ? 'active' : ''; ?>">
                    <i class="fa-solid fa-clipboard-check"></i> <span>Assessment</span>
                </a>
                <a href="<?php echo url('career/career_guidance.php'); ?>" class="nav-item <?php echo $isCareer ? 'active' : ''; ?>">
                    <i class="fa-solid fa-compass"></i> <span>Career Guidance</span>
                </a>
                <a href="<?php echo url('career_path.php'); ?>" class="nav-item <?php echo $isCareerPath ? 'active' : ''; ?>">
                    <i class="fa-solid fa-route"></i> <span>Career Paths</span>
                </a>

                <!-- AI TOOLS DROPDOWN -->
                <div class="nav-dropdown <?php echo $isAiToolsActive ? 'active' : ''; ?>" id="aiToolsDropdown">
                    <button class="nav-dropdown-toggle <?php echo $isAiToolsActive ? 'active' : ''; ?>" type="button" onclick="toggleDropdown(event)">
                        <i class="fa-solid fa-brain"></i>
                        <span>AI Tools</span>
                        <i class="fa-solid fa-chevron-down dropdown-arrow"></i>
                    </button>
                    <div class="nav-dropdown-menu">
                        <a href="<?php echo url('resume/resume.php'); ?>" class="dropdown-item <?php echo $isResume ? 'active' : ''; ?>">
                            <div class="dropdown-icon-box bg-orange">
                                <i class="fa-solid fa-file-invoice"></i>
                            </div>
                            <div class="dropdown-item-text">
                                <strong>Resume Builder</strong>
                                <small>Build professional AI resumes</small>
                            </div>
                        </a>
                        <a href="<?php echo url('resume/resume_analysis.php'); ?>" class="dropdown-item <?php echo $isATS ? 'active' : ''; ?>">
                            <div class="dropdown-icon-box bg-blue">
                                <i class="fa-solid fa-file-circle-check"></i>
                            </div>
                            <div class="dropdown-item-text">
                                <strong>ATS Scanner</strong>
                                <small>Score &amp; optimize for ATS</small>
                            </div>
                        </a>
                        <a href="<?php echo url('interview/mock_interview.php'); ?>" class="dropdown-item <?php echo $isInterview ? 'active' : ''; ?>">
                            <div class="dropdown-icon-box bg-green">
                                <i class="fa-solid fa-microphone-lines"></i>
                            </div>
                            <div class="dropdown-item-text">
                                <strong>Mock Interview</strong>
                                <small>Practice real AI voice interviews</small>
                            </div>
                        </a>
                    </div>
                </div>

                <a href="<?php echo url('feedback/feedback.php'); ?>" class="nav-item <?php echo $isFeedback ? 'active' : ''; ?>">
                    <i class="fa-solid fa-comment-dots"></i> <span>Feedback</span>
                </a>
            <?php else: ?>
                <a href="<?php echo url('index.php'); ?>" class="nav-item <?php echo $isHome ? 'active' : ''; ?>">
                    <i class="fa-solid fa-house"></i> <span>Home</span>
                </a>
                <a href="<?php echo url('features.php'); ?>" class="nav-item <?php echo $isFeatures ? 'active' : ''; ?>">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> <span>Features</span>
                </a>
                <a href="<?php echo url('career_path.php'); ?>" class="nav-item <?php echo $isCareerPath ? 'active' : ''; ?>">
                    <i class="fa-solid fa-route"></i> <span>Career Paths</span>
                </a>
                <a href="<?php echo url('how_it_works.php'); ?>" class="nav-item <?php echo $isHowItWorks ? 'active' : ''; ?>">
                    <i class="fa-solid fa-gears"></i> <span>How It Works</span>
                </a>
                <a href="<?php echo url('about.php'); ?>" class="nav-item <?php echo $isAbout ? 'active' : ''; ?>">
                    <i class="fa-solid fa-users"></i> <span>About</span>
                </a>
                <a href="<?php echo url('contact/contact.php'); ?>" class="nav-item <?php echo $isContact ? 'active' : ''; ?>">
                    <i class="fa-solid fa-headset"></i> <span>Contact</span>
                </a>
            <?php endif; ?>

            <!-- MOBILE AUTH BUTTONS (Visible inside mobile drawer) -->
            <div class="mobile-nav-buttons">
                <?php if ($isAdminLoggedIn): ?>
                    <a href="<?php echo url('admin/dashboard.php'); ?>" class="nav-user-badge">
                        <span class="user-avatar-circle admin-avatar"><i class="fa-solid fa-shield-halved"></i></span>
                        <span class="user-display-name"><?php echo $navUserName; ?> (Admin)</span>
                    </a>
                    <a href="<?php echo url('auth/logout.php?action=logout'); ?>" class="nav-logout-btn">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                <?php elseif ($isUserLoggedIn): ?>
                    <a href="<?php echo url('profile/profile.php'); ?>" class="nav-user-badge">
                        <span class="user-avatar-circle"><?php echo $userInitial; ?></span>
                        <span class="user-display-name"><?php echo $navUserName; ?></span>
                    </a>
                    <a href="<?php echo url('auth/logout.php?action=logout'); ?>" class="nav-logout-btn">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                <?php else: ?>
                    <a href="<?php echo url('auth/login.php'); ?>" class="nav-login-btn">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Login</span>
                    </a>
                    <a href="<?php echo url('auth/register.php'); ?>" class="nav-register-btn">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Register</span>
                    </a>
                <?php endif; ?>
            </div>
        </nav>

        <!-- DESKTOP AUTH BUTTONS -->
        <div class="nav-buttons desktop-buttons">
            <?php if ($isAdminLoggedIn): ?>
                <a href="<?php echo url('admin/dashboard.php'); ?>" class="nav-user-badge admin-badge" title="Admin Portal">
                    <span class="user-avatar-circle admin-avatar"><i class="fa-solid fa-shield-halved"></i></span>
                    <span class="user-display-name"><?php echo $navUserName; ?></span>
                </a>
                <a href="<?php echo url('auth/logout.php?action=logout'); ?>" class="nav-logout-btn" title="Sign Out">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            <?php elseif ($isUserLoggedIn): ?>
                <a href="<?php echo url('profile/profile.php'); ?>" class="nav-user-badge" title="My Profile (<?php echo $navUserName; ?>)">
                    <span class="user-avatar-circle"><?php echo $userInitial; ?></span>
                    <span class="user-display-name"><?php echo $navUserName; ?></span>
                </a>
                <a href="<?php echo url('auth/logout.php?action=logout'); ?>" class="nav-logout-btn" title="Sign Out">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            <?php else: ?>
                <a href="<?php echo url('auth/login.php'); ?>" class="nav-login-btn">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>Login</span>
                </a>
                <a href="<?php echo url('auth/register.php'); ?>" class="nav-register-btn">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Register</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

<script>
function toggleMenu() {
    const menu = document.getElementById("mobileMenu");
    const icon = document.getElementById("menuToggleIcon");
    if (menu) {
        menu.classList.toggle("active");
        if (icon) {
            if (menu.classList.contains("active")) {
                icon.classList.remove("fa-bars");
                icon.classList.add("fa-xmark");
            } else {
                icon.classList.remove("fa-xmark");
                icon.classList.add("fa-bars");
            }
        }
    }
}

function toggleDropdown(e) {
    if (window.innerWidth <= 1200) {
        e.preventDefault();
        e.stopPropagation();
        const dropdown = document.getElementById("aiToolsDropdown");
        if (dropdown) dropdown.classList.toggle("open-mobile");
    }
}

// Close dropdown and mobile menu on outside click
document.addEventListener("click", function(e) {
    const dropdown = document.getElementById("aiToolsDropdown");
    if (dropdown && !dropdown.contains(e.target)) {
        dropdown.classList.remove("open-mobile");
    }
    const menu = document.getElementById("mobileMenu");
    const toggleBtn = document.getElementById("navMenuToggle");
    if (menu && menu.classList.contains("active") && !menu.contains(e.target) && (!toggleBtn || !toggleBtn.contains(e.target))) {
        menu.classList.remove("active");
        const icon = document.getElementById("menuToggleIcon");
        if (icon) {
            icon.classList.remove("fa-xmark");
            icon.classList.add("fa-bars");
        }
    }
});

// Smooth sticky navbar shadow on scroll
window.addEventListener("scroll", function() {
    const navbar = document.querySelector(".navbar");
    if (navbar) {
        if (window.pageYOffset > 10) {
            navbar.classList.add("navbar-scrolled");
        } else {
            navbar.classList.remove("navbar-scrolled");
        }
    }
}, { passive: true });
</script>