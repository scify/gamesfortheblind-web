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

<!-- TOP BAR, MOBILE -->
<div id="top-layout-container"><div id="top-layout-container-inner">
	
	<div id="top-bar"><div id="top-bar-middle"><div id="top-bar-inner">

		<?php
		/*----------------------------------------------------------------
		-  MOBILE GRAPHIC SWITCHER (Desktop layout and night mode)
		---------------------------------------------------------------- */
		?>
		<div id="mobile-top-bar-graphic-switcher-container" class="mobile-top-bar-buttons-container top-bar-module"><section>
			<?php

			//Print the section header
			printSectionHeading(ZHONGFRAMEWORK_LANGUAGE_MOBILE_LAYOUT_OPTIONS_HEADING,2,2,false,'');
			
			echo '<ul class="list-reset">';

			//Print the "default layout" link 
			echo '<li>';
			echo '<a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI.'?layoutMode=default" rel="nofollow" title="'.ZHONGFRAMEWORK_LANGUAGE_DESKTOP_LAYOUT_TITLE.'" id="default-mode-switcher" class="mobile-top-bar-tool-button">';
			echo '<span class="visually-hidden">'.ZHONGFRAMEWORK_LANGUAGE_DESKTOP_LAYOUT_CONTENT.'</span>';
			echo '<span class="zhongframework-icon zhongframework-icon-default-layout" aria-hidden="true"></span>';			
			echo '</a>';
			echo '</li>';
			
			//Print night/day mode link
			if(ZHONGFRAMEWORK_PARAMETER_ENABLE_NIGHT_LINK_MOBILE=="true" && ZHONGFRAMEWORK_GRAPHIC_MODE=='default-graphic-mode'){
				echo '<li><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI.'?graphicMode=night" rel="nofollow" role="button" ';
				echo 'title="'.ZHONGFRAMEWORK_LANGUAGE_NIGHT_MODE_TITLE.'" id="night-mode-switcher" class="mobile-top-bar-tool-button">';
				echo '<span class="top-bar-tool-text visually-hidden">'.ZHONGFRAMEWORK_LANGUAGE_NIGHT_MODE_CONTENT.'</span>';
				echo '<span class="zhongframework-icon zhongframework-icon-night-mode" aria-hidden="true"></span>';
				echo '</a></li>';								
				}
			if(ZHONGFRAMEWORK_PARAMETER_ENABLE_NIGHT_LINK_MOBILE=="true" && ZHONGFRAMEWORK_GRAPHIC_MODE=='night-mode'){
				echo '<li><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI.'?graphicMode=day" rel="nofollow" role="button" ';								
				echo 'title="'.ZHONGFRAMEWORK_LANGUAGE_DAY_MODE_TITLE.'" id="night-mode-switcher" class="mobile-top-bar-tool-button">';
				echo '<span class="top-bar-tool-text visually-hidden">'.ZHONGFRAMEWORK_LANGUAGE_DAY_MODE_CONTENT.'</span>';
				echo '<span class="zhongframework-icon zhongframework-icon-day-mode" aria-hidden="true"></span>';
				echo '</a></li>';								
				}

			echo '</ul>';
			?>
		</section></div>
	
		<?php
		/*----------------------------------------------------------------
		-  MOBILE TOP BAR TOOLS
		---------------------------------------------------------------- */
		?>
		<div id="mobile-top-bar-tools-container" class="mobile-top-bar-buttons-container"><section>
			<?php
				
				//Print the section header
				printSectionHeading(ZHONGFRAMEWORK_LANGUAGE_TOP_TOOLBAR,2,2,false,'');
				
				echo '<ul class="list-reset">';
				
				//Font-resizer button
				echo '<li><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.'#mobile-top-bar_module-container_fontsize" id="mobile-top-bar-tool_fontsize-button" class="mobile-top-bar-tool-button" role="button">';
				echo '<span class="visually-hidden">'.ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_FONT_HEADER.'</span>';
				echo '<span class="zhongframework-icon zhongframework-icon-font-resize" aria-hidden="true"></span>';
				echo '</a></li>';
				
				//Login button
				if(ZHONGFRAMEWORK_LOGIN_MOD_EXISTS) { /* if the login module is activated, Print an anchor to the form */
					echo '<li><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.'#mobile-top-bar_module-container_login" id="mobile-top-bar-tool_login-button" class="mobile-top-bar-tool-button" role="button">';
					echo '<span class="visually-hidden">'.ZHONGFRAMEWORK_LANGUAGE_LOGIN.'</span>';
					echo '<span class="zhongframework-icon zhongframework-icon-login" id="mobile-top-bar-tool_login-icon" aria-hidden="true"></span>';			
					echo '</a></li>';
					}
				
				//Search button
				if(ZHONGFRAMEWORK_SEARCH_MOD_EXISTS){
					echo '<li><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.'#mobile-top-bar_module-container_search" id="mobile-top-bar-tool_search-button" class="mobile-top-bar-tool-button" role="button">';
					echo '<span class="visually-hidden">'.ZHONGFRAMEWORK_LANGUAGE_SEARCH.'</span>';
					echo '<span class="zhongframework-icon zhongframework-icon-search" aria-hidden="true"></span>';					
					echo '</a></li>';
					}
				
				//Language switcher button
				if(ZHONGFRAMEWORK_LANGUAGE_SWITCHER_MOD_EXISTS){
					echo '<li><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.'#mobile-top-bar_module-container_language" id="mobile-top-bar-tool_language-button" class="mobile-top-bar-tool-button" role="button">';
					echo '<span class="visually-hidden">'.ZHONGFRAMEWORK_LANGUAGE_LANGUAGE_OPTIONS.'</span>';
					echo '<span class="zhongframework-icon zhongframework-icon-language" aria-hidden="true"></span>';					
					echo '</a></li>';
					}
				
				echo '</ul>';
			?>
		</section></div>
	
	</div></div></div>
	<!-- END top bar -->
	
	<hr class="removed"/>
	
	<?php
	/*----------------------------------------------------------------
	-  SEARCH/LOGIN/LANGUAGE/FONT-RESIZER MODULES
	---------------------------------------------------------------- */
	
	//Font-resizer module
	if( ZHONGFRAMEWORK_PARAMETER_ENABLE_FONT_RESIZER=="true"){
		echo '<div id="mobile-top-bar_module-container_fontsize" class="mobile-top-bar_module-container custom-module-style_light" aria-atomic="true" aria-live="polite">';
		//Set the header level and and print the module
		$accessibilityPanelHeadingsLevel = '3';
		require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/font_resizer_module.php');
		echo '</div>';
		}
	
	//Search module:
	if(ZHONGFRAMEWORK_SEARCH_MOD_EXISTS){
		echo '<div id="mobile-top-bar_module-container_search" class="mobile-top-bar_module-container custom-module-style_light" aria-atomic="true" aria-live="polite">';
		require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/search_module.php');
		echo '</div>';
		}
	
	//Login module:
	if(ZHONGFRAMEWORK_LOGIN_MOD_EXISTS){
		echo '<div id="mobile-top-bar_module-container_login" class="mobile-top-bar_module-container custom-module-style_light" aria-atomic="true" aria-live="polite">';
		require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/login_module.php');
		echo '</div>';
		}
	
	//Language switcher module
	if(ZHONGFRAMEWORK_LANGUAGE_SWITCHER_MOD_EXISTS){
		echo '<div id="mobile-top-bar_module-container_language" class="mobile-top-bar_module-container custom-module-style_light" aria-atomic="true" aria-live="polite">';
		require(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/views/modules/language_switcher_module.php');
		echo '</div>';
		}
	?>

</div></div>
<!-- END top-layout-container -->
