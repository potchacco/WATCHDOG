<?php
require_once 'vendor/autoload.php';
session_start();

$client = new Google_Client();
$client->setClientId('YOUR_CLIENT_ID_HERE');
$client->setClientSecret('YOUR_CLIENT_SECRET_HERE');
$client->setRedirectUri('http://localhost/yourproject/google-callback.php');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    $google_service = new Google\Service\Oauth2($client);
    $user = $google_service->userinfo->get();

    // Example: Store in session (or insert to DB if new)
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_name'] = $user->name;
    $_SESSION['user_picture'] = $user->picture;

    header('Location: user_homepage.php'); // redirect to dashboard
    exit;
}
?>
