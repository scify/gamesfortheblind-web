<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

if(ZHONGFRAMEWORK_SEARCH_MOD_EXISTS) : ?>

<!-- SEARCH BLOCK -->
<div id="search-module-outer">

	<section role="search">

		<?php //Print section heading
		printSectionHeading(ZHONGFRAMEWORK_LANGUAGE_SEARCH,2,$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['search'],false,''); ?>

		<div id="search-module-inner">
			<?php $parentCMSHandler->printSearchModule(); ?>
		</div>

	</section>

</div>
<!-- END search block -->
<?php endif; ?>

<?php require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/section_anchors_module.php'); ?>