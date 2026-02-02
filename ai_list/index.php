<?php
require_once '../global-library/database.php';

if (isset($_GET['ai_list'])) {
    $view = 'AILIST';
} else {
    $view = '';
}

$currentPage = 'AILIST';

switch ($view) {
    case 'AILIST':
        $content   = 'ai_list.php';
        $pageTitle = 'AILIST';
        break;

    default:
        $content   = 'ai_list.php';
        $pageTitle = 'AILIST';
        break;
}

require_once '../include/template.php';
?>