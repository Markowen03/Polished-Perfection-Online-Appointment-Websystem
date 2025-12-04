<?php
$staffCollection = $client->appointmentdb->A_staff;
$staffList = $staffCollection->find()->toArray();
?>

<style>

.booking-modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background: rgba(20, 20, 20, 0.85);
  backdrop-filter: blur(6px);
}

.booking-modal .modal-content {
  background: #1a1a1a;
  margin: 6% auto;
  padding: 20px;
  border-radius: 12px;
  width: 90%;
  max-width: 420px;
  color: #fff;
  border: 1px solid #333;
  animation: fadeIn 0.25s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.93); }
  to { opacity: 1; transform: scale(1); }
}

.close-btn {
  float: right;
  font-size: 28px;
  cursor: pointer;
  color: #fff;
}
.close-btn:hover {
  color: #ff5757;
}

.btn-main {
  background: #ff0066;
  border: none;
  padding: 12px;
  width: 100%;
  color: white;
  border-radius: 8px;
  margin-top: 10px;
  cursor: pointer;
  font-size: 15px;
}
.btn-main:hover {
  background: #cc0052;
}

.service-inline {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 10px;
  background: #222;
  border-radius: 10px;
  margin-bottom: 12px;
}

.service-inline img {
  width: 85px;
  height: 85px;
  object-fit: cover;
  border-radius: 10px;
  border: 1px solid #444;
}

.modal-content label {
  font-weight: 600;
  margin-top: 10px;
  display: block;
}

.modal-content input,
.modal-content select,
.modal-content textarea {
  width: 100%;
  padding: 10px;
  border-radius: 6px;
  background: #2a2a2a;
  border: 1px solid #444;
  color: white;
}
</style>

<div id="bookingModal" class="booking-modal" aria-hidden="true">
  <div class="modal-content" role="dialog" aria-modal="true">
    <span class="close-btn" onclick="closeBookingModal()">&times;</span>
    <h2>Confirm Booking</h2>

    <div id="modalServiceInfo" class="service-inline" style="display: none;">
      <img id="modalServiceImg" src="" alt="service">
      <div>
         <div id="modalServiceName" style="font-weight:700;"></div>
         <div id="modalServicePrice" style="font-size:13px;color:#ccc;"></div>
      </div>
    </div>

    <form action="process_booking.php" method="POST">
      <input type="hidden" name="service_id" id="modal_service_id">

      <label>Select Staff</label>
      <select name="staff_id" required>
        <option value="">-- Choose Staff --</option>
        <?php foreach ($staffList as $staff): ?>
          <option value="<?php echo (string)$staff->_id; ?>">
            <?php echo htmlspecialchars($staff->name); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Select Date</label>
      <input type="date" name="appointment_date" required>

      <label>Select Time</label>
      <input type="time" name="appointment_time" required>

      <label>Payment Method</label>
      <select name="payment_method" required>
        <option value="">-- Choose Payment --</option>
        <option value="cash">Cash</option>
        <option value="gcash">GCash</option>
        <option value="credit_card">Credit Card</option>
      </select>

      <label>Notes (optional)</label>
      <textarea name="notes"></textarea>

      <button type="submit" class="btn-main">Confirm Booking</button>
    </form>
  </div>
</div>

<script>
function openBookingModal(id, title, img, price) {
    document.getElementById("modal_service_id").value = id;
    document.getElementById("modalServiceName").innerText = title;
    document.getElementById("modalServiceImg").src = img;
    document.getElementById("modalServicePrice").innerText = "₱" + price;

    document.getElementById("modalServiceInfo").style.display = "flex";
    document.getElementById("bookingModal").style.display = "block";
}

function closeBookingModal() {
    document.getElementById("bookingModal").style.display = "none";
}

window.addEventListener("click", function (e) {
    if (e.target === document.getElementById("bookingModal")) {
        closeBookingModal();
    }
});
</script>
