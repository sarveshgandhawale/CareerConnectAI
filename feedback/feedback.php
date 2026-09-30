<?php
require_once __DIR__ . '/../config/db.php';

$success = '';
$error = '';

$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
$prefillName = $_SESSION['name'] ?? '';
$prefillEmail = $_SESSION['email'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $category = trim($_POST['category'] ?? 'General Portal');
    $rating = (int)($_POST['rating'] ?? 5);
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $rating = max(1, min(5, $rating));

        $stmt = mysqli_prepare($conn, "INSERT INTO feedback (user_id, name, email, rating, comments) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "issis", $userId, $name, $email, $rating, $message);
            if (mysqli_stmt_execute($stmt)) {
                $success = "Thank you for your valuable feedback! Your input helps us continuously improve CareerConnect AI.";
                if ($userId) {
                    logActivity($conn, $userId, 'Submit Feedback', 'Feedback', "Submitted a {$rating}-star review for {$category}.");
                }
            } else {
                $error = "Failed to submit feedback. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Fetch recent high-rated feedback to showcase
$recentFeedback = [];
$fQ = mysqli_query($conn, "SELECT name, rating, comments, created_at FROM feedback ORDER BY id DESC LIMIT 4");
if ($fQ) {
    while ($f = mysqli_fetch_assoc($fQ)) {
        $recentFeedback[] = $f;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Feedback &amp; Reviews | CareerConnect AI</title>
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
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-start;
            gap: 8px;
        }
        .star-rating input {
            display: none;
        }
        .star-rating label {
            font-size: 28px;
            color: #cbd5e1;
            cursor: pointer;
            transition: color 0.2s;
        }
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #f59e0b;
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
                    <i class="fa-solid fa-heart me-1"></i> Continuous Improvement
                </span>
                <h1 class="fw-bold display-6 text-dark mb-2">
                    <i class="fa-solid fa-comment-dots text-primary me-2"></i> Student Feedback &amp; Portal Reviews
                </h1>
                <p class="lead text-muted mb-0">
                    Tell us about your experience using CareerConnect AI's career roadmaps, ATS resume scanner, and mock interviews.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="p-3 bg-light rounded-4 border d-inline-block text-center shadow-sm">
                    <div class="text-warning fs-4 mb-1">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <span class="text-muted small fw-semibold">4.9 / 5 Average Rating</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- LEFT: FEEDBACK FORM -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 bg-white h-100">
                <h4 class="fw-bold text-dark mb-3">
                    <i class="fa-solid fa-pen-nib text-primary me-2"></i> Share Your Experience
                </h4>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> <?php echo htmlspecialchars($success); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-exclamation me-2"></i> <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Your Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($prefillName); ?>" placeholder="John Doe" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Email Address</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($prefillEmail); ?>" placeholder="name@example.com" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Primary Feature Rated</label>
                            <select name="category" class="form-select">
                                <option value="Career Guidance & Roadmaps" selected>AI Career Guidance &amp; Roadmaps</option>
                                <option value="Resume Builder & ATS Scanner">Resume Builder &amp; ATS Scanner</option>
                                <option value="AI Mock Interview Practice">AI Mock Interview Practice</option>
                                <option value="Skill & Aptitude Assessment">Skill &amp; Aptitude Assessment</option>
                                <option value="General Portal Experience">Overall Portal Experience</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Rating</label>
                            <div class="star-rating pt-1">
                                <input type="radio" id="star5" name="rating" value="5" checked><label for="star5" title="5 Stars"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 Stars"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 Stars"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 Stars"><i class="fa-solid fa-star"></i></label>
                                <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="1 Star"><i class="fa-solid fa-star"></i></label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary">Your Feedback &amp; Suggestions</label>
                            <textarea name="message" class="form-control" rows="5" placeholder="Tell us how CareerConnect AI helped your preparation or how we can make it even better..." required></textarea>
                        </div>
                    </div>

                    <div class="mt-4 pt-2">
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-semibold shadow">
                            <i class="fa-solid fa-paper-plane me-2"></i> Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- RIGHT: RECENT REVIEWS -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                <h5 class="fw-bold text-dark mb-3">
                    <i class="fa-solid fa-comments text-primary me-2"></i> Recent Student Reviews
                </h5>

                <?php if (!empty($recentFeedback)): ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($recentFeedback as $fb): ?>
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($fb['name']); ?></h6>
                                    <div class="text-warning small">
                                        <?php for ($s = 1; $s <= (int)$fb['rating']; $s++): ?>
                                            <i class="fa-solid fa-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p class="text-secondary small mb-1" style="line-height: 1.6;">
                                    "<?php echo htmlspecialchars($fb['comments']); ?>"
                                </p>
                                <small class="text-muted" style="font-size: 11px;">
                                    <i class="fa-regular fa-clock me-1"></i> <?php echo date('M d, Y', strtotime($fb['created_at'])); ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="fw-bold text-dark mb-0">Sneha Patil</h6>
                            <div class="text-warning small"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                        </div>
                        <p class="text-secondary small mb-0">"The AI ATS resume scanner gave me specific keyword suggestions that boosted my profile!"</p>
                    </div>
                    <div class="p-3 bg-light rounded-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="fw-bold text-dark mb-0">Rahul Sharma</h6>
                            <div class="text-warning small"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                        </div>
                        <p class="text-secondary small mb-0">"Mock interview speech coach felt like talking to a real technical hiring manager."</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
