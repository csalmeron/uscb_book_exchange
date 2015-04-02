<?php
require_once 'Model/theModel.php';

//login/logout module for zach to code
$email = isset($_POST['email']);
$password = isset($_POST['password']);
$logout = isset($_GET['logout']);

$theUser = isset($_SESSION['user']);

if ($email && $password && !$theUser) {

	$email = $_POST['email'];
	$password = $_POST['password'];

	$dbh = new DB();

	$emailID = $dbh -> getEmailIDByEmailName($email);

	//user account does not exist
	if (!$emailID) {

		echo 'Error with username or password. Please re-enter credentials to log in.';

	} else {

		$userToLogIn = $dbh -> getUserByEmailID($emailID);

		if (!$userToLogIn) {

			echo 'Error with username or password. Please re-enter credentials to log in.';

		} else {
			
			//echo print_r($userToLogIn);

			$pwHash = Password::hashPassword($password);

			$accountPWHash = $userToLogIn -> getPasswordHash();

			if (Password::comparePassword($pwHash, $accountPWHash)) {

				//stores the user object in a session called $_SESSION['user']
				$userToLogIn -> login();
				
				echo 'Welcome, ' . $userToLogIn->getFirstName() . '.';

			} else {

				echo 'Error with username or password. Please re-enter credentials to log in.';

			}

		}

	}

} elseif ($logout && $theUser) {
	
	$logout = $_GET['logout'];

	$theUser = $_SESSION['user'];

	if ($theUser -> logout()) {

		echo ' logged out successfully';
		//redirect to the homepage

	} else {

		echo ' we could not log you out!';

	}
} else {
	
	echo 'Please Log in or register with us!';
}


?>