<?php
require_once('database/db.php');

session_start();

require_once 'config.php';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $percentage = $_POST['percentage'];
    $countries = $_POST['countries'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("INSERT INTO demographics (name, percentage, countries, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $name, $percentage, $countries, $comment);

    if ($stmt->execute()) {
        echo "<script>alert('Added row successfully!');
        window.location.href = 'about-us.php';
        </script>";
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a new row - AlgoVerse Academy</title>

    <link rel="stylesheet" href="<?php echo htmlspecialchars(getStylesheetPath('crud.css')); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bona+Nova+SC:ital,wght@0,400;0,700;1,400&family=Rubik:ital,wght@0,300..900;1,300..900&family=Spicy+Rice&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bona+Nova+SC:ital,wght@0,400;0,700;1,400&family=Cinzel:wght@400..900&family=Rubik:ital,wght@0,300..900;1,300..900&family=Spicy+Rice&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bona+Nova+SC:ital,wght@0,400;0,700;1,400&family=Changa:wght@200..800&family=Cinzel:wght@400..900&family=Rubik:ital,wght@0,300..900;1,300..900&family=Spicy+Rice&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <section class="create-section">
        <div class="row">
            <div class="col-12">
                <div class="form-div">
                    <h1>Add a new row</h1>
                    <div class="inner-form">
                        <form method="post">
                            <p>Continent:</p>
                            <input type="text" name="name" placeholder="Continent" required>
                            <p>Percentage of users:</p>
                            <input type="number" name="percentage" placeholder="Percentage" step="0.01" required>
                            <p>Key countries:</p>
                            <input type="text" name="countries" placeholder="Key Countries" required>
                            <p>Comments:</p>
                            <textarea type="text" name="comment" placeholder="Comment" required></textarea>
                            <div class="create-button-div">
                                <button type="submit">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>