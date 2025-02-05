<?php
include("../../config/dbcon.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    try {
        if ($action === 'edit') {
            // Handle edit action
            $itemId = $_POST['itemId'] ?? null;
            $itemName = $_POST['itemName'] ?? null;
            $quantity = $_POST['quantity'] ?? null;
            $itemPrice = $_POST['itemPrice'] ?? null;

            if ($itemId && is_numeric($itemPrice)) {
                $stmt = $pdo->prepare("UPDATE items SET itemName = :itemName, quantity = :quantity, itemPrice = :itemPrice WHERE itemId = :itemId");
                $stmt->execute([
                    'itemName' => $itemName,
                    'quantity' => $quantity,
                    'itemPrice' => $itemPrice,
                    'itemId' => $itemId,
                ]);

                echo json_encode(['success' => true, 'message' => 'Item updated successfully']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Invalid input data for edit.']);
            }
        } elseif ($action === 'delete') {
            // Handle delete action
            $itemId = $_POST['itemId'] ?? null;

            if ($itemId) {
                $stmt = $pdo->prepare("DELETE FROM items WHERE itemId = :itemId");
                $stmt->execute(['itemId' => $itemId]);

                echo json_encode(['success' => true, 'message' => 'Item deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Invalid item ID for delete.']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid action.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
