<?php
require 'database/db.php';
header('Content-Type: application/json');

$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}


$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';


if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'Photo upload failed']);
    exit;
}


$allowedMime = ['image/png', 'image/jpeg', 'image/gif'];
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($_FILES['photo']['tmp_name']);

if (!in_array($mime, $allowedMime, true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid image type']);
    exit;
}


$ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
$basename = bin2hex(random_bytes(8)); 
$filename = sprintf('%s.%s', $basename, $ext);
$targetPath = $uploadDir . $filename;


if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save uploaded file']);
    exit;
}


$photoPathForDb = 'uploads/' . $filename;


if (empty($name) || empty($description)) {
    http_response_code(400);
    echo json_encode(['error' => 'Name and description are required']);
    exit;
}


$stmt = $conn->prepare(
    "INSERT INTO courses (Name, Photo, Description) VALUES (?, ?, ?)"
);
$stmt->bind_param("sss", $name, $photoPathForDb, $description);

if ($stmt->execute()) {
    echo json_encode([
        'id' => $conn->insert_id,
        'Name' => $name,
        'Photo' => $photoPathForDb,
        'Description' => $description
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}

$stmt->close();
$conn->close();
?>
