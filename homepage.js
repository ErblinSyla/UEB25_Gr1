document.addEventListener("DOMContentLoaded", () => {
    const hamburger = document.getElementById("hamburger");
    const navLinks = document.getElementById("nav-links");

    // Toggle the menu visibility on click
    hamburger.addEventListener("click", () => {
        navLinks.classList.toggle("active");
    });
});
document.addEventListener('DOMContentLoaded', () => {
    // Get the current URL
    const currentPage = window.location.href;

    // Get all anchor tags in the navigation
    const navLinks = document.querySelectorAll('.links a');

    // Check if the current URL matches any of the link URLs
    navLinks.forEach(link => {
        if (currentPage.match(link.href)) {
            // Add an active class to highlight the current page
            link.classList.add('active');
        }
    });
});


document.addEventListener("DOMContentLoaded", () => {
    const instructorsSection = document.querySelector('.instructors');
    const instructorElements = document.querySelectorAll('.instructor-1, .instructor-2, .instructor-3');

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1'; // Make visible
                entry.target.style.transform = 'translateY(0)'; // Reset position
                observer.unobserve(entry.target); // Stop observing once animated
            }
        });
    }, {
        threshold: 0.1 // Trigger when 10% of the element is visible
    });

    // Observe each instructor element
    observer.observe(instructorsSection);
    instructorElements.forEach(element => observer.observe(element));
});
document.addEventListener("DOMContentLoaded", () => {
    const elementsToAnimate = document.querySelectorAll(
        '.projects-main,.projects, .calculator-project, .todo-list-project, .weather-project, .flexible-learning, .flexible-title h2, .flexible-title p, .flexible-options li,#flexible-image,#quote,.form-div,.form-div h3,.form-div h4,.form-div input,.form-div #submit-input'
    );

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1'; // Make visible
                entry.target.style.transform = 'translateY(0)'; // Reset position
                observer.unobserve(entry.target); // Stop observing
            }
        });
    }, {
        threshold: 0.1 // Trigger when 10% of the element is visible
    });

    elementsToAnimate.forEach(element => observer.observe(element));
});


