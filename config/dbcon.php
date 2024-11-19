<?php
$host = "localhost";
$db = "idandbilling";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}
?>