<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$usersCollection = $db->users;

$uploadDir = "image/";
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (isset($_FILES['profile_picture'])) {
    $fileError = $_FILES['profile_picture']['error'];
    $fileTmp   = $_FILES['profile_picture']['tmp_name'];
    $fileName  = $_FILES['profile_picture']['name'];

    if ($fileError === 0) {

        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (!in_array($fileExt, ['jpg', 'jpeg', 'png'])) {
            $_SESSION['profile_error'] = "Invalid file type. Only JPG, JPEG, PNG allowed.";
            header("Location: home.php");
            exit;
        }

   
        $newFileName = "profile_" . $user_id . "." . $fileExt;
        $filePath = $uploadDir . $newFileName;

      
        if (move_uploaded_file($fileTmp, $filePath)) {
           
            try {
                $usersCollection->updateOne(
                    ['_id' => new MongoDB\BSON\ObjectId($user_id)],
                    ['$set' => ['profile_picture' => $filePath]]
                );

              
                $_SESSION['user_profile'] = $filePath;

                $_SESSION['profile_success'] = "Profile picture updated successfully!";
                header("Location: home.php");
                exit;

            } catch (Throwable $e) {
                $_SESSION['profile_error'] = "Failed to update profile in database.";
                header("Location: home.php");
                exit;
            }
        } else {
            $_SESSION['profile_error'] = "Failed to move uploaded file.";
            header("Location: home.php");
            exit;
        }
    } else {
        $_SESSION['profile_error'] = "Upload error code: $fileError";
        header("Location: home.php");
        exit;
    }
} else {
    $_SESSION['profile_error'] = "No file uploaded.";
    header("Location: home.php");
    exit;
}
?>
