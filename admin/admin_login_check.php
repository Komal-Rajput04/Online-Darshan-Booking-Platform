<?php
session_start();
include("../user/db.php");

$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM admin_users WHERE username = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();
    if (password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: index.php");
        exit();
    }
}

echo "<script>alert('Invalid username or password'); window.location.href='admin_login.php';</script>";
?>
