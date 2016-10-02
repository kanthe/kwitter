<?php
/**
 * Doing sequrity checks on added articles since they are sent
 * with form.
 *	Performs tasks related to articles, depending on sent GET method parameter:
 *		- addFollower: Adding a record to databas table "userfollowing"
 *		- removeFollower: Deleting a record to databas table "userfollowing"
 *		- allContacts: Bringing all contacts from database. Returning them in XML form.
 *		- following: Bringing all contacts user is following. Returning them in XML form.
 *		- followers: Bringing all contacts user follows. Returning them in XML form.
 *
 *	@author		Robin Kanthe
 *	@email		kanthe.robin@gmail.com
 *	@version		1.0
 *	@since		2015-08-21
 *	@requires	login.php, contacts.php
 */
	try {
		session_start();
		include "connect.php";
		// Checking connection. Redirecting to login.php if connection fails.
		if(!$connection = mysql_connect($server, $dbUsername, $password)) {
			$_SESSION['errorLogin'] = "Connection failed.";
			header("Location: ../../login.php");
			exit;
		}
		// Checking if database exists. Redirecting to login.php if not.
		else if(!$database = mysql_select_db($databaseName)) {
			$_SESSION['errorLogin'] = "Database does not exist.";
			header("Location: ../../login.php");
			exit;
		}
		else {
			/*******************************************/
			/* 					FUNCTIONS					 */
			/*******************************************/
			//
			// Selecting users which the logged in user follows from the userfollowing table.
			// Returning id's of the followed users 
			//
			function selectFollowings() {
				$query = "SELECT following_id	FROM userfollowing 
					WHERE follower_id!=following_id 
					AND follower_id='" . $_SESSION['id'] . "'";
				$result = mysql_query($query);
				$i = 0;
				$followings[0] = false;
				
				while($row = mysql_fetch_assoc($result)) {
					$followings[$i] = $row['following_id'];
					$i++;
				}
				return $followings;
			}
			//
			// Selecting followers of the logged in user from the userfollowing table
			// Returning id's of the followers
			//
			function selectFollowers() {
				$query = "SELECT follower_id	FROM userfollowing 
					WHERE follower_id!=following_id 
					AND following_id='" . $_SESSION['id'] . "'";
				$result = mysql_query($query);
				$i = 0;
				$followers[0] = false;
				
				while($row = mysql_fetch_assoc($result)) {
					$followers[$i] = $row['follower_id'];
					$i++;
				}
				return $followers;
			}
			// If $_GET['method'] = 'addFollower': Inserts the user and the user
			// whos following button was clicked, inte the database userfollowing table.
			if($_GET['method'] == 'addFollower') {
				$query = "INSERT INTO userfollowing (follower_id, following_id) 
					VALUES ('" . $_SESSION['id'] . "', (
						SELECT user_id FROM userdetails 
						WHERE username='" . $_GET['username'] . "'))";
				mysql_query($query);
				mysql_close($connection);
				header("Location: ../../contacts.php");
				exit;
			}
			// If $_GET['method'] = 'removeFollower': Deletes record from 
			// the database userfollowing table. Triggered when 
			// a following button is clicked
			else if($_GET['method'] == 'removeFollower') {
				mysql_query("DELETE FROM userfollowing 
					WHERE userfollowing.follower_id='" . $_SESSION['id'] . "' 
					AND userfollowing.following_id=(
						SELECT userdetails.user_id FROM userdetails 
						WHERE userdetails.username='" . $_GET['username'] . "')");
				mysql_close($connection);
				header("Location: ../../contacts.php");
				exit;
			}
			// If $_GET['method'] = 'allContacts': Selecting all user details data
			// of all contacts. 
			else if($_GET['method'] == 'allContacts') {
				$query = "SELECT user_id, username, fullname, picture
					FROM userdetails 
					WHERE user_id!='" . $_SESSION['id'] . "' 
					ORDER BY fullname";
				
				$followings = selectFollowings();
				$followers = selectFollowers();
			}
			// If $_GET['method'] = 'following': Selecting all user details data
			// of all contacts user is following. 
			else if($_GET['method'] == 'following') {
				$query = "SELECT userdetails.user_id, userdetails.fullname, userdetails.username, userdetails.picture 
					FROM userdetails, userfollowing 
					WHERE userdetails.user_id!='" . $_SESSION['id'] . "' 
					AND userfollowing.follower_id='" . $_SESSION['id'] . "' 
					AND userdetails.user_id=userfollowing.following_id 
					AND userfollowing.follower_id!=userfollowing.following_id 
					ORDER BY userdetails.fullname";
				$followings = selectFollowings();
				$followers = selectFollowers();
			}
			// If $_GET['method'] = 'followers': Selecting all user details data
			// of all contacts that follows user.
			else if($_GET['method'] == 'followers') {
				$query = "SELECT userdetails.user_id, userdetails.fullname, userdetails.username, userdetails.picture 
					FROM userdetails, userfollowing 
					WHERE userdetails.user_id!='" . $_SESSION['id'] . "' 
					AND userfollowing.following_id='" . $_SESSION['id'] . "' 
					AND userdetails.user_id=userfollowing.follower_id 
					AND userfollowing.follower_id!=userfollowing.following_id 
					ORDER BY userdetails.fullname";
				$followings = selectFollowings();
				$followers = selectFollowers();
			}
			// Continuing from allContacts, following and followers selection
			// Building an XML document containing all 
			// data and returns it to the file doing the AJAX get request.
			$result = mysql_query($query);
			header('Content-Type: application/xml; charset=utf-8');
			$domDoc = new DOMDocument();
			$contacts = $domDoc->createElement('contacts');
			$domDoc->appendChild($contacts);
			
			while($row = mysql_fetch_assoc($result)) {
				$contact = $domDoc->createElement('contact');
				$fullname = $domDoc->createElement('fullname');
				$fullname->nodeValue = $row['fullname'];
				$username = $domDoc->createElement('username');
				$username->nodeValue = $row['username'];
				$picture = $domDoc->createElement('picture');
				$picture->nodeValue = "src/img/" . $row['picture'];
				// Creates a new tag and
				// determines if the logged in user is following the current contact
				// If so the tag is set to 'true'.
				$following = false;
				$followingTag = $domDoc->createElement('following');
				
				for($i = 0; $i < count($followings); $i++) {
					
					if($followings[$i] == $row['user_id']) {
						$following = true;
						break;
					}
				}
				$followingTag->nodeValue = $following;
				// Creates a new tag and
				// determines if the logged in user is followed by the current contact
				// If so the tag is set to 'true'.
				$follower = false;
				$followerTag = $domDoc->createElement('follower');
				
				for($i = 0; $i < count($followers); $i++) {
					
					if($followers[$i] == $row['user_id']) {
						$follower = true;
						break;
					}
					else {
						$follower = false;
					}
				}
				$followerTag->nodeValue = $follower;
				
				$contacts->appendChild($contact);
				$contact->appendChild($fullname);
				$contact->appendChild($username);
				$contact->appendChild($picture);
				$contact->appendChild($followingTag);
				$contact->appendChild($followerTag);
			}
			echo $domDoc->saveXML();
			
			mysql_close($connection);
		}
	}
	catch (Exception $e) {
		var_dump('Caught exception in contactHandler.php: '.$e->getMessage());
	}
?>