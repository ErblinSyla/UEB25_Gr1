<footer style="background-color:#f8f9fa; padding: 20px 0; margin-top: 40px;">
    <div class="row text-center">
        <div class="col-12">
            <p style="margin-top: 15px; font-style: italic; color: #555;">
                <?php
                    // Fetch programming joke from jokeapi.dev
                    $jokeApiUrl = "https://v2.jokeapi.dev/joke/Programming?type=single";
                    $response = @file_get_contents($jokeApiUrl);
                    if ($response !== false) {
                        $data = json_decode($response, true);
                        if (isset($data['joke'])) {
                            echo "💡 Joke of the moment: " . htmlspecialchars($data['joke']);
                        } else {
                            echo "💡 Can't fetch a joke right now. Try again later!";
                        }
                    } else {
                        echo "💡 Can't fetch a joke right now. Try again later!";
                    }
                ?>
            </p>
            <p>
                &copy; <span><?php echo date("Y"); ?></span> 
                <strong>AlgoVerse Academy</strong>. All rights reserved. 🌐
            </p>
            <p>
                <a href="/privacy-policy.php">Privacy Policy</a> |
                <a href="/terms-of-service.php">Terms of Service</a> |
                <a href="/contact.php">Contact</a>
            </p>
            <p>
                Follow us: 
                <a href="https://facebook.com/AlgoVerseAcademy" target="_blank">Facebook</a> • 
                <a href="https://instagram.com/AlgoVerseAcademy" target="_blank">Instagram</a> • 
                <a href="https://linkedin.com/company/AlgoVerseAcademy" target="_blank">LinkedIn</a>
            </p>
        </div>
    </div>
</footer>
