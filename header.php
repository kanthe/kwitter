<?php
/**
 * Top content of all pages, except login.php. 
 * Adds style sheet, js scripts and navigation menu. 
 * Onclick events directs user to the different pages.
 * Checking form inputs with logincheck.php and addUser.php
 * if user is logged correctly and if form inputs are done 
 * correctly.
 *
 *	@author		Robin Kanthe
 *	@email		kanthe.robin@gmail.com
 *	@version		1.0
 *	@since		2015-08-21
 * @requires	Pages: 			index.php, contacts.php, settings.php, logout.php,
 *					Style sheet:	style.css, 
 *					Javascript:		JSOOP.js, Ajax.js, Event.js, Main.js, 
 *					PHP script:		logincheck.php, createToken.php
 */
	try {
		// Starts a new session if there is none.
		if(session_status() != PHP_SESSION_ACTIVE) {
	 		session_start();
	 	}
	 	// Checking if user-id and password is valid
		$_GET['method'] = 'check';
		include_once "src/php/logincheck.php";
		
		// Generates a crypted text string to use to ensure 
		// that data from the form below does not come from other servers
	 	include_once "src/php/createToken.php";
		$newToken = generateFormToken();
	}
	catch (Exception $e) {
		var_dump('Caught exception in header.php: '.$e->getMessage());
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
	<head>
		
		<!-- META DATA -->
		
		<meta http-equiv="Content-Type"	content="text/html; charset=utf-8" />
		
		<!-- CSS  -->
		
		<link href="src/css/style.css" rel="stylesheet" type="text/css" media="screen" title="Default" />
		
		<!-- JAVASCRIPT -->
		
		<script type="text/javascript" language="javascript" src="src/js/utils/JsOOP.js"></script>
		<script type="text/javascript" language="javascript" src="src/js/net/Ajax.js"></script>
		<script type="text/javascript" language="javascript" src="src/js/events/Event.js"></script>
		<script type="text/javascript" language="javascript" src="src/js/Main.js"></script>
		
		<!-- TITLE -->
		
		<title>1ME205 - Assignment three</title>
		
	</head>
	
	<body>
		
		<div id="page-wrapper">
			
			<div id="page-header-wrapper">
			
				<!-- Top of pages. -->
				
				<div id="brand">
					<h1>Kwitter</h1>
					<h4>Connect with friends</h4>
				</div>
				
				<!-- Navigation menu -->				
				
				<div id="nav">
					<ul>
						<li onclick="window.location.href='index.php'"><h3>Kweets</h3></li>
						<li onclick="window.location.href='index.php?kweetList=myKweets'"><h3>My Kweets</h3></li>
						<li onclick="window.location.href='contacts.php'"><h3>Contacts</h3></li>
						<li onclick="window.location.href='settings.php'"><h3>Settings</h3></li>
					</ul>
					<button id="logout" type="button" onclick="window.location.href='src/php/logout.php'">Log out</button></li>
				</div>
				
			</div> <!-- page-header-wrapper -->
			
			<div id="page-content-wrapper">

