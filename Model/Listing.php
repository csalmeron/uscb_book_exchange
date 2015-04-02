<?php


class Listing{
	//Listing class for ernest to code. please reference the DB schema 
	private $listingID;//
	private $ISBN;//
	private $userID;//
	private $price;//
	private $isNegotiable;//
	private $description;
	
	//These should be all the set and get methods needed for the project
	
	function __construct(){
		
		$this->price = 0;
		$this->isNegotiable = 0;
		$this->description = NULL;
		
	}
	
function getListingID(){
	
		return $this ->listingID;
		
}
	
function getISBN(){
	
		return $this ->ISBN;
		
}
	
	
function getUserID(){
	
		return $this ->userID;
}
		
function getPrice(){
	
		return $this -> price;
	
}
	
function isNegotiable(){
	
		return $this -> isNegotiable;
								
}
	
function getDescription(){
	
		return $this -> description;
		
}
	
function setListingID($newListingID){
	
		$this-> listingID = $newListingID;
		
}
	
function setPrice($newPrice){
	
		$this-> price = $newPrice;

}

function setISBN($newISBN){
	
		$this->ISBN = $newISBN;
		
}	

function setUserID($newUserID){
	
		$this->userID = $newUserID;
							
}

function setIsNegotiable($newIsNegotiable){
	
		$this-> isNegotiable = $newIsNegotiable;
		
}


function setDescription($newDescription){
	
		$this-> description = $newDescription;
											
}
	
//this should be all the functions needed to test 
//whether the the listing plaved in the back end is good
function isListingSet()
{
	
	if(!isset($this-> userID )){
	
	return FAlSE;
		
  }	
	
	if(!isset($this-> ISBN )){
	
	return FAlSE;
		
	 }	
	
	// if(!isset($this-> listingID )){
// 	
	// return FAlSE;
// 		
	// }	
	
	if(!isset($this-> price )){
	
	return FAlSE;
		
	}	
	
	if(!isset($this-> isNegotiable )){
	
	return FAlSE;
		
	}	
	
	// if(!isset($this-> description )){
// 	
	// return FAlSE;
// 		
	// }	
	
	return TRUE;
	
    }

function printlisting(){
		
	print_r($this);	
	
}

	
}	//end class listing
	
	

?>