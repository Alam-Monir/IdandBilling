<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../config/dbcon.php');

header("Content-Type: application/json");

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (
        empty($data['invoiceNumber']) ||
        empty($data['invoiceDate']) ||
        empty($data['deliveryDate']) ||
        empty($data['customerName']) ||
        empty($data['customerAddress']) ||
        empty($data['customerContact']) ||
        empty($data['gstPercentage']) ||
        empty($data['table'])
    ) {
        throw new Exception("Incomplete data received.");
    }

    $invoiceNumber = $data['invoiceNumber'];
    $invoiceDate = $data['invoiceDate'];
    $deliveryDate = $data['deliveryDate'];
    $customerName = $data['customerName'];
    $customerAddress = $data['customerAddress'];
    $customerContact = $data['customerContact'];
    $gstPercentage = $data['gstPercentage'];
    $table = $data['table'];

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT customerId FROM customers WHERE customerName = ? AND customerContact = ?");
    $stmt->execute([$customerName, $customerContact]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($customer) {
        $customerId = $customer['customerId'];
    } else {
        $customerId = bin2hex(random_bytes(16));
        $stmt = $pdo->prepare("INSERT INTO customers (customerId, customerName, customerAddress, customerContact) VALUES (?, ?, ?, ?)");
        $stmt->execute([$customerId, $customerName, $customerAddress, $customerContact]);
    }

    $stmt = $pdo->prepare("INSERT INTO invoices (invoiceId, customerId, gstPercentage, invoiceDate, deliveryDate) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$invoiceNumber, $customerId, $gstPercentage, $invoiceDate, $deliveryDate]);

    $stmt = $pdo->prepare("INSERT INTO invoiceitems (invoiceItemId, invoiceId, itemName, unit, unitPrice, quantity) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($table as $row) {
        $itemName = $row['itemName'];
        $quantity = $row['quantity'];
        $unit = $row['unit'];
        $unitPrice = $row['unitPrice'];

        $invoiceItemId = uniqid("item_");

        $stmt->execute([$invoiceItemId, $invoiceNumber, $itemName, $unit, $unitPrice, $quantity]);
    }

    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "Invoice and items saved successfully!"
    ]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
