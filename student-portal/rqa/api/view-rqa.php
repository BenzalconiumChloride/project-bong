<?php
require_once '../../../global-library/database.php';

if (empty($_GET['id'])) {
    die('Invalid request');
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("
    SELECT fileName, rFile, dateAdded
    FROM tblrqa
    WHERE rId = ?
      AND isDeleted = 0
    LIMIT 1
");
$stmt->execute([$id]);
$rqa = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$rqa) {
    die('Record not found');
}

$ext = strtolower(pathinfo($rqa['rFile'], PATHINFO_EXTENSION));
$isImage = in_array($ext, ['jpg','jpeg','png','webp']);

// IMPORTANT: Ensure this path correctly leads to your uploads folder
// If view-rqa.php is in administrator-page/rqa/api/, 
// and uploads is in the root, the path below is likely correct.
$filePath = "../../assets/rqa-files/" . $rqa['rFile'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View - <?= htmlspecialchars($rqa['fileName']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        .view-container {
            height: 85vh; /* Sets height to 85% of viewport */
            width: 100%;
            border: 1px solid #ddd;
            background: #525659; /* Matches typical PDF viewer background */
        }
        iframe {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid mt-3">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><?= htmlspecialchars($rqa['fileName']) ?></h5>
                <small class="text-muted">Posted on <?= $rqa['dateAdded'] ?></small>
            </div>
            <div>
                <a href="<?= WEB_ROOT; ?>administrator-page/rqa/" class="btn btn-sm btn-secondary">Back</a>
                <a href="<?= $filePath ?>" class="btn btn-sm btn-primary" download>Download</a>
            </div>
        </div>
        <div class="card-body p-0"> <?php if ($isImage): ?>
                <div class="text-center p-3">
                    <img src="<?= $filePath ?>" class="img-fluid rounded">
                </div>
            <?php else: ?>
                <div class="view-container">
                    <iframe 
                        src="<?= $filePath ?>#toolbar=1&navpanes=0&scrollbar=1" 
                        type="application/pdf">
                    </iframe>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

</body>
</html>