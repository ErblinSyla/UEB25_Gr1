<?php
require 'database/db.php';
header('Content-Type: application/json');

$id = $_POST['id'] ?? '';
$name = $_POST['name'] ?? '';
$title = $_POST['title'] ?? '';
$gender = $_POST['gender'] ?? '';
$biography = $_POST['biography'] ?? '';

if (empty($id) || empty($name) || empty($title) || empty($gender) || empty($biography)) {
    http_response_code(400);
    echo json_encode(['error' => 'All fields are required']);
    exit;
}

$stmt = $conn->prepare("UPDATE professors SET Name = ?, Title = ?, Gender = ?, Biography = ? WHERE id = ?");
$stmt->bind_param("ssssi", $name, $title, $gender, $biography, $id);

if ($stmt->execute()) {
    echo json_encode([
        'id' => $id,
        'Name' => $name,
        'Title' => $title,
        'Gender' => $gender,
        'Biography' => $biography
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}

$stmt->close();
$conn->close();
?>