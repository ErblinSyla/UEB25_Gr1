<?php
session_start();
require_once('database/db.php');

?>

<!DOCTYPE html>
<html>

<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - AlgoVerse Academy</title>
    <link rel="stylesheet" href="styles/signup.css">

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
    <section class="sign-up-section">
        <div class="row">
            <div class="col-12">
                <div class="form-div">
                    <h1>Create a Student Account</h1>
                    <div class="inner-form">
                        <form method="POST">
                            <div class="row">
                                <div class="col-5 form-field">
                                    <p>Name:</p>
                                    <input type="text" name="name" required>
                                </div>
                                <div class="col-2"></div>
                                <div class="col-5 form-field">
                                    <p>Surname:</p>
                                    <input type="text" name="surname" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-5 form-field">
                                    <p>Email:</p>
                                    <input type="email" name="email" required>
                                </div>
                                <div class="col-2"></div>
                                <div class="col-5 form-field">
                                    <p>Age:</p>
                                    <input type="number" name="age" min="0" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-5 form-field">
                                    <p>Phone Number:</p>
                                    <input type="tel" name="phone" pattern="[0-9]{10,15}" required>
                                </div>
                                <div class="col-2"></div>
                                <div class="col-5 form-field">
                                    <p>Gender:</p>
                                    <select name="gender" required>
                                        <option value="">--Select Gender--</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <p>Username:</p>
                                    <input type="text" name="username" required>
                                </div>
                                <div class="col-2"></div>
                                <div class="col-5">
                                    <p>Password:</p>
                                    <input type="password" name="password" required>
                                </div>
                            </div>
                            <div class="sign-up-button-div">
                                <button type="submit">Sign Up</button>
                                <h5>Already have an account? Log in <a href="login.php">here</a></h5>
                            </div>
                        </form>
                        <?php
                        if (isset($error)) {
                            echo "<p style='color: red;'>$error</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>