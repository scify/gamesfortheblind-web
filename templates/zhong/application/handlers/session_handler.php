<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

/*==========================================================================
   DETECT MOBILE
==========================================================================*/
//Check if the client uses a mobile
require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/handlers/other/detect_user_device.php');

/*==========================================================================
   DETECT CRAWLER
==========================================================================*/
//Check if the client is a crawler
require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/handlers/other/detect_crawler.php');

/*==========================================================================
   SESSION HANDLER & COOKIE HANDLER
==========================================================================*/

/**
 * SESSION VARIABLES:
 * $_SESSION['zhongFrameworkFontSize'] ( used for setting the layout mode ) 4 possible values:
 * 'default-layout', 'full-access', 'high-contrast', 'mobile-layout'
 * $_SESSION['zhongFrameworkGraphicMode'] ( used only if javascript is disabled ) has three possible values:
 * 'default-graphic-mode', 'best-legibility', 'night-mode'
 * $_SESSION['zhongFrameworkFontSize'] ( used only if javascript is disabled ) sets the font size in percentage
 * $_SESSION['zhongFrameworkLayoutWidth'] ( used only if javascript is disabled )
 **/

// Default cookie expires is one month.
define("ZHONGFRAMEWORK_EXPIRE_DATE_COOKIE_USER_PREFERENCES", 60 * 60 * 12 * 60 + time());

/*----------------------------------------------------------------
-  NEW SESSION HANDLER
---------------------------------------------------------------- */
// If a NEW SESSION is started, the session variables are initialized.
if(!isset($_SESSION['zhongFrameworkLayoutMode']) || 
   !isset($_SESSION['zhongFrameworkGraphicMode']) || 
   !isset($_SESSION['zhongFrameworkFontSize']) || 
   !isset($_SESSION['zhongFrameworkLayoutWidth'])){
	
	/*----------------------------------------------------------------
	-  COOKIE HAS NOT BEEN SET
	---------------------------------------------------------------- */
	// If ANY of the cookie hasn't been set, store the default values
	if(!isset($_COOKIE['zhongFrameworkLayoutMode']) || 
	   !isset($_COOKIE['zhongFrameworkGraphicMode']) || 
	   !isset($_COOKIE['zhongFrameworkFontSize']) || 
	   !isset($_COOKIE['zhongFrameworkLayoutWidth'])){
		//As default value, set the "default layout" mode
		setcookie('zhongFrameworkLayoutMode','default-layout',ZHONGFRAMEWORK_EXPIRE_DATE_COOKIE_USER_PREFERENCES);
		$_SESSION['zhongFrameworkLayoutMode']='default-layout';


		//But, if the client is using a mobile, set the "mobile layout"
		if($ZHONGFRAMEWORK_CLIENT_DEVICE_TYPE==='mobile'){
			setcookie('zhongFrameworkLayoutMode','mobile-layout',ZHONGFRAMEWORK_EXPIRE_DATE_COOKIE_USER_PREFERENCES);
			$_SESSION['zhongFrameworkLayoutMode']='mobile-layout';
			}
		
		
		setcookie('zhongFrameworkGraphicMode','default-graphic-mode',ZHONGFRAMEWORK_EXPIRE_DATE_COOKIE_USER_PREFERENCES);
		$_SESSION['zhongFrameworkGraphicMode']='default-graphic-mode';	
		setcookie('zhongFrameworkFontSize',ZHONGFRAMEWORK_PARAMETER_FONT_SIZE,ZHONGFRAMEWORK_EXPIRE_DATE_COOKIE_USER_PREFERENCES);
		$_SESSION['zhongFrameworkFontSize']=ZHONGFRAMEWORK_PARAMETER_FONT_SIZE;		
		setcookie('zhongFrameworkLayoutWidth',ZHONGFRAMEWORK_PARAMETER_LAYOUT_WIDTH_MODE,ZHONGFRAMEWORK_EXPIRE_DATE_COOKIE_USER_PREFERENCES);
		$_SESSION['zhongFrameworkLayoutWidth']=ZHONGFRAMEWORK_PARAMETER_LAYOUT_WIDTH_MODE;
		}
	/*----------------------------------------------------------------
	-  COOKIE HAS BEEN SET
	---------------------------------------------------------------- */
	// If a cookie has already been set, then get cookie values
	else{
		$_SESSION['zhongFrameworkLayoutMode']=htmlentities($_COOKIE['zhongFrameworkLayoutMode'],ENT_QUOTES);
		$_SESSION['zhongFrameworkGraphicMode']=htmlentities($_COOKIE['zhongFrameworkGraphicMode'],ENT_QUOTES);
		$_SESSION['zhongFrameworkFontSize']=htmlentities($_COOKIE['zhongFrameworkFontSize'],ENT_QUOTES);
		$_SESSION['zhongFrameworkLayoutWidth']=htmlentities($_COOKIE['zhongFrameworkLayoutWidth'],ENT_QUOTES);
		}
	}


