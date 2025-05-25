<?php
require 'database/db.php';
header('Content-Type: application/json');

$id = $_POST['id'] ?? '';
$name = $_POST['name'] ?? '';
$photo = $_POST['photo'] ?? '';
$description = $_POST['description'] ?? '';

if (empty($id) || empty($name) || empty($photo) || empty($description)) {
    http_response_code(400);
    echo json_encode(['error' => 'All fields are required']);
    exit;
}

$stmt = $conn->prepare("UPDATE courses SET Name = ?, Photo = ?, Description = ? WHERE id = ?");
$stmt->bind_param("sssi", $name, $photo, $description, $id);

if ($stmt->execute()) {
    echo json_encode([
        'id' => $id,
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