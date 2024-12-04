<?php
include('../../config/dbcon.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    if ($action === 'create') {
        $customerId = bin2hex(random_bytes(16));
        $name = trim($_POST['name'] ?? '');
        $contact = trim($_POST['contact'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if (!empty($name) && !empty($contact) && !empty($address)) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO customers (customerId, customerName, customerContact, customerAddress) 
                    VALUES (:customerId, :name, :contact, :address)
                ");
                $stmt->bindParam(':customerId', $customerId);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':contact', $contact);
                $stmt->bindParam(':address', $address);
                $stmt->execute();

                echo 'success';
            } catch (PDOException $e) {
                echo 'Database error: ' . $e->getMessage();
            }
        } else {
            echo 'Invalid action.';
        }
    } elseif ($action === 'delete') {
        // Handle delete action
        $customerId = $_POST['customerId'] ?? null;

        if ($customerId) {
            $stmt = $pdo->prepare("DELETE FROM customers WHERE customerId = :customerId");
            $stmt->execute(['customerId' => $customerId]);

            echo 'success';
        } else {
            echo 'Invalid customer ID for delete.';
        }
    } elseif ($action === 'edit') {
        // Handle edit action
        $customerId = $_POST['customerId'] ?? null;
        $customerName = $_POST['customerName'] ?? null;
        $customerContact = $_POST['customerContact'] ?? null;
        $customerAddress = $_POST['customerAddress'] ?? null;

        if ($customerId) {
            $stmt = $pdo->prepare("UPDATE customers SET customerName = :customerName, customerContact = :customerContact, customerAddress = :customerAddress WHERE customerId = :customerId");
            $stmt->execute([
                'customerName' => $customerName,
                'customerContact' => $customerContact,
                'customerAddress' => $customerAddress,
                'customerId' => $customerId,
            ]);

            echo 'success';
        } else {
            echo 'Invalid input data for edit.';
        }
    } else {
        echo 'Please provide all required fields.';
    }
} else {
    echo 'Invalid request method.';
}
