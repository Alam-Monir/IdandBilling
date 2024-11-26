<?php
require '../../config/dbcon.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $layoutType = $_POST['layoutType'];
        $layoutName = $_POST['layoutName'];
        $schoolName = $_POST['schoolName'];
        $schoolAddress = $_POST['schoolAddress'];

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

        $query = "INSERT INTO idLayout (id, layoutType, layoutName, schoolName, schoolAdd, bgImage, logo, sign, createdAt, updatedAt)
                  VALUES (UUID(), :layoutType, :layoutName, :schoolName, :schoolAddress, :bgImage, :logo, :sign, NOW(), NOW())";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':layoutType', $layoutType);
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

header('Content-Type: application/json');
echo json_encode($response);
?>
