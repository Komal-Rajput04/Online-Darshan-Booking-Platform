<?php
include "../user/db.php";

$result = $connect->query("SELECT * FROM bookings ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Booked Emails</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="adminstyle.css">
  </head>
<body>
    <?php include "header.php";?>
    <div class="main-content">
      <div class="container py-4">
  <h3>All Booking Emails</h3>
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>ID</th>
        <th>Temple ID</th>
        <th>Date</th>
        <th>Persons</th>
        <th>Amount</th>
        <th>Email</th>
        <th>Booked At</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= $row['temple_id'] ?></td>
          <td><?= $row['booking_date'] ?></td>
          <td><?= $row['num_slots'] ?></td>
          <td>₹<?= $row['amount'] ?></td>
          <td><?= $row['email'] ?></td>
          <td><?= $row['created_at'] ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  </div>
</div>
</body>
</html>
