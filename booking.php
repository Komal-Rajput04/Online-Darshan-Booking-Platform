<?php
session_start();
include 'user/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user/login.php");
    exit();
}

$sql = "SELECT * FROM temples";
$result = $connect->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Temple List</title>
    <link rel="shortcut icon" href="assets/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .text-center-middle {
        text-align: center;
        vertical-align: middle;
    }
    </style>
</head>
<body>
<div class="full-page-wrapper">
<?php include 'includes/header.php'; ?>
<br>
<br>
<br><br>
<div class="container mt-4">
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Temple Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
            <td class="text-center-middle"><?php echo $row['id']; ?></td>
            <td class="text-center-middle">
                <img src="upf/<?php echo $row['image']; ?>" width="80" height="80">
            </td>
            <td class="text-center-middle" style="font-size:22px; font-weight:bold;"><?php echo $row['name']; ?></td>
            <td class="text-center-middle">
                <a href="user/book_darshan.php?temple_id=<?= $row['id'] ?>" class="btn btn-warning text-white">Book Darshan</a>
            </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

    <?php include 'includes/footer.php'; ?>
</div>

</body>
</html>
