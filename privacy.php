<!-- privacy.php -->
<?php include "user/db.php"?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>
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
  <h2>Privacy Policy</h2>
  <p><strong>Effective Date:</strong> <?php echo date('F d, Y'); ?></p>

  <p>The Online Darshan Booking Platform respects your privacy. This Privacy Policy outlines how we collect, use, protect, and disclose information provided by users.</p>

  <h5>1. Information Collection</h5>
  <ul>
    <a>We collect personal details during registration such as name, email, phone, location, and OTP.</a><br>
    <a>Data related to bookings, event participation, and feedback is stored securely in our database.</a><br>
  </ul>

  <h5>2. Use of Information</h5>
  <ul>
    <a>To process bookings and send confirmation emails or receipts.</a><br>
    <a>To personalize your experience across multiple temples and events.</a><br>
    <a>To improve our services and communication with users.</a><br>
  </ul>

  <h5>3. Data Security</h5>
  <p>We implement strong encryption and security protocols on all transactions and storage systems. Admin access is role-based and activity is logged.</p>

  <h5>4. Sharing of Information</h5>
  <p>We do not sell or rent user data. Information may be shared with temple authorities or partners only for purposes related to booking management and events.</p>

  <h5>5. User Rights</h5>
  <ul>
    <a>Users may update or delete their profile information at any time.</a><br>
    <a>For data deletion requests, please contact support via the Contact Us page.</a><br>
  </ul>

  <h5>6. Cookies</h5>
  <p>We use cookies to enhance performance and session management. You can disable them in your browser settings.</p>

  <h5>7. Contact</h5>
  <p>If you have questions or concerns regarding your data, contact us at <strong>example@abc.com</strong>.</p>
</div>
</body>
</html>
<?php include 'includes/footer.php'; ?>
