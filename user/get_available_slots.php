<?php
include 'db.php';

$temple_id = intval($_GET['temple_id']);
$date = $_GET['date'];

// Get the latest slot for that date and temple
$stmt = $connect->prepare("SELECT id, available_seats FROM slots WHERE temple_id = ? AND slot_date = ?");
$stmt->bind_param("is", $temple_id, $date);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "status" => "success",
        "slot_id" => $row['id'],
        "available_seats" => $row['available_seats']
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "No slot found"
    ]);
}
?>
