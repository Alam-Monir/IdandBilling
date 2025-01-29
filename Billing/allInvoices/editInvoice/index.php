<?php
include('../../includes/header.php');
include('../../includes/nav.php');
include('../../../config/dbcon.php');

$invoiceId = isset($_GET['invoiceId']) ? $_GET['invoiceId'] : '';

$invoice = null;
$invoiceItems = [];

$gstOptions = [
    1 => 'No GST',
    2 => '5%',
    3 => '12%',
    4 => '18%',
    5 => '33%'
];

if ($invoiceId > 0) {
    try {
        $query = "
            SELECT 
                invoices.invoiceDate, 
                invoices.deliveryDate, 
                invoices.gstPercentage, 
                customers.customerName, 
                customers.customerContact, 
                customers.customerAddress, 
                invoices.state
            FROM 
                invoices
            INNER JOIN 
                customers 
            ON 
                invoices.customerId = customers.customerId
            WHERE 
                invoices.invoiceId = :invoiceId";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':invoiceId', $invoiceId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $invoice = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "<script>alert('Invoice not found');</script>";
        }

        // Query to fetch invoice items
        $itemQuery = "SELECT * FROM invoiceItems WHERE invoiceId = :invoiceId";
        $itemStmt = $pdo->prepare($itemQuery);
        $itemStmt->bindParam(':invoiceId', $invoiceId, PDO::PARAM_INT);
        $itemStmt->execute();

        $invoiceItems = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<script>alert('Database error: " . $e->getMessage() . "');</script>";
    }
} else {
    echo "<script>alert('Invalid invoice ID');</script>";
}
?>
<style>
    .CSS i {
        visibility: hidden;
        opacity: 0;
        transition: visibility 0s, opacity 0.3s;
    }

    .CSS:hover i {
        visibility: visible;
        opacity: 1;
    }
</style>

<!-- Edit Invoice Form -->
<div class="card position-absolute start-0 bg-body-tertiary h-100" style="width:35%;">
    <div class="card d-flex align-items-center h-100">
        <h4 class="pt-4">Edit Invoice</h4>
        <!-- Invoice form inputs -->
        <div class="form-floating w-50 mt-4">
            <input type="date" id="invoiceDate" class="form-control mt-3" placeholder="Invoice Date">
            <label for="invoiceDate">Invoice Date</label>
        </div>
        <div class="form-floating w-50">
            <input type="text" id="customerName" class="form-control mt-3" placeholder="Customer Name">
            <label for="customerName">Customer Name</label>
        </div>
        <div class="form-floating w-50">
            <input type="text" id="customerAddress" class="form-control mt-3" placeholder="Customer Address">
            <label for="customerAddress">Customer Address</label>
        </div>
        <div class="form-floating w-50">
            <input type="text" id="customerContact" class="form-control mt-3" placeholder="Customer Contact" pattern="\d{10}" title="Please enter 10 digits">
            <label for="customerContact">Customer Contact</label>
        </div>

        <div class="input-group mb-2 w-50 mt-3">
            <select class="form-select" id="gstSelector">
                <option disabled selected>Select GST</option>
                <option value="1">No GST</option>
                <option value="2">5%</option>
                <option value="3">12%</option>
                <option value="4">18%</option>
                <option value="5">33%</option>
            </select>
        </div>

        <div class="form-floating w-50">
            <input type="text" id="Items" class="form-control mt-3" placeholder="Items" value="<?= htmlspecialchars($invoice['items'] ?? ''); ?>">
            <label for="Items">Items</label>
        </div>

        <div class="form-floating w-50">
            <input type="date" id="deliveryDate" class="form-control mt-3" placeholder="Delivery Date">
            <label for="deliveryDate">Delivery Date</label>
        </div>

        <div class="mt-3">Delivery Status</div>
        <div class="btn-group mt-2" role="group">
            <input type="radio" class="btn-check" name="deliveryStatus" id="notDelivered" autocomplete="off" value="notDelivered">
            <label class="btn btn-outline-primary" for="notDelivered">Not Delivered</label>

            <input type="radio" class="btn-check" name="deliveryStatus" id="delivered" autocomplete="off" value="delivered">
            <label class="btn btn-outline-primary" for="delivered">Delivered</label>
        </div>
        <div class="d-grid gap-3 d-md-block mt-3 mb-4">
            <button type="button" class="btn btn-outline-danger" id="cancelInvoice">Cancel</button>
            <button type="button" class="btn btn-outline-success" id="saveInvoice">Save</button>
        </div>
    </div>
</div>


