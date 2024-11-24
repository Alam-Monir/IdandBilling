<?php
header('Content-Type: application/json');
include('../../config/dbcon.php');

try {
    $layoutId = $_POST['layoutId'] ?? null;

    if (!$layoutId) {
        echo json_encode(['success' => false, 'message' => 'Layout ID is required.']);
        exit;
    }

    $query = $pdo->prepare("SELECT * FROM idLayout WHERE id = :id");
    $query->execute([':id' => $layoutId]);
    $existingData = $query->fetch(PDO::FETCH_ASSOC);

    if (!$existingData) {
        echo json_encode(['success' => false, 'message' => 'Layout not found.']);
        exit;
    }

    $fieldsToUpdate = [];
    $params = [':id' => $layoutId];
    $uploadDir = '../img/uploads/';
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    function handleFileUpload($inputName, $dbColumn, &$fieldsToUpdate, &$params, $uploadDir, $allowedExtensions) {
        if (!empty($_FILES[$inputName]['name'])) {
            $fileName = basename($_FILES[$inputName]['name']);
            $filePath = $uploadDir . $fileName;
            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

            if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $filePath)) {
                    $fieldsToUpdate[] = "$dbColumn = :$dbColumn";
                    $params[":$dbColumn"] = $filePath;
                } else {
                    echo json_encode(['success' => false, 'message' => "Failed to upload $inputName."]);
                    exit;
                }
            } else {
                echo json_encode(['success' => false, 'message' => "Invalid file type for $inputName."]);
                exit;
            }
        }
    }

    handleFileUpload('bgImage', 'bgImage', $fieldsToUpdate, $params, $uploadDir, $allowedExtensions);
    handleFileUpload('schoolLogo', 'logo', $fieldsToUpdate, $params, $uploadDir, $allowedExtensions);
    handleFileUpload('principalSign', 'sign', $fieldsToUpdate, $params, $uploadDir, $allowedExtensions);

    foreach ($_POST as $field => $value) {
        if ($field !== 'layoutId' && $existingData[$field] != $value) {
            $fieldsToUpdate[] = "$field = :$field";
            $params[":$field"] = $value;
        }
    }

    if (empty($fieldsToUpdate)) {
        echo json_encode(['success' => true, 'message' => 'No changes detected.']);
        exit;
    }

    $sql = "UPDATE idLayout SET " . implode(', ', $fieldsToUpdate) . " WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute($params)) {
        echo json_encode(['success' => true, 'message' => 'Changes saved successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update data.']);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An internal error occurred.']);
}
?>
