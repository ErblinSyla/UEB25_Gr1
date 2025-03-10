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







function reveal() {
    var reveals = document.querySelectorAll('.reveal');
    
    for(var i = 0; i < reveals.length; i++) {
        
        var windowheight = window.innerHeight;
        var revealtop = reveals[i].getBoundingClientRect().top;
        var revealpoint = 150;
        
        if(revealtop < windowheight - revealpoint) {
            reveals[i].classList.add('active');
        }
    }   
}

function revealLeft() {
    var reveals = document.querySelectorAll('.reveal-left');
    
    for(var i = 0; i < reveals.length; i++) {
        
        var windowheight = window.innerHeight;
        var revealtop = reveals[i].getBoundingClientRect().top;
        var revealpoint = 150;
        
        if(revealtop < windowheight - revealpoint) {
            reveals[i].classList.add('active');
        }
    }   
}

function revealRight() {
    var reveals = document.querySelectorAll('.reveal-right');
    
    for(var i = 0; i < reveals.length; i++) {
        
        var windowheight = window.innerHeight;
        var revealtop = reveals[i].getBoundingClientRect().top;
        var revealpoint = 150;
        
        if(revealtop < windowheight - revealpoint) {
            reveals[i].classList.add('active');
        }
    }   
}

function formFilled() {
    const form = document.getElementById("comment-input");
    if(form.value.trim() == '') {
        alert("Please leave a comment first!");
    } else {
        alert("The review has successfully been published!");
        form.value = "";
    }
}

