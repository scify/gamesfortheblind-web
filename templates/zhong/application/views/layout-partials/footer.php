<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

/*==========================================================================
   FOOTER
==========================================================================*/
//Load the footer only if it is enabled (by the backend) AND is not empty
if(ZHONGFRAMEWORK_FOOTER_EXISTS):
?>

<footer>
<div id="footer-wrapper">
<div id="footer-wrapper-inner" class="<?php echo ZHONGFRAMEWORK_PARAMETER_GLOBAL_LAYOUT_WIDTH_TYPE == 'contained' ? '' : 'layout-width-rail'; ?>">
	
	<?php
	/*----------------------------------------------------------------
	-  FOOTER MODULES & CREDITS
	---------------------------------------------------------------- */
	if(ZHONGFRAMEWORK_FOOTER_CONTENT_EXISTS) : ?>
		
		<section>
		
			<?php //Print section heading
			printSectionHeading(ZHONGFRAMEWORK_LANGUAGE_FOOTER,2,$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['footer'],false,''); ?>
		
			<div id="footer" class="clear-both"><div id="footer-inner">
				
				<?php //Load the FORTH group of "user-mod" (custom-4-A/40)
					if(ZHONGFRAMEWORK_CUSTOM_MODULES_BLOCK_4_EXISTS){
						$custom_module_index=31;
						$custom_module_layout_block=4;
						$isUserModRoleComplementary=false;
						require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/custom_modules_print.php');
						}
					?>
				
				<?php // Load footer-credits position
					if(ZHONGFRAMEWORK_FOOTER_CREDITS_MOD_EXISTS) : ?>
					<div id="footer-credits" class="clear-both" role="contentinfo">
						<?php $parentCMSHandler->printFooterCreditsModule(); ?>
					</div>
				<?php endif;?>
				
			</div></div>

		</section>

	<?php endif;?>

	<?php
	/*----------------------------------------------------------------
	-  FOOTER MENU
	---------------------------------------------------------------- */
	if(ZHONGFRAMEWORK_FOOTER_MENU_MOD_EXISTS) : ?>
	
		<nav role="navigation">
		
			<?php //Print section heading
			printSectionHeading(ZHONGFRAMEWORK_LANGUAGE_FOOTER_MENU,2,$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['footerMenu'],false,''); ?>
			
			<div id="footer-menu" class="clear-both menu-container"><div id="footer-menu-inner">
				<?php $parentCMSHandler->printFooterMenuModule(); ?>
			</div></div>
			
		</nav>

	<?php endif;?>
	
	<?php require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/section_anchors_module.php'); ?>
		
</div></div></footer>

<?php endif; //END "if footer enabled" condition ?>

