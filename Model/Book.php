<?php

//Bill did this since Zach's motherfucking ass ddn't show up to class.
class Book{
	
	
	private $ISBN;
	private $publisherID;
	private $title;
	private $author;
	private $subject;
	
	
	function __construct(){
		
	}
	
	function getISBN(){
		
		return $this->ISBN;
		
	}
	
	function getPublisherID(){
		
		return $this->publisherID;
		
	}
	
	function getTitle(){
		
		return $this->title;
		
	}
	
	function getAuthor(){
		
		return $this->author;
		
	}
	
	function getSubject(){
		
		return $this->subject;
		
	}
	
	function setISBN($newISBN){
		
		$this->ISBN = $newISBN;
		
	}
	
	function setPublisherID($newPublisherID){
		
		$this->publisherID = $newPublisherID;
		
	}
	
	function settitle(){
		
		$this->title = $newTitle;
		
	}
	
	function setAuthor($newAuthor){
		
		$this->author = $newAuthor;
		
	}
	
	function setSubject($newSubject){
		
		$this->subject = $newSubject;
		
	}
		
}


?>