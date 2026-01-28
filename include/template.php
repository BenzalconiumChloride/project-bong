<?php
if (!defined('WEB_ROOT')) {
    header('Location: ../index.php');
    exit;
}

$self = WEB_ROOT . 'index.php';

?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">

<head>
    <meta charset="utf-8">
    <title><?php echo $pageTitle ?? 'CRM'; ?></title>

    <meta name="author" content="themesflat.com">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <?php include($_SERVER["DOCUMENT_ROOT"] . '/' . $webRoot . '/include/global-css.php'); ?>


</head>

<?php
// $userId = $_SESSION['user_id'];

// $USERDATA = $conn->prepare("SELECT * FROM bs_user WHERE is_deleted = 0 AND user_id = ?");
// $USERDATA->execute([$userId]);
// $USER_DATAFETCH = $USERDATA->fetch(PDO::FETCH_ASSOC);

?>


<body class="main">

    <?php include($_SERVER["DOCUMENT_ROOT"] . '/' . $webRoot . '/include/preload.php'); ?>

    <?php include($_SERVER["DOCUMENT_ROOT"] . '/' . $webRoot . '/include/header.php'); ?>



        <?php require_once $content; ?>



    <?php include($_SERVER["DOCUMENT_ROOT"] . '/' . $webRoot . '/include/footer.php'); ?>


    <?php include($_SERVER["DOCUMENT_ROOT"] . '/' . $webRoot . '/include/global-js.php'); ?>

</body>

</html>