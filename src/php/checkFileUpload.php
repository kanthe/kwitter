<?php
 /**
 *	Triggered when doing a images file upload.
 * Transferring the file to the "img" directory.
 * Checking if upload succeeded and that it is a
 * image file with size less than 1 MB.
 *
 *	@author		Robin Kanthe
 *	@email		kanthe.robin@gmail.com
 *	@version		1.0
 *	@since		2015-08-21
 *	@requires	login.php, settings.php
 */
	try {
		function checkFileUpload($formName) {
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
				mysql_close($connection);
				header("Location: ../../login.php");
				exit;
			}
			// If a file is uploaded
			else if(isset($_FILES[$formName])) {
				
				if(substr($_FILES[$formName]['type'], 0, 5) != "image") { 
					$_SESSION['errorPic'] = "The file is not an image. Please try again.";
					header("Location: settings.php");
					exit;
				}
				if($_FILES[$formName]['size'] > 1000000) { 
					$_SESSION['errorPic'] = "Image is too large. Maximum file size: 1 megabyte. Please try again.";
					header("Location: settings.php");
					exit;
				}
				// Filename of picture will be the same as of the uploaded file
				$filename = $_FILES[$formName]['tmp_name'];
				// Moving the uploaded file from the temp directory to the img directory
				$destination = 'src/img/' . $_FILES[$formName]['name'];
				$success = move_uploaded_file($filename, $destination);
				// If file transfer succeeds, file name is saved in the database
				if($success) {
					$query = "UPDATE userdetails 
						SET picture='" . $_FILES[$formName]['name'] . "' 
						WHERE user_id='" . $_SESSION['id'] . "'"; 
					$result = mysql_query($query);
					$_SESSION['errorPic'] = "The file was succesfully uploaded.";
				}
				// Else an error message is printed
				else {
					$_SESSION['errorPic'] = "Failed uploading file.";
				}
				mysql_close($connection);
			}
		}
	}
	catch (Exception $e) {
		var_dump('Caught exception in checkFileUpload.php: '.$e->getMessage());
	}
?>