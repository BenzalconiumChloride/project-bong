<?php
require_once '../../global-library/database.php';

if (isset($_GET['math-game'])) {
    $view = 'Math Game';
} else {
    $view = '';
}

$currentPage = 'Math Game';

switch ($view) {
    case 'Math Game':
        $content   = 'math-game.php';
        $pageTitle = 'Math Game';
        break;

    default:
        $content   = 'math-game.php';
        $pageTitle = 'Math Game';
        break;
}

require_once '../include/template.php';
?>