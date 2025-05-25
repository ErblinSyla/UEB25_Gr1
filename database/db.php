<?php

$host = '127.0.0.1';
$user = 'root';
$password = '';
$database = 'algoversedb';

$conn = new mysqli($host, $user, $password, $database);

if ($conn -> connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

?>
