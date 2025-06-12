<?php 
session_start();
include('db.php');  // Ensure this is included to access the database connection

$errors = [];

if (isset($_POST["register"])) {
    // Get user input
    $email = $_POST["email"];
    $password = $_POST["password"];
    $username = $_POST["username"];
    $name = $_POST["name"];
    $location = $_POST["location"];

    // Password validation function
    /*function isValidPassword($password) {
        // At least 8 chars, 1 uppercase, 1 lowercase, 1 number, 1 special char
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
    }*/

    if (empty($email) || empty($password) || empty($username)) {
        $errors[] = "Email, username, and password fields cannot be empty.";
    } /*elseif (!isValidPassword($password)) {
        $errors[] = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    } */else {
        // Check if the user already exists
        $check_query = mysqli_query($connect, "SELECT * FROM users WHERE email = '$email' OR username = '$username'");
        $rowCount = mysqli_num_rows($check_query);

        if ($rowCount > 0) {
            $errors[] = "User with email or username already exists!";
        } else {
            // Generate OTP
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['mail'] = $email;
            $_SESSION['username'] = $username;

            // Insert user details into the database (without OTP for now)
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $otp_expiry = date('Y-m-d H:i:s', strtotime('+15 minutes')); // OTP expiry time
            $result = mysqli_query($connect, "INSERT INTO users (email, password, username, name, location, otp_code, otp_expiry, status) 
                                              VALUES ('$email', '$password_hash', '$username', '$name', '$location', '$otp', '$otp_expiry', 'inactive')");

            if ($result) {
                // Include PHPMailer and send OTP email
                require "Mail/phpmailer/PHPMailerAutoload.php";
                $mail = new PHPMailer;

                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'tls';

                $mail->Username = 'sayali.birje0308@gmail.com'; // SMTP USERNAME
                $mail->Password = 'nrjy cnzm cdui nlit'; // SMTP PASSWORD
                
                $mail->setFrom('sayali.birje0308@gmail.com', 'Online Darshan Booking');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = "Your OTP - Online Darshan Booking Verification";
                $mail->Body = "
                <div style='font-family: Arial, sans-serif; padding: 20px; background-color: #f9f9f9; border-radius: 10px; border: 1px solid #ddd;'>
                    <div style='text-align: center; margin-bottom: 20px;'>
                        <h2 style='color: #e09e02;'>Online Darshan Booking</h2>
                    </div>
                    <p>Namaste <strong>$name</strong>,</p>
                    <p>Thank you for joining the <strong>Online Darshan Booking Platform</strong> – your gateway to a seamless and spiritual temple experience.</p>
                    <div style='text-align: center; margin: 30px 0;'>
                        <p style='font-size: 18px;'>Your One-Time Password (OTP) is:</p>
                        <h2 style='font-size: 32px; color: #e09e02; letter-spacing: 2px;'>$otp</h2>
                        <p style='font-size: 14px; color: #888;'>Valid for the next 15 minutes</p>
                    </div>
                    <p>With our platform, you can now:</p>
                    <ul>
                        <li>📅 Book darshan slots & Maha Aarti events across multiple temples</li>
                        <li>📖 Explore temple history, rituals, and event schedules</li>
                        <li>💳 Pay securely online and receive instant confirmation</li>
                        <li>📺 Watch live ceremonies from the comfort of your home</li>
                    </ul>
                    <p>We're here to help you connect spiritually without the stress of long queues and travel hassle. Let’s preserve and celebrate our temple traditions—digitally and devotionally. 🌸</p>
                    <hr style='margin: 30px 0;'>
                    <p style='font-size: 12px; color: #999;'>If you did not request this verification, please ignore this email or contact our support team.</p>
                    <p style='font-size: 12px; color: #999;'>Blessings, <br><strong>Online Darshan Booking Team</strong></p>
                </div>
            ";

                // Attempt to send the email
                if (!$mail->send()) {
                    $errors[] = "Registration failed, email sending failed.";
                } else {
                    // Redirect to OTP verification page
                    echo "<script>
                            alert('Registration successful. OTP sent to $email.');
                            window.location.replace('verification.php');
                          </script>";
                    exit();
                }
            } else {
                $errors[] = "Registration failed, try again later.";
            }
        }
    }
}
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon" />
    <title>User Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../assets/style.css" />
    <style>
        .is-invalid {
            border-color: #dc3545;
        }
    </style>
</head>
<body class="full-page-wrapper">
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card shadow rounded">
                <div class="card-header bg-warning text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0 mx-auto" style="font-weight: bold; font-size: 2rem;">Register</h2>
                        <a href="../index.php" class="text-white" style="font-size: 2rem; position: absolute; right: 20px; text-decoration: none;">&times;</a>
                    </div>
                </div>

                <div class="card-body bg-white">
                    <!-- Display server-side errors here -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error) {
                                echo "<p class='mb-1'>$error</p>";
                            } ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="register.php" novalidate>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input
                              type="text"
                              class="form-control"
                              id="username"
                              name="username"
                              placeholder="username"
                              required
                              value="<?= isset($username) ? htmlspecialchars($username) : '' ?>"
                            />
                        </div>
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input
                              type="text"
                              class="form-control"
                              id="name"
                              name="name"
                              placeholder="Enter full name"
                              required
                              value="<?= isset($name) ? htmlspecialchars($name) : '' ?>"
                            />
                        </div>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input
                              type="text"
                              class="form-control"
                              id="location"
                              name="location"
                              placeholder="Enter location"
                              required
                              value="<?= isset($location) ? htmlspecialchars($location) : '' ?>"
                            />
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input
                              type="email"
                              class="form-control"
                              id="email"
                              name="email"
                              placeholder="Enter email"
                              required
                              value="<?= isset($email) ? htmlspecialchars($email) : '' ?>"
                            />
                        </div>

                        <div class="form-group position-relative">
                            <label for="password">Password</label></div>
                        <div class="input-group">
                            
                            <input
                              type="password"
                              class="form-control"
                              id="password"
                              name="password"
                              placeholder="Password"
                              required
                           />
                           <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fa-solid fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>
                        </div>
                              
                        <button
                          type="submit"
                          name="register"
                          class="btn btn-block"
                          style="background-color: #e09e02; color: white; border: none; font-size: 16px;"
                        >
                          Register
                        </button>
                        <p class="mt-3">
                          Already have an account? <a href="login.php" style="color: #e09e02;">Login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

</script> 
</body> 
</html>