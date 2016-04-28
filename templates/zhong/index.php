<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

	/*----------------------------------------------------------------
	-  STARTING UP (HANDLERS & PARAMETERS SETTING)
	---------------------------------------------------------------- */

	define("_ZHONGFRAMEWORK",true);

	//Define absolute path for the current template
	define("ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR",dirname(__FILE__)."/");

	//Get parent platform info
	require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/handlers/cms/cms_platform_handler.php');

	//Get template parameters
	require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/handlers/cms/cms_template_parameters_handler.php');
	
	//If enabled, set up the PHP DEBUG MODE
	if(ZHONGFRAMEWORK_PARAMETER_ENABLE_PHP_DEBUGGER=="true" || isset($_GET["zf-php-debug"])){
		ini_set('display_errors',1);
		error_reporting(E_ALL);
		}

	//Get CMS parameters
	require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/handlers/cms/cms_native_parameters_handler.php');
	
	//Get CMS components/modules views && create a new object
	require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/handlers/cms/cms_components_view.php');
	$parentCMSHandler = new ParentCMSHandler();
	
	//Initialize session
	require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/handlers/session_handler.php');
	
	//Initialize language
	require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/handlers/language_handler.php');
	
	//Initialize minify methods
	require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/helpers/minify_methods.php');
	
	//Initialize common functions
	require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/helpers/common_functions.php');

	//Document begin
	echo "<!DOCTYPE html>";

	/*----------------------------------------------------------------
	-  PRINT: <html> TAG
	---------------------------------------------------------------- */
	$html_other_attr = 'xmlns="http://www.w3.org/1999/xhtml" ';
	$html_other_attr .= 'xml:lang="'.substr(ZHONGFRAMEWORK_WEBSITE_LANGUAGE,0,2).'" ';
	$html_other_attr .= 'lang="'.substr(ZHONGFRAMEWORK_WEBSITE_LANGUAGE,0,2).'" ';
	$html_other_attr .= 'dir="'.ZHONGFRAMEWORK_WEBSITE_TEXT_DIRECTION.'"';
	echo '<!--[if gt IE 8]><!--> <html class="no-js" '.$html_other_attr.'> <!--<![endif]-->';	
	echo '<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" '.$html_other_attr.'> <![endif]-->';
	echo '<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8 ie7" '.$html_other_attr.'> <![endif]-->';
	echo '<!--[if IE 8]> <html class="no-js lt-ie9 ie8" '.$html_other_attr.'> <![endif]-->';
?>

<head>
	<!--[if lt IE 9]>
	<script>
	document.createElement("header");document.createElement("footer");document.createElement("section"); 
	document.createElement("aside");document.createElement("nav");document.createElement("article"); 
	document.createElement("hgroup");document.createElement("time");document.createElement("main");
	</script>
	<![endif]-->
	<?php
	
		/*----------------------------------------------------------------
		-  INCLUDE: DOCUMENT HEAD (CMS HEAD, CSSs, JSs)
		---------------------------------------------------------------- */
		
		//Includes CMS's head component
		$parentCMSHandler->printHead();
		
		//Load CSS styles
		require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/handlers/assets/style_handler.php');
		
		//Load head
		require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/handlers/head_handler.php');
		
	?>
</head>

