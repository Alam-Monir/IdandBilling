<?php
require '../../config/dbcon.php'; // Ensure you have a PDO connection in this file

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $layoutName = $_POST['layoutName'];
        $schoolName = $_POST['schoolName'];
        $schoolAddress = $_POST['schoolAddress'];

        // File upload handling
        $bgImage = null;
        $schoolLogo = null;
        $principalSign = null;

        if (isset($_FILES['bgImage']) && $_FILES['bgImage']['error'] === UPLOAD_ERR_OK) {
            $bgImage = '../img/uploads/' . basename($_FILES['bgImage']['name']);
            move_uploaded_file($_FILES['bgImage']['tmp_name'], $bgImage);
        }

        if (isset($_FILES['schoolLogo']) && $_FILES['schoolLogo']['error'] === UPLOAD_ERR_OK) {
            $schoolLogo = '../img/uploads/' . basename($_FILES['schoolLogo']['name']);
            move_uploaded_file($_FILES['schoolLogo']['tmp_name'], $schoolLogo);
        }

        if (isset($_FILES['principalSign']) && $_FILES['principalSign']['error'] === UPLOAD_ERR_OK) {
            $principalSign = '../img/uploads/' . basename($_FILES['principalSign']['name']);
            move_uploaded_file($_FILES['principalSign']['tmp_name'], $principalSign);
        }

        // Insert into the database
        $query = "INSERT INTO idLayout (id, layoutName, schoolName, schoolAdd, bgImage, logo, sign, createdAt, updatedAt)
                  VALUES (UUID(), :layoutName, :schoolName, :schoolAddress, :bgImage, :logo, :sign, NOW(), NOW())";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':layoutName', $layoutName);
        $stmt->bindParam(':schoolName', $schoolName);
        $stmt->bindParam(':schoolAddress', $schoolAddress);
        $stmt->bindParam(':bgImage', $bgImage);
        $stmt->bindParam(':logo', $schoolLogo);
        $stmt->bindParam(':sign', $principalSign);

        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['message'] = 'Database error: Unable to save layout.';
        }
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid request method.';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
