<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

//Get the heading level for the modules on the accessibility panel
if(ZHONGFRAMEWORK_PARAMETER_HEADINGS_LEVEL_MODE=='HTML4'){
	$accessibilityPanelHeadingsLevel = '3';
	}
if(ZHONGFRAMEWORK_PARAMETER_HEADINGS_LEVEL_MODE=='HTML5'){
	$accessibilityPanelHeadingsLevel = '2';
	}

if($ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['modulesOnAccessibilityPanel']!='default'){
	$accessibilityPanelHeadingsLevel = $ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['modulesOnAccessibilityPanel'];
	}

?>

<?php if(ZHONGFRAMEWORK_PARAMETER_ENABLE_TOP_BAR=="true" && ZHONGFRAMEWORK_LAYOUT_MODE!="mobile-layout" && !ZHONGFRAMEWORK_IS_CLIENT_CRAWLER): ?>
	<!-- TOP LAYOUT CONTAINER - containing "TOP BAR" and "ACCESSIBILITY PANEL" -->
	<div id="top-layout-container"><div id="top-layout-container-inner">

	<?php
	/*==========================================================================
	   TOP BAR
	==========================================================================*/
	if(ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout"): // displays basic bar only in default layout mode?>
	
		<!-- BASIC BAR -->
		<div id="top-bar">
			<div id="top-bar-middle" class="layout-width-rail">
				<div id="top-bar-inner">
				
				<?php //Print the Breadcrumbs in the accessibility bar
					require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/breadcrumbs_module.php');
					?>
				
				<div id="top-bar-tools-container" class="top-bar-module">
				


					<?php
					/*----------------------------------------------------------------
					-  MOBILE LAYOUT and NIGHT MODE SWITCHERS
					---------------------------------------------------------------- */
					if( ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout"
					&& (ZHONGFRAMEWORK_PARAMETER_ENABLE_MOBILE_LINK=="true" || ZHONGFRAMEWORK_PARAMETER_ENABLE_NIGHT_LINK=="true")
					&& (ZHONGFRAMEWORK_GRAPHIC_MODE=='default-graphic-mode' || ZHONGFRAMEWORK_GRAPHIC_MODE=='night-mode')): ?>
						
						<?php if(ZHONGFRAMEWORK_PARAMETER_ENABLE_MOBILE_LINK=="true"){ // Print mobile mode link ?>
						<div class="top-bar-tool">
							<a href="<?php echo ZHONGFRAMEWORK_WEBSITE_CURRENT_URI;?>?layoutMode=mobile" rel="nofollow" title="<?php echo ZHONGFRAMEWORK_LANGUAGE_MOBILE_LAYOUT_TITLE; ?>" id="mobile-mode-link" class="show-tooltip">
								<span class="top-bar-tool-text"><?php echo ZHONGFRAMEWORK_LANGUAGE_MOBILE_LAYOUT_CONTENT; ?></span>
								<span class="zhongframework-icon zhongframework-icon-mobile" aria-hidden="true"></span>
							</a>
						</div>
						<?php } ?>
						
						<?php //Print night/day mode link
							if(ZHONGFRAMEWORK_PARAMETER_ENABLE_NIGHT_LINK=="true" && ZHONGFRAMEWORK_GRAPHIC_MODE=='default-graphic-mode'){
								echo '<div class="top-bar-tool"><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI.'?graphicMode=night" rel="nofollow" ';
								echo 'title="'.ZHONGFRAMEWORK_LANGUAGE_NIGHT_MODE_TITLE.'" id="night-mode-switcher" class="show-tooltip">';
								echo '<span class="top-bar-tool-text">'.ZHONGFRAMEWORK_LANGUAGE_NIGHT_MODE_CONTENT.'</span>';
								echo '<span class="zhongframework-icon zhongframework-icon-night-mode" aria-hidden="true"></span>';
								echo '</a></div>';
								}
							if(ZHONGFRAMEWORK_PARAMETER_ENABLE_NIGHT_LINK=="true" && ZHONGFRAMEWORK_GRAPHIC_MODE=='night-mode'){
								echo '<div class="top-bar-tool"><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI.'?graphicMode=day" rel="nofollow" ';								
								echo 'title="'.ZHONGFRAMEWORK_LANGUAGE_DAY_MODE_TITLE.'" id="night-mode-switcher" class="show-tooltip">';
								echo '<span class="top-bar-tool-text">'.ZHONGFRAMEWORK_LANGUAGE_DAY_MODE_CONTENT.'</span>';
								echo '<span class="zhongframework-icon zhongframework-icon-day-mode" aria-hidden="true"></span>';
								echo '</a></div>';
								}

							?>
						
					<?php endif; ?>


	
					<?php
					/*----------------------------------------------------------------
					-  "ACCESSIBILITY" BUTTON
					---------------------------------------------------------------- */
					//Shows the "accessibility button" ( only if javascript is enabled and only in default layout mode )
					if(ZHONGFRAMEWORK_PARAMETER_ENABLE_ACCESSIBILITY_PANEL=="true"){ ?>
						<script id="show-accessibility-panel-button-container_template" type="text/template">
							<div id="show-accessibility-panel-button-container" class="top-bar-tool">
								<button class="show-tooltip" id="show-accessibility-panel-button" title="<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_ADVANCED_HIDDEN_TITLE; ?>">
									<span class="top-bar-tool-text"><?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_ADVANCED_HIDDEN_CONTENT; ?></span>
									<span class="zhongframework-icon zhongframework-icon-accessibility" aria-hidden="true"></span>									
								</button>
							</div>
						</script>
						<script type="text/javascript">
							document.write(jQuery('#show-accessibility-panel-button-container_template').html());
						</script>
						<noscript></noscript>
					<?php } ?>
				</div>
				<!-- END top blocks container -->

				</div>
			</div>
		</div>
		<!-- END top bar -->
		
	<?php endif; ?>

	<?php
	/*==========================================================================
	   ACCESSIBILITY PANEL
	==========================================================================*/
	//Show the accessibility panel only if enabled and not in mobile layout
	if(ZHONGFRAMEWORK_PARAMETER_ENABLE_ACCESSIBILITY_PANEL=="true" && ZHONGFRAMEWORK_LAYOUT_MODE!="mobile-layout"): ?>
		
		<!-- ACCESSIBILITY PANEL -->
		<div id="accessibility-panel" aria-atomic="true" aria-live="polite"><section>
			
			<?php printSectionHeading(ZHONGFRAMEWORK_LANGUAGE_ACCESSIBILITY_OPTIONS,2,$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['accessibilityOptions'],false,''); ?>
			
			<div id="accessibility-panel-inner" class="layout-width-rail">
			<?php
				/*----------------------------------------------------------------
				-  LAYOUT MODE SWITCHER
				---------------------------------------------------------------- */
				if( ZHONGFRAMEWORK_PARAMETER_ENABLE_LAYOUT_MODES_MENU=="true"
				&& ZHONGFRAMEWORK_LAYOUT_MODE!='mobile-layout'){ 
					require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/layout_mode_switcher_module.php');
					}
				
				/*----------------------------------------------------------------
				-  FONT RESIZER
				---------------------------------------------------------------- */
				if( ZHONGFRAMEWORK_PARAMETER_ENABLE_FONT_RESIZER=="true"
				&& ZHONGFRAMEWORK_LAYOUT_MODE!='full-access'
				&& ZHONGFRAMEWORK_LAYOUT_MODE!='high-contrast' ){
					require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/font_resizer_module.php');
					}
				
				echo '<div class="clear-both"></div>';
				
				/*----------------------------------------------------------------
				-  GRAPHIC MODE SWITCHER
				---------------------------------------------------------------- */
				if(ZHONGFRAMEWORK_PARAMETER_ENABLE_GRAPHIC_MODES_MENU=="true"
				&& ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout"){
					require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/graphic_mode_switcher_module.php');
					}
				
				/*----------------------------------------------------------------
				-  LAYOUT MODE SWITCHER
				---------------------------------------------------------------- */
				if(ZHONGFRAMEWORK_PARAMETER_ENABLE_TOGGLE_LAYOUT_WIDTH=="true"
				&& ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout"){
					require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/layout_width_switcher_module.php');
					}
				
				//Additional user module
				if(ZHONGFRAMEWORK_ACCESSIBILITY_PANEL_CUSTOM_MODULE_MOD_EXISTS) : ?>
					<div class="clear-both"></div>
					<div id="accessibility-panel-module_custom">
						<?php $parentCMSHandler->printAccessibilityPanelCustomModule(); ?>
					</div>
				<?php endif; ?>
				
				<div class="clear-both"></div>

			</div>

		</section></div>
		<!-- END accessibility panel -->
		
		<?php require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/section_anchors_module.php'); ?>
			
	<?php endif; ?>
		
	</div></div>
	<!-- END top-layout-container & top-layout-container-inner -->

<?php endif; ?>