<!-- BODY -->
<?php
	/*----------------------------------------------------------------
	-  PRINT: <body> TAG | Different classes are applied
	---------------------------------------------------------------- */

	echo '<body class="';
	// Print Layout mode = "default-layout" | "full-access" | "high-contrast" | "mobile-layout"
	echo ZHONGFRAMEWORK_LAYOUT_MODE.' ';
	// Print Graphic mode = "default-graphic-mode" | "best-legibility" | "night-mode"
	if(ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout" || ZHONGFRAMEWORK_LAYOUT_MODE=="mobile-layout"){
		echo ZHONGFRAMEWORK_GRAPHIC_MODE.' ';
		}
	// Print Layout width mode = "full-layout-width" | "fixed-layout-width" | "liquid-layout-width"
	if(ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout"){
		echo ZHONGFRAMEWORK_LAYOUT_WIDTH_MODE.'-layout-width ';
		}
	else{
		echo 'full-layout-width ';
		}
	//Echo client's device
	echo 'client-device-is-'.$ZHONGFRAMEWORK_CLIENT_DEVICE_TYPE.' ';
	//Class for the top menu style
	if(ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout"){
		echo 'main-menu-style_'.ZHONGFRAMEWORK_PARAMETER_MAIN_MENU_STYLE.' ';
		}
	//Class for the side menu style
	if(ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout"){	
		echo 'side-menu-style_'.ZHONGFRAMEWORK_PARAMETER_SIDE_MENU_STYLE.' ';
		}
	//Is the top bar in a fixed position?
	if(ZHONGFRAMEWORK_PARAMETER_ENABLE_FIXED_TOP_BAR=="true" && 
	   ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout" && 
	   $ZHONGFRAMEWORK_CLIENT_DEVICE_TYPE=='desktop'){
		echo 'fixed-top-bar ';
		}
	//If the right/left column exists, add a class for it
	if(ZHONGFRAMEWORK_RIGHT_COLUMN_EXISTS){ echo "right-column-exists "; }
	if(ZHONGFRAMEWORK_LEFT_COLUMN_EXISTS){ echo "left-column-exists "; }
	//If the graphic mode is NOT best legibility, then print the accessible button style (icon or text)
	if(ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout"){
		if(ZHONGFRAMEWORK_GRAPHIC_MODE!="best-legibility")
			echo 'top-bar-buttons_'.ZHONGFRAMEWORK_PARAMETER_ACCESSIBILITY_BUTTON_STYLE.'-style ';
		else
			echo 'top-bar-buttons_text-style ';
		}
	//Presentation alignment (left, right or center) for the website header (logo, title, subtitle)
	echo 'presentation-align-'.ZHONGFRAMEWORK_PARAMETER_PRESENTATION_ALIGNMENT.' ';
	//Is the logo visible?
	echo 'show-logo-'.ZHONGFRAMEWORK_PARAMETER_SHOW_LOGO.' ';
	//If any module is printed in right and/or left columns a different class will be assigned to the content area
	if(ZHONGFRAMEWORK_RIGHT_COLUMN_EXISTS && ZHONGFRAMEWORK_LEFT_COLUMN_EXISTS)
		echo 'main-content-container-width-3 ';
	else{
		if(ZHONGFRAMEWORK_RIGHT_COLUMN_EXISTS || ZHONGFRAMEWORK_LEFT_COLUMN_EXISTS)
			echo 'main-content-container-width-2 ';
		if(!ZHONGFRAMEWORK_RIGHT_COLUMN_EXISTS && !ZHONGFRAMEWORK_LEFT_COLUMN_EXISTS)
			echo 'main-content-container-width-1 ';
		}
	//Sets a class for the left column depending if the right column is present
	if(ZHONGFRAMEWORK_RIGHT_COLUMN_EXISTS)
		echo 'left-column-width-3 ';
	else
		echo 'left-column-width-2 ';
	//Sets a class for the right column depending if the left column is present
	if(ZHONGFRAMEWORK_LEFT_COLUMN_EXISTS)
		echo 'right-column-width-3 ';
	else
		echo 'right-column-width-2 ';
	//Set the float rules for the content/left/right columns according to the chosen order
	if(ZHONGFRAMEWORK_PARAMETER_LAYOUT_COLUMN_ORDER=='rcl' || ZHONGFRAMEWORK_PARAMETER_LAYOUT_COLUMN_ORDER=='crl')
		echo 'main-content-container-column-float-left ';
	else
		echo 'main-content-container-column-float-right ';
	if(ZHONGFRAMEWORK_PARAMETER_LAYOUT_COLUMN_ORDER=='lcr' || ZHONGFRAMEWORK_PARAMETER_LAYOUT_COLUMN_ORDER=='lrc')
		echo 'left-column-float-left ';
	else
		echo 'left-column-float-right ';
	if(ZHONGFRAMEWORK_PARAMETER_LAYOUT_COLUMN_ORDER=='lrc' || ZHONGFRAMEWORK_PARAMETER_LAYOUT_COLUMN_ORDER=='rcl')
		echo 'right-column-float-left ';
	else
		echo 'right-column-float-right ';
	if(ZHONGFRAMEWORK_LAYOUT_MODE=='mobile-layout'){
		echo 'mobile-top-bar-buttons_'.ZHONGFRAMEWORK_PARAMETER_HEADER_MOBILE_STYLE.'-style ';
		}
	if(ZHONGFRAMEWORK_LAYOUT_MODE=='full-access'){
		echo 'full-access_menus-navigation-mode_'.ZHONGFRAMEWORK_PARAMETER_MENUS_NAVIGATION_MODE_FULL_ACCESS.' ';
		}
	if(ZHONGFRAMEWORK_LAYOUT_MODE=='high-contrast'){
		echo 'high-contrast_menus-navigation-mode_'.ZHONGFRAMEWORK_PARAMETER_MENUS_NAVIGATION_MODE_HIGH_CONTRAST.' ';
		}
	if(ZHONGFRAMEWORK_LAYOUT_MODE=='mobile-layout'){
		echo 'mobile_menus-navigation-mode_'.ZHONGFRAMEWORK_PARAMETER_MENUS_NAVIGATION_MODE_MOBILE_LAYOUT.' ';
		}
	echo 'global-layout-width-type_'.ZHONGFRAMEWORK_PARAMETER_GLOBAL_LAYOUT_WIDTH_TYPE.' ';
	//Echo parent CMS name & release version
	echo 'parent-cms-'.ZHONGFRAMEWORK_PARENT_CMS_RELEASE_CLASS.' ';
	// END <body> tag
	echo '">';
	?>

