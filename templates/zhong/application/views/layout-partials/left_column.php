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

<div id="left-column" class="side-column">
	<div id="left-column-inner">
	
		<?php
		/*----------------------------------------------------------------
		-  SIDE MENU
		---------------------------------------------------------------- */
		if(ZHONGFRAMEWORK_SIDE_MENU_EXISTS) : ?>
		<!-- SIDE MENU -->
			<nav role="navigation">
				<?php printSectionHeading(ZHONGFRAMEWORK_LANGUAGE_SIDE_MENU,2,$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['mainMenu'],false,''); ?>
				<div id="side-menu" class="menu-container custom-module-style_<?php echo $ZHONGFRAMEWORK_PARAMETER_MAINMODULES_STYLE['side-menu']; ?>">
					<?php $parentCMSHandler->printMainMenuModule(); ?>
				</div>
			</nav>
			<?php require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/section_anchors_module.php'); ?>
		<!-- END side menu -->
		<?php endif; ?>
		
		<?php
		/*----------------------------------------------------------------
		-  LEFT MODULE
		---------------------------------------------------------------- */
		if(ZHONGFRAMEWORK_LEFT_MOD_EXISTS) : ?>
		<!-- ASIDE (LEFT COLUMN) -->
			<aside role="complementary">
				<?php printSectionHeading(ZHONGFRAMEWORK_LANGUAGE_ADDITIONAL_RESOURCES_LEFT_COLUMN,2,$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['additionalResourcesLeft'],false,''); ?>
				<div id="left-additional" class="custom-module-style_<?php echo $ZHONGFRAMEWORK_PARAMETER_MAINMODULES_STYLE['left-column']; ?>">
					<?php $parentCMSHandler->printLeftColumnModule(); ?>
				</div>
			</aside>
			<?php require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/section_anchors_module.php'); ?>
		<!-- END aside (left column) -->
		<?php endif; ?>
	
		<?php
		/*----------------------------------------------------------------
		-  LOGIN MODULE
		---------------------------------------------------------------- */
			//If not mobile layout, print the LOGIN MODULE ( in the mobile layout it is print in he header of the page )
			if(ZHONGFRAMEWORK_LOGIN_MOD_EXISTS && ZHONGFRAMEWORK_LAYOUT_MODE!='mobile-layout' ) {
				require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/login_module.php');
				}
		?>

	</div>
</div>
