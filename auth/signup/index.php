<?php
include('../includes/header.php');
include('../../config/dbcon.php');
?>

<!-- All Codes Should Be Written Here -->
<div class="card vh-100" style="max-width: 900vh;">
  <div class="row g-0">
   
    <div class="col-md-8" style="position:relative;">
      <div class="card-body">
        <h3 class="card-title mt-5" style="margin-left: 150px;">Create An Account</h3>
        <h6 class="card-title mt-4" style="margin-left: 150px;">All ready have an Account</h6>

        <div class="form-floating mb-3" style="margin-left: 150px;position:relative">
            <input type="text" class="form-control mt-5" style="width: 40vh;" id="givenCustomerName" name="name" placeholder="" required>
            <label for="givenCustomerName">First Name</label>
          </div>
          <div class="form-floating mb-3" style="margin-left: 450px;position:absolute; margin-top:-75px">
            <input type="text" class="form-control " style="width: 40vh;" id="givenCustomerName" name="name" placeholder="" required>
            <label for="givenCustomerName">Last Name</label>
          </div>

          <div class="form-floating mb-3" style="margin-left: 150px;">
            <input type="text" class="form-control mt-4" style="width: 83vh;" id="givenCustomerName" name="name" placeholder="" required>
            <label for="givenCustomerName">Email Address Or phone number</label>
          </div>

          <div class="form-floating mb-3" style="margin-left: 150px;">
            <input type="password" class="form-control mt-4" style="width: 83vh;" id="givenCustomerName" name="name" placeholder="" required>
            <label for="givenCustomerName">Password</label>
          </div> 

          <div class=" mb-3" style="margin-left: 150px; position:absolute">   
          <input type="checkbox"  id="btncheck1" autocomplete="off">
          <label  for="btncheck1">I agree to the trams and condition</label>
          </div>

          <button type="button" class="btn btn-success mt-5 p-2" style="width: 83vh; margin-left: 150px;">Create account</button>
     
      </div>
    </div>

    <div class="card" style="width: 40%; position:absolute; border-radius:30px;margin-left:60%;">
      <img src="../img/mm.avif" class="img-fluid rounded-start vh-100 rounded-5" style="border-radius:30px" alt="...">
    </div>

  </div>
</div>

<?php include('../includes/footer.php'); ?>