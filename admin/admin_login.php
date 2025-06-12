<?php session_start(); ?> 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-color: #d3d3d3; /* Light Gray */
      height: 100vh;
    }
    .login-box {
      margin-top: 80px;
      max-width: 600px; /* Increased width */
      padding: 50px;    /* Increased padding */
      background-color: #f5f5f5; /* Silver */
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.25);
    }
    .login-box h3 {
      color: #000;
      margin-bottom: 30px;
    }
    .form-control {
      background-color: #e6e6e6;
      border: 1px solid #999;
    }
    .btn-login {
      background-color: #333;
      color: #fff;
      font-size: 16px;
      padding: 10px;
    }
    .btn-login:hover {
      background-color: #000;
    }
  </style>
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center">
    <div class="login-box">
      <h3 class="text-center">Admin Login</h3>
      <form method="POST" action="admin_login_check.php">
        <div class="form-group">
          <label>Username</label>
          <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-login btn-block" type="submit">Login</button>
      </form>
    </div>
  </div>
</body>
</html>
