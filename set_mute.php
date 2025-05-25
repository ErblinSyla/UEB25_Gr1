<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mute = isset($_POST['mute']) ? $_POST['mute'] : 'off';
    $cookie_name = isset($_POST['cookie_name']) ? $_POST['cookie_name'] : '';

    if ($cookie_name) {
        setcookie($cookie_name, $mute, time() + (86400 * 30), "/"); // 30-day cookie
        echo "Mute cookie set successfully";
    } else {
        http_response_code(400);
        echo "Invalid cookie name";
    }
} else {
    http_response_code(405);
    echo "Method not allowed";
}
?>