<?php




require_once 'Model/theModel.php';


$listingID = isset($_POST['listingID']);
$theUser = isset($_SESSION['user']);

//FOR TESTING PURPOSES ONLY, COMMENT OUT FOR LIVE
//$listingID = 1;
//$theUser = 1;

if($listingID && $theUser){
	
	//FOR TESTING PURPOSES ONLY, COMMENT OUT FOR LIVE
	//$listingID = 3;
	//$userID = 5;
	
	$listingID = $_POST['listingID'];
	$theUser = $_SESSION['user'];
	
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
	
	echo ' please log in to continue';

   }



?>