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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@700&family=Fredoka+One&display=swap" rel="stylesheet">
    
    <?php include($_SERVER["DOCUMENT_ROOT"] . '/' . $webRoot . '/student-portal/include/global-css.php'); ?>


</head>

<?php
// $userId = $_SESSION['user_id'];

// $USERDATA = $conn->prepare("SELECT * FROM bs_user WHERE is_deleted = 0 AND user_id = ?");
// $USERDATA->execute([$userId]);
// $USER_DATAFETCH = $USERDATA->fetch(PDO::FETCH_ASSOC);

?>


<body class="main">


    <?php include($_SERVER["DOCUMENT_ROOT"] . '/' . $webRoot . '/student-portal/include/header.php'); ?>



        <?php require_once $content; ?>



    


    <?php include($_SERVER["DOCUMENT_ROOT"] . '/' . $webRoot . '/student-portal/include/global-js.php'); ?>

</body>

</html>