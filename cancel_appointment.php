<?php
@include 'config.php';
@include 'session.php';  

$user_id = $_SESSION['user_id']; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: appointment.php");
    exit;
}

$appointment_id = $_POST['appointment_id'] ?? null;

if (!$appointment_id || !preg_match('/^[0-9a-fA-F]{24}$/', $appointment_id)) {
    $_SESSION['error_message'] = "Invalid appointment ID.";
    header("Location: appointment.php");
    exit;
}

$appointmentCollection = $client->appointmentdb->appointment;

try {
    $oid = new MongoDB\BSON\ObjectId($appointment_id);

    $existing = $appointmentCollection->findOne([
        '_id' => $oid,
        'user_id' => $user_id
    ]);

    if (!$existing) {
        $_SESSION['error_message'] = "Appointment not found or not yours.";
        header("Location: appointment.php");
        exit;
    }

    $currentStatus = strtolower($existing['status'] ?? 'pending');
    if (!in_array($currentStatus, ['pending', 'confirmed'])) {
        $_SESSION['error_message'] = "This appointment cannot be cancelled.";
        header("Location: appointment.php");
        exit;
    }

    $appointmentCollection->updateOne(
        ['_id' => $oid, 'user_id' => $user_id],
        ['$set' => [
            'status' => 'cancelled',
            'cancelled_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]]
    );

    $_SESSION['info_message'] = "Appointment cancelled successfully.";
    header("Location: appointment.php");
    exit;

} catch (Throwable $e) {
    $_SESSION['error_message'] = "Error: " . $e->getMessage();
    header("Location: appointment.php");
    exit;
}
