<?php
require_once 'vendor/autoload.php'; // make sure you installed google/apiclient via composer

$client = new Google_Client();
$client->setClientId('YOUR_CLIENT_ID_HERE');
$client->setClientSecret('YOUR_CLIENT_SECRET_HERE');
$client->setRedirectUri('http://localhost/yourproject/google-callback.php');
$client->addScope('email');
$client->addScope('profile');

header('Location: ' . $client->createAuthUrl());
exit;
?>
