<?php
include('../../config/dbcon.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';

    if ($action === 'deleteItem') {
        $itemName = isset($_POST['itemName']) ? trim($_POST['itemName']) : '';

        if (empty($itemName)) {
            echo "Item name is required to delete.";
            exit;
        }

        try {
            $stmt = $pdo->prepare("DELETE FROM items WHERE itemName = :itemName");
            $stmt->bindParam(':itemName', $itemName);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo "Item successfully deleted!";
            } else {
                echo "Item not found.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } elseif ($action === 'createItem') {
        $itemName = isset($_POST['itemName']) ? trim($_POST['itemName']) : '';
        $itemPrice = isset($_POST['itemPrice']) ? trim($_POST['itemPrice']) : '';

        if (empty($itemName) || empty($itemPrice)) {
            echo "All fields are required.";
            exit;
        }

        $itemId = bin2hex(random_bytes(16));

        try {
            $stmt = $pdo->prepare("INSERT INTO items (itemId, itemName, itemPrice) VALUES (:itemId, :itemName, :itemPrice)");
            $stmt->bindParam(':itemId', $itemId);
            $stmt->bindParam(':itemName', $itemName);
            $stmt->bindParam(':itemPrice', $itemPrice);
            $stmt->execute();

            echo "Item successfully created!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
