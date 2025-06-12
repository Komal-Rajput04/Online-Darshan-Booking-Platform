<?php 
include 'user/db.php';

$sql = "SELECT * FROM temples";
$result = $connect->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Temple List</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="assets/logo.png" type="image/x-icon">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="assets/style.css">
</head>
<body >
<div class="full-page-wrapper">
<?php include 'includes/header.php'; ?>
<br><br>
<br>
<div class="container mt-5">
    <div class="row g-4">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="col-md-4 d-flex">
                <div class="card shadow-sm w-100">
                    <img src="/upf<?php echo $row['image']; ?>" class="card-img-top img-fluid temple-img" alt="Temple Image">
                    <div class="card-body d-flex flex-column">
                        <div>
                            <h5 class="card-title fw-semibold"><?php echo $row['name']; ?></h5>
                            <p class="card-text mb-2">
                                <?php echo substr(strip_tags($row['description']), 0, 100); ?>...
                                <span class="read-more"
                                    data-bs-toggle="modal"
                                    data-bs-target="#templeModal"
                                    data-title="<?php echo htmlspecialchars($row['name']); ?>"
                                    data-description="<?php echo htmlspecialchars($row['description'], ENT_QUOTES); ?>">
                                    Read More
                                </span>
                            </p>
                        </div>
                        <a href="user/book_darshan.php?temple_id=<?= $row['id'] ?>" class="btn btn-warning mt-auto">Book Darshan</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="templeModal" tabindex="-1" aria-labelledby="templeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="templeModalLabel">Temple Name</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="templeModalDescription">
        <!-- Full description will be injected here -->
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
</div>
<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/script.js"></script>

</body>
</html>

