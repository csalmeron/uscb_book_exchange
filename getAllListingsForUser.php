<?php
require_once 'Model/theModel.php';


$user = isset($_SESSION['user']);
$listing = isset($_GET['listing']);

//$listing = 1;
//$user = 1;

if ($user && $listing) {
	
	$user = $_SESSION['user'];

	$userID = $user -> getUserID();
	
	//$userID = 5;

	$dbh = new DB();

	$result = $dbh -> getListingsByUserID($userID);

	if (!$result) {

		echo 'user has no listing or error';

	}// end of if statement

	else {
		
		echo json_encode($result);
		
		

	}//end of else

}//end if statement
else {
	echo 'user needs to log in to view this information';

}
?>