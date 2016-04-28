<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

// If default layout mode OR mobile mode is set AND the top button is active, Print "top" anchor
if(ZHONGFRAMEWORK_PARAMETER_ENABLE_TOP_BUTTON_DEFAULT_LAYOUT=="true" &&
  (ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout" || ZHONGFRAMEWORK_LAYOUT_MODE=='mobile-layout')){
	echo '<div id="goto-top-block">';
	echo '<p class="wrapper-element">';
	echo '<a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.'#page-top" rel="nofollow" accesskey="0" title="'.ZHONGFRAMEWORK_LANGUAGE_JUMP_TO_TOP.'" id="goto-top">'.ZHONGFRAMEWORK_LANGUAGE_TOP.'</a>';
	echo '</p>';
	echo '</div>';
	echo '<hr class="removed"/>';
	}
else{
	echo '<div class="clear-both"></div>';
	}
?>