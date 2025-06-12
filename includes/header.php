<?php  
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'user/db.php';

$default_profile_picture = 'user/uploads/default-profile-icon.png';
$display_picture = $default_profile_picture;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id = $user_id";
    $user_result = mysqli_query($connect, $query);

    if ($user_result && mysqli_num_rows($user_result) > 0) {
        $user_info = mysqli_fetch_assoc($user_result);
        $db_profile_path = $user_info['profile_picture'] ?? '';

        if (!empty($db_profile_path)) {
            // Add only once: user/uploads/profile_abc.jpg
            $relative_path = 'user/' . $db_profile_path;

            // Build correct absolute path
            $absolute_path = realpath(__DIR__ . '/../' . $relative_path);

            if ($absolute_path && file_exists($absolute_path)) {
                $display_picture = $relative_path;  // valid image path
            } else {
                // Optional: log or debug fallback
                // echo "File does not exist at path: $absolute_path";
                $display_picture = $default_profile_picture;
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Darshan Booking</title>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/style.css">
    <style> /* Logo Styling */
           @import url('https://fonts.googleapis.com/css2?family=Exo:wght@600&display=swap');

.logo {
  font-family: 'Exo', sans-serif;
  font-size: 40px !important;
  font-weight: 600;
  color: white;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
  margin: 0;
  letter-spacing: 1px;
}

.subtitle {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-size: 14px;
  color: white;
  margin-top: 5px;
}

.branding {
  text-align: left;
}

    </style>
</head>
<body>
    <header>
        <div class="navbar" id="navbar">
        <div class="branding">
            <h1 class="logo">eDarshan</h1>
            <h5 class="subtitle">Electronic darshan with a spiritual essence</h5>
        </div>

            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Temple</a></li>
                    <li><a href="booking.php">Book Darshan</a></li>
                    <li><a href="events.php">Events</a></li>
                    <?php
                        // Check if there is a live stream
                        $live_result = mysqli_query($connect, "SELECT COUNT(*) AS live_count FROM livestream WHERE is_live = 1");
                        $live_data = mysqli_fetch_assoc($live_result);
                        $is_live = $live_data['live_count'] > 0;
                    ?>

                    <li> 
                        <?php if ($is_live): ?>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <!-- Live (User Logged In) -->
                                <a href="live.php" class="text-danger fw-bold">
                                    <i class="fas fa-circle text-danger"></i> Live
                                </a>
                            <?php else: ?>
                                <!-- Live (User Not Logged In) -->
                                <a href="#" class="text-danger fw-bold" onclick="return confirmLogin();">
                                    <i class="fas fa-circle text-danger"></i> Live
                                </a>
                                <script>
                                    function confirmLogin() {
                                        alert("Please log in first to watch the live stream.");
                                        window.location.href = "user/login.php";
                                        return false;
                                    }
                                </script>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- No Live -->
                            <a href="live.php" class="text-muted">
                                <i class="fas text-muted"></i> Live
                            </a>
                        <?php endif; ?>
                    </li>

                    <!-- Sidebar Toggle Button -->
                    <li>
                        <button id="menuToggle" class="toggle-btn" >
                            <i class="fas fa-bars"></i>
                        </button>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">User Panel</div>

            <?php if (isset($user_info)): ?>
                <div class="text-center my-3">
                <img src="<?= htmlspecialchars($display_picture) ?>"
                    class="rounded-circle"
                    style="width: 100px; height: 100px; object-fit: cover;"
                    alt="Profile Picture">

                
                    <p class="text-white mt-2"><?= htmlspecialchars($user_info['username']) ?></p>
                </div>


                <a href="user/profile.php"><i class="fas fa-user"></i> My Profile</a>
                <a href="user/edit-profile.php"><i class="fas fa-edit"></i> Edit Profile</a>
                <a href="user/change_password.php"><i class="fas fa-key"></i> Change Password</a>
                <a href="user/contact.php"><i class="bi bi-chat-dots-fill"></i> Contact Us</a>
                <a href="user/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <a href="user/login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                <a href="user/register.php"><i class="fas fa-user-plus"></i> Register</a>
            <?php endif; ?>
        </div>

    </header>

    <!-- JavaScript -->
    <script>
       document.addEventListener("DOMContentLoaded", function() { 
            let sidebar = document.getElementById("sidebar");
            let menuToggle = document.getElementById("menuToggle"); // Sidebar toggle button
            let navbar = document.getElementById("navbar"); // Navbar

            menuToggle.addEventListener("click", function() {
                sidebar.classList.toggle("active"); // Toggle sidebar visibility
                navbar.classList.toggle("shrink");  // Shrink navbar width when sidebar opens
            });
        });
         
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
