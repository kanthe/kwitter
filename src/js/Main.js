 /**
 *	Creates an Ajax object.
 * Added to all pages, except login.php
 *
 *	@author		Robin Kanthe
 *	@email		kanthe.robin@gmail.com
 *	@version		1.0
 *	@since		2015-08-21
 *	@requires	Ajax.js
 */

var Main = {
	
	/*
 	  ***************************
	  * Public property			 *
	  ***************************
	*/
	ajax : null,
	// --------------------------------------------------------
	// Main class constructor. Creating an Ajax object.
	// --------------------------------------------------------
	//
	init : function() {
		try {
			Main.ajax = new Ajax(); // Creating AJAX object
		}
		catch (e) {
			console.log('Error in Main.js function init: ' + e.message);
		}
	},
	doNothing : function (data) {
		
	}
}

/*
 	********************************************
	* Starts the Main class when website loads *
	********************************************
*/
Event.addEventListener(window, Event.LOAD, Main.init);
