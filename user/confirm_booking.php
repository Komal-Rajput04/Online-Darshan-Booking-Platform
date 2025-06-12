<?php 
require 'fpdf/fpdf.php';
require 'Mail/phpmailer/PHPMailerAutoload.php';
include 'db.php';

// Start the session to get the user ID
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<div style='color:red;text-align:center;'>Please login to book an event.</div>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch the email of the logged-in user
$userResult = $connect->query("SELECT email FROM users WHERE id = '$user_id'");
$user = $userResult->fetch_assoc();
$email = $user['email'];  // Get the email

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $event_id = (int)$_POST['event_id'];
    $people = (int)$_POST['people'];
    $booking_date = date('Y-m-d');

    // Validate the email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div style='color:red;text-align:center;'>Invalid email address.</div>";
        exit;
    }

    // Fetch event details
    $event = $connect->query("SELECT * FROM events WHERE id = $event_id")->fetch_assoc();
    if (!$event) {
        echo "<div style='color:red;text-align:center;'>Event not found.</div>";
        exit;
    }

    // Check slot availability
    if ($people > $event['slots_available']) {
        echo "<div style='color:red;text-align:center;'>Only {$event['slots_available']} slots available. Please reduce the number of people.</div>";
        exit;
    }

    $amount = $event['amount'];
    $total = $amount * $people;

    // Save booking to database with prepared statement
    $stmt = $connect->prepare("INSERT INTO book (event_id, email, people, total_amount, booking_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isids", $event_id, $email, $people, $total, $booking_date);
    if (!$stmt->execute()) {
        echo "<div style='color:red;text-align:center;'>Booking failed. Please try again later.</div>";
        exit;
    }

    // Update event slots after booking
    $new_slots = $event['slots_available'] - $people;
    $update_stmt = $connect->prepare("UPDATE events SET slots_available = ? WHERE id = ?");
    $update_stmt->bind_param("ii", $new_slots, $event_id);
    $update_stmt->execute();

    // Generate PDF receipt
    $pdf = new FPDF();
    $pdf->AddPage();
    
    // Header Title
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Temple Event Booking Receipt', 0, 1, 'C');
    $pdf->Ln(5);
    
    // Booking Info
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Event: ' . $event['event_name'], 0, 1);
    $pdf->Cell(0, 10, 'Temple: ' . $event['temple_name'], 0, 1);
    $pdf->Cell(0, 10, 'Email: ' . $email, 0, 1);
    $pdf->Cell(0, 10, 'Booking Date: ' . $booking_date, 0, 1);
    $pdf->Ln(5);
    
    // Table Header
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(220, 230, 240);
    $pdf->Cell(20, 10, 'S.No', 1, 0, 'C', true);
    $pdf->Cell(80, 10, 'Description', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Number of pepole', 1, 0, 'C', true);
    $pdf->Cell(60, 10, 'Amount ', 1, 1, 'C', true);
    
    // Table Row Data
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(20, 10, '1', 1);
    $pdf->Cell(80, 10, $event['event_name'], 1);
    $pdf->Cell(30, 10, $people, 1);
    $pdf->Cell(60, 10,  $total, 1, 1);
    
    // Total Row
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(130, 10, 'Total Amount', 1);
    $pdf->Cell(60, 10,   $total, 1, 1);
    
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 10, 'Thank you for your booking. Please bring a valid ID proof.', 0, 1, 'C');
    
    // Save PDF
    $pdfFile = 'receipt/receipt_' . time() . '.pdf';
    $pdf->Output('F', $pdfFile);
    
    // Send confirmation email with PDF attachment
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '';  // Your SMTP email
        $mail->Password = '';  // Your SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('', 'online darshanbooking'); // Your From Name and Email
        $mail->addAddress($email);
        $mail->addAttachment($pdfFile);

        $mail->isHTML(true);
        $mail->Subject = 'Temple Event Booking Confirmation';
        $mail->Body = "<h3>Thank you for booking!</h3><p>Your receipt is attached.</p>";

        $mail->send();

        // Delete the PDF after sending it
        unlink($pdfFile);

        // Success message and redirect
       
   // After successful insert & email sent
header("Location: ../events.php?success=true"); // Change filename if different
exit();
  
    } catch (Exception $e) {
        echo "<div style='color:red;text-align:center;'>Email could not be sent. Error: {$mail->ErrorInfo}</div>";
    }
}
?>
