<?php
include("includes/header.php");
include("includes/nav.php");
include("../config/dbcon.php");
include ('../auth/auth_check_admin.php');
?>

<!-- left-box -->
<div class="row">
<h5 class="modal-title fs-4" id="exampleModalLabel">Admin Details</h5>
  <div class="col-sm-6 mb-3 mb-sm-0 w-25">
    <div class="card">
      <div class="card-body">

      <h1 class="modal-title fs-6" id="exampleModalLabel">Change User Name</h1>
      <div class="form-floating w-80">
            <input type="text" id="Name" class="form-control mt-3" placeholder="Customer Name">
            <label for="text">Name</label>
        </div>
        <div class="form-floating w-80  top-0 end-0 mt-3">
            <input type="text" id="Name" class="form-control" placeholder="text">
            <label for="text">Last Name</label>
        </div>

        <h1 class="modal-title fs-6 mt-4" id="exampleModalLabel">Change phone number</h1>
      <div class="form-floating w-80">
            <input type="number" id="number" class="form-control mt-3" placeholder="number">
            <label for="number">phone number</label>
        </div>

        <h1 class="modal-title fs-6 mt-3" id="exampleModalLabel">Change Email Address</h1>
      <div class="form-floating w-80">
            <input type="text" id="email" class="form-control mt-3" placeholder="email">
            <label for="email">Email</label>
        </div>

        <h1 class="modal-title fs-6 mt-4" id="exampleModalLabel">Change Password</h1>
      <div class="form-floating w-80">
            <input type="password" id="password" class="form-control mt-3" placeholder="password">
            <label for="password"> New Password</label>
        </div>

      
      <div class="form-floating w-80">
            <input type="password" id="password" class="form-control mt-3" placeholder="password">
            <label for="password">confirm Password</label>
        </div>


    
      <button type="button" class="btn btn-success w-80 mt-3">Save</button>
        <button type="button" class="btn btn-outline-danger w-80 mt-3" data-bs-dismiss="modal">Cancel</button>




      </div>
    </div>
  </div>

<!-- right-box -->
  <div class="col-sm-6 w-75">
    <div class="card">
      <div class="card-body vh-100">
      
      <table class="table">
  <thead>
    <tr>
      <th scope="col">SL.NO</th>
      <th scope="col">Name</th>
      <th scope="col">Contact</th>
      <th scope="col">Email</th>
      <th scope="col">Address</th>
      <th scope="col">Manage</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Minu</td>
      <td>1234567890</td>
      <td>T jirania</td>
      <td>*****</td>
      <td><i class="bi bi-pen mx-1 edit-quantity-icon" style="cursor: pointer;"></i></td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>gandu</td>
      <td>244353636</td>
      <td>road 10</td>
      <td>*****</td>
      <td><i class="bi bi-pen mx-1 edit-quantity-icon" style="cursor: pointer;"></i></td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td>land</td>
      <td>4636346</td>
      <td>fhdfghdfg</td>
      <td>*****</td>
      <td><i class="bi bi-pen mx-1 edit-quantity-icon" style="cursor: pointer;"></i></td>
    </tr>
  </tbody>
</table>




      </div>
    </div>
  </div>
</div>

<?php include("includes/footer.php"); ?>