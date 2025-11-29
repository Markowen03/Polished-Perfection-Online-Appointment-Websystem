<?php

use MongoDB\BSON\ObjectId;

require_once __DIR__ . '/../config.php';
require 'admin_session.php';

$admin_id = $_SESSION['admin_id'] ?? null;

$collection = $client->appointmentdb->A_services;

$message = [];


if (isset($_POST['add_service'])) {

    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $details = trim($_POST['details']);
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

   
    $existing = $collection->findOne(['service_name' => $name]);

    if ($existing) {
        $message[] = 'Service name already exists!';
    } else {

        if ($image_size > 2000000) {
            $message[] = 'Image size is too large!';
        } else {

            move_uploaded_file($image_tmp_name, $image_folder);

            $insert = $collection->insertOne([
                'service_name' => $name,
                'price' => $price,
                'description' => $details,
                'image' => $image,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            ($insert->getInsertedId())
                ? $message[] = 'Service added successfully!'
                : $message[] = 'Failed to add service!';
        }
    }
}



if (isset($_GET['delete'])) {

    $delete_id = $_GET['delete'];

    if (!empty($delete_id)) {

        $objectId = new ObjectId($delete_id);

        $service = $collection->findOne(['_id' => $objectId]);

        if ($service) {

            if (!empty($service['image']) && file_exists("uploaded_img/" . $service['image'])) {
                unlink("uploaded_img/" . $service['image']);
            }

            $collection->deleteOne(['_id' => $objectId]);
        }
    }

    header("Location: admin_services.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Polish Perfection Dashboard</title>
   <link rel="icon" href="../image/logo2.jpg" type="image/jpeg">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="../css/admin_style.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>

<?php @include 'admin_header.php'; ?>



<section class="add-products">
    <form action="" method="POST" enctype="multipart/form-data">
        <h3>Add New Service</h3>

        <input type="text" class="box" required placeholder="Enter service name" name="name">
        <input type="number" min="0" class="box" required placeholder="Enter price" name="price">
        <textarea name="details" class="box" required placeholder="Enter description" cols="30" rows="10"></textarea>
        <input type="file" accept="image/jpg, image/jpeg, image/png" required class="box" name="image">

        <input type="submit" value="Add Service" name="add_service" class="btn">
    </form>
</section>

<br><br><br><br><br><br><br>

<h2 class="title">List of Services</h2>

<section class="show-products">
    <div class="box-container">

        <?php
        $services = $collection->find([], ['sort' => ['created_at' => -1]]);
        $hasServices = false;

        foreach ($services as $service):
            $hasServices = true;
            $service_id = (string)$service['_id'];
        ?>

        <div class="box">
            <div class="price">₱<?= $service['price'] ?></div>

            <img src="uploaded_img/<?= $service['image'] ?>"
                 style="width:100%; height:200px; object-fit:cover; border-radius:5px;">

            <div class="name"><?= $service['service_name'] ?></div>
            <div class="details"><?= $service['description'] ?></div>

            
            <a href="#" class="option-btn"
               onclick="openUpdateModal(
                '<?= $service_id ?>',
                '<?= htmlspecialchars($service['service_name'], ENT_QUOTES) ?>',
                '<?= $service['price'] ?>',
                '<?= htmlspecialchars($service['description'], ENT_QUOTES) ?>',
                '<?= $service['image'] ?>'
               )">Update</a>

            <a href="#" class="delete-btn" onclick="confirmDelete('<?= $service_id ?>')">Delete</a>

        </div>

        <?php endforeach; ?>

        <?php if (!$hasServices): ?>
            <p class="empty">No services added yet!</p>
        <?php endif; ?>

    </div>
</section>


<script src="../js/admin_script.js"></script>


<?php include 'admin_modal.php'; ?>

</body>
</html>
