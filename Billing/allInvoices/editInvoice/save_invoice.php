<?php
include('../../../config/dbcon.php');
include ('../../../auth/auth_check_admin.php');

$inputData = json_decode(file_get_contents("php://input"), true);

$invoiceId = $inputData['invoiceId'];
$invoiceItems = $inputData['invoiceItems'];

$stmt = $pdo->prepare("SELECT invoiceItemId FROM invoiceitems WHERE invoiceId = :invoiceId");
$stmt->execute([':invoiceId' => $invoiceId]);
$existingItems = $stmt->fetchAll(PDO::FETCH_COLUMN);

$updatedItems = [];
$receivedItems = [];

foreach ($invoiceItems as $item) {
    $invoiceItemId = $item['invoiceItemId'] ?? null;
    $itemName = $item['itemName'];
    $quantity = $item['quantity'];
    $unit = $item['unit'];
    $unitPrice = $item['rate'];

    if (!empty($invoiceItemId)) {
        $receivedItems[] = $invoiceItemId;

        $stmt = $pdo->prepare("SELECT * FROM invoiceitems WHERE invoiceItemId = :invoiceItemId AND invoiceId = :invoiceId");
        $stmt->execute([':invoiceItemId' => $invoiceItemId, ':invoiceId' => $invoiceId]);
        $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingItem) {
            $isChanged = false;
            if (
                $existingItem['itemName'] !== $itemName || $existingItem['quantity'] != $quantity ||
                $existingItem['unit'] !== $unit || $existingItem['unitPrice'] != $unitPrice
            ) {
                $isChanged = true;
            }

            if ($isChanged) {
                $updateStmt = $pdo->prepare("
                    UPDATE invoiceitems 
                    SET itemName = :itemName, quantity = :quantity, unit = :unit, unitPrice = :unitPrice 
                    WHERE invoiceItemId = :invoiceItemId AND invoiceId = :invoiceId
                ");
                $updateStmt->execute([
                    ':itemName' => $itemName,
                    ':quantity' => $quantity,
                    ':unit' => $unit,
                    ':unitPrice' => $unitPrice,
                    ':invoiceItemId' => $invoiceItemId,
                    ':invoiceId' => $invoiceId
                ]);
                $updatedItems[] = $item;
            }
        }
    } else {
        $invoiceItemId = uniqid('item_');
        $insertStmt = $pdo->prepare("
            INSERT INTO invoiceitems (invoiceItemId, invoiceId, itemName, quantity, unit, unitPrice) 
            VALUES (:invoiceItemId, :invoiceId, :itemName, :quantity, :unit, :unitPrice)
        ");
        $insertStmt->execute([
            ':invoiceItemId' => $invoiceItemId,
            ':invoiceId' => $invoiceId,
            ':itemName' => $itemName,
            ':quantity' => $quantity,
            ':unit' => $unit,
            ':unitPrice' => $unitPrice
        ]);
        $updatedItems[] = array_merge($item, ['invoiceItemId' => $invoiceItemId]);
        $receivedItems[] = $invoiceItemId;
    }
}

$itemsToDelete = array_diff($existingItems, $receivedItems);

if (!empty($itemsToDelete)) {
    $placeholders = implode(',', array_fill(0, count($itemsToDelete), '?'));
    $deleteStmt = $pdo->prepare("DELETE FROM invoiceitems WHERE invoiceItemId IN ($placeholders) AND invoiceId = ?");
    $deleteStmt->execute(array_merge($itemsToDelete, [$invoiceId]));
}

header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Invoice items saved successfully']);
