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

<aside role="complementary">

	<?php printSectionHeading(ZHONGFRAMEWORK_LANGUAGE_ADDITIONAL_RESOURCES_RIGHT_COLUMN,2,$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['additionalResourcesRight'],false,''); ?>

	<div id="right-column" class="side-column">
		<div id="right-column-inner">
			<div id="right-additional" class="custom-module-style_<?php echo $ZHONGFRAMEWORK_PARAMETER_MAINMODULES_STYLE['right-column']; ?>"> 
				<?php $parentCMSHandler->printRightColumnModule(); ?>
			</div>
		</div>
	</div>

</aside>

<?php require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/section_anchors_module.php'); ?>
