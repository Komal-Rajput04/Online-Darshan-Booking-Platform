<?php
session_start();
include 'db.php';  // DB connection here

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_otp = $_POST['otp'];

    // Check if OTP matches
    if ($entered_otp == $_SESSION['otp']) {
        // OTP is correct, update user status to 'active'
        $email = $_SESSION['mail'];
        $update_query = mysqli_query($connect, "UPDATE users SET status = 'active' WHERE email = '$email'");

        if ($update_query) {
            echo "<script>alert('OTP Verified Successfully. You are now registered!'); window.location.replace('login.php');</script>";
        } else {
            echo "<script>alert('Error verifying OTP. Please try again later.');</script>";
        }
    } else {
        echo "<script>alert('Invalid OTP. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="full-page-wrapper">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card shadow rounded">
                    <div class="card-header bg-warning text-white">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0">Verify OTP</h4>
                            <a href="../index.php" class="text-white" style="font-size: 2rem; position: absolute; right: 20px; text-decoration: none;">&times;</a>
                        </div>
        
                        <form method="POST" action="verification.php">
                            <div class="form-group">
                                <label for="otp">Enter OTP</label>
                                <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter 6-digit OTP" maxlength="6" required>

                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" name="send_otp" style="background-color: #e09e02; color: white; border: none; font-size: 20px;">
                                    Verify OTP
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
