<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
	defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );
	
	//Open the <header> tag
	echo '<!-- WEBSITE BANNER -->';
	echo '<header role="banner"><div id="website-banner">';
	echo '<div id="website-banner-middle" class="';
	echo ZHONGFRAMEWORK_PARAMETER_GLOBAL_LAYOUT_WIDTH_TYPE == 'contained' ? '' : 'layout-width-rail';
	echo '">';
	echo '<div id="website-banner-inner">';	
	
	/*==========================================================================
	   LOGO
	==========================================================================*/
	if(ZHONGFRAMEWORK_PARAMETER_SHOW_LOGO=="true"){
		echo'<div id="logo-wrap">';
		
		/*----------------------------------------------------------------
		-  Is the logo a heading?
		---------------------------------------------------------------- */
		if($ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['websiteLogo']!='no-heading'){
			//If yes, print the heading tag (for example, <h1>)
			echo '<'.$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['websiteLogo'].' class="wrapper-element">';
			}
		else{
			//If the title is disabled, make the logo the document's main heading
			if(ZHONGFRAMEWORK_PARAMETER_SHOW_TITLE=="false"){
				echo '<h1 class="wrapper-element">';
				}
			}
		
		/*----------------------------------------------------------------
		-  Is the logo a link to the homepage?
		---------------------------------------------------------------- */
		if(ZHONGFRAMEWORK_PARAMETER_ENABLE_LOGO_LINK=="true"){
			echo '<a href="'.ZHONGFRAMEWORK_WEBSITE_BASE_URI.'index.php" title="'.ZHONGFRAMEWORK_LANGUAGE_HOMEPAGE.'" id="logo-wrap-link" class="show-tooltip">';
			}
		
		/*----------------------------------------------------------------
		-  Print the image logo path
		---------------------------------------------------------------- */
		echo '<img src="';
		//If the "high contrast logo" is set, then print it!
		if(ZHONGFRAMEWORK_PARAMETER_SHOW_LOGO_HIGH_VISIBILITY=='true' && 
		   ZHONGFRAMEWORK_LAYOUT_MODE=='high-contrast' && 
		   ZHONGFRAMEWORK_PARAMETER_LOGO_PATH_HIGH_VISIBILITY!=''){
		   echo ZHONGFRAMEWORK_PARAMETER_LOGO_PATH_HIGH_VISIBILITY;
		   }
		//else:
		else{
			//If no logo is set, keep the default logo ( in the template directory )
			if( ZHONGFRAMEWORK_PARAMETER_LOGO_PATH=="" ){
				echo ZHONGFRAMEWORK_WEBSITE_TEMPLATE_URI.'/assets/images/logo.png'; }
			//else, print the selected one
			else { echo ZHONGFRAMEWORK_PARAMETER_LOGO_PATH; }
			}
		
		/*----------------------------------------------------------------
		-  ALT attribute
		---------------------------------------------------------------- */
		//[If] the alt attribute is empty and NO title is set (this means that only a logo is showed)
		//[OR] If the logo is a link and the alt attribute is empty
		//[Then] print the website name as the alt attribute
		if((ZHONGFRAMEWORK_PARAMETER_ALT_LOGO=='' && ZHONGFRAMEWORK_PARAMETER_SHOW_TITLE=="false") ||
		   (ZHONGFRAMEWORK_PARAMETER_ALT_LOGO=='' && ZHONGFRAMEWORK_PARAMETER_ENABLE_LOGO_LINK=="true")){
			//then set the ALT attribute as the name of the site: 
			echo '" alt="'.ZHONGFRAMEWORK_WEBSITE_NAME.'"';
			}
		else{
			//Else, print the alt attribute normally
			echo '" alt="'.ZHONGFRAMEWORK_PARAMETER_ALT_LOGO.'"'; 
			}
		
		echo ' />'; // end <img> tag.
		
		if(ZHONGFRAMEWORK_PARAMETER_ENABLE_LOGO_LINK=="true"){
			echo '</a>'; //end link tag
			}
		
		//Is the logo a heading?
		if($ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['websiteLogo']!='no-heading'){
			//If yes, close the heading tag (for example, </h1>)
			echo '</'.$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['websiteLogo'].'>';
			}
		else{
			//If both the title and subtitle are disabled, make the logo the document's main heading
			if(ZHONGFRAMEWORK_PARAMETER_SHOW_TITLE=="false"){
				echo '</h1>';
				}
			}
		
		echo '</div>'; //end "logo-wrap"
		} //end-if
	
	/*==========================================================================
	   WEBSITE TITLE & SUBTITLE
	==========================================================================*/
	echo '<!--TITLE & SUBTITLE container-->';
	echo '<div id="titles-container"><div id="titles-container-middle"><div id="titles-container-inner">';
	//Print the title
	if(ZHONGFRAMEWORK_PARAMETER_SHOW_TITLE=="true"){
		//The variable $ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['websiteTitle'] can be h1,h2,h3,h4,h5,h6 OR span
		echo '<'.$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['websiteTitle'].' id="title">';
		echo ZHONGFRAMEWORK_PARAMETER_TITLE;
		echo '</'.$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['websiteTitle'].'>';
		}
	//Print the Subtitle
	if(ZHONGFRAMEWORK_PARAMETER_SHOW_SUBTITLE=="true"){
		//The variable $ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['websiteTitle'] can be h1,h2,h3,h4,h5,h6 OR span
		echo '<'.$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['websiteSubtitle'].' id="subtitle">';
		echo ZHONGFRAMEWORK_PARAMETER_SUBTITLE;
		echo '</'.$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['websiteSubtitle'].'>';
		}
	echo '</div></div></div>';
	echo '<!--END title & subtitle container-->';
	echo '</div></div></div></header>';
	echo '<!-- END website-banner -->';
?>
