<?php
require 'database/db.php';
header('Content-Type: application/json');

$name = $_POST['name'] ?? '';
$title = $_POST['title'] ?? '';
$gender = $_POST['gender'] ?? '';
$biography = $_POST['biography'] ?? '';

if (empty($name) || empty($title) || empty($gender) || empty($biography)) {
    http_response_code(400);
    echo json_encode(['error' => 'All fields are required']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO professors (Name, Title, Gender, Biography) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $title, $gender, $biography);

if ($stmt->execute()) {
    echo json_encode([
        'id' => $conn->insert_id,
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