/*==========================================================================
   PHP "GET" HANDLER
==========================================================================*/

/*----------------------------------------------------------------
-  [GET] LAYOUT MODE
---------------------------------------------------------------- */
if(isset($_GET['layoutMode'])){
	// Get value passed via URL
	define("ZHONGFRAMEWORK_PHP_GET_PARAMETER_LAYOUT_MODE",htmlentities($_GET['layoutMode'],ENT_QUOTES));
	// Check the validity of the value
	if(ZHONGFRAMEWORK_PHP_GET_PARAMETER_LAYOUT_MODE!=="default" && 
	   ZHONGFRAMEWORK_PHP_GET_PARAMETER_LAYOUT_MODE!=="full-access" && 
	   ZHONGFRAMEWORK_PHP_GET_PARAMETER_LAYOUT_MODE!=="highcontrast" && 
	   ZHONGFRAMEWORK_PHP_GET_PARAMETER_LAYOUT_MODE!=="mobile")
		{$_SESSION['zhongFrameworkLayoutMode']='default-layout';}
	// Set the new session value 
	if(ZHONGFRAMEWORK_PHP_GET_PARAMETER_LAYOUT_MODE==="default"){ $_SESSION['zhongFrameworkLayoutMode']='default-layout'; }
	if(ZHONGFRAMEWORK_PHP_GET_PARAMETER_LAYOUT_MODE==="full-access"){ $_SESSION['zhongFrameworkLayoutMode']='full-access'; }
	if(ZHONGFRAMEWORK_PHP_GET_PARAMETER_LAYOUT_MODE==="highcontrast"){ $_SESSION['zhongFrameworkLayoutMode']='high-contrast'; }
	if(ZHONGFRAMEWORK_PHP_GET_PARAMETER_LAYOUT_MODE==="mobile"){ $_SESSION['zhongFrameworkLayoutMode']='mobile-layout'; }
	// When the layout mode is changed reset the other values
	$_SESSION['zhongFrameworkGraphicMode']='default-graphic-mode';
	setcookie('zhongFrameworkGraphicMode','default-graphic-mode',ZHONGFRAMEWORK_EXPIRE_DATE_COOKIE_USER_PREFERENCES);
	$_SESSION['zhongFrameworkLayoutWidth']=ZHONGFRAMEWORK_PARAMETER_LAYOUT_WIDTH_MODE;
	setcookie('zhongFrameworkLayoutWidth',ZHONGFRAMEWORK_PARAMETER_LAYOUT_WIDTH_MODE,ZHONGFRAMEWORK_EXPIRE_DATE_COOKIE_USER_PREFERENCES);
	// Store the new value into the cookie
	setcookie('zhongFrameworkLayoutMode',$_SESSION['zhongFrameworkLayoutMode'],ZHONGFRAMEWORK_EXPIRE_DATE_COOKIE_USER_PREFERENCES);
	}

