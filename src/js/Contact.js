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
 *	@requires	Event.js, Main.js, contactHandler.php
 */

function Contact(fullname, username, picture, following, follower) {
	// Private reference to the object
	var self = this;
	/*
	   **********************
	   * Properties			*
	   **********************
	*/
	this.fullname = fullname;
	this.username = username;
	this.picture = picture;
	this.following = following;
	this.follower = follower;
	//
	// --------------------------------------------------------
	// Contact class constructor. Enabling all js functionality
	// --------------------------------------------------------
	//
	function createContact() {
		try {
			// Creating Contact elements
			self.element = document.createElement('div');
			self.element.setAttribute('id', 'contact');
			var profileContainer = document.createElement('div');
			profileContainer.setAttribute('class', 'profileContainer');
			var pFullname = document.createElement('h3');
			pFullname.innerHTML = fullname;
			var pUsername = document.createElement('h4');
			pUsername.innerHTML = username;
			var pictureTag = document.createElement('img');
			pictureTag.setAttribute('src', picture);
			var followBtn = document.createElement('button');
			followBtn.setAttribute('class', 'followBtn');
			var followerBtn = document.createElement('button');
			followerBtn.setAttribute('class', 'followerBtn');
			followerBtn.setAttribute('disabled', 'disabled');
			var hr = document.createElement('hr');
			
			// Determining the style class of the "following" button.
			// If it is 1 (true) the class is set to "following" 
			// meaning button color will be green 
			// and button text will be "following".
			// Else (false) reults in class "notFollowing" 
			// meaning button color will be red 
			// and button text will be not following.
			if (following == 1) {
				followBtn.setAttribute('class', 'following');
				followBtn.innerHTML = 'Following';
			}
			else {
				followBtn.setAttribute('class', 'notFollowing');
				followBtn.innerHTML = 'Not following';
			}
			// The same goes for the "follower" field.
			// Class "follower" means color is blue.
			// Class "noFollower" means field in not visible
			if (follower == 1) {
				followerBtn.setAttribute('class', 'follower');
				followerBtn.innerHTML = 'Follower';
			}
			else {
				followerBtn.setAttribute('class', 'noFollower');
				followerBtn.innerHTML = 'No follower';
			}
			// Clicking the "following" button triggeres function "changeFollowStatus" (below)
			Event.addEventListener(followBtn, Event.CLICK, changeFollowStatus);
			
			// Attaching them to the Contact
			self.element.appendChild(profileContainer);
			profileContainer.appendChild(pFullname);
			profileContainer.appendChild(pUsername);
			profileContainer.appendChild(pictureTag);
			self.element.appendChild(followBtn);
			self.element.appendChild(followerBtn);
			self.element.appendChild(hr);
			}
		catch (e) {
			console.log('Error in Contacts.js function createContact: ' + e.message);
		}
	}
	
	/*
	   **********************
	   * Private methods		*
	   **********************
	*/
	//
	// Triggered by clicking the "following" button changes following status, which means
	// the corresponding row in the database is deleted/added added and
	// the class will change to "following"/"notFollowing".
	//
	function changeFollowStatus(event) {
		try {
			var btnClass = this.getAttribute('class');
			var username = this.parentNode.getElementsByTagName('h4')[0].innerHTML;
			
			if (btnClass == 'following') {
				this.setAttribute('class', 'notFollowing');
				this.innerHTML = 'Not following';
				Main.ajax.get("src/php/contactHandler.php?method=removeFollower&username=" + username, Main.doNothing);
			}
			else if (btnClass == 'notFollowing') {
				this.setAttribute('class', 'following');
				this.innerHTML = 'Following';
				Main.ajax.get("src/php/contactHandler.php?method=addFollower&username=" + username, Main.doNothing);
			}
		}
		catch (e) {
			console.log('Error in Contact.js function changeFollowStatus: ' + e.message);
		}
	}
	/*
	   *****************************************************************
	   * Triggers Contact class constructor which creates a Contact object *
	   *****************************************************************
	*/
	createContact();
}