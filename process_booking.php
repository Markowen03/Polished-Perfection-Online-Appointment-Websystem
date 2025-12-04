<?php
@include 'config.php';
@include 'session.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';
require __DIR__ . '/PHPMailer/Exception.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id      = $_SESSION['user_id'];
$userEmail    = $_SESSION['user_email'] ?? null;
$userName     = $_SESSION['user_name'] ?? "User";


$service_id       = $_POST['service_id'] ?? null;
$staff_id         = $_POST['staff_id'] ?? null;
$appointment_date = $_POST['appointment_date'] ?? null;
$appointment_time = $_POST['appointment_time'] ?? null;
$payment_method   = $_POST['payment_method'] ?? null;
$notes            = $_POST['notes'] ?? '';

if (!$service_id || !$staff_id || !$appointment_date || !$appointment_time || !$payment_method) {
    $_SESSION['booking_error'] = "Please fill in all required booking fields.";
    header("Location: services.php");
    exit;
}

$appointmentCollection = $client->appointmentdb->appointment;
$serviceCollection     = $client->appointmentdb->A_services;

try {
    $price = 0;

    if (preg_match('/^[0-9a-fA-F]{24}$/', $service_id)) {
        try {
            $serviceObjId = new MongoDB\BSON\ObjectId($service_id);
            $serviceDoc = $serviceCollection->findOne(['_id' => $serviceObjId]);
            if ($serviceDoc) $price = $serviceDoc->price ?? 0;
        } catch (Throwable $e) {}
    }

    $appointmentCollection->insertOne([
        'user_id' => $user_id,
        'service_id' => (string)$service_id,
        'staff_id' => (string)$staff_id,
        'appointment_datetime' => $appointment_date . ' ' . $appointment_time,
        'status' => 'pending',
        'notes' => $notes,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
        'total_price' => $price,
        'payment_status' => 'pending',
        'payment_method' => $payment_method,
        'transaction_id' => '',
        'payment_date' => ''
    ]);


    if ($userEmail) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = "smtp.gmail.com";
            $mail->SMTPAuth   = true;
            $mail->Username   = "markowenbadua711@gmail.com";
            $mail->Password   = "yqkj rvoh panp ilcc";
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom("markowenbadua711@gmail.com", "Polished Perfection");
            $mail->addAddress($userEmail, $userName);

            $mail->isHTML(true);
            $mail->Subject = "Your Booking Confirmation on Polished Perfection";

            $mail->Body = "
                <h2>Booking Confirmed!</h2>
                <p>Hello <strong>$userName</strong>,</p>
                <p>Your appointment has been successfully booked.</p>
                
                <h3>Booking Details:</h3>
                <p><strong>Date:</strong> $appointment_date</p>
                <p><strong>Time:</strong> $appointment_time</p>
                <p><strong>Payment Method:</strong> $payment_method</p>
                <p><strong>Notes:</strong> $notes</p>

                <br><br>
                <p>Thank you for choosing our service!</p>
            ";

            $mail->send();

        } catch (Exception $e) {
            error_log("Mail Error: " . $mail->ErrorInfo);
        }
    }

    header("Location: appointment.php?created=1");
    exit;

} catch (Throwable $e) {

    $_SESSION['booking_error'] = "Booking failed: " . $e->getMessage();
    header("Location: services.php");
    exit;
}
?>
