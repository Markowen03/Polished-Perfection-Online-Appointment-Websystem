<?php
@include 'config.php';
@include 'session.php'; 


if (!isset($user_id)) {
    header("Location: login.php");
    exit;
}


$staffCollection = $client->appointmentdb->A_staff;
try {
    $staffList = $staffCollection->find()->toArray();
} catch (Throwable $e) {
    $staffList = [];
}

$service_results = [];
$staff_results = [];
$searched = false;
$search_box = '';

if (isset($_POST['search_btn'])) {
    $searched = true;
    $search_box = trim($_POST['search_box']);

    if ($search_box !== "") {
        try {
 
            $service_results = iterator_to_array($client->appointmentdb->A_services->find([
                'service_name' => ['$regex' => $search_box, '$options' => 'i']
            ]));

            $staff_results = iterator_to_array($client->appointmentdb->A_staff->find([
                'name' => ['$regex' => $search_box, '$options' => 'i']
            ]));
        } catch (Throwable $e) {
            $service_results = [];
            $staff_results = [];
        }
    }
}


$hasService = count($service_results) > 0;
$hasStaff   = count($staff_results) > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Search - Polish Perfection</title>
   <link rel="icon" href="image/logo2.jpg" type="image/jpeg">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .results-title { font-size: 28px; font-weight: 700; margin: 30px 0 10px 10px; }
      .staff-info { font-size: 20px; margin-top: 5px; color: #333; }
   </style>
</head>
<body>

<?php include 'header.php'; ?>
<?php include 'modal.php'; ?> 

<section class="heading">
    <h3>Search</h3>
</section>

<section class="search-form">
    <form action="" method="POST">
        <input type="text" class="box" placeholder="Search services or staff..." name="search_box" value="<?= htmlspecialchars($search_box) ?>">
        <input type="submit" class="btn" value="Search" name="search_btn">
    </form>
</section>

<section class="products" style="padding-top: 0;">
   <div class="box-container">

      <?php if (!$searched): ?>
         <p class="empty">Search something!</p>
      <?php else: ?>

         <?php if (!$hasService && !$hasStaff): ?>
             <p class="empty">No results found!</p>
         <?php endif; ?>

         <?php if ($hasService): ?>
            <div class="results-title">Services</div>
            <?php foreach ($service_results as $service):
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
         <?php endif; ?>

         <?php if ($hasStaff): ?>
            <div class="results-title">Staff</div>
            <?php foreach ($staff_results as $staff):
                $imagePath = "admin/uploaded_img/" . ($staff->image ?? "default.jpg");
            ?>
            <div class="box">
                <img src="<?= $imagePath ?>" class="image">
                <div class="name"><?= htmlspecialchars($staff->name) ?></div>
                <p class="staff-info"><strong>Age:</strong> <?= htmlspecialchars($staff->age) ?></p>
                <p class="staff-info"><strong>Address:</strong> <?= htmlspecialchars($staff->address) ?></p>
            </div>
            <?php endforeach; ?>
         <?php endif; ?>

      <?php endif; ?>

   </div>
</section>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>

</body>
</html>
