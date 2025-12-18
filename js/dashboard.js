const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');

function toggleMenu(){
  sidebar.classList.toggle('active');
  overlay.classList.toggle('active');
}

// Close sidebar when clicking on overlay
overlay.addEventListener('click', function() {
  toggleMenu();
});

// Close sidebar when clicking on a link
document.querySelectorAll('.sidebar a').forEach(link => {
  link.addEventListener('click', function() {
    toggleMenu();
  });
});