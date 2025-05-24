<?php
$currentPage = 'profile';
require_once('database/db.php');

require 'navbar.php';
session_start();

$user_name = isset($_SESSION['name']) ? $_SESSION['name'] : null;


//if (!$user_name) {
//   header("Location: login.php");
//    exit();
//}

try {
    $query = "
        SELECT DISTINCT c.Name, c.Description
        FROM courses c 
        JOIN course_applications ca 
        ON c.Name = ca.course
        WHERE ca.name = ?
    ";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $courses = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
} catch (Exception $e) {
    echo "Error fetching courses: " . $e->getMessage();
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="style_light/profile.css">
    <link rel="stylesheet" href="style_light/navbar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bona+Nova+SC:ital,wght@0,400;0,700;1,400&family=Rubik:ital,wght@0,300..900;1,300..900&family=Spicy+Rice&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bona+Nova+SC:ital,wght@0,400;0,700;1,400&family=Cinzel:wght@400..900&family=Rubik:ital,wght@0,300..900;1,300..900&family=Spicy+Rice&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bona+Nova+SC:ital,wght@0,400;0,700;1,400&family=Changa:wght@200..800&family=Cinzel:wght@400..900&family=Rubik:ital,wght@0,300..900;1,300..900&family=Spicy+Rice&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <script src="scripts/javascript.js"></script>


</head>
<body>

    <section class="profile-section">
        <div class="row">
            <div class="col-12">
                <img src="images/algoverse-logo.png" alt="Profile Picture" class="profile-picture">
                <h1 class="profile-name"><?php echo (isset($_SESSION['name']) && isset($_SESSION['surname'])) ? htmlspecialchars($_SESSION['name'] . ' ' . $_SESSION['surname']) : 'Guest User'; ?></h1>
                <p class="profile-username"><?php echo isset($_SESSION['username']) ? '@' . htmlspecialchars($_SESSION['username']) : '@guest'; ?></p>
            </div>
        </div>
        <div class="courses-section">
    <h2>Enrolled Courses</h2>
    <div class="row">
        <?php if ($courses): ?>
            <?php foreach ($courses as $course): ?>
                <div class="col-4">
                    <div class="course-card">
                        <h3><?php echo htmlspecialchars($course['Name']); ?></h3>
                        <p><?php echo htmlspecialchars($course['Description']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p>No courses enrolled or user not found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
    </section>
</body>
</html>