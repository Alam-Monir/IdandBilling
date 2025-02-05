<?php
include('../../config/dbcon.php');
include ('../../auth/auth_check_admin.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['invoiceId'])) {
        $invoiceId = $_POST['invoiceId'];

        try {
            $pdo->beginTransaction();

            $stmtItems = $pdo->prepare("DELETE FROM invoiceItems WHERE invoiceId = :invoiceId");
            $stmtItems->bindParam(':invoiceId', $invoiceId, PDO::PARAM_INT);
            $stmtItems->execute();

            $stmtInvoice = $pdo->prepare("DELETE FROM invoices WHERE invoiceId = :invoiceId");
            $stmtInvoice->bindParam(':invoiceId', $invoiceId, PDO::PARAM_INT);
            $stmtInvoice->execute();

            $pdo->commit();

            echo "success";
        } catch (PDOException $e) {
            $pdo->rollBack();

            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Invalid request.";
    }
} else {
    echo "Invalid request method.";
}
