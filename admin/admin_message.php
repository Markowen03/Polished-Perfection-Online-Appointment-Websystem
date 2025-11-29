<?php
require '../config.php';
require 'admin_session.php';



if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    $messagesCollection->deleteOne([
        "_id" => new MongoDB\BSON\ObjectId($delete_id)
    ]);

    header('location:admin_message.php');
    exit;
}


$usersCollection = $client->appointmentdb->users;
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

 
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <style>

      .messages .box-container {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
         gap: 20px;
         padding: 20px;
      }

      .chat-card {
         background: #fff;
         border-radius: 15px;
         padding: 15px;
         box-shadow: 0 4px 12px rgba(0,0,0,0.1);
         display: flex;
         flex-direction: column;
         transition: .2s;
      }

      .chat-card:hover {
         transform: translateY(-3px);
         box-shadow: 0 6px 15px rgba(0,0,0,0.15);
      }

      .profile-row {
         display: flex;
         align-items: flex-start;
         gap: 15px;
      }

      .profile-pic {
         width: 70px;
         height: 70px;
         border-radius: 50%;
         object-fit: cover;
         border: 3px solid #ddd;
      }

      .msg-content {
         flex: 1;
         background: #f4f6f9;
         padding: 12px 15px;
         border-radius: 12px;
         position: relative;
         font-size: 14px;
      }

      .msg-header {
         font-weight: 700;
         display: flex;
         justify-content: space-between;
         margin-bottom: 3px;
         font-size: 15px;
      }

      .msg-email {
         color: #777;
         font-size: 13px;
         margin-bottom: 6px;
      }

      .msg-text {
         margin-top: 5px;
         font-size: 14px;
         line-height: 1.4;
      }

      .delete-btn {
         margin-top: 15px;
         width: 100%;
         text-align: center;
         display: block;
         background: #ff4d4d;
         padding: 10px;
         color: #fff;
         font-weight: 600;
         border-radius: 10px;
         transition: .2s;
      }

      .delete-btn:hover {
         background: #d63333;
      }

   </style>

</head>
<body>

<?php @include 'admin_header.php'; ?>

<section class="messages">

   <h1 class="title">messages</h1>

   <div class="box-container">

      <?php
      $allMessages = $messagesCollection->find([], [
         'sort' => ['created_at' => -1]
      ]);

      $hasMessages = false;

      foreach ($allMessages as $msg) {
         $hasMessages = true;

       
         $user = $usersCollection->findOne([
            "_id" => new MongoDB\BSON\ObjectId($msg->user_id)
         ]);

         $profilePic = "../uploaded_img/default_profile.jpg";
         if ($user && isset($user->profile_picture)) {
            $profilePic = "../" . $user->profile_picture;
         }
      ?>

      <div class="chat-card">

         <div class="profile-row">
            <img src="<?php echo $profilePic; ?>" class="profile-pic">

            <div class="msg-content">
               <div class="msg-header">
                  <?php echo htmlspecialchars($msg->name); ?>
                  <span>
                  <?php 
                     echo isset($msg->created_at)
                        ? $msg->created_at->toDateTime()->format("Y-m-d H:i")
                        : "N/A";
                  ?>
                  </span>
               </div>

               <div class="msg-email"><?php echo htmlspecialchars($msg->email); ?></div>
               <div class="msg-text"><?php echo htmlspecialchars($msg->message); ?></div>
            </div>
         </div>

       
         <a href="#" onclick="confirmDeleteMessage('<?php echo $msg->_id; ?>')" 
            class="delete-btn">Delete Message</a>

      </div>

      <?php } ?>

      <?php if (!$hasMessages) { ?>
         <p class="empty">you have no messages!</p>
      <?php } ?>

   </div>

</section>

<script src="js/admin_script.js"></script>

<script>
function confirmDeleteMessage(id) {
    Swal.fire({
        title: "Delete this message?",
        text: "This action cannot be undone!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "admin_message.php?delete=" + id;
        }
    });
}
</script>

</body>
</html>
