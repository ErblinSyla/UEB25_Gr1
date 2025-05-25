<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$currentPage = 'admission';
require('navbar.php');
require_once('database/db.php');
$name = $email = $comment = $phone = "";
$errors = [];

$jsonPath = 'data/admission_form.json';
require_once 'utils/BaseFormData.php';
require 'utils/XSSValidator.php';

require_once 'config.php';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

$submited = false;
class ReviewFormData extends ParentClass
{
    public $phone;
    public $comment;
    public $star = 0;

    function __construct($name, $email, $phone, $comment, $star = 0)
    {
        parent::__construct($name, $email);
        $this->phone = $phone;
        $this->comment = $comment;
        $this->star = $star;
    }

    function display()
    {
        return "Name: " . htmlspecialchars($this->name) .
            "<br>Email: " . htmlspecialchars($this->email) .
            "<br>Phone: " . htmlspecialchars($this->phone) .
            "<br>Comment: " . nl2br(htmlspecialchars($this->comment)) .
            "<br>Star: " . htmlspecialchars($this->star) . "/5";
    }
    public function JSONify()
    {
        return "\n\t{\n" .
            "\t\t\"Name\": \"" . $this->name . "\",\n" .
            "\t\t\"Email\": \"" . $this->email . "\",\n" .
            "\t\t\"Phone\": \"" . $this->phone . "\",\n" .
            "\t\t\"Comment\": \"" . $this->comment . "\",\n" .
            "\t\t\"Star\": " . $this->star .
            "\n\t}";
    }
}

