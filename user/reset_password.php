<?php
session_start();
include('db.php');

if (!isset($_SESSION['mail']) || !isset($_SESSION['otp'])) {
    header("Location: forgot_password.php");
    exit();
}

if (isset($_POST['reset_password'])) {
    $entered_otp = $_POST['otp'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    $email = $_SESSION['mail'];

    if ($entered_otp == $_SESSION['otp']) {
        if ($new_pass === $confirm_pass) {
            $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
            mysqli_query($connect, "UPDATE users SET password='$hashed' WHERE email='$email'");
            session_unset();
            session_destroy();
            echo "<script>alert('Password changed successfully'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Passwords do not match.');</script>";
        }
    } else {
        echo "<script>alert('Invalid OTP');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="shortcut icon" href="assets/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card shadow rounded">
                    <div class="card-header bg-warning text-white">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <a href="../index.php" class="btn btn-outline-secondary btn-close" aria-label="Close"></a>
                        </div>
                        <h4 class="mb-0">Reset Password</h4>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label>Enter OTP</label>
                                <input type="text" name="otp" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" name="reset_password" style="background-color: #e09e02; color: white; border: none; font-size: 23px;">
                                    Reset Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
