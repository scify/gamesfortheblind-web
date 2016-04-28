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
   CUSTOM MODULES PRINT
==========================================================================*/
// If complementary, enable the aside tag
if($isUserModRoleComplementary){
	echo '<aside role="complementary">';
	if($custom_module_layout_block===1){
		//Print the ID tag
		echo '<div id="complementary-content-upper">';
		//And print the heading
		printSectionHeading(ZHONGFRAMEWORK_LANGUAGE_SUPPLEMENTARY_CONTENT_UPPER,2,$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['supplementaryContentUpper'],false,'');
		}
	if($custom_module_layout_block===3){
		//Print the ID tag
		echo '<div id="complementary-content-lower">';
		//And print the heading
		printSectionHeading(ZHONGFRAMEWORK_LANGUAGE_SUPPLEMENTARY_CONTENT_LOWER,2,$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['supplementaryContentLower'],false,'');		
		}
	}

/*----------------------------------------------------------------
-  FULL WIDTH MODULE (1 MODULE in a row)
---------------------------------------------------------------- */  
if($ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS[$custom_module_index]){
	echo '<div id="custom-modules-container-'.$custom_module_layout_block.'-A" class="custom-modules-container">';
	//echo '<section>';
		echo '<div id="custom-module-'.$custom_module_layout_block.'-A" class="custom-module_column-width-1 custom-module-outer">';
		echo '<div class="custom-module-style_'.$ZHONGFRAMEWORK_PARAMETER_USERMODULES_STYLE[$custom_module_index].'  custom-module-inner">';
      	$parentCMSHandler->printCustomUserModule($custom_module_layout_block,'A','');
		echo '</div></div>';
	echo '<div class="clear-both"></div>';
	//echo '</section>';
	echo '</div>'; // END "custom-modules-container"
	} ?>

<?php
/*----------------------------------------------------------------
-  HALF WIDTH MODULE (2 MODULES in a row)
---------------------------------------------------------------- */ 
if($ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS[$custom_module_index+1] || $ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS[$custom_module_index+2]){
	echo '<div id="custom-modules-container-'.$custom_module_layout_block.'-B" class="custom-modules-container">';
	//echo '<section>';
		echo '<div id="custom-module-'.$custom_module_layout_block.'-B1" class="custom-module_column-width-2 custom-module-outer">';
		echo '<div class="custom-module-style_'.$ZHONGFRAMEWORK_PARAMETER_USERMODULES_STYLE[$custom_module_index+1].' custom-module-inner">';	
		if($ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS[$custom_module_index+1]){
			$parentCMSHandler->printCustomUserModule($custom_module_layout_block,'B','1');
			}
		echo '</div></div>';
		echo '<div id="custom-module-'.$custom_module_layout_block.'-B2" class="custom-module_column-width-2 custom-module-outer">';
		echo '<div class="custom-module-style_'.$ZHONGFRAMEWORK_PARAMETER_USERMODULES_STYLE[$custom_module_index+2].' custom-module-inner">';	
		if($ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS[$custom_module_index+2]){
			$parentCMSHandler->printCustomUserModule($custom_module_layout_block,'B','2');
			}
		echo '</div></div>';
	echo '<div class="clear-both"></div>';
	//echo '</section>';
	echo '</div>'; // END "custom-modules-container"
	} ?>

