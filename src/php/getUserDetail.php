<?php
 /**
 *	Retrieving data from the database userdetails table.
 * Triggered by an AJAX get request, where a get parameter
 * determines data should be retrieved.
 *
 *	@author		Robin Kanthe
 *	@email		kanthe.robin@gmail.com
 *	@version		1.0
 *	@since		2015-08-21
 *	@requires	login.php
 */
	try {
		if(session_status() != PHP_SESSION_ACTIVE) {
 			session_start();
 		}
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
		// Retrieving users username from database. Returning it to method doing the AJAX get request.
		else if($_GET['method'] == 'username') {
			$result = mysql_query("SELECT username FROM userdetails WHERE user_id='" . $_SESSION['id'] . "'");
			$username = mysql_fetch_assoc($result)['username'];
			echo $username;
		}
		// Retrieving users full name from database. Returning it to method doing the AJAX get request.
		else if($_GET['method'] == 'fullname') {
			$result = mysql_query("SELECT fullname FROM userdetails WHERE user_id='" . $_SESSION['id'] . "'");
			$fullname = mysql_fetch_assoc($result)['fullname'];
			echo $fullname; 
		}
		// Retrieving users profile picture from database. Returning it to method doing the AJAX get request.
		else if($_GET['method'] == 'profilePic') {
			$result = mysql_query("SELECT picture FROM userdetails WHERE user_id='" . $_SESSION['id'] . "'");
			$profilePic = mysql_fetch_assoc($result)['picture'];
			echo $profilePic;
		}
		else {
			echo "Error: No value on GET parameter 'method'. ";
		}
		mysql_close($connection);
	}
	catch (Exception $e) {
		var_dump('Caught exception in getUserDetail.php: '.$e->getMessage());
	}	
?>