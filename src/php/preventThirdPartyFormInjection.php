<?php
/*
 *	Script, which prevents that a third party server injects 
 * malicious script in a form. After a coded token have been 
 * saved in a session variable and sent in the form with the 
 * createToken.php script, the two values are compared and if 
 * one of them don't exist or they have different values, 
 * script exits to login.php with an error message.
 * 
 * Source: CSS Tricks – Serious Form Sequrity 
 * 	<https://css-tricks.com/serious-form-security> [2009-05-19]
 *
 *	@author		Robin Kanthe
 *	@email		kanthe.robin@gmail.com
 *	@version		1.0
 *	@since		2015-08-21
 */
 	// 
 	// If the values are not equal, script exits to login.php
	if(!verifyFormToken()) {
		try {
			$_SESSION['attacError'] = "Login failed/connection interrupted for sequrity reasons. Suspected third party form injection attack.";
			header("Location: ../../login.php");
			exit;
		}
		catch (Exception $e) {
			var_dump('Caught exception in preventThirdPartyFormInjection.php: '.$e->getMessage());
		}
	}
	//
	// Function for compare the saved token, $_SESSION['token'], and the sent token, $_POST['token']
	// Returns true if they are equal, otherwise false
	//
	function verifyFormToken() {
		try {
		   // Check if a session is started and a token is transmitted, if not return an error
			if(!isset($_SESSION['token'])) { 
				return false;
		    }
			// Check if the form is sent with token in it
			if(!isset($_POST['token'])) {
				return false;
		    }
			// Compare the tokens against each other if they are still the same
			if ($_SESSION['token'] !== $_POST['token']) {
				return false;
		    }
			return true;
		}
		catch (Exception $e) {
			var_dump('Caught exception in preventThirdPartyFormInjection.php, function : verifyFormToken'.$e->getMessage());
		}
	}
?>