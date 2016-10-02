<?php
/**
 * Script triggered when update user details form is 
 * sent in settings.php. Updates userdetails in database.
 * Don't update and returns to settings.js if not filled correctly. 
 *
 *	@author		Robin Kanthe
 *	@email		kanthe.robin@gmail.com
 *	@version		1.0
 *	@since		2015-08-21
 *	@requires	login.php, settings.php
 */
	try {
		session_start();
		$_SESSION['errorChange'] = '';
		$_POST['username'] = htmlspecialchars( $_POST['username'] );
		$_POST['fullname'] = htmlspecialchars( $_POST['fullname'] );
		$_POST['password'] = htmlspecialchars( $_POST['password'] );
		$_POST['confirmPassword'] = htmlspecialchars( $_POST['confirmPassword'] );
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
		// If username or fullname fields was empty when sending the form, 
		// nothing is updated
		else if (empty($_POST['username']) || empty($_POST['fullname'])) {
			$_SESSION['errorChange'] = "Username and Full name fields are not filled.";
		}
		// Else username, fullname is updated in database. Eventual new password is changed.
		else {
			mysql_query("UPDATE userdetails 
				SET username='" . $_POST['username'] . "', fullname='" . $_POST['fullname'] . "' 
				WHERE user_id='" . $_SESSION['id'] . "'");
			$_SESSION['errorChange'] = $_SESSION['errorChange'] . "Username and/or Full name updated. <br>";
			$_SESSION['username'] = $_POST['username'];
			$_SESSION['fullname'] = $_POST['fullname'];
			// Checking password length
			if(strlen($_POST['password']) < 8) {
				$_SESSION['errorChange'] .= "The password must have at least 8 characters. Please try again.";
			}
			// If the from form sent password and confirmed password does not match,
			// password is not updated.
			else if($_POST['password'] != $_POST['confirmPassword']) {
				$_SESSION['errorChange'] .= "New password is invalid or confirmed password and password did not match. <br>";
			}
			// Password is updated if not empty and matches confirmed password.
			else if(!empty($_POST['password']) && $_POST['password'] == $_POST['confirmPassword']) {
				$_SESSION['password'] = crypt($_POST['password'],  '$6$rounds=5000$du6FpKCXbm;?cwQ$');
				mysql_query("UPDATE user SET password='" . $_SESSION['password'] . "' WHERE id='" . $_SESSION['id'] . "'");
				$_SESSION['errorChange'] .= "Password successfully changed. <br>";
			}
		}
		
		mysql_close($connection);
		header("Location: ../../settings.php");
	}
	catch (Exception $e) {
		var_dump('Caught exception in updateUserDetails.php: '.$e->getMessage());
	}
?>