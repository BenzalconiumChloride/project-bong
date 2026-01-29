<?php
require_once '../global-library/database.php';
require_once 'include/functions.php';

// Check if user is logged in
checkUser();

// Check if user is a teacher (access_level = 1)
if (isset($_SESSION['user_id'])) {
	$userId = $_SESSION['user_id'];
	
	// Get user's access level from database
	$stmt = $conn->prepare("SELECT access_level FROM bs_user WHERE user_id = ? AND is_deleted != '1'");
	$stmt->execute([$userId]);
	
	if ($stmt->rowCount() > 0) {
		$userData = $stmt->fetch();
		$accessLevel = $userData['access_level'];
		
		// If user is a student (access_level = 0), redirect to student portal
		if ($accessLevel == 0) {
			header('Location: ' . WEB_ROOT . 'student-portal/');
			exit;
		}
		// If access_level is neither 0 nor 1, redirect to home
		else if ($accessLevel != 1) {
			header('Location: ' . WEB_ROOT . '');
			exit;
		}
		// If access_level = 1 (teacher), allow access to continue
	} else {
		// User not found in database, logout
		session_destroy();
		header('Location: ' . WEB_ROOT . '');
		exit;
	}
} else {
	// Not logged in, redirect to home
	header('Location: ' . WEB_ROOT . '');
	exit;
}

$currentPage = 'Home';
$content = 'home/home.php';
$pageTitle = 'Home';

require_once 'include/template.php';

?>