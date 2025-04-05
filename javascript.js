document.addEventListener('DOMContentLoaded', () => {
    const currentPage = window.location.href;
    const navLinks = document.querySelectorAll('.links a');
    navLinks.forEach(link => {
        if (currentPage.match(link.href)) {
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

