<?php
@include 'session.php'; 
@include 'config.php';


$client = $client ?? $GLOBALS['client']; 
$db = $client->appointmentdb;


$collection = $db->A_services;
try {
    $services = $collection->find([], ['limit' => 6])->toArray();
} catch (Throwable $e) {
    $services = [];
}


$staffCollection = $db->A_staff;
try {
    $staffList = $staffCollection->find()->toArray();
} catch (Throwable $e) {
    $staffList = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Polish Perfection</title>
   <link rel="icon" href="image/logo2.jpg" type="image/jpeg">
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>
<?php include 'modal.php'; ?>

<section class="home">
   <div class="content">
      <h3>POLISHED PERFECTION</h3>
   </div>
</section>

<section class="products">
   <h1 class="title">LATEST SERVICES</h1>

   <div class="box-container">
      <?php if (!empty($services)): ?>
         <?php foreach ($services as $service):
            $id = (string)$service->_id;
            $name = $service->service_name ?? "No Name";
            $price = $service->price ?? 0;
            $imagePath = "admin/uploaded_img/" . ($service->image ?? "default.jpg");
         ?>
         <div class="box">
            <a href="view_page.php?sid=<?= $id ?>" class="fas fa-eye"></a>
            <div class="price">₱<?= htmlspecialchars($price) ?></div>
            <img src="<?= $imagePath ?>" class="image">
            <div class="name"><?= htmlspecialchars($name) ?></div>

            <button type="button" class="btn"
               onclick="openBookingModal('<?= $id ?>','<?= addslashes($name) ?>','<?= $imagePath ?>','<?= $price ?>')">Book Now</button>

         </div>
         <?php endforeach; ?>
      <?php else: ?>
         <p class="empty">No services added yet!</p>
      <?php endif; ?>
   </div>

   <div class="more-btn">
      <a href="services.php" class="option-btn">Load More</a>
   </div>
</section>

<?php include 'footer.php'; ?>
</body>
</html>
