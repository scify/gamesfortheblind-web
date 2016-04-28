<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
 // This script transfers PHP variables into JavaScript variables
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

//Start the compressing method
ob_start("simpleJSMinifyMethod");
?>

<?php if(ZHONGFRAMEWORK_PARAMETER_PREVENT_JAVASCRIPT_ERRORS_IE_ALERT=='true'): ?>
//Prevents IE8< to display javascript errors
if(document.getElementsByTagName('html')[0].className.match("lt-ie9")){window.onerror=function(){return true;};}
<?php endif; ?>

//The "zhong framework" global object
var zhongFramework = new function(){

	this.defaultLayoutWidthMode='<?php echo ZHONGFRAMEWORK_PARAMETER_LAYOUT_WIDTH_MODE; ?>';
	this.tooltipsEnabled=<?php echo ZHONGFRAMEWORK_PARAMETER_ENABLE_JAVASCRIPT_TOOLTIPS; ?>;
	this.sameHeightCustomModules=<?php echo ZHONGFRAMEWORK_PARAMETER_SAME_HEIGHT_CUSTOM_MODULES; ?>;
	this.http_host="<?php echo htmlentities($_SERVER['HTTP_HOST'],ENT_QUOTES); ?>";
	
	this.accessPanelTitle_closed="<?php echo html_entity_decode(ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_ADVANCED_HIDDEN_TITLE,ENT_QUOTES,"UTF-8"); ?>";
	this.accessPanelText_closed="<?php echo html_entity_decode(ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_ADVANCED_HIDDEN_CONTENT,ENT_QUOTES,"UTF-8"); ?>";
	this.accessPanelTitle_open="<?php echo html_entity_decode(ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_ADVANCED_SHOWED_TITLE,ENT_QUOTES,"UTF-8"); ?>";
	this.accessPanelText_open="<?php echo html_entity_decode(ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_ADVANCED_SHOWED_CONTENT,ENT_QUOTES,"UTF-8"); ?>";
	this.newWindowLinkText="<?php echo html_entity_decode(ZHONGFRAMEWORK_LANGUAGE_NEW_WINDOW_LINK,ENT_QUOTES,"UTF-8"); ?>";
	this.externalLinkText="<?php echo html_entity_decode(ZHONGFRAMEWORK_LANGUAGE_EXTERNAL_LINK,ENT_QUOTES,"UTF-8"); ?>";
	this.nightModeLinkText="<?php echo html_entity_decode(ZHONGFRAMEWORK_LANGUAGE_NIGHT_MODE_CONTENT,ENT_QUOTES,"UTF-8"); ?>";
	this.nightModeLinkTitle="<?php echo html_entity_decode(ZHONGFRAMEWORK_LANGUAGE_NIGHT_MODE_TITLE,ENT_QUOTES,"UTF-8"); ?>";
	this.dayModeLinkText="<?php echo html_entity_decode(ZHONGFRAMEWORK_LANGUAGE_DAY_MODE_CONTENT,ENT_QUOTES,"UTF-8"); ?>";
	this.dayModeLinkTitle="<?php echo html_entity_decode(ZHONGFRAMEWORK_LANGUAGE_DAY_MODE_TITLE,ENT_QUOTES,"UTF-8"); ?>";
	
	<?php
	//Set the default font size (different if default layout or mobile layout)
	if(ZHONGFRAMEWORK_LAYOUT_MODE=="mobile-layout"){
		echo 'this.defaultFontSize='.ZHONGFRAMEWORK_PARAMETER_DEFAULT_FONT_SIZE_MOBILE.';';
		}
	else{
		echo 'this.defaultFontSize='.ZHONGFRAMEWORK_PARAMETER_FONT_SIZE.';';
		}
	?>
	
	<?php
		if(ZHONGFRAMEWORK_PARAMETER_MAIN_MENU_STYLE=='verticalFloating' || ZHONGFRAMEWORK_PARAMETER_MAIN_MENU_STYLE=='horizontalFloating')
			{ echo 'this.topMenuFloating=true;'; }
		else
			{ echo 'this.topMenuFloating=false;'; }
		if(ZHONGFRAMEWORK_PARAMETER_SIDE_MENU_STYLE=='floating')
			{ echo 'this.sideMenuFloating=true;'; }
		else
			{ echo 'this.sideMenuFloating=false;'; }
		?>
	
	//Get the client's device info
	<?php
		if($ZHONGFRAMEWORK_CLIENT_DEVICE_TYPE=='desktop')
			{ echo 'this.isMobile=false;this.isTablet=false;'; }
		else{
			if($ZHONGFRAMEWORK_CLIENT_DEVICE_TYPE=='mobile'){ echo "this.isMobile=true;"; }
			else{ echo "this.isMobile=false;"; }
			if($ZHONGFRAMEWORK_CLIENT_DEVICE_TYPE=='tablet'){ echo "this.isTablet=true;"; }
			else{ echo "this.isTablet=false;"; }
			}
		?>
	this.isPortable = this.isMobile || this.isTablet;
	
	}; //END zhongFramework global object

<?php
//Flush the output (due to the compression method)
ob_end_flush();
?>
