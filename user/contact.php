<!?php include('templates/header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #000; /* Black background */
            color: #fff; /* Default text color white */
        }
        .contact-heading {
            color: gold;
        }
        .form-container {
            background-color: #1c1c1c;
            padding: 30px;
            border-radius: 10px;
        }
        label {
            color: #fff;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4 contact-heading">📩 Contact Us</h2>
    <form action="contact_submit.php" method="POST" class="form-container shadow">
        <div class="mb-3">
            <label class="form-label">Name:</label>
            <input type="text" name="name" class="form-control" placeholder="Your name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Subject:</label>
            <input type="text" name="subject" class="form-control" placeholder="Subject of your message" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Message:</label>
            <textarea name="message" class="form-control" rows="5" placeholder="Type your message here..." required></textarea>
        </div>
        <button type="submit" class="btn btn-warning text-dark">Send Message</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!?php include('templates/footer.php'); ?>
</body>
</html>
