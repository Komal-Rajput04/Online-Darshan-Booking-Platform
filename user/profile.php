<?php 
include 'db.php';  // Include the database connection file
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-warning">Please login first.</div>';
    exit;
}

$user_id = $_SESSION['user_id']; // Get user id

// Fetch current user data
$stmt = mysqli_prepare($connect, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon">
    <title>My Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            background-color: #f8fafc;
        }
        .profile-card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #e09e02;
            font-weight: bold;
            margin-bottom: 2rem;
        }
        
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
        }
       
        .save-btn, .logout-btn {
            width: 100%;
            margin-top: 10px;
        }
        .logout-btn {
            background-color:rgb(233, 148, 21);
            color: white;
        }
    </style>
</head>
<body class="full-page-wrapper">
<a href="../index.php" class="text-white" style="font-size: 2rem; position: absolute; right: 20px; text-decoration: none;">&times;</a>
<div class="container mt-5">
    <h2 class="text-center">My Profile</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="profile-card text-center">
                <?php if (!empty($user['profile_picture'])): ?>
                    <img src="<?= htmlspecialchars($user['profile_picture']) ?>" class="profile-img" alt="Profile Picture">
                <?php else: ?>
                    <img src="uploads/default-profile-icon.png" class="profile-img" alt="Default Avatar">
                <?php endif; ?>
                <h4 class="font-weight-bold"><?= htmlspecialchars($user['name']) ?></h4>
                <p class="text-muted mb-1"><?= htmlspecialchars($user['email']) ?></p>
                <br>
                <a class="btn btn-light save-btn" href="edit-profile.php">Edit Profile</a>
                <br><br>
                <a href="../index.php" class="btn btn-light save-btn">Back to Home</a>
                <br><br>
                <a href="logout.php" class="btn logout-btn">Logout</a>
                <br><br>
            </div>
        </div>

        <div class="col-md-8">
            <div class="profile-card">
                <div class="nav nav-tabs mb-3">
                        <a>Personal Info</a>
                </div>
                    
                   <!-- <li class="nav-item">
                        <a class="nav-link" href="edit-profile.php">Edit Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="forgot_password.php">Change Password</a>
                    </li>-->
                

                <form>
                    <div class="form-group">
                        <label>username</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="name">Full Name</label> 
                        <input type="text" class="form-control" value=" <?= htmlspecialchars($user['name']) ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" class="form-control" value=" <?= htmlspecialchars($user['location']) ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="status">Account Status</label> 
                        <input type="text" class="form-control" value=" <?= htmlspecialchars($user['status']) ?>" readonly>
                    </div>
                    
                </form>

            </div>
        </div>
    </div>
</div>
</div>
<!-- FontAwesome for icons -->
<!script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"><!/script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