/*----------------------------------------------------------------
-  [GET] GRAPHIC MODE
---------------------------------------------------------------- */
if(isset($_GET['graphicMode'])){
	// Get value passed via URL
	define("ZHONGFRAMEWORK_PHP_GET_PARAMETER_GRAPHIC_MODE",htmlentities($_GET['graphicMode'],ENT_QUOTES));
	// Check the validity of the value
	if(ZHONGFRAMEWORK_PHP_GET_PARAMETER_GRAPHIC_MODE!=="default" && 
	   ZHONGFRAMEWORK_PHP_GET_PARAMETER_GRAPHIC_MODE!=="best" && 
	   ZHONGFRAMEWORK_PHP_GET_PARAMETER_GRAPHIC_MODE!=="night")
		{$_SESSION['zhongFrameworkGraphicMode']='default-graphic-mode';}
	// Set the new session value
	if(ZHONGFRAMEWORK_PHP_GET_PARAMETER_GRAPHIC_MODE==="default"){ $_SESSION['zhongFrameworkGraphicMode']='default-graphic-mode'; }
	if(ZHONGFRAMEWORK_PHP_GET_PARAMETER_GRAPHIC_MODE==="best"){ $_SESSION['zhongFrameworkGraphicMode']='best-legibility'; }
	if(ZHONGFRAMEWORK_PHP_GET_PARAMETER_GRAPHIC_MODE==="night"){ $_SESSION['zhongFrameworkGraphicMode']='night-mode'; }
	// If high-contrast OR full access, then return to the default mode
	if($_SESSION['zhongFrameworkLayoutMode']==='high-contrast' || $_SESSION['zhongFrameworkLayoutMode']==='full-access'){
		$_SESSION['zhongFrameworkLayoutMode']='default-layout';
		setcookie('zhongFrameworkGraphicMode','default-layout',ZHONGFRAMEWORK_EXPIRE_DATE_COOKIE_USER_PREFERENCES);
		}
	// Store the new value into the cookie
	setcookie('zhongFrameworkGraphicMode',$_SESSION['zhongFrameworkGraphicMode'],ZHONGFRAMEWORK_EXPIRE_DATE_COOKIE_USER_PREFERENCES);
	}

/*----------------------------------------------------------------
-  [GET] FONT SIZE
---------------------------------------------------------------- */
if(isset($_GET['fontSize'])){
	// Get value passed via URL
	define("ZHONGFRAMEWORK_PHP_GET_PARAMETER_FONT_SIZE",htmlentities($_GET['fontSize'],ENT_QUOTES));
	// Check the validity of the value	
	if(ZHONGFRAMEWORK_PHP_GET_PARAMETER_FONT_SIZE!=="default" && 
	   ZHONGFRAMEWORK_PHP_GET_PARAMETER_FONT_SIZE!=="increase" && 
	   ZHONGFRAMEWORK_PHP_GET_PARAMETER_FONT_SIZE!=="decrease")
		{$_SESSION['zhongFrameworkFontSize']=ZHONGFRAMEWORK_PARAMETER_FONT_SIZE;}
	// Set the new session value
	if(ZHONGFRAMEWORK_PHP_GET_PARAMETER_FONT_SIZE==="default"){
		//if mobile layout, set the default mobile font size
		if($_SESSION['zhongFrameworkLayoutMode']==='mobile-layout'){
			$_SESSION['zhongFrameworkFontSize']=ZHONGFRAMEWORK_PARAMETER_DEFAULT_FONT_SIZE_MOBILE;
			}
		//If NOT in mobile layout, then set the default value
		else{
			$_SESSION['zhongFrameworkFontSize']=ZHONGFRAMEWORK_PARAMETER_FONT_SIZE;
			}
		}
	if(ZHONGFRAMEWORK_PHP_GET_PARAMETER_FONT_SIZE==="increase"){
		$_SESSION['zhongFrameworkFontSize']=floatval($_SESSION['zhongFrameworkFontSize']);
		$_SESSION['zhongFrameworkFontSize']+=10;
		}
	if(ZHONGFRAMEWORK_PHP_GET_PARAMETER_FONT_SIZE==="decrease"){
		$_SESSION['zhongFrameworkFontSize']=floatval($_SESSION['zhongFrameworkFontSize']);
		$_SESSION['zhongFrameworkFontSize']-=10;
		}
	// Store the new value into the cookie
	setcookie('zhongFrameworkFontSize',$_SESSION['zhongFrameworkFontSize'],ZHONGFRAMEWORK_EXPIRE_DATE_COOKIE_USER_PREFERENCES);
	}

