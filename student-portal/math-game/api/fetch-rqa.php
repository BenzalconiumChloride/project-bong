<?php
require_once '../../../global-library/database.php';
header('Content-Type: application/json');

try {

    // FETCH ALL RQA
    $stmt = $conn->prepare("
        SELECT
            rId,
            fileName,
            rFile,
            dateAdded
        FROM tblrqa
        WHERE isDeleted = 0
        ORDER BY dateAdded DESC
    ");

    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $rows
    ]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Fetch failed'
    ]);
}
?>