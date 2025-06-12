<?php
session_start();
include "db.php";
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = $connect->query($sql);
$user = $result->fetch_assoc();	
$templeId = $_GET['temple_id']; // From URL like book_darshan.php?temple_id=1
$year = date("Y"); // current yearc:\wamp64\www\temple_ booking\get_available_slots.php

$releasedMonths = [];
//$res = $conn->query("SELECT month FROM slot_releases WHERE year = $year AND status = 'released' AND temple_id = $templeId");
$res = $connect->query("SELECT month FROM slot_releases WHERE year = $year AND status = 'released'");
while ($row = $res->fetch_assoc()) {
    $releasedMonths[] = (int)$row['month'];
}

$stmt = $connect->prepare("SELECT name FROM temples WHERE id = ?");
$stmt->bind_param("i", $templeId);
$stmt->execute();
$result = $stmt->get_result();
$templeName = $result->fetch_assoc()['name'] ?? "Unknown Temple";
?>
<!DOCTYPE html>
<html>
<head>
  <title><?= htmlspecialchars($templeName) ?> Slot Booking</title>
  <link rel="shortcut icon" href="../assets/logo.png" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/style.css">
  <style>
    .calendar-month {
      border: 1px solid #ddd;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 20px;
      min-width: 250px;
      background-color: #fff;
    }
    .calendar-header {
      font-weight: bold;
      text-align: center;
      margin-bottom: 8px;
    }
    .calendar-day {
      width: 40px;
      height: 40px;
      margin: 2px;
      text-align: center;
      line-height: 36px;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
    }
    .available { background-color: green; color: white; }
    .full { background-color: red; color: white; }
    .not-released { background-color: blue; color: white; }
    .user-selected { background-color: gray; color: white; }
    .weekdays {
      display: flex;
      justify-content: space-between;
      font-size: 12px;
      font-weight: bold;
      margin-bottom: 4px;
    }
    .weekdays div {
      width: 40px;
      text-align: center;
    }
    .days-grid {
      display: flex;
      flex-wrap: wrap;
    }
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}
.modal-content {
  background-color: white;
  padding: 30px;
  border-radius: 12px;
  text-align: center;
  width: 90%;
  max-width: 450px;
  box-shadow: 0 10px 20px rgba(0,0,0,0.3);
}
.circle {
  width: 70px;
  height: 70px;
  background-color: #28a745; /* green */
  border-radius: 50%;
  margin: 0 auto;
  display: flex;
  justify-content: center;
  align-items: center;
}
.checkmark {
  color: white;
  font-size: 36px;
  font-weight: bold;
}
.modal-content h2 {
  font-size: 20px;
  margin-top: 20px;
}
.modal-content p {
  margin: 0;
}
.modal-content button {
  background-color: #28a745;
  color: white;
  padding: 10px 25px;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  cursor: pointer;
  margin-top: 15px;
}
  </style>
</head>
<body class="full-page-wrapper">
<div>
<div class="container my-4">
<h4 class="text-center mb-4"><?= htmlspecialchars($templeName) ?> Temple Booking</h4>

<!-- Navigation -->
<div class="d-flex justify-content-between align-items-center mb-3">
  <button class="btn btn-outline-primary" onclick="changeMonth(-1)">← Previous</button>
  <h5 id="monthRange" class="text-center m-0 flex-grow-1 text-uppercase"></h5>
  <button class="btn btn-outline-primary" onclick="changeMonth(1)">Next →</button>
</div>

<!-- Calendar -->
<div class="row" id="calendarWrapper"></div>

<!-- Legend -->
<div class="legend text-center mb-4">
  <span class="badge bg-success">Available</span>
  <span class="badge bg-danger">Quota Full</span>
  <span class="badge bg-primary">Quota Not Released</span>
  <span class="badge bg-secondary">Selected</span>
</div>

<!-- Form Inputs -->
<div class="form-section row mb-4">
  <div class="col-md-3">
    <label>Available Slots:</label>
    <input type="text" id="availableSlots" class="form-control" readonly value="0">
  </div>
  <div class="col-md-3">
    <label>No. of Persons:</label>
    <input type="number" id="persons" class="form-control" value="1" min="1" max="200">
  </div>
  <div class="col-md-3 d-flex align-items-center gap-3">
    <div><strong>Amount (₹):</strong></div>
    <div id="fixedAmount">200</div>
    <div><strong>Total: ₹</strong> <span id="totalAmount">200</span></div>
  </div>
