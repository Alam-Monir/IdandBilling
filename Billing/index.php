<?php
include('includes/header.php');
include('includes/nav.php');
?>

<div class="d-flex mt-3">

    <div class="w-40 p-5 ml-5 shadow bg-body-tertiary" style="height: 100vh;">
        <div class="d-flex justify-content-center fw-bold">Customer Details</div>
        <input type="text" id="sellerName" class="form-control mt-5" placeholder="Customer Name">
        <input type="text" id="sellerName" class="form-control mt-4" placeholder="contact">
        <input type="text" id="sellerName" class="form-control mt-4" placeholder="items">

        <div class="d-flex mt-3 grid gap-0 column-gap-3">
            <button type="button" class="btn btn-danger ">Cancel</button>
            <button type="button" class="btn btn-success">Export</button>
        </div>
    </div>

    <div class="w-100 shadow bg-body-tertiary">
    <div class="d-flex justify-content-center fw-bold mb-5">Bill To Chokheleng </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Sl.no</th>
                    <th scope="col">Item Name</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price/Unit</th>
                    <th scope="col">GST</th>
                    <th scope="col">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Mimu</td>
                    <td>nishan</td>
                    <td>land</td>
                    <td>shafru</td>
                    <td>bishal</td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                    <td>land</td>
                    <td>land</td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td colspan="2">Larry the Bird</td>
                    <td>@twitter</td>
                    <td>land</td>
                    <td>land</td>
                </tr>
            </tbody>
        </table>
    </div>


</div>
<?php include('includes/footer.php') ?>