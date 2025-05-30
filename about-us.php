<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$currentPage = 'about-us';
require('navbar.php');
require_once('database/db.php');

require_once 'config.php';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - AlgoVerse Academy</title>
    <!-- STYLE -->
    <link rel="stylesheet" href="style/about-us.css">
    <link rel="stylesheet" href="style/navbar.css">

    <link rel="stylesheet" href="<?php echo htmlspecialchars(getStylesheetPath('about-us.css')); ?>">
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
    <main class="main-section">
        <div style="position: relative; width: 100%; height: 92vh;">
            <div class="row" style="position: relative; z-index: 1;">
                <div class="col-1"></div>
                <div class="col-12 canvas-text">
                    <div>
                        <h2>One of the largest programming academies.</h2>
                        <h2>20 million visitors each month!</h2>
                    </div>
                </div>
                <div class="col-1"></div>
            </div>
            <canvas id="backgroundCanvas" style="position: absolute; top: 0; left: 0; width: 100%; height: 92vh;"></canvas>
        </div>
        <section class="about-us">
            <div class="row">
                <div class="col-12 reveal">
                    <h2 class="title-main">What is AlgoVerse Academy?</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="why-algoverse">
                        <p class="reveal-left">Algoverse Academy is an innovative online learning platform tailored to provide high-quality programming education. Whether you're a beginner eager to learn the fundamentals or an experienced coder aiming to advance your skills, Algoverse Academy offers a range of courses and resources to help you achieve your goals.</p>
                        <h4 class="reveal-right">Why choose Algoverse Academy?</h4>
                        <ul class="reveal">
                            <li><b>Diverse Course Offerings</b>
                                <ul>
                                    <li><b>Foundational Courses:</b> Ideal for those just starting their coding journey, covering programming basics and introductory languages.
                                        <ol class="reveal-left">
                                            <li>Python, JavaScript, HTML/CSS</li>
                                            <li>Problem-solving techniques and basic algorithms</li>
                                        </ol>
                                    </li>
                                    <li><b>Intermediate Courses:</b> For those looking to enhance their skills.
                                        <ol class="reveal-right">
                                            <li>Object-oriented programming (OOP)</li>
                                            <li>Data structures (Arrays, Linked Lists, Stacks, Queues)</li>
                                        </ol>
                                    </li>
                                    <li><b>Advanced Courses:</b> Tailored for seasoned developers who want to specialize further.
                                        <ol class="reveal-left">
                                            <li>Artificial Intelligence and Machine Learning</li>
                                            <li>Advanced algorithms, system design, and optimization techniques</li>
                                        </ol>
                                    </li>
                                </ul>
                            </li>
                            <li><b>Expert Instructors</b>
                                <ul class="revea-right">
                                    <li>Courses led by industry professionals with years of experience.</li>
                                    <li>Personalized mentorship to help guide you through challenging concepts.</li>
                                    <li>Real-world insights from instructors actively working in the tech industry.</li>
                                </ul>
                            </li>
                            <li><b>Real-World Projects</b>
                                <ul>
                                    <li>Practical, hands-on projects to apply your skills:
                                        <ol class="reveal-left">
                                            <li>Build a personal website using HTML, CSS, and JavaScript.</li>
                                            <li>Create algorithms that solve real-world problems (e.g., sorting, searching).</li>
                                        </ol>
                                    </li>
                                    <li>Portfolio-ready projects to showcase on GitHub or LinkedIn.</li>
                                    <li>Collaborate with peers on larger team projects to simulate working in a tech company.</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="easy-learning">
                <div class="row">
                    <div class="col-12">
                        <h2 class="title-main">Easy Learning</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1"></div>
                    <div class="col-5 reveal-left">
                        <audio id="iframe-hover-audio" src="audio/div-hover.mp3"></audio>
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/shN0HNnvaoM?si=ltvzHnQG9OmIn-Uq" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                    <div class="col-5">
                        <p class="reveal-right">At Algoverse Academy, we believe that learning should be accessible and enjoyable. Our easy-to-follow courses break down complex topics into simple, digestible lessons that cater to different learning styles. With interactive exercises, quizzes, and hands-on projects, you can apply what you've learned in real time. Our platform's intuitive design ensures that you stay engaged and motivated as you progress through each lesson. Whether you're a visual learner or prefer to dive into coding right away, Algoverse Academy makes learning to code easier than ever before.</p>
                    </div>
                    <div class="col-1"></div>
                </div>
            </div>
            <script>
                function addHoverAudioToIframe(iframeSelector, audioId) {
                    const iframe = document.querySelector(iframeSelector);
                    const audio = document.getElementById(audioId);

                    if (iframe && audio) {
                        iframe.addEventListener('mouseenter', () => {
                            audio.currentTime = 0;
                            audio.play().catch(error => {
                                console.error('Audio playback failed:', error);
                            });
                        });
                    } else {
                        console.error(`Iframe or audio element not found: ${iframeSelector}, ${audioId}`);
                    }
                }

                document.addEventListener('DOMContentLoaded', () => {
                    addHoverAudioToIframe('iframe', 'iframe-hover-audio');
                });
            </script>
            <?php
            $sql = "SELECT id, name, percentage, countries, comment FROM demographics ORDER BY percentage DESC";
            $result = $conn->query($sql);

            $continents = [];

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $continents[] = $row;
                }
            }

            $percentages = array_column($continents, 'percentage');
            $maxPercentage = max($percentages);
            $minPercentage = min($percentages);

            function getColorFromPercentage($percentage, $min, $max)
            {
                $ratio = ($percentage - $min) / ($max - $min);

                $r = (int)(213 + (0 - 213) * $ratio);
                $g = (int)(0 + (200 - 0) * $ratio);
                $b = 0;

                return "rgb($r, $g, $b)";
            }
            ?>
            <div class="demographics">
                <div class="row">
                    <div class="col-12 reveal">
                        <h2 class="title-main">Demographics</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="reveal">
                            <thead>
                                <tr>
                                    <th><i>Continent</i></th>
                                    <th><i>Percentage of Users</i></th>
                                    <th><i>Key Countries</i></th>
                                    <th><i>Comments</i></th>
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                        <th>Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($continents as $c):
                                    $color = getColorFromPercentage($c['percentage'], $minPercentage, $maxPercentage);
                                ?>
                                    <tr>
                                        <td><?= $c['name'] ?></td>
                                        <td><span style="color: <?= $color ?>;"><?= $c['percentage'] ?>%</span></td>
                                        <td><?= $c['countries'] ?></td>
                                        <td><?= $c['comment'] ?></td>
                                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                            <td>
                                                <form action="update-demo.php" method="get" style="display:inline;">
                                                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                                    <button class="table-button" type="submit">
                                                        <img class="update-img" src="images/update.png">
                                                    </button>
                                                </form>
                                                <form action="delete-demo.php" method="post" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
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
                        <form action="create-demo.php" method="get" style="display: inline;">
                            <button class="table-button-create" type="submit">Create</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <audio id="td-hover-audio" src="audio/div-hover.mp3"></audio>
            <script>
                function addHoverAudioToTableCells(tdSelector, audioId) {
                    const cells = document.querySelectorAll(tdSelector);
                    const audio = document.getElementById(audioId);

                    if (audio) {
                        cells.forEach(cell => {
                            cell.addEventListener('mouseenter', () => {
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
                    addHoverAudioToTableCells('td', 'td-hover-audio');
                });
            </script>
        </section>
    </main>
   
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
    <script>
        const canvas = document.getElementById('backgroundCanvas');
        const ctx = canvas.getContext('2d');

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const gradient = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
        gradient.addColorStop(0, 'blue');
        gradient.addColorStop(1, 'purple');
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        for (let i = 0; i < 50; i++) {
            ctx.beginPath();
            ctx.arc(Math.random() * canvas.width, Math.random() * canvas.height, Math.random() * 50, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(255, 255, 255, ${Math.random()})`;
            ctx.fill();
        }

        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            ctx.fillStyle = gradient;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
        });

        const hamburger = document.getElementById('hamburger');
        const navLinks = document.getElementById('nav-links');

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    </script>
</body>
</html>