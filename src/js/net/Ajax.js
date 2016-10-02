/**
 *	Dynamic class to handle AJAX-based data transmission.
 *
 *	@author		Henrik Andersen additional comments added by Robin Kanthe 2015-04-02
 *	@email		henrik.andersen@lnu.se
 *	@version	1.0
 *	@since		xxxx-xx-xx
 *	@requires	
 */
function Ajax() {  

	//-------------------------------------------------------------
	//  Private constants
	//-------------------------------------------------------------
	
	/**
	 *	Constant for undefined XMLHttpRequest objects, meant to be 
	 *	used internally in the class.
	 */
	var UNDEFINED = 'undefined';
	
	/**
	 *	Constant identifying GET-methods.
	 */
	var GET = 'GET';
	
	/**
	 *	Constant identifying POST-methods.
	 */
	var POST = 'POST';
	
	//-------------------------------------------------------------
	//  Public methods
	//-------------------------------------------------------------
	
	/**
	 *	Private method that creates a XMLhttpRequest object used 
	 *	for data transmission.
	 *
	 *	@return	Object
	 */
	this.getHTTPObject = function() {
		// Try different request syntax, used on different browsers.
		if (typeof XMLHttpRequest == UNDEFINED){
			XMLHttpRequest = function(){
			
				try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); }
				catch (error) {}
				try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); }
				catch (error) {}
				try { return new ActiveXObject("Msxml2.XMLHTTP"); }
				catch (error) {}
				
				return false;
			}
		}
		// Returns the request object.
		return new XMLHttpRequest();
	}
	
	/**
	 *	Method to retrieve data asynchronously using JavaScript (AJAX).
	 *
	 *	@param	URL			The URL to the data source.
	 *	@param	callback	The callback method that is activated when 
	 *						the download is complete.
	 *
	 *	@return undefined
	 */
	this.get = function(URL, callback, contentType) {
		contentType = contentType || "application/x-www-form-urlencoded; charset=UTF-8";
		// Creates an XMLHttpRequest object
		var request = this.getHTTPObject();
			// Uses the method "open" to get data from a web page/file "URL" asynchroically "true"
			request.open(GET, URL, true);
			// If xml file: Set header for xml content type
			request.setRequestHeader("Content-type", contentType);
			if (contentType === 'xml')
				request.overrideMimeType('text/'+contentType);
			
			request.onreadystatechange = function(){
				if (request.readyState == request.DONE){
					// Calls back the object asynchronously
					callback(request);
					request = null;
				}
			}
			// Sends nothing
			request.send();
	}
	
	/**
	 *	Method to post data asynchronously using JavaScript (AJAX).
	 *
	 *	@param	URL			The URL to the data source.
	 *	@param	parameters	The information that will be sent via the POST-call.
	 *	@param	callback	The callback method that is activated when 
	 *						the transmission is complete.
	 *
	 *	@return undefined
	 */
	this.post = function(URL, parameters, callback) {
		// Creates an XMLHttpRequest object
		var request = this.getHTTPObject();
			// Uses the method "open" to post (send) data to a web page/file "URL" asynchroically "true"
			request.open(POST, URL, true);
			request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			request.setRequestHeader("Content-length", parameters.length);
			request.setRequestHeader("Connection", "close");
			
			request.onreadystatechange = function(){
				if (request.readyState == request.DONE){
					// Calls back the object asynchronously
					callback(request);
					request = null;
				}
			}
			// Sends parameters to the server
			request.send(parameters);
	}
}