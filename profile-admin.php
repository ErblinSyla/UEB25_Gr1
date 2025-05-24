<?php
require_once('database/db.php');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

try {
    $query_reviews = "SELECT * FROM review";
    $stmt_reviews = $conn->prepare($query_reviews);
    if (!$stmt_reviews) {
        throw new Exception("Prepare failed for reviews: " . $conn->error);
    }
    $stmt_reviews->execute();
    $result_reviews = $stmt_reviews->get_result();
    $reviews = $result_reviews->fetch_all(MYSQLI_ASSOC);
    $stmt_reviews->close();

    $query_applications = "SELECT * FROM course_applications";
    $stmt_applications = $conn->prepare($query_applications);
    if (!$stmt_applications) {
        throw new Exception("Prepare failed for course applications: " . $conn->error);
    }
    $stmt_applications->execute();
    $result_applications = $stmt_applications->get_result();
    $applications = $result_applications->fetch_all(MYSQLI_ASSOC);
    $stmt_applications->close();

    $query_newsletter = "SELECT * FROM newsletter";
    $stmt_newsletter = $conn->prepare($query_newsletter);
    if (!$stmt_newsletter) {
        throw new Exception("Prepare failed for newsletter: " . $conn->error);
    }
    $stmt_newsletter->execute();
    $result_newsletter = $stmt_newsletter->get_result();
    $newsletter = $result_newsletter->fetch_all(MYSQLI_ASSOC);
    $stmt_newsletter->close();
} catch (Exception $e) {
    echo "Error fetching data: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>

    <link rel="stylesheet" href="<?php echo htmlspecialchars(getStylesheetPath('profile-admin.css')); ?>">

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
    <nav>
        <div class="row mobile-row">
            <div class="col-4 title">
                <div class="logo">
                    <img id="logo" src="utils/algoverse_logo.svg" alt="AlgoVerse Academy Logo">
                </div>
                <h2>AlgoVerse Academy</h2>
            </div>
            <div class="col-8">
                <ul class="links" id="nav-links">
                    <li><a href="about-us.php">About Us</a></li>
                    <li><a href="courses.php">Our Courses</a></li>
                    <li><a href="admission.php">Admissions</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="logout.php">Log Out</a></li>
                </ul>
                <div class="hamburger" id="hamburger">
                    <p>&#9776;</p>
                </div>
            </div>
        </div>
    </nav>
    <section class="profile-section">
        <div class="row">
            <div class="col-12">
                <img src="images/algoverse-logo.png" alt="Profile Picture" class="profile-picture">
                <h1 class="profile-name"><?php echo (isset($_SESSION['name']) && isset($_SESSION['surname'])) ? htmlspecialchars($_SESSION['name'] . ' ' . $_SESSION['surname']) : 'Admin User'; ?> (Admin)</h1>
                <p class="profile-username"><?php echo isset($_SESSION['username']) ? '@' . htmlspecialchars($_SESSION['username']) : '@admin'; ?></p>
            </div>
        </div>
        <div class="admin-section">
            <h2>Reviews</h2>
            <div class="table-container">
                <?php if ($reviews): ?>
                    <table>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Comment</th>
                            <th>Rating</th>
                            <th>Response</th>
                        </tr>
                        <?php foreach ($reviews as $review): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($review['name']); ?></td>
                                <td><?php echo htmlspecialchars($review['email']); ?></td>
                                <td><?php echo htmlspecialchars($review['phone_number']); ?></td>
                                <td><?php echo htmlspecialchars($review['comment']); ?></td>
                                <td><?php echo htmlspecialchars($review['star']); ?></td>
                                <td><?php echo htmlspecialchars($review['response']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>No reviews found.</p>
                <?php endif; ?>
            </div>

            <h2>Course Applications</h2>
            <div class="table-container">
                <?php if ($applications): ?>
                    <table>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Course</th>
                        </tr>
                        <?php foreach ($applications as $application): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($application['name']); ?></td>
                                <td><?php echo htmlspecialchars($application['email']); ?></td>
                                <td><?php echo htmlspecialchars($application['course']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>No course applications found.</p>
                <?php endif; ?>
            </div>

            <h2>Newsletter Subscriptions</h2>
            <div class="table-container">
                <?php if ($newsletter): ?>
                    <table>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                        </tr>
                        <?php foreach ($newsletter as $subscription): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($subscription['name']); ?></td>
                                <td><?php echo htmlspecialchars($subscription['email']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p>No newsletter subscriptions found.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</body>
</html>