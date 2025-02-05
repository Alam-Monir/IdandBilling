<?php
include('../includes/header.php');
include('../../config/dbcon.php');
?>

<!-- All Codes Should Be Written Here -->
<div class="card vh-100" style="max-width: 900vh; border-radius:30px">
  <div class="row g-0">
    <div class="card" style="width: 40%; position:absolute;border-radius:30px">
      <img src="../img/hj.avif" class="img-fluid rounded-start vh-100" style="width: 100rem;border-radius:30px" alt="...">
    </div>
    <div class="col-md-8" style="position:relative;margin-left:40%;border-radius:30px">
      <div class="card-body">
        <h3 class="" style="padding-left:385px;margin-top:30px">Welcome Back</h3>
        <h6 class="" style="padding-left:370px; margin-top:30px">Please login to your account</h6>

        <!-- input field -->
        <div class="form-floating mb-3" style="margin-left: 200px;">
            <input type="text" class="form-control mt-5" style="width: 80vh;" id="givenCustomerName" name="name" placeholder="" required>
            <label for="givenCustomerName">Email Address</label>
          </div>

          <div class="form-floating mb-3" style="margin-left: 200px;">
            <input type="password" class="form-control mt-5" style="width: 80vh;" id="givenCustomerName" name="name" placeholder="" required>
            <label for="givenCustomerName">Password</label>
          </div>  

          <div class=" mb-3" style="margin-left: 200px; position:absolute">   
          <input type="checkbox"  id="btncheck1" autocomplete="off">
          <label  for="btncheck1">Remember Me</label>
          </div>
          
          <div class=" mb-3" style="margin-left: 92vh; color:red; position:relative"> 
            <p>forgot Password</p>
          </div>

     
          <button type="button" class="btn btn-success mt-5 p-2" style="width: 150px; margin-left: 200px;">Login</button>

          <button type="button" class="btn btn-outline-success mt-5 p-2"style="width: 150px; margin-left: 250px;">Create Account</button>
      

      </div>
    </div>
  </div>
</div>

<?php include('../includes/footer.php'); ?>