<!-- Invoice Table -->
<div id="invoice" class="ml-5 bg-body-tertiary position-absolute end-0 h-100" style="width: 65%; margin-right:5px">
    <div class="container text-center card h-100">
        <div class="row">
            <!-- Seller Information -->
            <?php
            $query = "SELECT sellerName, address, email, contact, gstNo FROM sellerInfo LIMIT 1";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $seller = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>

            <div id="sellerInfo" class="col-sm-5 col-md-6 fw-bold mt-3">
                <?php if ($seller): ?>
                    <?= htmlspecialchars($seller['sellerName']); ?>
                    <div class="fw-normal mt-3 mb-3">
                        Address: <?= htmlspecialchars($seller['address']); ?><br>
                        Email: <?= htmlspecialchars($seller['email']); ?><br>
                        Contact: <?= htmlspecialchars($seller['contact']); ?><br>
                        <span id="sellerGstInfo">GST No. <?= htmlspecialchars($seller['gstNo']); ?></span>
                    </div>
                <?php else: ?>
                    Seller information not available.
                <?php endif; ?>
            </div>

            <div class="col-sm-5 offset-sm-2 col-md-6 offset-md-0 card position-relative mt-3 mr-3">
                <div class="card w-50 position-absolute top-0 start-0 fw-bold" id="invoiceNumber">
                    Invoice Number<br><?= htmlspecialchars($invoiceId ?? 'Unknown'); ?>
                </div>
                <div class="card w-50 position-absolute top-0 end-0 fw-bold" id="displayInvoiceDate">
                    Date<br><span><?= isset($invoice['invoiceDate']) ? htmlspecialchars(date('d-m-Y', strtotime($invoice['invoiceDate']))) : 'N/A'; ?></span>
                </div>

                <div id="customerDetails" class="fw-normal mt-5 mb-10">
                    To: <span id="displayCustomerName"><?= htmlspecialchars($invoice['customerName'] ?? 'Unknown'); ?></span> <br>
                    Address: <span id="displayCustomerAddress" class="card-text"><?= htmlspecialchars($invoice['customerAddress'] ?? 'Unknown'); ?></span><br>
                    Contact: <span id="displayCustomerContact"><?= htmlspecialchars($invoice['customerContact'] ?? 'Unknown'); ?></span><br>
                </div>
            </div>
        </div>

        <hr>

        <table class="table" id="dataTable">
            <thead>
                <tr>
                    <th scope="col">SL.no</th>
                    <th scope="col">Item Name</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Unit</th>
                    <th scope="col">Rate</th>
                    <th scope="col" class="gst-column-head">GST</th>
                    <th scope="col" class="gstAmount-column-head">GST Amount</th>
                    <th scope="col">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($invoiceItems)): ?>
                    <?php
                    $totalAmountSum = 0;
                    foreach ($invoiceItems as $index => $item): ?>
                        <tr data-row-id="row-<?= $index; ?>" data-invoice-item-id="<?= $item['invoiceItemId']; ?>">
                            <td><?= $index + 1; ?></td>
                            <td class="item-name">
                                <div class="CSS">
                                    <span class="itemName">
                                        <?= htmlspecialchars($item['itemName']); ?>
                                    </span>
                                    <a href="#"
                                        class="edit-name"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        data-name="<?= htmlspecialchars($item['itemName']); ?>"
                                        data-column-name="Item Name"
                                        data-invoice-item-id="<?= $item['invoiceItemId']; ?>">
                                        <i class="bi bi-pen edit-name-icon" style="cursor: pointer;"></i>
                                    </a>
                                    <a href="#"
                                        class="remove-button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#removeRowModal"
                                        data-row-id="row-<?= $index; ?>"
                                        data-name="<?= htmlspecialchars($item['itemName']); ?>"
                                        data-invoice-item-id="<?= $item['invoiceItemId']; ?>">
                                        <i class="bi bi-dash-circle py-1 remove-row-icon" style="cursor: pointer;"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="item-quantity">
                                <div class="CSS">
                                    <span class="Quantity">
                                        <?= htmlspecialchars($item['quantity']); ?>
                                    </span>
                                    <a href="#"
                                        class="edit-quantity"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        data-quantity="<?= htmlspecialchars($item['quantity']); ?>"
                                        data-column-name="Quantity"
                                        data-invoice-item-id="<?= $item['invoiceItemId']; ?>">
                                        <i class="bi bi-pen mx-1 edit-quantity-icon" style="cursor: pointer;"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="unit">
                                <div class="CSS">
                                    <span class="unit">
                                        <?= htmlspecialchars($item['unit']); ?>
                                    </span>
                                    <a href="#"
                                        class="edit-unit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        data-unit="<?= htmlspecialchars($item['unit']); ?>"
                                        data-column-name="Unit"
                                        data-invoice-item-id="<?= $item['invoiceItemId']; ?>">
                                        <i class="bi bi-pen mx-1 edit-unit-icon" style="cursor: pointer;"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="unitPrice">
                                <div class="CSS">
                                    <span class="Rate">
                                        <?= number_format($item['unitPrice'], 2); ?>
                                    </span>
                                    <a href="#"
                                        class="edit-unitPrice"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        data-unitPrice="<?= htmlspecialchars($item['unitPrice']); ?>"
                                        data-column-name="Rate"
                                        data-invoice-item-id="<?= $item['invoiceItemId']; ?>">
                                        <i class="bi bi-pen mx-1 edit-unitPrice-icon" style="cursor: pointer;"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="gst-column">
                                <?= isset($gstOptions[$invoice['gstPercentage']]) ? $gstOptions[$invoice['gstPercentage']] : 'Unknown'; ?>
                            </td>
                            <td class="gstAmount-column">
                                <?php
                                $gstPercentage = isset($gstOptions[$invoice['gstPercentage']]) ? intval(rtrim($gstOptions[$invoice['gstPercentage']], '%')) : 0;
                                $gstAmount = ($item['unitPrice'] * $item['quantity'] * $gstPercentage) / 100;
                                echo number_format($gstAmount, 2);
                                ?>
                            </td>
                            <td class="total-amount-column">
                                <?php
                                $totalAmount = ($item['unitPrice'] * $item['quantity']) + $gstAmount;
                                $totalAmountSum += $totalAmount;
                                echo number_format($totalAmount, 2);
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No items found for this invoice.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="card mb-5">
            <div class="fw-bold p-2 g-col-6 position-absolute top-0 start-0">Total Amount :</div>
            <div class="fw-bold p-2 g-col-6 position-absolute top-0 end-0" id="totalAmount">
                ₹ <?= number_format($totalAmountSum, 2); ?>
            </div>
        </div>

        <div class="card">
            <div class="fw-bold p-2 g-col-6 position-absolute top-0 start-0 " id="amountInWords">Amount in words : </div>
        </div>

        <div class="footer text-center" style="margin-top: auto; padding: 10px 0;">
            <hr style="margin: 0;">
            <div class="pb-2">Thank You For Doing Business With Us.</div>
        </div>
    </div>
