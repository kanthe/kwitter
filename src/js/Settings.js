 /**
 * Creating forms to change profile picture and user details.
 * Inserting them to "changeProfilePicture" and "changeProfilePicture" tags in settings.php
 * Inserting userdetails from database in form fields. 
 * 
 *	@author		Robin Kanthe
 *	@email		kanthe.robin@gmail.com
 *	@version		1.0
 *	@since		2015-08-21
 *	@requires	Event.js, Main.js, getUserDetail.php
 */

var Settings = {
	//
	// ----------------------------------------------------------
	// Settings class constructor. Enabling all js functionality
	// ----------------------------------------------------------
	//
	init : function() {
		try {
			// Creates forms
			Settings.userdetailsForm();
			// Getting user details from database
			Main.ajax.get("src/php/getUserDetail.php?method=username", Settings.insertUsername);
			Main.ajax.get("src/php/getUserDetail.php?method=fullname", Settings.insertFullname);
			Main.ajax.get('src/php/getUserDetail.php?method=profilePic', Settings.getProfilePic);
		}
		catch (e) {
			console.log('Error in Settings.js function init: ' + e.message);
		}
	},
	userdetailsForm : function() {
		try {
			//
			// Creating DOM elements for upload profile picture
			//
			var changeProfilePicture = document.getElementById("changeProfilePicture");
			var profilePic = document.createElement('img');
			profilePic.setAttribute('class', 'profilePic');
			var picForm = document.createElement('form');
			picForm.setAttribute('action', 'settings.php');
			picForm.setAttribute('method', 'post');
			picForm.setAttribute('enctype', 'multipart/form-data');
			var picP = document.createElement('p');
			var picLabel = document.createElement('label');
			picLabel.innerHTML = "File: ";
			var fileInput = document.createElement('input');
			fileInput.setAttribute('type', 'file');
			fileInput.setAttribute('name', 'imgFile');
			var sendP = document.createElement('p');
			var sendFileBtn = document.createElement('input');
			sendFileBtn.setAttribute('type', 'submit');
			sendFileBtn.setAttribute('value', 'Send');
			sendFileBtn.setAttribute('id', 'sendFileBtn');
			var errorPic = document.createElement('div');
			errorPic.setAttribute('class', 'error');
			//
			// Creating DOM elements for change user details
			//
			var changeUserDetails = document.getElementById("changeUserDetails");
			var form = document.createElement('form');
			form.setAttribute('action', 'src/php/updateUserDetails.php');
			form.setAttribute('method', 'post');
			var p1 = document.createElement('p');
			p1.innerHTML = "Username: ";
			var username = document.createElement('input');
			username.setAttribute('type', 'text');
			username.setAttribute('name', 'username');
			var p2 = document.createElement('p');
			p2.innerHTML = "Full name: ";
			var fullname = document.createElement('input');
			fullname.setAttribute('type', 'text');
			fullname.setAttribute('name', 'fullname');
			var p3 = document.createElement('p');
			p3.innerHTML = "Password: ";
			var password = document.createElement('input');
			password.setAttribute('type', 'password');
			password.setAttribute('name', 'password');
			var p4 = document.createElement('p');
			p4.innerHTML = "Confirm password: ";
			var confirmPassword = document.createElement('input');
			confirmPassword.setAttribute('type', 'password');
			confirmPassword.setAttribute('name', 'confirmPassword');
			var token = document.createElement('input');
			token.setAttribute('type', 'hidden');
			token.setAttribute('name', 'token');
			token.setAttribute('value', '<?php echo $newToken; ?>');
			var confirmBtn = document.createElement('input');
			confirmBtn.setAttribute('type', 'submit');
			confirmBtn.setAttribute('value', 'Confirm');
			//
			// Attaching the DOM elements
			//
			changeProfilePicture.appendChild(profilePic);
			changeProfilePicture.appendChild(picForm);
			picForm.appendChild(picP);
			picP.appendChild(picLabel);
			picP.appendChild(fileInput);
			picForm.appendChild(sendP);
			sendP.appendChild(sendFileBtn);
			changeUserDetails.appendChild(errorPic);
			changeUserDetails.appendChild(form);
			form.appendChild(p1);
			form.appendChild(p2);
			form.appendChild(p3);
			form.appendChild(p4);
			p1.appendChild(username);
			p2.appendChild(fullname);
			p3.appendChild(password);
			p4.appendChild(confirmPassword);
			form.appendChild(token);
			form.appendChild(confirmBtn);
		}
		catch (e) {
			console.log('Error in Settings.js function userdetailsForm: ' + e.message);
		}
	},
	insertErrorPic : function(data) {
		try {
			var errorPicText = data.responseText;
			var errorPic = document.getElementById('errorPic');
			errorPic.innerHTML = errorPicText;
		}
		catch (e) {
			console.log('Error in Settings.js function insertErrorPic: ' + e.message);
		}
	},
	insertUsername : function(data) {
		try {
			var usernameText = data.responseText;
			var username = document.getElementsByName('username')[0];
			username.setAttribute('value', usernameText);
		}
		catch (e) {
			console.log('Error in Settings.js function insertUsername: ' + e.message);
		}
	},
	insertFullname : function(data) {
		try {
			var fullnameText = data.responseText;
			var fullname = document.getElementsByName('fullname')[0];
			fullname.setAttribute('value', fullnameText);
		}
		catch (e) {
			console.log('Error in Settings.js function insertFullname: ' + e.message);
		}
	},
	getProfilePic : function(data) {
		try {
			var picName = data.responseText;
			var profilePic = document.getElementsByClassName('profilePic')[0];
			profilePic.setAttribute('src', 'src/img/' + picName);
		}
		catch (e) {
			console.log('Error in Settings.js function getProfilePic: ' + e.message);
		}
	},
}

/*
 	********************************************
	* Starts the Settings class when website loads *
	********************************************
*/
Event.addEventListener(window, Event.LOAD, Settings.init);