if (isset($_POST['submit']) && $_POST["submit"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $comment = trim($_POST["comment"]);
    $phone = trim($_POST["phone"]);
    $star = $_POST["star"] ?? null;

    if (validateXSSAttacks($name) || validateXSSAttacks($email) || validateXSSAttacks($phone) || validateXSSAttacks($comment)) {
        exit();
    }

    if (!preg_match("/^[a-zA-ZëËçÇáàéèËÏîÎÜüÙùËÄäÖö\s]{2,50}$/", $name)) {
        $errors[] = "Emri nuk është i vlefshëm (vetëm shkronja, 2-50 karaktere).";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email-i nuk është i vlefshëm.";
    }

    if (!preg_match("/^.{5,500}$/s", $comment)) {
        $errors[] = "Komenti duhet të ketë të paktën 5 karaktere.";
    }

    if (!preg_match("/^\+?[0-9]{8,15}$/", $phone)) {
        $errors[] = "Numri i telefonit nuk është i vlefshëm.";
    }

    if ($star == null) {
        $star = 0;
    }
    if (empty($errors)) {
        $review = new ReviewFormData($name, $email, $phone, $comment, $star);

        if (filesize($jsonPath) == 0) {
            $jsonData = "[" . $review->JSONify() . "\n]";
            file_put_contents($jsonPath, $jsonData);
        } else {

            $jsonData = file_get_contents($jsonPath);
            $jsonData = rtrim($jsonData, "]\n") . "\n ," . $review->JSONify() . "\n]";
            file_put_contents($jsonPath, $jsonData);
        }

        
        $stmt = $conn->prepare("INSERT INTO review (name, email, phone_number, comment, star , profile_picture , timestamp , response) VALUES (?, ?, ?, ?, ? , ? , ? , ?)");
        $profile_picture = "images/algoverse-logo.png"; 
        $timestamp = date("Y-m-d H:i:s");
        $response = null; 

        $stmt->bind_param("ssssisss", $name, $email, $phone, $comment, $star, $profile_picture, $timestamp, $response);
        $submited = true;
        if ($stmt->execute()) {
            echo "<script>alert('Review u ruajt me sukses në bazën e të dhënave.');</script>";
        } else {
            echo "<script>alert('Gabim gjatë ruajtjes në databazë: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Gabim në plotësim! Kontrolloni të dhënat.');</script>";
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admissions - AlgoVerse Academy</title>
    
    <link rel="stylesheet" href="style/admission.css">
    <link rel="stylesheet" href="style/navbar.css">

    <link rel="stylesheet" href="<?php echo htmlspecialchars(getStylesheetPath('admission.css')); ?>">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(getStylesheetPath('navbar.css')); ?>">

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
    <style>
        .pay-img:hover {
            box-shadow: 1px 1px 7px #0D92F4;
        }

        .enroll img:hover {
            box-shadow: 1px 1px 7px #0D92F4;
        }

        .join-img:hover {
            box-shadow: 1px 1px 7px #0D92F4;
        }

        nav .title h2 {
            font-family: "Spicy Rice", serif;
        }

        .title-main {
            font-family: "Changa", sans-serif;
            font-size: 29px;
            text-shadow: 1px 1px 4px;
        }

        nav ul li a {
            font-family: "Cinzel", serif;
            font-weight: bold;
        }

        .review-div:hover {
            box-shadow: 1px 1px 7px #0D92F4;
        }

        .form-div:hover {
            box-shadow: 1px 1px 7px #0D92F4;
        }

        .review-img:hover {
            box-shadow: 1px 1px 7px #0D92F4;
        }

        table:hover {
            box-shadow: 1px 1px 7px #0D92F4;
        }

        .wallpaper {
            background-image: url("./images/admissions-wallpaper.jpg");
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <audio id="nav-hover-audio" src="audio/navbar-hover.mp3"></audio>
    <audio id="nav-click-audio" src="audio/shift-page.mp3"></audio>
    <main class="main-section">
        <section class="wallpaper">
            <div class="wallpaper-text">
                <div class="row">
                    <div class="col-6 wall-text">
                        <h1>Discover how to join our academy and start learning programming with expert instructors.</h1>
                    </div>
                    <div class="col-5 wall-button">
                        <p style="font-family: 'MyFontItalic', Arial, Helvetica, sans-serif;">Best choice you can make!</p>
                        <audio id="enroll-click-audio" src="audio/shift-page.mp3"></audio>

                        <a href="#enroll-id"><b>Enroll Now</b></a>
                    </div>
                </div>
            </div>
        </section>
        <section class="why-join-us">
            <div class="row">
                <div class="col-12 reveal">
                    <h2 class="title-main">Why Join Us?</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-4 reveal">
                    <div class="join-col">
                        <div class="join-text">
                            <p>Access to industry-expert instructors</p>
                        </div>
                        <img class="join-img" src="images/instructors.jpg" alt="instructors">
                    </div>
                </div>
                <div class="col-4 reveal">
                    <div class="join-col">
                        <div class="join-text">
                            <p>Hands-on projects for real-world coding experience.</p>
                        </div>
                        <img class="join-img" src="images/coding.jpg" alt="coding">
                    </div>
                </div>
                <div class="col-4 reveal">
                    <div class="join-col">
                        <div class="join-text">
                            <p>Career support, networking, or certification opportunities.</p>
                        </div>
                        <img class="join-img" src="images/certification.avif" alt="certification">
                    </div>
                </div>
            </div>
        </section>
        <audio id="hover-audio" src="audio/div-hover.mp3"></audio>
        <section id="enroll-id" class="enroll">
            <div class="row">
                <div class="col-12 reveal">
                    <h2 class="title-main">Enrollment Process</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-5 reveal-left">
                    <audio id="image-hover-audio" src="audio/div-hover.mp3"></audio>

                    <img class="enroll-img" src="images/enroll.png">
                </div>
                <div class="col-7">
                    <ul class="ul-list" style="list-style-type: disc;">
                        <li class="list reveal-left">Select the desired <mark><a href="courses.php" target="_blank">course</a></mark> you wish to follow,</li>
                        <li class="list reveal-right">Fill out the form with all your details,</li>
                        <li class="list reveal-left">Wait for our response by email or phone number.</li>
                    </ul>
                </div>
            </div>
        </section>
        <?php
        define("MONEDHA", "$");

        $sql = "SELECT id, name, price, features, frequency FROM pricing_plans ORDER BY price ASC";
        $result = $conn->query($sql);

        $plans = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $plans[] = $row;
            }
        }
        $sql = "SELECT r.id, r.comment, r.rating, r.created_at, r.response, u.name, u.profile_picture_path 
                FROM review r 
                JOIN users u ON r.user_id = u.id 
                ORDER BY r.rating DESC";
        $result = $conn->query($sql);

        $reviews = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reviews[] = $row;
            }
        }

    

        function formatedPrice($price)
        {
            return MONEDHA . number_format($price, 2);
        }

        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        ?>

        <section class="pricing-plans">
            <div class="row">
                <div class="col-12 reveal">
                    <h2 class="title-main">Pricing Plans</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="reveal">
                        <thead>
                            <tr>
                                <th>Plan Name</th>
                                <th>Price</th>
                                <th>Features</th>
                                <th>Payment Frequency</th>
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($plans as $plan): ?>
                                <tr>
                                    <td><?= strtoupper($plan["name"]) ?></td>
                                    <td><?= formatedPrice($plan["price"]) ?></td>
                                    <td><?= ucfirst($plan["features"]) ?></td>
                                    <td>
                                        <?php
                                        if ($plan["frequency"] === "One-time") {
                                            echo "<span style='color:green;'>One-time</span>";
                                        } else {
                                            echo "<span style='color:blue;'>{$plan["frequency"]}</span>";
                                        }
                                        ?>
                                    </td>
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                        <td>
                                            <form action="update-plans.php" method="get" style="display:inline;">
                                                <input type="hidden" name="id" value="<?= $plan['id'] ?>">
                                                <button class="table-button" type="submit">
                                                    <img class="update-img" src="images/update.png">
                                                </button>
                                            </form>
                                            <form action="delete-plans.php" method="post" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                                <input type="hidden" name="id" value="<?= $plan['id'] ?>">
                                                <button class="table-button" type="submit">
                                                    <img class="delete-img" src="images/delete.png">
                                                </button>
                                            </form>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <div class="admin-actions reveal-left" style="margin-top: 20px;">
                    <form action="create-plans.php" method="get" style="display: inline;">
                        <button class="table-button-create" type="submit">Create</button>
                    </form>
                </div>
            <?php endif; ?>
            <audio id="td-hover-audio" src="audio/div-hover.mp3"></audio>
        </section>
        <section class="payment">
            <div class="row">
                <div class="col-12 reveal">
                    <h2 class="title-main">Payment Options</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-3 reveal">
                    <div class="payment-col">
                        <div class="join-text">
                            <p>Credit Card</p>
                        </div>
                        <img class="pay-img" src="images/credit-cards.png" alt="credit-cards">
                        <a href="files/credit-card-payment.pdf" download="CreditCardInfo">
                            <button>See More</button>
                        </a>
                    </div>
                </div>
                <div class="col-3 reveal">
                    <div class="payment-col">
                        <div class="join-text">
                            <p>Digital Wallets</p>
                        </div>
                        <img class="pay-img" src="images/digital-wallets.jpg" alt="digital-wallets">
                        <a href="files/digital-wallet-payment.pdf" download="DigitalWalletInfo">
                            <button>See More</button>
                        </a>
                    </div>
                </div>
                <div class="col-3 reveal">
                    <div class="payment-col">
                        <div class="join-text">
                            <p>Cash-On Delivery</p>
                        </div>
                        <img class="pay-img" src="images/cash-on.webp" alt="cash-on">
                        <a href="files/cash-on-delivery-payment.pdf" download="CashOnDeliveryInfo">
                            <button>See More</button>
                        </a>
                    </div>
                </div>
                <div class="col-3 reveal">
                    <div class="payment-col">
                        <div class="join-text">
                            <p>Bank Transfers</p>
                        </div>
                        <img class="pay-img" src="images/bank-transfer.png" alt="bank-transfer">
                        <a href="files/bank-transfers-payment.pdf" download="BankTransferInfo">
                            <button>See More</button>
                        </a>
                    </div>
                </div>
            </div>
            <audio id="hover-audio" src="audio/div-hover.mp3"></audio>
            <audio id="click-audio" src="button-click.mp3"></audio>

        </section>
        <section class="reviews">
            <div class="row">
                <div class="col-12 reveal">
                    <h2 class="title-main">Reviews</h2>
                </div>
            </div>
            <div class="row">
                <?php foreach ($reviews as $review): ?>
                <?php if($review['response'] == null ||  $review['response'] ==''): ?>
                    <div class="col-4">
                        <div class="review-div reveal">
                            <div class="row">
                                <div class="col-3">
                                    <img class="review-img" src="<?= htmlspecialchars($review['profile_picture_path']) ?>">
                                    <p><?= htmlspecialchars($review['name']) ?></p>
                                </div>
                                <div class="col-9">
                                    <div class="rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span data-value="<?= $i ?>"><?= $i <= $review['rating'] ? '&#9733;' : '&#9734;' ?></span>
                                        <?php endfor; ?>
                                    </div>
                                    <p><?= htmlspecialchars($review['comment']) ?></p>
                                </div>
                            </div>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <div class="row">
                                    <div class="col-8">
                                        
                                        <textarea id="myTextarea<?= $review['id'] ?>" name="response" style="border:2px dashed black; border-radius:5px; margin-right:50px; resize: none;" rows="6" cols="38" placeholder="Keep it brief and professional!"></textarea>
                                    </div>
                                    <div class="col-4">
                                        
                                        <form action="admission.php" method="post">
                                            <br>
                                           
                                            <input type="submit" name="submit"
                                                   style="margin-right:18px; margin-bottom:12px;"
                                                   class="table-button-create" value="Submit"
                                                onclick = "document.getElementById('response<?= $review['id']?>').value = document.getElementById('myTextarea<?= $review['id'] ?>').value">
                            
                                           
                                            <input type="button"
                                                   style="margin-right:18px;"
                                                   class="table-button-create" value="Cancel"
                                                   onclick="document.getElementById('myTextarea<?= $review['id'] ?>').value = '';">
                            
                                           
                                            <input type="hidden" name="id" value="<?= $review['id'] ?>">
                                            <input type="hidden" id = "response<?= $review['id']?>" name="response" value="">
                                        </form>
                                    </div> 
                                </div>
                                <?php
                                
                                if (isset($_POST['submit'])) {
                                    $response = $_POST['response'];
                                    $id = $_POST['id'];
                                    $stmt = $conn->prepare("UPDATE review SET response = ? WHERE id = ?");
                                    $stmt->bind_param("si", $response, $id);
                                    if ($stmt->execute()) {
                                    } else {
                                        echo "<script>alert('Error updating response: " . $stmt->error . "');</script>";
                                    }
                                    $stmt->close();
                                }
                                ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php endforeach; ?>

            </div>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'student' && !$submited): ?>
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-div reveal">
                                    <form method="POST" action="admission.php" id="review-form" enctype="multipart/form-data">
                                        <h3>Leave a Review</h3>
                                        <h4>Name:</h4>
                                        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
                                        <h4>Email</h4>
                                        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
                                        <h4>Phone Number:</h4>
                                        <input type="text" name="phone" value="<?= htmlspecialchars($phone ?? '') ?>">
                                        <h4>Comment:</h4>
                                        <textarea id="comment-input" name="comment" style="resize:vertical;max-height: 300px;"><?= htmlspecialchars($comment) ?></textarea>
                                        <h4 class="rating-h4">Rating:</h4>
                                        <div class="clickable-rating">
                                            <span data-value="1">★</span>
                                            <span data-value="2">★</span>
                                            <span data-value="3">★</span>
                                            <span data-value="4">★</span>
                                            <span data-value="5">★</span>
                                            <input type="hidden" name="star" id="starRating" value="">
                                        </div>
                                        <button type="submit" name='submit'>Submit</button>
                                        <?php if (!empty($errors)) : ?>
                                            <div style="color: red; margin-top: 10px;">
                                                <?php foreach ($errors as $error) : ?>
                                                    <div><?= $error ?></div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($successMessage)) : ?>
                                            <div style="color: green; margin-top: 10px;">
                                                <?= $successMessage ?>
                                            </div>
                                        <?php endif; ?>
                                    </form>

                                    <?php
                                    if (!is_dir("output")) mkdir("output", 0777, true);
                                    if (!is_dir("logs")) mkdir("logs", 0777, true);

                                    function errorHandler($errno, $errstr, $errfile, $errline)
                                    {
                                        $logMsg = "[" . date("Y-m-d H:i:s") . "] ERROR: [$errno] $errstr at $errfile:$errline\n";
                                        file_put_contents("logs/error.log", $logMsg, FILE_APPEND);
                                        echo "<p style='color:red;'>Something went wrong. Error is saved to log file.</p>";
                                        return true;
                                    }
                                    set_error_handler("errorHandler");

                                    $errors = [];
                                    $successMessage = '';
                                    $name = $email = $phone = $comment = $star = '';

                                    try {
                                        if ($_SERVER["REQUEST_METHOD"] === "POST") {
                                            $name = htmlspecialchars($_POST["name"] ?? '');
                                            $email = htmlspecialchars($_POST["email"] ?? '');
                                            $phone = htmlspecialchars($_POST["phone"] ?? '');
                                            $comment = htmlspecialchars($_POST["comment"] ?? '');
                                            $star = htmlspecialchars($_POST["star"] ?? '');

                                            if (empty($name) || empty($email) || empty($comment)) {
                                                throw new Exception("Name, Email, and Comment are required!");
                                            }

                                            $line = "Name: $name | Email: $email | Phone: $phone | Rating: $star | Comment: $comment | Time: " . date("Y-m-d H:i:s") . "\n";

                                            $result = file_put_contents("output/reviews.txt", $line, FILE_APPEND);
                                            if ($result === false) {
                                                throw new Exception("Could not write review to file!");
                                            }

                                            $successMessage = "Thank you for your review, $name!";
                                            $name = $email = $phone = $comment = $star = '';
                                        }
                                    } catch (Exception $e) {
                                        file_put_contents("logs/error.log", "[" . date("Y-m-d H:i:s") . "] EXCEPTION: " . $e->getMessage() . "\n", FILE_APPEND);
                                        $errors[] = "Something went wrong. Could not save your review.";
                                    }
                                    ?>


                                    <script>
                                        document.querySelectorAll('.clickable-rating span').forEach(star => {
                                            star.addEventListener('click', function() {
                                                document.querySelectorAll('.clickable-rating span').forEach(s => {
                                                    s.classList.remove('active');
                                                });
                                                const rating = this.getAttribute('data-value');
                                                for (let i = 1; i <= rating; i++) {
                                                    document.querySelector(`.clickable-rating span[data-value="${i}"]`).classList.add('active');
                                                }
                                                document.getElementById('starRating').value = rating;
                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4"></div>
                </div>
            <?php endif; ?>
            <div class="row">
                <?php if ($submited && $review['response'] == null): ?>
                    <div class="col-4"></div>
                    <div class="col-4">
                        <h1 style='text-align: center;'>
                            Review pending response , please be patient!
                        </h1>
                    </div>
                    <div class="col-4"></div>
                <?php endif; ?>
            </div>
            <div class="row">
                <?php if ($submited && $review['response'] != null): ?>
                    <div class="col-4"></div>
                    <div class="col-4">
                        <h1>
                            Comment from admin
                        </h1>
                        <textarea>
                        <?= htmlspecialchars($review['response']); ?>
                    </textarea>
                    </div>
                    <div class="col-4"></div>
                <?php endif; ?>
            </div>
        </section>
        <section class="faq">
            <div class="row">
                <div class="col-12 reveal">
                    <h2 class="title-main">Frequently Asked Questions</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <dl>
                        <dt class="reveal-left">★ What makes Algoverse Academy different from other programming schools?</dt>
                        <dd>Algoverse Academy focuses on hands-on learning with real-world projects and mentorship from experienced developers, ensuring you’re job-ready by the end of the course.</dd>

                        <dt class="reveal-right">★ I’m completely new to coding. Can I still join?</dt>
                        <dd>Absolutely! We have beginner-friendly courses that start with the basics and guide you step-by-step.</dd>

                        <dt class="reveal-left">★ How much time will I need to dedicate each week?</dt>
                        <dd>It depends on the course, but most require 5-10 hours per week. You can learn at your own pace since the lessons are pre-recorded.</dd>

                        <dt class="reveal-right">★ Will I get help if I’m stuck on a project or concept?</dt>
                        <dd>Of course! We offer live Q&A sessions, a community forum, and one-on-one support from our instructors.</dd>

                        <dt class="reveal-left">★ Do I need any special software or tools to start?</dt>
                        <dd>Nope! We’ll guide you through setting up everything you need, and most courses only require a laptop and an internet connection.</dd>
                    </dl>
                </div>
                <div class="col-2"></div>
            </div>
        </section>
    </main>
    <?php
    require('footer.php');
    ?>
    <script>
        window.addEventListener('scroll', reveal);
        window.addEventListener('scroll', revealLeft);
        window.addEventListener('scroll', revealRight);
    </script>
    <script>
        function addHoverAudioEffectToLinks(linkSelector, audioId) {
            const links = document.querySelectorAll(linkSelector);
            const audio = document.getElementById(audioId);

            if (audio) {
                links.forEach(link => {
                    link.addEventListener('mouseenter', () => {
                        audio.currentTime = 0;
                        audio.play().catch(error => {
                            console.error('Audio playback failed:', error);
                        });
                    });
                });
            } else {
                console.error(`Audio element not found: ${audioId}`);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            addHoverAudioEffectToLinks('.links a', 'nav-hover-audio');
        });


        function addClickAudioEffectToLinks(linkSelector, audioId) {
            const links = document.querySelectorAll(linkSelector);
            const audio = document.getElementById(audioId);

            if (audio) {
                links.forEach(link => {
                    link.addEventListener('click', (event) => {
                        event.preventDefault();
                        audio.currentTime = 0;
                        audio.play().catch(error => {
                            console.error('Audio playback failed:', error);
                        });

                        audio.onended = () => {
                            window.location.href = link.href;
                        };
                    });
                });
            } else {
                console.error(`Audio element not found: ${audioId}`);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            addClickAudioEffectToLinks('.links a', 'nav-click-audio');
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const enrollLink = document.querySelector(".wall-button a");
            const enrollAudio = document.getElementById("enroll-click-audio");

            enrollLink.addEventListener("click", function(event) {
                event.preventDefault();

                enrollAudio.currentTime = 0;
                enrollAudio.play().catch(error => {
                    console.error('Audio playback failed:', error);
                });

                enrollAudio.onended = function() {
                    window.location.href = "#enroll-id";
                };
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const joinCols = document.querySelectorAll(".join-col");
            const hoverAudio = document.getElementById("hover-audio");

            joinCols.forEach(function(col) {
                col.addEventListener("mouseenter", function() {
                    hoverAudio.currentTime = 0;
                    hoverAudio.play().catch(error => {
                        console.error('Audio playback failed:', error);
                    });
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const enrollImage = document.querySelector(".enroll-img");
            const hoverAudio = document.getElementById("image-hover-audio");

            enrollImage.addEventListener("mouseenter", function() {
                hoverAudio.currentTime = 0;
                hoverAudio.play().catch(error => {
                    console.error('Audio playback failed:', error);
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tableCells = document.querySelectorAll(".pricing-plans table td");
            const hoverAudio = document.getElementById("td-hover-audio");

            tableCells.forEach(td => {
                td.addEventListener("mouseenter", function() {
                    hoverAudio.currentTime = 0;
                    hoverAudio.play().catch(error => {
                        console.error("Audio playback failed:", error);
                    });
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const paymentImages = document.querySelectorAll(".pay-img");
            const paymentButtons = document.querySelectorAll(".payment-col button");

            const hoverAudio = document.getElementById("hover-audio");
            const clickAudio = document.getElementById("click-audio");

            const playAudio = (audioElement) => {
                audioElement.currentTime = 0;
                audioElement.play().catch(error => {
                    console.error("Audio playback failed:", error);
                });
            };

            paymentImages.forEach(image => {
                image.addEventListener("mouseenter", function() {
                    playAudio(hoverAudio);
                });
            });

            paymentButtons.forEach(button => {
                button.addEventListener("click", function() {
                    playAudio(clickAudio);
                });
            });
        });
    </script>
    <script>
        const currentDate = new Date();
        const currentMonth = currentDate.toLocaleString('default', {
            month: 'long'
        });
        const currentYear = currentDate.getFullYear();
        document.getElementById('year-month').textContent = `${currentMonth} ${currentYear}`;
    </script>
    <script>
        const hamburger = document.getElementById('hamburger');
        const navLinks = document.getElementById('nav-links');

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });


        $(document).ready(function() {
            $('.rating span').on('mouseover', function() {
                const value = $(this).data('value');
                const isHalf = $(this).hasClass('half');
                $(this).siblings().addBack().each(function() {
                    const starValue = $(this).data('value');
                    if (isHalf && starValue === value) {
                        $(this).addClass('hover');
                        return false;
                    }
                    $(this).toggleClass('hover', starValue <= value);
                });
            });

            $('.rating span').on('mouseout', function() {
                $(this).siblings().addBack().removeClass('hover');
            });

            $('.rating span').on('click', function() {
                const value = $(this).data('value');
                const parent = $(this).parent();
                parent.find('span').each(function() {
                    const starValue = $(this).data('value');
                    if ($(this).hasClass('half') && starValue === value) {
                        $(this).addClass('selected');
                        return false;
                    }
                    $(this).toggleClass('selected', starValue <= value);
                });
            });

            $('.clickable-rating').each(function() {
                let isLocked = false;

                const stars = $(this).find('span');

                stars.on('mouseover', function() {
                    if (isLocked) return;

                    const value = $(this).data('value');

                    $(this).siblings().addBack().each(function() {
                        $(this).toggleClass('hover', $(this).data('value') <= value);
                    });
                });

                stars.on('mouseout', function() {
                    if (isLocked) return;

                    stars.removeClass('hover');
                });

                stars.on('click', function() {
                    if (isLocked) return;

                    const value = $(this).data('value');
                    console.log('Rating selected:', value);

                    stars.each(function() {
                        $(this).toggleClass('selected', $(this).data('value') <= value);
                    });

                    isLocked = true;
                    stars.removeClass('hover');
                    console.log('Rating is now locked');
                });
            });
        });

        document.querySelectorAll('dt').forEach(question => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                if (answer.classList.contains('visible')) {
                    answer.classList.remove('visible');
                } else {
                    answer.classList.add('visible');
                }
            });
        });
    </script>
</body>
</html>
