<?php

require_once 'PHPMailer-master/PHPMailerAutoload.php';

//this is the email class
class Email {

	private $mail;
	private $connectionString;

	function __construct() {
		$this -> connectionString = "http://localhost:8080/uscbtextbookexchange/";

		$this -> mail = new PHPMailer();

		//$mail->SMTPDebug = 3;                               // Enable verbose debug output

		$this -> mail -> isSMTP();
		// Set mailer to use SMTP
		$this -> mail -> Host = 'smtp.gmail.com';
		// Specify main and backup SMTP servers
		$this -> mail -> SMTPAuth = true;
		// Enable SMTP authentication
		$this -> mail -> Username = 'uscbtextbook@gmail.com';
		// SMTP username
		$this -> mail -> Password = 'yoloswag420';
		// SMTP password
		$this -> mail -> SMTPSecure = 'tls';
		// Enable TLS encryption, `ssl` also accepted
		$this -> mail -> Port = 587;
		// TCP port to connect to

		$this -> mail -> From = 'uscbtextbook@gmail.com';
		$this -> mail -> FromName = 'USCB Textbook Exchange';

		$this -> mail -> isHTML(true);
		// Set email format to HTML

	}

	public function isValidUSCBEmail($email) {

		if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {

			if (strpos($email, '@email.sc.edu')) {
				return TRUE;
			} else if (strpos($email, '@email.uscb.edu')) {
				return TRUE;
			} else if (strpos($email, '@uscb.edu')) {
				return TRUE;
			} else {
				return FALSE;
			}

		} else {
			return FALSE;
		}

	}

	public static function hashEmailOrPassword($emailToHash) {

		$hash = hash('sha256', $emailToHash);

		return $hash;
	}

	private function sendEmail($to, $subject, $body) {

		$this -> mail -> addAddress($to);
		// Add a recipient

		$this -> mail -> Subject = $subject;
		$this -> mail -> Body = $body;
		$this -> mail -> AltBody = $body;

		if (!$this -> mail -> send()) {

			return FALSE;

		} else {

			return TRUE;

		}

	}

	function sendValidationEmail($email) {

		$emailHash = $this -> hashEmailOrPassword($email);

		$body = 'Please click the link to register your email. ';

		$getRequest = "<a href=" . $this -> connectionString . "verifyEmailP2.php?email=" . $email . "&code=" . $emailHash . "> Click Here!</a>";

		$body = $body . $getRequest;

		$subject = "Register for USCB Textbook Exchange";

		if ($this -> sendEmail($email, $subject, $body)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function sendPasswordResetRequest($to, $password) {

		$emailHash = $this -> hashEmailOrPassword($to);

		$passwordHash = $this -> hashEmailOrPassword($password);

		$body = 'Please click the link to reset your password. ';

		$getRequest = "<a href=" . $this -> connectionString . "forgotPassword.php?email=" . $to . "&code1=" . $emailHash . "&code2=" . $passwordHash . "> Click Here!</a>";

		$body = $body . $getRequest;

		$subject = "Reset Password for USCB Textbook Exchange";

		if ($this -> sendEmail($to, $subject, $body)) {
			return TRUE;
		} else {
			return FALSE;
		}

	}

}//end class Email
?>