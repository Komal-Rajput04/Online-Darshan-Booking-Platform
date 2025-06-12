<?php 
include '../user/db.php';

// Add Temple
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addTemple'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];

    // Image Upload
    $target_dir = "../assets/uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    // Insert using prepared statement
    $stmt = $connect->prepare("INSERT INTO temples (name, description, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $description, $target_file);

    if ($stmt->execute()) {
        echo "<script>alert('Temple added successfully!'); window.location.href='admin_about.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    }

// Delete Temple
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Get the image path before deleting the record
    $sql = "SELECT image FROM temples WHERE id = $id";
    $result = $connect->query($sql);
    $row = $result->fetch_assoc();
    $image_path = $row['image'];

    // Delete image file
    if (file_exists($image_path)) {
        unlink($image_path);
    }

    // Delete from database
    $sql = "DELETE FROM temples WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Temple deleted successfully!'); window.location.href='admin_about.php';</script>";
    } else {
        echo "Error deleting record: " . $connect->error;
    }
}

// Fetch All Temples
$sql = "SELECT * FROM temples";
$result = $connect->query($sql);

$connect->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="adminstyle.css">
    </head>
<body>
    <?php include "header.php";?>
    <div class="main-content">
<div class="container mt-4">
    <h3>Add Temple</h3>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Temple Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <button type="submit" name="addTemple" class="btn btn-primary">Add Temple</button>
    </form>

    <h2 class="mt-5">Manage Temples</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Temple Name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><img src="<?php echo $row['image']; ?>" width="80" height="80"></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo substr($row['description'], 0, 50) . '...'; ?></td>
                    <td>
                        <a href="edit_about.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="admin_about.php?delete_id=<?php echo $row['id']; ?>" 
                        class="btn btn-danger btn-sm" 
                        onclick="return confirm('Are you sure you want to delete this temple?');">Delete</a>
                    </td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</div>
</body>
</html>
