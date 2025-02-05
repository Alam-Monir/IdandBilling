<?php
include('../../includes/header.php');
include('../../includes/nav.php');
include('../../../config/dbcon.php');
include ('../../../auth/auth_check_admin.php');

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
        /* position: absolute; */
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

            <!-- tabel invoice  -->

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
                                    <span class="itemName" data-invoice-item-id="<?= $item['invoiceItemId']; ?>" data-invoice-id="<?= $item['invoiceId']; ?>">
                                        <?= htmlspecialchars($item['itemName']); ?>
                                    </span>
                                    <a href="#"
                                        class="edit-name"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        data-name="<?= htmlspecialchars($item['itemName']); ?>"
                                        data-column-name="Item Name"
                                        data-invoice-item-id="<?= $item['invoiceItemId']; ?>">
                                        <i class="bi bi-pen edit-name-icon m-1" style="cursor: pointer;"></i>
                                    </a>
                                    <a href="#"
                                        class="remove-button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#removeRowModal"
                                        data-row-id="row-<?= $index; ?>"
                                        data-name="<?= htmlspecialchars($item['itemName']); ?>"
                                        data-invoice-item-id="<?= $item['invoiceItemId']; ?>">
                                        <i class="bi bi-dash-circle py-1 remove-row-icon ms-4" style="cursor: pointer;"></i>
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

<!-- Function to convert a number to words -->
<script>
    function numberToWords(num) {
        if (num === 0) return 'Zero Only';

        const a = [
            '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
            'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
        ];
        const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        function toWords(n) {
            if (n === 0) return '';
            let str = '';
            if (n >= 100) {
                str += a[Math.floor(n / 100)] + ' Hundred ';
                n %= 100;
            }
            if (n > 19) {
                str += b[Math.floor(n / 10)] + (n % 10 ? ' ' + a[n % 10] : '');
            } else if (n > 0) {
                str += a[n];
            }
            return str.trim();
        }

        let result = '';
        let integerPart = Math.floor(num);
        let decimalPart = Math.round((num - integerPart) * 100);

        if (integerPart >= 10000000) {
            result += toWords(Math.floor(integerPart / 10000000)) + ' Crore ';
            integerPart %= 10000000;
        }

        if (integerPart >= 100000) {
            result += toWords(Math.floor(integerPart / 100000)) + ' Lakh ';
            integerPart %= 100000;
        }

        if (integerPart >= 1000) {
            result += toWords(Math.floor(integerPart / 1000)) + ' Thousand ';
            integerPart %= 1000;
        }

        if (integerPart > 0) {
            result += toWords(integerPart);
        }

        if (decimalPart > 0) {
            result += ' And ' + toWords(decimalPart) + ' Paise';
        }

        return result.trim() + ' Only';
    }
</script>

<!-- add new items -->
<script>
    function updateGstForAllRows() {
        const gstSelector = document.getElementById('gstSelector');
        const selectedGstValue = parseInt(gstSelector.value);

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

        const gstColumns = document.querySelectorAll('.gst-column-head, .gstAmount-column-head');
        const gstRows = document.querySelectorAll('.gst-column, .gstAmount-column');

        if (gstPercentage === 0) {
            gstColumns.forEach(column => column.style.display = 'none');
            gstRows.forEach(row => row.style.display = 'none');
        } else {
            gstColumns.forEach(column => column.style.display = '');
            gstRows.forEach(row => row.style.display = '');
        }

        const rows = document.querySelectorAll('#dataTable tbody tr');

        rows.forEach(row => {
            const gstColumn = row.querySelector('.gst-column');
            const gstAmountColumn = row.querySelector('.gstAmount-column');
            const rateCell = row.querySelector('.unitPrice');
            const quantityCell = row.querySelector('.item-quantity');

            const rate = parseFloat(rateCell.querySelector('.Rate').textContent);
            const quantity = parseInt(quantityCell.querySelector('.Quantity').textContent);

            if (gstPercentage > 0) {
                gstColumn.textContent = `${gstPercentage}%`;
                const gstAmount = (rate * quantity * gstPercentage) / 100;
                gstAmountColumn.textContent = gstAmount.toFixed(2);
            } else {
                gstColumn.textContent = '';
                gstAmountColumn.textContent = '';
            }

            const totalAmount = (rate * quantity) + (gstPercentage > 0 ? parseFloat(gstAmountColumn.textContent) : 0);
            row.querySelector('.total-amount-column').textContent = totalAmount.toFixed(2);
        });

        updateTotalAmount();
    }

    function updateTotalAmount() {
        let totalAmount = 0;

        const rows = document.querySelectorAll('#dataTable tbody tr');
        rows.forEach(row => {
            const totalAmountCell = row.querySelector('.total-amount-column');
            if (totalAmountCell) {
                totalAmount += parseFloat(totalAmountCell.textContent);
            }
        });

        const totalAmountElement = document.getElementById('totalAmount');
        if (totalAmountElement) {
            totalAmountElement.textContent = '₹ ' + totalAmount.toFixed(2);
        }

        const totalInWords = numberToWords(totalAmount);
        const totalInWordsElement = document.getElementById('totalInWords');
        if (totalInWordsElement) {
            totalInWordsElement.textContent = 'Amount in Words: ' + totalInWords;
        }
    }


    document.getElementById('gstSelector').addEventListener('change', function() {
        updateGstForAllRows();
    });

    document.getElementById('Items').addEventListener('keypress', function(event) {
        const inputValue = event.target.value.trim();

        if (event.key === 'Enter' && inputValue.length > 0) {
            event.preventDefault();

            const items = inputValue.split(' ').map(item => item.trim()).filter(item => item.length > 0);

            const gstSelector = document.getElementById('gstSelector');
            const selectedGstValue = parseInt(gstSelector.value);

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

            updateGstForAllRows();

            items.forEach((itemName, index) => {
                const quantity = 1;
                const unit = 'pcs';
                const rate = 100;
                let gstAmount = 0;

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
                            <i class="bi bi-pen edit-name-icon" style="cursor: pointer ;margin-right:20px;"></i>
                        </a>
                        <a href="#" class="remove-button" data-bs-toggle="modal" data-bs-target="#removeRowModal" data-row-id="row-${slNo}" data-name="${itemName}">
                            <i class="bi bi-dash-circle py-1 remove-row-icon" style="cursor: pointer; postionin:relative; margin-right:20px"></i>
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

                updateTotalAmount();
            });

            document.getElementById('Items').value = '';
        }
    });
