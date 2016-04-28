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
   SECTION HEADING (Print the heading for a section)
==========================================================================*/
function printSectionHeading($headingContent,$defaultHeadingLevel,$customHeadingLevel,$enableHeaderTag,$additionalClass){
	
	/*----------------------------------------------------------------
	-  Initialize (Get the value of the heading level & other)
	---------------------------------------------------------------- */
	
	//If the custom heading level is set to zero, then it means it is disabled, therefore just do nothing
	if($customHeadingLevel=='disabled'){ return; }
	
	//If the client is a crawler, do not print the headings
	if(ZHONGFRAMEWORK_IS_CLIENT_CRAWLER){ return; }
	
	//This var sets the "aria-level" heading level (default value)
	$ariaLevelHeading = $defaultHeadingLevel;
	
	//If the heading level is the default one, then follow either HTML4 or HTML5 mode:
	if($customHeadingLevel=="default"){
		
		//Default case (HTML4): The level is equal to the default one
		$headingLevel = $defaultHeadingLevel;
		
		//HTML5 mode: If the headings level mode is set to "html5" AND the <header> tag is enabled, then always set the "heading level" to <h1>
		if(ZHONGFRAMEWORK_PARAMETER_HEADINGS_LEVEL_MODE=="HTML5"){
			$headingLevel=1;
			}

		}
	else{
		//If the heading level is custom, set the chosen value
		$headingLevel=$customHeadingLevel;
		//And update also the aria level
		$ariaLevelHeading=$customHeadingLevel;
		}

	//If "default-layout" or "mobile-layout", then the headings are hidden:
	if(ZHONGFRAMEWORK_LAYOUT_MODE=='default-layout' || ZHONGFRAMEWORK_LAYOUT_MODE=='mobile-layout'){
		$additionalClass.=' visually-hidden';
		}

	/*----------------------------------------------------------------
	-  Print the heading:
	---------------------------------------------------------------- */
	
	if(ZHONGFRAMEWORK_LAYOUT_MODE=="full-access" || 
	   ZHONGFRAMEWORK_LAYOUT_MODE=="high-contrast" || 
	   ZHONGFRAMEWORK_PARAMETER_ENABLE_HIDDEN_HEADINGS_DEFAULT_LAYOUT=="true"){
		if($enableHeaderTag){ echo '<header>'; }
		//Opening tag + classes
		echo '<h'.$headingLevel.' class="section-heading '.$additionalClass.'" role="heading" aria-level="'.$ariaLevelHeading.'">';
		//Heading content
		echo $headingContent;
		//Closing tag
		echo '</h'.$headingLevel.'>';
		if($enableHeaderTag){ echo '</header>'; }
		}

	}
?>