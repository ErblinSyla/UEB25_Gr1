<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Courses and Professors</title>
  <link rel="stylesheet" href="courses.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="courses.js"></script>
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

  <nav>
    <div class="row mobile-row" style="justify-content: space-between; width: 100%;">
      <div class="col-4 title">
        <div class="logo">
          <img id="logo" src="algoverse_logo.svg" alt="AlgoVerse Academy Logo">
        </div>
        <h2 style="color: black;">AlgoVerse Academy</h2>
      </div>
      <div class="col-8">
        <ul class="links" id="nav-links">
          <li><a href="homepage.php">Homepage</a></li>
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
  </nav>
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

  <section class="courses">
    <h2>Our Courses</h2>
    <div class="row">

      <div class="col">
        <div class="card">
          <img src="images/pythonlogo.jpg" alt="Course 1" />
          <h3>Python</h3>
          <p>
            Python is a versatile programming language known for its simplicity and readability. Perfect for beginners and widely used in web development, data science, AI, and automation
          </p>
        </div>
      </div>

      <div class="col">
        <div class="card">
          <img src="images/cpplogo.png" alt="Course 2" />
          <h3>C++</h3>
          <p>
            C++ is a powerful, high-performance language often used for system software, game development, and applications requiring real-time performance.
          </p>
        </div>
      </div>

      <div class="col">
        <div class="card">
          <img src="images/javascriptlogo4.png" alt="Course 3" />
          <h3>JavaScript</h3>
          <p>
            JavaScript is the essential language for web development, enabling interactive and dynamic content on websites. Learn to bring your web pages to life!
          </p>
        </div>
      </div>

      <div class="col">
        <div class="card">
          <img src="images/htmllogo.png" alt="Course 4" />
          <h3>HTML</h3>
          <p>HTML (HyperText Markup Language) is the backbone of every website. It structures web content, allowing you to create pages with text, images, and links.
          </p>
        </div>
      </div>

      <div class="col">
        <div class="card">
          <img src="images/csslogo.png" alt="Course 5" />
          <h3>CSS</h3>
          <p>
            CSS (Cascading Style Sheets) controls the styling of web pages. Learn how to design beautiful, responsive websites with layout, colors, and animations.
          </p>
        </div>
      </div>

      <div class="col">
        <div class="card">
          <img src="images/github logo.png" alt="Course 6" />
          <h3>GitHub</h3>
          <p>
            GitHub is a platform for version control and collaboration. Learn to manage your projects, track changes, and collaborate with other developers efficiently.
          </p>
        </div>
      </div>
    </div>
    <audio id="card-hover-audio" src="audio/div-hover.mp3"></audio>
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

    <div class="apply-container">
      <audio id="button-click-audio" src="audio/button-click.mp3"></audio>

      <button id="applyButton">Apply for a Course</button>
    </div>
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
    <div id="applyModal" class="apply-modal">
      <div class="apply-modal-content">
        <span class="close-btn" id="closeModal">&times;</span>
        <h3>Apply for a Course</h3>
        <form id="applyForm" autocomplete="on" method="POST">
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
            <input type="file" id="file-upload" name="file-upload" accept="image/*, .pdf, .doc, .docx" hidden />
          </div>
          <span id="file-error" class="error-message"></span>

          <?php 
    
    require_once 'homepage.php';

    class ApplyCourses extends FormData{
      public $password;
      public $course;
      public $file;

      function __construct($name, $email, $password, $course, $file){
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->course = $course;
        $this->file = $file;  
      }

      function NameInEmail(){
        $NAME  = strtolower($this->name);
        $EMAIL = strtolower($this->email);

        if(strpos($EMAIL, $NAME) !== FALSE){
          echo "Name found in email!";
        }else{
          echo "Name not found in Email!";
        }
      }

    }
    
    ?>

          <button type="submit">Submit Application</button>
          <output id="submission-count">No applications submitted yet.</output>
        </form>
      </div>
    </div>
    <audio id="success-audio" src="audio/review-published.mp3"></audio>
    <audio id="failure-audio" src="audio/review-cancel.mp3"></audio>
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
    <style>
      .error-message {
        color: red;
        font-size: 12px;
      }
    </style>
  </section>
  <section style="background-color: white">
    <h2>Our Professors</h2>
    <div class="row">
      <div class="col">
        <div class="card">
          <img src="images/prof.webp" alt="Professor 1" />
          <h3>Professor John Doe</h3>
          <p>
            John Doe is an expert in HTML, CSS and javascript, with over 10+ years of experience.
          </p>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <img src="images/prof2.png" alt="Professor 2" />
          <h3>Professor Jane Smith</h3>
          <p>
            Jane Smith specializes in C++, Python and teaches GitHub basics thru our courses.
          </p>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <div class="row">
      <div class="col-12">
        <p>&copy; <span><?php echo date("Y-m-d"); ?></span> <em>AlgoVerse Academy. All rights reserved.</em></p>
      </div>
    </div>
  </footer>

  <script>
    const currentDate = new Date();
    const currentMonth = currentDate.toLocaleString('default', {
      month: 'long'
    });
    const currentYear = currentDate.getFullYear();

    document.getElementById('year-month').textContent = `${currentMonth} ${currentYear}`;
  </script>

</body>

</body>

</html>