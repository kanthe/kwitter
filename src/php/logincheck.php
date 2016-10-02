<?php
 /**
 *	Script for sequrity checks and validation.
 * Doing different things depending on sent GET parameter:
 *		- login: Checking parameters sent when signing in.
 *		- check: Checking session parameters id and password to confirm 
 *					user when any of the pages are loaded.
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
 		if($_GET['method'] != 'check') {
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
		// If connection succeeds and user is logging in from login.php.
		// Converting eventual html code to text, to prevent malicious scripts or 
		// redirection to malicious websites in form fields.
		// Saves email value in $_SESSION['email'] to be used to refill input fields 
		// in case of invalid user inputs.
		// Redirecting to login page if form is filled with incorrect values.
		else if($_GET['method'] == 'login') {
			unset($_GET['method']);
			$_SESSION['emailLogin'] = $_POST['emailLogin'] = htmlspecialchars( $_POST['emailLogin'] );
			$_POST['password'] = htmlspecialchars( $_POST['password'] );
			// Getting email and encrypted password from database.
			$result = mysql_query("SELECT email FROM user WHERE email='" . $_POST['emailLogin'] . "'");
			$emailLogin = mysql_fetch_assoc($result)['email'];
			$result = mysql_query("SELECT password FROM user WHERE email='" . $_POST['emailLogin'] . "'");
			$password = mysql_fetch_assoc($result)['password'];
			// Checking if all fields have been filled
			if(empty($_POST['emailLogin']) || empty($_POST['password'])) {
				$_SESSION['errorLogin'] = "All fields are not filled. Please try again.";
				mysql_close($connection);
				header("Location: ../../login.php");
				exit;
			}
			// Checking if password from database equals password from form input
			else if ($emailLogin != $_POST['emailLogin']) {
				$_SESSION["errorLogin"] = "No user was found with that e-mail address. Please try again.";
				mysql_close($connection);
				header("Location: ../../login.php");
				exit;
			}
			// Checking if the encrypted email from database equals email from form input, encrypted
			else if($password != crypt($_POST['password'], '$6$rounds=5000$du6FpKCXbm;?cwQ$')) {
			// else if($password != $_POST['password']) {
				$_SESSION['errorLogin'] = "Password is incorrect. Please try again.";
				mysql_close($connection);
				header("Location: ../../login.php");
				exit;
			}
			// If no input errors was found, the user id and encrypted password are stored in $_SESSION variables, 
			// to be used when identifying user, before entering a page.
			// That is done in this php script, with $_GET['method'] = 'check'
			// User is now directed to index.php, where the check is done.
			else {
				$result = mysql_query("SELECT id FROM user WHERE email='" . $_POST['emailLogin'] . "'");
				$_SESSION['id'] = mysql_fetch_assoc($result)['id'];
				$_SESSION['password'] = $password;
				mysql_close($connection);
				header("Location: ../../index.php");
				exit;
			}
		}
		// If connection succeeds and before user is visiting a page (except login.php),
		// a check of user id and password is done with $_SESSION['id'] and $_SESSION['password'].
		else if($_GET['method'] == 'check') {
			unset($_GET['method']);
			// Bringing users id and password from the database
			$result = mysql_query("SELECT password FROM user WHERE id='" . $_SESSION['id'] . "'");
			$password = mysql_fetch_assoc($result)['password'];
			// If user is not logged in $_SESSION['id'] and/or $_SESSION['password'] is not set
			// User is redirected to login page
			if(!isset($_SESSION['id']) || !isset($_SESSION['password'])) {
				$_SESSION['errorLogin'] = "You are not signed in. Please sign in";
				mysql_close($connection);
				header("Location: login.php");
				exit;
			}
			// If password is wrong user is redirected to login page
			else if($password != $_SESSION['password']) {
				$_SESSION['errorLogin'] = "Cannot load page. Password is incorrect.";
				mysql_close($connection);
				header("Location: login.php");
				exit;
			}
			mysql_close($connection);
		}
		// If $_GET['method'], unexpectedly, is neither 'login' or 'check', 
		// error message is returned.
		else {
			$_SESSION["errorLogin"] = "Error: Invalid/no value on GET parameter 'method'. ";
		}
	}
	catch (Exception $e) {
		var_dump('Caught exception in logincheck.php: '.$e->getMessage());
	}	
?>