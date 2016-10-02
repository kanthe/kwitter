<?php
 /**
 *	Taking values from Add user form in Login.js 
 * and adds them to database. 
 * Also writing message to the $_SESSION["errorAdd"]
 * variable and redirecting to login.php when user 
 * fill the form improperly.
 *
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
 		// Preventing other servers from sending malicious scripts
		include_once "preventThirdPartyFormInjection.php";
		// Checking for email header injections
		include "preventEmailHeaderInjection.php";
		// Converting eventual html code to text, to prevent malicious scripts or 
		// redirection to malicious websites in form fields.
		// Saves form values in $_SESSION variables to be used to refill input fields 
		// in case of invalid user inputs.
		$_SESSION['emailAdd'] = $_POST['emailAdd'] = htmlspecialchars( $_POST['emailAdd'] );
		$_SESSION['username'] = $_POST['username'] = htmlspecialchars( $_POST['username'] );
		$_SESSION['fullname'] = $_POST['fullname'] = htmlspecialchars( $_POST['fullname'] );
		$_POST['password'] = htmlspecialchars( $_POST['password'] );
		$_POST['confirmPassword'] = htmlspecialchars( $_POST['confirmPassword'] );
		// Connecting to database 
		include "connect.php";
		// Checking connection. Redirecting to login.php if connection fails.
		if(!$connection = mysql_connect($server, $dbUsername, $password)) {
			$_SESSION['errorAdd'] = "Connection failed.<hr>";
			header("Location: ../../login.php");
			exit;
		}
		// Checking if database exists. Redirecting to login.php if not.
		else if(!$database = mysql_select_db($databaseName)) {
			$_SESSION['errorAdd'] = "Database does not exist.";
			header("Location: ../../login.php");
			exit;
		}
		// If connection succeded
		// Checking if all fields have been filled
		else if(empty($_POST['emailAdd']) || empty($_POST['username']) || empty($_POST['fullname']) 
					|| empty($_POST['password'])) {
			$_SESSION['errorAdd'] = "Not all fields are filled. Please try again.";
			mysql_close($connection);
			header("Location: ../../login.php");
			exit;
		}
		// Checking password length
		else if(strlen($_POST['password']) < 8) {
			$_SESSION['errorAdd'] = "The password must have at least 8 characters. Please try again.";
			mysql_close($connection);
			header("Location: ../../login.php");
			exit;
		}
		// Checking if password and confirmed password match
		else if($_POST['password'] != $_POST['confirmPassword']) {
			$_SESSION['errorAdd'] = "The confirmed password and the password does not match. Please try again.";
			mysql_close($connection);
			header("Location: ../../login.php");
			exit;
		}
		// If form was filled correctly
		else {
			$query = "SELECT email FROM user WHERE email='" . $_POST['emailAdd'] . "'";
			$emailAdd = mysql_query($query);
			$query = "SELECT username FROM userDetails WHERE username='" . $_POST['username'] . "'";
			$username = mysql_query($query);
			
			// Checking if the email is already used by another user
			if(mysql_num_rows($emailAdd) != 0) {
				$_SESSION["errorAdd"] = "Email already exists. Please try again.";
				mysql_close($connection);
				header("Location: ../../login.php");
				exit;
			}
			// Checking if the username is already used by another user
			else if(mysql_num_rows($username) != 0) {
				$_SESSION["errorAdd"] = "Username already exists. Please try again.";
				mysql_close($connection);
				header("Location: ../../login.php");
				exit;
			}
			// If email and username are available
			else {
				// Saving id and encrypted password in session variables.
				// They are used on all pages to determine if a user is logged in.
				// Inserts posted data into database tables user, userdetails and userfollowing.
				// A user is following oneself.
				// Then relocating to index.php
				$_SESSION['password'] = crypt($_POST['password'], '$6$rounds=5000$du6FpKCXbm;?cwQ$'); // Hashing password
				$result = mysql_query("INSERT INTO user (email, password) 
					VALUES ('" . $_POST['emailAdd'] . "', '" . $_SESSION['password'] . "')");
				$query = "SELECT id FROM user WHERE email='" . $_POST['emailAdd'] . "'";
				$result = mysql_query($query);
				$_SESSION['id'] = mysql_fetch_assoc($result)['id'];
				/*
				mysql_query("INSERT INTO userdetails (user_id, username, fullname, picture) VALUES ('" . $_SESSION['id'] . "', '" . $_POST['username'] . "', '" . $_POST['fullname'] . "', 1439743110_male3.png')");
				*/				
				$result = mysql_query("INSERT INTO userdetails (user_id, username, fullname) 
					VALUES ('" . $_SESSION['id'] . "', '" . $_SESSION['username'] . "', '" . $_SESSION['fullname'] . "')");
				$result = mysql_query("INSERT INTO userfollowing (follower_id, following_id) 
					VALUES ('" . $_SESSION['id'] . "', '" . $_SESSION['id'] . "')");
				mysql_close($connection);
				header("Location: ../../index.php");
			}
		}
	}
	catch (Exception $e) {
		var_dump('Caught exception in addUser.php: '.$e->getMessage());
	}
?>