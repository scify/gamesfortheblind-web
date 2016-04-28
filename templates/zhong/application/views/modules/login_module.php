<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
 
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

if(ZHONGFRAMEWORK_LOGIN_MOD_EXISTS) : ?>

<!-- LOGIN MOD -->
<section>

	<?php printSectionHeading(ZHONGFRAMEWORK_LANGUAGE_LOGIN,2,$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['login'],false,''); ?>

	<div id="login-module" class="custom-module-style_<?php echo $ZHONGFRAMEWORK_PARAMETER_MAINMODULES_STYLE['login']; ?>">
		<?php $parentCMSHandler->printLoginModule(); ?>
	</div>
	
</section>
<!-- END login mod -->

<?php //Print internal anchors
require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/section_anchors_module.php'); ?>

<?php endif; ?>