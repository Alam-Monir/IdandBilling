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

  /* .navbar {
    display: flex;
    font-weight: 600;
    justify-content: space-between;
    gap: 30px;
  } */

  .navbar a {
    border-radius: 10px;
    border: solid black;
    padding: 5px;
  }

  .navbar a:hover {
    background-color: black;
    color: #fff;
  }

  /* 
  .navbar .active {
    background-color: red;
    color: #fff;
  } */

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


  .navbar .active {
    background-color: red;
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