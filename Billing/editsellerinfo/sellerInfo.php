<?php
require '../../config/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sellerName = $_POST['sellerName'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $gstNo = $_POST['gstNo'] ?? '';
    $address = $_POST['address'] ?? '';

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM sellerInfo");
    $stmt->execute();
    $exists = $stmt->fetchColumn();

    if ($exists > 0) {
        $stmt = $pdo->prepare("UPDATE sellerInfo SET sellerName = ?, contact = ?, gstNo = ?, address = ? LIMIT 1");
        $stmt->execute([$sellerName, $contact, $gstNo, $address]);
        echo json_encode(['status' => 'updated']);
    } else {
        $stmt = $pdo->prepare("INSERT INTO sellerInfo (sellerName, contact, gstNo, address) VALUES (?, ?, ?, ?)");
        $stmt->execute([$sellerName, $contact, $gstNo, $address]);
        echo json_encode(['status' => 'inserted']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT * FROM sellerInfo LIMIT 1");
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($data);
}
?>