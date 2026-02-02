<?php
require_once '../../../global-library/database.php';
header('Content-Type: application/json');

try {
    if (empty($_SESSION['user_id'])) {
        throw new Exception('Unauthorized');
    }

    $id = (int)($_POST['rId'] ?? 0);
    if (!$id) {
        throw new Exception('Invalid ID');
    }

    // 1. Fetch the filename before updating/deleting the record
    $stmtFetch = $conn->prepare("SELECT rFile FROM tblrqa WHERE rId = ? LIMIT 1");
    $stmtFetch->execute([$id]);
    $row = $stmtFetch->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $fileName = $row['rFile'];
        $filePath = "../../assets/rqa-files/" . $fileName;

        // 2. Delete the physical file from the folder if it exists
        if (!empty($fileName) && file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // 3. Update the database record (Soft Delete)
    // Note: If you want to remove it entirely from the DB, use: DELETE FROM tblrqa WHERE rId=?
    $stmt = $conn->prepare("UPDATE tblrqa SET isDeleted=1 WHERE rId=?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'File and record deleted successfully']);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>