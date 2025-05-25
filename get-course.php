<?php

ob_start();
require 'database/db.php';
header('Content-Type: application/json; charset=UTF-8');


ob_clean();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing ID']);
    exit;
}

$stmt = $conn->prepare("SELECT id, Name, Photo, Description FROM courses WHERE id = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Database query preparation failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if ($course) {
    echo json_encode($course);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Course not found']);
}

$stmt->close();
$conn->close();
exit;
?>