<?php //Clear floating elements - layout hack; TOP OF THE PAGE ?>
<div class="clear-both" id="page-top"></div>

<?php
	/*----------------------------------------------------------------
	-  PRINT: HIDDEN MESSAGES
	---------------------------------------------------------------- */
	//Displays messages only for screenreaders and Internet Esplorer ( if IE <= ver.7 )
	require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/hidden_messages_module.php');
	?>

<?php
	/*----------------------------------------------------------------
	-  PRINT: "JUMP TO CONTENT" ANCHOR
	---------------------------------------------------------------- */
	//If NOT in full access layout then print the "jump to content" anchor
	if(ZHONGFRAMEWORK_LAYOUT_MODE!='full-access' && !ZHONGFRAMEWORK_IS_CLIENT_CRAWLER){
		echo '<p class="wrapper-element"><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.'#main-content-container-inner" id="jump-to-content">'.ZHONGFRAMEWORK_LANGUAGE_JUMP_TO_MAIN_CONTENT.'</a></p>';
		}
	?> 

<?php
	/*----------------------------------------------------------------
	-  PRINT: ANCHORS MENU
	---------------------------------------------------------------- */
	//Print the anchors menu (only in "full access" layout)
	require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/anchors_menu_module.php');
	?>


<?php

	/*----------------------------------------------------------------
	-  PRINT: MOBILE TOP BAR
	---------------------------------------------------------------- */
	//Print the mobile header only in "mobile layout"
	if(ZHONGFRAMEWORK_LAYOUT_MODE=='mobile-layout')	{
		require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/layout-partials/mobile_top_bar.php');
		}


	?>

<?php
	/*----------------------------------------------------------------
	-  PRINT: TOP BAR & ACCESSIBILITY PANEL
	---------------------------------------------------------------- */
	//Print the accessibility bar (containing "BASIC BAR" and "ADVANCED BAR")
	//The accessibility bar is printed on the TOP of the document ONLY in DEFAULT layout and in MOBILE layout.
	if(ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout" || ZHONGFRAMEWORK_LAYOUT_MODE=="mobile-layout"){
		require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/layout-partials/top_bar.php');
		}
	?>

<?php 
	/*----------------------------------------------------------------
	-  LAYOUT CONTAINER BEGINS
	---------------------------------------------------------------- */
 ?>
