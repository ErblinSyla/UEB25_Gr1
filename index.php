<?php
require_once 'utils/BaseFormData.php';
require 'utils/XSSValidator.php';

require_once 'config.php';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

class FormData extends ParentClass
{
    public function inherJSONify()
    {
        return $this->JSONify();
    }
}

$jsonPath = 'data/homepage_form.json';

function cleanName($input)
{
    $clean = preg_replace("/[^a-zA-Z\s]/", "", $input);
    return preg_replace("/\s+/", " ", trim($clean));
}

function validateEmail($email)
{
    return preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name-input'] ?? '');
    $email = trim($_POST['email-input'] ?? '');
    $errors = [];

    if (validateXSSAttacks($name) || validateXSSAttacks($email)) {
        exit();
    }

    $name = cleanName($name);

    if (empty($name)) {
        $errors[] = "Name is required";
    } elseif (preg_match("/[0-9]/", $name)) {
        $errors[] = "Name cannot contain numbers";
    }

    if (!validateEmail($email)) {
        $errors[] = "Invalid email format";
    }

    if (empty($errors)) {
        $newsletter = new FormData($name, $email);
        $message = "Thank you for signing up, " . $newsletter->name . "!";

        if (filesize($jsonPath) == 0) {
            $jsonData = "[" . $newsletter->inherJSONify() . "\n]";
            file_put_contents($jsonPath, $jsonData);
        } else {
            $jsonData = file_get_contents($jsonPath);
            $jsonData = rtrim($jsonData, "]\n") . "\n ," . $newsletter->inherJSONify() . "\n]";
            file_put_contents($jsonPath, $jsonData);
        }

        require_once('database/db.php');

        $checkStmt = $conn->prepare("SELECT id FROM newsletter WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $message .= "\nThis email is already subscribed!";
        } else {
            $insertStmt = $conn->prepare("INSERT INTO newsletter (name, email) VALUES (?, ?)");
            $insertStmt->bind_param("ss", $name, $email);

            if ($insertStmt->execute()) {
                $message .= "\nSubscription successful!";

                // Dërgo email me PHPMailer
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'fatosrama.fr@gmail.com';       // Zëvendëso me emailin tënd
                    $mail->Password   = 'nxnj adhd dlhn bijj';    // Zëvendëso me fjalëkalimin e aplikacionit
                    $mail->SMTPSecure = 'tls';
                    $mail->Port       = 587;

                    $mail->setFrom('fatosrama.fr@gmail.com', 'AlgoVerse Academy');
                    $mail->addAddress($email, $name);
                    $mail->isHTML(true);
                    $mail->Subject = 'Welcome to AlgoVerse Academy!';
                   
                    $mail->Body = "<h2>Hello $name, </h2><p>We’re excited to let you know that <strong>registrations for our Summer Courses 2025</strong> are now open! </p>
                    <p>Thank you for subscribing to our newsletter — you’ll be the first to get updates, early access, and exclusive content about all our upcoming sessions.</p>
                    <p>Get ready for a summer full of learning, growth, and fun with us. </p>
                    <p>With warm regards,<br>AlgoVerse Academy </p>";
                    
                    $mail->send();
                    $message .= "\nA welcome email was sent to your address.";
                } catch (Exception $e) {
                    $message .= "\nEmail could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                $message .= "\nDatabase error: " . $insertStmt->error;
            }

            $insertStmt->close();
        }

        $checkStmt->close();
        $conn->close();
    } else {
        $message = implode("\n", $errors);
    }

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo $message;
        exit();
    }
}

