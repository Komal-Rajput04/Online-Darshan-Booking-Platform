<?php
include "../user/db.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $year = $_POST['year'];
    $month = $_POST['month'];
    $status = $_POST['status'];

    // Insert or update
   /* $stmt = $conn->prepare("INSERT INTO slot_releases (year, month, status) VALUES (?, ?, ?)
                            ON DUPLICATE KEY UPDATE status = ?");
    $stmt->bind_param("iiss", $year, $month, $status, $status);
    $stmt->execute();*/
	
	// Always upsert (insert or update) the slot release status
    $stmt = $connect->prepare("REPLACE INTO slot_releases (year, month, status) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $year, $month, $status);
    $stmt->execute();

}

// Fetch all current release statuses
$releases = $connect->query("SELECT * FROM slot_releases ORDER BY year, month");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Release Slots</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>
    <?php include "header.php";?>
    <div class="main-content">
    <div class="container mt-5">
    <h3>Slot Release Control Panel</h3>

    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-3">
            <label>Year</label>
            <select name="year" class="form-select">
                <option value="2025">2025</option>
                <option value="2025">2026</option>
            </select>
        </div>
        <div class="col-md-3">
            <label>Month</label>
            <select name="month" class="form-select">
                <?php
                for ($m = 1; $m <= 12; $m++) {
                    echo "<option value='$m'>" . date("F", strtotime("2025-$m-01")) . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-3">
            <label>Status</label>
            <select name="status" class="form-select">
                <option value="released"></option>
                <option value="released">Release</option>
                <option value="not_released">Not Released</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-success">Update Slot</button>
        </div>
    </form>

    <h5>Current Slot Status</h5>
    <table class="table table-bordered w-50">
        <thead>
            <tr>
                <th>Month</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $releases->fetch_assoc()): ?>
            <tr>
                <td><?= date("F Y", strtotime("{$row['year']}-{$row['month']}-01")) ?></td>
                <td><?= ucfirst($row['status']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>
    </div>
</body>
</html>
