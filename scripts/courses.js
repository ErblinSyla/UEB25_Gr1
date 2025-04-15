document.addEventListener('DOMContentLoaded', () => {
  const currentPage = window.location.href;

  const navLinks = document.querySelectorAll('.links a');

  navLinks.forEach(link => {
      if (currentPage.match(link.href)) {
          link.classList.add('active');
      }
  });
});

document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("applyModal");
    const applyButton = document.getElementById("applyButton");
    const closeModal = document.getElementById("closeModal");
  
    applyButton.onclick = function () {
      modal.style.display = "block";
    };
  
    closeModal.onclick = function () {
      modal.style.display = "none";
    };
  
    window.onclick = function (event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    };
  
    const applyForm = document.getElementById("applyForm");
    applyForm.onsubmit = function (event) {
      event.preventDefault();
      alert("Application submitted!");
      modal.style.display = "none";
    };
  });