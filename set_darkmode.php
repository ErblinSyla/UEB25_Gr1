<?php
session_start();


if (!isset($_SESSION['username'])) {
    http_response_code(403);
    exit('Unauthorized');
}

$username = $_SESSION['username'];
$darkmode_cookie_name = isset($_POST['cookie_name']) ? $_POST['cookie_name'] : "darkmode_" . $username;


if (isset($_POST['darkmode']) && in_array($_POST['darkmode'], ['on', 'off'])) {
    $dark_mode = $_POST['darkmode'];
    setcookie($darkmode_cookie_name, $dark_mode, time() + (30 * 24 * 60 * 60), '/', '', true, true);
    http_response_code(200);
    exit('Success');
}

http_response_code(400);
exit('Invalid request');
?>