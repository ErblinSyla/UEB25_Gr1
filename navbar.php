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
                <li><a href="index.php" <?= ($currentPage == 'homepage') ? 'id="homepage"' : '' ?>>Homepage</a></li>
                <li><a href="about-us.php" <?= ($currentPage == 'about') ? 'id="about"' : '' ?>>About Us</a></li>
                <li><a href="courses.php" <?= ($currentPage == 'courses') ? 'id="courses"' : '' ?>>Our Courses</a></li>
                <li><a href="admission.php" <?= ($currentPage == 'admission') ? 'id="admission"' : '' ?>>Admissions</a></li>
                <li><a href="contact.php" <?= ($currentPage == 'contact') ? 'id="contact"' : '' ?>>Contact</a></li>
                <?php if (isset($_SESSION['username'])): ?>
                    <li><a href="logout.php" id="logout">Log Out</a></li>
                <?php endif; ?>
            </ul>
            <div class="hamburger" id="hamburger">
                <p>&#9776;</p>
            </div>
        </div>
    </div>
</nav>
