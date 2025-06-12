<?php 
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = mysqli_prepare($connect, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Handle delete picture
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_picture'])) {
    if (!empty($user['profile_picture']) && $user['profile_picture'] !== 'uploads/default-profile-icon.png') {
        $file_path = __DIR__ . '/' . $user['profile_picture'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        $default_picture = 'uploads/default-profile-icon.png';
        $stmt = mysqli_prepare($connect, "UPDATE users SET profile_picture = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'si', $default_picture, $user_id);
        mysqli_stmt_execute($stmt);
        $user['profile_picture'] = $default_picture;

        $message = ['type' => 'info', 'text' => 'Profile picture deleted.'];
    }
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $location = trim($_POST['location']);
    $profile_picture = $user['profile_picture'];

    if (!empty($_FILES['profile_picture']['name']) && $_FILES['profile_picture']['error'] == 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));

        if (in_array($file_extension, $allowed_extensions)) {
            $new_filename = 'uploads/profile_' . uniqid() . '.' . $file_extension;
            $destination = __DIR__ . '/' . $new_filename;

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination)) {
                if (!empty($user['profile_picture']) && $user['profile_picture'] !== 'uploads/default-profile-icon.png') {
                    $old_path = __DIR__ . '/' . $user['profile_picture'];
                    if (file_exists($old_path)) unlink($old_path);
                }

                $profile_picture = $new_filename;
            } else {
                $message = ['type' => 'danger', 'text' => 'Failed to upload file.'];
            }
        } else {
            $message = ['type' => 'danger', 'text' => 'Invalid file type.'];
        }
    }

    $stmt = mysqli_prepare($connect, "UPDATE users SET name = ?, location = ?, profile_picture = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'sssi', $name, $location, $profile_picture, $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $message = ['type' => 'success', 'text' => 'Profile updated successfully.'];
        $user['name'] = $name;
        $user['location'] = $location;
        $user['profile_picture'] = $profile_picture;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #e09e02;
            font-weight: bold;
            margin-bottom: 1rem;
            font-size: 2rem;
        }
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e09e02;
        }
        .btn-orange {
            background-color: #e09e02;
            color: white;
        }
        label {
            font-weight: 600;
        }
    </style>
</head>
<body class="full-page-wrapper">
<br><br>
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center">
        <a href="../index.php" class="text-white" style="font-size: 2rem; position: absolute; right: 20px; text-decoration: none;">&times;</a>
    </div>

    <div class="card">
        <h2>Edit Profile Info</h2>
        <hr>

        <?php if (isset($message)): ?>
            <div class="alert alert-<?= $message['type'] ?>"><?= $message['text'] ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="row align-items-center mb-4">
                <div class="col-md-4 text-center">
                    <label>Current Profile Picture:</label><br>
                    <?php
                        $display_picture = (!empty($user['profile_picture']) && file_exists(__DIR__ . '/' . $user['profile_picture']))
                            ? $user['profile_picture']
                            : 'uploads/default-profile-icon.png';
                    ?>
                    <img src="<?= htmlspecialchars($display_picture) ?>" width="100" class="img-thumbnail mb-2">
                </div>
                <div class="col-md-8">
                    <div class="mb-3">
                        <label>Full Name:</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label>Location:</label>
                        <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($user['location']) ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Upload New Profile Picture:</label>
                    <input type="file" name="profile_picture" class="form-control">
                </div>

                <div class="d-flex justify-content-start gap-3 mt-3">
                    <button type="submit" name="update_profile" class="btn btn-orange">Update Profile</button>
                    <?php if ($user['profile_picture'] !== 'uploads/default-profile-icon.png'): ?>
                        <button type="submit" name="delete_picture" class="btn btn-danger">Delete Profile Picture</button>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
