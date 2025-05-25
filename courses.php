<?php
session_start();
require 'database/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$_SESSION['role'] = $_SESSION['role'] ?? 'user';

if (!isset($_SESSION['course_counts'])) {
    $countQuery = "SELECT c.Name AS course, COUNT(*) AS count
FROM course_applications ca
JOIN courses c ON ca.course_id = c.ID
GROUP BY ca.course_id, c.Name;
";
    $countResult = $conn->query($countQuery);
    $_SESSION['course_counts'] = [];
    while ($row = $countResult->fetch_assoc()) {
        $_SESSION['course_counts'][$row['course']] = $row['count'];
    }
}

$currentPage = 'courses';
$jsonPath = 'data/courses_form.json';
require_once 'utils/BaseFormData.php';
require 'utils/XSSValidator.php';
require_once 'config.php';

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

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['action'])) {
    $targetFilePath = "Uploads/default.svg";
    $fileData = null;
    $fileName = '';
    $fileType = '';

    if (!empty($_FILES['file-upload']['name']) && $_FILES['file-upload']['error'] == UPLOAD_ERR_OK) {
        $fileName = basename($_FILES["file-upload"]["name"]);
        $targetFilePath = "Uploads/" . $fileName;
        $fileType = $_FILES["file-upload"]["type"];

        if (file_exists($targetFilePath)) {
            die("Sorry, file already exists.");
        }

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
        $jsonData = file_get_contents($jsonPath);
        $jsonData = rtrim($jsonData, "]\n") . "\n ," . $applyCourses->JSONify() . "\n]";
        file_put_contents($jsonPath, $jsonData);
    }

    $stmt = $conn->prepare("INSERT INTO course_applications (name, email, password, course, file_name, file_type, file_data) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    if ($fileData === null) {
        $fileData = file_get_contents("Uploads/default.svg");
        $fileName = "default.svg";
        $fileType = "image/svg+xml";
    }

    $stmt->bind_param("sssssss", $name, $email, $password, $course, $fileName, $fileType, $fileData);

    if ($stmt->execute()) {
        $countQuery = "SELECT course, COUNT(*) as count FROM course_applications GROUP BY course";
        $countResult = $conn->query($countQuery);
        $_SESSION['course_counts'] = [];
        while ($row = $countResult->fetch_assoc()) {
            $_SESSION['course_counts'][$row['course']] = $row['count'];
        }
        $_SESSION['success_message'] = "Application submitted successfully.";
        $stmt->close();
        header("Location: courses.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

require 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Courses and Professors</title>

    <link rel="stylesheet" href="style/courses.css">
    <link rel="stylesheet" href="style/navbar.css">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(getStylesheetPath('courses.css')); ?>">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(getStylesheetPath('navbar.css')); ?>">  
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bona+Nova+SC:ital,wght@0,400;0,700;1,400&family=Rubik:ital,wght@0,300..900;1,300..900&family=Spicy+Rice&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bona+Nova+SC:ital,wght@0,400;0,700;1,400&family=Cinzel:wght@400..900&family=Rubik:ital,wght@0,300..900;1,300..900&family=Spicy+Rice&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bona+Nova+SC:ital,wght@0,400;0,700;1,400&family=Changa:wght@200..800&family=Cinzel:wght@400..900&family=Rubik:ital,wght@0,300..900;1,300..900&family=Spicy+Rice&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <style>
        .col-other, .col {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        .card {
            background: transparent !important;
            display: block !important;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .dark-mode .card {
            background: transparent !important;
        }
        .card img {
            background: transparent !important;
            object-fit: contain;
        }
        .apply-container {
            display: block !important;
            text-align: center;
            margin: 20px 0;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            transition: opacity 0.3s ease;
        }
        .modal.active {
            display: block;
            opacity: 1;
        }
        .modal-content {
            background-color: #3d4548;
            color: #f0f0f0;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 30px;
            border: 2px solid #0a5d9c;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            display: block;
            z-index: 1001;
        }
        .dark-mode .modal-content {
            background-color: #3d4548;
            color: #f0f0f0;
        }
        .light-mode .modal-content {
            background-color: #ffffff;
            color: #333333;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .modal-content h3 {
            margin: 0 0 20px;
            font-family: 'Ubuntu', sans-serif;
            color: #f0f0f0;
        }
        .light-mode .modal-content h3 {
            color: #333333;
        }
        .modal-content label {
            display: block;
            margin: 10px 0 5px;
            font-family: 'Rubik', sans-serif;
            color: #f0f0f0;
        }
        .light-mode .modal-content label {
            color: #333333;
        }
        .modal-content input, .modal-content textarea, .modal-content select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #0a5d9c;
            border-radius: 4px;
            background-color: #2d3436;
            color: #f0f0f0;
            font-family: 'Rubik', sans-serif;
        }
        .light-mode .modal-content input,
        .light-mode .modal-content textarea,
        .light-mode .modal-content select {
            background-color: #f0f0f0;
            color: #333333;
        }
        .modal-content button[type="submit"] {
            background-color: #0a5d9c;
            color: #f0f0f0;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Ubuntu', sans-serif;
            transition: background-color 0.3s ease;
        }
        .modal-content button[type="submit"]:hover {
            background-color: #084c82;
        }
        .close-btn {
            color: #f0f0f0;
            float: right;
            font-size: 28px;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .light-mode .close-btn {
            color: #333333;
        }
        .close-btn:hover {
            color: #0a5d9c;
        }
        .error-message {
            color: #ff4d4d;
            font-size: 12px;
            display: block;
            margin-bottom: 10px;
        }
        .card-buttons {
            margin-top: 10px;
        }
        .card-buttons button {
            margin-right: 5px;
            background-color: #0a5d9c;
            color: #f0f0f0;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .card-buttons button:hover {
            background-color: #084c82;
        }
        .reveal-left {
            position: relative;
            transform: translateX(-50px);
            opacity: 0;
            transition: all 1s ease;
        }
        .reveal-left.active {
            transform: translateX(0);
            opacity: 1;
        }
        #addCourseButton, #addProfessorButton {
            margin-right: 5px;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-family: 'Ubuntu', sans-serif;
        }
        section h2 {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        section h2 button {
            margin: 0;
        }
    </style>
</head>
<body class="<?php echo $dark_mode === 'on' ? 'dark-mode' : 'light-mode'; ?>">
    <audio id="nav-hover-audio" src="audio/navbar-hover.mp3"></audio>
    <audio id="nav-click-audio" src="audio/shift-page.mp3"></audio>
    <section class="courses">
        <h2>Our Courses
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <button id="addCourseButton">Add New Course</button>
        <?php endif; ?>
        </h2>
        <?php
        
        $query = "SELECT id, Name, Photo, Description FROM courses";
        $result = $conn->query($query);
        if (!$result) {
            echo "<p>Error fetching courses: " . $conn->error . "</p>";
        } else {
            $courseData = [];
            while ($row = $result->fetch_assoc()) {
                $courseData[] = $row;
            }
            if (empty($courseData)) {
                echo "<p>No courses found.</p>";
            } else {
                $courseTitles = [];
                foreach ($courseData as $key => $course) {
                    $courseTitles[$key] = $course['Name'];
                }
                asort($courseTitles);
        ?>
        <div class="row-other" id="courseContainer">
            <?php foreach ($courseTitles as $key => $title): ?>
                <?php $course = $courseData[$key]; ?>
                <div class="col" data-course-id="<?php echo $course['id']; ?>">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($course['Photo']); ?>" alt="<?php echo htmlspecialchars($course['Name']); ?>" />
                        <h3><?php echo htmlspecialchars($course['Name']); ?></h3>
                        <p><?php echo htmlspecialchars($course['Description']); ?></p>
                        <p><strong>Applications:</strong> <?php echo isset($_SESSION['course_counts'][$course['Name']]) ? $_SESSION['course_counts'][$course['Name']] : 0; ?></p>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <div class="card-buttons">
                                <button class="edit-course" data-id="<?php echo $course['id']; ?>">Edit</button>
                                <button class="delete-course" data-id="<?php echo $course['id']; ?>">Delete</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php } } ?>
        <audio id="card-hover-audio" src="audio/div-hover.mp3"></audio>
        <?php if ($_SESSION['role'] !== 'admin'): ?>
            <div class="apply-container">
                <button id="applyButton">Apply for a Course</button>
            </div>
        <?php endif; ?>
        <div id="applyModal" class="modal">
            <div class="modal-content">
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
                    <input list="course-list" name="course" id="course" required>
                    <datalist id="course-list">
                        <option value="GitHub">
                        <option value="Python">
                        <option value="C++">
                        <option value="HTML">
                        <option value="CSS">
                        <option value="JavaScript">
                        <option value="PHP">
                    </datalist>
                    <span id="course-error" class="error-message"></span>
                    <label for="file-upload">Upload your file:</label>
                    <div id="drag-drop-area" class="drag-drop-area">
                        <p>Drag and drop your file here or click to select a file.</p>
                        <input type="file" id="file-upload" name="file-upload" accept="image/*, .pdf, .bmp, .svg, .jpg, .jpeg, .doc, .docx" hidden />
                    </div>
                    <span id="file-error" class="error-message"></span>
                    <button type="submit">Submit Application</button>
                    <output id="submission-count">No applications submitted yet.</output>
                </form>
            </div>
        </div>
        <div id="courseModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" id="closeCourseModal">&times;</span>
                <h3 id="courseModalTitle">Add New Course</h3>
                <form id="courseForm">
                    <input type="hidden" id="courseId">
                    <label for="courseName">Course Name:</label>
                    <input type="text" id="courseName" required />
                    <span id="courseName-error" class="error-message"></span>
                    <label for="coursePhoto">Photo Upload:</label>
                    <input type="file" id="coursePhoto" accept="image/*" required />
                    <span id="coursePhoto-error" class="error-message"></span>
                    <label for="courseDescription">Description:</label>
                    <textarea rows="5" id="courseDescription" required></textarea>
                    <span id="courseDescription-error" class="error-message"></span>
                    <button type="submit">Save</button>
                </form>
            </div>
        </div>
    </section>
    <section>
        <h2>Our Professors
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <button id="addProfessorButton">Add New Professor</button>
            <?php endif; ?>
        </h2>
        <?php
        $query = "SELECT id, Name, Title, Gender, Biography FROM professors";
        $result = $conn->query($query);
        if (!$result) {
            echo "<p>Error fetching professors: " . htmlspecialchars($conn->error) . "</p>";
        } else {
            $professors = [];
            while ($row = $result->fetch_assoc()) {
                $professors[] = $row;
            }
            if (empty($professors)) {
                echo "<p>No professors found.</p>";
            } else {
                ksort($professors);
        ?>
        <div class="row-other" id="professorContainer">
            <?php foreach ($professors as $p): ?>
                <div class="col-other reveal-left" data-professor-id="<?php echo htmlspecialchars($p['id']); ?>">
                    <div class="card">
                        <img src="images/prof<?php echo ($p['Gender'] === 'Female') ? 2 : 1; ?>.webp" alt="Professor <?php echo htmlspecialchars($p['Name']); ?>" />
                        <h3><?php echo htmlspecialchars($p['Title'] . ' ' . $p['Name']); ?></h3>
                        <p><?php echo htmlspecialchars($p['Biography']); ?></p>
                        <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <div class="card-buttons">
                                <button class="edit-professor" data-id="<?php echo htmlspecialchars($p['id']); ?>" aria-label="Edit professor">Edit</button>
                                <button class="delete-professor" data-id="<?php echo htmlspecialchars($p['id']); ?>" aria-label="Delete professor">Delete</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
            }
        }
        ?>
        <div id="professorModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" id="closeProfessorModal">&times;</span>
                <h3 id="professorModalTitle">Add New Professor</h3>
                <form id="professorForm">
                    <input type="hidden" id="professorId">
                    <label for="professorName">Name:</label>
                    <input type="text" id="professorName" required />
                    <span id="professorName-error" class="error-message"></span>
                    <label for="professorTitle">Title:</label>
                    <input type="text" id="professorTitle" required />
                    <span id="professorTitle-error" class="error-message"></span>
                    <label for="professorGender">Gender:</label>
                    <select id="professorGender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    <span id="professorGender-error" class="error-message"></span>
                    <label for="professorBiography">Biography:</label>
                    <textarea rows="5" id="professorBiography" required></textarea>
                    <span id="professorBiography-error" class="error-message"></span>
                    <button type="submit">Save</button>
                </form>
            </div>
        </div>
    </section>
    <?php require('footer.php'); ?>
    <audio id="success-audio" src="audio/review-published.mp3"></audio>
    <audio id="failure-audio" src="audio/review-cancel.mp3"></audio>
    <script>
$(document).ready(function() {
    console.log('jQuery version:', $.fn.jquery);

    
    function checkReveal() {
        const revealElements = document.querySelectorAll('.reveal-left');
        const windowHeight = window.innerHeight;
        const revealPoint = 150;
        revealElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            if (elementTop < windowHeight - revealPoint) {
                element.classList.add('active');
            }
        });
    }
    checkReveal();
    window.addEventListener('scroll', checkReveal);

    $(document).on('click', '.edit-course', function() {
        console.log('Edit course button clicked, ID:', $(this).data('id'));
        const id = $(this).data('id');
        if (!id) {
            console.error('No course ID found');
            alert('Error: No course ID provided');
            return;
        }
        $.ajax({
            url: 'get-course.php',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                console.log('Get course response:', response);
                if (response.error) {
                    console.error('Server error:', response.error);
                    alert('Error: ' + response.error);
                    return;
                }
                $('#courseModalTitle').text('Edit Course');
                $('#courseId').val(response.id);
                $('#courseName').val(response.Name);
                $('#coursePhoto').val('');
                $('#courseDescription').val(response.Description);
                $('#courseModal').addClass('active');
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', xhr.responseText, status, error);
                alert('Error fetching course data: ' + (xhr.responseText || error));
            }
        });
    });

    $('#addCourseButton').click(function() {
        console.log('Add course button clicked');
        $('#courseModalTitle').text('Add New Course');
        $('#courseForm')[0].reset();
        $('#courseId').val('');
        $('#courseModal').addClass('active');
    });

    $(document).on('click', '.delete-course', function() {
        console.log('Delete course button clicked, ID:', $(this).data('id'));
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this course?')) {
            $.ajax({
                url: 'delete-course.php',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    console.log('Delete course response:', response);
                    if (response.error) {
                        alert('Error: ' + response.error);
                        return;
                    }
                    $(`div[data-course-id="${id}"]`).remove();
                    $('#success-audio').get(0).play();
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', xhr.responseText, status, error);
                    alert('Error deleting course: ' + (xhr.responseText || error));
                }
            });
        }
    });

    $('#courseForm').submit(function(e) {
        e.preventDefault();
        console.log('Course form submitted');
        const id = $('#courseId').val();
        const url = id ? 'update-course.php' : 'create-course.php';
        const formData = new FormData();
        formData.append('id', id);
        formData.append('name', $('#courseName').val());
        formData.append('photo', $('#coursePhoto')[0].files[0]);
        formData.append('description', $('#courseDescription').val());

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                console.log('Save course response:', response);
                if (response.error) {
                    alert('Error: ' + response.error);
                    return;
                }
                if (id) {
                    $(`div[data-course-id="${id}"] .card`).html(`
                        <img src="${response.Photo}" alt="${response.Name}" />
                        <h3>${response.Name}</h3>
                        <p>${response.Description}</p>
                        <p><strong>Applications:</strong> ${response.Applications || 0}</p>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <div class="card-buttons">
                                <button class="edit-course" data-id="${response.id}" aria-label="Edit course">Edit</button>
                                <button class="delete-course" data-id="${response.id}" aria-label="Delete course">Delete</button>
                            </div>
                        <?php endif; ?>
                    `);
                } else {
                    $('#courseContainer').append(`
                        <div class="col" data-course-id="${response.id}">
                            <div class="card">
                                <img src="${response.Photo}" alt="${response.Name}" />
                                <h3>${response.Name}</h3>
                                <p>${response.Description}</p>
                                <p><strong>Applications:</strong> 0</p>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <div class="card-buttons">
                                        <button class="edit-course" data-id="${response.id}" aria-label="Edit course">Edit</button>
                                        <button class="delete-course" data-id="${response.id}" aria-label="Delete course">Delete</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    `);
                }
                $('#courseModal').removeClass('active');
                $('#success-audio').get(0).play();
            },
            error: function(xhr, status, error) {
              console.error('Status:', status);
              console.error('Error:', error);
              console.error('Response text:', xhr.responseText);
              alert('Error saving course: ' + xhr.responseText);
            }
        });
    });

    $(document).on('click', '.edit-professor', function() {
        console.log('Edit professor button clicked, ID:', $(this).data('id'));
        const id = $(this).data('id');
        $.ajax({
            url: 'get-professor.php',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                console.log('Professor response:', response);
                if (response.error) {
                    alert('Error: ' + response.error);
                    return;
                }
                $('#professorModalTitle').text('Edit Professor');
                $('#professorId').val(response.id);
                $('#professorName').val(response.Name);
                $('#professorTitle').val(response.Title);
                $('#professorGender').val(response.Gender);
                $('#professorBiography').val(response.Biography);
                $('#professorModal').addClass('active');
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', xhr.responseText, status, error);
                alert('Error fetching professor data: ' + (xhr.responseText || error));
            }
        });
    });

    $('#addProfessorButton').click(function() {
        console.log('Add professor button clicked');
        $('#professorModalTitle').text('Add New Professor');
        $('#professorForm')[0].reset();
        $('#professorId').val('');
        $('#professorModal').addClass('active');
    });

    $(document).on('click', '.delete-professor', function() {
        console.log('Delete professor button clicked, ID:', $(this).data('id'));
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this professor?')) {
            $.ajax({
                url: 'delete-professor.php',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    console.log('Delete professor response:', response);
                    if (response.error) {
                        alert('Error: ' + response.error);
                        return;
                    }
                    $(`div[data-professor-id="${id}"]`).remove();
                    $('#success-audio').get(0).play();
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', xhr.responseText, status, error);
                    alert('Error deleting professor: ' + (xhr.responseText || error));
                }
            });
        }
    });

    $('#professorForm').submit(function(e) {
        e.preventDefault();
        console.log('Professor form submitted');
        const id = $('#professorId').val();
        const url = id ? 'update-professor.php' : 'create-professor.php';
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                id: id,
                name: $('#professorName').val(),
                title: $('#professorTitle').val(),
                gender: $('#professorGender').val(),
                biography: $('#professorBiography').val()
            },
            dataType: 'json',
            success: function(response) {
                console.log('Save professor response:', response);
                if (response.error) {
                    alert('Error: ' + response.error);
                    return;
                }
                const imageSrc = response.Gender === 'Female' ? 'images/prof2.webp' : 'images/prof1.webp';
                if (id) {
                    $(`div[data-professor-id="${id}"] .card`).html(`
                        <img src="${imageSrc}" alt="Professor ${response.Name}" />
                        <h3>${response.Title} ${response.Name}</h3>
                        <p>${response.Biography}</p>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <div class="card-buttons">
                                <button class="edit-professor" data-id="${response.id}" aria-label="Edit professor">Edit</button>
                                <button class="delete-professor" data-id="${response.id}" aria-label="Delete professor">Delete</button>
                            </div>
                        <?php endif; ?>
                    `);
                } else {
                    $('#professorContainer').append(`
                        <div class="col-other reveal-left" data-professor-id="${response.id}">
                            <div class="card">
                                <img src="${imageSrc}" alt="Professor ${response.Name}" />
                                <h3>${response.Title} ${response.Name}</h3>
                                <p>${response.Biography}</p>
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <div class="card-buttons">
                                        <button class="edit-professor" data-id="${response.id}" aria-label="Edit professor">Edit</button>
                                        <button class="delete-professor" data-id="${response.id}" aria-label="Delete professor">Delete</button>
                                    </div>
                        <?php endif; ?>
                            </div>
                        </div>
                    `);
                    checkReveal();
                }
                $('#professorModal').removeClass('active');
                $('#success-audio').get(0).play();
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', xhr.responseText, status, error);
                alert('Error saving professor: ' + (xhr.responseText || error));
            }
        });
    });

    $('#closeCourseModal, #closeProfessorModal, #closeModal').click(function() {
        $(this).closest('.modal').removeClass('active');
        $('body').css('overflow', 'auto');
    });

    $(window).click(function(e) {
        if ($(e.target).hasClass('modal')) {
            $('.modal').removeClass('active');
            $('body').css('overflow', 'auto');
        }
    });

    const dragDropArea = $('#drag-drop-area');
    const fileInput = $('#file-upload');
    dragDropArea.click(() => fileInput.click());
    dragDropArea.on('dragover', (e) => {
        e.preventDefault();
        dragDropArea.css({ border: '2px dashed #0a5d9c', backgroundColor: '#3d4548' });
    });
    dragDropArea.on('dragleave', () => {
        dragDropArea.css({ border: '2px dashed #0a5d9c', backgroundColor: '#2d3436' });
    });
    dragDropArea.on('drop', (e) => {
        e.preventDefault();
        dragDropArea.css({ border: '2px dashed #0a5d9c', backgroundColor: '#2d3436' });
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            fileInput[0].files = files;
            fileInput.trigger('change');
        }
    });
    fileInput.on('change', function() {
        if (this.files && this.files[0]) {
            dragDropArea.html(`<p>File selected: ${this.files[0].name}</p>`);
        } else {
            dragDropArea.html('<p>Drag and drop your file here or click to select a file.</p>');
        }
    });

    $('#applyButton').click(function() {
        console.log('Apply button clicked');
        $('#applyModal').addClass('active');
        $('body').css('overflow', 'hidden');
    });

    $('#closeModal').click(function() {
        $('#applyModal').removeClass('active');
        $('body').css('overflow', 'auto');
    });

    $(window).click(function(e) {
        if (e.target.id === 'applyModal') {
            $('#applyModal').removeClass('active');
            $('body').css('overflow', 'auto');
        }
    });

    function validateNotEmpty(value, errorId) {
        const errorMessage = $(`#${errorId}`);
        if (value.trim().length === 0) {
            errorMessage.text('This field is required.');
            return false;
        } else {
            errorMessage.text('');
            return true;
        }
    }

    function validateEmail(email) {
        const errorMessage = $('#email-error');
        const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!regex.test(email)) {
            errorMessage.text('Please enter a valid email address.');
            return false;
        } else {
            errorMessage.text('');
            return true;
        }
    }

    function validatePassword(password) {
        const errorMessage = $('#password-error');
        const regex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
        if (!regex.test(password)) {
            errorMessage.text('Password must be at least 8 characters long and contain at least one number.');
            return false;
        } else {
            errorMessage.text('');
            return true;
        }
    }

    function validateFile(fileInput) {
        const errorMessage = $('#file-error');
        if (!fileInput.files || fileInput.files.length === 0) {
            errorMessage.text('Please upload a file.');
            return false;
        }
        const file = fileInput.files[0];
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!allowedTypes.includes(file.type)) {
            errorMessage.text('Invalid file type. Allowed types: JPG, PNG, GIF, PDF, DOC, DOCX.');
            return false;
        }
        errorMessage.text('');
        return true;
    }

    $('#applyForm').submit(function(e) {
        e.preventDefault();
        console.log('Apply form submitted');
        const name = $('#name').val();
        const email = $('#email').val();
        const password = $('#password').val();
        const course = $('#course').val();
        const fileInput = $('#file-upload')[0];

        let isValid = true;
        if (!validateNotEmpty(name, 'name-error')) isValid = false;
        if (!validateEmail(email)) isValid = false;
        if (!validatePassword(password)) isValid = false;
        if (!validateNotEmpty(course, 'course-error')) isValid = false;
        if (!validateFile(fileInput)) isValid = false;

        if (isValid) {
            this.submit();
        }
    });

    $('#name').blur(function() { validateNotEmpty(this.value, 'name-error'); });
    $('#email').blur(function() { validateEmail(this.value); });
    $('#password').blur(function() { validatePassword(this.value); });
    $('#course').blur(function() { validateNotEmpty(this.value, 'course-error'); });
    $('#file-upload').change(function() { validateFile(this); });

    function addHoverAudioEffectToLinks(linkSelector, audioId) {
        const links = document.querySelectorAll(linkSelector);
        const audio = document.getElementById(audioId);
        if (audio) {
            links.forEach(link => {
                link.addEventListener('mouseenter', () => {
                    audio.currentTime = 0;
                    audio.play().catch(error => console.error('Audio playback failed:', error));
                });
            });
        }
    }

    function addClickAudioEffectToLinks(linkSelector, audioId) {
        const links = document.querySelectorAll(linkSelector);
        const audio = document.getElementById(audioId);
        if (audio) {
            links.forEach(link => {
                link.addEventListener('click', (event) => {
                    event.preventDefault();
                    audio.currentTime = 0;
                    audio.play().catch(error => console.error('Audio playback failed:', error));
                    audio.onended = () => window.location.href = link.href;
                });
            });
        }
    }

    function addClickAudioToButton(buttonSelector, audioId) {
        const button = document.querySelector(buttonSelector);
        const audio = document.getElementById(audioId);
        if (button && audio) {
            button.addEventListener('click', () => {
                audio.currentTime = 0;
                audio.play().catch(error => console.error('Audio playback failed:', error));
            });
        }
    }

    function addHoverAudioToCards(cardSelector, audioId) {
        const cards = document.querySelectorAll(cardSelector);
        const audio = document.getElementById(audioId);
        if (audio) {
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    audio.currentTime = 0;
                    audio.play().catch(error => console.error('Audio playback failed:', error));
                });
            });
        }
    }

    addHoverAudioEffectToLinks('.links a', 'nav-hover-audio');
    addClickAudioEffectToLinks('.links a', 'nav-click-audio');
    addClickAudioToButton('#applyButton', 'button-click-audio');
    addHoverAudioToCards('.col, .col-other', 'card-hover-audio');

    $('.col, .col-other').css({ display: 'block', visibility: 'visible', opacity: 1 });
});
    </script>
</body>
</html>
