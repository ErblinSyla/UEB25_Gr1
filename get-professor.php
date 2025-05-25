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

$stmt = $conn->prepare("SELECT id, Name, Title, Gender, Biography FROM professors WHERE id = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Database query preparation failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$professor = $result->fetch_assoc();

if ($professor) {
    echo json_encode($professor);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Professor not found']);
}

$stmt->close();
$conn->close();
exit;
?>