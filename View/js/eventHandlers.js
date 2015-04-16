$(document).ready(function() {

	//page dependencies

	var redirectTime = 1000;

	checkLoggedInCookie();

	verifyUser();

	setOptions();

	initializeModals();

	//end page dependencies

	//start event handlers

	$("#passwordSubmit").click(function() {

		var email = $('#emailFP').val();

		var password = $('#passwordFP').val();

		var passwordConfirm = $('#passwordConfirmFP').val();

		if ((email !== '') && (password !== '') && (passwordConfirm !== '')) {

			$('#FPStatus').html("Waiting for Server...");

			forgotPasswordRequest(email, password, passwordConfirm).success(function(data) {

				$('#FPStatus').html(data);

			}).error(function(xhr, textStatus, errorThrown) {

				$('#FPStatus').html(textStatus);

			});

		}//end if
		else {

			$('#FPStatus').html("Please enter all fields!");

		} //end else

	});
	//end password reset submit handler

	$("#loginSubmit").click(function() {

		var email = $('#emailLogin').val();

		var password = $('#passwordLogin').val();

		if ((email !== '') && (password !== '')) {

			$('#loginStatus').html("Waiting for Server...");

			logIn(email, password).success(function(data) {

				$('#loginStatus').html(data);

				if (data.indexOf("Welcome") !== -1) {

					document.cookie = "loggedIn=1";

					setTimeout(function() {
						window.location.href = "index.html";

					}, redirectTime);

				}

			}).error(function(xhr, textStatus, errorThrown) {

				$('#loginStatus').html(textStatus);

			});

		}//end if
		else {

			$('#loginStatus').html("Please enter all fields!");

		} //end else

	});
	//end password reset submit handler

	$("#registerSubmit").click(function() {

		var email = $('#emailRegister').val();

		if ((email !== '')) {

			$('#registerSubmit').prop("disabled", true);

			$('#registerStatus').html("Waiting for Server...");

			registerUserRequest(email).success(function(data) {

				$('#registerSubmit').prop("disabled", false);

				$('#registerStatus').html(data);

				if (data == 'a registration email has been sent!') {

					setTimeout(function() {
						window.location.href = "index.html";

					}, redirectTime);

				}

			}).error(function(xhr, textStatus, errorThrown) {

				$('#registerSubmit').prop("disabled", false);

				$('#registerStatus').html(textStatus);

			});

		}//end if
		else {

			$('#registerStatus').html("Please enter all fields!");

		} //end else
	});

	$('#createAcctSubmit').click(function() {

		email = $('#emailBox').val();

		code = $('#codeBox').val();

		firstName = $('#fName').val();

		lastName = $('#lName').val();

		password = $('#pw').val();

		passwordConfirmed = $('#pwc').val();

		schedule = $('#schedule').val();

		if ((email == '') || (firstName == '') || (lastName == '') || (password == '') || (passwordConfirmed == '') || (schedule == '')) {

			$('#createAcctStatus').html('Please fill out all fields!');

		} else if (code == '' || code == undefined) {

			window.location.href = "index.html";

		} else {

			$('#createAcctStatus').html('Waiting on Server...');

			createUser(email, code, password, passwordConfirmed, firstName, lastName, schedule).success(function(data) {

				$('#createAcctStatus').html(data);

				if (data.indexOf('success') != -1) {

					setTimeout(function() {
						window.location.href = "index.html";

					}, redirectTime);

				}

			}).error(function(xhr, textStatus, errorThrown) {

				$('#createAcctStatus').html(textStatus);

			});

		}

	});

	$('#logOut').click(function() {

		$('#logOutStatus').html("Waiting on Server...");

		logOut().success(function(data) {

			$('#logOutStatus').html(data);

			document.cookie = "loggedIn=0";

			setTimeout(function() {
				window.location.href = "index.html";

			}, redirectTime);

		}).error(function(xhr, textStatus, errorThrown) {

			$('#logOutStatus').html(textStatus);

		});

	});

	$("#SearchButton").click(function() {

		var searchQuery = $('#search').val();

		searchListings(searchQuery).success(function(data) {
			parseListingResults(data);

		}).error(function(xhr, textStatus, errorThrown) {

			parseListingResults(textStatus);

		});

	});

	$('form[name=newListing]').submit(function() {

		var price = this.elements.namedItem("price").value;
		var isNegotiable = this.elements.namedItem("isNegotiable").value;
		var description = this.elements.namedItem("description").value;
		var ISBN = this.elements.namedItem("isbn").value;
		var title = this.elements.namedItem("title").value;
		var subject = this.elements.namedItem("subject").value;
		var author = this.elements.namedItem("author").value;
		var publisher = this.elements.namedItem("publisher").value;

		ISBN = ISBN.replace(/-/g, "");

		ISBN = parseInt(ISBN);

		price = price.replace("$", "");

		price = parseFloat(price);

		if (isNaN(ISBN) && isNaN(price)) {

			ISBN = '';
			price = '';

		} else if (isNaN(ISBN)) {

			ISBN = '';

		} else if (isNaN(price)) {

			price = '';

		}

		if (!price || !isNegotiable || !ISBN || !title || !subject || !author || !publisher) {

			$('#createListingStatus').html("Please complete all fields!");

		} else {

			if (!description) {

				description = "";

			}

			createListing(ISBN, title, subject, author, publisher, price, isNegotiable, description).success(function(data) {

				$('#createListingStatus').html(data);

				setTimeout(function() {
					window.location.href = "index.html";

				}, redirectTime);

			}).error(function(xhr, textStatus, errorThrown) {

				$('#createListingStatus').html(textStatus);

			});

		}

		return false;

	});

	$("#deleteAcct").click(function() {

		deleteUser().success(function(data) {

			$('#AccountStatus').html(data);

			document.cookie = "loggedIn=0";

			setTimeout(function() {
				window.location.href = "index.html";
			}, redirectTime);

		}).error(function(xhr, textStatus, errorThrown) {

			$('#AccountStatus').html(textStatus);

		});

	});

	$('form[name=editSchedule]').submit(function() {

		//window.alert("BOOMSWAGNESS");

		var schedule = this.elements.namedItem("newSchedule").value;

		if (schedule == '') {

			$('#AccountStatus').html("Please fill out the schedule before submitting!");

		} else {

			editUser(schedule).success(function(data) {

				$('#AccountStatus').html(data);

				setTimeout(function() {
					window.location.href = "index.html";

				}, redirectTime);

			}).error(function(xhr, textStatus, errorThrown) {

				$('#AccountStatus').html(textStatus);

			});

		}

		return false;

	});

});
//end document ready