$message = "";

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlgoVerse Academy</title>
    
    <link rel="stylesheet" href="style/homepage.css">

    <link rel="stylesheet" href="<?php echo htmlspecialchars(getStylesheetPath('homepage.css')); ?>">

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
        nav .title h2 {
            font-family: "Spicy Rice", serif;
        }

        #instructors-title,
        #hands-title,
        #flexible-title {
            font-family: "Changa", sans-serif;
            font-size: 29px;
            text-shadow: 1px 1px 4px;
        }

        .hands-on-projects h3 {
            font-family: "Changa", sans-serif;
        }

        nav ul li a {
            font-family: "Cinzel", serif;
            font-weight: bold;
        }

        .main-wallpaper {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), url("./images/homepage-wallpaper.jpg") center/cover no-repeat;
        }
    </style>
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
                    <li><a href="signup.php">Sign Up</a></li>
                    <li><a href="login.php">Log In</a></li>
                    
                </ul>
                <div class="hamburger" id="hamburger">
                    <p>&#9776;</p>
                </div>
            </div>
        </div>
    </nav>

    <audio id="nav-hover-audio" src="audio/navbar-hover.mp3"></audio>
    <audio id="nav-click-audio" src="audio/shift-page.mp3"></audio>


    <script>
        function addHoverAudioEffectToLinks(linkSelector, audioId) {
            const links = document.querySelectorAll(linkSelector);
            const audio = document.getElementById(audioId);

            if (audio) {
                Array.from(links).map(link => {
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

    <script src="scripts/homepage.js"></script>
    <main class="main-section">
        <!--                                               MAIN SECTION                                          -->
        <section class="main-wallpaper">
            <div class="header-text">
                <h1>Master Coding Today!</h1>
            </div>
            <div class="main-text">
                <p style="font-family: 'MyFontItalic', Arial, Helvetica, sans-serif;">Join our comprehensive coding courses and take your skills to the next level!</p>
                <p style="font-family: 'MyFontItalic', Arial, Helvetica, sans-serif;" id="tech-text">Earn your spot on the worlds leading big tech companies such as: </p>
                <div id="tech-logo">
                    <img id="apple" src="images/apple.png">
                    <img id="google" src="images/google.png">
                    <img id="microsoft" src="images/microsoft.png">
                    <img id="spotify" src="images/spotify.png">
                    <img id="amazon" src="images/amazon.png">
                </div>
            </div>
            <div class="text-admission">
                <p>What are you waiting for? Sign up for our course today and discover everything you can learn!</p>
                <audio id="admission-click-audio" src="audio/shift-page.mp3"></audio>

                <a href="signup.php" class="cta-button">Sign Up!</a>
            </div>
        </section>
        <script>
            function addClickAudioEffectToLinks(linkSelector, audioId, callback) {
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
                                if (callback) callback();
                                window.location.href = link.href;
                            };
                        });
                    });
                } else {
                    console.error(`Audio element not found: ${audioId}`);
                }
            }

            addClickAudioEffectToLinks('.cta-button', 'admission-click-audio', () => {
                console.log('Audio finished, navigating to the next page');
            });
            document.addEventListener("DOMContentLoaded", () => {
                setTimeout(() => {
                    const section = document.querySelector('.main-wallpaper');
                    section.style.opacity = '1';
                    section.style.transition = 'opacity 1s';
                }, 1000);
            });
        </script>
        <!---                                          EXPERT INSTRUCTORS SECTION                                   -->
        <section class="instructors">
            <h2 id="instructors-title">Expert Instructors</h2>
            <p><strong>Learn from industry professionals with years of experience.</strong></p>
            <div class="instructor-1">
                <!--                             INSTRUCTOR 1                            -->
                <audio id="audio1" src="audio/div-hover.mp3"></audio>
                <img id="instructor1_image" src="images/instructor-1.png">
                <h1 id="instructor1-name">Dhuratë Hyseni</h1>
                <h2 id="instructor1-degree">PhD Proffesor at University of Prishtina</h2>
                <p id="instructor1-description">Dr. Dhuratë Hyseni is not only an accomplished web development expert but also a Professor at the University of Prishtina.
                    With extensive experience in both academic and practical fields, she specializes in front-end technologies like HTML, CSS, and JavaScript,
                    as well as back-end frameworks such as Node.js and Python.
                    As a professor, she has mentored numerous students, helping them master key web development skills, from designing user-friendly interfaces
                    to building dynamic web applications. Under her guidance, students gain a deep understanding of best practices in web development,
                    including optimization, security, and accessibility.</p>
            </div>
            <div class="instructor-2">
                <!--                            INSTRUCTOR 2                            -->
                <audio id="audio2" src="audio/div-hover.mp3"></audio>
                <img id="instructor2_image" src="images/instructor-2.png">
                <h1 id="instructor2-name">Hussein Hussein</h1>
                <h2 id="instructor2-degree">PhD Graduate at Northeastern University</h2>
                <p id="instructor2-description">Dr. Hussein Hussein is an expert instructor with a PhD in Computer Science from Northeastern University, specializing in databases, web servers, and web security. He brings a wealth of knowledge in managing and optimizing databases like MySQL and PostgreSQL, as well as configuring and securing web servers like Apache and Nginx. With a focus on real-world applications, Dr. Hussein teaches students how to design secure, scalable web architectures, ensuring that they are well-equipped to handle web security challenges such as SQL injection and cross-site scripting (XSS). His courses combine in-depth theory with hands-on experience, preparing students for successful careers in backend development and system security.</p>
            </div>
            <div class="instructor-3">
                <!--                             INSTRUCTOR 3                           -->
                <audio id="audio3" src="audio/div-hover.mp3"></audio>
                <img id="instructor3_image" src="images/instructor-3.png">
                <h1 id="instructor3-name">Ethan Williams</h1>
                <h2 id="instructor3-degree">PhD Graduate at University of Bridgeport</h2>
                <p id="instructor3-description">Dr. Ethan Williams is a PhD graduate in Computer Engineering from the University of Bridgeport, specializing in backend development, cybersecurity, and cloud engineering. With over a decade of experience, he has worked on secure, scalable backend systems using languages like Python and Java, while also designing cloud solutions on platforms like AWS and Azure. Passionate about security, Dr. Williams helps organizations safeguard their systems against cyber threats. As an educator, he equips students with the practical skills needed to excel in backend development, cybersecurity, and cloud technologies.</p>
            </div>

        </section>
        <!--                                       HANDS-ON-PROJECTS SECTION                                         -->
        <section class="hands-on-projects">
            <div class="projects-main">
                <h2 id="hands-title">Hands-On Projects</h2>
                <p><strong>Build real-world projects to showcase your skills. Here are a few examples:</strong></p>
                <div class="projects">
                    <div class="calculator-project">
                        <audio id="audio4" src="audio/div-hover.mp3"></audio>
                        <iframe
                            id="calculator-video"
                            src="https://www.youtube.com/embed/HQCLzqhiT2w"
                            title="Calculator Project"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                        <h3>Calculator</h3>
                        <p>Create a functional calculator using HTML, CSS, and JavaScript.</p>
                    </div>
                    <div class="todo-list-project">
                        <audio id="audio5" src="audio/div-hover.mp3"></audio>
                        <iframe
                            id="todo-video"
                            src="https://www.youtube.com/embed/G0jO8kUrg-I"
                            title="To-Do List Project"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                        <h3>To-Do List</h3>
                        <p>Build a dynamic to-do list with local storage support.</p>
                    </div>
                    <div class="weather-project">
                        <audio id="audio1" src="audio/div-hover.mp3"></audio>
                        <iframe
                            id="weather-video"
                            src="https://www.youtube.com/embed/MIYQR-Ybrn4"
                            title="Weather App Project"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                        <h3>Weather App</h3>
                        <p>Fetch and display real-time weather data using APIs.</p>
                    </div>
                </div>
            </div>
        </section>
        <!--                                                 FLEXIBLE LEARNING SECTION                                   -->
        <section class="flexible-learning">
            <div class="flexible-title">
                <h2 id="flexible-title">Flexible Learning</h2>
                <p><strong>Access courses online, anytime, anywhere. Learn at your own pace with:</strong></p>
            </div>
            <div class="flexible-content">

                <ul class="flexible-options">

                    <li id="mobile"><strong>Mobile-Friendly:</strong> Learn on your phone, tablet, or desktop.</li>
                    <li id="download"><strong>Offline Access:</strong> Download course content to study anywhere.</li>
                    <li id="support"><strong>24/7 Support:</strong> Get help whenever you need it.</li>
                </ul>
                <audio id="audio-flexible" src="audio/div-hover.mp3"></audio>
                <img id="flexible-image" src="images/flexible-learning.png" alt="Flexible Learning Image">
                <blockquote id="quote">
                    <p>"AlgoVerse's flexible courses allowed me to learn coding while working full-time!"</p>
                    <cite>— Jane Doe, Former Student</cite>
                </blockquote>
            </div>
            <div class="form-div reveal">
                <form id="newsletterForm" action="" autocomplete="on" method="POST">
                    <h3>Sign Up to our Newsletter!</h3>
                    <h4>Enter your Name:</h4>
                    <input type="text" name="name-input" placeholder="Enter your name" required>
                    <h4>Enter your E-mail:</h4>
                    <input type="email" name="email-input" placeholder="example@mail.com" required>
                    <input type="submit" id="submit-input">
                </form>
            </div>

            <?php
            if (!is_dir("output")) mkdir("output", 0777, true);
            if (!is_dir("logs")) mkdir("logs", 0777, true);

            function errorHandler($errno, $errstr, $errfile, $errline)
            {
                $logMsg = "[" . date("Y-m-d H:i:s") . "] ERROR: [$errno] $errstr at file $errfile in row $errline\n";
                file_put_contents("logs/error.log", $logMsg, FILE_APPEND);
                echo "<p style='color:red;'>Something went wrong. Error is saved to log file.</p>";
                return true;
            }

            set_error_handler("errorHandler");

            try {
                if ($_SERVER["REQUEST_METHOD"] === "POST") {
                    $emri = htmlspecialchars($_POST["name-input"]);
                    $email = htmlspecialchars($_POST["email-input"]);

                    if (empty($emri) || empty($email)) {
                        throw new Exception("All fields are required!");
                    }

                    $rreshti = "Name: $emri - Email: $email - Timestamp: " . date("Y-m-d H:i:s") . "\n";

                    $result = file_put_contents("output/newsletter.txt", $rreshti, FILE_APPEND);
                    if ($result === false) {
                        throw new Exception("Could not save to file!");
                    }

                    echo "<script>alert('Thank you for signing up $emri');</script>";
                }
            } catch (Exception $e) {
                $logMsg = "[" . date("Y-m-d H:i:s") . "] EXCEPTION: " . $e->getMessage() . "\n";
                file_put_contents("logs/error.log", $logMsg, FILE_APPEND);
                echo "<p style='color:red;'>Something went wrong, could not save the info!</p>";
            }
            ?>

        </section>
    </main>
    <script>
        /* document.getElementById('newsletterForm').addEventListener('submit', function(event) {
            const nameInput = document.getElementById('name-input');
            const nameError = document.getElementById('name-error');

            if (isNaN(nameInput.value) === false || nameInput.value.trim() === "") {
                event.preventDefault();
                nameError.style.display = "block"; 
            } else {
                nameError.style.display = "none";
            }
        });
        document.getElementById('newsletterForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const nameInputField = document.getElementById('name-input');
            const emailInputField = document.getElementById('email-input');

            const formFields = [
                nameInputField.value,
                emailInputField.value
            ];

            const filledFields = formFields.filter(field => field.trim() !== "");

            if (filledFields.length === formFields.length) {
                alert("Thank you for signing up to our newsletter!");
            } else {
                alert("Please fill in all the fields.");
            }
        });
*/
        // document.getElementById('newsletterForm').addEventListener('submit', async (e) => {
        //     e.preventDefault();

        //     // Clean name input before submission
        //     const nameInput = document.querySelector('[name="name-input"]');
        //     nameInput.value = nameInput.value.replace(/[^a-zA-Z\s]/g, '')
        //         .replace(/\s+/g, ' ')
        //         .trim();

        //     const response = await fetch('', {
        //         method: 'POST',
        //         body: new FormData(e.target),
        //         headers: {
        //             'X-Requested-With': 'XMLHttpRequest'
        //         }
        //     });

        //     const result = await response.text();
        //     alert(result);

        //     // Clear form if successful
        //     if (!result.includes("Invalid")) {
        //         e.target.reset();
        //     }
        // });




        function addHoverAudioEffect(instructorSelector, audioId) {
            const instructor = document.querySelector(instructorSelector);
            const audio = document.getElementById(audioId);

            if (instructor && audio) {
                instructor.addEventListener('mouseenter', () => {
                    audio.currentTime = 0;
                    audio.play().catch(error => {
                        console.error('Audio playback failed:', error);
                    });
                });
            } else {
                console.error(`Element not found: ${instructorSelector} or ${audioId}`);
            }
        }

        addHoverAudioEffect('.instructor-1', 'audio1');
        addHoverAudioEffect('.instructor-2', 'audio2');
        addHoverAudioEffect('.instructor-3', 'audio3');


        function addHoverAudioEffect(elementSelector, audioId) {
            const element = document.querySelector(elementSelector);
            const audio = document.getElementById(audioId);

            if (element && audio) {
                element.addEventListener('mouseenter', () => {
                    try {
                        audio.currentTime = 0;
                        audio.play();
                    } catch (error) {
                        console.error('Error playing audio:', error);
                    }
                });
            } else {
                console.error(`Element not found: ${elementSelector} or ${audioId}`);
            }
        }


        document.addEventListener('DOMContentLoaded', () => {
            addHoverAudioEffect('.calculator-project', 'audio4');
            addHoverAudioEffect('.todo-list-project', 'audio5');
            addHoverAudioEffect('.weather-project', 'audio1');
        });

        function addHoverAudioEffect(elementSelector, audioId) {
            const element = document.querySelector(elementSelector);
            const audio = document.getElementById(audioId);

            if (element && audio) {
                element.addEventListener('mouseenter', () => {
                    audio.currentTime = 0;
                    audio.play().catch(error => {
                        console.error('Audio playback failed:', error);
                    });
                });
            } else {
                console.error(`Element not found: ${elementSelector} or ${audioId}`);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            addHoverAudioEffect('#flexible-image', 'audio-flexible');
        });

        document.getElementById('newsletterForm').addEventListener('submit', function(event) {
            const nameInput = document.getElementById('name-input');
            nameInput.value = nameInput.value.replace(/\s+/g, ' ').trim();
        });
    </script>
    <!--                                                    COPYRIGHT                                                -->
    <!-- <footer>
        <div class="row">
            <div class="col-12">
                <p>&copy; <span><?php echo date("Y-m-d"); ?></span> <em>AlgoVerse Academy. All rights reserved.</em></p>
            </div>
        </div>
    </footer> -->
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