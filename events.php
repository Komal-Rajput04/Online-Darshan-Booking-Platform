<?php  
session_start();
include 'user/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = $connect->query($sql);
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Temple Special Events Booking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
  <link rel="shortcut icon" href="assets/logo.png" type="image/x-icon">
  <style>
    body {
      background: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
      transform: scale(1.02);
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    .card-available { border-left: 5px solid #28a745; }
    .card-upcoming { border-left: 5px solid #ffc107; }
    .card-not-available { border-left: 5px solid #dc3545; opacity: 0.6; }
    .modal-header { background-color: #007bff; color: white; }
    #slotAlert { font-size: 0.9rem; }
    .bookBtn:disabled {
      background-color: #6c757d !important;
      border-color: #6c757d !important;
      cursor: not-allowed;
    }
    
    .checkmark {
  width: 72px;
  height: 72px;
  margin: 0 auto;
  stroke-width: 2;
  stroke-miterlimit: 10;
  animation: scale .3s ease-in-out .4s both;
}

.checkmark__check {
  stroke-width: 4;
  stroke-linecap: round;
  stroke-linejoin: round;
  stroke-dasharray: 48;
  stroke-dashoffset: 48;
  animation: stroke 0.4s ease-out forwards 0.5s;
}

@keyframes stroke {
  100% { stroke-dashoffset: 0; }
}

@keyframes scale {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.1); }
}

  </style>
</head>
<body>
<div class="full-page-wrapper">
  <?php include 'includes/header.php'; ?>

  <div class="container mt-4">
    <!h2 class="mb-4">Special Temple Events<!/h2>
    <br><br>
    <br><br>
    <div class="row">
      <?php 
        $result = $connect->query("SELECT * FROM events");
        while ($row = $result->fetch_assoc()) {
          $cardClass = $row['status'] === 'Available' ? 'card-available' : ($row['status'] === 'Upcoming' ? 'card-upcoming' : 'card-not-available');
          $disabled = ($row['status'] !== 'Available' || $row['slots_available'] <= 0) ? 'disabled' : '';

          echo "<div class='col-md-4'>
                  <div class='card mb-3 $cardClass'>
                    <div class='card-body'>
                      <h5 class='card-title'>{$row['event_name']}</h5>
                      <h6 class='card-subtitle mb-2 text-muted'>{$row['temple_name']}</h6>
                      <p class='card-text'>{$row['description']}</p>
                      <p><b>Time:</b> {$row['event_time']}</p>
                      <p><b>Status:</b> {$row['status']}</p>
                      <p><b>Amount per person:</b> ₹{$row['amount']}</p>
                      <p><b>Available Slots:</b> {$row['slots_available']}</p>
                      <button class='btn btn-primary bookBtn' data-id='{$row['id']}' data-name='{$row['event_name']}' data-temple='{$row['temple_name']}' data-amount='{$row['amount']}' data-slots='{$row['slots_available']}' data-userid='$user_id' data-email='{$user['email']}' $disabled>Book Now</button>
                    </div>
                  </div>
                </div>";
        }
      ?>
    </div>
  </div>

  <!-- Booking Modal -->
  <div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="user/confirm_booking.php" method="POST">
          <div class="modal-header">
            <h5 class="modal-title">Book Event</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="event_id" id="event_id">
            <input type="hidden" name="email" value="<?php echo $user['email']; ?>">
            <input type="hidden" id="slots_available">
            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

            <div class="form-group mb-2">
              <label>Event</label>
              <input type="text" id="event_name" class="form-control" readonly>
            </div>
            <div class="form-group mb-2">
              <label>Email:</label>
              <p><?php echo $user['email']; ?></p>
            </div>
            <div class="form-group mb-2">
              <label>No. of People</label>
              <input type="number" name="people" id="people" class="form-control" min="1" required>
            </div>
            <div class="form-group mb-2">
              <label>Total Amount (₹)</label>
              <input type="text" id="total_amount" class="form-control" readonly>
            </div>
            <div class="alert alert-danger d-none" id="slotAlert">Only limited slots available. Please reduce the number of people.</div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Pay & Confirm</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Payment Success Modal -->
 <!-- Payment Success Modal -->
<div class="modal fade" id="paymentSuccessModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4 border-0 bg-white text-center">
      <div class="modal-body">
        <!-- Animated Checkmark SVG with green circle and white check -->
        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
          <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="#28a745"/>
          <path class="checkmark__check" fill="none" stroke="#fff" d="M14 27l7 7 16-16"/>
        </svg>
        <h4 class="mt-4 text-dark">Your payment is successful</h4>
        <p class="text-secondary">Your receipt has been sent to:</p>
        <p class="text-dark"><strong><?php echo $user['email']; ?></strong></p>
        <button type="button" class="btn btn-success mt-3" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>



  <?php include 'includes/footer.php'; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php if (isset($_GET['success']) && $_GET['success'] === 'true'): ?>
<script>
  $(document).ready(function () {
    $('#paymentSuccessModal').modal('show');
  });
</script>
<?php endif; ?>

<script>
  $(document).ready(function () {
    $('.bookBtn').click(function () {
      let eventId = $(this).data('id');
      let eventName = $(this).data('name');
      let amount = $(this).data('amount');
      let slots = parseInt($(this).data('slots'));

      $('#event_id').val(eventId);
      $('#event_name').val(eventName);
      $('#slots_available').val(slots);
      $('#total_amount').val('');
      $('#people').val('');
      $('#slotAlert').addClass('d-none');

      $('#bookingModal').modal('show');

      $('#people').on('input', function () {
        let count = parseInt($(this).val());
        if (count > slots) {
          $('#slotAlert').removeClass('d-none');
          $('#total_amount').val('');
        } else {
          $('#slotAlert').addClass('d-none');
          $('#total_amount').val(count * amount);
        }
      });
    });
  });
</script>
</body>
</html>
