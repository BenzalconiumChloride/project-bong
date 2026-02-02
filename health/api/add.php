<?php
require_once '../../../global-library/database.php';
header('Content-Type: application/json');
session_start();

try {

    // ===============================
    // AUTH
    // ===============================
    if (empty($_SESSION['user_id'])) {
        throw new Exception('Unauthorized');
    }

    // ===============================
    // INPUTS
    // ===============================
    $fileName = trim($_POST['fileName'] ?? '');

    if ($fileName === '') {
        throw new Exception('File name is required');
    }

    // ===============================
    // FILE CHECK
    // ===============================
    if (!isset($_FILES['rFile']) || $_FILES['rFile']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File is required');
    }

    $file = $_FILES['rFile'];

    // Allowed types
    $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'webp'];
    $allowedMimeTypes  = [
        'application/pdf',
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $mimeType  = mime_content_type($file['tmp_name']);

    if (!in_array($extension, $allowedExtensions, true)) {
        throw new Exception('Invalid file type');
    }

    if (!in_array($mimeType, $allowedMimeTypes, true)) {
        throw new Exception('Invalid file content');
    }

    // ===============================
    // FILE STORAGE
    // ===============================
    $uploadDir = '../../assets/rqa-files/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $storedFileName = $fileName . uniqid('rqa_', true) . '.' . $extension;
    $destination    = $uploadDir . $storedFileName;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception('Failed to upload file');
    }

    // ===============================
    // INSERT
    // ===============================
    $stmt = $conn->prepare("
        INSERT INTO tblrqa (
            fileName,
            rFile,
            addedBy,
            dateAdded
        ) VALUES (?, ?, ?, NOW())
    ");

    $stmt->execute([
        $fileName,
        $storedFileName,
        $_SESSION['user_id']
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'File uploaded successfully'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>