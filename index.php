<?php include "auth/auth_check_staff.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Id and Billing</title>
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="/idandbilling/assets/css/bootstrap-icons.min.css" rel="stylesheet" />
</head>
<style>
  .color-text {
    background: linear-gradient(to right, green, black, red, blue, yellow);
    -webkit-background-clip: text;
    color: transparent;
  }

  .color-text {
    margin-left: 70vh;
    position: absolute;
  }
  .container1{
    background-color: rgb(210, 210, 210);
  }
</style>

<body>
  <div>
    <!--nav-->
    <section class="container1">
      <div class="nav1">
        <h1 class="color-text">CHOKELENG GRAPHICS</h1>


        <!-- Button trigger modal -->
        <button type="button" class="btn" ; data-bs-toggle="modal" data-bs-target="#exampleModal">
          <i class="bi bi-list fs-2"></i>
        </button>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">

              <!-- Settings-logout -->

              <a href="auth/logout.php">
                <button class="btn btn-danger p-2 position-absolute top-0 end-0">Logout <i class="bi bi-box-arrow-right"></i></button>
              </a>
              <a href="settings/">
                <button class="btn btn-secondary position-absolute top-0 end-90">Settings <i class="bi bi-gear"></i></button>
              </a>


            </div>
          </div>
        </div>
      </div>
  </div>
  </section>

  <!-- id -->
  <div class="card position-absolute  bg-body-tertiary h-100 w-50 d-flex justify-content-center" style="background-image: url(./Billing/image/id2.png); background-position: center; background-size:100%; ">
    <a href="Id/">
      <button type="button" class="btn btn-outline-light btn-lg px-5 mt-3" style="margin-left: 30vh;">
        Id <i class="bi bi-person-badge"></i>
      </button>
    </a>
  </div>

  <!--BILLING-->
  <div class="card position-absolute end-0 bg-body-tertiary h-100 w-50 d-flex justify-content-center" style="background-image: url(./Billing/image/billing.png); background-position: center; background-size:100%; ">
    <a href="Billing/">
      <button type="button" class="btn btn-outline-secondary btn-lg px-5" style="margin-left: 40vh;color:black;">
        Billing <i class="bi bi-receipt-cutoff"></i>
      </button>
  </div>



 

  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/jquery-3.7.1.min.js"></script>
</body>

</html>