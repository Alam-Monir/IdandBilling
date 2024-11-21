<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Billing</title>
</head>

    <body>
      <?php include "includes/nav.php" ?>
      <?php include "style.css" ?>
      <section class="container">
        <div class="nav">
          <div class="box">
            <a href="#">INVOICE</a>
            <a href="#">EDIT ITEM</a>
            <a href="#">EDIT SELLER INFO</a>
            <a href="#">CUSTOMER DETAIL</a>
          </div>
        </div>
      </section>
      <section class="container">
        <div class="left">
          <input type="text" name="username" placeholder="Customer Name">
          <input type="text" name="Customer" placeholder="Customer Detail">
          <input list="item-options" id="item" name="item" placeholder="Item Detail">
          <datalist id="item-options">
            <option value="land">
            <option value="land">
            <option value="land">
            <option value="land">
            <option value="land">
          </datalist>
          <input type="text" name="Quantity" placeholder="Quantity">

        </div>
      </section>

      <section class="container">
        <div class="right">

        </div>
      </section>


    </body>

</html>