<?php
include('../includes/header.php');
include('../../config/dbcon.php');
?>

<!-- All Codes Should Be Written Here -->
 <div class=" vh-100">
  <div class="row g-0">
    <div class="card" style="width: 45%; position:absolute;border-radius:30px;">
      <img src="../img/hj.avif" class="img-fluid rounded-start vh-100">
    </div>
    <div class="col-md-8" style="position:relative;margin-left:45%;border-radius:30px">
      

      <div class="card  vh-100 border border-3" style="align-items:center; width:100%;">
  <form action="login.php" method="POST">
    <div class="mb-3 mt-5">
      <h3 class="me-5 mb-5" style="margin-top:30px">Login To Your Account</h3>
      <label for="email" class="form-label">Email address</label>
      <input type="email" class="form-control w-300" id="email" name="email" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    <button type="button" class="btn btn-outline-success" style="margin-left: 200px;">Create Account</button>
  </form>
</div>
     
    </div>
  </div>
</div> 

<!-- <div class="card mt-5 vh-50 border border-3  p-5 " style="align-items:center">
  <form action="login.php" method="POST">
    <div class="mb-3">
      <h3 class="me-5 mb-5" style="margin-top:30px">Login To Your Account</h3>
      <label for="email" class="form-label">Email address</label>
      <input type="email" class="form-control w-300" id="email" name="email" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    <button type="button" class="btn btn-outline-success" style="margin-left: 200px;">Create Account</button>
  </form>
</div> -->
<?php include('../includes/footer.php'); ?>