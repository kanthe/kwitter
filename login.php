<?php
/**
 *	The "login" page.
 * Adding style sheet, navigation menu and 
 * forms for signing in and adding user.
 * Checking form inputs with logincheck.php and addUser.php
 * if user is logged correctly and if form inputs are done 
 * correctly.
 *
 *	@author		Robin Kanthe
 *	@email		kanthe.robin@gmail.com
 *	@version		1.0
 *	@since		2015-08-21
 *	@requires	style.css, createToken.php, logincheck.php, addUser.php
 */
	try {
		// Usets sessions id and password so that user has to login (again)
		// or add user when entering this page.
		if(isset($_SESSION['id'])) {
			unset($_SESSION['id']);
		}
		if(isset($_SESSION['password'])) {
			unset($_SESSION['password']);
		}
		// Starts a new session if there is none.
		if(session_status() != PHP_SESSION_ACTIVE) {
	 		session_start();
	 	}
		// Generates a crypted text string to use to ensure 
		// that data from the form below does not come from other servers
	 	include_once "src/php/createToken.php";
		$newToken = generateFormToken();
		
		// Checking if there is an error message to be printed and form input 
		// to be added from logincheck.php, when attempting to login user.
		// They are saved as session variables. If there is, it is assigned 
		// to a local variable and session variable is unset.
		$emailLogin = '';
		$errorLogin = 'Welcome to Kwitter! Please sign in or create a new user.';
		$attacError = '';
		
		if(isset($_SESSION['emailLogin'])) {
			$emailLogin = $_SESSION['emailLogin'];
			unset($_SESSION['emailLogin']);
		}
		if(isset($_SESSION['errorLogin'])) {
			$errorLogin = $_SESSION['errorLogin'];
			unset($_SESSION['errorLogin']);
		}
		// Such checks are done also for add user attempts.
		$emailAdd = '';
		$username = '';
		$fullname = '';
		$errorAdd = 'New to Kwitter? Register here.';
		
		if(isset($_SESSION['emailAdd'])) {
			$emailAdd = $_SESSION['emailAdd'];
			unset($_SESSION['emailAdd']);
		}
		if(isset($_SESSION['username'])) {
			$username = $_SESSION['username'];
			unset($_SESSION['username']);
		}
		if(isset($_SESSION['fullname'])) {
			$fullname = $_SESSION['fullname'];
			unset($_SESSION['fullname']);
		}
		if(isset($_SESSION['errorAdd'])) {
			$errorAdd = $_SESSION['errorAdd'];
			unset($_SESSION['errorAdd']);
		}
		if(isset($_SESSION['attacError'])) {
			$attacError = $_SESSION['attacError'];
			unset($_SESSION['attacError']);
		}
	}
	catch (Exception $e) {
		var_dump('Caught exception in login.php: '.$e->getMessage());
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
	<head>
		
		<!-- META DATA -->
		
		<meta http-equiv="Content-Type"	content="text/html; charset=utf-8" />
		
		<!-- CSS -->

		
		<link href="src/css/style.css" rel="stylesheet" type="text/css" media="screen" title="Default" />
		
		<!-- TITLE -->
		
		<title>1ME205 - Assignment three - Sign in</title>
		
	</head>
	
	<body>
	
		<div id="page-wrapper">
		
			<div id="page-header-wrapper">
			
				<div id="brand" class="brand_login">
					<h1>Kwitter</h1>
					<h4>Connect with friends</h4>
				</div>
				
			</div>
			
			<div id="page-content-wrapper">
		
			<!-- Form for signing in -->
			
			<h1>Sign in</h1>
			<hr>
			<form action="src/php/logincheck.php?method=login" method="POST">
				<p>Email: <input type="text" name="emailLogin" value="<?php echo $emailLogin; ?>"></p>
				<p>Password: <input type="password" name="password" ></p>
				<input type="hidden" name="token" value="<?php echo $newToken; ?>">
				<input type="submit" value="Sign in">
			</form>
			
			<!-- Printing error in sign in form -->
			
			<p class="error"><?php 
				echo $errorLogin;
				echo $attacError;
			?></p>
			<hr class="login_hr">
			
			<!-- Form for adding a new user -->
			
			<h2>New to Kwitter?</h2>
			<form action="src/php/addUser.php" method="POST">
				<p>Email: <input type="text" name="emailAdd" value="<?php echo $emailAdd; ?>"></p>
				<p>Username: <input type="text" name="username" value="<?php echo $username; ?>"></p>
				<p>Full name: <input type="text" name="fullname" value="<?php echo $fullname; ?>"></p>
				<p>Password: <input type="password" name="password"><br></p>
				<p>Confirm password: <input type="password" name="confirmPassword"><br></p>
				<input type="hidden" name="token" value="<?php echo $newToken; ?>">
				<input type="submit" value="Submit">
			</form>
			
			<!-- Printing error in add user form -->
			
			<p class="error"><?php 
				echo $errorAdd;
			?></p>
			<hr class="login_hr">
		</div>
	</body>
</html>