<?php
/**
 * @version		$Id$
 * @author		JoomlaUX!
 * @package		Joomla.Site
 * @subpackage	mod_jux_megamenu
 * @copyright	Copyright (C) 2008 - 2013 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html GNU/GPL version 3
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).'/helper.php');

// Change DEMO_MODE value to 1 to enable the demo mode.

if(!defined('DEMO_MODE')) {
    // Change DEMO_MODE value to 1 to enable the demo mode.
    define('DEMO_MODE', 0);
}

if(DEMO_MODE) {
	$input = JFactory::getApplication()->input;
	$data = $input->post->get('jux_demo_control_form', array(), 'array');

	$properties = $params->toArray();
	foreach($properties as $key => $value) {
		$params->set($key, isset($data[$key]) ? $data[$key] : $value);
	}
}

$menutype 	= $params->get('menutype', 'mainmenu');

$responsive	= $params->get('responsive_menu',	'1');
$layout		= $params->get('layout', 'default');

$menuStyle			= 'megamenu';
$menuOrientation	= $params->get('hozorver', 'horizontal');
$stickyAlignment = $params->get('sticky_alignment', 'left');
$horizontal_submenu_direction = $params->get('horizontal_submenu_direction');
$menuAlignment		= 'left';
if($menuOrientation == 'horizontal') {
    $menuAlignment  = $params->get('horizontal_menustyle', 'left');
} else {
    $menuAlignment	= $params->get('vertical_submenu_direction', 'lefttoright') == 'lefttoright' ? 'left' : 'right';
}

$menuStyle	.= " $menuOrientation $menuAlignment $layout $stickyAlignment $horizontal_submenu_direction";

$document	= JFactory::getDocument();

$document->addStyleSheet(JUri::base(true).'/modules/mod_jux_megamenu/assets/css/style.css');
$document->addStyleSheet(JUri::base(true).'/modules/mod_jux_megamenu/assets/css/animate.css');
$document->addStyleSheet(JUri::base(true).'/modules/mod_jux_megamenu/assets/css/jux-font-awesome.css');
// $document->addStyleSheet(JUri::base(true).'/modules/mod_jux_megamenu/assets/css/jux-responsivestyle.css');
$document->addStyleSheet(JUri::base(true).'/modules/mod_jux_megamenu/assets/css/style/'.$layout.'.css');

JHtml::_('jquery.framework');
$document->addScript(JUri::base(true).'/modules/mod_jux_megamenu/assets/js/headroom.js');
if($params->get('bootstrap')==1){ 
	$document->addScript(JUri::base(true).'/modules/mod_jux_megamenu/assets/js/bootstrap.min.js');
}
if($params->get('sticky_menu')==1){
	$document->addScript(JUri::base(true).'/modules/mod_jux_megamenu/assets/js/script.js');
}
$document->addScript(JUri::base(true).'/modules/mod_jux_megamenu/assets/js/SmoothScroll.js');
// if use CSS3 only, disable mootools and megacss menu script
if ($params->get('css3_noJS', 1)) {
	$menuStyle	.= ' noJS';
} else { // Use mootools libraries and enable megacss menu script
	JHTML::_('behavior.framework', true);
	JHTML::_('behavior.tooltip', true);
	$document->addScript(JUri::base(true).'/modules/mod_jux_megamenu/assets/js/HoverIntent.js');
	
}
 	$customcss = 'modules/mod_jux_megamenu/assets/css/stylec/custom-' . $module->id . '.css';
    if (mod_JUX_MegaMenuHelper::getCssProcessor($params,$customcss,'#jux_memamenu'.$module->id)){
        $document->addStyleSheet($customcss);
    }

$dropdownmenu    = new Mod_JUX_MegaMenu($params);
require(JModuleHelper::getLayoutPath('mod_jux_megamenu'));