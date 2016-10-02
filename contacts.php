<?php
/**
 *	The "Contacts" page.
 * Adding header.php, which adds style sheet, js scripts,
 * navigation menu and checks if user is logged in and if
 * form inputs are done correctly.
 * Adding a list of contacts.
 * Adding buttons to choose which contacts to be displayed.
 *
 *	@author		Robin Kanthe
 *	@email		kanthe.robin@gmail.com
 *	@version		1.0
 *	@since		2015-08-21
 *	@requires	Contact.js, Contacts.js, header.php, kweetHandler.php
 */
	include_once "header.php"; // Including the header.php file
?>

<!-- JAVASCRIPT specific for contacts.php -->

<script type="text/javascript" language="javascript" src="src/js/Contact.js"></script>
<script type="text/javascript" language="javascript" src="src/js/Contacts.js"></script>

				<h2>Contacts</h2>
				<div>
					<ul>
						<h4><li id="allContacts" class="contactFilter">All contacts</li></h4>
						<h4><li id="following" class="contactFilter">Contacts you follow</li></h4>
						<h4><li id="followers" class="contactFilter">Followers</li></h4>
					</ul>
				</div>
				<hr>
				<div id="contactList">
					<!-- Contact objects are inserted here -->
				</div>
			</div> <!-- page-content-wrapper -->
		
		</div> <!-- page-wrapper -->
	
	</body>

</html>