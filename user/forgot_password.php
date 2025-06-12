<?php
session_start();
include('db.php');

if (isset($_POST['send_otp'])) {
    $email = $_POST['email'];
    $check = mysqli_query($connect, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($check) > 0) {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['mail'] = $email;

        // Send OTP using PHPMailer
        require "Mail/phpmailer/PHPMailerAutoload.php";
        
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';

        $mail->Username = '';//your email
        $mail->Password = '';//your SMTP password

        $mail->setFrom('', 'Online Darshan Booking');//enter ypur email id
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "Reset Your Password - Online Darshan Booking";

        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 20px; background-color: #f9f9f9; border-radius: 10px; border: 1px solid #ddd;'>
                <div style='text-align: center; margin-bottom: 20px;'>
                    <h2 style='color: #e09e02;'>🔐 Password Reset Request</h2>
                </div>
        
                <p>Namaste,</p>
        
                <p>We received a request to reset your password for your <strong>Online Darshan Booking</strong> account.</p>
        
                <div style='text-align: center; margin: 30px 0;'>
                    <p style='font-size: 18px;'>Your One-Time Password (OTP) is:</p>
                    <h2 style='font-size: 32px; color: #e09e02; letter-spacing: 2px;'>$otp</h2>
                    <p style='font-size: 14px; color: #888;'>This OTP is valid for the next <strong>15 minutes</strong>.</p>
                </div>
        
                <p>Please enter this OTP on the password reset page to proceed. If you did not request a password reset, you can safely ignore this email.</p>
        
                <hr style='margin: 30px 0;'>
        
                <p style='font-size: 12px; color: #999;'>Need help? Reach out to our support team at <a href='mailto:support@yourdomain.com'>support@yourdomain.com</a>.</p>
                <p style='font-size: 12px; color: #999;'>Wishing you a peaceful and hassle-free temple experience. <br><strong>– Online Darshan Booking Team</strong></p>
            </div>
        ";

        if (!$mail->send()) {
            echo "<script>alert('OTP sending failed.');</script>";
        } else {
            header("Location: reset_password.php");
            exit();
        }
    } else {
        echo "<script>alert('Email not registered.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon">
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
                <h4 class="mb-0" style="font-size: 2rem; ">Forgot Password</h4>
                <a href="../index.php" class="text-white" style="font-size: 2rem; position: absolute; right: 20px; text-decoration: none;">&times;</a>
            </div>
          </div>
          <div class="card-body">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Enter Registered Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" name="send_otp" style="background-color: #e09e02; color: white; border: none; font-size: 20px;">
                    Send OTP
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