</script>

<!-- edit icons -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentRowId = null;

        document.querySelector('tbody').addEventListener('click', function(event) {
            let target = event.target;

            if (target.tagName === 'I') {
                target = target.closest('a');
            }

            if (target && (target.classList.contains('edit-name') ||
                    target.classList.contains('edit-quantity') ||
                    target.classList.contains('edit-unit') ||
                    target.classList.contains('edit-unitPrice'))) {

                event.preventDefault();

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
                            columnElement.textContent = parseInt(updatedValue) || 0;
                        } else if (columnName === 'Rate') {
                            columnElement.textContent = parseFloat(updatedValue).toFixed(2) || '0.00';
                        } else {
                            columnElement.textContent = updatedValue;
                        }
                    }

                    if (columnName === 'Quantity' || columnName === 'Rate') {
                        const gstElement = rowElement.querySelector('.gst-column');
                        const gstPercentage = parseFloat(gstElement.textContent.replace('%', '').trim()) || 0;
                        updateRowCalculations(rowElement, gstPercentage);
                    }
                }

                const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                modal.hide();
                currentRowId = null;
            }
        });



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

        document.querySelectorAll('.remove-button').forEach(button => {
            button.addEventListener('click', function() {
                const rowId = this.getAttribute('data-row-id');
                const itemName = this.getAttribute('data-name');

                rowToDelete = document.querySelector(`tr[data-row-id="${rowId}"]`);

                document.getElementById('hiddenItemName').value = itemName;
                document.getElementById('displayItemName').textContent = itemName;
            });
        });

        document.querySelector('#removeRowFormModal').addEventListener('submit', function(event) {
            event.preventDefault();

            if (rowToDelete) {
                rowToDelete.remove();
                updateSerialNumbers();
                updateTotalAmountSum();
                rowToDelete = null;
            }

            const modal = bootstrap.Modal.getInstance(document.getElementById('removeRowModal'));
            modal.hide();
        });

        function updateSerialNumbers() {
            const rows = document.querySelectorAll('tbody tr[data-row-id]');
            rows.forEach((row, index) => {
                row.querySelector('td:first-child').textContent = index + 1;

                row.setAttribute('data-row-id', `row-${index}`);

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

            const totalAmountDisplay = document.getElementById('totalAmount');
            if (totalAmountDisplay) {
                totalAmountDisplay.textContent = '₹ ' + totalAmountSum.toFixed(2);
            }

            const amountInWordsDisplay = document.getElementById('amountInWords');
            if (amountInWordsDisplay) {
                amountInWordsDisplay.textContent = 'Amount in words: ' + numberToWords(totalAmountSum);
            }
        }
    });
</script>

