<?php
// Database connection setup
$servername = "localhost";   // MySQL server
$username = "root";          // database username
$password = "";              // database password (empty for default MySQL setup)
$dbname = "darshan_booking"; // database name

// Create the connection
$connect = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}
?>