<?php
/**
 *	The "settings" page.
 * Adding header.php, which adds style sheet, js scripts,
 * navigation menu and checks if user is logged in and if
 * form inputs are done correctly.
 *
 *	@author		Robin Kanthe
 *	@email		kanthe.robin@gmail.com
 *	@version		1.0
 *	@since		2015-08-21
 *	@requires	Settings.js, header.php, checkFileUpload.php
 */
	try {
		include_once "src/php/checkFileUpload.php"; // Script for checking file upload
		include_once "header.php"; // Including the header.php file
		
		// Checking if there is an error message to be printed, when attempting 
		// to upload image or update user details.
		// They are saved as session variables. If there is, it is assigned 
		// to a local variable and session variable is unset.
		$errorPic = '';
		$errorChange = '';
		
		if(isset($_SESSION['errorPic'])) {
			$errorPic = $_SESSION['errorPic'];
			unset($_SESSION['errorPic']);
		}
		if(isset($_SESSION['errorChange'])) {
			$errorChange = $_SESSION['errorChange'];
			unset($_SESSION['errorChange']);
		}
	}
	catch (Exception $e) {
		var_dump('Caught exception in settings.php: '.$e->getMessage());
	}
?>

<!-- JAVASCRIPT specific for settings.php -->

<script type="text/javascript" language="javascript" src="src/js/Settings.js"></script>
				
				<!-- Continuing after header.php -->
				
				<h2>Settings</h2>
				<hr>
				<h3>Upload profile picture</h3>
				
				<div id="changeProfilePicture">
				
				<!-- Place for form to upload profile picture, mostly written in Settings.js -->
				
				</div>
				
				<?php 
					// Checking if user is sending a request to upload a file from 
					//	the file upload form. Then checking if it is valid. 
					checkFileUpload('imgFile');
				?>
				
				<!-- Printing error in file upload -->
				
				<p class="error"><?php echo $errorPic; ?></p>
				<hr>
				<h3>Change user details</h3>
				
				<div id="changeUserDetails">
				
					<!-- Form to change the user details, written in Settings.js, is put here -->
					
				</div>
				
				<!-- Printing error in changing user details -->
				
				<p class="error"><?php echo $errorChange; ?></p>
				<hr class="settings_hr">
					
			</div> <!-- page-content-wrapper -->
		
		</div> <!-- page-wrapper -->
	
	</body>

</html>