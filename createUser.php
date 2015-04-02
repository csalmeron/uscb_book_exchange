<?php

require_once 'Model/theModel.php';

$email = isset($_POST['email']);
$emailHash = isset($_POST['hash']);
$password = isset($_POST['pw']);
$passwordConfirm = isset($_POST['pwc']);
$theFirstName = isset($_POST['fName']);
$theLastName = isset($_POST['lName']);
$theSchedule = isset($_POST['schedule']);

$theUser = isset($_SESSION['user']);

//echo $email . " 1 " . $emailHash . " 2 " . $password . " 3 " . $passwordConfirm . " 4 " . $theFirstName . " 5 " . $theLastName . " 6 " . $theSchedule . " 7 " . $theUser;

if ($email && $emailHash && $password && $passwordConfirm && $theFirstName && $theLastName && !$theUser) {

	$email = $_POST['email'];
	$emailHash = $_POST['hash'];
	$password = $_POST['pw'];
	$passwordConfirm = $_POST['pwc'];
	$theFirstName = $_POST['fName'];
	$theLastName = $_POST['lName'];

	if (Email::hashEmailOrPassword($email) == $emailHash) {

		if (Password::comparePassword($password, $passwordConfirm)) {

			if (Password::isGoodPassword($password)) {

				$dbh = new DB();

				$emailID = $dbh -> getEmailIDByEmailName($email);

				//verify the user does not already exist
				if (!$emailID) {

					//email does not exist, so attempt to insert the email into the DB
					if ($dbh -> insertEmail($email)) {

						$emailID = $dbh -> getEmailIDByEmailName($email);

						if (!$emailID) {

							echo ' an error occured!';

						} else {

							if ($theSchedule) {

								$theSchedule = $_POST['schedule'];

							} else {

								$theSchedule = NULL;

							}
							//HERE

							//password is validated, so hash the password to upload into the DB
							$hashedPW = Password::hashPassword($password);

							//all data is validated, so we will create an account
							//we do not set a userID, since this is created at the DB level

							$newUser = new User();

							$newUser -> setEmailID($emailID);

							$newUser -> setFirstName($theFirstName);

							$newUser -> setLastName($theLastName);

							$newUser -> setSchedule($theSchedule);

							$newUser -> setPasswordHash($hashedPW);

							if ($dbh -> insertUser($newUser)) {

								echo 'Account successfully created. Please log in to continue.';

							} else {

								echo ' An error occured creating your account!';

							}

						}

					} else {

						echo ' an error occured trying to create your account!';

					}
				}//end if
				else {

					echo ' The user already exists!';

				}//end else

			} else {

				echo 'password does not fit requirements!';

			}
		}//end if

		else {

			echo 'passwords do not match!';

		}

	}//end if

	else {
		echo ' Email tampered with. Please enter your corrrect USCB email!';

	}
}//end if

else {

	echo ' please complete all fields.';

}
?>
