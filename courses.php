<?php
require 'database/db.php';

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$currentPage = 'contact';
require 'navbar.php';
$jsonPath = 'data/courses_form.json';

require_once 'utils/BaseFormData.php';
require 'utils/XSSValidator.php';

require_once 'config.php';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

class ApplyCourses extends ParentClass
{
    private $password;
    public $course;
    public $file;

    public function __construct($name, $email, $course, $file)
    {
        parent::__construct($name, $email);
        $this->course = $course;
        $this->file = $file;
    }

    // public function NameInEmail() {
    //     $NAME  = strtolower($this->name);
    //     $EMAIL = strtolower($this->email);

    //     if (strpos($EMAIL, $NAME) !== false) {
    //         echo "Name found in email!";
    //     } else {
    //         echo "Name not found in Email!";
    //     }
    // }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function JSONify()
    {
        return json_encode($this);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $targetFilePath = "uploads/default.svg";
    $fileData = null;
    $fileName = '';
    $fileType = '';

    // Validate file upload
    if (!empty($_FILES['file-upload']['name']) && $_FILES['file-upload']['error'] == UPLOAD_ERR_OK) {
        $fileName = basename($_FILES["file-upload"]["name"]);
        $targetFilePath = "uploads/" . $fileName;
        $fileType = $_FILES["file-upload"]["type"];

        // Validate file
        if (file_exists($targetFilePath)) {
            die("Sorry, file already exists.");
        }
        
        // Move uploaded file
        if (move_uploaded_file($_FILES["file-upload"]["tmp_name"], $targetFilePath)) {
            $fileData = file_get_contents($targetFilePath);
            echo "The file " . htmlspecialchars($fileName) . " has been uploaded.";
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    }

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $course = $_POST['course'] ?? '';

    if (validateXSSAttacks($name) || validateXSSAttacks($email) || validateXSSAttacks($password)) {
        exit();
    }

    $applyCourses = new ApplyCourses($name, $email, $course, $targetFilePath);
    $applyCourses->setPassword($password);

    if (filesize($jsonPath) == 0) {
        $jsonData = "[" . $applyCourses->JSONify() . "\n]";
        file_put_contents($jsonPath, $jsonData);
    } else {
        //if file is not empty execute this code
        $jsonData = file_get_contents($jsonPath);
        $jsonData = rtrim($jsonData, "]\n") . "\n ," . $applyCourses->JSONify() . "\n]";
        file_put_contents($jsonPath, $jsonData);
    }
   
    require_once('database/db.php');
    
    $stmt = $conn->prepare("INSERT INTO course_applications 
        (name, email, password, course, file_name, file_type, file_data) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    // Use empty blob if no file uploaded
    if ($fileData === null) {
        $fileData = file_get_contents("uploads/default.svg");
        $fileName = "default.svg";
        $fileType = "image/svg+xml";
    }

    $stmt->bind_param("sssssss", $name, $email, $password, $course, $fileName, $fileType, $fileData);

    if ($stmt->execute()) {
        echo "Application submitted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    // $conn->close(); e kam shkru qeto ne rreshtin 342
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Courses and Professors</title>

    <link rel="stylesheet" href="<?php echo htmlspecialchars(getStylesheetPath('courses.css')); ?>">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(getStylesheetPath('navbar.css')); ?>">  

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="scripts/courses.js"></script>
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

    <!-- <nav>

    <div class="row mobile-row">
      <div class="col-4 title">
        <div class="logo">
          <img id="logo" src="utils/algoverse_logo.svg" alt="AlgoVerse Academy Logo">

        </div>
        <h2>AlgoVerse Academy</h2>
      </div>
      <div class="col-8">
        <ul class="links" id="nav-links">
          <li><a href="index.php">Homepage</a></li>
          <li><a href="about-us.php">About Us</a></li>
          <li><a id="courses" href="courses.php">Our Courses</a></li>
          <li><a href="admission.php">Admissions</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
        <div class="hamburger" id="hamburger">
          <p>&#9776;</p>
        </div>
      </div>
    </div>
  </nav> -->
    <audio id="nav-hover-audio" src="audio/navbar-hover.mp3"></audio>
    <audio id="nav-click-audio" src="audio/shift-page.mp3"></audio>
    <section class="courses">
        <h2>Our Courses</h2>
        <?php

        $query = "SELECT Name , Photo , Description FROM courses";
        $result = $conn->query($query);

        $courseData = [];
        while ($row = $result->fetch_assoc()) { // Equivalent to mysql_fetch_assoc()
            $courseData[] = $row;
        }

        // Separate array for titles
        $courseTitles = [];
        foreach ($courseData as $key => $course) {
            $courseTitles[$key] = $course['Name'];
        }

        // Sort by title
        asort($courseTitles);
        ?>

        <div class="row-other">
            <?php foreach ($courseTitles as $key => $title): ?>
                <?php $course = $courseData[$key]; ?>
                <div class="col">
                    <div class="card">
                        <img src="<?= $course['Photo'] ?>" alt="<?= $course['Name'] ?>" />
                        <h3><?= $course['Name'] ?></h3>
                        <p><?= $course['Description'] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <audio id="card-hover-audio" src="audio/div-hover.mp3"></audio>
        <div class="apply-container reveal">
            <audio id="button-click-audio" src="audio/button-click.mp3"></audio>
            <a href="#top"><button id="applyButton">Apply for a Course</button></a>

        </div>
        <div id="applyModal" class="apply-modal">
            <div class="apply-modal-content">
                <span class="close-btn" id="closeModal">&times;</span>
                <h3>Apply for a Course</h3>
                <form id="applyForm" autocomplete="on" action="" method="POST" enctype="multipart/form-data">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required autocomplete="name" />
                    <span id="name-error" class="error-message"></span>


                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required autocomplete="email" />
                    <span id="email-error" class="error-message"></span>


                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required />
                    <span id="password-error" class="error-message"></span>


                    <label for="course">Select Course:</label>
                    <input list="course-list" name="course" id="course">
                    <datalist id="course-list" name="course" required>
                        <option value="GitHub">
                        <option value="Python">
                        <option value="C++">
                        <option value="HTML">
                        <option value="CSS">
                        <option value="JavaScript">
                    </datalist>
                    <span id="course-error" class="error-message"></span>
                    <label for="file-upload">Upload your file (e.g., resume or profile picture):</label>
                    <div id="drag-drop-area" class="drag-drop-area">
                        <p>Drag and drop your file here or click to select a file.</p>
                        <input type="file" id="file-upload" name="file-upload" accept="image/*, .pdf, .bmp , .svg , .jpg , .jpeg , .doc , .docx" hidden />
                    </div>
                    <span id="file-error" class="error-message"></span>
                    <button type="submit">Submit Application</button>
                    <output id="submission-count">No applications submitted yet.</output>
                </form>
            </div>
        </div>
        <script>
    document.addEventListener('DOMContentLoaded', function () {
        const dragDropArea = document.getElementById('drag-drop-area');
        const fileInput = document.getElementById('file-upload');

        dragDropArea.addEventListener('click', () => {
            fileInput.click();
        });

        dragDropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dragDropArea.style.border = '2px dashed #000';
            dragDropArea.style.backgroundColor = '#f0f0f0';
        });

        dragDropArea.addEventListener('dragleave', () => {
            dragDropArea.style.border = '';
            dragDropArea.style.backgroundColor = '';
        });

        // Update the drop event listener:
dragDropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    dragDropArea.style.border = '';
    dragDropArea.style.backgroundColor = '';

    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        // Trigger the change event to update UI
        const changeEvent = new Event('change');
        fileInput.dispatchEvent(changeEvent);
    }
});
fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            dragDropArea.innerHTML = `<p>File selected: ${this.files[0].name}</p>`;
        } else {
            dragDropArea.innerHTML = '<p>Drag and drop your file here or click to select a file.</p>';
        }
    });
    });
