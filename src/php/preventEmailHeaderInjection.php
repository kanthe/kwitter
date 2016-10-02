<?php
 /**
 *	Script preventing a user to put an email injection in a form 
 * Uses the fact that these injections has to contain "Bcc", 
 * "Cc:" or "Content-Type:" and recogizes them with a regular 
 * expression. Exits to login.php in that case.
 * Source: David Powers (2010), PHP Solutions – Dynamic Web Design Made Easy, 
 * Second edition, s.118-121)
 *
 *	@author		Robin Kanthe
 *	@email		kanthe.robin@gmail.com
 *	@version		1.0
 *	@since		2015-08-21
 *	@requires	login.php, index.php
 */
	try {
		$suspect = false; // If true a suspected e-mail injection was found
		// Regular expression used to find suspected email injections
		$pattern = '/Content-Type:|Bcc:|Cc:/i'; 
		isSuspect($_POST, $pattern, $suspect); // Testing all $_POST variables
		// Returning to login page if one $_POST variable matches $pattern
		if($suspect) {
			$_SESSION['attacError'] = "Login failed/connection interrupted for sequrity reasons. Suspected email injection attack. ";
			header("Location: ../../login.php");
			exit;
		}
	}
	catch (Exception $e) {
		var_dump('Caught exception in preventEmailHeaderInjection.php'.$e->getMessage());
	}
	//
	// Testing if a value/array contains a certain regular expression ($pattern)
	//
	function isSuspect($val, $pattern, &$suspect) {
		try {
			// If val is an array
			if(is_array($val)) {
				// Testing each value in the array
				foreach ($val as $item) {
					isSuspect($item, $pattern, $suspect);
				}
			}
			// If value is not an array
			else {
				// Testing if the value matches the regular expression ($pattern)
				if(preg_match_all($pattern, $val)) {
					$suspect = true;
				}
	 		}
 		}
		catch (Exception $e) {
			var_dump('Caught exception in preventEmailHeaderInjection.php, function : isSuspect'.$e->getMessage());
		}
 	}
?>