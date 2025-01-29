<?php
include('../includes/header.php');
include('../includes/nav.php');
include('../../config/dbcon.php');
?>

<!-- Delete Modal -->
<div class="modal fade" id="deleteInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="deleteFormModal" action="" method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="invoiceId" id="deleteInvoiceId" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-truncate">
                    Are you sure you want to delete Invoice <strong id="invoiceToDelete"></strong>?
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

<!-- Invoices table -->
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
                <th scope="col">Status</th>
                <th scope="col">Manage</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                $stmt = $pdo->prepare("SELECT 
                    invoices.invoiceId,
                    customers.customerName AS customerName,
                    customers.customerContact,
                    customers.customerAddress,
                    invoices.invoiceDate,
                    invoices.deliveryDate,
                    invoices.state
                FROM invoices
                INNER JOIN customers ON invoices.customerId = customers.customerId 
                ORDER BY invoices.state ASC");
                $stmt->execute();
                $invoice = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($invoice)) {
                    $slNo = 1;
                    foreach ($invoice as $invoice) {
                        $invoiceDate = date("d-m-Y", strtotime($invoice['invoiceDate']));
                        $deliveryDate = date("d-m-Y", strtotime($invoice['deliveryDate']));
                        $status = $invoice['state'] == 0 ? 'Not Delivered' : 'Delivered';
                        $invoiceId = $invoice['invoiceId'];
            ?>
                        <tr>
                            <th><?= htmlspecialchars($invoice['invoiceId']); ?></th>
                            <td><?= htmlspecialchars($invoice['customerName']); ?></td>
                            <td><?= htmlspecialchars($invoice['customerContact']); ?></td>
                            <td><?= htmlspecialchars($invoice['customerAddress']); ?></td>
                            <td><?= htmlspecialchars($invoiceDate); ?></td>
                            <td><?= htmlspecialchars($deliveryDate); ?></td>
                            <td><?= htmlspecialchars($status); ?></td>
                            <td>
                                <a href="editInvoice?invoiceId=<?php echo $invoiceId; ?>" class="edit-button">
                                    <i class="bi bi-pen px-2"></i>
                                </a>
                                <a href="#"
                                    class="delete-button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteInvoiceModal"
                                    data-invoice-id="<?= htmlspecialchars($invoice['invoiceId']); ?>">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="8" class="text-center">No Invoice found.</td>
                    </tr>
                <?php
                }
            } catch (PDOException $e) {
                ?>
                <tr>
                    <td colspan="8" class="text-center text-danger">Error: <?= htmlspecialchars($e->getMessage()); ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>

    </table>
</div>

<!-- delete script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-button');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const invoiceId = button.getAttribute('data-invoice-id');
                document.getElementById('deleteInvoiceId').value = invoiceId;
                document.getElementById('invoiceToDelete').textContent = invoiceId;
            });
        });
    });

    document.getElementById("deleteFormModal").addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        fetch("invoiceLogic.php", {
                method: "POST",
                body: formData,
            })
            .then((response) => response.text())
            .then((data) => {
                if (data.trim() === 'success') {
                    alert('Invoice deleted successfully!');
                    window.location.reload();
                } else {
                    alert('Error deleting Invoice: ' + data);
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("An error occurred while processing the request.");
            });
    });
</script>

<?php include('../includes/footer.php'); ?>