</div>

<div class="text-center">
  <button class="btn btn-primary px-4" id="bookBtn">Book Now</button>
</div>

<!-- Booking Summary Modal -->
<div class="modal fade" id="bookingSummary" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title">Booking Summary</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Temple:</strong> <?= $templeName ?></p>
        <p><strong>Persons:</strong> <span id="popupPersons"></span></p>
        <p><strong>Total:</strong> ₹<span id="popupTotal"></span></p>
        <label>Email:</label>
        <input type="email" id="emailInput" class="form-control" required placeholder="you@example.com">
      </div>
      <div class="modal-footer">
        <button class="btn btn-success" id="proceedToPay">Pay</button>
      </div>
    </div>
  </div>
</div>

<!-- Final Confirm Modal -->
<div class="modal fade" id="confirmPayment" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content p-3 text-center">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Payment</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to pay ₹<span id="finalAmount">0</span> for <span id="finalPersons">0</span> person(s)?</p>
      </div>
      <div class="modal-footer justify-content-center">
        <button class="btn btn-success" onclick="confirmPayment()">Yes, Pay</button>
        <button class="btn btn-danger" onclick="cancelBooking()">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!-- Payment Success Modal -->
<div id="successModal" class="modal-overlay" style="display:none">
  <div class="modal-content">
     <div class="circle">
    <span class="checkmark">&#10003;</span>
	</div>
    <h2>Your payment was successful</h2>
    <p>Your receipt has been sent to:</p>
    <p id="userEmailDisplay" style="font-weight: bold;"></p>
    <button onclick="closeSuccessModal()">OK</button>
  </div>
</div>
<!-- Hidden Booking Form -->
<form id="bookingForm" action="process_booking.php" method="POST" style="display:none;">
  <input type="hidden" name="temple_id" value="<?= $templeId ?>">
  <input type="hidden" name="slot_id" id="slotIdInput">
  <input type="hidden" name="slot_date" id="bookingDateInput">
  <input type="hidden" name="num_slots" id="slotCountInput">
  <input type="hidden" name="email" id="emailInputHidden">
</form>
<script>
const releasedMonths = <?= json_encode($releasedMonths) ?>;
let selectedSlot = null;
let viewStartMonth = 1;
const monthsPerView = 4;
const personsInput = document.getElementById('persons');
const totalDisplay = document.getElementById('totalAmount');
const fixedAmount = 200;

function renderCalendars() {
  const wrapper = document.getElementById('calendarWrapper');
  wrapper.innerHTML = '';
  const today = new Date();
  const year = today.getFullYear();
  const endMonth = Math.min(viewStartMonth + monthsPerView - 1, 12);

  const startMonthName = new Date(year, viewStartMonth - 1).toLocaleString('default', { month: 'long' });
  const endMonthName = new Date(year, endMonth - 1).toLocaleString('default', { month: 'long' });
  document.getElementById('monthRange').textContent = `${startMonthName.toUpperCase()} - ${endMonthName.toUpperCase()} ${year}`;

  for (let month = viewStartMonth; month <= endMonth; month++) {
    const firstDay = new Date(year, month - 1, 1).getDay();
    const daysInMonth = new Date(year, month, 0).getDate();
    const monthName = new Date(year, month - 1).toLocaleString('default', { month: 'long' });

    let card = `<div class="col-md-3 calendar-month">
      <div class="calendar-header">${monthName} ${year}</div>
      <div class="weekdays">
        <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
      </div>
      <div class="days-grid">`;

    for (let i = 0; i < firstDay; i++) {
      card += `<div class="calendar-day"></div>`;
    }

    for (let day = 1; day <= daysInMonth; day++) {
      const dateObj = new Date(year, month - 1, day);
      const dateStr = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
      const isReleased = releasedMonths.includes(month);
      let statusClass = '';

      if (dateObj < today.setHours(0, 0, 0, 0)) {
        statusClass = 'full';
      } else if (!isReleased) {
        statusClass = 'not-released';
      } else {
        statusClass = 'available';
      }

      card += `<div class="calendar-day ${statusClass}" data-day="${dateStr}" onclick="selectSlot(this)">${day}</div>`;
    }

    card += `</div></div>`;
    wrapper.innerHTML += card;
  }
}

