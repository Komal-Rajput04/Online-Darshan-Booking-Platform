<?php  
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'user/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user/login.php");
    exit();
}

$query = "SELECT * FROM livestream WHERE is_live = 1 ORDER BY updated_at DESC";
$result = mysqli_query($connect, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Live Darshan | Divine Connection</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="shortcut icon" href="assets/logo.png" type="image/x-icon">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tangerine:wght@700&family=Mukta:wght@400;600&display=swap" rel="stylesheet">

    <!-- Audio Files -->
    <audio id="bhajan" src="assets/audio/shree-ram-jay-ram-jay-jay-ram-41364.mp3" loop></audio>
</head>

<body>

<?php include 'includes/header.php'; ?>
<br><br>
<div class="full-page-wrapper">
<div class="container py-5">
<br>
    <!-- Divine Intro -->
    <div class="intro-section">
        <h1>Welcome to Your Divine Journey</h1>
        <p>In today's busy life, feel connected to your temple from the comfort of your home.<br>Join live darshan, attend Maha Aarti, and feel spiritually enriched anytime, anywhere.</p>
    </div>

    <!-- Bhajan Controls -->
    <div class="bhajan-controls">
        <button class="btn btn-outline-success btn-lg shadow-sm px-4 py-2" onclick="toggleBhajan()">Bhajan</button>
    </div>

    <!-- Streams -->
    <h3 class="text-center text-danger mb-4 animate__animated animate__fadeInUp">🔴 Live Darshan Streams</h3>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="row g-4">
            <?php while($stream = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-6 col-lg-4 fade-up">
                    <div class="stream-card">
                        <div class="ratio ratio-16x9">
                            <iframe src="<?= htmlspecialchars($stream['youtube_url']) ?>" frameborder="0" allowfullscreen></iframe>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title" title="May divine blessings be with you 🙏"><?= htmlspecialchars($stream['title']) ?></h5>
                            <p class="text-success fw-bold">Live Now</p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center mt-4 animate__animated animate__fadeIn">No live streams are currently active. Please check back soon 🙏</div>
    <?php endif; ?>

</div>
<?php include 'includes/footer.php'; ?>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/live.js"></script>

</body>
</html>
