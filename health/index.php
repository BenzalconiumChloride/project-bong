<?php

require_once '../global-library/database.php';


if (isset($_GET['health'])) {
    $view = 'HEALTH';
} else {
    $view = '';
}

$currentPage = 'HEALTH';

switch ($view) {
    case 'HEALTH':
        $content   = 'health.php';
        $pageTitle = 'HEALTH';
        break;

    default:
        $content   = 'health.php';
        $pageTitle = 'HEALTH';
        break;
}

require_once '../include/template.php';
?>