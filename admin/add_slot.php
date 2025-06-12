<?php
include '../user/db.php';
 //Handle Add New Slot (for released months only)
if (isset($_POST['add_slot'])) {
    $temple_id = $_POST['temple'];
    $slot_time = $_POST['slot_time'];
    $total_seats = 2000;
	

    $release_query = $connect->prepare("SELECT year, month FROM slot_releases WHERE status = 'released'");
    //$release_query->bind_param("i", $temple_id);
    $release_query->execute();
    $release_result = $release_query->get_result();

    while ($release = $release_result->fetch_assoc()) {
        $year = (int)$release['year'];
        $month = (int)$release['month'];
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        for ($day = 1; $day <= $days_in_month; $day++) {
            $slot_date = sprintf("%04d-%02d-%02d", $year, $month, $day);

            if ($slot_date < date('Y-m-d')) continue;

            $check = $connect->prepare("SELECT id FROM slots WHERE temple_id = ? AND slot_date = ? AND slot_time = ?");
            $check->bind_param("iss", $temple_id, $slot_date, $slot_time);
            $check->execute();
            $check->store_result();

            if ($check->num_rows === 0) {
                $stmt = $connect->prepare("INSERT INTO slots (temple_id, slot_time, slot_date, total_seats, available_seats, status) VALUES (?, ?, ?, ?, ?, 'Available')");
                $stmt->bind_param("issii", $temple_id, $slot_time, $slot_date, $total_seats, $total_seats);
                $stmt->execute();
            }

            $check->close();
        }
    }

    echo "<script>alert('Slots successfully created for all released months!'); window.location='add_slot.php';</script>";
    exit;
}

// Fetch temples
$temples = $connect->query("SELECT * FROM temples");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Slot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="adminstyle.css">

</head>
<body class="container mt-5">
    <h2>Add Darshan Slots for Released Months</h2>

    <form method="POST" class="row g-3 mt-3">
        <div class="col-md-4">
            <label class="form-label">Temple</label>
            <select name="temple" class="form-select" required>
                <option value="">Select Temple</option>
                <?php while($row = $temples->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Slot Time</label>
            <input type="text" name="slot_time" class="form-control" placeholder="e.g. 6:00 AM - 9:00 AM" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Slot Date</label>
            <input type="text" name="slot_date" class="form-control" placeholder="y-m-d" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Seats</label>
            <input type="number" name="seats" class="form-control" value="2000" required>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" name="add_slot" class="btn btn-primary w-100">Create Slots</button>
        </div>
    </form>
</body>
</html>
