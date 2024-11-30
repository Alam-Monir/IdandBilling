<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Billing</title>
  <link rel="stylesheet" href="style.css">
</head>

    <body>
      <?php include "includes/nav.php" ?>
     
      <section class="container1">
        <div class="left">
          <input type="text" name="username" placeholder="Customer Name">
          <input type="text" name="Customer" placeholder="Customer Detail">
          <input list="item-options" id="item" name="item" placeholder="Item Detail">
          <datalist id="item-options">
            <option value="kk">
            <option value="land">
            <option value="land">
            <option value="land">
            <option value="land">
          </datalist>
          <input type="text" name="Quantity" placeholder="Quantity">

        </div>
      </section>

      <section class="container1">
        <div class="right">
          <p>land</p>
        </div>
      </section>


    </body>

</html>