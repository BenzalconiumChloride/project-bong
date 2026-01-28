<?php
if (!defined('WEB_ROOT')) {
	header('Location: index');
	exit;
}

if (isset($_GET['logout'])) {
	doLogout();
}

function checkUser()
{
	if (!isset($_SESSION['user_id'])) {
		header('Location: ' . WEB_ROOT . 'login');
		exit;
	}

}

function doLogin()
{	
	include SRV_ROOT .'global-library/database.php';
	
	$emailAddress = $_POST['txtEmailAddress'];
	$password = $_POST['txtPassword'];		

	if ($emailAddress == "") {

		$data = ["emailAddress" => null, "message" => "You must enter your email address"];
	} else if ($password == "") {

		$data = ["emailAddress" => "$emailAddress", "message" => "You must enter the password"];
	} else {

		$chk = $conn->prepare("SELECT user_id, password FROM bs_user WHERE email = '$emailAddress' AND is_deleted != '1'");
		$chk->execute();

		if($chk->rowCount() > 0)
		{
			while($chk_data = $chk->fetch())
			{
				$hashedPassword = $chk_data['password'];

				if (password_verify($password, $hashedPassword)) {
					
					$_SESSION['user_id'] = $chk_data['user_id'];

	
					$sql = $conn->prepare("UPDATE bs_user SET last_login = '$today_date1' WHERE user_id = '{$chk_data['user_id']}'");
					$sql->execute();


					if (isset($_SESSION['login_return_url'])) {
							
						header('Location:' . WEB_ROOT . '');
						exit;

					} else {
						
						header('Location:' . WEB_ROOT . '');
						exit;
					}

				} else {

					$data = ["emailAddress" => "$emailAddress", "message" => "Invalid password, please try again."];

					return $data;
				}
			}
		} else {
			$data = ["emailAddress" => null, "message" => "Invalid email address, please try again."];

			return $data;
		}
	}
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

	header('Location:' . WEB_ROOT . '');
	exit;
}
?>
