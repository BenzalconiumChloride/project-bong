<?php
require_once 'global-library/database.php';
require_once 'include/functions.php';

checkUser();

if (isset($_SESSION['user_id'])) {
	$userId = $_SESSION['user_id'];
} else {
}

$currentPage = 'Home';
$content = 'home/home.php';
$pageTitle = 'Home';

require_once 'include/template.php';