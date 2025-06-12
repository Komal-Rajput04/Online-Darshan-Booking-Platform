<?php  
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = mysqli_real_escape_string($connect, $_POST['current_password']);
    $new_password = mysqli_real_escape_string($connect, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($connect, $_POST['confirm_password']);

    // Fetch user details
    $query = "SELECT * FROM users WHERE id = $user_id";
    $result = mysqli_query($connect, $query);
    $user = mysqli_fetch_assoc($result);

    // Check if current password matches
    if (password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $update_query = "UPDATE users SET password = '$hashed_password' WHERE id = $user_id";
            mysqli_query($connect, $update_query);

            $_SESSION['message'] = ['type' => 'success', 'text' => 'Password updated successfully!'];
        } else {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'New passwords do not match!'];
        }
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Current password is incorrect!'];
    }

    header("Location: change_password.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Change Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .form-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }
    .card {
      padding: 2rem;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      border-radius: 12px;
      position: relative;
    }
    .close-btn {
      position: absolute;
      top: 15px;
      right: 15px;
      font-size: 1.5rem;
      color: #000;
      text-decoration: none;
      z-index: 10;
    }
  </style>
</head>
<div>
    <div class="full-page-wrapper">
<div class="form-container">
  <div class="card col-md-6 col-lg-4">
    <!-- Close button to return to homepage -->
    <a href="../index.php" class="close-btn" aria-label="Close">&times;</a>

    <h4 class="mb-4">Change Password</h4>

    <?php if (isset($_SESSION['message'])): ?>
      <div class="alert alert-<?php echo $_SESSION['message']['type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['message']['text']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <form action="change_password.php" method="POST">
      <div class="mb-3">
        <label for="current_password" class="form-label">Current Password</label>
        <input type="password" name="current_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="new_password" class="form-label">New Password</label>
        <input type="password" name="new_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirm New Password</label>
        <input type="password" name="confirm_password" class="form-control" required>
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-primary" style="background-color: #e09e02; color: white; border: none; font-size: 16px;">Change Password</button>
      </div>

        <p class="mb-0">Forgot your password? <a href="forgot_password.php"><br>Reset it here</a>
    </form>
  </div>
</div>
    </div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
