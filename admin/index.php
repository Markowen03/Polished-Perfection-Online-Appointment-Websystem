<?php
require '../config.php';
session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: admin_home.php");
    exit;
}

$collection = $db->A_users;

$message = "";
$secretKey = "6Lc_FtYrAAAAAFbHEnt48rxhmYy-_JDpnLPV6O74";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['action'] === 'admin_login') {

        $email = strtolower(trim($_POST['email']));
        $password = $_POST['password'];

        if (empty($_POST['g-recaptcha-response'])) {
            $message = "Captcha missing!";
        } else {
            $captcha = $_POST['g-recaptcha-response'];


            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://www.google.com/recaptcha/api/siteverify",
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query([
                    'secret' => $secretKey,
                    'response' => $captcha
                ]),
                CURLOPT_RETURNTRANSFER => true
            ]);

            $verifyResponse = curl_exec($curl);
            curl_close($curl);

            $captchaSuccess = json_decode($verifyResponse);

            if (!isset($captchaSuccess->success) || !$captchaSuccess->success) {
                $message = "Please verify the captcha!";
            } else {
      
                $admin = $collection->findOne(['email' => $email]);

                if ($admin && password_verify($password, $admin['password'])) {
                 
                    $_SESSION['admin_id']    = (string)$admin['_id'];
                    $_SESSION['admin_name']  = $admin['name'];
                    $_SESSION['admin_email'] = $admin['email'];

                    header("Location: admin_home.php");
                    exit();
                } else {
                    $message = "Invalid admin credentials!";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Polish Perfection Dashboard</title>
   <link rel="icon" href="../image/logo2.jpg" type="image/jpeg">

<link rel="stylesheet" href="../style.css">
<link rel="icon" href="image/logo2.jpg" type="image/jpeg">

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script defer src="script.js"></script>
</head>
<body>

<div id="flash-message" data-message="<?= htmlspecialchars($message) ?>"></div>

<div class="container" id="container">

 
  <div class="form-container sign-in">
    <form method="POST">
      <h1>Admin Login</h1>

      <input type="email" name="email" placeholder="Admin Email" required>
      <input type="password" name="password" placeholder="Password" required>

      <div class="g-recaptcha" data-sitekey="6Lc_FtYrAAAAAK3_wedtIMlzlNweIdlxep_CWm6b"></div>

      <button type="submit" name="action" value="admin_login">Sign In</button>
    </form>
  </div>

  <div class="toggle-container">
    <div class="toggle">
      <div class="toggle-panel toggle-right">
        <h1>Administrator Panel</h1>
        <p>Authorized personnel only</p>
      </div>
    </div>
  </div>

</div>

</body>
</html>
