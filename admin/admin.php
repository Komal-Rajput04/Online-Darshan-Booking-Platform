<?php
include '../user/db.php';  // This will now use the MySQLi connection
session_start();

// Fetch all users using MySQLi
$query = "SELECT * FROM users";
$result = mysqli_query($connect, $query);
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $user_id = $_GET['delete_id'];

    // Delete the user from the database
    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($connect, $delete_query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);  // 'i' is for integer
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        echo "<script>alert('User deleted successfully.'); window.location='admin.php';</script>";
    } else {
        echo "<script>alert('Error deleting user.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="adminstyle.css">
</head>
<body>
    <?php include "header.php";?>
    <div class="main-content">
    <div class="container mt-5">
        <h3>User Management</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Profile Picture</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['username'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['name'] ?></td>
                        <td><?= $user['location'] ?></td>
                        <td><img src="../user/<?= $user['profile_picture'] ?>" alt="Profile" width="50" height="50"></td>
                        <td>
                            <!-- Admin action buttons like delete or edit user -->
                            <a href="admin.php?delete_id=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            <!--a href="edit-user.php?user_id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">Edit</a>-->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