<!-- updating db script -->
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
        fetch(`invoiceData.php?invoiceId=${invoiceId}`)
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
            });

        function updateInvoiceDisplay() {
            if (displayInvoiceDate) {
                if (invoiceDate.value) {
                    const date = new Date(invoiceDate.value);
                    const formattedDate = date ? `${String(date.getDate()).padStart(2, '0')}-${String(date.getMonth() + 1).padStart(2, '0')}-${date.getFullYear()}` : 'N/A';
                    displayInvoiceDate.innerText = formattedDate;
                } else {
                    displayInvoiceDate.innerText = 'N/A';
                }
            }

            displayCustomerName.innerText = customerName.value || 'Unknown';
            displayCustomerAddress.innerText = customerAddress.value || 'Unknown';
            displayCustomerContact.innerText = customerContact.value || 'Unknown';

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

            const selectedGstText = gstSelector.options[gstSelector.selectedIndex]?.text || '';
            const selectedGstPercentage = parseInt(selectedGstText.replace('%', '')) || 0;

            let totalAmountSum = 0;

            items.forEach((item, index) => {
                const amount = item.unitPrice * item.quantity;
                let gstAmount = (selectedGstPercentage !== 1) ? (amount * selectedGstPercentage) / 100 : 0;
                let totalAmount = amount + gstAmount;

                if (gstAmountColumns[index]) gstAmountColumns[index].innerText = gstAmount.toFixed(2);
                if (gstColumns[index]) gstColumns[index].innerText = selectedGstText;
                if (totalAmountColumns[index]) totalAmountColumns[index].innerText = totalAmount.toFixed(2);

                totalAmountSum += totalAmount;
            });

            document.getElementById('totalAmount').innerText = `₹ ${totalAmountSum.toFixed(2)}`;
            document.getElementById('amountInWords').innerText = 'Amount in words: ' + numberToWords(totalAmountSum);
        }

        function updateSellerGstDisplay() {
            sellerGstNumber.innerText = gstSelector.value === '1' ? '' : 'GSTIN : 1234ABCD5678';
        }

        gstSelector.addEventListener('change', () => {
            updateInvoiceDisplay();
            updateSellerGstDisplay();
        });

        [invoiceDate, customerName, customerAddress, customerContact, deliveryDate, notDelivered, delivered].forEach(field => {
            field.addEventListener('input', updateInvoiceDisplay);
        });

        saveInvoice.addEventListener('click', function() {
            // Collect invoice items data
            let invoiceItems = [];
            document.querySelectorAll("tbody tr").forEach(row => {
                let invoiceItemId = row.getAttribute("data-invoice-item-id") || "";
                let itemName = row.querySelector(".itemName").innerText.trim();
                let quantity = row.querySelector(".Quantity").innerText.trim();
                let unit = row.querySelector(".unit").innerText.trim();
                let rate = row.querySelector(".Rate").innerText.trim();

                invoiceItems.push({
                    invoiceItemId,
                    itemName,
                    quantity,
                    unit,
                    rate
                });
            });

            // Prepare requests
            let invoiceItemsRequest = fetch("save_invoice.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    invoiceId,
                    invoiceItems
                })
            }).then(response => response.json());

            let invoiceData = new FormData();
            invoiceData.append('invoiceId', invoiceId);
            invoiceData.append('invoiceDate', invoiceDate.value || null);
            invoiceData.append('deliveryDate', deliveryDate.value || null);
            invoiceData.append('gstPercentage', gstSelector.value || null);
            invoiceData.append('state', delivered.checked ? 'delivered' : 'notDelivered');
            invoiceData.append('customerName', customerName.value || null);
            invoiceData.append('customerContact', customerContact.value || null);
            invoiceData.append('customerAddress', customerAddress.value || null);

            let invoiceDetailsRequest = fetch("invoiceData.php", {
                method: "POST",
                body: invoiceData
            }).then(response => response.json());

            // Execute both requests and show a single alert
            Promise.all([invoiceItemsRequest, invoiceDetailsRequest])
                .then(([invoiceItemsResult, invoiceDetailsResult]) => {
                    if (invoiceItemsResult.success && invoiceDetailsResult.status === "updated") {
                        alert("Invoice saved successfully!");
                    } else {
                        alert("Error: Could not save all data.");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while saving. Please try again.");
                });
        });

        cancelInvoice.addEventListener('click', function() {
            invoiceDate.value = initialInvoiceData.invoiceDate || '';
            customerName.value = initialInvoiceData.customerName || '';
            customerAddress.value = initialInvoiceData.customerAddress || '';
            customerContact.value = initialInvoiceData.customerContact || '';
            gstSelector.value = initialInvoiceData.gstPercentage || '';
            deliveryDate.value = initialInvoiceData.deliveryDate || '';
            delivered.checked = (initialInvoiceData.state === 'delivered');
            notDelivered.checked = (initialInvoiceData.state !== 'delivered');

            updateInvoiceDisplay();
        });
    });
</script>

<!-- save items and print pdf -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script>
    const exportButton = document.querySelector('#saveInvoice');
    const invoiceCard = document.querySelector('#invoice');

    exportButton.addEventListener('click', () => {
        if (typeof html2canvas !== 'function' || typeof jsPDF !== 'function') {
            console.error('Required libraries (html2canvas or jsPDF) are not loaded properly.');
            return;
        }

        html2canvas(invoiceCard, {
            scale: 2
        }).then((canvas) => {
            const imgData = canvas.toDataURL('image/jpeg', 1.0);

            const pdf = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4',
            });

            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

            pdf.addImage(imgData, 'JPEG', 0, 0, pdfWidth, pdfHeight);

            pdf.save('invoice.pdf');
        }).catch((error) => {
            console.error('Error while capturing the invoice:', error);
        });
    });
</script>


<?php include('../../includes/footer.php'); ?>