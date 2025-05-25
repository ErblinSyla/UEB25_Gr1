<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dyslexia = isset($_POST['dyslexia']) ? $_POST['dyslexia'] : 'off';
    $cookie_name = isset($_POST['cookie_name']) ? $_POST['cookie_name'] : '';

    if ($cookie_name) {
        setcookie($cookie_name, $dyslexia, time() + (86400 * 30), "/"); // 30-day cookie
        echo "Dyslexia cookie set successfully";
    } else {
        http_response_code(400);
        echo "Invalid cookie name";
    }
} else {
    http_response_code(405);
    echo "Method not allowed";
}
?>