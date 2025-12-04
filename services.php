<?php
@include 'config.php';
@include 'session.php';

$staffCollection = $client->appointmentdb->A_staff;
try {
    $staffList = $staffCollection->find()->toArray();
} catch (Throwable $e) {
    $staffList = [];
}

$collection = $client->appointmentdb->A_services;
$services = $collection->find([]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Polish Perfection</title>
   <link rel="icon" href="image/logo2.jpg" type="image/jpeg">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>
<?php include 'modal.php'; ?>

<section class="heading">
    <h3>Services</h3>
</section>

<section class="products">
   <h1 class="title">Available Services</h1>
   <div class="box-container">
      <?php foreach ($services as $service): 
         $sid = (string)$service->_id;
         $imagePath = "admin/uploaded_img/" . ($service->image ?? "default.jpg");
      ?>
      <div class="box">
         <a href="view_page.php?sid=<?= $sid ?>" class="fas fa-eye"></a>
         <div class="price">₱<?= $service->price ?></div>
         <img src="<?= $imagePath ?>" class="image">
         <div class="name"><?= htmlspecialchars($service->service_name) ?></div>
         <button type="button" class="btn"
            onclick="openBookingModal(
                '<?= $sid ?>',
                '<?= addslashes($service->service_name) ?>',
                '<?= $imagePath ?>',
                '<?= $service->price ?>'
            )">
            Book Now
         </button>
      </div>
      <?php endforeach; ?>
   </div>
</section>

<?php include 'footer.php'; ?>
</body>
</html>
