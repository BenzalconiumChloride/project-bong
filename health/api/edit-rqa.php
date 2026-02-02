<?php
require_once '../../../global-library/database.php';
header('Content-Type: application/json');
session_start();

try {
    if (empty($_SESSION['user_id'])) {
        throw new Exception('Unauthorized');
    }

    $id = (int)($_POST['rId'] ?? 0);
    if (!$id) {
        throw new Exception('Invalid ID');
    }

    $displayTitle = trim($_POST['fileName'] ?? '');
    if (!$displayTitle) {
        throw new Exception('File display name is required');
    }

    // Initialize parameters for the SQL update
    // We update the display name by default
    $sqlFields = "fileName = ?";
    $params = [$displayTitle];

    // ==========================================
    // FILE UPLOAD (Only if a new file is chosen)
    // ==========================================
    if (!empty($_FILES['rFile']['name'])) {
        
        $uploadDir = "../../assets/rqa-files/";
        
        // Ensure directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $originalName = $_FILES['rFile']['name'];
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        
        // Sanitize the display name to use as part of the physical filename
        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $displayTitle);
        
        // Create Unique Filename: FileName + UniqueID + Extension
        $uniqueFileName = $safeName . '_' . uniqid() . '.' . $ext;

        if (move_uploaded_file($_FILES['rFile']['tmp_name'], $uploadDir . $uniqueFileName)) {
            // Add the file update to the SQL string
            $sqlFields .= ", rFile = ?";
            $params[] = $uniqueFileName;
        } else {
            throw new Exception('Failed to move uploaded file.');
        }
    }

    // Add final parameters for WHERE clause
    $params[] = $id;

    $stmt = $conn->prepare("
        UPDATE tblrqa 
        SET $sqlFields 
        WHERE rId = ? 
          AND isDeleted = 0
    ");

    $stmt->execute($params);

    echo json_encode([
        'success' => true,
        'message' => 'RQA updated successfully'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>