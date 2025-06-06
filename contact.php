<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$currentPage = 'contact';
require('navbar.php');
require('database/db.php');

$jsonPath = 'data/contact_form.json';
require_once 'utils/BaseFormData.php';
require 'utils/XSSValidator.php';

require_once 'config.php';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

class ContactFormData extends ParentClass
{
    public $gender;
    public $age;
    public $interests = [];
    public $message;

    public function __construct($name, $email, $gender, $age, $interests = [], $message = '')
    {
        parent::__construct($name, $email);
        $this->gender = $gender;
        $this->age = $age;
        $this->interests = $interests;
        $this->message = $message;
    }

    private function createJSONArr()
    {
        $formattedArray = "";
        foreach ($this->interests as $interest) {
            $formattedArray .= "\"$interest\"";
            if ($interest !== end($this->interests)) {
                $formattedArray .= ",";
            }
        }
        return $formattedArray;
    }

    public function JSONify()
    {
        return "\n\t{\n" .
            "\t\t\"Name\": \"" . $this->name . "\",\n" .
            "\t\t\"Age\": \"" . $this->age . "\",\n" .
            "\t\t\"Gender\": \"" . $this->gender . "\",\n" .
            "\t\t\"Email\": \"" . $this->email . "\",\n" .
            "\t\t\"Interests\": " . "[" . $this->createJSONArr() . "]" . ",\n" .
            "\t\t\"Message\": \"" . $this->message . "\"" .
            "\n\t}";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    function showAlertAndGoBack($message)
    {
        echo "<script>alert('" . addslashes($message) . "'); window.history.back();</script>";
        exit();
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $age = $_POST['age'] ?? 0;
    $interests = $_POST['interests'] ?? [];
    $message = trim($_POST['message'] ?? '');

    if (validateXSSAttacks($name) || validateXSSAttacks($email) || validateXSSAttacks($age) || validateXSSAttacks($message)) {
        exit();
    }

    if (!preg_match('/^[a-zA-Z\s\-\.\'çÇëË]{2,50}$/', $name)) {
        showAlertAndGoBack("Invalid name format. Only letters, spaces, and basic punctuation allowed.");
    }

    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        showAlertAndGoBack("Invalid email format.");
    }

    if (!preg_match('/^(1[0-1][0-9]|120|[1-9][0-9]|[1-9])$/', $age)) {
        showAlertAndGoBack("Age must be between 1 and 120.");
    }

    if (!in_array($gender, ['male', 'female'])) {
        showAlertAndGoBack("Invalid gender selection.");
    }

    if (empty($interests)) {
        showAlertAndGoBack("Please select at least one interest.");
    }

    foreach ($interests as $interest) {
        if (!in_array($interest, ['Front End', 'Back End', 'Full Stack'])) {
            showAlertAndGoBack("Invalid interest selected.");
        }
    }

    if (strlen($message) < 10 || strlen($message) > 1000) {
        showAlertAndGoBack("Message must be between 10 and 1000 characters.");
    }

    if (preg_match('/[<>{}]/', $message)) {
        showAlertAndGoBack("Message contains invalid characters.");
    }

    if (isset($_POST['phone']) && !empty($_POST['phone'])) {
        $phone = preg_replace('/[^0-9]/', '', $_POST['phone']);
        if (strlen($phone) == 10) {
            $phone = preg_replace('/(\d{3})(\d{3})(\d{4})/', '($1) $2-$3', $phone);
        }
    }

    $name = preg_replace_callback('/\b(\w)/', function ($matches) {
        return strtoupper($matches[1]);
    }, strtolower($name));

    $formData = new ContactFormData($name, $email, $gender, $age, $interests, $message);

    if (filesize($jsonPath) == 0) {
        $jsonData = "[" . $formData->JSONify() . "\n]";
        file_put_contents($jsonPath, $jsonData);
    } else {
        $jsonData = file_get_contents($jsonPath);
        $jsonData = rtrim($jsonData, "]\n") . "\n ," . $formData->JSONify() . "\n]";
        file_put_contents($jsonPath, $jsonData);
    }

    $formattedInterests = implode(", ", $interests);
    try {
        $line = "Name: $name | Email: $email | Gender: $gender | Age: $age | Interests: $formattedInterests | Message: $message";
        if ($phone) {
            $line .= " | Phone: $phone";
        }
        $line .= PHP_EOL;

        file_put_contents(__DIR__ . '/output/contact_us.txt', $line, FILE_APPEND | LOCK_EX);
    } catch (Exception $e) {
        showAlertAndGoBack("Failed to save form data. Please try again.");
    }
    $conn = new mysqli("127.0.0.1", "root", "", "algoversedb");

    if ($conn->connect_error) {
        die("Lidhja me databazën dështoi: " . $conn->connect_error);
    }

    require_once('database/db.php');
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $age = isset($_POST['age']) ? intval($_POST['age']) : 0;
    $message = $_POST['message'] ?? '';
    $interests = isset($_POST['interests']) ? implode(", ", $_POST['interests']) : '';

    $stmt = $conn->prepare("INSERT INTO contact_us (name, email, gender, age, interests, message) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $name, $email, $gender, $age, $interests, $message);

    $stmt->execute(); 

    $stmt->close();
    $conn->close();

    echo '<script>window.location.href = "thankyou.html";</script>';
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contact Us - AlgoVerse Academy</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="style/contact.css">
    <link rel="stylesheet" href="style/navbar.css">

    <link rel="stylesheet" href="<?php echo htmlspecialchars(getStylesheetPath('contact.css')); ?>">
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
    <script>
        window.addEventListener('scroll', reveal);
        window.addEventListener('scroll', revealLeft);
        window.addEventListener('scroll', revealRight);
    </script>
    <meta name="viewport" content="widht=device-width, initial-scale=1.0">
    <style>
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
    </style>
</head>
<body>
 
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const currentPage = window.location.href;
            const navLinks = document.querySelectorAll('.links a');
            navLinks.forEach(link => {
                if (currentPage.match(link.href)) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    <audio id="nav-hover-audio" src="audio/navbar-hover.mp3"></audio>
    <audio id="nav-click-audio" src="audio/shift-page.mp3"></audio>
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
    <section class="contact">
        <div class="content">
            <h2>Contact Us</h2>
            <p>All information about us are displayed down below</p>

        </div>
        <div class="container">
            <div class="contactInfo">
                <div class="box">
                    <div class="icon"><i class="fa fa-location-arrow" aria-hidden="true"></i></div>
                    <div class="text">

                        <h3>Address</h3>
                        <address>
                            "Luan Haradinaj" St.,<br>
                            Nr. 11,<br>
                            10000 Prishtinë, Kosovë<br><br><br>
                        </address>
                        <div id="google-map">
                            <div id="google-maps-canvas" style="height:100%; width:100%;max-width:100%;">
                                <iframe style="height:100%;width:100%;border:0;" frameborder="0" src="https://www.google.com/maps/embed/v1/place?q=11+Luan+Haradinaj,+Prishtinë+10000&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box">
                    <div class="icon"><i class="fa fa-phone" aria-hidden="true"></i></div>
                    <div class="text">
                        <h3>Phone</h3>
                        <a href="tel:+38349111222">+383 49 111 222</a>
                    </div>
                </div>
                <div class="box">
                    <div class="icon"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                    <div class="text">
                        <h3>Email</h3>
                        <p><a href="mailto:algoverse-academy@gmail.com">algoverse-academy@gmail.com</a></p>
                    </div>
                </div>
            </div>
            <div class="contactForm">
                <form autocomplete="on" id="contact-form" action="" method="POST">
                    <h2>Send Message</h2>
                    <div class="inputBox">
                        <input type="text" name="name" placeholder="Full Name" required="required" autocomplete="name">
                        <span id="name-error" class="error-message"></span>
                    </div>
                    <div class="inputBox">
                        <input type="text" name="email" placeholder="Email" required="required" autocomplete="email">
                        <span id="email-error" class="error-message"></span>
                    </div>
                    <div class="inputBox">
                        <input type="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" name="phone" placeholder="Phone Number" required="required" autocomplete="phone">
                        <span id="phone-error" class="error-message"></span>
                    </div>
                    <div class="inputBox">
                        <div class="gender-group">
                            <label for="gender">Gender:</label>
                            <input type="radio" id="male" name="gender" value="male" required>
                            <label for="male">M</label>
                            <input type="radio" id="female" name="gender" value="female">
                            <label for="female">F</label>
                        </div>
                        <span id="gender-error" class="error-message"></span>
                    </div>
                    <div class="inputBox">
                        <label for="age">Age:</label>
                        <input type="number" id="age" name="age" min="1" max="120" required>
                        <span id="age-error" class="error-message"></span>
                    </div>
                    <div class="inputBox">
                        <label for="interests">Your Interests:</label>
                        <div class="checkbox-group">
                            <label>
                                <input type="checkbox" id="Front End" name="interests[]" value="Front End">
                                Front End
                            </label>
                            <label>
                                <input type="checkbox" id="Back End" name="interests[]" value="Back End">
                                Back End
                            </label>
                            <label>
                                <input type="checkbox" id="Full Stack" name="interests[]" value="Full Stack">
                                Full Stack
                            </label>
                        </div>
                        <span id="interests-error" class="error-message"></span>
                    </div>

                    <div class="inputBox">
                        <textarea required="required" placeholder="Write a Message..." id="message" name="message"></textarea>
                        <span id="
                        message-error" class="error-message"></span>
                    </div>
                    <div class="inputBox">
                        <input type="submit" value="Send">
                    </div>
                </form>

            </div>
        </div>
        <script>
            document.getElementById('contact-form').addEventListener('submit', function(event) {
                const nameInput = document.querySelector('input[name="name"]');
                const emailInput = document.querySelector('input[name="email"]');
                const ageInput = document.getElementById('age');
                const genderInputs = document.querySelectorAll('input[name="gender"]');
                const interestsInputs = document.querySelectorAll('input[name="interests[]"]');
                const nameError = document.getElementById('name-error');
                const emailError = document.getElementById('email-error');
                const genderError = document.getElementById('gender-error');
                const ageError = document.getElementById('age-error');
                const interestsError = document.getElementById('interests-error');
                const messageError = document.getElementById('message-error');

                let valid = true;

                if (isNaN(nameInput.value) === false || nameInput.value.trim() === "") {
                    event.preventDefault();
                    nameError.textContent = "Please enter a valid name (no numbers allowed).";
                    nameError.style.display = "block";
                    valid = false;
                } else {
                    nameError.style.display = "none";
                }

                const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
                if (!emailInput.value.match(emailPattern)) {
                    event.preventDefault();
                    emailError.textContent = "Please enter a valid email address.";
                    emailError.style.display = "block";
                    valid = false;
                } else {
                    emailError.style.display = "none";
                }

                let genderSelected = false;
                genderInputs.forEach(gender => {
                    if (gender.checked) genderSelected = true;
                });
                if (!genderSelected) {
                    event.preventDefault();
                    genderError.textContent = "Please select your gender.";
                    genderError.style.display = "block";
                    valid = false;
                } else {
                    genderError.style.display = "none";
                }

                if (ageInput.value < 1 || ageInput.value > 120 || isNaN(ageInput.value)) {
                    event.preventDefault();
                    ageError.textContent = "Please enter a valid age (between 1 and 120).";
                    ageError.style.display = "block";
                    valid = false;
                } else {
                    ageError.style.display = "none";
                }

                let interestsSelected = false;
                interestsInputs.forEach(input => {
                    if (input.checked) interestsSelected = true;
                });
                if (!interestsSelected) {
                    event.preventDefault();
                    interestsError.textContent = "Please select at least one area of interest.";
                    interestsError.style.display = "block";
                    valid = false;
                } else {
                    interestsError.style.display = "none";
                }

                const messageInput = document.querySelector('textarea');
                if (messageInput.value.trim() === "") {
                    event.preventDefault();
                    messageError.textContent = "Please enter a message.";
                    messageError.style.display = "block";
                    valid = false;
                } else {
                    messageError.style.display = "none";
                }

                return valid;
            });
        </script>

    </section>
  
    <?php
    require('footer.php');
    ?>

    <script>
        const currentDate = new Date();

        const currentMonth = currentDate.toLocaleString('default', {
            month: 'long'
        });
        const currentYear = currentDate.getFullYear();

        document.getElementById('year-month').textContent = `${currentMonth} ${currentYear}`;
    </script>
</body>
</html>
