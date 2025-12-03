<?php
require 'config.php';
session_start(); 


if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit;
}

$collection = $db->users;
$message = "";
$secretKey = "6Lc_FtYrAAAAAFbHEnt48rxhmYy-_JDpnLPV6O74";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';


    if ($action === 'register') {
        $name = trim($_POST['name']);
        $email = strtolower(trim($_POST['email']));
        $password = $_POST['password'];

        $existingUser = $collection->findOne(['email' => $email]);

        if ($existingUser) {
            $message = "Email already registered!";
        } else {
            $collection->insertOne([
                "name" => $name,
                "email" => $email,
                "password" => password_hash($password, PASSWORD_DEFAULT),
                "profile_picture" => "image/default-user.png"
            ]);
            $message = "Registration successful! You can now login.";
        }
    }

    
    if ($action === 'login') {
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
                $user = $collection->findOne(['email' => $email]);

                if ($user && password_verify($password, $user['password'])) {
             
                    $_SESSION['user_id']      = (string)$user['_id'];
                    $_SESSION['user_name']    = $user['name'];
                    $_SESSION['user_email']   = $user['email'];
                    $_SESSION['user_profile'] = $user['profile_picture'] ?? "image/default-user.png";

                    header("Location: home.php");
                    exit();
                } else {
                    $message = "Invalid email or password!";
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
<title>Polish Perfection</title>
<link rel="icon" href="image/logo2.jpg" type="image/jpeg">

<link rel="stylesheet" href="style.css">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script defer src="script.js"></script>
</head>
<body>


<div id="flash-message" data-message="<?= htmlspecialchars($message) ?>"></div>

<div class="container" id="container">


  <div class="form-container sign-up">
    <form method="POST">
      <h1>Create Account</h1>
      <input type="text" name="name" placeholder="Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="action" value="register">Sign Up</button>
    </form>
  </div>

 
  <div class="form-container sign-in">
    <form method="POST">
      <h1>Sign In</h1>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>

      <div class="g-recaptcha" data-sitekey="6Lc_FtYrAAAAAK3_wedtIMlzlNweIdlxep_CWm6b"></div>

      <a href="#" id="forgot">Forget Your Password?</a>
      <button type="submit" name="action" value="login">Sign In</button>
    </form>
  </div>


  <div class="toggle-container">
    <div class="toggle">
      <div class="toggle-panel toggle-left">
        <h1>Welcome!</h1>
        <p>Please enter all the necessary information. Thank you!</p>
        <button class="hidden" id="login">Sign In</button>
      </div>
      <div class="toggle-panel toggle-right">
        <h1>Polished Perfection</h1>
        <p>Don’t have an account? Register now</p>
        <button class="hidden" id="register">Sign Up</button>
      </div>
    </div>
  </div>

</div>
</body>
</html>
