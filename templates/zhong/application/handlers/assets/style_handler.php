<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

/*----------------------------------------------------------------
-  MAIN STYLE HANDLER
---------------------------------------------------------------- */

if(ZHONGFRAMEWORK_PARAMETER_ENABLE_MINIFY_METHODS=="true"){
	//If the minify option is set to true, then call the CSS stylesheets aggregator (passing layout parameters through url)
	echo '<link rel="stylesheet" type="text/css" media="screen,projection,handheld" href="'.ZHONGFRAMEWORK_WEBSITE_TEMPLATE_URI.'/assets/mixers/css/';
	echo 'style.css.php?layoutMode='.ZHONGFRAMEWORK_LAYOUT_MODE;
	echo '&amp;graphicMode='.ZHONGFRAMEWORK_GRAPHIC_MODE;
	echo '&amp;minify='.ZHONGFRAMEWORK_PARAMETER_ENABLE_MINIFY_METHODS;
	echo '&amp;platform='.ZHONGFRAMEWORK_PARENT_CMS_PLATFORM;
	echo '&amp;platformVersion='.ZHONGFRAMEWORK_PARENT_CMS_RELEASE_CLASS;
	echo '&amp;textDirection='.ZHONGFRAMEWORK_WEBSITE_TEXT_DIRECTION;
	echo '" />';
	}
else{
	//If the minify option isset to false, simply include the link to the CSS
	require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/handlers/assets/stylesheets_handler.php');
	foreach($css_files_to_include as $css_file){
		echo '<link rel="stylesheet" type="text/css" media="screen,projection,handheld" href="'.ZHONGFRAMEWORK_WEBSITE_TEMPLATE_URI.'/assets/'.$css_file.'" />';
		}
	}
?>

<?php
/*----------------------------------------------------------------
-  PRINT STYLE
---------------------------------------------------------------- */

echo '<style type="text/css" media="print">';
//Start the compressing method
ob_start("simpleCSSMinifyMethod");
//Include the print style
require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'assets/css/other/print.css');
/**
 * Custom print style:
**/
//Font family
if(ZHONGFRAMEWORK_PARAMETER_PRINT_FONT_TYPE=='garamond'){ 
	echo 'body{font-family:Garamond,"Apple Garamond",Georgia,serif !important;}';
	}
elseif(ZHONGFRAMEWORK_PARAMETER_PRINT_FONT_TYPE=='centurygothic'){
	echo 'body{font-family: "Century Gothic", CenturyGothic, AppleGothic, sans-serif !important;}';
	}
elseif(ZHONGFRAMEWORK_PARAMETER_PRINT_FONT_TYPE=='timesnewroman'){
	echo 'body{font-family: TimesNewRoman, "Times New Roman", Times, Baskerville, Georgia, serif !important;}';
	}
//Font size
if(ZHONGFRAMEWORK_PARAMETER_PRINT_FONT_SIZE=='big'){ 
	echo 'body{font-size:110% !important;}';
	}
elseif(ZHONGFRAMEWORK_PARAMETER_PRINT_FONT_SIZE=='normal'){
	echo 'body{font-size:80% !important;}';
	}
elseif(ZHONGFRAMEWORK_PARAMETER_PRINT_FONT_SIZE=='small'){
	echo 'body{font-size:65% !important;}';
	}
//Show/hide layout elements
if(ZHONGFRAMEWORK_PARAMETER_PRINT_SHOW_HEADER=='false'){ 
	echo '#header{display:none !important;}';
	}
if(ZHONGFRAMEWORK_PARAMETER_PRINT_SHOW_LOGO=='false'){ 
	echo '#logo-wrap{display:none !important;}';
	}
if(ZHONGFRAMEWORK_PARAMETER_PRINT_SHOW_BREADCRUMBS=='false'){ 
	echo '#breadcrumbs{display:none !important;}';
	}
if(ZHONGFRAMEWORK_PARAMETER_PRINT_SHOW_FOOTER=='false'){ 
	echo '#footer-wrapper{display:none !important;}';
	}
if(ZHONGFRAMEWORK_PARAMETER_PRINT_IMAGES=='true'){ 
	echo 'img{display:none !important;}';
	}
//Flush the output (due to the compression method)
ob_end_flush();
echo '</style>'
?>

<?php
/*----------------------------------------------------------------
-  SPEECH
---------------------------------------------------------------- */

echo '<style type="text/css" media="speech">';
//Start the compressing method
ob_start("simpleCSSMinifyMethod");
//Include the speech style
require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'assets/css/other/speech.css');
//Flush the output (due to the compression method)
ob_end_flush();
echo '</style>'
?>

<?php
/*----------------------------------------------------------------
-  INTERNET EXPLORERs STYLE
---------------------------------------------------------------- */
?>
<!--[if IE 6]>
	<link rel="stylesheet" href="<?php echo ZHONGFRAMEWORK_WEBSITE_TEMPLATE_URI; ?>/assets/css/other/ie6only.css" type="text/css" />
<![endif]-->
<!--[if IE 7]>
	<link rel="stylesheet" href="<?php echo ZHONGFRAMEWORK_WEBSITE_TEMPLATE_URI; ?>/assets/css/other/ie7only.css" type="text/css" />
<![endif]-->
<!--[if IE 8]>
	<link rel="stylesheet" href="<?php echo ZHONGFRAMEWORK_WEBSITE_TEMPLATE_URI; ?>/assets/css/other/ie8only.css" type="text/css" />
<![endif]-->

<?php
/*----------------------------------------------------------------
-  CUSTOM USER STYLE
---------------------------------------------------------------- */
?>
<style type="text/css"><?php

	//Start the compressing method
	ob_start("simpleCSSMinifyMethod");

	if(ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout" || ZHONGFRAMEWORK_LAYOUT_MODE=='mobile-layout'){
		
		//Include custom colors & style depending on the template backend parameters
		require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/handlers/assets/custom_user_style.php');
		
		//Include the "custom user inline CSS"		
		echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_INLINE_CSS;
		
		}
	
	//Include the custom overrides
	require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'assets/custom-overrides/css/overrides.css');

	//Flush the output (due to the compression method)
	ob_end_flush();

?></style>
