<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


$user_id      = $_SESSION['user_id'];
$user_name    = $_SESSION['user_name'] ?? 'Guest';
$user_email   = $_SESSION['user_email'] ?? '';
$user_profile = $_SESSION['user_profile'] ?? 'image/default-user.png';
?>
