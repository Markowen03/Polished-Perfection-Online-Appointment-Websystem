<?php
@include 'config.php';
@include 'session.php';

$collection      = $client->appointmentdb->A_services;
$staffCollection = $client->appointmentdb->A_staff;

try {
    $staffList = $staffCollection->find()->toArray();
} catch (Throwable $e) {
    $staffList = [];
}

$service = null;
$sid = $_GET['sid'] ?? '';

if (preg_match('/^[0-9a-fA-F]{24}$/', $sid)) {
    try {
        $service = $collection->findOne([
            "_id" => new MongoDB\BSON\ObjectId($sid)
        ]);
    } catch (Throwable $e) {
        $service = null;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Polish Perfection</title>

   <link rel="icon" href="image/logo2.jpg" type="image/jpeg">
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/view_page.css">
</head>
<body>

<?php include 'header.php'; ?>
<?php include 'modal.php'; ?>

<section class="quick-view">
   <h1 class="title">Service Details</h1>

   <?php if ($service): ?>

   <div class="service-card">
      <img src="admin/uploaded_img/<?= htmlspecialchars($service->image) ?>" alt="Service Image">

      <div class="service-info">

         <div class="name"><?= htmlspecialchars($service->service_name) ?></div>

         <div class="price">₱<?= number_format($service->price, 2) ?></div>

         <div class="details">
            <?= nl2br(htmlspecialchars($service->description)) ?>
         </div>

         <button class="btn-main"
                 onclick="openBookingModal(
                     '<?= $sid ?>',
                     '<?= htmlspecialchars(addslashes($service->service_name)) ?>',
                     'admin/uploaded_img/<?= htmlspecialchars($service->image) ?>',
                     '<?= $service->price ?>'
                 )">
            Book Now
         </button>

      </div>
   </div>

   <?php else: ?>
      <p class="empty">Service not found.</p>
   <?php endif; ?>

   <a href="services.php" class="back-btn">← Back to Services</a>
</section>

<?php include 'footer.php'; ?>

</body>
</html>
