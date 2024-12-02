<?php include('../includes/header.php') ?>
<?php include('../includes/nav.php') ?>

<div class="d-flex flex-row bd-highlight mb-1">
  <div class="w-50 p-5 mt-5 shadow bg-body-tertiary m-lg-1">
    <form class="rounded-3">
      <div class="d-flex justify-content-center fw-bold">Customer Details</div>
      <input type="text" class="form-control mt-3" placeholder="Customer Name">
      <div class="my-4">
        <input type="text" class="form-control" placeholder="Customer Contact">
      </div>
      <textarea class="form-control mt-3" placeholder="Customer Address" id="floatingTextarea2" style="height: 100px"></textarea>
      <label for="floatingTextarea2"></label>
      <div class="d-flex justify-content-sm-evenly mt-3">
        <button type="button" class="btn btn-secondary">Cancel</button>
        <button type="button" class="btn btn-success">Create</button>
      </div>
    </form>
  </div>


  <!--right container-->

  <div class="w-50 p-5 mt-5 shadow bg-body-tertiary m-lg-1">
    <div class="d-flex justify-content-center mb-5 fw-bold">Saved Customers</div>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Sl.no</th>
          <th scope="col">Name</th>
          <th scope="col">Contact</th>
          <th scope="col">Address</th>
          <th scope="col">Manage</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td>t shirt</td>
          <td>100</td>
          <td>10.0</td>
          <td>10.0</td>
        </tr>
        <tr>
          <th scope="row">2</th>
          <td>shirt</td>
          <td>100</td>
          <td>20.0</td>
          <td>10.0</td>
        </tr>
        <tr>
          <th scope="row">3</th>
          <td>shirt</td>
          <td>1k</td>
          <td>11.0</td>
          <td>10.0</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<?php include('../includes/footer.php') ?>