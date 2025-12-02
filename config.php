<?php

require __DIR__ . '/vendor/autoload.php';

use MongoDB\Client;

$client = new Client("mongodb://localhost:27017");


$db = $client->appointmentdb;


$collection = $db->A_services; 
$messagesCollection = $db->A_messages;

?>
