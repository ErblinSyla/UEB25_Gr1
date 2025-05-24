<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    exit('Unauthorized');
}

$username = $_SESSION['username'];
$darkmode_cookie_name = isset($_POST['cookie_name']) ? $_POST['cookie_name'] : "darkmode_" . $username;

// Set the dark mode cookie based on the POST data
if (isset($_POST['darkmode']) && in_array($_POST['darkmode'], ['on', 'off'])) {
    $dark_mode = $_POST['darkmode'];
    // Set cookie for 30 days, user-specific
    setcookie($darkmode_cookie_name, $dark_mode, time() + (30 * 24 * 60 * 60), '/', '', true, true);
    http_response_code(200);
    exit('Success');
}

http_response_code(400);
exit('Invalid request');
?>