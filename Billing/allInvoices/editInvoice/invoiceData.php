<?php
header('Content-Type: application/json');
include('../../../config/dbcon.php');

if (!isset($pdo)) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection not initialized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $invoiceId = $_GET['invoiceId'] ?? null;

    if (!$invoiceId) {
        echo json_encode(['status' => 'error', 'message' => 'Invoice ID is required']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            SELECT 
                invoices.invoiceId, 
                invoices.invoiceDate, 
                invoices.deliveryDate, 
                invoices.gstPercentage, 
                invoices.state AS state, 
                customers.customerName, 
                customers.customerAddress, 
                customers.customerContact
            FROM invoices
            INNER JOIN customers ON invoices.customerId = customers.customerId
            WHERE invoices.invoiceId = :invoiceId
        ");

        $stmt->execute(['invoiceId' => $invoiceId]);
        $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($invoice) {
            echo json_encode($invoice);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invoice not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoiceId = $_POST['invoiceId'];
    $invoiceDate = $_POST['invoiceDate'] ?? null;
    $deliveryDate = $_POST['deliveryDate'] ?? null;
    $gstPercentage = $_POST['gstPercentage'] ?? null;
    $state = $_POST['state'] ?? null;
    $customerName = $_POST['customerName'] ?? null;
    $customerContact = $_POST['customerContact'] ?? null;
    $customerAddress = $_POST['customerAddress'] ?? null;

    try {
        // Update invoice details if provided
        $updateInvoice = $pdo->prepare("
            UPDATE invoices
            SET 
                invoiceDate = COALESCE(:invoiceDate, invoiceDate),
                deliveryDate = COALESCE(:deliveryDate, deliveryDate),
                gstPercentage = COALESCE(:gstPercentage, gstPercentage),
                state = COALESCE(:state, state)
            WHERE invoiceId = :invoiceId
        ");

        $updateInvoice->execute([
            'invoiceDate' => $invoiceDate,
            'deliveryDate' => $deliveryDate,
            'gstPercentage' => $gstPercentage,
            'state' => ($state === 'delivered') ? 1 : 0,
            'invoiceId' => $invoiceId,
        ]);

        // Update customer details if provided
        $updateCustomer = $pdo->prepare("
            UPDATE customers
            SET 
                customerName = COALESCE(:customerName, customerName),
                customerContact = COALESCE(:customerContact, customerContact),
                customerAddress = COALESCE(:customerAddress, customerAddress)
            WHERE customerId = (
                SELECT customerId FROM invoices WHERE invoiceId = :invoiceId
            )
        ");

        $updateCustomer->execute([
            'customerName' => $customerName,
            'customerContact' => $customerContact,
            'customerAddress' => $customerAddress,
            'invoiceId' => $invoiceId,
        ]);

        echo json_encode(['status' => 'updated']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
