/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   accessibletemplate Terms & Conditions - http://www.accessibletemplate.com/en/terms-and-conditions
 **/
(function(){

/*--------- Yes, use strict mode! ---------*/
'use strict';

/*==========================================================================
   STARTING-UP
==========================================================================*/

//Resolve possible conflicts (i.e. mootools)
jQuery.noConflict();

// Add a "js" class
jQuery("html").addClass("js");

/*==========================================================================
   COMMON VARIABLES
==========================================================================*/

var currentFontSize; //the current font size set by the user
var fontSizeDifference = 10; //font scaling rate in percentage. e.g. if set to 10, after biggerFontSize(true) 100% -> 110%
var cookieDaysExpire = 30; //set the number of days for the cookie to be expired
var tooltipPositionX; // coordinates for tooltips
var tooltipPositionY;
var graphicMode = "default"; //Default graphic mode is "default"
var isPlanelClosing = false; //checking variable
var userCustomLayoutWidth = 'auto'; //Custom layout width set by the user

//jQuery repeated elements:
var $body = jQuery('body');

/*==========================================================================
   HELPERS
==========================================================================*/

/**
 * CSS3 TRANSITION IN THE WHOLE BODY
**/
//Use CSS3 effects to animate ANY change on the document
function activateBodyCSS3Transition(){
	$body
		.addClass('css3-transition')
		.delay(400)
		.queue(function(){
			jQuery(this)
				.removeClass('css3-transition')
				.dequeue();
			});
	}

/*==========================================================================
   HANDLERS
==========================================================================*/

/*----------------------------------------------------------------
-  LAYOUT WIDTH HANDLER
---------------------------------------------------------------- */
//Toggles full/fixed/liquid layout width
var layoutWidthRailElementStyle;
function setLayoutWidthMode(layoutwidthmode){
	
	//If the user had previously set a custom layout width AND the new layout will NOT be a custom one, then remove the custom status
	if(userCustomLayoutWidth!=='auto' && layoutwidthmode!=='custom'){
		//Remove any "inline width" declaration from any "layout-width-rail" (for example style="width:1200px")
		jQuery('.layout-width-rail').each(function(){
			layoutWidthRailElementStyle = jQuery(this).attr('style');
			layoutWidthRailElementStyle = layoutWidthRailElementStyle.replace(new RegExp('width:(.....|....|...|..|.)(px|em|%);?','i'),'');
			jQuery(this).attr('style',layoutWidthRailElementStyle);
			userCustomLayoutWidth='auto';
			});
		}
	
	//Change the class for the layout width (only in default-layout)
	if($body.hasClass('default-layout')){
		$body
			.removeClass("full-layout-width")
			.removeClass("fixed-layout-width")
			.removeClass("liquid-layout-width")
			.removeClass("custom-layout-width")
			.addClass(layoutwidthmode+"-layout-width");
		}
	//Keep "full-width" for the other layouts
	else{
		$body
			.removeClass("full-layout-width")
			.removeClass("fixed-layout-width")
			.removeClass("liquid-layout-width")
			.removeClass("custom-layout-width")
			.addClass("full-layout-width");
		}
	zhongFramework.defaultLayoutWidthMode=layoutwidthmode;
	}

/*----------------------------------------------------------------
-  ACCESSIBILITY PANEL HANDLERS
---------------------------------------------------------------- */
//Hides the accessibility panel
function hideAccessibilityPanel(){
	if(isPlanelClosing){ return; }
	isPlanelClosing = true;
	jQuery('#show-accessibility-panel-button')
		.attr("title", zhongFramework.accessPanelTitle_closed)
		.find('.top-bar-tool-text')
			.text(zhongFramework.accessPanelText_closed);
	$body
		.removeClass("accessibility-panel-visible")
		.addClass("accessibility-panel-hidden");
	jQuery('#accessibility-panel')
		.slideUp("slow")
		.queue(function(){
			isPlanelClosing = false;
			jQuery(this).addClass('removed');
			jQuery(this).dequeue();
			});
	}

//Shows/hides the accessibility panel
function toggleAccessibilityPanelVisibility(){
	if(isPlanelClosing){ return; }
	if($body.hasClass('accessibility-panel-hidden')){
		isPlanelClosing = true;
		jQuery('#show-accessibility-panel-button')
			.attr("title", zhongFramework.accessPanelTitle_open)
			.find('.top-bar-tool-text')
				.text(zhongFramework.accessPanelText_open);
		$body
			.removeClass("accessibility-panel-hidden")
			.addClass("accessibility-panel-visible");
		jQuery('#accessibility-panel')
			.removeClass('removed')
			.slideDown("slow")
			.queue(function(){
				isPlanelClosing = false;
				jQuery(this).dequeue();
				});
		}
	else{ hideAccessibilityPanel(); }
	}

/*----------------------------------------------------------------
-  FONT SIZE HANDLERS
---------------------------------------------------------------- */
//Updates var currentFontSize; see var sizeDifference for changing the rate in percentage
function biggerFontSize(bigger){
	if(bigger){
		currentFontSize = currentFontSize + fontSizeDifference;
		if(currentFontSize > 400){currentFontSize = 400;} // doesn't allow to go over 280% font-size
		}
	else{
		currentFontSize = currentFontSize - fontSizeDifference;
		if(currentFontSize < 40){currentFontSize = 40;} // doesn't allow to go under 40% font-size
		}
	setFontSize(currentFontSize);
	}

//Reverts var currentFontSize to default.
function revertFontSize(){
	currentFontSize = zhongFramework.defaultFontSize;
	setFontSize(zhongFramework.defaultFontSize);
	}

//Sets the new font-size property for the  whole document
function setFontSize(newFontSize){ $body.css('font-size',newFontSize+'%');	}

/*----------------------------------------------------------------
-  USER PREFERENCES HANDLERS
---------------------------------------------------------------- */
//This function is called on window.unload; saves the user preferences in a cookie
function saveUserSettings(){
	var date = new Date();
	date.setTime(date.getTime()+(cookieDaysExpire*24*60*60*1000)); //to change the expiration date, see var:cookieDaysExpire
	var expires = "; expires="+date.toGMTString();
	//the data (user settings) is store in this format: "data1|data2|data3..."
	var cookieData = currentFontSize+'|'+zhongFramework.defaultLayoutWidthMode+'|'+graphicMode+'|'+userCustomLayoutWidth;
	document.cookie = "zhongFrameworkUserSettingsJS="+cookieData+expires+"; path=/"; //create or update the cookie
	}

// This function is called on window.load; gets the user preferences
function getUserSettings(){
	//Read cookie
	var userDataRaw = readCookie("zhongFrameworkUserSettingsJS");
	
	//If the cookie hasn't been created yet, set the default values ( see backend template options )
	if(userDataRaw===null){
		revertFontSize();
		setLayoutWidthMode(zhongFramework.defaultLayoutWidthMode);
		return;
		}
	
	//If the cookie is present, set the user's preferences
	if(userDataRaw!==null){
		var userData = userDataRaw.split('|');
		setUserSettings(userData[0],userData[1],userData[2],userData[3]);
		}
	}

// Read settings on user cookie; returns a string in the format "data1|data2|data3..."
function readCookie(cookie_name){
	var ca = document.cookie.split(';');
	var cookie_name_equals = cookie_name+"=";
	var c;
	for(var i=0;i < ca.length;i++) {
		c = ca[i];
		while (c.charAt(0)===' '){ c = c.substring(1,c.length); } //remove initial spaces
		if (c.indexOf(cookie_name_equals) === 0) return c.substring(cookie_name_equals.length,c.length);
		}
	return null;
	}

//Sets the user's Settings
function setUserSettings(fontSize_cookie,layoutWidthMode_cookie,graphicMode_cookie,customLayoutWidth_cookie){
	
	//If full access or high contrast, keep the default font size, then exit
	if($body.hasClass('full-access') || $body.hasClass('high-contrast')){
		setFontSize(zhongFramework.defaultFontSize);
		return;
		}
	
	//Check for undefined values
	if(typeof fontSize_cookie == 'undefined') {fontSize_cookie=zhongFramework.defaultFontSize;}
	if(typeof layoutWidthMode_cookie == 'undefined') {layoutWidthMode_cookie=zhongFramework.defaultLayoutWidthMode;}
	if(typeof graphicMode_cookie == 'undefined') {graphicMode_cookie='default';}
	if(typeof customLayoutWidth_cookie == 'undefined') {customLayoutWidth_cookie=zhongFramework.defaultLayoutWidthMode;}

	//If default layout or mobile then set CUSTOM FONT SIZE
	currentFontSize=zhongFramework.defaultFontSize;
	if(parseFloat(fontSize_cookie.replace(",", "."))){
		currentFontSize=parseFloat(fontSize_cookie.replace(",", "."));
		}
	setFontSize(currentFontSize);
	
	//Set the CUSTOM LAYOUT WIDTH (if previously set)
	if(customLayoutWidth_cookie!=='auto' && $body.hasClass('default-layout') && parseFloat(customLayoutWidth_cookie.replace(",",".").replace('px',''))){
		//Set the layout mode in "full-width" (default value if a custom layout width is set)
		$body.addClass('custom-layout-width')
			.removeClass('full-layout-width').removeClass('liquid-layout-width').removeClass('fixed-layout-width');
		jQuery('.layout-width-rail').css('width',customLayoutWidth_cookie);
		userCustomLayoutWidth=customLayoutWidth_cookie;		
	   	layoutWidthMode_cookie='custom';
	   	setLayoutWidthMode(layoutWidthMode_cookie);
		}
	//If the user didn't set a custom LAYOUT WIDTH, set the standard value
	else{
		//Check the value of the cookie & set the layout width mode
		if(layoutWidthMode_cookie!=='full' && layoutWidthMode_cookie!=='fixed' && layoutWidthMode_cookie!=='fluid'){
			layoutWidthMode_cookie=zhongFramework.defaultLayoutWidthMode;
			}
		setLayoutWidthMode(layoutWidthMode_cookie);
		}
	
	//Set the NIGHT MODE (if best legibility has not been set & not in mobile mode)
	if(graphicMode_cookie==="night" && !$body.hasClass('best-legibility')){
		graphicMode = "night";
		$body
			.removeClass('default-graphic-mode')
			.addClass('night-mode');
		jQuery('#night-mode-switcher')
			.attr('title',zhongFramework.dayModeLinkTitle)
			.find('.top-bar-tool-text').text(zhongFramework.dayModeLinkText)
			.parent().find('.zhongframework-icon-night-mode').attr('class','zhongframework-icon zhongframework-icon-day-mode');
		}
	
	}

/*----------------------------------------------------------------
-  NIGHT MODE TRANSITION HANDLER
---------------------------------------------------------------- */
//This script handles the transition for night mode
function nightModeTransitionHandler(){
	jQuery('#night-mode-switcher').click(function(e){
		e.preventDefault();
		activateBodyCSS3Transition(); //Make a CSS3 animation
		if($body.hasClass('night-mode')){
			jQuery('#night-mode-switcher')
				.attr('title',zhongFramework.nightModeLinkTitle)
				.find('.top-bar-tool-text').text(zhongFramework.nightModeLinkText)
				.parent().find('.zhongframework-icon-day-mode').attr('class','zhongframework-icon zhongframework-icon-night-mode');
			$body
				.addClass('default-graphic-mode')
				.removeClass('night-mode');
			graphicMode='default';
			}
		else{
			jQuery('#night-mode-switcher')
				.attr('title',zhongFramework.dayModeLinkTitle)
				.find('.top-bar-tool-text').text(zhongFramework.dayModeLinkText)
				.parent().find('.zhongframework-icon-night-mode').attr('class','zhongframework-icon zhongframework-icon-day-mode');
			$body
				.addClass('night-mode')
				.removeClass('default-graphic-mode');
			graphicMode='night';
			}
		$body.delay(400)
			.queue(function(){
				jQuery(this)
					.removeClass('css3-transition')
					.dequeue();
				});
		//Saves the cookie
		saveUserSettings();
		});
	}

/*----------------------------------------------------------------
-  DEFAULT LAYOUT MODIFICATIONS
---------------------------------------------------------------- */
//This function modifies some native classes printed by the CMS.
//It doesn't effect any content, it's intention is purely graphic 
function defaultLayoutModifications(){
	
	/**
	 * MODs in Joomla3
	**/
	if($body.hasClass('parent-cms-Joomla3')){
		//Just remove the "primary button" class and make them "simple buttons"
		jQuery('#search-module-outer button')
			.removeClass('btn-primary').removeClass('btn').removeClass('button');
		jQuery('#login-module button')
			.removeClass('btn-primary').removeClass('btn').removeClass('button');
		}
	
	}

/*----------------------------------------------------------------
-  SCROLL TO TOP ANIMATION
---------------------------------------------------------------- */
function scrollToTopAnimationHandler(){
	jQuery('#goto-top-block #goto-top').click(function(e){
		e.preventDefault();
		jQuery('body,html').animate({scrollTop:1},600);
		});	
	}

/*----------------------------------------------------------------
-  USER MODULES - SAME HEIGHT 
---------------------------------------------------------------- */
function sameHeightCustomModulesHandler(){
	//Check if the option was set from the backend
	if(zhongFramework.sameHeightCustomModules){
		
		//For each "row" of custom modules
		jQuery('.custom-modules-container').each(function(){
			
			//Get all the custom modules in the row
			var customModulesInTheRow = jQuery(this).find('.custom-module-inner');
			
			//If the module is the only one in the row, skip it
			if(customModulesInTheRow.length===1){return;}
			
			//Get the max height among these custom modules
			var maxHeightCustomModule = customModulesInTheRow.eq(0).outerHeight();
			for(var i=1;i<customModulesInTheRow.length;i++){ //(NOTE: the counter starts from 1)
				
				if(maxHeightCustomModule < customModulesInTheRow.eq(i).outerHeight()){
					maxHeightCustomModule = customModulesInTheRow.eq(i).outerHeight();
					}

				}
			
			//Set the min-height or each module
			customModulesInTheRow.css('min-height',maxHeightCustomModule+'px');
			//Set the min height also to nested modules
			customModulesInTheRow.find('[class*=custom-module-style_]').css('min-height',maxHeightCustomModule+'px');
			//But reset it from double nested modules
			customModulesInTheRow.find('[class*=custom-module-style_] [class*=custom-module-style_]').css('min-height','0');
			
			});
		
		}
	}

/*----------------------------------------------------------------
-  LAYOUT WIDTH RESIZE HANDLER
---------------------------------------------------------------- */
//Custom layout width decided by the user
function userLayoutWidthResizeHandler(){

	//If IE7, don't add this feature
	if(jQuery('html').hasClass('ie7')){jQuery('#layout-width-resize-tool-container').remove();return;}
	
	//If portable (tablet or mobile), don't add this feature
	if(zhongFramework.isPortable){jQuery('#layout-width-resize-tool-container').remove();return;}
	
	//If IE8, add support for opacity
	if(jQuery('html').hasClass('ie8')){
		jQuery('.layout-width-resize-handle').css('opacity','0');
		jQuery('.layout-width-resize-handle').hover(
			function(){jQuery(this).css('opacity','0.7');},
			function(){jQuery(this).css('opacity','0');});
		}
	
	//Initialize common variables
	var currentLayoutWidth, currentPointerPosition, bodyStyleAttribute, resizeHandleDirection, customLayoutWidthPx, layoutWidthRailElements;
	var disableSelectionByCSS = '-moz-user-select:none;-webkit-user-select:none;-ms-user-select:none;user-select:none;';
	
	jQuery('.layout-width-resize-trigger')
		//When one of the two handles are clicked, start the resize process:
		.mousedown(function(e){

			//Get the "rail" elements
			layoutWidthRailElements = jQuery('.layout-width-rail');
			//Get the position of the cursor
			currentPointerPosition=e.pageX;
			//Get the current layout width
			currentLayoutWidth=layoutWidthRailElements.eq(0).width();
			
			//Set the custom-layout-width class
			$body
				.addClass('custom-layout-width')
				.removeClass('full-layout-width').removeClass('liquid-layout-width').removeClass('fixed-layout-width');
			
			//Set an inline width for all the "layout width rail" elements
			layoutWidthRailElements.css('width',currentLayoutWidth+'px');
				
			//Is the left or right handle pressed? if left then it is a positive addition. If right a negative one.
			if(jQuery(this).is('#layout-width-resize-handle_left') || jQuery(this).is('#layout-width-resize-icon'))
				{resizeHandleDirection=1;}
			else
				{resizeHandleDirection=-1;}
			
			//Disable selection while in "mousedown"
			$body.attr('unselectable','on').attr('onselectstart','return false;').attr('onmousedown','return false;');
			bodyStyleAttribute = document.getElementsByTagName('body')[0].getAttribute('style');
			document.getElementsByTagName('body')[0].setAttribute('style',bodyStyleAttribute+disableSelectionByCSS);
			
			//While the mouse is moving, get the event and resize the layout:
			$body
				.bind("mousemove",function(e){
					//Resize the layout!
					customLayoutWidthPx = currentLayoutWidth+((currentPointerPosition-e.pageX)*2*resizeHandleDirection);
					layoutWidthRailElements.css('width',customLayoutWidthPx);
					})
				//When the user releases the click, 
				.mouseup(function(){
					
					//unbind the mousemove/mouseup event
					$body.unbind("mousemove").unbind("mouseup");
					
					//Set a new value to the cookie variable
					userCustomLayoutWidth=jQuery('.layout-width-rail').css('width');
					
					//Get the selection back to normal
					bodyStyleAttribute = document.getElementsByTagName('body')[0].getAttribute('style');
					bodyStyleAttribute = bodyStyleAttribute.replace(disableSelectionByCSS,'');
					document.getElementsByTagName('body')[0].setAttribute('style',bodyStyleAttribute);
					$body.removeAttr('unselectable').removeAttr('onselectstart').removeAttr('onmousedown');

					});
			});
	}

/*==========================================================================
   JAVASCRIPT SNIPPETS
==========================================================================*/

function jSnippets(){

	/**
	 * TOOLTIPS
	 **/
	//Note: tooltips enabled only if NOT mobile or tablet
	if(!zhongFramework.isPortable && zhongFramework.tooltipsEnabled===true){
		//Create a variable that points to the tooltip element
		var tooltipElement, tooltipFather;
		jQuery('.show-tooltip').hover(function(){
			tooltipFather = jQuery(this);
			tooltipElement = jQuery('<span class="tooltip-title">'+tooltipFather.attr('title')+'</span>')
			$body.append(tooltipElement);
			jQuery(this).attr('title','');// removes the title attribute from the link
			tooltipElement.fadeIn(460); // fadeIn the <span> element
			jQuery(this).mousemove(function(e){
				tooltipPositionX=e.pageX+16 - jQuery(window).scrollLeft();
				tooltipPositionY=e.pageY+10 - jQuery(window).scrollTop();
				if( tooltipPositionX+tooltipElement.width() > jQuery(window).width()-16 )
					{ tooltipPositionX = jQuery(window).width()-tooltipElement.width()-16; }
				if( tooltipPositionY+tooltipElement.height() > jQuery(document).height()-10 )
					{ tooltipPositionY = jQuery(document).height()-tooltipElement.height()-10; }
				tooltipElement // when the mouse moves, move also the <span> element
					.css('left',tooltipPositionX)
					.css('top',tooltipPositionY);
				});
		},function(){ // when the mouse un-hover the element:
			if(!(jQuery(this).attr('title'))){ // if the title is unchanged
				jQuery(this).attr('title',tooltipElement.text()); // set the title back
				}
			tooltipElement.remove(); // select the <span> element and remove it
			});
		}
	// END show-tooltips
	
	}
/*--------- END jSnippets() ---------*/

/*==========================================================================
   "DOCUMENT READY"
   //All handlers have been defined. Now fire the scripts!
==========================================================================*/
jQuery(document).ready(function(){
/*----------------------------------------------------------------
-  COMMON SCRIPTS (ALL LAYOUTS)
---------------------------------------------------------------- */

//Load user settings from cookie:
getUserSettings();
	
//Hide the mobile toolbar by scrolling 1px down
setTimeout(function(){
	//Some browsers "remember" the previous scroll position. If so, do not scroll to "1px"
	//Do the scrolling only if portable
	if(jQuery(window).scrollTop()===0 && zhongFramework.isPortable){ 
		window.scrollTo(0,1);
		}
	},400);

//If portable, disable top bar in a "fixed position" (some devices don't support "position:fixed")
if(zhongFramework.isPortable && $body.hasClass('fixed-top-bar')){
	$body.removeClass('fixed-top-bar');
	}

/*----------------------------------------------------------------
-  ONLY IN DEFAULT LAYOUT
---------------------------------------------------------------- */
if( $body.hasClass("default-layout") ){
	
	//The Accessibility panel is hidden by default
	$body.addClass('accessibility-panel-hidden');
	jQuery('#accessibility-panel').addClass('removed');
	
	//Load Javascript snippets
	jSnippets();
	
	//Dynamically modify some classes of the layout
	defaultLayoutModifications();
	
	/**
	 * ON-CLICK EVENTS
	**/
	
	//Show/Hide accessibility panel when clicking the accessibility button in the top bar
	jQuery('#show-accessibility-panel-button').click(function(){
		toggleAccessibilityPanelVisibility();
		});
	
	//Detect changing for Layout Width
	jQuery('#liquid-width-button').click(function(){ activateBodyCSS3Transition(); setLayoutWidthMode('liquid'); });
	jQuery('#fixed-width-button').click(function(){ activateBodyCSS3Transition(); setLayoutWidthMode('fixed'); });
	jQuery('#full-width-button').click(function(){ activateBodyCSS3Transition(); setLayoutWidthMode('full'); });
	
	//Detect font size changing
	jQuery('#larger-font-button').click(function(){ biggerFontSize(true); });
	jQuery('#reset-font-button').click(function(){ revertFontSize(); });
	jQuery('#smaller-font-button').click(function(){ biggerFontSize(false); });
	
	/**
	 * OTHER EVENTS
	**/
	
	//When the user hits the ESC key and the accessibility panel is open, hide it!
	jQuery(document).keydown(function(e){
		if (e.keyCode === 27 && $body.hasClass("accessibility-panel-visible")){
			toggleAccessibilityPanelVisibility(); // hide the panel
			}
		});
	
	//When the focused object is NOT in the accessibility panel, then close the panel
	jQuery(":not(#accessibility-panel *)").bind("focus",function(){
		//Just exit if the panel is already hidden OR is closing
		if($body.hasClass('accessibility-panel-hidden') || isPlanelClosing){return;}
		//Else
		else{ hideAccessibilityPanel(); }
		});
	
	/**
	 * KEYBOARD ACCESSIBLE MENU
	**/
	//This script makes the floating top menu & main menu accessible via keyboard
	var keyboardAccessibleMenu = function(menuID){
		// loads all links of the menu in an array
		var floatingMenuLinks = jQuery(menuID).find(".menu").find("a");
		for (var i=0; i<floatingMenuLinks.length; i++){
			floatingMenuLinks[i].onfocus=function(){ //on focus...
				// If the selected link
				
				// if the next sibling has a sub menu, hide it!
				if(jQuery(this).parent().next("li").children("ul").is("*")){
					jQuery(this).parent().next("li").children("ul").removeClass('visible-floating-menu');
					}
				// if the previous element has a sub menu, hide it!
				if(jQuery(this).parent().prev("li").children("ul").is("*")){
					jQuery(this).parent().prev("li").children("ul").removeClass('visible-floating-menu');
					}
				// if the current link ( on focus state ) has any sub menu, show it!
				if(jQuery(this).parent().children("ul").is("*")){
					jQuery(this).parent().children("ul").addClass('visible-floating-menu');
					}
				};
			}
		//When the focused object is NOT in the menu, then remove all visible sub-menus
		jQuery(":not("+menuID+" *)").bind("focus",function(){
			//If there is not element having the class "visible-floating-menu" then just do nothing
			if(!jQuery(menuID).find('.visible-floating-menu').size()){ return; };
			//If it exists, then remove the class (hide the sub menu)
			jQuery(menuID).find('.visible-floating-menu').each(function(){jQuery(this).removeClass('visible-floating-menu')});
			});
		}; //END "keyboardAccessibleMenu" function
	if(zhongFramework.topMenuFloating){ // if the top menu has floating items
		keyboardAccessibleMenu('#main-menu-container');
		}
	if(zhongFramework.sideMenuFloating){ // if the side menu has floating items
		keyboardAccessibleMenu('#main-body');
		}
	
	/**
	 * OTHER HANDLERS
	**/
	
	//Night mode transition handler
	nightModeTransitionHandler();
	//Scrolling to top page animation
	scrollToTopAnimationHandler();
	//User Layout Width Resize Handler
	userLayoutWidthResizeHandler();
	
	} //END "only in default layout" 

/*----------------------------------------------------------------
-  ONLY IN FULL ACCESS LAYOUT
---------------------------------------------------------------- */
if( $body.hasClass("full-access") ){

	//This script adds a warning string to links that open a new window
	jQuery('a[target="_blank"]').append('<span class="opens-new-window-link" title="'+zhongFramework.newWindowLinkText+'"> ( '+zhongFramework.newWindowLinkText+' )</span>');
	
	//This script adds a warning string to external link 
	//(Select all <a> elements that have 'href' starting with 'http://' && 'href' NOT CONTAINING 'site base url')
	jQuery('a[href^="http://"]:not(a[href*="'+zhongFramework.http_host+'"])')
		.append('<span class="external-link" title="'+zhongFramework.externalLinkText+'"> ( '+zhongFramework.externalLinkText+' )</span>');
	
	} //END "only in full access layout"

/*----------------------------------------------------------------
-  ONLY IN MOBILE LAYOUT
---------------------------------------------------------------- */
if( $body.hasClass("mobile-layout") ){

	//load JavaScript snippets
	jSnippets();
	//Dynamically modify some classes of the layout
	defaultLayoutModifications();
	
	//The night/day mode link is better to be considered as a button (only for the accessibility tree)
	jQuery('#night-mode-switcher').attr('role','button');
	
	/**
	 * ON-CLICK EVENTS
	**/
	
	//Detect font size changing
	jQuery('#larger-font-button').click(function(){ biggerFontSize(true); });
	jQuery('#reset-font-button').click(function(){ revertFontSize(); });
	jQuery('#smaller-font-button').click(function(){ biggerFontSize(false); });
	
	//Handles the "expandable menu navigation"
	if( $body.hasClass("mobile_menus-navigation-mode_expandable") ){
		jQuery('.menu-container .parent>ul').slideUp(0);
		jQuery('.menu-container .parent>a').click(function(e){e.preventDefault();});
		jQuery('.menu-container .parent').one('click',function(){
			jQuery(this).children('ul').slideDown(400);
			jQuery(this).children('a').click(function(){window.location=jQuery(this).attr('href');});
			});
		} //END "expandable menu navigation" handler
	
	/**
	 * SETTING UP
	**/
	
	/* Disable the "anchor function" for those links and add an animation to show the module */
	jQuery('#mobile-top-bar-tool_fontsize-button').click(
		function(e){
			e.preventDefault();
			if(jQuery(this).hasClass('active')){ jQuery(this).removeClass('active'); }
			else{ jQuery(this).addClass('active'); }
			jQuery('#mobile-top-bar_module-container_fontsize').slideToggle('slow');
			});
	jQuery('#mobile-top-bar-tool_login-button').click(
		function(e){
			e.preventDefault();
			if(jQuery(this).hasClass('active')){ jQuery(this).removeClass('active'); }
			else{ jQuery(this).addClass('active'); }
			jQuery('#mobile-top-bar_module-container_login').slideToggle('slow');
			});
	jQuery('#mobile-top-bar-tool_search-button').click(
		function(e){
			e.preventDefault();
			if(jQuery(this).hasClass('active')){ jQuery(this).removeClass('active'); }
			else{ jQuery(this).addClass('active'); }
			jQuery('#mobile-top-bar_module-container_search').slideToggle('slow');
			});
	jQuery('#mobile-top-bar-tool_language-button').click(
		function(e){
			e.preventDefault();
			if(jQuery(this).hasClass('active')){ jQuery(this).removeClass('active'); }
			else{ jQuery(this).addClass('active'); }
			jQuery('#mobile-top-bar_module-container_language').slideToggle('slow');
			});
	
	/**
	 * OTHER HANDLERS
	**/
	
	//Night mode transition handler
	nightModeTransitionHandler();
	//Scrolling to top page animation
	scrollToTopAnimationHandler();
	
	} //END "only in mobile layout"

/*----------------------------------------------------------------
-  BOTH IN DEFAULT LAYOUT & MOBILE LAYOUT
---------------------------------------------------------------- */
if( $body.hasClass("default-layout") || $body.hasClass("mobile-layout") ){
	
	//The night/day mode link is better to be considered as a button (only for the accessibility tree)
	jQuery('#night-mode-switcher').attr('role','button');
	
	}//END "Both in default & mobile layout"

}); /*--------- END "document.ready" ---------*/
	
/*==========================================================================
   ON WINDOW LOADED
   //When EVERYHITNG is loaded (imgs included)
==========================================================================*/
jQuery(window).load(function(){

/*----------------------------------------------------------------
-  ONLY IN DEFAULT LAYOUT
---------------------------------------------------------------- */
if( $body.hasClass("default-layout") ){
	
	//If enabled, set the same height for the custom modules
	sameHeightCustomModulesHandler();
	
	/**
	 * ACCORDION block
	 **/
	jQuery('.accordion-block')
		.css("display","block")
		.css("overflow","hidden")
		.css("position","relative")
		.append('<span class="accordion-block-readMore icon-show-action" aria-hidden="true"></span>') // add the button
		.css({ // set the 20% of the height to the element + the height of the button + 6px
			height:function(){
				return jQuery(this).height()/100*20+jQuery(this).find(".accordion-block-readMore").height()+6;
				}
			});
	jQuery('.accordion-block-readMore').click(function(){
		if(jQuery(this).hasClass('icon-show-action')){
			jQuery(this)
				.removeClass('icon-show-action').addClass('icon-hide-action')
				.parent()
				.css({ // set 100% height to the element + the height of the button + 6px
					height:function(){
						return (jQuery(this).height()-jQuery(this).find(".accordion-block-readMore").height()-6)*100/20+jQuery(this).find(".accordion-block-readMore").height()+6;
						}}
					);
			}
		else{
			jQuery(this)
				.removeClass('icon-hide-action').addClass('icon-show-action')
				.parent()
				.css({ // set the 20% of the height to the element + the height of the button + 6px
					height:function(){
						return (jQuery(this).height()-jQuery(this).find(".accordion-block-readMore").height()-6)/100*20+jQuery(this).find(".accordion-block-readMore").height()+6;
						}
					});
			}
		});	// END accordion block

	}
	
}); /*--------- END "window load" ---------*/

/*==========================================================================
   ON WINDOW UNLOAD
==========================================================================*/
//Save user settings in the cookie
jQuery(window).unload(function(){ saveUserSettings(); });

})();