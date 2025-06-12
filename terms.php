<!-- privacy.php -->
<?php include "user/db.php"?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditionsy</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
         body { background-color: #f9f9f9; font-family: 'Segoe UI', sans-serif; }
    .container { padding: 40px; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-top: 30px; border-radius: 10px; }
    h2 { color: #007bff; }
  </style>
    </style>
</head>
<body class="full-page-wrapper">
    <?php include "includes/header.php";?>
    <br>
    <br><br><br>
<div class="container">
  <h2>Terms & Conditions</h2>
  <p><strong>Last Updated:</strong> <?php echo date('F d, Y'); ?></p>

  <p>These Terms and Conditions govern the use of the Online Darshan Booking Platform. By accessing this website, you agree to comply with and be bound by the following terms.</p>

  <h5>1. User Responsibilities</h5>
  <ul>
    <a>You agree to provide accurate personal information during registration and booking.</a><br>
    <a>You are responsible for maintaining confidentiality of your login credentials.</a><br>
    <a>You agree not to misuse or disrupt the services of this platform.</a><br>
  </ul>

  <h5>2. Booking Policies</h5>
  <ul>
    <a>All bookings are subject to slot availability and temple rules.</a><br>
    <a>Once confirmed, bookings are non-refundable unless explicitly stated.</a><br>
    <a>We reserve the right to cancel or reschedule events under unforeseen circumstances.</a><br>
  </ul>

  <h5>3. Intellectual Property</h5>
  <p>All content on this platform including text, logos, and images is protected under intellectual property rights and may not be used without permission.</p>

  <h5>4. Live Streaming</h5>
  <p>Live streaming of Aartis and events is provided where available. We are not responsible for third-party interruptions or YouTube platform issues.</p>

  <h5>5. Limitation of Liability</h5>
  <p>We are not liable for losses or damages resulting from system downtime, user mistakes during booking, or network delays.</p>

  <h5>6. Changes to Terms</h5>
  <p>We reserve the right to update these terms at any time. Continued use of the platform after changes constitutes acceptance.</p>

  <h5>7. Governing Law</h5>
  <p>These terms shall be governed by the laws of India. Any disputes will be subject to the jurisdiction of Uttar Pradesh courts.</p>

  <h5>8. Contact</h5>
  <p>For support or questions, contact us at <strong>example@abc.com</strong>.</p>
</div>
</body>
</html>
<?php include 'includes/footer.php'; ?>