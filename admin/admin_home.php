<?php
require '../config.php';
require 'admin_session.php';

$servicesCollection     = $db->A_services;
$appointmentsCollection = $db->appointment;
$staffCollection        = $db->A_staff;
$messagesCollection     = $db->A_messages; 
$usersCollection        = $db->users;
$adminsCollection      = $db->A_users;


$services_count     = $servicesCollection->countDocuments();
$appointments_count = $appointmentsCollection->countDocuments();
$staff_count        = $staffCollection->countDocuments();
$messages_count     = $messagesCollection->countDocuments();
$users_count        = $usersCollection->countDocuments();
$admin_count       = $adminsCollection->countDocuments();
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Polish Perfection Dashboard</title>
   <link rel="icon" href="../image/logo2.jpg" type="image/jpeg">
   <link rel="stylesheet"
         href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php @include 'admin_header.php'; ?>

<section class="dashboard">
   <h1 class="title">Dashboard</h1>

   <div class="box-container">

      <div class="box">
         <h3><?php echo $services_count; ?></h3>
         <p>Services Added</p>
      </div>

      <div class="box">
         <h3><?php echo $appointments_count; ?></h3>
         <p>Appointments</p>
      </div>

      <div class="box">
         <h3><?php echo $staff_count; ?></h3>
         <p>Staff</p>
      </div>

      <div class="box">
         <h3><?php echo $messages_count; ?></h3>
         <p>Messages</p>
      </div>

      <div class="box">
         <h3><?php echo $users_count; ?></h3>
         <p>User Accounts</p>
      </div>

      <div class="box">
         <h3><?php echo $admin_count; ?></h3>
         <p>Admin</p>

   </div>
</section>

<script src="js/admin_script.js"></script>

</body>
</html>
