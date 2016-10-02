 /**
 * Makes the list of contacts and insert it in the "contactList" tag.
 * Adding functionality to retreive different lists when clicking 
 * on fields/links "All contacts", "Contacts you follow" or "Followers".
 * 
 *	@author		Robin Kanthe
 *	@email		kanthe.robin@gmail.com
 *	@version		1.0
 *	@since		2015-08-21
 *	@requires	Event.js, Main.js, Contact.js, contactHandler.php
 */
 
var Contacts = {
	
	/*
 	  *************************
	  * Public static methods *
	  *************************
	*/
	// --------------------------------------------------------
	// Contacts class constructor. Enabling all js functionality
	// --------------------------------------------------------
	//
	init : function() {
		try {
			// Getting all contacts from database with Ajax get request.
			Main.ajax.get("src/php/contactHandler.php?method=allContacts", Contacts.getContactList);
			var allContacts = document.getElementById('allContacts');
			var following = document.getElementById('following');
			var followers = document.getElementById('followers');
			// On click on "All contacts" field/link
			// Getting all contacts from database with Ajax get request.
			Event.addEventListener(allContacts, Event.CLICK, function () {
				Main.ajax.get("src/php/contactHandler.php?method=allContacts", Contacts.getContactList);
			});
			// On click on "Contacts you follow" field/link
			// Getting contacts user is following from database with Ajax get request.
			Event.addEventListener(following, Event.CLICK, function () {
				Main.ajax.get("src/php/contactHandler.php?method=following", Contacts.getContactList);
			});
			// On click on "Followers" field/link
			// Getting contacts that follows user from database with Ajax get request.
			Event.addEventListener(followers, Event.CLICK, function () {
				Main.ajax.get("src/php/contactHandler.php?method=followers", Contacts.getContactList);
			});
		}
		catch (e) {
			console.log('Error in Contacts.js function init: ' + e.message);
		}
	},
	//
	// Receives XML response (data.responseXML) from contactHandler.php.
	// The "contact" tags contains all data about the user.
	// Extracts this data and uses it to build Contact objects using Contact.js.
	// Inserts the Contact objects to the "contactList" tag in index.php.
	//
	getContactList : function(data) {
		try {
			var xmlData = data.responseXML; // Respons of "kweetHandler.php"
			var contactListTag = xmlData.getElementsByTagName("contacts")[0];
			var contacts = contactListTag.getElementsByTagName("contact");
			var contactList = document.getElementById("contactList");
			
			while (contactList.hasChildNodes()) {
				contactList.removeChild(contactList.firstChild);
			}
			var i;
			// Stepping through all contacts given by respons of "contactHandler.php"
			// and extracts data from the contacts.
			// Then creates Contact objects.
			for (i = 0; i < contacts.length; i++) {
				// Retrieving contact info
				var contact = contacts[i];
				var fullname = contact.getElementsByTagName("fullname")[0].innerHTML;
				var username = contact.getElementsByTagName("username")[0].innerHTML;
				var picture = contact.getElementsByTagName("picture")[0].innerHTML;
				var following = contact.getElementsByTagName("following")[0].innerHTML;
				var follower = contact.getElementsByTagName("follower")[0].innerHTML;
				// Creates Contact object
				var contactObj = new Contact(fullname, username, picture, following, follower);
				contactList.appendChild(contactObj.element);
			}
		}
		catch (e) {
			console.log('Error in Contact.js function getContactList: ' + e.message);
		}
	},
}

/*
 	********************************************
	* Starts the Contacts class when website loads *
	********************************************
*/
Event.addEventListener(window, Event.LOAD, Contacts.init);

