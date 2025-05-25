<?php
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$darkmode_cookie_name = $username ? "darkmode_" . $username : null;
$dark_mode = $username && isset($_COOKIE[$darkmode_cookie_name]) && $_COOKIE[$darkmode_cookie_name] === 'on' ? 'on' : 'off';

$profile_link = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? 'profile-admin.php' : 'profile.php';
?>

<nav>
    <div class="row mobile-row">
        <div class="col-4 title">
            <div class="logo">
                <img id="logo" src="utils/algoverse_logo.svg" alt="AlgoVerse Academy Logo">
            </div>
            <h2><a href="index.php" id="algoverse">AlgoVerse Academy</a></h2>
        </div>
        <div class="col-8">
            <ul class="links" id="nav-links">
                <li><a href="about-us.php" <?= ($currentPage == 'about') ? 'id="about"' : '' ?>>About Us</a></li>
                <li><a href="courses.php" <?= ($currentPage == 'courses') ? 'id="courses"' : '' ?>>Our Courses</a></li>
                <li><a href="admission.php" <?= ($currentPage == 'admission') ? 'id="admission"' : '' ?>>Admissions</a></li>
                <li><a href="contact.php" <?= ($currentPage == 'contact') ? 'id="contact"' : '' ?>>Contact</a></li>

                <?php if (isset($_SESSION['username'])): ?>
                    <div class="profile-container">
                        <div class="profile-icon">
                            <span><?= strtoupper(substr($_SESSION['username'], 0, 2)) ?></span>
                        </div>
                        <div class="profile-dropdown">
                            <a href="<?= htmlspecialchars($profile_link) ?>" class="dropdown-item">
                                <i class="icon-user"></i> My Profile
                            </a>

                            <div class="toggle-item">
                                <span>Dark Mode</span>
                                <label class="theme-switch">
                                    <input type="checkbox" id="themeToggle" <?= $dark_mode === 'on' ? 'checked' : '' ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <!-- Mute Toggle -->
                            <div class="toggle-item">
                                <span>Mute Audio</span>
                                <label class="theme-switch">
                                    <input type="checkbox" id="muteToggle" <?= isset($_COOKIE['mute_' . $username]) && $_COOKIE['mute_' . $username] === 'on' ? 'checked' : '' ?>>
                                    <span class="slider"></span>
                                </label>
                            </div>

                            <div class="dropdown-divider"></div>

                            <a href="logout.php" class="dropdown-item logout">
                                <i class="icon-logout"></i> Log Out
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <li><a href="login.php" id="login">Log In</a></li>
                <?php endif; ?>

            </ul>
            <div class="hamburger" id="hamburger">
                <p>&#9776;</p>
            </div>
        </div>
    </div>
</nav>

<script>
    document.getElementById('themeToggle').addEventListener('change', function() {
        const isDarkMode = this.checked ? 'on' : 'off';
        const username = '<?php echo addslashes($username); ?>'; 
        const darkmodeCookieName = username ? "darkmode_" + username : null;

        if (darkmodeCookieName) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'set_darkmode.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    window.location.reload();
                }
            };
            xhr.send('darkmode=' + isDarkMode + '&cookie_name=' + encodeURIComponent(darkmodeCookieName));
        }
    });
</script>
<script>
    document.getElementById('muteToggle').addEventListener('change', function() {
    const isMuted = this.checked ? 'on' : 'off';
    const username = '<?php echo addslashes($username); ?>';
    const muteCookieName = username ? 'mute_' + username : null;
    const audioElements = document.querySelectorAll('audio');

    // Toggle muted attribute on all audio elements
    audioElements.forEach(audio => {
        audio.muted = this.checked;
    });

    if (muteCookieName) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'set_mute.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // No reload needed; mute state is applied instantly
            }
        };
        xhr.send('mute=' + isMuted + '&cookie_name=' + encodeURIComponent(muteCookieName));
    }
});

// Apply mute state on page load
document.addEventListener('DOMContentLoaded', function() {
    const muteToggle = document.getElementById('muteToggle');
    const audioElements = document.querySelectorAll('audio');
    if (muteToggle.checked) {
        audioElements.forEach(audio => {
            audio.muted = true;
        });
    }
});
</script>
<style>
    .profile-dropdown {
        position: absolute;
        right: 0;
        top: 100%;
        width: 200px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        padding: 8px 0;
        display: none;
        z-index: 100;
        font-family: 'Inter', sans-serif;
    }

    .profile-container:hover .profile-dropdown {
        display: block;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        padding: 10px 16px;
        color: #333;
        text-decoration: none;
        transition: background 0.2s;
    }

    .dropdown-item:hover {
        background: #f8f8f8;
    }

    .dropdown-item.logout {
        color: #e74c3c;
    }

    .dropdown-item i {
        margin-right: 10px;
        width: 18px;
    }

    .dropdown-divider {
        height: 1px;
        background: #eee;
        margin: 6px 0;
    }

    .toggle-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 16px;
    }

    .theme-switch {
        position: relative;
        display: inline-block;
        width: 42px;
        height: 22px;
    }

    .theme-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #ddd;
        transition: .3s;
        border-radius: 22px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 3px;
        bottom: 3px;
        background: white;
        transition: .3s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background: #4CAF50;
    }

    input:checked+.slider:before {
        transform: translateX(20px);
    }

    body.dark-mode .profile-dropdown {
        background: #2d3436;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
    }

    body.dark-mode .dropdown-item {
        color: #f0f0f0;
    }

    body.dark-mode .dropdown-item:hover {
        background: #3d4548;
    }

    body.dark-mode .dropdown-divider {
        background: #3d4548;
    }
    .toggle-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 16px;
}
.theme-switch {
    position: relative;
    display: inline-block;
    width: 42px;
    height: 22px;
}
.theme-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #ddd;
    transition: .3s;
    border-radius: 22px;
}
.slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 3px;
    bottom: 3px;
    background: white;
    transition: .3s;
    border-radius: 50%;
}
input:checked+.slider {
    background: #4CAF50;
}
input:checked+.slider:before {
    transform: translateX(20px);
}
</style>