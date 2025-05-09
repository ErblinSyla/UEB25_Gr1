<?php
require_once('database/db.php');

$id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id) {
    echo "Missing plan ID.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $features = $_POST['features'];
    $frequency = $_POST['frequency'];

    $stmt = $conn->prepare("UPDATE pricing_plans SET name=?, price=?, features=?, frequency=? WHERE id=?");
    $stmt->bind_param("sdssi", $name, $price, $features, $frequency, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Updated row successfully!');
        window.location.href = 'admission.php';
        </script>";
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

$result = $conn->query("SELECT * FROM pricing_plans WHERE id=$id");
$plan = $result->fetch_assoc();
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
                    <h1>Update row</h1>
                    <div class="inner-form">
                        <form method="post">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <p>Plan name:</p>
                            <input type="text" name="name" value="<?= $plan['name'] ?>" required>
                            <p>Price:</p>
                            <input type="number" name="price" value="<?= $plan['price'] ?>" required>
                            <p>Features:</p>
                            <input type="text" name="features" value="<?= $plan['features'] ?>" required>
                            <p>Payment frequency:</p>
                            <select name="frequency" required>
                                <option value="One-time" <?= $plan['frequency'] === 'One-time' ? 'selected' : '' ?>>One-time</option>
                                <option value="Monthly" <?= $plan['frequency'] === 'Monthly' ? 'selected' : '' ?>>Monthly</option>
                                <option value="Yearly" <?= $plan['frequency'] === 'Yearly' ? 'selected' : '' ?>>Yearly</option>
                            </select>
                            <div class="create-button-div">
                                <button type="submit">Update Plan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>