<?php include("../user/db.php"); ?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="adminstyle.css">
</head>
<body>

<!-- Static Sidebar -->
<nav class="sidebar">
  <h4 class="text-center text-white">Admin Panel</h4>
  <ul class="nav flex-column mt-4 px-3">
    <li class="nav-item"><a class="nav-link text-white" href="admin.php">Manage Users</a></li>
    <li class="nav-item"><a class="nav-link text-white" href="admin_about.php">Manage Temples</a></li>
    <li class="nav-item"><a class="nav-link text-white" href="admin_event.php">Manage Events</a></li>
    <li class="nav-item"><a class="nav-link text-white" href="manage_livestream.php">Manage Live Stream</a></li>
    
    <li class="nav-item"><a class="nav-link text-white" href="admin_contact_messages.php">Manage User Queries</a></li>
    <li class="nav-item"><a class="nav-link text-white" href="create_new_admin.php">Manage Admin</a></li>
    <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="bookingDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             Manage Darshan Booking
          </a>
          <div class="dropdown-menu" aria-labelledby="bookingDropdown">
            <a class="dropdown-item" href="add_slot.php">Add Slot</a>
            <a class="dropdown-item" href="admin_booking_history.php">Booking history</a>
            <a class="dropdown-item" href="admin_slot_release.php">slot release</a>
            <!--a class="dropdown-item" href="admin_booking_by_user.php">Filter by User</a>-->
          </div>
    </li>
    <li class="nav-item"><a class="nav-link text-white" href="admin_logout.php">logout</a></li>
  </ul>
</nav>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
