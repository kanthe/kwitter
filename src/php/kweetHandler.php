<?php
/**
 * Doing sequrity checks on added/changed kweets (messages) since they are sent
 * with form.
 *	Performs tasks related to articles, depending on sent GET method parameter:
 *		- new: Adding kweets to database
 *		- delete: Removing a kweet
 * And depending on sent GET kweetList parameter:
 *		- myKweets: Bringing logged in users kweets from database. Returning them in XML form.
 *		- allKweets: Bringing all kweets from database. Returning them in XML form.
 *	@author		Robin Kanthe
 *	@email		kanthe.robin@gmail.com
 *	@version		1.0
 *	@since		2015-08-21
 *	@requires	login.php, index.php
 */
	try {
		if(session_status() != PHP_SESSION_ACTIVE) {
 			session_start();
 		}
		if(isset($_GET['method']) && $_GET['method'] == 'new') {
			// Preventing other servers from sending malicious scripts
			include_once "preventThirdPartyFormInjection.php";
		}
		// Checking for email header injections
		include "preventEmailHeaderInjection.php";
		// Connecting to database 
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
		// If $_SESSION['kweetList'] = 'myKweets': Selecting all data of logged in users kweets from database
		else if(isset($_SESSION['kweetList']) && $_SESSION['kweetList'] == 'myKweets') {
			$query = "SELECT userdetails.username AS username, userdetails.fullname AS fullname, userdetails.picture AS picture,
				kweet.id AS kweetId, kweet.user_id AS userId, kweet.message AS message, kweet.date AS date
				FROM userdetails, kweet, user 
				WHERE kweet.user_id=user.id 
				AND userdetails.user_id=user.id 
				AND user.id='" . $_SESSION['id'] . "' 
				ORDER BY date DESC LIMIT 50";
			unset($_SESSION['kweetList']);
		}
		// If $_SESSION['kweetList'] = 'allKweets': Selecting all data of all users kweets from database
		else if(isset($_SESSION['kweetList']) && $_SESSION['kweetList'] == 'allKweets') {
			$query = "SELECT userdetails.username AS username, userdetails.fullname AS fullname, userdetails.picture AS picture,
				kweet.id AS kweetId, kweet.user_id AS userId, kweet.message AS message, kweet.date AS date
				FROM userdetails, kweet, userfollowing 
				WHERE userfollowing.follower_id='" . $_SESSION['id'] . "' 
				AND kweet.user_id=userfollowing.following_id 
				AND userdetails.user_id=userfollowing.following_id
				 ORDER BY date DESC LIMIT 50";
			unset($_SESSION['kweetList']);
		}
		// If $_GET['method'] = 'new': Adding kweet sent by form to database.
		// Converting html characters to plain text preventing malicious scripts.
		// Redirecting to index.php if field is empty of more than 140 characters.
		else if(isset($_GET['method']) && $_GET['method'] == 'new') {
			$_POST['kweet'] = htmlspecialchars($_POST['kweet']);
			
			if(!isset($_POST['kweet']) || empty($_POST['kweet'])) {
				$_SESSION['errorIndex'] = "Text field is empty. You need to write something in order to send it.";
			}
			else if(strlen($_POST['kweet']) > 140) {
				$_SESSION['errorIndex'] = "The Kweet is not allowed to be longer than 140 characters. Please try again.";
				$_SESSION['kweet'] = $_POST['kweet'];
			}
			else {
				mysql_query("INSERT INTO kweet (user_id, message) 
					VALUES ('" . $_SESSION['id'] . "', '" . $_POST['kweet'] . "')");
			}
			mysql_close($connection);
			header("Location: ../../index.php");
			exit;
		}
		// If $_GET['method'] = 'delete': Deleting kweet from database.
		else if(isset($_GET['method']) && $_GET['method'] == 'delete' && isset($_GET['id'])) {
			$query = "DELETE FROM kweet WHERE id='" . $_GET['id'] . "'";
			mysql_query($query);
			header("Location: ../../index.php");
			exit;
		}
		// Continuing from myKweets and allKweets selection
		// Building an XML document containing all 
		// data and returns it to the file doing the AJAX get request.
		$result = mysql_query($query);
		
		header('Content-Type: application/xml; charset=utf-8');
		$domDoc = new DOMDocument();
		$kweets = $domDoc->createElement('kweets');
		$domDoc->appendChild($kweets);
		
		while($row = mysql_fetch_assoc($result)) {
				
			$kweet = $domDoc->createElement('kweet');
			$username = $domDoc->createElement('username');
			$username->nodeValue = $row['username'];
			$fullname = $domDoc->createElement('fullname');
			$fullname->nodeValue = $row['fullname'];
			$picture = $domDoc->createElement('picture');
			$picture->nodeValue = "src/img/" . $row['picture'];
			$kweetId = $domDoc->createElement('kweetId');
			$kweetId->nodeValue = $row['kweetId'];
			$message = $domDoc->createElement('message');
			$message->nodeValue = $row['message'];
			$date = $domDoc->createElement('date');
			$date->nodeValue = $row['date'];
			$removable = $domDoc->createElement('removable');
			
			if($row['userId'] == $_SESSION['id']) {
				$removable->nodeValue = 'yes';
			}
			else {
				$removable->nodeValue = 'no';
			}
			
			$kweets->appendChild($kweet);
			$kweet->appendChild($username);
			$kweet->appendChild($fullname);
			$kweet->appendChild($picture);
			$kweet->appendChild($kweetId);
			$kweet->appendChild($message);
			$kweet->appendChild($date);
			$kweet->appendChild($removable);
		}
		echo $domDoc->saveXML();
		mysql_close($connection);
	}
	catch (Exception $e) {
		var_dump('Caught exception in kweetHandler.php: '.$e->getMessage());
	}	
?>

