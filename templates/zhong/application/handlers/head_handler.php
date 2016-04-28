<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' ); ?>
<?php

/*==========================================================================
   	MISCELLANEOUS
==========================================================================*/

//custom favicon
if(ZHONGFRAMEWORK_PARAMETER_WEBSITE_FAVICON!=""){
	echo '<link rel="icon" type="image/ico" href="'.ZHONGFRAMEWORK_PARAMETER_WEBSITE_FAVICON.'" />';
	}


//If "mobile layout", set the correct meta-viewport
if(ZHONGFRAMEWORK_LAYOUT_MODE=="mobile-layout"){
	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0" />';
	}



/*----------------------------------------------------------------
-  GOOGLE FONT IMPORT
---------------------------------------------------------------- */
if(ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_GOOGLE_IMPORT_URL_1!=""){
	echo '<link href="'.ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_GOOGLE_IMPORT_URL_1.'" rel="stylesheet" type="text/css">';
	}
if(ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_GOOGLE_IMPORT_URL_2!=""){
	echo '<link href="'.ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_GOOGLE_IMPORT_URL_2.'" rel="stylesheet" type="text/css">';
	}
if(ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_GOOGLE_IMPORT_URL_3!=""){
	echo '<link href="'.ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_GOOGLE_IMPORT_URL_3.'" rel="stylesheet" type="text/css">';
	}
if(ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_GOOGLE_IMPORT_URL_4!=""){
	echo '<link href="'.ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_GOOGLE_IMPORT_URL_4.'" rel="stylesheet" type="text/css">';
	}



/*----------------------------------------------------------------
-  CUSTOM HEAD TAGS
---------------------------------------------------------------- */
//Print user custom tag
foreach( $ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEAD_TAG as $customHeadTag ){
	//If disabled, skip to the next one
	if($customHeadTag["type"]=="disabled"){ continue; }
	//tag begin:
	echo '<'.$customHeadTag["type"];
	foreach( $customHeadTag["attributes"] as $customHeadTagAttribute => $customHeadTagAttributeContent ){
		//If the attribute is empty, skip it
		if($customHeadTagAttribute==''){ continue; }
		echo ' '.$customHeadTagAttribute.'="'.$customHeadTagAttributeContent.'"';
		}
	//tag end:
	if($customHeadTag["type"]=="script")
		{ echo '></script>'; }
	else
		{ echo ' />'; }
	}


?>

<?php
/*==========================================================================
   JAVASCRIPTS
==========================================================================*/

/*----------------------------------------------------------------
-  REMOVE no-js class from the <html> tag
---------------------------------------------------------------- */ ?>
<script type="text/javascript">
	document.getElementsByTagName("html")[0].className = document.getElementsByTagName("html")[0].className.replace("no-js","");
</script>

<?php
/*----------------------------------------------------------------
-  JAVASCRIPT DEBUGGER
---------------------------------------------------------------- */
if(ZHONGFRAMEWORK_PARAMETER_ENABLE_JAVASCRIPT_DEBUGGER=="true"): ?>
	<!-- Firebug + Javascript debugger -->
	<script type="text/javascript" src="https://getfirebug.com/firebug-lite.js">{
	    startInNewWindow: true,
	    startOpened: true
		}
	</script>
	<script type="text/javascript" language="javascript"> 
		window.onerror = function(message, url, lineNumber) {  
			console.debug("Page: "+url);
			console.debug("Error: "+message);
			console.debug("Line Number: "+lineNumber);
			console.debug(" ");
			return true;
			}; 
	</script>
<?php endif; ?>

<?php
/*----------------------------------------------------------------
-  MODERNIZR LIBRARY
---------------------------------------------------------------- */
//Loads modernizr library if it is enabled from the backend
if(ZHONGFRAMEWORK_PARAMETER_ENABLE_MODERNIZR_LIBRARY=="true"){?>
	<script type="text/javascript"  src="<?php echo ZHONGFRAMEWORK_WEBSITE_TEMPLATE_URI ?>/assets/javascript/modernizr/modernizr.js"></script>
<?php } ?>

<?php
/*----------------------------------------------------------------
-  JQUERY LIBRARY
---------------------------------------------------------------- */
//Load jQuery library if it is enabled from the backend
//(note that it will not be included if another copy of the library has already been included)
if(ZHONGFRAMEWORK_PARAMETER_ENABLE_JQUERY_LIBRARY=="true"){?>
<script>window.jQuery || document.write('<script src="<?php echo ZHONGFRAMEWORK_WEBSITE_BASE_URI ?>templates/<?php echo ZHONGFRAMEWORK_WEBSITE_TEMPLATE_NAME;?>/assets/javascript/jQuery/jquery.min.js"><\/script>')</script>
<?php } ?>