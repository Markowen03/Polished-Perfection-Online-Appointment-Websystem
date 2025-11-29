<?php
require_once __DIR__ . '/../config.php'; 
require 'admin_session.php';


use MongoDB\BSON\ObjectId;

$admin_id = $_SESSION['admin_id'];

$collection = $client->selectCollection("appointmentdb", "A_staff");

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];

   $collection->deleteOne([
   "_id" => new ObjectId($delete_id)
]);


   header("location: admin_staff.php");
   exit();
}

if(isset($_POST['add_staff'])){
   $name = $_POST['name'];
   $age = intval($_POST['age']);
   $address = $_POST['address'];

   $image_name = $_FILES['image']['name'];
   $image_tmp = $_FILES['image']['tmp_name'];
   $folder = "uploaded_img/" . $image_name;

   if(!empty($image_name)){
      move_uploaded_file($image_tmp, $folder);
   }

   $collection->insertOne([
      "name" => $name,
      "age" => $age,
      "address" => $address,
      "image" => $image_name
   ]);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Polish Perfection Dashboard</title>
   <link rel="icon" href="../image/logo2.jpg" type="image/jpeg">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php @include 'admin_header.php'; ?>

<section class="users">
   <section class="add-products">
    <form action="" method="POST" enctype="multipart/form-data">
        <h3>Add New Staff</h3>
        <input type="text" class="box" required placeholder="Enter staff name" name="name">
        <input type="number" min="0" class="box" required placeholder="Enter age" name="age">
        <textarea name="address" class="box" required placeholder="Enter address" cols="30" rows="10"></textarea>
        <input type="file" accept="image/jpg, image/jpeg, image/png" required class="box" name="image">
        <input type="submit" value="Add Staff" name="add_staff" class="btn">
    </form>
</section>

<br><br><br><br><br>

<h2 class="title">List of Staff</h2>

<br><br><br>

   <div class="box-container">
      <?php
         $staffList = $collection->find([]);

         foreach($staffList as $staff){
      ?>
      <div class="box">

         <img src="uploaded_img/<?php echo $staff['image']; ?>" 
              style="width:100%; height:180px; object-fit:cover; border-radius:5px;">

         <p><strong>Name:</strong> <?php echo $staff['name']; ?></p>
         <p><strong>Age:</strong> <?php echo $staff['age']; ?></p>
         <p><strong>Address:</strong> <?php echo $staff['address']; ?></p>

         <a href="admin_staff.php?delete=<?php echo $staff['_id']; ?>" 
            onclick="return confirm('Delete this staff?');" 
            class="delete-btn">
            Delete
         </a>
      </div>
      <?php } ?>
   </div>

</section>

<script src="js/admin_script.js"></script>
</body>
</html>
