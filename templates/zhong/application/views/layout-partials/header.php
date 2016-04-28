<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );
?>

<!-- WEBSITE HEADER -->
<div id="header" class="clear-both"><div id="header-inner">

	<?php
	/*==========================================================================
	   HEADER-TOP: Website help, mobile mode & site map, language switcher and search module
	==========================================================================*/
	if(ZHONGFRAMEWORK_SUPPORT_MENU_MOD_EXISTS || ZHONGFRAMEWORK_LANGUAGE_SWITCHER_MOD_EXISTS || ZHONGFRAMEWORK_SEARCH_MOD_EXISTS): ?>
	
	<!-- HEADER TOP -->
	<div id="header-top">
	<div id="header-top-inner" class="<?php echo ZHONGFRAMEWORK_PARAMETER_GLOBAL_LAYOUT_WIDTH_TYPE == 'contained' ? '' : 'layout-width-rail'; ?>">
	
		<?php
		/*----------------------------------------------------------------
		-  SUPPORT MENU
		---------------------------------------------------------------- */
		require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/support_menu_module.php');?>

		<?php
		/*----------------------------------------------------------------
		-  LANGUAGE SWITCHER MENU (print here only if not mobile)
		---------------------------------------------------------------- */
		if(ZHONGFRAMEWORK_LAYOUT_MODE!='mobile-layout' ){
			require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/language_switcher_module.php');
			} ?>

		<?php
		/*----------------------------------------------------------------
		-  SEARCH MODULE (print here only if not mobile)
		---------------------------------------------------------------- */
		if(ZHONGFRAMEWORK_LAYOUT_MODE!='mobile-layout' ){
			require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/search_module.php');
			} ?>

	</div></div>
	<div class="clear-both"></div>
	<!-- END header-top -->
	
	<?php endif; ?>

	<?php
	/*==========================================================================
	   WEBSITE PRESENTATION
	==========================================================================*/
	//Print the WEBSITE PRESENTATION here only in default layout & mobile layout
	if(ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout" || ZHONGFRAMEWORK_LAYOUT_MODE=='mobile-layout' ){
		require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/website_banner_module.php');
		}
	?>
	<div class="clear-both"></div>

	<?php
	/*==========================================================================
	   MAIN MENU
	==========================================================================*/	
	//Load the main-menu position
	if(ZHONGFRAMEWORK_MAIN_MENU_MOD_EXISTS){
		require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/main_menu_module.php');
		echo '<div class="clear-both"></div>';
		} ?>

</div></div>
<!-- END website header -->