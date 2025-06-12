<?php include("../user/db.php"); ?>
<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="adminstyle.css">
</head>
<body>
<?php include "header.php"; ?>
<div class="main-content">
  <div class="container">
  <h3>Welcome to Admin Dashboard</h3>
  <p>Use the menu to manage users, temples, events, and livestreams.</p>
</div>
</div>
<!?php include "footer.php"; ?>

</body>
</html>

