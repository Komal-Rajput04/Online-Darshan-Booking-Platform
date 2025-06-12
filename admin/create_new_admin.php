<?php 
include "../user/db.php";
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_admin'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $connect->prepare("INSERT INTO admin_users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    if ($stmt->execute()) {
        echo "<script>alert('New admin created');</script>";
    } else {
        echo "<script>alert('Error: Username may already exist');</script>";
    }
}

// Handle deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $connect->query("DELETE FROM admin_users WHERE id = $id");
    echo "<script>alert('Admin deleted');</script>";
    header("Location: create_new_admin.php");
    exit();
}

// Handle editing
if (isset($_POST['edit_admin'])) {
    $id = $_POST['edit_id'];
    $username = $_POST['edit_username'];
    $password = password_hash($_POST['edit_password'], PASSWORD_DEFAULT);
    $stmt = $connect->prepare("UPDATE admin_users SET username=?, password=? WHERE id=?");
    $stmt->bind_param("ssi", $username, $password, $id);
    $stmt->execute();
    echo "<script>alert('Admin updated');</script>";
    header("Location: create_new_admin.php");
    exit();
}

// Fetch all admins
$result = $connect->query("SELECT * FROM admin_users");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Admins</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="adminstyle.css">
  <style>
    .modal { z-index: 1050; } /* Ensures modal appears above all */
  </style>
</head>
<body>
  <?php include "header.php"; ?>
  <div class="main-content">
    <div class="container mt-5">
      <h3 class="mb-4">Create New Admin</h3>
      <form method="post" class="mb-5">
        <input type="hidden" name="create_admin" value="1">
        <div class="form-group">
          <input type="text" name="username" class="form-control" placeholder="New Admin Username" required>
        </div>
        <div class="form-group">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-dark">Create Admin</button>
      </form>

      <h4 class="mb-3">All Admin Users</h4>
      <table class="table table-bordered table-striped">
        <thead class="thead-dark">
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          mysqli_data_seek($result, 0); 
          while ($row = $result->fetch_assoc()) { ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td>
                <button class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#editModal<?= $row['id'] ?>">Edit</button>
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this admin?')" class="btn btn-sm btn-danger">Delete</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>

      <!-- Edit Modals Outside Table -->
      <?php 
      mysqli_data_seek($result, 0);
      while ($row = $result->fetch_assoc()) { ?>
        <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <form method="post" class="modal-content">
              <input type="hidden" name="edit_admin" value="1">
              <input type="hidden" name="edit_id" value="<?= $row['id'] ?>">
              <div class="modal-header">
                <h5 class="modal-title">Edit Admin</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>Username</label>
                  <input type="text" name="edit_username" value="<?= htmlspecialchars($row['username']) ?>" class="form-control" required>
                </div>
                <div class="form-group">
                  <label>New Password</label>
                  <input type="password" name="edit_password" class="form-control" placeholder="Enter new password" required>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>

  <!-- Scripts: Full jQuery & Bootstrap Bundle -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