function changeMonth(dir) {
  viewStartMonth += dir * monthsPerView;
  if (viewStartMonth < 1) viewStartMonth = 1;
  if (viewStartMonth > 12 - monthsPerView + 1) viewStartMonth = 12 - monthsPerView + 1;
  renderCalendars();
}

function selectSlot(el) {
  if (el.classList.contains('not-released') || el.classList.contains('full')) return;

  document.querySelectorAll('.calendar-day.user-selected').forEach(btn => {
    btn.classList.remove('user-selected');
    btn.classList.add('available');
  });

  el.classList.remove('available');
  el.classList.add('user-selected');

  selectedSlot = el.dataset.day;
  document.getElementById("bookingDateInput").value = selectedSlot;

  fetch(`get_available_slots.php?temple_id=<?= $templeId ?>&date=${selectedSlot}`)
    .then(res => res.json())
    .then(data => {
      if (data.status === "success") {
        document.getElementById('availableSlots').value = data.available_seats;
        document.getElementById('slotIdInput').value = data.slot_id;
      } else {
        alert("Slot not found or not released yet.");
        document.getElementById('availableSlots').value = 0;
      }
    })
    .catch(err => {
      console.error("Error fetching slot data:", err);
      alert("Server error while fetching slot.");
    });
}
document.getElementById('bookBtn').addEventListener('click', function () {
  const persons = parseInt(personsInput.value);
  const available = parseInt(document.getElementById('availableSlots').value);

  if (!selectedSlot) {
    alert("Please select a date.");
    return;
  }

  if (persons < 1 || persons > 200) {
    alert("Please enter a valid number of persons (1–200).");
    return;
  }

  if (persons > available) {
    alert("Not enough slots available for the selected date.");
    return;
  }

  document.getElementById('popupPersons').textContent = persons;
  const total = fixedAmount * persons;
  document.getElementById('popupTotal').textContent = total;
  document.getElementById('finalAmount').textContent = total;
  document.getElementById('finalPersons').textContent = persons;

  new bootstrap.Modal(document.getElementById('bookingSummary')).show();
});

document.getElementById('proceedToPay').addEventListener('click', function () {
  const email = document.getElementById('emailInput').value.trim();
  if (!email || !validateEmail(email)) {
    alert("Please enter a valid email address.");
    return;
  }

  document.getElementById('emailInputHidden').value = email;
  document.getElementById('slotCountInput').value = personsInput.value;

  bootstrap.Modal.getInstance(document.getElementById('bookingSummary')).hide();
  new bootstrap.Modal(document.getElementById('confirmPayment')).show();
});

function confirmPayment() {
  bootstrap.Modal.getInstance(document.getElementById('confirmPayment')).hide();
  document.getElementById('bookingForm').submit();
}

function cancelBooking() {
  bootstrap.Modal.getInstance(document.getElementById('confirmPayment')).hide();
  document.querySelectorAll('.calendar-day.user-selected').forEach(btn => {
    btn.classList.remove('user-selected');
    btn.classList.add('available');
  });
  document.getElementById('availableSlots').value = 0;
  document.getElementById("slotIdInput").value = selectedSlotId;
  document.getElementById("bookingDateInput").value = selectedDate;
  document.getElementById("slotCountInput").value = slotCount;
  document.getElementById("emailInputHidden").value = userEmail;
  document.getElementById("bookingForm").submit();


  personsInput.value = 1;
  updateTotal();
}

personsInput.addEventListener('input', updateTotal);
function updateTotal() {
  const persons = parseInt(personsInput.value) || 0;
  totalDisplay.textContent = fixedAmount * persons;
}

function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email.toLowerCase());
}


renderCalendars();

// payment 
function closeSuccessModal() {
  document.getElementById("successModal").style.display = "none";
  window.location.href = "../booking.php";
}

// Optional: Trigger the modal if redirected with success flag
window.onload = function() {
  const params = new URLSearchParams(window.location.search);
  if (params.get('success') === '1' && params.get('email')) {
    document.getElementById('userEmailDisplay').innerText = params.get('email');
    document.getElementById('successModal').style.display = 'flex';
  }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</div>
</div>
</body>
</html>

