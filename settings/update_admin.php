<?php
include("../config/dbcon.php");

$response = ["status" => "error", "message" => "Something went wrong!"];

try {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    // Fetch admin ID
    $stmt = $pdo->prepare("SELECT id FROM users WHERE isAdmin = 1 LIMIT 1");
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        $response["message"] = "Admin not found!";
        echo json_encode($response);
        exit;
    }

    $adminId = $admin['id'];

    // Update admin details
    $stmt = $pdo->prepare("UPDATE users SET firstName = ?, lastName = ?, contact = ?, email = ? WHERE id = ?");
    $stmt->execute([$firstName, $lastName, $phone, $email, $adminId]);

    $response["status"] = "success";
    $response["message"] = "Admin details updated successfully!";
} catch (PDOException $e) {
    $response["message"] = "Database error: " . $e->getMessage();
}

echo json_encode($response);
?>
