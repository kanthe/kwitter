 /**
 *	Used by index.php to insert full name from database 
 * if nav menu "MyKweets" was clicked and make the list 
 * of kweets and insert it in the "kweetList" tag.
 * 
 *	@author		Robin Kanthe
 *	@email		kanthe.robin@gmail.com
 *	@version		1.0
 *	@since		2015-08-21
 *	@requires	Event.js, Main.js, Kweet.js
 *					getUserDetail.php, kweetHandler.php
 */

var Home = {
	
	// --------------------------------------------------------
	// Home class constructor. Enabling all js functionality
	// --------------------------------------------------------
	//
	init : function() {
		try {
			// Getting full name of user with an Ajax get request
			// Main.ajax.get('src/php/getUserDetail.php?method=fullname', Home.getFullname);
			// Putting all Kweets or users Kweets to the "kweetList" tag
			Main.ajax.get("src/php/kweetHandler.php", Home.getKweetList);
		}
		catch (e) {
			console.log('Error in Home.js function init: ' + e.message);
		}
	},
	// 
	// Inserts full name (data.responseText), received from the get request
	// to the "myName" tag, if it exists.
	//
	getFullname : function(data) {
		try {
			var myName;
			myName = document.getElementById('myName');
			myName.innerHTML = diplayName;
			alert(myName.innerHTML);
		}
		catch (e) {
			console.log('Error in Home.js function getFullname: ' + e.message);
		}
	},
	//
	// Receives XML response (data.responseXML) from kweetHandler.php.
	// The "kweet" tags contains all data about the user.
	// Extracts this data and uses it to build a Kweet objects using Kweet.js.
	// Inserts the Kweet objects to the "kweetList" tag in index.php.
	//
	getKweetList : function(data) {
		try {
			var xmlData = data.responseXML; // Respons of "kweetHandler.php"
			var kweetListTag = xmlData.getElementsByTagName("kweets")[0];
			var kweets = kweetListTag.getElementsByTagName("kweet");
			var kweetList = document.getElementById("kweetList");
			
			// Stepping through all kweets given by respons of "kweetHandler.php"
			// and extracts data from the kweets.
			// Then creates Kweet objects.
			for (var i = 0; i < kweets.length; i++) {
				// Retrieving kweet info
				var kweet = kweets[i];
				var username = kweet.getElementsByTagName("username")[0].innerHTML;
				var fullname = kweet.getElementsByTagName("fullname")[0].innerHTML;
				var picture = kweet.getElementsByTagName("picture")[0].innerHTML;
				var kweetId = kweet.getElementsByTagName("kweetId")[0].innerHTML;
				var message = kweet.getElementsByTagName("message")[0].innerHTML;
				var date = kweet.getElementsByTagName("date")[0].innerHTML;
				var removable = kweet.getElementsByTagName("removable")[0].innerHTML;
				// Creates Kweet object
				var kweetObj = new Kweet(username, fullname, picture, kweetId, message, date, removable);
				kweetList.appendChild(kweetObj.element); // Inserts it to the "kweetsList" tag
			}
		}
		catch (e) {
			console.log('Error in Home.js function getkweetList: ' + e.message);
		}
	},
	doNothing : function (data) {
		
	}
}

/*
 	********************************************
	* Starts the Home class when website loads *
	********************************************
*/
Event.addEventListener(window, Event.LOAD, Home.init);