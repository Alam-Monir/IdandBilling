<?php include('../includes/header.php') ?>
<?php include('../includes/nav.php') ?>

<div class="d-flex justify-content-center mt-3">
<div class="w-50 p-5 ml-5 shadow bg-body-tertiary">
<form class="rounded-3">
<div class="d-flex justify-content-center fw-bold">Edit Seller Info</div>
<input type="text" class="form-control mt-5" placeholder="Seller Name">
<input type="text" class="form-control mt-3" placeholder="Seller Contact">
<input type="text" class="form-control mt-3" placeholder="GST No">
<div class="form-floating">
  <textarea class="form-control mt-3" placeholder="Seller Address" id="floatingTextarea2" style="height: 100px"></textarea>
  <label for="floatingTextarea2">Seller Address</label>
  <div class="d-flex justify-content-sm-evenly mt-3">
  <button type="button" class="btn btn-danger">Cancel</button>
  <button type="button" class="btn btn-primary">Save</button>
  </div>
</div>
</form>
</div>
</div>







<?php include('../includes/footer.php') ?>