</div>


<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST">
                <input type="hidden" name="quantity" id="value" value="">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">
                        Edit <strong id="columnName"></strong>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" id="editvalue" placeholder="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="save-button" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Remove Modal -->
<div class="modal fade" id="removeRowModal" tabindex="-1" role="dialog" aria-labelledby="removeRowModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="removeRowFormModal" action="" method="POST">
                <input type="hidden" name="itemName" id="hiddenItemName" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-truncate">
                    Are you sure you want to delete entire row <strong id="displayItemName"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelButton"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const invoiceId = urlParams.get('invoiceId');

        if (!invoiceId) {
            alert('Invoice ID is missing in the URL');
            return;
        }

        const invoiceDate = document.getElementById('invoiceDate');
        const customerName = document.getElementById('customerName');
        const customerAddress = document.getElementById('customerAddress');
        const customerContact = document.getElementById('customerContact');
        const gstSelector = document.getElementById('gstSelector');
        const deliveryDate = document.getElementById('deliveryDate');
        const notDelivered = document.getElementById('notDelivered');
        const delivered = document.getElementById('delivered');
        const saveInvoice = document.getElementById('saveInvoice');
        const cancelInvoice = document.getElementById('cancelInvoice');
        const sellerGstNumber = document.getElementById('sellerGstInfo');

        // Display elements
        const displayInvoiceDate = document.querySelector("#displayInvoiceDate span");
        const displayCustomerName = document.getElementById("displayCustomerName");
        const displayCustomerAddress = document.getElementById("displayCustomerAddress");
        const displayCustomerContact = document.getElementById("displayCustomerContact");

        let initialInvoiceData = {};

        // Fetch invoice and customer data
        fetch(`invoiceData.php?invoiceId=${invoiceId}`, {
                method: 'GET',
            })
            .then(response => response.json())
            .then(data => {
                if (data.status !== 'error') {
                    invoiceDate.value = data.invoiceDate || '';
                    customerName.value = data.customerName || '';
                    customerAddress.value = data.customerAddress || '';
                    customerContact.value = data.customerContact || '';
                    gstSelector.value = data.gstPercentage || '';
                    deliveryDate.value = data.deliveryDate || '';
                    if (data.state === 1) delivered.checked = true;
                    else notDelivered.checked = true;

                    initialInvoiceData = {
                        invoiceDate: invoiceDate.value,
                        customerName: customerName.value,
                        customerAddress: customerAddress.value,
                        customerContact: customerContact.value,
                        gstPercentage: gstSelector.value,
                        deliveryDate: deliveryDate.value,
                        state: delivered.checked ? 'delivered' : 'notDelivered',
                    };

                    updateInvoiceDisplay();
                } else {
                    alert(data.message);
                }

                //toggleInvoiceSaveButton();
            });

        function hasInvoiceChanges() {
            return (
                invoiceDate.value !== initialInvoiceData.invoiceDate ||
                customerName.value !== initialInvoiceData.customerName ||
                customerAddress.value !== initialInvoiceData.customerAddress ||
                customerContact.value !== initialInvoiceData.customerContact ||
                gstSelector.value !== initialInvoiceData.gstPercentage ||
                deliveryDate.value !== initialInvoiceData.deliveryDate ||
                (delivered.checked ? 'delivered' : 'notDelivered') !== initialInvoiceData.state
            );
        }

        function toggleInvoiceSaveButton() {
            saveInvoice.disabled = !hasInvoiceChanges();
        }

        function updateInvoiceDisplay() {
            if (displayInvoiceDate) {
                if (invoiceDate.value) {
                    const date = new Date(invoiceDate.value);
                    const formattedDate = date ?
                        `${String(date.getDate()).padStart(2, '0')}-${String(date.getMonth() + 1).padStart(2, '0')}-${date.getFullYear()}` :
                        'N/A';
                    displayInvoiceDate.innerText = formattedDate;
                } else {
                    displayInvoiceDate.innerText = 'N/A';
                }
            }

            if (displayCustomerName) {
                displayCustomerName.innerText = customerName.value || 'Unknown';
            }

            if (displayCustomerAddress) {
                displayCustomerAddress.innerText = customerAddress.value || 'Unknown';
            }

            if (displayCustomerContact) {
                displayCustomerContact.innerText = customerContact.value || 'Unknown';
            }

            // Update GST-related fields dynamically
            updateGSTDisplay();
            updateSellerGstDisplay();
        }


        function updateGSTDisplay() {
            const gstColumns = document.querySelectorAll('.gst-column');
            const gstAmountColumns = document.querySelectorAll('.gstAmount-column');
            const gstHeaders = document.querySelectorAll('.gst-column-head');
            const gstAmountHeaders = document.querySelectorAll('.gstAmount-column-head');
            const totalAmountColumns = document.querySelectorAll('.total-amount-column');
            const items = <?= json_encode($invoiceItems); ?>;

            const selectedGstValue = gstSelector.value;
            const selectedGstText = gstSelector.options[gstSelector.selectedIndex]?.text || '';
            const selectedGstPercentage = parseInt(selectedGstText.replace('%', '')) || 0;

            let totalAmountSum = 0;

            items.forEach((item, index) => {
                const amount = item.unitPrice * item.quantity;
                let gstAmount = 0;
                let totalAmount = amount;

                if (selectedGstValue !== '1') {
                    gstAmount = (amount * selectedGstPercentage) / 100;
                    totalAmount = amount + gstAmount;

                    if (gstAmountColumns[index]) {
                        gstAmountColumns[index].style.display = '';
                        gstAmountColumns[index].innerText = gstAmount.toFixed(2);
                    }

                    if (gstColumns[index]) {
                        gstColumns[index].style.display = '';
                        gstColumns[index].innerText = selectedGstText;
                    }

                    gstHeaders.forEach(header => {
                        header.style.display = '';
                    });
                    gstAmountHeaders.forEach(header => {
                        header.style.display = '';
                    });
                } else {
                    if (gstAmountColumns[index]) {
                        gstAmountColumns[index].style.display = 'none';
                        gstAmountColumns[index].innerText = '';
                    }

                    if (gstColumns[index]) {
                        gstColumns[index].style.display = 'none';
                    }

                    gstHeaders.forEach(header => {
                        header.style.display = 'none';
                    });
                    gstAmountHeaders.forEach(header => {
                        header.style.display = 'none';
                    });
                }

                if (totalAmountColumns[index]) {
                    totalAmountColumns[index].innerText = totalAmount.toFixed(2);
                }

                totalAmountSum += totalAmount;
            });

            const totalAmountDisplay = document.getElementById('totalAmount');
            if (totalAmountDisplay) {
                totalAmountDisplay.innerText = `₹ ${totalAmountSum.toFixed(2)}`;
            }

            // Update the amount in words display
            const amountInWordsDisplay = document.getElementById('amountInWords');
            if (amountInWordsDisplay) {
                amountInWordsDisplay.innerText = 'Amount in words: ' + numberToWords(totalAmountSum);
            }
        }

        function numberToWords(num) {
            if (num === 0) return 'Zero';

            const a = [
                '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
                'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
            ];
            const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
            const c = ['Hundred', 'Thousand', 'Lakh', 'Crore'];

            function toWords(n, suffix = '') {
                let str = '';
                if (n > 19) str += b[Math.floor(n / 10)] + (n % 10 ? ' ' + a[n % 10] : '') + suffix;
                else if (n > 0) str += a[n] + suffix;
                return str;
            }

            let result = '';
            if (num >= 1000) {
                result += toWords(Math.floor(num / 1000), ' Thousand ');
                num %= 1000;
            }
            if (num >= 100) {
                result += toWords(Math.floor(num / 100), ' Hundred ');
                num %= 100;
            }
            if (num > 0) {
                if (result !== '') result += 'And ';
                result += toWords(num);
            }

            return result.trim() + ' Only';
        }


        function updateSellerGstDisplay() {
            if (gstSelector.value === '1') { // "No GST" selected
                if (sellerGstNumber) sellerGstNumber.innerText = '';
            } else {
                if (sellerGstNumber) sellerGstNumber.innerText = 'GSTIN : 1234ABCD5678'; // Replace with actual GST number
            }
        }

        gstSelector.addEventListener('change', () => {
            updateInvoiceDisplay();
            updateSellerGstDisplay();
        });

        [invoiceDate, customerName, customerAddress, customerContact, deliveryDate, notDelivered, delivered].forEach(field => {
            field.addEventListener('input', () => {
                updateInvoiceDisplay();
                //toggleInvoiceSaveButton();
            });
        });

        saveInvoice.addEventListener('click', function() {
            const invoiceData = new FormData();
            invoiceData.append('invoiceId', invoiceId);
            invoiceData.append('invoiceDate', invoiceDate.value || null);
            invoiceData.append('deliveryDate', deliveryDate.value || null);
            invoiceData.append('gstPercentage', gstSelector.value || null);
            invoiceData.append('state', delivered.checked ? 'delivered' : 'notDelivered');
            invoiceData.append('customerName', customerName.value || null);
            invoiceData.append('customerContact', customerContact.value || null);
            invoiceData.append('customerAddress', customerAddress.value || null);

            fetch('invoiceData.php', {
                    method: 'POST',
                    body: invoiceData,
                })
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'updated') {
                        alert('Changes saved successfully.');
                        // location.reload();
                    } else {
                        alert('Error: ' + result.message);
                    }
                    initialInvoiceData = {
                        invoiceDate: invoiceDate.value,
                        customerName: customerName.value,
                        customerAddress: customerAddress.value,
                        customerContact: customerContact.value,
                        gstPercentage: gstSelector.value,
                        deliveryDate: deliveryDate.value,
                        state: delivered.checked ? 'delivered' : 'notDelivered',
                    };
                    //toggleInvoiceSaveButton();
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('An error occurred while saving changes. Please try again.');
                });
        });

        cancelInvoice.addEventListener('click', function() {
            invoiceDate.value = initialInvoiceData.invoiceDate || '';
            customerName.value = initialInvoiceData.customerName || '';
            customerAddress.value = initialInvoiceData.customerAddress || '';
            customerContact.value = initialInvoiceData.customerContact || '';
            gstSelector.value = initialInvoiceData.gstPercentage || '';
            deliveryDate.value = initialInvoiceData.deliveryDate || '';
            if (initialInvoiceData.state === 'delivered') delivered.checked = true;
            else notDelivered.checked = true;

            updateInvoiceDisplay();
            //toggleInvoiceSaveButton();
        });
    });