<!-- LAYOUT CONTAINER -->
<div id="layout-container-outer">
	<div id="gradient-effect"></div>
	<div id="layout-container_zng" 
	     class="main-layout-container <?php echo ZHONGFRAMEWORK_PARAMETER_GLOBAL_LAYOUT_WIDTH_TYPE == 'contained' ? 'layout-width-rail' : ''; ?>">

	<?php
		/*----------------------------------------------------------------
		-  LAYOUT MODs (depending on the layout, different modules are print)
		---------------------------------------------------------------- */
		//If Full Access layout or high contrast layout or mobile layout, then print the website header
		if(ZHONGFRAMEWORK_LAYOUT_MODE=="full-access" || ZHONGFRAMEWORK_LAYOUT_MODE=="high-contrast"){
			require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/website_banner_module.php');
			}
		//If Full Access layout or high contrast layout, then print the breadcrumbs			
		if((ZHONGFRAMEWORK_LAYOUT_MODE=='high-contrast' || ZHONGFRAMEWORK_LAYOUT_MODE=='full-access')&&(ZHONGFRAMEWORK_BREADCRUMB_MOD_EXISTS)){
			require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/breadcrumbs_module.php');
			}
		//If Full Access layout or high contrast layout or mobile layout, then print the section anchors	
		if(ZHONGFRAMEWORK_LAYOUT_MODE=="full-access" || ZHONGFRAMEWORK_LAYOUT_MODE=="high-contrast" || ZHONGFRAMEWORK_LAYOUT_MODE=="mobile-layout" ){
			require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/section_anchors_module.php');
			}
		//If Full Access layout or high contrast layout, then print the accessibility options
		if(ZHONGFRAMEWORK_LAYOUT_MODE=='high-contrast' || ZHONGFRAMEWORK_LAYOUT_MODE=='full-access'){
			require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/layout-partials/top_bar.php');
			echo '<hr class="removed"/>';
			}
		?>

	<!-- HEADER -->
	<?php
		/*----------------------------------------------------------------
		- PRINT: HEADER
		---------------------------------------------------------------- */
		//Print the layout header (containing the top menu, title, logo etc...)
		require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/layout-partials/header.php');
		?>
	<!-- END header -->
	
	<?php 
		/*----------------------------------------------------------------
		- PRINT: MAIN BODY
		---------------------------------------------------------------- */
	 ?>
	<!-- MAIN BODY -->
	<div id="main-body" class="clear-both">
	<div id="main-body-inner" class="<?php echo ZHONGFRAMEWORK_PARAMETER_GLOBAL_LAYOUT_WIDTH_TYPE == 'contained' ? '' : 'layout-width-rail'; ?>">
		
		<?php
		//Include user layout width resize module (in this position only in 'stretched' layout type)
		if(ZHONGFRAMEWORK_PARAMETER_GLOBAL_LAYOUT_WIDTH_TYPE=='stretched'){
			require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/user_layout_width_resize_module.php');	
			}
			?>
		
		<?php
			/*----------------------------------------------------------------
			- PRINT: FIRST group of "user-mod" (custom-1-#)
			---------------------------------------------------------------- */
			//NOTE that this "user-mod" block will be printed BEFORE the left & right column ONLY in DEFAULT layout mode
			if(ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout" && ZHONGFRAMEWORK_CUSTOM_MODULES_BLOCK_1_EXISTS){
				$custom_module_index=1;
				$custom_module_layout_block=1;
				$isUserModRoleComplementary=true;
				require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/custom_modules_print.php');
				}
			?>		
		
		<?php
			/*----------------------------------------------------------------
			- PRINT: LEFT COLUMN
			---------------------------------------------------------------- */
			//If left column exists, then print it!
			if(ZHONGFRAMEWORK_LEFT_COLUMN_EXISTS):
			?>
			<!-- LEFT COLUMN -->
			<?php require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/layout-partials/left_column.php'); ?>
			<!-- END left column -->
		<?php endif; ?>
		
		<?php
			/*----------------------------------------------------------------
			- PRINT: RIGHT COLUMN
			---------------------------------------------------------------- */
			//If right column exists, then print it!
			if(ZHONGFRAMEWORK_RIGHT_COLUMN_EXISTS):
			?>
			<!-- RIGHT COLUMN -->
			<?php require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/layout-partials/right_column.php'); ?>
			<!-- END right column -->
		<?php endif; ?>
		
		<!-- CONTENT AREA -->
		<?php
			/*----------------------------------------------------------------
			- PRINT: CONTENT AREA
			---------------------------------------------------------------- */
			require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/layout-partials/content_area.php');
		?>
		<!-- END main-content-container -->

		<?php
		//Print the "Top" anchor
		require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/anchors_after_content_module.php'); ?>

	</div></div>
	<!-- END main-body -->

	<!-- FOOTER -->
	<?php
		/*----------------------------------------------------------------
		- PRINT: FOOTER
		---------------------------------------------------------------- */
		require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/layout-partials/footer.php');
	?>
	<!-- END footer -->
	
	<?php
		//Include user layout width resize module (in this position only in 'contained' layout type)
		if(ZHONGFRAMEWORK_PARAMETER_GLOBAL_LAYOUT_WIDTH_TYPE=='contained'){
			require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/user_layout_width_resize_module.php');	
			}
	?>
	
	</div></div>
	<!-- END layout-container && layout-container-outer -->
	
	<?php
		//Load debug position
		$parentCMSHandler->printDebugModule();
		?>
	
	<?php //Clear bottom layout ?>
	<div class="clear-both" style="height:1px"></div>
	
	<!-- Footer Javascript -->
	<?php
		/*----------------------------------------------------------------
		- INCLUDE: FOOTER JAVASCRIPTs
		---------------------------------------------------------------- */
		require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/handlers/assets/javascript_footer_handler.php');
	?>
	<!-- END footer Javascript -->
	
</body>
</html>