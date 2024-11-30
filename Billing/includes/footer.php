<script src="/idandbilling/assets/js/bootstrap.bundle.min.js"></script>
<script src="/idandbilling/assets/js/jquery-3.7.1.min.js"></script>
<script>
    // script.js
const navLinks = document.querySelectorAll('.nav-link'); // Select all navbar links

// Add click event listener to each link
navLinks.forEach(link => {
    link.addEventListener('click', () => {
        // Remove the active class from all links
        navLinks.forEach(link => link.classList.remove('active'));

        // Add active class to the clicked link
        link.classList.add('active');
    });
});

</script>
</body>

</html>