</script>

<!-- add new items -->
<script>
    // Function to convert a number to words
    function numberToWords(amount) {
        const words = [
            '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
            'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen',
            'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'
        ];

        if (amount === 0) {
            return 'Zero';
        }

        const strAmount = amount.toString();
        let [integerPart, decimalPart] = strAmount.split('.');

        let wordsResult = '';

        if (integerPart.length > 3) {
            // Handle thousands
            const thousands = Math.floor(parseInt(integerPart) / 1000);
            wordsResult += words[thousands] + ' Thousand ';
            integerPart = (parseInt(integerPart) % 1000).toString();
        }

        if (integerPart.length === 3) {
            // Handle hundreds
            const hundreds = Math.floor(parseInt(integerPart) / 100);
            wordsResult += words[hundreds] + ' Hundred ';
            integerPart = (parseInt(integerPart) % 100).toString();
        }

        if (parseInt(integerPart) < 20) {
            wordsResult += words[parseInt(integerPart)] + ' ';
        } else {
            const tens = Math.floor(parseInt(integerPart) / 10);
            const ones = parseInt(integerPart) % 10;
            wordsResult += words[20 + tens - 2] + ' ' + words[ones] + ' ';
        }

        wordsResult = wordsResult.trim();

        // Add decimal part if exists
        if (decimalPart) {
            wordsResult += ' and ' + decimalPart + '/100';
        }

        return wordsResult;
    }
    // Function to update GST and GST amount for all rows
    function updateGstForAllRows() {
        const gstSelector = document.getElementById('gstSelector');
        const selectedGstValue = parseInt(gstSelector.value);

        // Calculate GST percentage based on the selected value
        let gstPercentage = 0;
        switch (selectedGstValue) {
            case 1:
                gstPercentage = 0;
                break;
            case 2:
                gstPercentage = 5;
                break;
            case 3:
                gstPercentage = 12;
                break;
            case 4:
                gstPercentage = 18;
                break;
            case 5:
                gstPercentage = 33;
                break;
            default:
                gstPercentage = 0;
                break;
        }

        // Show/Hide GST and GST Amount columns based on the selected GST value
        const gstColumns = document.querySelectorAll('.gst-column-head, .gstAmount-column-head');
        const gstRows = document.querySelectorAll('.gst-column, .gstAmount-column');

        if (gstPercentage === 0) {
            gstColumns.forEach(column => column.style.display = 'none');
            gstRows.forEach(row => row.style.display = 'none');
        } else {
            gstColumns.forEach(column => column.style.display = '');
            gstRows.forEach(row => row.style.display = '');
        }

        // Now update all rows with the selected GST value
        const rows = document.querySelectorAll('#dataTable tbody tr');

        rows.forEach(row => {
            const gstColumn = row.querySelector('.gst-column');
            const gstAmountColumn = row.querySelector('.gstAmount-column');
            const rateCell = row.querySelector('.unitPrice');
            const quantityCell = row.querySelector('.item-quantity');

            const rate = parseFloat(rateCell.querySelector('.Rate').textContent);
            const quantity = parseInt(quantityCell.querySelector('.Quantity').textContent);

            // Update GST column and calculate GST Amount if applicable
            if (gstPercentage > 0) {
                gstColumn.textContent = `${gstPercentage}%`;
                const gstAmount = (rate * quantity * gstPercentage) / 100;
                gstAmountColumn.textContent = gstAmount.toFixed(2);
            } else {
                gstColumn.textContent = '';
                gstAmountColumn.textContent = '';
            }

            // Recalculate total amount for the row
            const totalAmount = (rate * quantity) + (gstPercentage > 0 ? parseFloat(gstAmountColumn.textContent) : 0);
            row.querySelector('.total-amount-column').textContent = totalAmount.toFixed(2);
        });

        updateTotalAmount(); // Update the total amount when GST is changed
    }

    // Function to update total amount and amount in words
    function updateTotalAmount() {
        let totalAmount = 0;

        // Loop through all rows to calculate the total amount
        const rows = document.querySelectorAll('#dataTable tbody tr');
        rows.forEach(row => {
            const totalAmountCell = row.querySelector('.total-amount-column');
            if (totalAmountCell) { // Ensure the element exists before accessing it
                totalAmount += parseFloat(totalAmountCell.textContent);
            }
        });

        // Check if the totalAmount element exists before updating it
        const totalAmountElement = document.getElementById('totalAmount');
        if (totalAmountElement) {
            totalAmountElement.textContent = '₹ ' + totalAmount.toFixed(2);
        }

        // Convert the total amount to words
        const totalInWords = numberToWords(totalAmount);
        const totalInWordsElement = document.getElementById('totalInWords');
        if (totalInWordsElement) {
            totalInWordsElement.textContent = 'Amount in Words: ' + totalInWords;
        }
    }


    // Event listener for the `gstSelector` change
    document.getElementById('gstSelector').addEventListener('change', function() {
        updateGstForAllRows(); // Update all rows with the new GST value
    });

    // Event listener for the `Items` input field and adding new items
    document.getElementById('Items').addEventListener('keypress', function(event) {
        const inputValue = event.target.value.trim();

        if (event.key === 'Enter' && inputValue.length > 0) {
            event.preventDefault(); // Prevent form submission if inside a form

            // Split the input by spaces to treat each word as an item
            const items = inputValue.split(' ').map(item => item.trim()).filter(item => item.length > 0);

            // Get the selected GST from the dropdown
            const gstSelector = document.getElementById('gstSelector');
            const selectedGstValue = parseInt(gstSelector.value);

            // Calculate GST percentage based on the selected value
            let gstPercentage = 0;
            switch (selectedGstValue) {
                case 1:
                    gstPercentage = 0;
                    break;
                case 2:
                    gstPercentage = 5;
                    break;
                case 3:
                    gstPercentage = 12;
                    break;
                case 4:
                    gstPercentage = 18;
                    break;
                case 5:
                    gstPercentage = 33;
                    break;
                default:
                    gstPercentage = 0;
                    break;
            }

            const existingRows = document.querySelectorAll('#dataTable tbody tr');
            const existingRowsCount = existingRows.length;

            // Update GST columns visibility before adding the new row
            updateGstForAllRows();

            items.forEach((itemName, index) => {
                const quantity = 1;
                const unit = 'pcs';
                const rate = 100;
                let gstAmount = 0;

                // Calculate GST if applicable
                if (gstPercentage > 0) {
                    gstAmount = (rate * quantity * gstPercentage) / 100;
                }
                const totalAmount = (rate * quantity) + gstAmount;

                const slNo = existingRowsCount + index + 1;

                const newRow = `
            <tr data-row-id="row-${slNo}">
                <td>${slNo}</td>
                <td class="item-name">
                    <div class="CSS">
                        <span class="itemName">${itemName}</span>
                        <a href="#" class="edit-name" data-bs-toggle="modal" data-bs-target="#editModal" data-name="${itemName}" data-column-name="Item Name">
                            <i class="bi bi-pen edit-name-icon" style="cursor: pointer;"></i>
                        </a>
                        <a href="#" class="remove-button" data-bs-toggle="modal" data-bs-target="#removeRowModal" data-row-id="row-${slNo}" data-name="${itemName}">
                            <i class="bi bi-dash-circle py-1 remove-row-icon" style="cursor: pointer;"></i>
                        </a>
                    </div>
                </td>
                <td class="item-quantity">
                    <div class="CSS">
                        <span class="Quantity">${quantity}</span>
                        <a href="#" class="edit-quantity" data-bs-toggle="modal" data-bs-target="#editModal" data-quantity="${quantity}" data-column-name="Quantity">
                            <i class="bi bi-pen mx-1 edit-quantity-icon" style="cursor: pointer;"></i>
                        </a>
                    </div>
                </td>
                <td class="unit">
                    <div class="CSS">
                        <span class="unit">${unit}</span>
                        <a href="#" class="edit-unit" data-bs-toggle="modal" data-bs-target="#editModal" data-unit="${unit}" data-column-name="Unit">
                            <i class="bi bi-pen mx-1 edit-unit-icon" style="cursor: pointer;"></i>
                        </a>
                    </div>
                </td>
                <td class="unitPrice">
                    <div class="CSS">
                        <span class="Rate">${rate.toFixed(2)}</span>
                        <a href="#" class="edit-unitPrice" data-bs-toggle="modal" data-bs-target="#editModal" data-unitPrice="${rate.toFixed(2)}" data-column-name="Rate">
                            <i class="bi bi-pen mx-1 edit-unitPrice-icon" style="cursor: pointer;"></i>
                        </a>
                    </div>
                </td>
                <td class="gst-column">${gstPercentage > 0 ? `${gstPercentage}%` : ''}</td>
                <td class="gstAmount-column">${gstAmount.toFixed(2)}</td>
                <td class="total-amount-column">${totalAmount.toFixed(2)}</td>
            </tr>
        `;

                document.querySelector('#dataTable tbody').insertAdjacentHTML('beforeend', newRow);

                updateTotalAmount(); // Recalculate the total amount after adding new item
            });

            document.getElementById('Items').value = '';
        }
    });
