<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

//If full access layout, the "top" links point the "quick links menu", else the "top of the page"
if(ZHONGFRAMEWORK_LAYOUT_MODE=="full-access"){$topTarget='#anchors-menu';}
else{$topTarget='#page-top';}

//Print anchors to the quick access menu (these anchors are usually placed at the end of each section ) 
echo '<div class="anchors-container ';
//If default layout or mobile layout, then remove the anchor
if(ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout" || ZHONGFRAMEWORK_LAYOUT_MODE=="mobile-layout"){
	echo 'removed ';
	}
echo'">';

//Print a <p> element to wrap the anchor, then print the anchor
echo '<p class="wrapper-element"><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.$topTarget.'" rel="nofollow" class="top-anchor internal-link" ';

//If this is the first anchor printed, then add also the acceskey:
if(!isset($firstAnchor)){
	echo 'accesskey="'.ZHONGFRAMEWORK_LANGUAGE_ACCESSKEY_NAVIGATION_MENU.'" ';
	$firstAnchor=false;
	}

//Now print the title and the content:
echo 'title="';
if(ZHONGFRAMEWORK_LAYOUT_MODE!='full-access') { echo ZHONGFRAMEWORK_LANGUAGE_JUMP_TO_TOP; }
else { echo ZHONGFRAMEWORK_LANGUAGE_JUMP_TO_INTERNAL_NAV; } //If "full-access", Print a different title
echo '">';
if(ZHONGFRAMEWORK_LAYOUT_MODE!='full-access') { echo ZHONGFRAMEWORK_LANGUAGE_TOP; } //If NOT "full access", Print "top"
else { echo ZHONGFRAMEWORK_LANGUAGE_QUICK_ACCESS_MENU; } //If "full-access", Print "Quick access menu"

//Close the anchor, the <p> wrapper and the container 
echo '</a></p>';
echo '<hr class="removed"/>';
echo '</div>';
?>