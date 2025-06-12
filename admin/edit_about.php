<?php
include '../user/db.php';

if (!isset($_GET['id'])) {
    die('Temple ID not specified.');
}

$id = $_GET['id'];

// Fetch existing data
$stmt = $connect->prepare("SELECT * FROM temples WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$temple = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateTemple'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];

    // If new image uploaded
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "../assets/uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    } else {
        $target_file = $temple['image'];
    }

    $stmt = $connect->prepare("UPDATE temples SET name=?, description=?, image=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $description, $target_file, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Temple updated successfully!'); window.location.href='admin_about.php';</script>";
    } else {
        echo "Update failed: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Temple</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="adminstyle.css">
    </head>
<body>
    <?php include "header.php";?>
    <div class="main-content">
<div class="container mt-4">
    <h3>Edit Temple</h3>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Temple Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo $temple['name']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" required><?php echo $temple['description']; ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <img src="<?php echo $temple['image']; ?>" width="100"><br><br>
            <label class="form-label">Change Image (Optional)</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" name="updateTemple" class="btn btn-success">Update Temple</button>
        <a href="admin_about.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</div>
</body>
</html>
