<?php
require_once('database/db.php');

if (!isset($_GET['id']) && !isset($_POST['id'])) {
    die('Missing ID.');
}

$id = $_GET['id'] ?? $_POST['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $percentage = $_POST['percentage'];
    $countries = $_POST['countries'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("UPDATE demographics SET name=?, percentage=?, countries=?, comment=? WHERE id=?");
    $stmt->bind_param("sdssi", $name, $percentage, $countries, $comment, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Updated row successfully!');
        window.location.href = 'about-us.php';
        </script>";
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    $result = $conn->query("SELECT * FROM demographics WHERE id = $id");
    $row = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a new row - AlgoVerse Academy</title>
    <link rel="stylesheet" href="style_light/crud.css">

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
                    <h1>Update row</h1>
                    <div class="inner-form">
                        <form method="post">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <p>Continent:</p>
                            <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
                            <p>Percentage of users:</p>
                            <input type="number" step="0.01" name="percentage" value="<?= $row['percentage'] ?>" required>
                            <p>Key countries:</p>
                            <input type="text" name="countries" value="<?= htmlspecialchars($row['countries']) ?>" required>
                            <p>Comments:</p>
                            <textarea name="comment" rows="4" cols="50" required><?= htmlspecialchars($row['comment']) ?></textarea>
                            <div class="create-button-div">
                                <button type="submit">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>