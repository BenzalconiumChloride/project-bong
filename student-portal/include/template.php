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
    <title><?php echo $pageTitle ?? 'DepEd Silay'; ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <?php include($_SERVER["DOCUMENT_ROOT"] . '/' . $webRoot . '/student-portal/include/global-css.php'); ?>


</head>

<body class="bg-theme bg-theme1">
    <div class="wrapper">

        <div class="sidebar-wrapper" data-simplebar="true">
            <?php include($_SERVER["DOCUMENT_ROOT"] . '/' . $webRoot . '/student-portal/include/left-menu.php'); ?>
        </div>

        <header>
            <?php include($_SERVER["DOCUMENT_ROOT"] . '/' . $webRoot . '/student-portal/include/header.php'); ?>
        </header>
    
        <div class="page-wrapper">
            <?php require_once $content; ?>

            <div class="overlay toggle-icon"></div>
        
        </div>

    </div>

    <?php include($_SERVER["DOCUMENT_ROOT"] . '/' . $webRoot . '/student-portal/include/right-menu.php'); ?>
    

    <?php include($_SERVER["DOCUMENT_ROOT"] . '/' . $webRoot . '/student-portal/include/global-js.php'); ?>

</body>

</html>