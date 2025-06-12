<?php include('user/db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About the Temple</title>
    <link rel="shortcut icon" href="assets/logo.png" type="image/x-icon">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS Animation Library -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/style.css">
   
</head>
<body >
    
 <div class="full-page-wrapper">
    <?php include 'includes/header.php'; ?>

     <section id="home">
        <!-- Background Video -->
        <div class="video-background">
            <video autoplay muted loop id="bg-video">
                <source src="homepage.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>

        <!-- Optional: Centered Content over Video -->
        <div class="hero-content">
            <p>Welcome to Online Darshan Booking</p>
            <h1>Experience Divine Blessings from Anywhere</h1>
            <a href="booking.php" class="cta-button">Book Darshan</a>
        </div>
    </section>
    <br>
    <br>
    <section class="text-center py-5 bg-light mx-auto rounded-4 shadow" style="max-width: 1300px;" data-aos="zoom-in" data-aos-duration="1200">
        <div class="container py-4">
            <img src="assets/icon/temple.png" alt="Temple Icon" width="90" class="mb-3 animate__animated animate__pulse animate__infinite">
            <h1 class="display-5 fw-bold text-dark">Begin Your Divine Journey</h1>
            <p class="lead text-muted">
                Experience darshan and join holy celebrations — all from the comfort of your home.
            </p>
            <div class="mt-4">
                <a href="booking.php" class="btn btn-lg px-4 py-2 me-3" style="background-color: #e09e02; color: white; border: none;">
                    Book Darshan
                </a>
                <a href="events.php" class="btn btn-outline-dark btn-lg px-4 py-2">
                    Explore Events
                </a>
            </div>
        </div>
    </section>

        

    <!-- Features Section -->
    <section class="container my-5">
        <div class="row g-4">

            <!-- Book Darshan -->
            <div class="col-md-6" data-aos="fade-right" data-aos-duration="1000">
                <div class="card p-4 shadow-lg border-0 rounded-4 bg-light h-100 hover-shadow transition">
                    <div class="text-center mb-3">
                        <img src="assets/icon/temple-blessing.png" alt="Darshan Icon" width="60">
                    </div>
                    <h2 class="text-center text-dark">Reserve Your Sacred Moment</h2>
                    <p class="text-muted text-center">
                        Skip the lines, and begin your journey of devotion with a pre-booked darshan slot. Peace, without waiting.
                    </p>
                    <div class="text-center">
                        <a href="booking.php" class="btn px-4 py-2" style="background-color: #e09e02; color: white; border: none;">Book Now</a>
                    </div>
                </div>
            </div>

            <!-- Maha Aarti & Events -->
            <div class="col-md-6" data-aos="fade-left" data-aos-duration="1000">
                <div class="card p-4 shadow-lg border-0 rounded-4 bg-light h-100 hover-shadow transition">
                    <div class="text-center mb-3">
                        <img src="assets/icon/aarti.png" alt="Aarti Icon" width="60">
                    </div>
                    <h2 class="text-center text-dark">Join the Divine Celebration</h2>
                    <p class="text-muted text-center">
                        Be part of the vibrant Maha Aarti and festivals. Feel the energy, even from afar. Celebrate the divine with us.
                    </p>
                    <div class="text-center">
                        <a href="events.php" class="btn px-4 py-2" style="background-color: #e09e02; color: white; border: none;">View Events</a>
                    </div>
                </div>
            </div>

        </div>
</section>


    <?php include 'includes/footer.php'; ?>
</div>    
    <!-- Bootstrap JS (Required for Carousel) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
    AOS.init();
    </script>

</body>
</html>