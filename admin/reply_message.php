<?php
include('../user/db.php');
require "../user/Mail/phpmailer/PHPMailerAutoload.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $connect->prepare("SELECT * FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $msg = $stmt->get_result()->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $reply = $_POST['reply'];
    $email = $_POST['email'];
    $subject = "Reply to your query: " . $_POST['subject'];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '';//enter your email
        $mail->Password = '';//enter your SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('', 'Darshan Support');//your email
        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->Body = $reply;

        $mail->send();

        $stmt = $connect->prepare("UPDATE contact_messages SET replied=1, reply_text=? WHERE id=?");
        $stmt->bind_param("si", $reply, $id);
        $stmt->execute();

        echo "<script>alert('Reply sent successfully!'); window.location.href='admin_contact_messages.php';</script>";
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}
?>
<?php if (isset($msg)): ?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reply to Message</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            background-color: #ffffff; /* White card for contrast */
            border-radius: 10px;
        }
        .card-header {
            background-color: #343a40; /* Dark gray header */
        }
        .card-header h4 {
            color: #ffc107; /* Golden yellow text */
            margin-bottom: 0;
        }
        .form-label {
            font-weight: 500;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
    </style>

<link rel="stylesheet" href="adminstyle.css">
  </head>
<body>
    <?php include "header.php";?>
    <div class="main-content">
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header">
            <h4>📩 Reply to: <?= htmlspecialchars($msg['name']) ?> (<?= $msg['email'] ?>)</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                <input type="hidden" name="email" value="<?= $msg['email'] ?>">
                <input type="hidden" name="subject" value="<?= $msg['subject'] ?>">

                <div class="mb-3">
                    <label for="reply" class="form-label">Your Reply:</label>
                    <textarea id="reply" name="reply" class="form-control" rows="6" placeholder="Type your reply here..." required></textarea>
                </div>

                <button type="submit" class="btn btn-success">Send Reply</button>
                <a href="admin_contact_messages.php" class="btn btn-secondary ms-2">Back</a>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php endif; ?>
