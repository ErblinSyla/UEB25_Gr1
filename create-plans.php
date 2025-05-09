<?php
require_once('database/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $features = $_POST['features'];
    $frequency = $_POST['frequency'];

    $stmt = $conn->prepare("INSERT INTO pricing_plans (name, price, features, frequency) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $name, $price, $features, $frequency);

    if ($stmt->execute()) {
        echo "<script>alert('Added row successfully!');
        window.location.href = 'admission.php';
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
    <link rel="stylesheet" href="styles/crud.css">

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
                        <form method="post" action="">
                            <p>Plan Name:</p>
                            <input type="text" name="name" placeholder="Plan Name" required>
                            <p>Price:</p>
                            <input type="number" name="price" placeholder="Price" required>
                            <p>Features:</p>
                            <input type="text" name="features" placeholder="Features" required>
                            <p>Payment frequency:</p>
                            <select name="frequency" required>
                                <option value="One-time">One-time</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Yearly">Yearly</option>
                            </select>
                            <div class="create-button-div">
                                <button type="submit">Create Plan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>