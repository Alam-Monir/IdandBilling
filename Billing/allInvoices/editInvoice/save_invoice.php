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

    // Get the current items from the database for the provided invoiceId
    $invoiceId = $items[0]['invoiceId'];  // Assuming all items have the same invoiceId
    $existingItemsStmt = $pdo->prepare("SELECT invoiceItemId FROM invoiceItems WHERE invoiceId = :invoiceId");
    $existingItemsStmt->execute([':invoiceId' => $invoiceId]);

    // Get all the existing invoiceItemIds from the database
    $existingItems = $existingItemsStmt->fetchAll(PDO::FETCH_ASSOC);
    $existingItemIds = array_map(function ($item) {
        return $item['invoiceItemId'];
    }, $existingItems);

    // Track which items need to be deleted
    $incomingItemIds = [];

    foreach ($items as $item) {
        // If invoiceItemId is missing, generate a unique ID
        if (!isset($item['invoiceItemId']) || empty($item['invoiceItemId'])) {
            $item['invoiceItemId'] = uniqid("item_");  // Generate a unique ID for new items
        }

        // Add the itemId to the incoming list for deletion check later
        $incomingItemIds[] = $item['invoiceItemId'];

        // Check if the item already exists using the invoiceItemId
        $stmt = $pdo->prepare("SELECT * FROM invoiceItems WHERE invoiceItemId = :invoiceItemId LIMIT 1");
        $stmt->execute([':invoiceItemId' => $item['invoiceItemId']]);

        if ($stmt->rowCount() == 0) {
            // Item does not exist for this invoice, insert it as new
            $insertStmt = $pdo->prepare("INSERT INTO invoiceItems (invoiceItemId, invoiceId, itemName, quantity, unit, unitPrice) 
            VALUES (:invoiceItemId, :invoiceId, :itemName, :quantity, :unit, :unitPrice)");
            $insertStmt->execute([
                ':invoiceItemId' => $item['invoiceItemId'],  // Use the generated or provided invoiceItemId
                ':invoiceId' => $item['invoiceId'],
                ':itemName' => $item['itemName'],
                ':quantity' => $item['quantity'],
                ':unit' => $item['unit'],
                ':unitPrice' => $item['unitPrice']
            ]);
        } else {
            // Item exists, check if any field has changed
            $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

            // Prepare update fields and parameters
            $updateFields = [];
            $updateParams = [':invoiceItemId' => $item['invoiceItemId']];

            // Compare fields and add to the update fields if changed
            if ($existingItem['quantity'] != $item['quantity']) {
                $updateFields[] = 'quantity = :quantity';
                $updateParams[':quantity'] = $item['quantity'];
            }

            if ($existingItem['unit'] != $item['unit']) {
                $updateFields[] = 'unit = :unit';
                $updateParams[':unit'] = $item['unit'];
            }

            if ($existingItem['unitPrice'] != $item['unitPrice']) {
                $updateFields[] = 'unitPrice = :unitPrice';
                $updateParams[':unitPrice'] = $item['unitPrice'];
            }

            if ($existingItem['itemName'] != $item['itemName']) {
                $updateFields[] = 'itemName = :itemName';
                $updateParams[':itemName'] = $item['itemName'];
            }

            // If any field has changed, update the item
            if (!empty($updateFields)) {
                $updateQuery = "UPDATE invoiceItems SET " . implode(', ', $updateFields) . " WHERE invoiceItemId = :invoiceItemId";
                $updateStmt = $pdo->prepare($updateQuery);
                $updateStmt->execute($updateParams);
            }
        }
    }

    // Delete items that are no longer in the incoming request
    $itemsToDelete = array_diff($existingItemIds, $incomingItemIds);  // Find which items to delete

    if (!empty($itemsToDelete)) {
        $deleteStmt = $pdo->prepare("DELETE FROM invoiceItems WHERE invoiceItemId IN (" . implode(',', array_map(function ($id) {
            return "'" . $id . "'";
        }, $itemsToDelete)) . ")");
        $deleteStmt->execute();
    }

    // Commit the transaction if all operations were successful
    $pdo->commit();

    $response['success'] = true;
} catch (Exception $e) {
    // Rollback the transaction in case of an error
    $pdo->rollBack();
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