</script>

<!-- edit icons -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentRowId = null;

        // Function to convert numbers to words
        function numberToWords(num) {
            if (num === 0) return 'Zero Only';

            const a = [
                '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
                'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
            ];
            const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
            const c = ['Hundred', 'Thousand', 'Lakh', 'Crore'];

            function toWords(n, suffix = '') {
                let str = '';
                if (n > 19) str += b[Math.floor(n / 10)] + (n % 10 ? ' ' + a[n % 10] : '') + suffix;
                else if (n > 0) str += a[n] + suffix;
                return str;
            }

            let result = '';
            if (num >= 1000) {
                result += toWords(Math.floor(num / 1000), ' Thousand ');
                num %= 1000;
            }
            if (num >= 100) {
                result += toWords(Math.floor(num / 100), ' Hundred ');
                num %= 100;
            }
            if (num > 0) {
                if (result !== '') result += 'And ';
                result += toWords(num);
            }

            return result.trim() + ' Only';
        }

        // Attach event listeners to edit buttons dynamically
        document.querySelector('tbody').addEventListener('click', function(event) {
            let target = event.target;

            // Find the closest <a> tag that was clicked (icon inside it)
            if (target.tagName === 'I') {
                target = target.closest('a');
            }

            if (target && (target.classList.contains('edit-name') ||
                    target.classList.contains('edit-quantity') ||
                    target.classList.contains('edit-unit') ||
                    target.classList.contains('edit-unitPrice'))) {

                event.preventDefault(); // Prevent default link action

                const rowElement = target.closest('tr');
                currentRowId = rowElement.getAttribute('data-row-id');

                const columnName = target.getAttribute('data-column-name');
                let value = target.getAttribute('data-name') ||
                    target.getAttribute('data-quantity') ||
                    target.getAttribute('data-unit') ||
                    target.getAttribute('data-unitPrice');

                document.getElementById('value').value = value;
                document.getElementById('editvalue').value = value;
                document.getElementById('columnName').textContent = columnName;
            }
        });

        // Save updated values and apply changes to the row
        document.querySelector('#editModal #save-button').addEventListener('click', function() {
            if (currentRowId) {
                const updatedValue = document.getElementById('editvalue').value;
                const rowElement = document.querySelector(`tr[data-row-id="${currentRowId}"]`);
                if (rowElement) {
                    const columnName = document.getElementById('columnName').textContent;

                    const columnClassMap = {
                        'Item Name': 'item-name',
                        'Quantity': 'item-quantity',
                        'Unit': 'unit',
                        'Rate': 'unitPrice'
                    };

                    const columnClass = columnClassMap[columnName];

                    const columnElement = rowElement.querySelector(`.${columnClass} span`);
                    if (columnElement) {
                        if (columnName === 'Quantity') {
                            // Ensure Quantity is parsed as an integer
                            columnElement.textContent = parseInt(updatedValue) || 0; // Set 0 if NaN
                        } else if (columnName === 'Rate') {
                            // Ensure Rate is parsed as a float
                            columnElement.textContent = parseFloat(updatedValue).toFixed(2) || '0.00'; // Set '0.00' if NaN
                        } else {
                            // Set text for non-numeric fields like Item Name or Unit
                            columnElement.textContent = updatedValue;
                        }
                    }

                    // Update row calculations if the Quantity or Rate was updated
                    if (columnName === 'Quantity' || columnName === 'Rate') {
                        const gstElement = rowElement.querySelector('.gst-column');
                        const gstPercentage = parseFloat(gstElement.textContent.replace('%', '').trim()) || 0;
                        updateRowCalculations(rowElement, gstPercentage);
                    }
                }

                // Close the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                modal.hide();
                currentRowId = null;
            }
        });



        // Function to update row calculations
        function updateRowCalculations(rowElement, gstPercentage) {
            const quantityElement = rowElement.querySelector('.item-quantity .Quantity');
            const unitPriceElement = rowElement.querySelector('.unitPrice .Rate');
            const gstAmountElement = rowElement.querySelector('.gstAmount-column');
            const totalAmountElement = rowElement.querySelector('.total-amount-column');

            const quantity = parseFloat(quantityElement.textContent) || 0;
            const unitPrice = parseFloat(unitPriceElement.textContent.replace(/,/g, '')) || 0;

            const gstAmount = (quantity * unitPrice * gstPercentage) / 100;
            const totalAmount = (quantity * unitPrice) + gstAmount;

            gstAmountElement.textContent = gstAmount.toFixed(2);
            totalAmountElement.textContent = totalAmount.toFixed(2);

            updateTotalAmountSum();
        }

        // Function to update total amount and words
        function updateTotalAmountSum() {
            let totalAmountSum = 0;
            document.querySelectorAll('.total-amount-column').forEach(element => {
                totalAmountSum += parseFloat(element.textContent.replace(/,/g, '')) || 0;
            });

            document.getElementById('totalAmount').textContent = '₹ ' + totalAmountSum.toFixed(2);
            document.getElementById('amountInWords').textContent = 'Amount in words: ' + numberToWords(Math.floor(totalAmountSum));
        }
    });
