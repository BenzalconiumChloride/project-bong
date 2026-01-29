<?php
require_once '../../global-library/database.php';

if (isset($_GET['rqa'])) {
    $view = 'RQA';
} else {
    $view = '';
}

$currentPage = 'RQA';

switch ($view) {
    case 'RQA':
        $content   = 'rqa.php';
        $pageTitle = 'RQA';
        break;

    default:
        $content   = 'rqa.php';
        $pageTitle = 'RQA';
        break;
}

require_once '../include/template.php';
?>