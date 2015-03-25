<?php


require_once 'Model/DB.php';

$listingID = isset($_POST['listingID']);
$theUser = isset($_SESSION['user']);

//FOR TESTING PURPOSES ONLY, COMMENT OUT FOR LIVE
//$listingID = 1;
//$userID = 1;

if($listingID && $theUser){
	
	//FOR TESTING PURPOSES ONLY, COMMENT OUT FOR LIVE
	//$listingID = 3;
	//$userID = 9;
	
	$listingID = $_POST['listingID'];
	$theUser = $_SESSION['userID'];
	
	$userID = $theUser->getUserID();
	
	$dbh = new DB();
	
	if($dbh->deleteListingByListingIDAndUserID($listingID,$userID)){
		
		echo 'listing deleted successfully';
		
	}
	else{
		
		echo 'an error has occured';
	}
	
}
else
	 {
	
	//do nothing
	
   }



?>