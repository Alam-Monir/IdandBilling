<style>
  * {
    margin: 0;
    box-sizing: border-box;
  }

  a {
    text-decoration: none;
    color: black;
  }

  .nav1 {
    margin-top: 5px;
    background: #fff;
    padding: 30px 200px;
    border: solid black 3px;
    justify-content: space-between;
  }

  .navbar a {
    border-radius: 10px;
    border: solid black;
    padding: 5px;
  }

  .navbar a:hover {
    background-color: black;
    color: #fff;
  }

  .navbar {
    display: flex;
    font-weight: 600;
    justify-content: space-between;
    gap: 30px;
    font-weight: bold;
    color: black;
    list-style-type: none;
    padding: 0;
    margin: 0;
    display: flex;
  }

  .navbar li {
    margin: 0 15px;
  }

  .navbar a {
    color: black;
    text-decoration: none;
    padding: 15px;
    display: block;
  }

  .nav-link.active {
    background-color: black;
    color: white;
  }
</style>
<section class="container1">
  <div class="nav1">
    <ul class="navbar">
      <li><a href="/idandbilling/" class="nav-link"><i class="bi bi-house"></i> Home</a></li>
      <li><a href="/idandbilling/billing" class="nav-link">INVOICE</a></li>
      <li><a href="/idandbilling/billing/editItems/" class="nav-link">ITEMS</a></li>
      <li><a href="/idandbilling/billing/editsellerinfo/" class="nav-link">SELLER INFO</a></li>
      <li><a href="/idandbilling/billing/customerdetails/" class="nav-link">CUSTOMER DETAILS</a></li>
    </ul>
  </div>
</section>

<script>
  // Get the current URL path
  const currentPath = window.location.pathname.replace(/\/$/, ""); // Remove trailing slash if present

  // Select all nav links
  const navLinks = document.querySelectorAll(".nav-link");

  // Check the condition for setting the active class
  navLinks.forEach(link => {
    const href = link.getAttribute("href").replace(/\/$/, ""); // Remove trailing slash for consistency

    // Set active if navigating to "/idandbilling/billing/"
    if (href === currentPath || (currentPath === "/idandbilling" && href === "/idandbilling/billing")) {
      // Remove active class from all links first
      navLinks.forEach(l => l.classList.remove("active"));
      
      // Add active class to the current link
      link.classList.add("active");
    }
  });
</script>


