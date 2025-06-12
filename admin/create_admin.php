<?php
include "../user/db.php";
$username = "admin"; // change if needed
$password = password_hash("admin123", PASSWORD_DEFAULT); // change the password

$sql = "INSERT INTO admin_users (username, password) VALUES (?, ?)";
$stmt = $connect->prepare($sql);
$stmt->bind_param("ss", $username, $password);

if ($stmt->execute()) {
    echo "Admin user created!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();

?>