//HELPER FUNCTIONS. will put in model.js folder later

function parseListingResults(someData) {

	$("#listingContainer").empty();

	var myJSON = IsJsonString(someData);

	if (!myJSON) {

		$("#listingContainer").append("<p> " + someData + " </p>");

	} else {

		for (var i = 0; i < myJSON.length; i++) {

			var listing = "<div class = 'listing' id = 'listingNumber" + (i + 1) + "'>" + "<h3>" + "Textbook Details" + "</h3>" + "<div class = 'textbook-details'>" + "<div class ='left-column'>" + "<p class = price>" + "Price: " + myJSON[i]["price"] + "</p>" + "<p class = ISBN>" + "ISBN: " + myJSON[i]["ISBN"] + "</p>" + "<p class = title>" + "Title: " + myJSON[i]["title"] + "</p>" + "</div>" + "<div class ='right-column'>" + "<p class = isNegotiable>" + "Flexible on Price: " + myJSON[i]["isNegotiable"] + "</p>" + "<p class = author>" + "Author: " + myJSON[i]["author"] + "</p>" + "<p class = publisher>" + "Publisher: " + myJSON[i]["publisher"] + "</p>" + "</div>" + "<div class ='bottom-row'>" + "<p class = description>" + "Description: " + myJSON[i]["description"] + "</p>" + "</div>" + "</div>" + "<hr>" + "<h3>" + "Seller Details" + "</h3>" + "<div class = 'seller-details'>" + "<p class = fName>" + "Name: " + myJSON[i]["fName"] + " " + myJSON[i]["lName"] + "</p>" + "<p class = uscbEmail>" + "Email: " + myJSON[i]["uscbEmail"] + "</p>" + "<p class = schedule>" + "Schedule: " + myJSON[i]["schedule"] + "</p>" + "</div>" + "</div>";

			$('#listingContainer').append(listing);

		} // end for

	} // end else

}

