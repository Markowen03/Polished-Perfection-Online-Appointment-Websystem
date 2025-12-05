<?php
@include 'config.php';
@include 'session.php'; 

$collection = $client->appointmentdb->A_staff;

try {
    $staff_list = $collection->find([])->toArray();
} catch (Throwable $e) {
    $staff_list = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Polish Perfection</title>
   <link rel="icon" href="image/logo2.jpg" type="image/jpeg">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php @include 'header.php'; ?>

<section class="heading">
    <h3>Staff</h3>
</section>

<section class="products">
   <h1 class="title">Available Staff</h1>
   <div class="box-container">
      <?php if (!empty($staff_list)): ?>
         <?php foreach ($staff_list as $staff): ?>
            <div class="box">
               <img src="admin/uploaded_img/<?php echo $staff->image ?? 'default.jpg'; ?>" 
                    alt="" class="image">
               <div class="name" style="font-size: 20px;">
                   <?php echo htmlspecialchars($staff->name); ?>
               </div>
               <div class="info" style="font-size: 16px;">
                   <p><strong>Age:</strong> <?php echo htmlspecialchars($staff->age); ?></p>
                   <p><strong>Address:</strong> <?php echo htmlspecialchars($staff->address); ?></p>
               </div>
            </div>
         <?php endforeach; ?>
      <?php else: ?>
         <p class="empty">No staff added yet!</p>
      <?php endif; ?>
   </div>
</section>

<?php @include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
