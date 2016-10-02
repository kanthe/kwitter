/**
 *	Simple static class to manage event handlers. The class provides 
 *	a common interface (API) to manage event listeners through 
 *	various platforms (web browser). The class uses the W3C's event 
 *	handler with some modifications to support Internet Explorer and 
 *	older browsers. The purpose is to provide a uniform API for all 
 *	browsers.
 *
 *	This class is not dependent on other external javascript objects 
 *	or classes, which means it can be used in many JavaScript-based 
 *	projects.
 *
 *	The class was updated 2013-05-03 to support the new HTML5 
 *	events.
 *
 *	@author		Henrik Andersen <henrik.andersen@lnu.se>
 *	@copyright	Copyright (c) 2013.
 *	@license	Creative Commons (BY-NC-SA)
 *	@version	1.25
 *	@since		2008-03-13 (2013-05-03)
 *
 */
var Event = {

	//----------------------------------------------------------------
    //  Public static constants
    //----------------------------------------------------------------
    
	/**
	 *	When an element loses the focus.
	 *
	 *	@default "blur"
	 */
	BLUR : "blur",
	
	/**
	 *	When a form field value changes.
	 *
	 *	@default "change"
	 */
	CHANGE : "change",
	
	/**
	 *	When a mousedown and mouseup event occur on the same element 
	 *	OR an element is activated by the keyboard. All browsers but Safari 
	 *	have issues in preventing the default on form fields. Browsers judged 
	 *	Almost have two issues, the others (except Safari) one.
	 *
	 *	@default "click"
	 */
	CLICK : "click",
	
	/**
	 *	When the user right-clicks to get the context menu. Preventing 
	 *	the default (i.e. preventing the context menu from appearing) is 
	 *	the whole point of this event.
	 *
	 *	@default "contextmenu"
	 */
	CONTEXTMENU : "contextmenu",
	
	/**
	 *	When text is copied.
	 *
	 *	@default "copy"
	 */
	COPY : "copy",
	
	/**
	 *	When text is cut.
	 *
	 *	@default "cut"
	 */
	CUT : "cut",
	
	/**
	 *	When two click events take place on the same element within 
	 *	a reasonable timeframe.
	 *
	 *	@default "dblclick"
	 */
	DOUBLE_CLICK : "dblclick",
	
	/**
	 *	When the browser encounters a JavaScript error or a missing 
	 *	asset file.
	 *
	 *	@default "dblclick"
	 */
	ERROR : "error",
	
	/**
	 *	When an element receives the focus. Firefox Mac, Safari, and 
	 *	Chrome sometimes don’t support these events on links and/or 
	 *	form fields. See detail page for bug descriptions.
	 *
	 *	@default "focus"
	 */
	FOCUS : "focus",
	
	/**
	 *	As focus, but bubbles.
	 *
	 *	@default "focusin"
	 */
	FOCUS_IN : "focusin",
	
	/**
	 *	As blur, but bubbles.
	 *
	 *	@default "focusout"
	 */
	FOCUS_OUT : "focusout",
	
	/**
	 *	When the hash value of the location changes. This event is 
	 *	interesting for monitoring Back/Forward in Ajax interfaces.
	 *
	 *	@default "hashchange"
	 */
	HASH_CHANGE : "hashchange",
	
	/**
	 *	When the user depresses a key.
	 *
	 *	@default "keydown"
	 */
	KEY_DOWN : "keydown",
	
	/**
	 *	When a keystroke leads to a character being added to an 
	 *	HTML element.
	 *
	 *	This event should fire only if a keystroke leads to a 
	 *	character actually being added to an HTML element such as 
	 *	a text input. It should not fire when the user presses keys 
	 *	like the arrow keys which do not result in a character. The 
	 *	event should continue firing as long as the user keeps the 
	 *	key depressed.
	 *
	 *	@default "keypress"
	 */
	KEY_PRESS : "keypress",
	
	/**
	 *	When the user releases a key.
	 *
	 *	This event fires after the keystroke has been processed; for 
	 *	instance after a character has been added to a text input. 
	 *	It’s not possible to cancel the default action because that 
	 *	action has already taken place. This event should not repeat.
	 *
	 *	@default "keyup"
	 */
	KEY_UP : "keyup",
	
	/**
	 *	When an asset (HTML page, image, CSS or JS file) is loaded. 
	 *	Older IEs, Firefox, Safari, and Chrome do not fire a load 
	 *	event when a new style sheet is loaded.
	 *
	 *	@default "load"
	 */
	LOAD : "load",
	
	/**
	 *	When the user depresses a mouse button.
	 *
	 *	@default "mousedown"
	 */
	MOUSE_DOWN : "mousedown",
	
	/**
	 *	When the mouse enters an element, but doesn’t bubble.
	 *
	 *	@default "mouseenter"
	 */
	MOUSE_ENTER : "mouseenter",
	
	/**
	 *	When the mouse leaves an element, but doesn’t bubble.
	 *
	 *	@default "mouseleave"
	 */
	MOUSE_LEAVE : "mouseleave",
	
	/**
	 *	When the mouse leaves an element, but doesn’t bubble.
	 *
	 *	@default "mousemove"
	 */
	MOUSE_MOVE : "mousemove",
	
	/**
	 *	When the mouse leaves an element.
	 *
	 *	@default "mouseout"
	 */
	MOUSE_OUT : "mouseout",
	
	/**
	 *	When the mouse enters an element.
	 *
	 *	@default "mouseover"
	 */
	MOUSE_OVER : "mouseover",
	
	/**
	 *	When the user releases a mouse button.
	 *
	 *	@default "mouseup"
	 */
	MOUSE_UP : "mouseup",
	
	/**
	 *	When the user uses the mousewheel. Note that the page 
	 *	does not have to scroll for the event to fire.
	 *
	 *	@default "mousewheel"
	 */
	MOUSE_WHEEL : "mousewheel",
	
	/**
	 *	When text is pasted.
	 *
	 *	@default "paste"
	 */
	PASTE : "paste",
	
	/**
	 *	When the user resets a form.
	 *
	 *	@default "reset"
	 */
	RESET : "reset",
	
	/**
	 *	When the window is resized.
	 *
	 *	@default "resize"
	 */
	RESIZE : "resize",
	
	/**
	 *	When something is scrolled.
	 *
	 *	@default "scroll"
	 */
	SCROLL : "scroll",
	
	/**
	 *	When the user selects text.
	 *
	 *	@default "select"
	 */
	SELECT : "select",
	
	/**
	 *	When the user submits a form.
	 *
	 *	@default "submit"
	 */
	SUBMIT : "submit",
	
	/**
	 *	When a character is actually added to a control. (So it 
	 *	does not fire when the adding is prevented onkeydown or 
	 *	onkeypress.) 
	 *
	 *	@default "textinput"
	 */
	TEXT_INPUT : "textinput",
	
	/**
	 *	When the user navigates away from the page.
	 *
	 *	@default "unload"
	 */
	UNLOAD : "unload",
	
	/**
	 *	Equivalent of mousewheel. Works only with IE9+.
	 *
	 *	@default "wheel"
	 */
	WHEEL : "wheel",

    //----------------------------------------------------------------
    //  Public static methods
    //----------------------------------------------------------------
    
    /**
     *	Adds an event listener on an object.
     *
     *	@param	element		The element that the listener should be applied to.
     *	@param	type		What kind of event handlers that should be applied (mouseup, click, etc..).
     *	@param	callback	The method / function that is activated when the event occurs.
     *	@param	useCapture	Determines whether the event should be captured or not.
     *
     *	@return undefined
     */
    addEventListener: function(element, type, callback, useCapture) {
        
        if (useCapture == undefined)
        	useCapture  = false;
       
        if (element.addEventListener) {
            element.addEventListener(type, callback, useCapture);
        } else if (element.attachEvent){
            element.attachEvent("on" + type, callback);
        } else {
            element["on" + type] = callback;
        }
    },
    
    /**
     *	Removes an event listener from an object.
     *
     *	@param	element		The element that the listener should be applied to.
     *	@param	type		What kind of event handlers that should be applied (mouseup, click, etc..).
     *	@param	callback	The method / function that is activated when the event occurs.
     *	@param	useCapture	Determines whether the event should be captured or not.
     *
     *	@return undefined
     */
    removeEventListener: function(element, type, callback, useCapture) {
        
        if (useCapture == undefined)
        	useCapture  = false;
        
        if (element.removeEventListener) {
            element.removeEventListener(type, callback, useCapture);
        } else if (element.detachEvent) {
            element.detachEvent("on" + type, callback);
        } else {
            element["on" + type] = null;
        }
    },
    
    /**
     *	Stops event "bubbling" and default behaviour of the browser.
     *
     *	@param	event	The event to be prevented from bubbling.
     *
     *	@return undefined
     */
    stopEvent : function(event) {
    
    	if (event.stopPropagation) {
    		event.stopPropagation();
    		event.preventDefault();
    	}
    	else {
    	  // INTERNET EXPLORER FIXES
    	  event.cancelBubble = true;
    	  event.returnValue	 = false;
    	}
    },
    
    /**
     *	Safe way to get the event target of the event.
     *
     *	@param	event	The event whose origin is unknown.
     *
     *	@return undefined
     */
    getEventTarget : function(event) {
    
      if (event.target) return event.target;
      else if (event.srcElement) return event.srcElement;
      else return null;
    }
};