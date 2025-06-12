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
$temple_id = intval($_POST['temple_id']) ?? null;
$slot_id = intval($_POST['slot_id'])?? null;
$slot_date = $_POST['slot_date']?? null;
$email = $_POST['email']?? null;
$num_slots = intval($_POST['num_slots'])?? null;
$amount = $num_slots * 200;

// === Check 1: Max 200 slots per user per day ===
$checkUser = $connect->prepare("SELECT SUM(num_slots) as total FROM bookings WHERE booking_date = ? AND email = ?");
$checkUser->bind_param("ss", $slot_date, $email);
$checkUser->execute();
$userResult = $checkUser->get_result();
$userTotal = $userResult->fetch_assoc()['total'] ?? 0;

if (($userTotal + $num_slots) > 200) {
    die("Error: You can't book more than 200 slots per day.");
}

// === Check 2: Slot available ===
$checkSlot = $connect->prepare("SELECT available_seats FROM slots WHERE id = ?");
$checkSlot->bind_param("i", $slot_id);
$checkSlot->execute();
$slotResult = $checkSlot->get_result();
$availableSeats = $slotResult->fetch_assoc()['available_seats'];

if ($availableSeats < $num_slots) {
    die("Error: Not enough slots available. Only $availableSeats left.");
}

// === Insert Booking ===
$insert = $connect->prepare("INSERT INTO bookings (temple_id, slot_id, booking_date, num_slots, amount, email, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
$insert->bind_param("iisids", $temple_id, $slot_id, $slot_date, $num_slots, $amount, $email);
$insert->execute();

// === Get Temple Name ===
$result = $connect->query("SELECT name FROM temples WHERE id = $temple_id");
$templeName = ($result->num_rows > 0) ? $result->fetch_assoc()['name'] : "Unknown Temple";

// === Update slot ===
$update = $connect->prepare("UPDATE slots SET available_seats = available_seats - ? WHERE id = ?");
$update->bind_param("ii", $num_slots, $slot_id);
$update->execute();
// ======== FPDF Receipt ========
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Temple Darshan Booking Receipt', 0, 1, 'C');
$pdf->Ln(5);

// Booking Information Section
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Temple: ' . $templeName, 0, 1);
$pdf->Cell(0, 10, 'Booking Date: ' . $slot_date, 0, 1);
$pdf->Cell(0, 10, 'Email: ' . $email, 0, 1);
$pdf->Ln(5);

// Table Header
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(200, 220, 255);
$pdf->Cell(20, 10, 'S.No', 1, 0, 'C', true);
$pdf->Cell(70, 10, 'Item Description', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Quantity', 1, 0, 'C', true);
$pdf->Cell(60, 10, 'Amount ', 1, 1, 'C', true);

// Table Data (Dynamic or Static)
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(20, 10, '1', 1);
$pdf->Cell(70, 10, 'Darshan Booking', 1);
$pdf->Cell(40, 10, $num_slots, 1);
$pdf->Cell(60, 10,  $amount, 1, 1);

// Total Row
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(130, 10, 'Total Amount', 1);
$pdf->Cell(60, 10,  $amount, 1, 1);

$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'Thank you for booking. Please carry a valid ID proof during the visit.', 0, 1, 'C');

$filename = "receipt/receipt_" . time() . ".pdf";
$pdf->Output("F", $filename); // Save PDF


// === PHPMailer ===
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Change if using another SMTP
    $mail->SMTPAuth = true;
    $mail->Username = ''; // Replace with your Gmail
    $mail->Password = ''; // Replace with app-specific password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('', 'Temple Booking'); // Replace with your Gmail
    $mail->addAddress($email);
    $mail->Subject = 'Your Temple Darshan Booking Receipt';
    $mail->Body = "Dear devotee,\n\nThank you for booking your darshan. Please find your receipt attached.\n\nTemple: $templeName\nDate: $slot_date\nPersons: $num_slots\nAmount: ₹$amount";

    $mail->addAttachment($filename);
    $mail->send();

    // Delete file after sending
    unlink($filename);

   header("Location: book_darshan.php?temple_id=$temple_id&success=1&email=" . urlencode($email));
   exit;
 //echo "<script>alert('Booking successful! Receipt sent to your email.'); window.location.href='temples.php';</script>";
} catch (Exception $e) {
    echo "Email sending failed. Error: " . $mail->ErrorInfo;
}
?>

