<?php include('../includes/header.php') ?>
<?php include('../includes/nav.php') ?>

<div class="d-flex flex-row bd-highlight mb-1">
  <div class="w-50 p-5 mt-5 shadow bg-body-tertiary m-lg-1">
    <form class="rounded-3">
      <div class="d-flex justify-content-center fw-bold">Edit Items</div>
      <div class="mb-3 m-lg-1" style="margin: auto 15px auto 15px;">
        <label for="item-Name" class="form-label" style="margin: 15px ;">Create New Item</label>
        <input type="text" class="form-control" placeholder="Item Name">
      </div>
      <div class="mt-4">
        <input type="number" class="form-control ml-1" id="numberInput" step="0" placeholder="Item Price">
      </div>
      <div class="mt-4">
        <input type="number" class="form-control ml-1" id="quantityInput" step="0" placeholder="Item Quantity">
      </div>
      <div class="mb-3 form-check">
      </div>
      <div class="mb-4">
        <button class="btn btn-success m-lg-2">Save</button>
      </div>

      <label for="item-Name" class="form-label" style="margin: 15px;">Delete Item</label>
      <input type="text" class="form-control" placeholder="Enter Item Name">
      <div class="mt-3">
        <button class="btn btn-danger m-lg-2">Delete</button>
      </div>
    </form>
  </div>


  <!--right table container-->

  <div class="w-50 p-5 mt-5 shadow bg-body-tertiary m-lg-1">
    <div class="d-flex justify-content-center mb-5 fw-bold">Items</div>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Sl.no</th>
          <th scope="col">Name</th>
          <th scope="col">Price</th>
          <th scope="col">Quantity</th>
          <th scope="col">Manage</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td>t shirt</td>
          <td>100</td>
          <td>10.0</td>
          <td><a href="#"><i class="bi bi-pen px-2"></i></a><a href="#"><i class="bi bi-trash"></i></a></td>
        </tr>
        <tr>
          <th scope="row">2</th>
          <td>shirt</td>
          <td>100</td>
          <td>20.0</td>
          <td><i class="bi bi-pen px-2"></i><i class="bi bi-trash"></i></td>
        </tr>
        <tr>
          <th scope="row">3</th>
          <td>shirt</td>
          <td>1k</td>
          <td>11.0</td>
          <td><i class="bi bi-pen px-2"></i><i class="bi bi-trash"></i></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<?php include('../includes/footer.php') ?>