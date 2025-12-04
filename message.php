<?php  
@include 'config.php';
@include 'session.php'; 


if (!isset($user_id)) {
    header("Location: login.php");
    exit;
}


$messagesCollection = $client->appointmentdb->A_messages;

$message_feedback = []; 

if (isset($_POST['send'])) {
    $msg = htmlspecialchars($_POST['message'], ENT_QUOTES);

   
    $messagesCollection->insertOne([
        "user_id"    => $user_id,
        "name"       => htmlspecialchars($user_name, ENT_QUOTES),
        "email"      => htmlspecialchars($user_email, ENT_QUOTES),
        "message"    => $msg,
        "created_at" => new MongoDB\BSON\UTCDateTime()
    ]);

    $message_feedback[] = "Message sent successfully!";
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
    <h3>Contact Us</h3>
</section>

<section class="contact">

    <?php if (!empty($message_feedback)): ?>
        <div class="card" style="background:#f0fff6;color:#064; font-weight:600; margin-bottom:15px; text-align:center;">
            <?php echo implode('<br>', $message_feedback); ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <h3>Send us a message!</h3>

        <input type="text" 
               name="name" 
               class="box" 
               value="<?php echo htmlspecialchars($user_name); ?>" 
               readonly>

        <input type="email" 
               name="email" 
               class="box" 
               value="<?php echo htmlspecialchars($user_email); ?>" 
               readonly>

        <textarea name="message" 
                  class="box" 
                  placeholder="Enter your message" 
                  required></textarea>

        <input type="submit" value="Send Message" name="send" class="btn">
    </form>

</section>

<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