</script>

<!-- Remove Row -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let rowToDelete = null;

        // Handle clicking the remove button
        document.querySelectorAll('.remove-button').forEach(button => {
            button.addEventListener('click', function() {
                const rowId = this.getAttribute('data-row-id');
                const itemName = this.getAttribute('data-name');

                // Store row reference
                rowToDelete = document.querySelector(`tr[data-row-id="${rowId}"]`);

                // Update modal text
                document.getElementById('hiddenItemName').value = itemName;
                document.getElementById('displayItemName').textContent = itemName;
            });
        });

        // Handle delete confirmation
        document.querySelector('#removeRowFormModal').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission (useful for AJAX)

            if (rowToDelete) {
                rowToDelete.remove(); // Remove the row
                updateSerialNumbers(); // Update serial numbers after deletion
                updateTotalAmountSum(); // Update totals after deletion
                rowToDelete = null;
            }

            // Hide the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('removeRowModal'));
            modal.hide();
        });

        // Function to update serial numbers after row deletion
        function updateSerialNumbers() {
            const rows = document.querySelectorAll('tbody tr[data-row-id]');
            rows.forEach((row, index) => {
                // Update serial number
                row.querySelector('td:first-child').textContent = index + 1;

                // Update row ID to match new order
                row.setAttribute('data-row-id', `row-${index}`);

                // Update remove button's data-row-id
                const removeButton = row.querySelector('.remove-button');
                if (removeButton) {
                    removeButton.setAttribute('data-row-id', `row-${index}`);
                }
            });
        }

        function updateTotalAmountSum() {
            let totalAmountSum = 0;
            const totalAmountElements = document.querySelectorAll('.total-amount-column');

            totalAmountElements.forEach(element => {
                totalAmountSum += parseFloat(element.textContent.replace(/,/g, '')) || 0;
            });

            // Update the total amount display
            const totalAmountDisplay = document.getElementById('totalAmount');
            if (totalAmountDisplay) {
                totalAmountDisplay.textContent = '₹ ' + totalAmountSum.toFixed(2);
            }

            // Update the amount in words display
            const amountInWordsDisplay = document.getElementById('amountInWords');
            if (amountInWordsDisplay) {
                amountInWordsDisplay.textContent = 'Amount in words: ' + numberToWords(Math.floor(totalAmountSum));
            }
        }

        function numberToWords(num) {
            if (num === 0) return 'Zero Only';

            const a = [
                '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
                'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
            ];
            const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

            function toWords(n, suffix = '') {
                let str = '';
                if (n > 19) {
                    str += b[Math.floor(n / 10)] + (n % 10 ? ' ' + a[n % 10] : '') + suffix;
                } else if (n > 0) {
                    str += a[n] + suffix;
                }
                return str;
            }

            let result = '';
            if (num >= 1000) {
                result += toWords(Math.floor(num / 1000), ' Thousand ');
                num %= 1000;
            }
            if (num >= 100) {
                result += toWords(Math.floor(num / 100), ' Hundred ');
                num %= 100;
            }
            if (num > 0) {
                if (result !== '') result += 'And ';
                result += toWords(num);
            }

            return result.trim() + ' Only';
        }


    });
</script>

<!-- save items and print pdf -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script>
    const exportButton = document.querySelector('#saveInvoice'); // Assuming you have a button with id "export"
    const invoiceCard = document.querySelector('#invoice'); // The div containing the invoice

    // Function to capture the screenshot and generate the PDF
    exportButton.addEventListener('click', () => {
        if (typeof html2canvas !== 'function' || typeof jsPDF !== 'function') {
            console.error('Required libraries (html2canvas or jsPDF) are not loaded properly.');
            return;
        }

        html2canvas(invoiceCard, {
            scale: 2
        }).then((canvas) => {
            // Convert the canvas to a data URL (image format)
            const imgData = canvas.toDataURL('image/jpeg', 1.0);

            // Create a new jsPDF instance
            const pdf = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4',
            });

            // Calculate the dimensions of the PDF page based on the image
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

            // Add the image (screenshot) to the PDF
            pdf.addImage(imgData, 'JPEG', 0, 0, pdfWidth, pdfHeight);

            // Save the PDF
            pdf.save('invoice.pdf');
        }).catch((error) => {
            console.error('Error while capturing the invoice:', error);
        });
    });
</script>


<?php include('../../includes/footer.php'); ?>