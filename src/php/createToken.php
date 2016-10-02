<?php
/*
 *	Creates a unique coded value (token) and saves it on the 
 * server side ($_SESSION['token']) and client side ($token)
 * The token is later used in a script, which prevents that 
 * a third party server injects malicious script in a form. 
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
 	// Generates a unique value with a current time stamp, microtime()
 	// This value is then coded with md5 and saved to 
 	// $_SESSION['token'] and $token.
 	//
	function generateFormToken() {
		try {
	    	$token = md5(uniqid(microtime(), true));  
	    	$_SESSION['token'] = $token; 
			
	    	return $token;
    	}
		catch (Exception $e) {
			var_dump('Caught exception in generateFormToken: '.$e->getMessage());
		}
	}
?>