/**
 *	Creating visual representation of a Kweet using the data
 * extracted from the database with kweetHandler.php and
 * the Home.js function getKweetList.
 * Adding delete functionality to it.
 *
 *	@author		Robin Kanthe
 *	@email		kanthe.robin@gmail.com
 *	@version		1.0
 *	@since		2015-08-21
 *	@requires	Event.js, Main.js, kweetHandler.php
 */

function Kweet(username, fullname, picture, kweetId, message, date, removable) {
	// Private reference to the object
	var self = this;
	/*
	   **********************
	   * Properties			*
	   **********************
	*/
	this.username = username;
	this.fullname = fullname;
	this.picture = picture;
	this.kweetId = kweetId;
	this.message = message;
	this.date = date;
	// Indicating if the kweet is removable
	// kweetHandler.php makes users own kweets removable
	this.removable = removable; 
	
	// --------------------------------------------------------
	// Kweet class constructor. Enabling all js functionality
	// --------------------------------------------------------
	//
	function createKweet() {
		try {
			// Creating Kweet DOM elements
			self.element = document.createElement('div');
			self.element.setAttribute('id', 'kweet');
			// Creating div element which will contain all user info
			var profileContainer = document.createElement('div');
			profileContainer.setAttribute('class', 'profileContainer');
			var pFullname = document.createElement('h3');
			pFullname.innerHTML = fullname;
			var pUsername = document.createElement('h4');
			pUsername.innerHTML = username;
			var pictureTag = document.createElement('img');
			pictureTag.setAttribute('src', picture);
			
			// Creating div element which will contain the kweet,
			// the date and time of the kweet and the delete button
			// if kweet is removable.
			var messageContainer = document.createElement('div');
			messageContainer.setAttribute('class', 'messageContainer');
			var dateContainer = document.createElement('div');
			dateContainer.setAttribute('class', 'dateContainer');
			var pMessage = document.createElement('p');
			pMessage.innerHTML = message;
			var pDate = document.createElement('h5');
			pDate.innerHTML = date;
			pDate.setAttribute('class', 'date');
			var hr = document.createElement('hr');
			
			// Attaching them to the kweet.
			
			// Profile: full name, username and profile picture.
			self.element.appendChild(profileContainer);
			profileContainer.appendChild(pFullname);
			profileContainer.appendChild(pUsername);
			profileContainer.appendChild(pictureTag);
			// Message: kweet message and date and time.
			self.element.appendChild(messageContainer);
			messageContainer.appendChild(pMessage);
			self.element.appendChild(dateContainer);
			dateContainer.appendChild(pDate);
			
			// Creating and attaching delete button if removable = 'yes', 
			// that is, if user_id in database is the users id when logged in.
			if (self.removable == 'yes') {
				var deleteBtn = document.createElement('button');
				deleteBtn.setAttribute('class', 'deleteBtn');
				deleteBtn.innerHTML = "Delete";
				// Attaching the delete button to the date container.
				var dateContainer = self.element.getElementsByClassName('dateContainer')[0];
				dateContainer.appendChild(deleteBtn);
				// Shows a Yes and a No button beneath the delete button if it is clicked.
				Event.addEventListener(deleteBtn, Event.CLICK, deleteKweetYesOrNo);
			}
			self.element.appendChild(hr);
		}
		catch (e) {
			console.log('Error in Kweet.js function createKweet: ' + e.message);
		}
	}
	
	/*
	   **********************
	   * Private methods		*
	   **********************
	*/
	//
	// Triggered when delete button is clicked.
	// Adds a Yes and a No button beneath the delete button
	// When Yes button is clicked "deleteKweet" is triggered,
	// which removes the kweet.
	// When No button is clicked "removeYesNoButtons" is triggered,
	// which removes the Yes and No buttons.
	//
	function deleteKweetYesOrNo(event) {
		try {
			// Creates DOM elements.
			var areYouSure = document.createElement('div');
			areYouSure.id = 'areYouSure';
			var pAreYouSure = document.createElement('p');
			pAreYouSure.innerHTML = 'Are you sure you want to delete this kweet (y/n)?';
			var yesButton = document.createElement('button');
			yesButton.id = 'yesButton';
			yesButton.innerHTML = 'Yes';
			var noButton = document.createElement('button');
			noButton.id = 'noButton';
			noButton.innerHTML = 'No';
			var hr = document.createElement('hr');
			
			// Attaching the elements to the kweet
			self.element.appendChild(areYouSure);
			areYouSure.appendChild(pAreYouSure);
			areYouSure.appendChild(yesButton);
			areYouSure.appendChild(yesButton);
			areYouSure.appendChild(noButton);
			areYouSure.appendChild(hr);
			
			// Click events: 
			// Triggers deleteKweet if Yes is clicked,
			// Triggers removeYesNoButtons if No is pressed.
			Event.addEventListener(yesButton, Event.CLICK, deleteKweet);
			Event.addEventListener(noButton, Event.CLICK, removeYesNoButtons);
		}
		catch (e) {
			console.log('Error in Kweet.js function deleteKweetYesOrNo: ' + e.message);
		}
	}
	//
	// Triggered by click on Yes button after click on delete button.
	// Deletes kweet from the database using kweetHandler.php with GET parameter method=delete.
	// Removes the kweet object, that is this object.
	//
	function deleteKweet(event) {
		try {
			Main.ajax.get("src/php/kweetHandler.php?method=delete&id=" + self.kweetId, function() {
				self.element.parentNode.removeChild(self.element);
			});
		}
		catch (e) {
			console.log('Error in Kweet.js function deleteKweet: ' + e.message);
		}
	}
	//
	// Triggered by click on No button after click on delete button.
	// Removes element containing Yes and No buttons.
	//
	function removeYesNoButtons() {
		try {
			self.element.removeChild(document.getElementById('areYouSure'));
		}
		catch (e) {
			console.log('Error in Kweet.js function removeYesNoButtons: ' + e.message);
		}
	}
	/*
	   *****************************************************************
	   * Triggers Kweet class constructor which creates a Kweet object *
	   *****************************************************************
	*/
	createKweet();
}
