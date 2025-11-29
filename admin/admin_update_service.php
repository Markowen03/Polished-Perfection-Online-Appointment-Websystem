<?php
use MongoDB\BSON\ObjectId;

require_once __DIR__ . '/../config.php';

$collection = $client->appointmentdb->A_services;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = new ObjectId($_POST['update_id']);

    $name = $_POST['update_name'];
    $price = floatval($_POST['update_price']);
    $desc = $_POST['update_desc'];

  
    $updateData = [
        'service_name' => $name,
        'price' => $price,
        'description' => $desc,
        'updated_at' => date('Y-m-d H:i:s')
    ];

 
    if (!empty($_FILES['update_image']['name'])) {

        $img = $_FILES['update_image']['name'];
        $tmp = $_FILES['update_image']['tmp_name'];

        move_uploaded_file($tmp, "uploaded_img/" . $img);

      
        $updateData['image'] = $img;
    }

    $collection->updateOne(
        ['_id' => $id],
        ['$set' => $updateData]
    );

    echo "updated";
}