<?php
/*----------------------------------------------------------------
-  1/3 WIDTH MODULE (3 MODULES in a row)
---------------------------------------------------------------- */
if($ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS[$custom_module_index+3] || $ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS[$custom_module_index+4] || $ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS[$custom_module_index+5]){
	echo '<div id="custom-modules-container-'.$custom_module_layout_block.'-C" class="custom-modules-container">';
	//echo '<section>';
		echo '<div id="custom-module-'.$custom_module_layout_block.'-C1" class="custom-module_column-width-3 custom-module-outer">';
		echo '<div class="custom-module-style_'.$ZHONGFRAMEWORK_PARAMETER_USERMODULES_STYLE[$custom_module_index+3].' custom-module-inner">';		
		if($ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS[$custom_module_index+3]){
			$parentCMSHandler->printCustomUserModule($custom_module_layout_block,'C','1');
			}
		echo '</div></div>';
		echo '<div id="custom-module-'.$custom_module_layout_block.'-C2" class="custom-module_column-width-3 custom-module-outer">';
		echo '<div class="custom-module-style_'.$ZHONGFRAMEWORK_PARAMETER_USERMODULES_STYLE[$custom_module_index+4].' custom-module-inner">';		
		if($ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS[$custom_module_index+4]){
			$parentCMSHandler->printCustomUserModule($custom_module_layout_block,'C','2');
		}
		echo '</div></div>';
		echo '<div id="custom-module-'.$custom_module_layout_block.'-C3" class="custom-module_column-width-3 custom-module-outer">';
		echo '<div class="custom-module-style_'.$ZHONGFRAMEWORK_PARAMETER_USERMODULES_STYLE[$custom_module_index+5].' custom-module-inner">';		
		if($ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS[$custom_module_index+5]){
			$parentCMSHandler->printCustomUserModule($custom_module_layout_block,'C','3');
			}
		echo '</div></div>';
	echo '<div class="clear-both"></div>';
	//echo '</section>';
	echo '</div>'; // END "custom-modules-container"
	} ?>

<?php
/*----------------------------------------------------------------
-  1/4 WIDTH MODULE (4 MODULES in a row)
---------------------------------------------------------------- */
if($ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS[$custom_module_index+6] || $ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS[$custom_module_index+7] || $ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS[$custom_module_index+8] || $ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS[$custom_module_index+9]){
	echo '<div id="custom-modules-container-'.$custom_module_layout_block.'-D" class="custom-modules-container">';
	//echo '<section>';
		echo '<div id="custom-module-'.$custom_module_layout_block.'-D1" class="custom-module_column-width-4 custom-module-outer">';
		echo '<div class="custom-module-style_'.$ZHONGFRAMEWORK_PARAMETER_USERMODULES_STYLE[$custom_module_index+6].' custom-module-inner">';		
		if($ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS[$custom_module_index+6]){
			$parentCMSHandler->printCustomUserModule($custom_module_layout_block,'D','1');
			}
		echo '</div></div>';
		echo '<div id="custom-module-'.$custom_module_layout_block.'-D2" class="custom-module_column-width-4 custom-module-outer">';
		echo '<div class="custom-module-style_'.$ZHONGFRAMEWORK_PARAMETER_USERMODULES_STYLE[$custom_module_index+7].' custom-module-inner">';		
		if($ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS[$custom_module_index+7]){
			$parentCMSHandler->printCustomUserModule($custom_module_layout_block,'D','2');
			}
		echo '</div></div>';
		echo '<div id="custom-module-'.$custom_module_layout_block.'-D3" class="custom-module_column-width-4 custom-module-outer">';
		echo '<div class="custom-module-style_'.$ZHONGFRAMEWORK_PARAMETER_USERMODULES_STYLE[$custom_module_index+8].' custom-module-inner">';		
		if($ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS[$custom_module_index+8]){
			$parentCMSHandler->printCustomUserModule($custom_module_layout_block,'D','3');
			}
		echo '</div></div>';
		echo '<div id="custom-module-'.$custom_module_layout_block.'-D4" class="custom-module_column-width-4 custom-module-outer">';
		echo '<div class="custom-module-style_'.$ZHONGFRAMEWORK_PARAMETER_USERMODULES_STYLE[$custom_module_index+9].' custom-module-inner">';		
		if($ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS[$custom_module_index+9]){
			$parentCMSHandler->printCustomUserModule($custom_module_layout_block,'D','4');
			}
		echo '</div></div>';
	echo '<div class="clear-both"></div>';
	//echo '</section>';
	echo '</div>'; // END "custom-modules-container"
	}

// If complementary, close the aside tag
if($isUserModRoleComplementary){
	if($custom_module_layout_block===1 || $custom_module_layout_block===3){	echo '</div>'; }
	echo '</aside>';
	}
?>