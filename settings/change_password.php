<?php
include("../config/dbcon.php");

// Ensure response is always JSON
header('Content-Type: application/json');

// Initialize response
$response = ["status" => "error", "message" => "Something went wrong!"];

try {
    // Validate inputs
    $oldPassword = isset($_POST['oldPassword']) ? trim($_POST['oldPassword']) : null;
    $newPassword = isset($_POST['newPassword']) ? trim($_POST['newPassword']) : null;

    if (empty($oldPassword) || empty($newPassword)) {
        $response["message"] = "All fields are required!";
        echo json_encode($response);
        exit;
    }

    // Fetch admin details
    $stmt = $pdo->prepare("SELECT id, hashedPassword FROM users WHERE isAdmin = 1 LIMIT 1");
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        $response["message"] = "Admin not found!";
        echo json_encode($response);
        exit;
    }

    $adminId = $admin['id'];
    $hashedPassword = $admin['hashedPassword'] ?? '';

    // Verify old password
    if (!password_verify($oldPassword, $hashedPassword)) {
        $response["message"] = "Old password is incorrect!";
        echo json_encode($response);
        exit;
    }

    // Hash new password
    $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Update password in database
    $stmt = $pdo->prepare("UPDATE users SET hashedPassword = ? WHERE id = ?");
    $stmt->execute([$newHashedPassword, $adminId]);

    $response["status"] = "success";
    $response["message"] = "Password updated successfully!";
} catch (PDOException $e) {
    $response["message"] = "Database error: " . $e->getMessage();
}

echo json_encode($response);
?>
