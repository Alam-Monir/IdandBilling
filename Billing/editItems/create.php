<?php
include('../../config/dbcon.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? trim($_POST['action']) : '';

    if ($action === 'searchItem') {
        $searchTerm = isset($_POST['itemName']) ? trim($_POST['itemName']) : '';

        try {
            if (!empty($searchTerm)) {
                $stmt = $pdo->prepare("SELECT itemId, itemName, itemPrice 
                               FROM items 
                               WHERE itemName LIKE :searchTerm 
                               ORDER BY itemName ASC");
                $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
            } else {
                $stmt = $pdo->prepare("SELECT itemId, itemName, itemPrice 
                               FROM items 
                               ORDER BY itemName ASC");
            }
            $stmt->execute();
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($items)) {
                echo json_encode(['status' => 'success', 'data' => $items]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No items found.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
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