</script>

        <audio id="success-audio" src="audio/review-published.mp3"></audio>
        <audio id="failure-audio" src="audio/review-cancel.mp3"></audio>
        <style>
            .error-message {
                color: red;
                font-size: 12px;
            }
        </style>
    </section>
    <section>
        <h2>Our Professors</h2>

        <?php
        $query = "SELECT Name , Title , Gender , Biography FROM professors";
        $result = $conn->query($query);

        $professors = [];
        while ($row = $result->fetch_assoc()) { // Equivalent to mysql_fetch_assoc()
            $professors[] = $row;
        }

        ksort($professors);
        $conn->close();
        ?>
        <div class="row-other">
            <?php foreach ($professors as $p):
                $imageNumber = ($p['Gender'] === 'Female') ? 2 : 1;
            ?>
                <div class="col-other reveal-left">
                    <div class="card">
                        <img src="images/prof<?= $imageNumber ?>.webp" alt="Professor <?= $p['Name'] ?>" />
                        <h3><?= $p['Title'] ?> <?= $p['Name'] ?></h3>
                        <p>
                            <?= $p['Biography'] ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </section>
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
        window.addEventListener('scroll', reveal);
        window.addEventListener('scroll', revealLeft);
        window.addEventListener('scroll', revealRight);

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
        const dragDropArea = document.getElementById("drag-drop-area");
        const fileInput = document.getElementById("file-upload");


        dragDropArea.addEventListener("click", () => {
            fileInput.click();
        });


        dragDropArea.addEventListener("dragover", (event) => {
            event.preventDefault();
            dragDropArea.style.backgroundColor = "#e0f7ff";
        });


        dragDropArea.addEventListener("drop", (event) => {
            event.preventDefault();
            dragDropArea.style.backgroundColor = "#f9f9f9";
            const files = event.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
            }
        });


        fileInput.addEventListener("change", (event) => {
            const file = event.target.files[0];
            if (file) {
                dragDropArea.innerHTML = `<p>File selected: ${file.name}</p>`;
            } else {
                dragDropArea.innerHTML = "<p>Drag and drop your file here or click to select a file.</p>";
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById("applyModal");
            const applyButton = document.getElementById("applyButton");
            const closeModal = document.getElementById("closeModal");

            applyButton.onclick = function() {
                modal.style.display = "block";
            };

            closeModal.onclick = function() {
                modal.style.display = "none";
            };

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            };

            const applyForm = document.getElementById("applyForm");
            applyForm.onsubmit = function(event) {
                event.preventDefault();

                const name = document.getElementById("name").value;
                const email = document.getElementById("email").value;
                const course = document.getElementById("course").value;

                const successAudio = document.getElementById("success-audio");
                const failureAudio = document.getElementById("failure-audio");

                if (name && email && course) {
                    successAudio.currentTime = 0;
                    successAudio.play().catch(error => {
                        console.error('Audio playback failed:', error);
                    });

                    alert("Application submitted successfully!");
                    modal.style.display = "none";
                } else {
                    failureAudio.currentTime = 0;
                    failureAudio.play().catch(error => {
                        console.error('Audio playback failed:', error);
                    });

                    alert("Please fill in all the fields.");
                }
            };
        });
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById("applyModal");
            const applyButton = document.getElementById("applyButton");
            const closeModal = document.getElementById("closeModal");

            let submissionCount = [];

            applyButton.onclick = function() {
                modal.style.display = "block";
                document.body.style.overflow = "hidden";
            };

            closeModal.onclick = function() {
                modal.style.display = "none";
                document.body.style.overflow = "auto";
            };

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            };

            const applyForm = document.getElementById("applyForm");
            const submissionOutput = document.getElementById("submission-count");

            applyForm.onsubmit = function(event) {
                event.preventDefault();

                const name = document.getElementById("name").value;
                const email = document.getElementById("email").value;
                const course = document.getElementById("course").value;

                const successAudio = document.getElementById("success-audio");
                const failureAudio = document.getElementById("failure-audio");

                if (name && email && course) {
                    successAudio.currentTime = 0;
                    successAudio.play().catch(error => {
                        console.error('Audio playback failed:', error);
                    });

                    submissionCount = [...submissionCount, 1];
                    const totalSubmissions = submissionCount.reduce((acc, curr) => acc + curr, 0);

                    submissionOutput.textContent = `${totalSubmissions} application(s) submitted.`;

                    alert("Application submitted successfully!");
                    this.submit(); // Submit the form to the server

                    modal.style.display = "none";
                } else {
                    failureAudio.currentTime = 0;
                    failureAudio.play().catch(error => {
                        console.error('Audio playback failed:', error);
                    });

                    alert("Please fill in all the fields.");
                }
            };
        });

        function validateNotEmpty(value, errorId) {
            const errorMessage = document.getElementById(errorId);
            if (value.trim().length === 0) {
                errorMessage.textContent = "This field is required.";
                return false;
            } else {
                errorMessage.textContent = "";
                return true;
            }
        }

        function validateEmail(email) {
            const errorMessage = document.getElementById('email-error');
            const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!regex.test(email)) {
                errorMessage.textContent = "Please enter a valid email address.";
                return false;
            } else {
                errorMessage.textContent = "";
                return true;
            }
        }

        function validatePassword(password) {
            const errorMessage = document.getElementById('password-error');
            const regex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
            if (!regex.test(password)) {
                errorMessage.textContent = "Password must be at least 8 characters long and contain at least one number.";
                return false;
            } else {
                errorMessage.textContent = "";
                return true;
            }
        }

        document.getElementById('applyForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const course = document.getElementById('course').value;
            const fileUpload = document.getElementById('file-upload').files.length > 0;

            let isValid = true;

            if (!validateNotEmpty(name, 'name-error')) {
                isValid = false;
            }

            if (!validateEmail(email)) {
                isValid = false;
            }

            if (!validatePassword(password)) {
                isValid = false;
            }

            if (!validateNotEmpty(course, 'course-error')) {
                isValid = false;
            }

            if (!fileUpload) {
                document.getElementById('file-error').textContent = "Please upload a file.";
                isValid = false;
            } else {
                document.getElementById('file-error').textContent = "";
            }

            if (isValid) {
                console.log("Form is valid. Submit the application.");
                document.getElementById('submission-count').textContent = "Application submitted successfully!";
            }
        });

        document.getElementById('name').addEventListener('blur', function() {
            validateNotEmpty(this.value, 'name-error');
        });

        document.getElementById('email').addEventListener('blur', function() {
            validateEmail(this.value);
        });

        document.getElementById('password').addEventListener('blur', function() {
            validatePassword(this.value);
        });

        document.getElementById('course').addEventListener('blur', function() {
            validateNotEmpty(this.value, 'course-error');
        });

        document.getElementById('drag-drop-area').addEventListener('click', function() {
            document.getElementById('file-upload').click();
        });

        document.getElementById('file-upload').addEventListener('change', function(event) {
            const fileName = event.target.files[0]?.name;
            if (fileName) {
                console.log('File selected:', fileName);
            }
        });
    </script>
    <script>
        function addClickAudioToButton(buttonSelector, audioId) {
            const button = document.querySelector(buttonSelector);
            const audio = document.getElementById(audioId);

            if (button && audio) {
                button.addEventListener('click', () => {
                    audio.currentTime = 0;
                    audio.play().catch(error => {
                        console.error('Audio playback failed:', error);
                    });
                });
            } else {
                console.error(`Button or audio element not found: ${buttonSelector}, ${audioId}`);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            addClickAudioToButton('#applyButton', 'button-click-audio');
        });
    </script>
    <script>
        function addHoverAudioToCourseCards(cardSelector, audioId) {
            const cards = document.querySelectorAll(cardSelector);
            const audio = document.getElementById(audioId);

            if (audio) {
                cards.forEach(card => {
                    card.addEventListener('mouseenter', () => {
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
            addHoverAudioToCourseCards('.card', 'card-hover-audio');
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
</body>
</html>