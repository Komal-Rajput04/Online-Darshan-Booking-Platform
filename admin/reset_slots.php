<?php
include 'db.php';
$today = date('Y-m-d');

$reset = $connect->prepare("UPDATE slots SET available_seats = 2000, status = 'Available' WHERE slot_date = ?");
$reset->bind_param("s", $today);
$reset->execute();

echo "Seats reset done for today!";
?>
