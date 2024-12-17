<?php
include('../config/dbcon.php');

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $stmt = $pdo->prepare("SELECT customerName, customerAddress, customerContact FROM customers WHERE customerName LIKE :query LIMIT 10");
    $stmt->execute(['query' => "$query%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);
}
?>
