<?php
require_once 'Model/theModel.php';

$theUser = isset($_SESSION['user']);
$price =  isset($_POST['price']);
$isNegotiable = isset($_POST['isNegotiable']);
$description = isset($_POST['description']);
$listingID= isset($_POST['listingID']);

//FOR TEST PURPOSES ONLY SO WE DO NOT HAVE TO VERIFY USER FOR MODULE
//$theUser = 1;

	if($theUser && $listingID)
	{
		$listingID= $_POST['listingID'];
		
		$theUser = $_SESSION['user'];
		
		$userID = $theUser->getUserID();
		
		//FOR TEST PURPOSES ONLY
		//$userID = 5;
		
		$dbh = new DB();
		
		
		$listingToUpdate = $dbh->getListingbyListingID($listingID);

		echo print_r($listingToUpdate);
		
		if(!$listingToUpdate){
		
			echo "listing doesn't exist";
	
		}//end if
	
		else
		{
			
			
	
			 if($price)
	 			{
	 		
				$listingToUpdate->setPrice($_POST['price']);
	 	
			 	}
		     if($isNegotiable)
				 {
		 	
				$listingToUpdate->setIsNegotiable($_POST['isNegotiable']);
		
	 			}
		    if($description)
	 			{
	 	
				$listingToUpdate->setDescription($_POST['description']);
		
	 			}
		 	  	 
	 		
	 		
	 		if(!$dbh->updateListingWithUserID($listingToUpdate, $userID))
	 			{
	 	
	 			echo 'could not update listing';
	 
				 }
		
	 		else 
	 		{
	 	
			echo 'listing updated sucessfully';	
		
	 
			}			//end else
		}//end else
}// end if 

else 
{
	
	echo 'Please log into account to update Listing';
			
 }// end of else
	 	 

?>