/*----------------------------------------------------------------
-  [GET] LAYOUT WIDTH
---------------------------------------------------------------- */
if(isset($_GET['layoutWidth'])){
	// Get value passed via URL
	define("ZHONGFRAMEWORK_PHP_GET_PARAMETER_LAYOUT_WIDTH",htmlentities($_GET['layoutWidth'],ENT_QUOTES));
	// Check the validity of the value
	if(ZHONGFRAMEWORK_PHP_GET_PARAMETER_LAYOUT_WIDTH!=="full" && 
	   ZHONGFRAMEWORK_PHP_GET_PARAMETER_LAYOUT_WIDTH!=="fixed" && 
	   ZHONGFRAMEWORK_PHP_GET_PARAMETER_LAYOUT_WIDTH!=="liquid")
		{$_SESSION['zhongFrameworkLayoutWidth']=ZHONGFRAMEWORK_PARAMETER_LAYOUT_WIDTH_MODE;}
	// Set the new session value
	if(ZHONGFRAMEWORK_PHP_GET_PARAMETER_LAYOUT_WIDTH==="full"){ $_SESSION['zhongFrameworkLayoutWidth']="full"; }
	if(ZHONGFRAMEWORK_PHP_GET_PARAMETER_LAYOUT_WIDTH==="fixed"){ $_SESSION['zhongFrameworkLayoutWidth']="fixed"; }
	if(ZHONGFRAMEWORK_PHP_GET_PARAMETER_LAYOUT_WIDTH==="liquid"){ $_SESSION['zhongFrameworkLayoutWidth']="liquid"; }
	// Store the new value into the cookie
	setcookie('zhongFrameworkLayoutWidth',$_SESSION['zhongFrameworkLayoutWidth'],ZHONGFRAMEWORK_EXPIRE_DATE_COOKIE_USER_PREFERENCES);
	}


/*==========================================================================
   DEFINE SESSION PARAMETERS
==========================================================================*/

// Check session values; if invalid values -> assign default values
if($_SESSION['zhongFrameworkLayoutMode']!=='default-layout' && 
   $_SESSION['zhongFrameworkLayoutMode']!=='full-access' && 
   $_SESSION['zhongFrameworkLayoutMode']!=='high-contrast' && 
   $_SESSION['zhongFrameworkLayoutMode']!=='mobile-layout')
	{$_SESSION['zhongFrameworkLayoutMode']='default-layout';}
if($_SESSION['zhongFrameworkGraphicMode']!=='default-graphic-mode' && 
   $_SESSION['zhongFrameworkGraphicMode']!=='best-legibility' && 
   $_SESSION['zhongFrameworkGraphicMode']!=='night-mode')
	{$_SESSION['zhongFrameworkGraphicMode']='default-graphic-mode';}
if(!preg_match('/^[0-9]+.+[0-9]+$/',$_SESSION['zhongFrameworkFontSize']) && !preg_match('/^[0-9]+$/',$_SESSION['zhongFrameworkFontSize']))
	{$_SESSION['zhongFrameworkFontSize']=ZHONGFRAMEWORK_PARAMETER_FONT_SIZE;}
if($_SESSION['zhongFrameworkLayoutWidth']!=="full" && 
   $_SESSION['zhongFrameworkLayoutWidth']!=="fixed" && 
   $_SESSION['zhongFrameworkLayoutWidth']!=="liquid")
	{$_SESSION['zhongFrameworkLayoutWidth']=ZHONGFRAMEWORK_PARAMETER_LAYOUT_WIDTH_MODE;}

//Define session parameters
define("ZHONGFRAMEWORK_LAYOUT_MODE",$_SESSION['zhongFrameworkLayoutMode']);
define("ZHONGFRAMEWORK_GRAPHIC_MODE",$_SESSION['zhongFrameworkGraphicMode']);
define("ZHONGFRAMEWORK_FONT_SIZE",$_SESSION['zhongFrameworkFontSize']);
define("ZHONGFRAMEWORK_LAYOUT_WIDTH_MODE",$_SESSION['zhongFrameworkLayoutWidth']);

?>
