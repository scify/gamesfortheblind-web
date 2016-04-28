<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

if(ZHONGFRAMEWORK_MAIN_MENU_MOD_EXISTS) : ?>

<!-- TOP NAVIGATION -->
<nav role="navigation">

	<?php //Print section heading
	printSectionHeading(ZHONGFRAMEWORK_LANGUAGE_MAIN_MENU,2,$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['topMenu'],false,''); ?>

	<div id="main-menu-container" class="menu-container">
	<div id="main-menu-container-inner" 
	     class="<?php echo ZHONGFRAMEWORK_PARAMETER_GLOBAL_LAYOUT_WIDTH_TYPE == 'contained' ? '' : 'layout-width-rail'; ?>">

		<?php $parentCMSHandler->printTopMenuModule(); ?>

	</div></div>

</nav>
<!-- END top navigation -->

<?php //Print internal anchors
require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/section_anchors_module.php'); ?>

<?php endif; ?>