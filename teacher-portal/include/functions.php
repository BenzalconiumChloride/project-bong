<?php

if (isset($_GET['logout'])) {
	doLogout();
}

function checkUser()
{
	if (!isset($_SESSION['user_id'])) {
		header('Location: ' . WEB_ROOT . 'student-portal/login.php');
		exit;
	}

}

function doLogin()
{
    include SRV_ROOT . 'global-library/database.php';

    $emailAddress = trim($_POST['txtEmailAddress'] ?? '');
    $password     = $_POST['txtPassword'] ?? '';

    // ONE generic response only
    $genericError = [
        "emailAddress" => $emailAddress ?: null,
        "message" => "Invalid credentials. Please try again.",
        "status" => "error"
    ];

    // Basic empty check (still generic)
    if ($emailAddress === '' || $password === '') {
        return $genericError;
    }

    // Secure prepared statement (NO SQL injection)
    $stmt = $conn->prepare("
        SELECT user_id, password
        FROM bs_user
        WHERE email = :email
          AND is_deleted != '1'
        LIMIT 1
    ");
    $stmt->execute(['email' => $emailAddress]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Dummy hash to prevent timing attacks
    $hash = $user['password'] ?? '$2y$10$usesomesillystringforsalt$';

    // Always verify password
    $passwordValid = password_verify($password, $hash);

    // Unified failure path
    if (!$user || !$passwordValid) {
        sleep(1); // slow brute force
        return $genericError;
    }

    // SUCCESS — regenerate session to prevent fixation
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['user_id'];

    // Update last login safely
    $update = $conn->prepare("
        UPDATE bs_user
        SET last_login = NOW()
        WHERE user_id = :id
    ");
    $update->execute(['id' => $user['user_id']]);

    // Redirect (unchanged behavior)
    	header('Location: ' . WEB_ROOT . 'student-portal/');
    exit;
}


/*
	Logout a user
*/
function doLogout()
{
	if (isset($_SESSION['user_id'])) {
		unset($_SESSION['user_id']);

		session_unset();
		session_destroy();

		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		}
	}

	header('Location:' . WEB_ROOT . 'student-portal/');
	exit;
}
?>
