<?php
 /**
 *	Signs user out by destroying all session varibles
 * including the id and password needed when logged in user is 
 * verified when a page, restricted to logged in users is loaded
 *
 *	@author		Robin Kanthe
 *	@email		kanthe.robin@gmail.com
 *	@version		1.0
 *	@since		2015-08-21
 *	@requires	login.php
 */
 	try {
		session_start();
		session_destroy();
		header("Location: ../../login.php");
	}
	catch (Exception $e) {
		var_dump('Caught exception in logout.php: '.$e->getMessage());
	}
?>