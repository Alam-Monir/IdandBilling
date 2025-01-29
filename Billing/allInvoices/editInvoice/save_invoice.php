<?php
// Include the database connection
include('../../../config/dbcon.php'); // Assuming dbcon.php is in the same directory

header('Content-Type: application/json');

// Get the data from the AJAX request
$data = json_decode(file_get_contents('php://input'), true);
$items = $data['items'];
$response = ['success' => false];

try {
    // Start a transaction
    $pdo->beginTransaction();

    foreach ($items as $item) {
        // Prepare the SQL to check if the item already exists for the given invoiceId
        $stmt = $pdo->prepare("SELECT * FROM invoiceItems WHERE itemName = :itemName AND unitPrice = :unitPrice AND quantity = :quantity AND unit = :unit AND invoiceId = :invoiceId LIMIT 1");
        $stmt->execute([
            ':itemName' => $item['itemName'],
            ':unitPrice' => $item['unitPrice'],
            ':quantity' => $item['quantity'],
            ':unit' => $item['unit'],
            ':invoiceId' => $item['invoiceId'] // Include invoiceId in the check
        ]);

        if ($stmt->rowCount() == 0) {
            // Item does not exist for this invoice, insert it
            $insertStmt = $pdo->prepare("INSERT INTO invoiceItems (invoiceId, itemName, quantity, unit, unitPrice) VALUES (:invoiceId, :itemName, :quantity, :unit, :unitPrice)");
            $insertStmt->execute([
                ':invoiceId' => $item['invoiceId'],
                ':itemName' => $item['itemName'],
                ':quantity' => $item['quantity'],
                ':unit' => $item['unit'],
                ':unitPrice' => $item['unitPrice']
            ]);
        }
    }

    // Commit the transaction if all inserts were successful
    $pdo->commit();

    $response['success'] = true;
} catch (Exception $e) {
    // Rollback the transaction in case of an error
    $pdo->rollBack();
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
?>
