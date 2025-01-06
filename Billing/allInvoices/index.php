<?php
include('../includes/header.php');
include('../includes/nav.php');
include('../../config/dbcon.php')
?>

<div class="w-100 p-5 mt-5 shadow bg-body-tertiary m-lg-1">
    <div class="d-flex justify-content-around align-items-center mb-4">
        <h3 class="fw-bold">All Invoices</h3>
        <div class="d-flex align-items-center">
            <input type="text" class="form-control rounded-5 me-2" name="searchInvoice" id="searchInvoice" placeholder="Search">
            <button class="btn btn-outline-success rounded-5">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">Invoice Number</th>
                <th scope="col">Name</th>
                <th scope="col">Contact</th>
                <th scope="col">Address</th>
                <th scope="col">Invoice Date</th>
                <th scope="col">Delivery Date</th>
                <th scope="col">Manage</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>123abc</th>
                <td>Land Debbarma</td>
                <td>113456</td>
                <td>banikya chowmuhani, khayerpur, agartala, west tripura, 799008</td>
                <td>02/12/2024</td>
                <td>12/12/2024</td>
                <td><a href="#"><i class="bi bi-pen px-2"></i></a><a href="#"><i class="bi bi-trash"></i></a></td>
            </tr>
        </tbody>
    </table>
</div>


<?php include('../includes/footer.php') ?>