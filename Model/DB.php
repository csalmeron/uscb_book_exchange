<?php

require_once 'User.php';
require_once 'Listing.php';

class DB{

//fields	
private $dsn;
private $user;
private $password;	
private $dbh;
	
//sets the dsn, user, and password and creates a database handle $dbh that connects to our DB via PDO
function __construct(){
	
	//set up connection strings and stuff for a PDO object and return the PDO object
	
	$this->dsn = 'mysql:dbname=uscbtextbookexchange;host=127.0.0.1';
	$this->user = 'root';
	$this->password = '';
	
	try {
		
    	$dbh = new PDO($this->dsn, $this->user, $this->password);
		$this->dbh =  $dbh;
		
	} catch (PDOException $e) {
    	return FALSE;
	}

}

//email functions for DB

//insert an Email into the DB by email name. returns true if inserted, and false otherwise
function insertEmail($emailName){
	
	$stmt = $this->dbh->prepare("INSERT INTO email(uscbEmail) VALUES (:email)");
	$stmt->bindParam(':email', $nameOfEmail);
	
	$nameOfEmail = $emailName;
	
	try{
		if($stmt->execute()){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	catch(exception $e){
		return FALSE;
	}
	
}

//delete an email by the email name. returns true is deleted, and false otherwise
function deleteEmailByName($emailName){
	
	$stmt = $this->dbh->prepare("DELETE FROM email WHERE uscbEmail = :email LIMIT 1");
	$stmt->bindParam(':email', $nameOfEmail);
	
	$nameOfEmail = $emailName;
	
	try{
		if($stmt->execute()){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	catch(exception $e){
		return FALSE;
	}
}

//gets an email by the email name. returns an email name if found, and false otherwise
function getEmailByEmailName($emailName){
	
	$stmt = $this->dbh->prepare("SELECT uscbEmail FROM email WHERE uscbEmail = :email LIMIT 1");
	$stmt->bindParam(':email', $nameOfEmail);
	
	$nameOfEmail = $emailName;
	
	try{
		if($stmt->execute()){
			
			$result = $stmt->fetch();
			
			if(!$result){
				return FALSE;
			}
			else{
				return $result['uscbEmail'];
			}			
		}
		else{
			return FALSE;
		}
	}
	catch(exception $e){
		return FALSE;
	}
}

//gets an email by the email ID. returns an email name if found, and false otherwise
function getEmailByEmailID($emailID){
	
	$stmt = $this->dbh->prepare("SELECT uscbEmail FROM email WHERE uscbEmailID = :emailID LIMIT 1");
	$stmt->bindParam(':emailID', $IDofEmail);
	
	$IDofEmail = $emailID;
	
	try{
		if($stmt->execute()){
			
			$result = $stmt->fetch();
			
			if(!$result){
				return FALSE;
			}
			else{
				return $result['uscbEmail'];
			}			
		}
		else{
			return FALSE;
		}
	}
	catch(exception $e){
		return FALSE;
	}
}

//gets an email ID by the email. returns an email ID if found, and false otherwise
function getEmailIDByEmailName($emailName){
	
	$stmt = $this->dbh->prepare("SELECT uscbEmailID FROM email WHERE uscbEmail = :email LIMIT 1");
	$stmt->bindParam(':email', $nameOfEmail);
	
	$nameOfEmail = $emailName;
	
	try{
		if($stmt->execute()){
			
			$result = $stmt->fetch();
			
			if(!$result){
				return FALSE;
			}
			else{
				return $result['uscbEmailID'];
			}			
		}
		else{
			return FALSE;
		}
	}
	catch(exception $e){
		return FALSE;
	}
}

//do not need an update email function as we will not be changing/updating emails in the DB




//user functions for DB

//takes a user object and attempts to insert it into the DB. returns true if successfull, and false otherwise
function insertUser(User $user){
			
	$isUserSet = $user->isUserSet();
	
	if(!$isUserSet){
		return false;
	}
	else{
		
	$stmt = $this->dbh->prepare("INSERT INTO user ( uscbEmailID , fName , lName , schedule , passwordHash , isBanned ) VALUES ( :emailID , :fname , :lname , :schedule , :passwordHash , :isBanned )");
	
	$stmt->bindParam(':emailID', $IDofEmail);
	$stmt->bindParam(':fname', $theFirstName);
	$stmt->bindParam(':lname', $theLastName);
	$stmt->bindParam(':schedule', $theSchedule);
	$stmt->bindParam(':passwordHash', $thePWHash);
	$stmt->bindParam(':isBanned', $theBanStatus);
	
	$IDofEmail = $user->getEmailID();
	$theFirstName = $user->getFirstName();
	$theLastName = $user->getLastName();
	$theSchedule = $user->getSchedule();
	$thePWHash = $user->getPasswordHash();
	$theBanStatus = $user->isBanned();
	
	try{
		if($stmt->execute()){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	catch(exception $e){
		return FALSE;
	}
  }	
	
}

//takes a user object and attempts to delete it from the DB. returns true if successful, and false otherwise
function deleteUser(User $user){
	
	$isUserSet = $user->isUserSet();
	
	if(!$isUserSet){
		return false;
	}
	else{
			$emailID = $user->getEmailID();
			
			if($this->deleteUserByEmailID($emailID)){
				return TRUE;
			}
			else{
				return FALSE;
			}		
    	}		
}

//takes a string userID and attempts to delete the user from the DB. returns true if successful, and false otherwise
function deleteUserByUserID($userID){
	
	$stmt = $this->dbh->prepare("DELETE FROM user WHERE userID = :userID LIMIT 1");
	$stmt->bindParam(':userID', $IDofUser);
	
	$IDofUser = $userID;
	
	try{
		if($stmt->execute()){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	catch(exception $e){
		return FALSE;
	}
}

//takes a string emailID and attempts to delete the user from the DB. returns true if successfull, and false other wise. 
function deleteUserByEmailID($emailID){
	
	$stmt = $this->dbh->prepare("DELETE FROM user WHERE uscbEmailID = :emailID LIMIT 1");
	$stmt->bindParam(':emailID', $IDofEmail);
	
	$IDofEmail = $emailID;
	
	try{
		if($stmt->execute()){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	catch(exception $e){
		return FALSE;
	}
}

//takes a user object and attempts to return the current user in the database. will either return a user object if successful, or false if not found
function getUser(User $user){
	$emailID = $user->getEmailID();
	
	if(!isset($emailID)){
		return FALSE;
	}
	else{
		$someUserObject = $this->getUserByEmailID($emailID);
	
		if(!$someUserObject){
			return FALSE;
		}
		else{
			return $someUserObject;
		}
	}	
	
}

//takes a string userID and attemps to find a user in the database that matches the user id. If the user is found, it will return a user object with those records in the database. if not found, false will be returned.
function getUserByUserID($userID){
	
	$stmt = $this->dbh->prepare("SELECT * FROM user WHERE userID = :userID LIMIT 1");
	$stmt->bindParam(':userID', $IDofUser);
	
	$IDofUser = $userID;
	
	try{
		if($stmt->execute()){
			
			$result = $stmt->fetch();
			
			if(!$result){
				return FALSE;
			}
			else{
				$newUser = new User();
				
				$newUser->setUserID($result['userID']);
				$newUser->setEmailID($result['uscbEmailID']);
				$newUser->setFirstName($result['fName']);
				$newUser->setLastName($result['lName']);
				$newUser->setSchedule($result['schedule']);
				$newUser->setPasswordHash($result['passwordHash']);
				
				if($result['isBanned'] == 1){
					$newUser->ban();
				}
				
				return $newUser;
			}			
		}
		else{
			return FALSE;
		}
	}
	catch(exception $e){
		return FALSE;
	}
}

//takes a string emailID and attempts to return a user object from the DB. Returns a user object matching that emailID if true,
//and false if not found or error
function getUserByEmailID($emailID){
	
	$stmt = $this->dbh->prepare("SELECT * FROM user WHERE uscbEmailID = :emailID LIMIT 1");
	$stmt->bindParam(':emailID', $IDofEmail);
	
	$IDofEmail = $emailID;
	
	try{
		if($stmt->execute()){
			
			$result = $stmt->fetch();
			
			if(!$result){
				return FALSE;
			}
			else{
				$newUser = new User();
				
				$newUser->setUserID($result['userID']);
				$newUser->setEmailID($result['uscbEmailID']);
				$newUser->setFirstName($result['fName']);
				$newUser->setLastName($result['lName']);
				$newUser->setSchedule($result['schedule']);
				$newUser->setPasswordHash($result['passwordHash']);
				
				if($result['isBanned'] == 1){
					$newUser->ban();
				}
				
				return $newUser;
			}			
		}
		else{
			return FALSE;
		}
	}
	catch(exception $e){
		return FALSE;
	}
	
}

//takes a string email ID and attempts to return the user schedule on their email ID. it will either return the schedule if true, and false otherwise. 
function getUserScheduleByEmailID($emailID){
	
	$stmt = $this->dbh->prepare("SELECT schedule FROM user WHERE uscbEmailID = :emailID LIMIT 1");
	$stmt->bindParam(':emailID', $IDofEmail);
	
	$IDofEmail = $emailID;
	
	try{
		if($stmt->execute()){
			
			$result = $stmt->fetch();
			
			if(!$result){
				return FALSE;
			}
			else{
				
				$schedule = $result['schedule'];
								
				return $schedule;
			}			
		}
		else{
			return FALSE;
		}
	}
	catch(exception $e){
		return FALSE;
	}
}

//takes a full user object and attempts to update it in the DB. If successfull, the function will return true. Otherwise, it will return false.
function updateUser(User $user){
	
	$isUserSet = $user->isUserSet();
	
	if(!$isUserSet){
		return false;
	}
	else{
		
	$stmt = $this->dbh->prepare("UPDATE user SET ( fName = :fname, lName = lname , schedule = :schedule , passwordHash = :passwordHash, isBanned = :isBanned ) WHERE uscbEmailID = :emailID");
	
	$stmt->bindParam(':emailID', $IDofEmail);
	$stmt->bindParam(':fname', $theFirstName);
	$stmt->bindParam(':lname', $theLastName);
	$stmt->bindParam(':schedule', $theSchedule);
	$stmt->bindParam(':passwordHash', $thePWHash);
	$stmt->bindParam(':isBanned', $theBanStatus);
	
	$IDofEmail = $user->getEmailID();
	$theFirstName = $user->getFirstName();
	$theLastName = $user->getLastName();
	$theSchedule = $user->getSchedule();
	$thePWHash = $user->getPasswordHash();
	$theBanStatus = $user->isBanned();
	
	try{
		if($stmt->execute()){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	catch(exception $e){
		return FALSE;
	}
  }	
}

//takes a string email ID and a string password hash. it will attempt to update the password hash for the user with that email iD. If successfull, the function will return true. oherwise, it will return false.
function updateUserPWHashByEmailID($emailID, $passwordHash){
	
		
	$stmt = $this->dbh->prepare("UPDATE user SET passwordHash = :passwordHash WHERE uscbEmailID = :emailID");
	
	$stmt->bindParam(':emailID', $IDofEmail);
	$stmt->bindParam(':passwordHash', $thePWHash);
		
	$IDofEmail = $emailID;
	$thePWHash = $passwordHash;
		
	try{
		if($stmt->execute()){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	catch(exception $e){
		return FALSE;
	}
  
}

//takes a string email ID and a string schedule. It will attempt to update the user schedule for the user with that emailID. If successfull, the function will return true. Otherwise, it will return false.
function updateUserScheduleByEmailID($emailID, $schedule){
	
			
	$stmt = $this->dbh->prepare("UPDATE user SET schedule = :schedule WHERE uscbEmailID = :emailID");
	
	$stmt->bindParam(':emailID', $IDofEmail);
	$stmt->bindParam(':schedule', $theSchedule);	
	
	$IDofEmail = $emailID;
	$theSchedule = $schedule;
	
	try{
		if($stmt->execute()){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	catch(exception $e){
		return FALSE;
	}
  
}




// DB FUNCTIONS FOR ERNEST

function insertListing(Listing $someListing){
	
	if(!$someListing->isListingSet()){
	
		return FALSE;
		
	}
	else{
		
	$stmt = $this->dbh->prepare("INSERT INTO listing ( ListingID , ISBN , userID  , price , isNegotiable , description ) VALUES ( :newListing , :newISBN , :newUserID , :newPrice , :newisNegotiable, :newDescription )");
	
	$stmt->bindParam(':newListing', $newListing);
	$stmt->bindParam(':newISBN', $newISBN);
	$stmt->bindParam(':newUserID', $newUserID);
	$stmt->bindParam(':newPrice', $newPrice);	
	$stmt->bindParam(':newisNegotiable', $newIsNegotiable);
	$stmt->bindParam(':newDescription', $newDescription);
						
	$newListing = $someListing->getListingID();
	$newISBN = $someListing->getISBN();
	$newUserID = $someListing->getUserID();
	$newPrice = $someListing->getPrice();
	$newIsNegotiable = $someListing->isNegotiable();
	$newDescription = $someListing->getDescription();
	
	try{
		if($stmt->execute()){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}//end of the try statment
	catch(exception $e){
		return FALSE;
	}//end of the catch statement
	
  }	//end of the else
}//end of the function

function deleteListingByListingID($listingID){
	
		if(!isset($listingID)){
			
				return FALSE;
				
		}
		else{
			
			$stmt = $this->dbh->prepare("DELETE FROM listing WHERE listingID = :listingID LIMIT 1");
			$stmt->bindParam(':listingID',$theListingID);
	 
	 		$theListingID = $listingID; 			
			
	 		try{
	 			if($stmt->excute())
				
	 			{
	 				
				return TRUE;
				
				}	
				 		
	 			else{
	 				
	 			return FALSE;
					
	 			}
				
	}//end of the try
	
	catch(exception $e){
		
		return FALSE;
		
	}//end of the catch 
	
}//end of else
		
	
}//end of the function
		
	 
	

function deleteAllListingsByUserID($userID){
	
	if(!isset($userID)){
		
		return FALSE;
		
	}//end if
	
	else{
		
		$stmt =  $this->dbh->prepare("DELETE FROM listing WHERE userID = :theUserID");
		
		$stmt -> bindParam(':theUserID' , $IDofTheUser);

		$IDofTheUser = $userID;

		try{
	 			if($stmt->excute())				
	 			{
	 				
				return TRUE;
				
				}	
				 	
	 			else{
	 				
	 			return FALSE;
					
	 			}
				
		}//end of the try
			
		catch(exception $e){
		
			return FALSE;
			
		}//end of catch statement	
		
		
}//end else
	
}//end delete all function


//This should be the code need for this function 
//Goal fromt he looks of is for the Function to get listing by THE Listing ID
function getListingbyListingID($listingID){
	
	if(!isset($userID)){
		
		return FALSE;
		
	}//end if
	else{
		
		$stmt =  $this->dbh->prepare("SELECT * FROM listing WHERE listingID = :theListingID LIMIT 1");
	
		$stmt -> bindParam(':theListingID' , $IDofListing);

		$IDofListing = $listingID;

		try{
			
			if($stmt->excute()){
				
			$result = $stmt->fetch();
	
			if(!$result){
				
				return FALSE;
					
			}
		
			else{
				
				return createListingFromResult($result);
				
			}
		}
			
		else{
			
			return FALSE;
			
		}
	}	//end of try statement
	
	catch(exception $e){
		
		return FALSE;
	
	}//end of catch statement
		
}//end else		
	
}//end of function


function getListingsByUserID($userID){
	
	if(!isset($userID)){
		
		return FALSE;
		
	}//end if
	
	else{
		
		$stmt =  $this->dbh->prepare("SELECT * FROM listing WHERE userID = :theUserID");

		$stmt -> bindParam(':theUserID' , $IDofUser);

		$IDofUser = $userID;

		try{
			if($stmt -> excute()){
		
				if(!$result)
				{
			
				return FALSE;
					
				}
		
				else
				{						
				
					return createListingFromResult($result);
				
				}
			}
			
		else 
			{
			
			return FALSE;
			
		}
	
	}	//end of try statement
	catch(exception $e){
	
		return FALSE;
	
	}//end of catch statement
	
		
		
	}//end else
	
}//end of function


private function createListingFromResult($result){
	
				$foundListing = new Listing();
				
				$foundListing->setListingID($result['listingID']);
				
				$foundListing->setISBN($result['ISBN']);
				
				$foundListing->setUserID($result['userID']);
				
				$foundListing->setPrice($result['price']);
				
				$foundListing->setIsNegotiable($result['isNegotiable']);
				
				$foundListing->setDescription($result['description']);
				
				return $foundListing;
	
}//end function

function updateListingByListingID(Listing $someListing){
	
	if(!$someListing->isListingSet()){
		
		return FALSE;
		
	}
	else{		
		
		$stmt -> $this->dbh->prepare("UPDATE listing SET (listingID = :theListingID, ISBN = :theISBN, userID = :theUserID, price = :thePrice, isNegotiable = :theIsNegotiable, description = :theDescription) WHERE listingID = :someListingID");
	
		//"INSERT INTO listing ( ListingID , ISBN , userID  , price , isNegotiable , description ) VALUES ( :newListing , :newISBN , :newUserID , :newPrice , :newisNegotiable, :newDescription )
	
		$stmt ->bindParam(':theListingID',$IDofListing);
	
		$stmt ->bindParam(':theISBN',$theISBN);
	
		$stmt ->bindParam(':theUserID',$theUserID);
	
		$stmt ->bindParam(':thePrice',$thePrice);
	
		$stmt ->bindParam(':theIsNegotiable',$theIsNegotiable);
	
		$stmt ->bindParam(':theDescription',$theDescription);
	
		$stmt ->bindParam(':someListingID',$someListingID);	
	
		
	
		$IDofListing = $someListing->getListingID();
	
		$theISBN = $someListing->getISBN();
	
		$theUserID = $someListing->getUserID();
	
		$thePrice = $someListing->getPrice();
	
		$theIsNegotiable = $someListing->isNegotiable();
	
		$theDescription = $someListing->getDescription();
	
		$someListingID = $someListing->getListingID();	
	
		try{
		
			if($stmt->excute()){
			
				return TRUE;
			
			}
	
			else{
			
				return FALSE;	
	
			}
		}//end of try statment
	
		catch(exception $e){
		
			return FALSE;	
	
		}//end of catch statement
	
	}//end else
	
}//end of update Listing by Listing function


//For Tremaine
function searchListings($searchQuery){
	
}


//DB FUNCTIONS FOR ZACH

function insertBook(Book $newBook){
	
	$isBookSet = $newBook->isBookSet();
	
	if(!$isBookSet)
	{
		//echo 'this shit isnt set';
		return false;
	}
	else
	{
	
	$stmt = $this->dbh->prepare("INSERT INTO book ( ISBN, publisherID, title, author,subject ) VALUES ( :ISBN ,:publisherID,:title,:author,:subject)");
	
	$stmt->bindParam(':ISBN', $theISBN);
	$stmt->bindParam(':author', $theAuthor);
	$stmt->bindParam(':subject', $theSubject);
	$stmt->bindParam(':publisherID', $thePublisherID);
	$stmt->bindParam(':title', $bookTitle);
	
	$theISBN = $newBook->getISBN();
	$theAuthor = $newBook->getAuthor();
	$theSubject = $newBook->getSubject();
	$thePublisherID = $newBook->getPublisherID();
	$bookTitle = $newBook->getTitle();

	
	try
	{
		if($stmt->execute())
			{
			return TRUE;
   			}
		else
		{
			return FALSE;
		}
	}
	catch(exception $e)
	{	
	return FALSE;
	}
 } 
	
	
}

function deleteBook(Book $someBook){
	
	$isBookSet = $someBook->isUserSet();
	
	if(!$isBookSet)
	{
		echo "the book is not set";
		return false;
	}
	else
	{
		$bookTitle = $someBook->getTitle();
	
		if($this->deleteBookByBookTitle($bookTitle))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
    } 
	
}

function deleteBookByBookID($publisherID){
	
	$isBookSet = $publisherID->isUserSet();
	if(!$isBookSet)
	{
		return false;
	}
	else
	{
	$publisherID = $book->getBookID();
	
		if($this->deleteBookByBookID($publisherID))
		{
			return TRUE;
		}
	else
	{
		return FALSE;
	}
 } 
	
}

function getBook(Book $someBook){
	
		$stmt = $this->dbh->prepare("SELECT * FROM book WHERE ISBN = :ISBN");
		
		$stmt->bindParam(':ISBN', $theISBN);
		
		$theISBN = $ISBN;
		
		
		try{
				
			if($stmt->execute()){
	
			$result = $stmt->fetch();

				if(!$result){
		
					return FALSE;
				}
	
				else{
		
					$someBook = new Book();
	
					$someBook->setISBN($result['ISBN']);
	
					$someBook->setPublisherID($result['publisherID']);
	
					$someBook->settitle($result['title']);
	
					$someBook->setAuthor($result['author']);
	
					$someBook->setSubject($result['subject']);

				}//end else
			}//end if
		}//end try
		
	catch (exception $e)
	{
		return FALSE;
	
	}

}//end function
	


function getBookByBookID($publisherID)
{
	$stmt = $this->dbh->prepare("SELECT * FROM book WHERE publisherID = :publisherID");
    $stmt->bindParam(':publisherID', $thePublisherID);
    $thePublisherID = $publisherID;
	try
	{
 		if($stmt->execute())
 		{
			$result = $stmt->fetch();
			if(!$result)
			{
			return FALSE;
			}//end inner if statement
				else
				{
					$newBook = new Book();
					$newBook->setISBN($result['ISBN']);
					$newBook->setPublisherID($result['publisherID']);
					$newBook->settitle($result['title']);
					$newBook->setAuthor($result['author']);
					$newBook->setSubject($result['subject']);
				}//end else statement
		}//end outer if statement
	}
//		if($result['isBanned'] == 1)
//		{
//			$newBook->ban();
//		}
//			return $newBook;
//	
//	}//end if statement
//	else
//	{
//		return FALSE;
//	}
//end try block
catch(exception $e)
{
			return FALSE;
}
}






//no need to have update book function as we will not be updating books!

function insertPublisher($publisherName){
	
	if(!isset($publisherName))
	{
		return FALSE;
	}
	
		$stmt = $this->dbh->prepare("INSERT INTO publisher(publisher) VALUES (:publisherName)");
		$stmt->bindParam(':publisherName', $thePublisherName);
			
		$thePublisherName = $publisherName;
	
	try
	{
		if($stmt->execute())
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	catch(exception $e)
	{
		return FALSE;
	}
	
}

function deletePublisherByPublisherName($publisherName){
	
	
	if(!isset($publisherName))
	{	
		return FALSE;	
	}
	
	$stmt = $this->dbh->prepare("DELETE FROM publisher WHERE publisher = :publisherName LIMIT 1");
	$stmt->bindParam(':publisherName', $thePublisherName);
	
	$thePublisherName = $publisherName;
	
	try
	{
		if($stmt->execute())
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	catch(exception $e)
	{
		return FALSE;
	}
	
}

function deletePublisherByPublisherID($publisherID){
	
	if(!isset($publisherID)){
		
		return FALSE;
		
	}
	
	$stmt = $this->dbh->prepare("DELETE FROM publisher WHERE publisherID = :publisherID LIMIT 1");
	$stmt->bindParam(':publisherID', $thePublisherID);
	
	$thePublisherID = $publisherID;
	
	try{
		if($stmt->execute()){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	catch(exception $e){
		return FALSE;
	}
	
}

function getPublisherIDByName($publisherName){
	
	if(!isset($publisherName)){
		
		return FALSE;
		
	}
	
	$stmt = $this->dbh->prepare("SELECT publisherID FROM publisher WHERE publisher = :publisherName LIMIT 1");
	$stmt->bindParam(':publisherName', $thePublisherName);
	
	$thePublisherName = $publisherName;
	
	try{
		if($stmt->execute()){
			
			$result = $stmt->fetch();
			
			if(!$result){
				return FALSE;
			}
			else{
				return $result['publisherID'];
			}			
		}
		else{
			return FALSE;
		}
	}
	catch(exception $e){
		return FALSE;
	}
}

function getPublisherNameByID($publisherID){
	
	
	if(!isset($publisherID)){
		
		return FALSE;
		
	}
	
	$stmt = $this->dbh->prepare("SELECT publisher FROM publisher WHERE publisherID = :publisherID LIMIT 1");
	$stmt->bindParam(':publisherID', $thePublisherID);
	
	$thePublisherID = $publisherID;
	
	try{
		if($stmt->execute()){
			
			$result = $stmt->fetch();
			
			if(!$result){
				return FALSE;
			}
			else{
				return $result['publisher'];
			}			
		}
		else{
			return FALSE;
		}
	}
	catch(exception $e){
		return FALSE;
	}
	
}

function getPublisherNameByName($publisherName){
		
	
	if(!isset($publisherName)){
		
		return FALSE;
		
	}
	
	$stmt = $this->dbh->prepare("SELECT publisher FROM publisher WHERE publisher = :publisherName LIMIT 1");
	$stmt->bindParam(':publisherName', $thePublisherName);
	
	$thePublisherName = $publisherName;
	
	try{
		if($stmt->execute()){
			
			$result = $stmt->fetch();
			
			if(!$result){
				return FALSE;
			}
			else{
				return $result['publisher'];
			}			
		}
		else{
			return FALSE;
		}
	}
	catch(exception $e){
		return FALSE;
	}
}

function getPublisherIDByID($publisherID){
			
	if(!isset($publisherID)){
		
		return FALSE;
		
	}
	
	$stmt = $this->dbh->prepare("SELECT publisherID FROM publisher WHERE publisherID = :publisherID LIMIT 1");
	$stmt->bindParam(':publisherID', $thePublisherID);
	
	$thePublisherID = $publisherID;
	
	try{
		if($stmt->execute()){
			
			$result = $stmt->fetch();
			
			if(!$result){
				return FALSE;
			}
			else{
				return $result['publisherID'];
			}			
		}
		else{
			return FALSE;
		}
	}
	catch(exception $e){
		return FALSE;
	}
	
}

// no need to have update publisher as we will not be updating publishers
	
}//end class DB

?>