<?php
include("../config/dbcon.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $layoutName = $_POST['layoutName'] ?? null;

    header('Content-Type: application/json');

    if ($layoutName) {
        try {
            $stmt = $pdo->prepare("DELETE FROM idLayout WHERE layoutName = :layoutName");
            $stmt->execute(['layoutName' => $layoutName]);

            echo json_encode(['success' => true, 'message' => 'Layout deleted successfully']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid layout name.']);
    }
    exit;
}
?>
