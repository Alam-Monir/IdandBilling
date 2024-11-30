<?php include('../includes/header.php') ?>
<?php include('../includes/nav.php') ?>
<style>
 
</style>
<div class="d-flex flex-row bd-highlight mb-1">
<div class="w-50 p-5 mt-5 shadow bg-body-tertiary m-lg-1">
  <form class="rounded-3">
    <div class="d-flex justify-content-center">Edit Items</div>
    <div class="mb-3 m-lg-1" style="margin: auto 15px auto 15px;">
      <label for="item-Name" class="form-label" style="margin: 15px ;">Create New Item</label>
      <input type="text" class="form-control" placeholder="Item Name">
    </div>
    <div class="mt-4">
      <input type="number" class="form-control ml-1" id="numberInput" step="0" placeholder="Item Price">
    </div>
    <div class="mb-3 form-check">
    </div>
    <div class="mb-4">
      <button class="btn btn-success m-lg-2">Save</button>
    </div>

    
    <label for="item-Name" class="form-label" style="margin: 15px;" ;>Delete Item</label>
    <input type="text" class="form-control" placeholder="Enter Item Name">
    <div class="mt-3">
    <button class="btn btn-danger m-lg-2">Delete</button>
    </div>

  </form>
</div>


<!--right container-->

<div class="w-50 p-5 mt-5 shadow bg-body-tertiary m-lg-1">  
<div class="d-flex justify-content-center mb-5">Items</div> 
<table class="table">
  <thead>
    <tr>
      <th scope="col">Sl.no</th>
      <th scope="col">Item name</th>
      <th scope="col">Price</th>
      <th scope="col">Date Created</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>t shirt</td>
      <td>100</td>
      <td>10.0</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>shirt</td>
      <td>100</td>
      <td>20.0</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td>shirt</td>
      <td>1k</td>
      <td>11.0</td>
    </tr>
  </tbody>
</table>
</div>
</div>

<?php include('../includes/footer.php') ?>