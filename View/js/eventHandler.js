$(document).ready(function()
{	

   $("#SearchButton").click(function()
   {
   	
   	var searchQuery = $('#search').val();
   	
   	//window.alert(searchQuery);
   	   	
   	//window.alert("The error is before I call searchListing");
   	
    searchListings(searchQuery).success(function(data) 
    {   
    	window.alert("the function call has been made successfully");
    	//window.alert(data);
   	     	parseResults(data);  	
}).error(function(xhr, textStatus, errorThrown)
{
	window.alert(xhr.status);
	//window.alert("I have an error with (function(jqXHR, textStatus, errorThrown)");
	parseResults(textStatus);	
	//window.alert("I have an error after (function(jqXHR, textStatus, errorThrown)");
});

 });
 
 });
 
 //other functions
 
 function parseResults(someData){
 	
 	//window.alert("PARSING MAINE!");

	var myJSON = IsJsonString(someData);
   	// window.alert(myJSON);
   	 if(!myJSON){
   	 	
   	  $("#comment").html(someData);
   	  //window.alert("its not JSON breh!!!");
   	 } 
   	 else{
   	 	
   	 	for(var i=0;i<myJSON.length;i++){
 	
  innerLength = Object.keys(myJSON[i]).length/2;
  
   for(var j=0; j < innerLength; j++)
   {
    //  window.alert(j);
      
      $("#comment").after(myJSON[i][j]);
      
       } // end for
   	} // end for  
   	 	
   	 } // end else 
   	 
}

function IsJsonString(str) {
  try {
      var a = JSON.parse(str);
  } catch (e) {
     	 return false;
  }
  return a;
}