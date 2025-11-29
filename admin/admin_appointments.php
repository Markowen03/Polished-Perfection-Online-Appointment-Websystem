<?php
require '../config.php';
require 'admin_session.php';


use MongoDB\BSON\ObjectId;

$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->appointmentdb;

$appointmentsCollection = $db->appointment;
$staffCollection = $db->A_staff;
$servicesCollection = $db->A_services;
$userCollection = $db->users; 


if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $objectId = new ObjectId($delete_id);
    $appointmentsCollection->deleteOne(['_id' => $objectId]);
    header("Location: admin_appointments.php");
    exit();
}


if (isset($_POST['update_payment'])) {
    $appointment_id = $_POST['appointment_id'];
    $new_status = $_POST['new_payment_status'];

    $appointmentsCollection->updateOne(
        ['_id' => new ObjectId($appointment_id)],
        ['$set' => ['payment_status' => $new_status, 'updated_at' => date('Y-m-d H:i:s')]]
    );

    header("Location: admin_appointments.php");
    exit();
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
   <link rel="icon" href="../image/logo2.jpg" type="image/jpeg">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php @include 'admin_header.php'; ?>

<section class="placed-orders">

   <h1 class="title">Appointments</h1>

   <div class="box-container">

      <?php
         $appointments = $appointmentsCollection->find([], [
            'sort' => ['appointment_datetime' => -1]
         ]);

         $count = 0;

         foreach ($appointments as $a) {
            $count++;

           
            $user = $userCollection->findOne([
                '_id' => new ObjectId((string)$a->user_id)
            ]);

            $user_name = $user ? $user->name : "Unknown User";
            $user_email = $user ? $user->email : "N/A";

          
            $staff = $staffCollection->findOne(['_id' => new ObjectId((string)$a->staff_id)]);
            $staff_name = $staff ? $staff->name : "Unknown Staff";

            
            $service_name = '';
            if (is_array($a->service_id) || $a->service_id instanceof MongoDB\Model\BSONArray) {
                $names = [];
                foreach ($a->service_id as $sid) {
                    $service = $servicesCollection->findOne(['_id' => new ObjectId((string)$sid)]);
                    if ($service) $names[] = $service->service_name;
                }
                $service_name = implode(", ", $names);
            } else {
                $service = $servicesCollection->findOne(['_id' => new ObjectId((string)$a->service_id)]);
                $service_name = $service ? $service->service_name : "Unknown";
            }
      ?>

      <div class="box">

         <p> Appointment ID : <span><?= $a->_id ?></span> </p>

         
         <p> User Name : <span><?= $user_name ?></span></p>
         <p> User Email : <span><?= $user_email ?></span></p>
         <p> User ID : <span><?= $a->user_id ?></span></p>

         <p> Staff Name : <span><?= $staff_name ?></span></p>
         <p> Service Booked : <span><?= $service_name ?></span></p>
         <p> Appointment Date : <span><?= $a->appointment_datetime ?? "N/A" ?></span></p>
         <p> Status : <span><?= $a->status ?? "N/A" ?></span></p>
         <p> Notes : <span><?= $a->notes ?: "None" ?></span></p>
         <p> Total Price : <span>₱<?= $a->total_price ?? 0 ?></span></p>
         <p> Payment Method : <span><?= $a->payment_method ?? "N/A" ?></span></p>

         
         <form method="POST" action="">
            <p> Payment Status : 
                <select name="new_payment_status">
                    <option value="<?= $a->payment_status ?>" selected><?= $a->payment_status ?></option>
                    <option value="pending">pending</option>
                    <option value="paid">paid</option>
                    <option value="completed">completed</option>
                    <option value="cancelled">cancelled</option>
                </select>
            </p>
            <input type="hidden" name="appointment_id" value="<?= $a->_id ?>">
            <input type="submit" name="update_payment" class="option-btn" value="Update">
         </form>

         <br><br>

         <p> Created At : <span><?= $a->created_at ?? "N/A" ?></span></p>
         <p> Updated At : <span><?= $a->updated_at ?? "N/A" ?></span></p>
         <p> Cancelled At : <span><?= $a->cancelled_at ?? "N/A" ?></span></p>

         
         <a href="admin_appointments.php?delete=<?= $a->_id ?>" 
            class="delete-btn" 
            onclick="return confirm('Are you sure you want to delete this appointment?');">
            delete
         </a>

      </div>

      <?php } ?>

      <?php 
         if ($count === 0) {
            echo '<p class="empty">No appointments found!</p>';
         }
      ?>

   </div>

</section>

<script src="js/admin_script.js"></script>

</body>
</html>
