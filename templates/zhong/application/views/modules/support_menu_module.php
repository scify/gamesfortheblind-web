<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

if(ZHONGFRAMEWORK_SUPPORT_MENU_MOD_EXISTS): ?>
	
	<!-- HEADER MENU -->
	<div id="support-menu-outer">
		<nav role="navigation">
			
			<?php //Print section heading
			printSectionHeading(ZHONGFRAMEWORK_LANGUAGE_SUPPORT_MENU,2,$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['headerMenu'],false,''); ?>

			<?php if(ZHONGFRAMEWORK_SUPPORT_MENU_MOD_EXISTS): ?>
				<div id="support-menu-inner" class="menu-container">
					
					<?php $parentCMSHandler->printHeaderMenuModule() ?>
					
				</div>
			<?php endif; ?>
		
		</nav>
	</div>
	<!-- END header menu -->
	
	<?php require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/section_anchors_module.php'); ?>
	
<?php endif; ?>