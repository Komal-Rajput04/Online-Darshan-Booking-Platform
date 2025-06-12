<?php 
include('../user/db.php');

$result = $connect->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - User Queries</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>
    <?php include "header.php";?>
    <div class="main-content">
<div class="container mt-5">
    <h2 class="mb-4 text-center">User Queries</h2>
    
    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark text-center">
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="text-center"><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['subject'] ?></td>
                    <td><?= $row['message'] ?></td>
                    <td class="text-center">
                        <?php if ($row['replied']): ?>
                            <span class="badge bg-success">Replied</span>
                        <?php else: ?>
                            <a href="reply_message.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Reply</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
