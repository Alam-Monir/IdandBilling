<script src="/idandbilling/assets/js/bootstrap.bundle.min.js"></script>
<script src="/idandbilling/assets/js/jquery-3.7.1.min.js"></script>
<script>
    
const navLinks = document.querySelectorAll('.nav-link'); 


navLinks.forEach(link => {
    link.addEventListener('click', () => {
       
        navLinks.forEach(link => link.classList.remove('active'));

        
        link.classList.add('active');
    });
});

</script>
</body>

</html>