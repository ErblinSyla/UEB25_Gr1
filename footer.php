<footer style="background-color:#f8f9fa; padding: 20px 0; margin-top: 40px;">
    <div class="row text-center">
        <div class="col-12">
            <p id="programming-joke" style="margin-top: 15px; font-style: italic; color: #555;">
                Loading joke...
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
<script>
async function fetchJoke() {
    try {
        const response = await fetch('get-joke.php');
        if (!response.ok) throw new Error('Network error');
        const data = await response.json();
        document.getElementById('programming-joke').textContent = "💡 Joke of the moment: " + data.joke;
    } catch (err) {
        document.getElementById('programming-joke').textContent = "💡 Can't fetch a joke right now. Try again later!";
    }
}


fetchJoke();


setInterval(fetchJoke, 20000);// Fetch a new joke every 30 seconds
</script>