function parseListingResultsForUser(someData) {

	$("#listingContainerForUser").empty();

	var myJSON = IsJsonString(someData);

	if (!myJSON) {

		$("#listingContainerForUser").append("<p> " + someData + " </p>");

	} else {

		for (var i = 0; i < myJSON.length; i++) {

			var listing = "<div class = 'listing' id = 'listingID" + myJSON[i]["listingID"] + "'>" + 
			"<h3>" + "Listing Details" + "</h3>" +
			"<p class = price>" + "Price: "+ myJSON[i]["price"] + "</p>" + 
			"<p class = isNegotiable>"+ "Flexible on Pricing: " + myJSON[i]["isNegotiable"] + "</p>" + 
			"<p class = description>" +" Description: " + myJSON[i]["description"] + "</p>" + 
			"<p class = ISBN>" + "ISBN: " + myJSON[i]["ISBN"] + "</p><button onclick = 'deleteUserListing(this.id)' class = 'btn btn-default' id = '" + myJSON[i]["listingID"] + "'> Delete Listing </button></div>";

			$('#listingContainerForUser').append(listing);

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

function getCookie(cname) {
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ')
		c = c.substring(1);
		if (c.indexOf(name) == 0)
			return c.substring(name.length, c.length);
	}
	return "";
}

function checkLoggedInCookie() {
	var status = getCookie("loggedIn");
	if (status == "" || status == undefined) {
		document.cookie = "loggedIn=0";
	} else {
		return;
	}
}

function verifyUser() {

	var path = document.location.pathname;

	var filename = path.substring(path.lastIndexOf('/') + 1);

	if (filename == 'create_listing.html' || filename == 'editAccount.html' || filename == 'editListings.html') {

		if (getCookie("loggedIn") == 0 || getCookie("loggedIn") == undefined) {

			window.alert("You must log in to view this page");

			window.location.href = "index.html";
		}

	}

}

function setOptions() {

	var isLoggedIn = getCookie("loggedIn");

	$('#userOptions').empty();

	if (isLoggedIn == 1) {

		var nav = '<li id = "sell"><a href="create_listing.html">Sell</a></li><li id = "settings" class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> Account Settings <span class="caret"></span></a><ul class="dropdown-menu" role="menu"><li><a href="editAccount.html">Edit Account</a></li><li><a href="editListings.html">Your Listings</a></li></ul><li><a id = "logOut" href="#logout-modal" data-toggle="modal" data-target="#logout-modal">Log Out</a></li></li>';

		$('#userOptions').append(nav);

	} else {

		var nav = '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Register/Login <span class="caret"></span></a><ul class="dropdown-menu" role="menu"><li><a href="#register-modal" data-toggle="modal" data-target="#register-modal">Register</a></li><li><a href="#login-modal" data-toggle="modal" data-target="#login-modal">Log In</a></li><li><a href="#password-modal" data-toggle="modal" data-target="#password-modal">Forgot Password</a></li></ul></li>';

		$('#userOptions').append(nav);
	}

}

function initializeModals() {

	$("#modalList").load("modals.html");

}

function populateListingsForUser() {

	if (getCookie("loggedIn") == 1) {

		getUserListings().success(function(data) {

			parseListingResultsForUser(data);

		}).error(function(xhr, textStatus, errorThrown) {

			parseListingResultsForUser(textStatus);

		});

	}

}

function deleteUserListing(id) {

	var listingToDelete = id;

	id = parseInt(id);

	deleteListing(id).success(function(data) {

		$('#listingID' + id).remove();

	}).error(function(xhr, textStatus, errorThrown) {

		window.alert(textStatus);

	});

}