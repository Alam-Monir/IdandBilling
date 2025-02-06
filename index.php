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

<body>
  <div>
    <!-- id -->
    <div class="card position-absolute  bg-body-tertiary h-100 w-50 d-flex justify-content-center">
      <a href="Id/">
        <button type="button" class="btn btn-primary btn-lg px-5" style="margin-left: 40vh;">
          Id <i class="bi bi-person-badge"></i>
        </button>
      </a>
    </div>

    <!--BILLING-->
    <div class="card position-absolute end-0 bg-body-tertiary h-100 w-50 d-flex justify-content-center">
      <a href="Billing/">
        <button type="button" class="btn btn-primary btn-lg px-5" style="margin-left: 40vh;">
          Billing <i class="bi bi-receipt-cutoff"></i>
        </button>

        <!-- Settings-logout -->
         
        <a href="auth/logout.php">
          <button class="btn btn-danger p-2 position-absolute top-0 end-0">Logout <i class="bi bi-box-arrow-right"></i></button>
        </a>
        <a href="settings/">
          <button class="btn btn-secondary position-absolute top-0 end-90">Settings <i class="bi bi-gear"></i></button>
        </a>
    </div>

  </div>

  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/jquery-3.7.1.min.js"></script>
</body>

</html>