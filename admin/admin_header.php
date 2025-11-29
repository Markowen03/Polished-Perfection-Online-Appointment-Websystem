<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <div class="flex">

      <a href="admin_home.php" class="logo">Admin<span>Panel</span></a>

      <nav class="navbar">
         <a href="admin_home.php">Home</a>
         <a href="admin_services.php">Services</a>
         <a href="admin_staff.php">Staff</a>
         <a href="admin_appointments.php">Appointments</a>
         <a href="admin_message.php">Messages</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
      </div>

      <div class="account-box">
         <p>username : <span><?php echo $_SESSION['admin_name']; ?></span></p>
         <p>email : <span><?php echo $_SESSION['admin_email']; ?></span></p>
         <a href="logout.php" class="delete-btn">logout</a>
         <div>new <a href="login.php">login</a> | <a href="register.php">register</a> </div>
      </div>

   </div>

</header>