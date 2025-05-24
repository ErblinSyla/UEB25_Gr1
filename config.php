<?php

// Get the user's dark mode preference from the cookie
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$darkmode_cookie_name = $username ? "darkmode_" . $username : null;
$dark_mode = $username && isset($_COOKIE[$darkmode_cookie_name]) && $_COOKIE[$darkmode_cookie_name] === 'on' ? 'on' : 'off';

// Determine the base stylesheet path
$theme_base = $dark_mode === 'on' ? 'style_dark' : 'style_light';

// Function to get the full stylesheet path
function getStylesheetPath($file) {
    global $theme_base;
    return $theme_base . '/' . $file;
}
?>