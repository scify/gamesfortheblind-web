<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

if(ZHONGFRAMEWORK_LANGUAGE_SWITCHER_MOD_EXISTS) : ?>

	<!-- LANGUAGE SWITCHER -->
	<div id="language-switcher-outer">
	
		<nav role="navigation">
		
			<?php //Print section heading
			printSectionHeading(ZHONGFRAMEWORK_LANGUAGE_LANGUAGE_OPTIONS,2,$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['languageOptions'],false,''); ?>
			
			<div id="language-switcher-inner">
				<?php $parentCMSHandler->printLanguageSwitcherModule(); ?>
			</div>
			
		</nav>
	
	</div>
	<!-- END language switcher -->
	
	<?php //Print internal anchors
	require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/section_anchors_module.php'); ?>
	
<?php endif; ?>
