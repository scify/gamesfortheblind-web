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

<?php
/*----------------------------------------------------------------
-  Load the FIRST group of "user-mod" (custom-1-#) ONLY if layout mode is DIFFERENT than DEFAULT
---------------------------------------------------------------- */
if(ZHONGFRAMEWORK_LAYOUT_MODE!="default-layout" && ZHONGFRAMEWORK_CUSTOM_MODULES_BLOCK_1_EXISTS){
	$custom_module_index=1;
	$custom_module_layout_block=1;
	$isUserModRoleComplementary=true;
	require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/custom_modules_print.php');
	require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/section_anchors_module.php');
	}
	?>

<?php
/*==========================================================================
   MAIN CONTENT AREA
==========================================================================*/
?>

<div id="main-content-container">

	<main role="main"><section>
		
		<?php //Print section heading
		printSectionHeading(ZHONGFRAMEWORK_LANGUAGE_MAIN_CONTENT,1,$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['mainContent'],false,'page-content-section-heading'); ?>
		
		<div id="main-content-container-inner">

			<?php
			/*----------------------------------------------------------------
			-  //Load the SECOND group of "user-mod" (custom-2-#)
			---------------------------------------------------------------- */
			if(ZHONGFRAMEWORK_CUSTOM_MODULES_BLOCK_2_EXISTS){
				$custom_module_index=11;
				$custom_module_layout_block=2;
				$isUserModRoleComplementary=false;
				require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/custom_modules_print.php');
				}
				?>
	
			<div id="main-article-container"><article role="article">
				
				<?php
				//Show possible pre-content (e.g. error messages)
				$parentCMSHandler->printPreDocumentContent();
				?>
	
				<?php
				/*----------------------------------------------------------------
				-  PRINT MAIN ARTICLE
				---------------------------------------------------------------- */
				$parentCMSHandler->printMainDocumentContent();
				//Just an Easter egg :P
				if(ZHONGFRAMEWORK_PARAMETER_TITLE=="frankie"){require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'info/credits.html');}
				?>
	
			</article></div>

		</div>

	</section></main>

</div>

<?php require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/section_anchors_module.php'); ?>
<div class="clear-both"></div>

<?php
/*----------------------------------------------------------------
-  //Load the THIRD group of "user-mod" (custom-3-#)
---------------------------------------------------------------- */
if(ZHONGFRAMEWORK_CUSTOM_MODULES_BLOCK_3_EXISTS){
	$custom_module_index=21;
	$custom_module_layout_block=3;
	$isUserModRoleComplementary=true;
	require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/custom_modules_print.php');
	require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/section_anchors_module.php');
	}
	?>