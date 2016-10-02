<?php
/**
 *	The "Kweets" or "MyKweets" page.
 * Adding header.php, which adds style sheet, js scripts,
 * navigation menu and checks if user is logged in and if
 * form inputs are done correctly.
 * Adding a form for making Kweets.
 * Adding a list of Kweets.
 *
 *	@author		Robin Kanthe
 *	@email		kanthe.robin@gmail.com
 *	@version		1.0
 *	@since		2015-08-21
 *	@requires	Kweet.js, Home.js, header.php, kweetHandler.php
 */
	try {
		include_once "header.php"; // Including the header.php file
		
		// Checking if there is an error message to be printed and kweet input 
		// to be added, when attempting to write a new kweet (message).
		// They are saved as session variables. If there is, it is assigned 
		// to a local variable and session variable is unset.
		$kweet = '';
		$errorIndex = '';
		
		if(isset($_SESSION['kweet'])) {
			$kweet = $_SESSION['kweet'];
			unset($_SESSION['kweet']);
		}
		if(isset($_SESSION['errorIndex'])) {
			$errorIndex = $_SESSION['errorIndex'];
			unset($_SESSION['errorIndex']);
		}
		//
		// Determine if the full name of user is to be added as headline.
		// That happens if MyKweets are clicked in the navigation menu.
		// In that case only users Kweets are put in "kweetList" below.
		// Also users fullname is picked with "getUserDetail.php"
		// and put in a <h2> tag.
		//
		if(isset($_GET['kweetList']) && $_GET['kweetList'] == 'myKweets') {
			echo "<h2>";
			$_GET['method'] = 'fullname';
			include_once "src/php/getUserDetail.php";
			echo "</h2><hr>";
			$_SESSION['kweetList'] = 'myKweets';
			unset($_GET['method']);
			unset($_GET['kweetList']);
		}
		else {
			$_SESSION['kweetList'] = 'allKweets';
			unset($_GET['kweetList']);
		}
	}
	catch (Exception $e) {
		var_dump('Caught exception in index.php: '.$e->getMessage());
	}
?>

<!-- JAVASCRIPTS specific for index.php -->

<script type="text/javascript" language="javascript" src="src/js/Kweet.js"></script>
<script type="text/javascript" language="javascript" src="src/js/Home.js"></script>

				<!-- Continuing after header.php -->
				<h2 class='myName'></h2> <!-- Displays full name here if My Kweets link is clicked. -->
				
				<div id="newKweet">
					
					<h3>New Kweet</h3>
					<h5>Max 140 characters is allowed</h5>
					
					<!-- Form for posting new kweet (message) -->
					
					<form action="src/php/kweetHandler.php?method=new" method="POST">
						<p><textarea name="kweet"><?php ECHO $kweet ?></textarea></p>
						<input type="hidden" name="token" value="<?php echo $newToken; ?>">
						<p><input type="submit" value="Post"></p>
					</form>
					
					<h4 class='error'><?php 
						echo $errorIndex; 
					?></h4>
					<hr>
					
				</div>
				
				<div id="kweetList">
				
					<!-- List written in Home.js is put here -->
					
				</div>
			
			</div> <!-- page-content-wrapper -->
		
		</div> <!-- page-wrapper -->
	
	</body>

</html>