<?php 
include "../user/db.php";

// Handle deletion
if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] === 'delete') {
    $id = intval($_GET['id']);
    $stmt = $connect->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    echo "<script>alert('Event deleted successfully'); window.location.href='admin_event.php';</script>";
    exit;
}

// Fetch event by ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $connect->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $event = $stmt->get_result()->fetch_assoc();
}

// Update event if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $temple_name = $_POST['temple_name'];
    $event_name = $_POST['event_name'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $status = $_POST['status'];
    $amount = $_POST['amount'];
    $slots_available=$_POST['slots_available'];

    $stmt = $connect->prepare("UPDATE events SET temple_name=?, event_name=?, description=?, event_time=?, status=?, amount=?, slots_available=? WHERE id=?");
    $stmt->bind_param("sssssiii", $temple_name, $event_name, $description, $event_date, $status, $amount, $slots_available, $id);

    $stmt->execute();

    echo "<script>alert('Event updated successfully'); window.location.href='admin_event.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Event</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>
    <?php include "header.php";?>
    <div class="main-content">
    <div class="container">
        <h3>Edit Event</h3>
        <form method="POST" action="edit_event.php">
            <input type="hidden" name="id" value="<?= $event['id'] ?>">

            <div class="mb-3">
                <label>Temple Name</label>
                <input type="text" name="temple_name" class="form-control" value="<?= $event['temple_name'] ?>" required>
            </div>

            <div class="mb-3">
                <label>Event Name</label>
                <input type="text" name="event_name" class="form-control" value="<?= $event['event_name'] ?>" required>
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" required><?= $event['description'] ?></textarea>
            </div>

            <div class="mb-3">
                <label>Event Date</label>
                <input type="datetime-local" name="event_date" class="form-control"
                       value="<?= date('Y-m-d\TH:i', strtotime($event['event_time'])) ?>" required>
            </div>
            <div class="mb-3">
                <label>Slots Available</label>
                <input type="number" name="slots_available" class="form-control" value="<?= $event['slots_available'] ?>" required>
            </div>


            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option <?= $event['status'] == 'Available' ? 'selected' : '' ?>>Available</option>
                    <option <?= $event['status'] == 'Upcoming' ? 'selected' : '' ?>>Upcoming</option>
                    <option <?= $event['status'] == 'Not Available' ? 'selected' : '' ?>>Not Available</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Amount (₹)</label>
                <input type="number" name="amount" class="form-control" value="<?= $event['amount'] ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Event</button>
            <a href="admin_event.php" class="btn btn-secondary">Cancel</a>
            <a href="edit_event.php?id=<?= $event['id'] ?>&action=delete" class="btn btn-danger float-end"
               onclick="return confirm('Are you sure you want to delete this event?');">Delete Event</a>
        </form>
    </div>
    </div>
</body>
</html>
