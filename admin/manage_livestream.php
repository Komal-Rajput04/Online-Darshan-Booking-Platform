<?php
session_start();
include '../user/db.php';

// Handle Add Stream
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_stream'])) {
    $title = $_POST['title'];
    $youtube_url = $_POST['youtube_url'];
    $is_live = isset($_POST['is_live']) ? 1 : 0;

    // Convert to embeddable YouTube URL
    if (preg_match('/(?:youtube\.com\/(?:watch\?v=|live\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $youtube_url, $matches)) {
        $video_id = $matches[1];
        $youtube_url = "https://www.youtube.com/embed/" . $video_id;
    }

    $stmt = $connect->prepare("INSERT INTO livestream (title, youtube_url, is_live) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $youtube_url, $is_live);
    $stmt->execute();
    $message = "Livestream added!";
}

// Handle Stop Live
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['stop_live'])) {
    $stream_id = $_POST['stream_id'];

    $stmt = $connect->prepare("UPDATE livestream SET is_live = 0 WHERE id = ?");
    $stmt->bind_param("i", $stream_id);
    $stmt->execute();
    $message = "Livestream stopped!";
}

// Fetch all streams
$streams = mysqli_query($connect, "SELECT * FROM livestream ORDER BY updated_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Livestreams</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>
<?php include "header.php"; ?>
<div class="main-content">
    <div class="container my-5">
    
        <h3> Manage All Livestreams</h3>

        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>

        <!-- Add Stream Form -->
        <form method="POST" class="mb-4">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="title" class="form-control" placeholder="Stream Title" required>
                </div>
                <div class="col-md-5">
                    <input type="text" name="youtube_url" class="form-control" placeholder="YouTube URL (any format)" required>
                </div>
                <div class="col-md-2 d-flex align-items-center">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_live" id="is_live">
                        <label class="form-check-label" for="is_live">Live Now</label>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="submit" name="add_stream" class="btn btn-success w-100">Add</button>
                </div>
            </div>
        </form>

        <!-- Display All Streams -->
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>YouTube Embed</th>
                    <th>Live</th>
                    <th>Last Updated</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($streams)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td>
                            <iframe width="250" height="140" src="<?= htmlspecialchars($row['youtube_url']) ?>" frameborder="0" allowfullscreen></iframe>
                        </td>
                        <td><?= $row['is_live'] ? '<span class="badge bg-success">Live</span>' : '<span class="badge bg-secondary">Offline</span>' ?></td>
                        <td><?= $row['updated_at'] ?></td>
                        <td>
                            <?php if ($row['is_live']): ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="stream_id" value="<?= $row['id'] ?>">
                                    <button type="submit" name="stop_live" class="btn btn-danger btn-sm">Stop Live</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
   </div> 
</body>
</html>
