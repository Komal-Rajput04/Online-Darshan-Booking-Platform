<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include ('db.php');  // Including the connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user from the database using MySQLi
    $stmt = mysqli_prepare($connect, "SELECT * FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, 's', $email); // Bind email parameter
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            if ($user['status'] == 'active') { // Check if user is active
                // User logged in successfully
                $_SESSION['user_id'] = $user['id'];  // Store user_id in session
                $_SESSION['email'] = $email;         // Store email in session
                header("Location: /upf/index.php");
                exit;
            } else {
                echo '<div class="alert alert-danger">Please verify your email first.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Invalid password.</div>';
        }
    } else {
        echo '<div class="alert alert-danger">No such user found.</div>';
    }
}
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon">
    <title>User Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="full-page-wrapper">

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card shadow rounded">
                    <div class="card-header bg-warning text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="mb-0 mx-auto" style="font-weight: bold; font-size: 2rem;">Login</h2>
                            <a href="../index.php" class="text-white" style="font-size: 2rem; position: absolute; right: 20px; text-decoration: none;">&times;</a>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="login.php">
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-block" 
                                style="background-color: #e09e02; color: white; border: none; font-size: 16px;">
                                Login
                            </button>
                            <div class="d-flex justify-content-between mt-3">
                                <p class="mb-0">Don't have an account? <a href="register.php"><br>Create Account</a></p>
                                <p class="mb-0">Forgot your password? <a href="forgot_password.php"><br>Reset it here</a></p>
                            </div>
                        </form>
                    </div> <!-- End card-body -->
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
