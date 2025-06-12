<?php 
// admin_panel.php
include "../user/db.php";

// Handle event form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $event_name = mysqli_real_escape_string($connect, $_POST['event_name']);
  $temple_id = intval($_POST['temple_id']);
  $description = mysqli_real_escape_string($connect, $_POST['description']);
  $event_time = mysqli_real_escape_string($connect, $_POST['event_time']);
  $amount = floatval($_POST['amount']);
  $status = mysqli_real_escape_string($connect, $_POST['status']);
  $slots_available = intval($_POST['slots_available']);

  // Get temple name from temple ID
  $temple_query = $connect->query("SELECT name FROM temples WHERE id = $temple_id");
  $temple_data = $temple_query->fetch_assoc();
  $temple_name = $temple_data['name'];

  $insert = $connect->query("INSERT INTO events (event_name, temple_name, description, event_time, amount, status, slots_available) VALUES ('$event_name', '$temple_name', '$description', '$event_time', $amount, '$status', $slots_available)");

  if ($insert) {
    echo "<div class='alert alert-success'>Event added successfully.</div>";
  } else {
    echo "<div class='alert alert-danger'>Error: Could not add event.</div>";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Panel - Manage Events</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="adminstyle.css">
  </head>
<body>
    <?php include "header.php";?>
    <div class="main-content">
<div class="container mt-5">
  <h3>Add New Event</h3>
  <form method="POST">
    <div class="form-group">
      <label>Event Name</label>
      <input type="text" name="event_name" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Temple Name</label>
      <select name="temple_id" class="form-control" required>
        <option value="">-- Select Temple --</option>
        <?php
          $temples = $connect->query("SELECT id, name FROM temples");
          while ($temple = $temples->fetch_assoc()) {
            echo "<option value='{$temple['id']}'>{$temple['name']}</option>";
          }
        ?>
      </select>
    </div>

    <div class="form-group">
      <label>Description</label>
      <textarea name="description" class="form-control" required></textarea>
    </div>

    <div class="form-group">
      <label>Event Time</label>
      <input type="datetime-local" name="event_time" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Slots Available</label>
      <input type="number" name="slots_available" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Amount (₹)</label>
      <input type="number" name="amount" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Status</label>
      <select name="status" class="form-control">
        <option value="Available">Available</option>
        <option value="Upcoming">Upcoming</option>
        <option value="Not Available">Not Available</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Add Event</button>
  </form>

  <h3 class="mt-5">All Events</h3>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th><th>Event Name</th><th>Temple</th><th>Status</th><th>Time</th><th>Edit</th><th>Delete</th>
      </tr>
    </thead>
    <tbody> 
      <?php
        $res = $connect->query("SELECT * FROM events");
        while($row = $res->fetch_assoc()) {
          echo "<tr>
                  <td>{$row['id']}</td>
                  <td>{$row['event_name']}</td>
                  <td>{$row['temple_name']}</td>
                  <td>{$row['status']}</td>
                  <td>{$row['event_time']}</td>
                  <td><a href='edit_event.php?id={$row['id']}' class='btn btn-sm btn-warning'>Edit</a></td>
                  <td><a href='edit_event.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this event?');\">Delete</a></td>
                </tr>";
        }
      ?>
    </tbody>
  </table>
</div>
</body>
</html>
