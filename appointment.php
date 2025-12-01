<?php
@include 'config.php';
@include 'session.php'; 

$appointmentCollection = $client->appointmentdb->appointment;
$serviceCollection     = $client->appointmentdb->A_services;
$staffCollection       = $client->appointmentdb->A_staff;

$bookings = [];
$load_error = null;

try {
    $cursor = $appointmentCollection->find(
        ['user_id' => $user_id],
        ['sort' => ['created_at' => -1]]
    );
    $bookings = $cursor->toArray();
} catch (Throwable $e) {
    $bookings = [];
    $load_error = "Unable to load bookings.";
}


$info = $_SESSION['info_message'] ?? null;
$error = $_SESSION['error_message'] ?? null;
unset($_SESSION['info_message'], $_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Polish Perfection</title>
   <link rel="icon" href="image/logo2.jpg" type="image/jpeg">

   <link rel="stylesheet" href="css/style.css">
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
   .receipt { 
    max-width:900px; 
    margin:20px auto; 
    padding:12px; 
}

   .card { 
    background:#fff; 
    border-radius:8px; 
    padding:12px; 
    margin-bottom:12px; 
    box-shadow:0 6px 20px rgba(0,0,0,0.06); 
}

   .card h3 { 
    margin:0; 
}

   .meta { 
    color:#666; 
    font-size:13px; 
    margin-top:6px; 
}

   .status { 
    padding:6px 8px; 
    border-radius:6px; 
    font-weight:600; 
    display:inline-block; 
    margin-top:8px; 
}

   .status.pending { 
    background:#fff4e5; 
    color:#7a4a00; 
}

   .status.confirmed { 
    background:#e6ffed; 
    color:#0b5f2d; 
}

   .status.cancelled { 
    background:#ffe6e6; 
    color:#7a0000; 
}

   .empty { text-align:center; 
    color:#666; 
    padding:40px 0;
 }
   .clearfix::after { 
    content:""; 
    display:table; 
    clear:both; 
}

   .cancel-btn {
      background: #ff6b6b !important;
      color: #fff !important;
      border: none !important;
      padding: 6px 14px !important;
      font-size: 14px !important;
      border-radius: 5px !important;
      display: inline-block;
      width: auto !important;
      cursor: pointer;
   }

   .cancel-btn[disabled] { opacity: 0.6; cursor: not-allowed; }
   .small-meta { font-size:12px; color:#999; margin-top:6px; }

</style>

</head>
<body>

<?php @include 'header.php'; ?>

<section class="heading">
    <h3>My Appointments</h3>
</section>

<section class="receipt">

    <?php if ($info): ?>
        <div class="card" style="background:#f0fff6;color:#064; font-weight:600;">
            <?= htmlspecialchars($info); ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="card" style="background:#fff0f0;color:#700;">
            <?= htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($load_error): ?>
        <div class="card" style="background:#fff0f0;color:#700;">
            <?= htmlspecialchars($load_error); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($bookings)): ?>
        <?php foreach ($bookings as $b):

            $serviceName = "Unknown service";
            $staffName   = "Unknown staff";
            $servicePrice = $b["total_price"] ?? 0;

        
            if (!empty($b["service_id"]) && preg_match('/^[0-9a-fA-F]{24}$/', $b["service_id"])) {
                try {
                    $sDoc = $serviceCollection->findOne(["_id" => new MongoDB\BSON\ObjectId($b["service_id"])]);
                    if ($sDoc) $serviceName = $sDoc->service_name ?? $serviceName;
                } catch (Throwable $e) {}
            }

           
            if (!empty($b["staff_id"]) && preg_match('/^[0-9a-fA-F]{24}$/', $b["staff_id"])) {
                try {
                    $stDoc = $staffCollection->findOne(["_id" => new MongoDB\BSON\ObjectId($b["staff_id"])]);
                    if ($stDoc) $staffName = $stDoc->name ?? $staffName;
                } catch (Throwable $e) {}
            }

           
            $rawDT = $b["appointment_datetime"] ?? null;

            if ($rawDT) {
                $ts = strtotime($rawDT);
                $dateOnly = date("F d, Y", $ts);
                $timeOnly = date("h:i A", $ts);
            } else {
                $dateOnly = "Unknown";
                $timeOnly = "Unknown";
            }

            $status = strtolower($b["status"] ?? "pending");
            $statusClass = ($status === "confirmed") ? "confirmed" : (($status === "cancelled") ? "cancelled" : "pending");
        ?>

        <div class="card clearfix">

            <div style="float:left; max-width:65%;">
                <h3><?= htmlspecialchars($serviceName); ?></h3>
                <div class="meta">Staff: <?= htmlspecialchars($staffName); ?></div>

                <div class="meta">Date: <?= htmlspecialchars($dateOnly); ?></div>
                <div class="meta">Time: <?= htmlspecialchars($timeOnly); ?></div>

                <div class="meta">Payment: <?= htmlspecialchars(ucfirst($b["payment_method"] ?? "")); ?></div>

                <?php if (!empty($b["notes"])): ?>
                    <div class="meta">Notes: <?= nl2br(htmlspecialchars($b["notes"])); ?></div>
                <?php endif; ?>

                <div class="small-meta">Booked: <?= htmlspecialchars($b["created_at"] ?? ""); ?></div>
            </div>

            <div style="float:right; text-align:right;">
                <div style="font-weight:700; font-size:18px;">₱<?= number_format((float)$servicePrice, 2); ?></div>
                <div class="status <?= $statusClass; ?>"><?= ucfirst($status); ?></div>

                <?php if ($status !== "cancelled"): ?>
                    <form method="POST" action="cancel_appointment.php" class="cancel-form" data-id="<?= (string)$b['_id']; ?>" style="margin-top:8px;">
                        <input type="hidden" name="appointment_id" value="<?= htmlspecialchars((string)$b['_id']); ?>">
                        <button type="submit" class="cancel-btn">Cancel Appointment</button>
                    </form>
                <?php else: ?>
                    <div class="small-meta">Cancelled at: <?= htmlspecialchars($b["cancelled_at"] ?? ""); ?></div>
                <?php endif; ?>
            </div>

        </div>

        <?php endforeach; ?>
    <?php else: ?>
        <p class="empty">You haven't booked anything yet. Go to <a href="services.php">Services</a> to book.</p>
    <?php endif; ?>

</section>

<?php @include 'footer.php'; ?>

<script>

document.querySelectorAll('.cancel-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: "Cancel Appointment?",
            text: "This action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, Cancel",
            cancelButtonText: "No, Keep"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            } else {
                Swal.fire({
                    title: "",
                    text: "Your appointment was not cancelled.",
                    icon: "info",
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    });
});
</script>

</body>
</html>
