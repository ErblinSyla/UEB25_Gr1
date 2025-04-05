document.addEventListener("DOMContentLoaded", () => {
    const hamburger = document.getElementById("hamburger");
    const navLinks = document.getElementById("nav-links");

    hamburger.addEventListener("click", () => {
        navLinks.classList.toggle("active");
    });
});
document.addEventListener('DOMContentLoaded', () => {
    const currentPage = window.location.href;
    const navLinks = document.querySelectorAll('.links a');

    navLinks.forEach(link => {
        if (currentPage.match(link.href)) {
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
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });

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
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });

    elementsToAnimate.forEach(element => observer.observe(element));
});


