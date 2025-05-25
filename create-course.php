<?php
require 'database/db.php';
header('Content-Type: application/json');

$name = $_POST['name'] ?? '';
$photo = $_POST['photo'] ?? '';
$description = $_POST['description'] ?? '';

if (empty($name) || empty($photo) || empty($description)) {
    http_response_code(400);
    echo json_encode(['error' => 'All fields are required']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO courses (Name, Photo, Description) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $photo, $description);

if ($stmt->execute()) {
    echo json_encode([
        'id' => $conn->insert_id,
        'Name' => $name,
        'Photo' => $photo,
        'Description' => $description
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}

$stmt->close();
$conn->close();
?>