<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

/*----------------------------------------------------------------
-  DEFINE COMMON PARAMETERS
---------------------------------------------------------------- */

//Website language
define("ZHONGFRAMEWORK_WEBSITE_LANGUAGE",htmlspecialchars($this->language));

//Website text direction
define("ZHONGFRAMEWORK_WEBSITE_TEXT_DIRECTION",htmlspecialchars($this->direction));

//Current URI
define("ZHONGFRAMEWORK_WEBSITE_CURRENT_URI",htmlspecialchars(JURI::current()));
//Current URI with parameters (e.g. ?layoutMode=defalut-layout)
define("ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS",htmlspecialchars(JFactory::getURI()));

//Base URI
define("ZHONGFRAMEWORK_WEBSITE_BASE_URI",htmlspecialchars(JURI::base()));

//Template name & URI
define("ZHONGFRAMEWORK_WEBSITE_TEMPLATE_NAME",htmlspecialchars($this->template));
define("ZHONGFRAMEWORK_WEBSITE_TEMPLATE_URI",ZHONGFRAMEWORK_WEBSITE_BASE_URI."templates/".ZHONGFRAMEWORK_WEBSITE_TEMPLATE_NAME);

//Website name
define("ZHONGFRAMEWORK_WEBSITE_NAME",htmlspecialchars(JFactory::getApplication()->getCfg('sitename')));

/*----------------------------------------------------------------
-  CHECK IF MODULES EXIST (true",exists, false",not set)
---------------------------------------------------------------- */

//main menu exists?
define("ZHONGFRAMEWORK_SIDE_MENU_EXISTS",$this->countModules('side-menu'));
//left module exists?
define("ZHONGFRAMEWORK_LEFT_MOD_EXISTS",$this->countModules('left-column'));
//login module exists?
define("ZHONGFRAMEWORK_LOGIN_MOD_EXISTS",$this->countModules('login'));
//Left column exists?
define("ZHONGFRAMEWORK_LEFT_COLUMN_EXISTS",$this->countModules('side-menu') || $this->countModules('left-column') || $this->countModules('login'));

//Right module exists?
define("ZHONGFRAMEWORK_RIGHT_MOD_EXISTS",$this->countModules('right-column'));
//Right column exists?
define("ZHONGFRAMEWORK_RIGHT_COLUMN_EXISTS",$this->countModules('right-column'));

//Footer credits mod exists?
define("ZHONGFRAMEWORK_FOOTER_CREDITS_MOD_EXISTS",$this->countModules('footer-credits'));
//Footer menu mod exists?
define("ZHONGFRAMEWORK_FOOTER_MENU_MOD_EXISTS",$this->countModules('footer-menu'));

//Accessibility module exists?
define("ZHONGFRAMEWORK_ACCESSIBILITY_PANEL_CUSTOM_MODULE_MOD_EXISTS",$this->countModules('accessibility-panel'));

//Language switcher module exists?
define("ZHONGFRAMEWORK_LANGUAGE_SWITCHER_MOD_EXISTS",$this->countModules('language-switcher'));

//Main menu module exists?
define("ZHONGFRAMEWORK_MAIN_MENU_MOD_EXISTS",$this->countModules('main-menu'));

//Search module exists?
define("ZHONGFRAMEWORK_SEARCH_MOD_EXISTS",$this->countModules('search'));

//Breadcrumb module exists?
define("ZHONGFRAMEWORK_BREADCRUMB_MOD_EXISTS",$this->countModules('breadcrumbs'));

//Support menu module exists? 
define("ZHONGFRAMEWORK_SUPPORT_MENU_MOD_EXISTS",$this->countModules('support-menu'));

//Footer exists? 
define("ZHONGFRAMEWORK_FOOTER_EXISTS",$this->countModules('custom-4-A or custom-4-B1 or custom-4-B2 or custom-4-C1 or custom-4-C2 or custom-4-C3 or custom-4-D1 or custom-4-D2 or custom-4-D3 or custom-4-D4 or footer-menu or footer-credits'));
define("ZHONGFRAMEWORK_FOOTER_CONTENT_EXISTS",$this->countModules('custom-4-A or custom-4-B1 or custom-4-B2 or custom-4-C1 or custom-4-C2 or custom-4-C3 or custom-4-D1 or custom-4-D2 or custom-4-D3 or custom-4-D4 or footer-credits'));

//Users blocks exist?
define("ZHONGFRAMEWORK_CUSTOM_MODULES_BLOCK_1_EXISTS",$this->countModules('custom-1-A or custom-1-B1 or custom-1-B2 or custom-1-C1 or custom-1-C2 or custom-1-C3 or custom-1-D1 or custom-1-D2 or custom-1-D3 or custom-1-D4'));
define("ZHONGFRAMEWORK_CUSTOM_MODULES_BLOCK_2_EXISTS",$this->countModules('custom-2-A or custom-2-B1 or custom-2-B2 or custom-2-C1 or custom-2-C2 or custom-2-C3 or custom-2-D1 or custom-2-D2 or custom-2-D3 or custom-2-D4'));
define("ZHONGFRAMEWORK_CUSTOM_MODULES_BLOCK_3_EXISTS",$this->countModules('custom-3-A or custom-3-B1 or custom-3-B2 or custom-3-C1 or custom-3-C2 or custom-3-C3 or custom-3-D1 or custom-3-D2 or custom-3-D3 or custom-3-D4'));
define("ZHONGFRAMEWORK_CUSTOM_MODULES_BLOCK_4_EXISTS",$this->countModules('custom-4-A or custom-4-B1 or custom-4-B2 or custom-4-C1 or custom-4-C2 or custom-4-C3 or custom-4-D1 or custom-4-D2 or custom-4-D3 or custom-4-D4'));

//User modules esists? 
$ZHONGFRAMEWORK_CUSTOM_MODULE_EXISTS = array(
    1 => $this->countModules('custom-1-A'),
    2 => $this->countModules('custom-1-B1'),
    3 => $this->countModules('custom-1-B2'),
    4 => $this->countModules('custom-1-C1'),
    5 => $this->countModules('custom-1-C2'),
    6 => $this->countModules('custom-1-C3'),
    7 => $this->countModules('custom-1-D1'),
    8 => $this->countModules('custom-1-D2'),
    9 => $this->countModules('custom-1-D3'),
    10 => $this->countModules('custom-1-D4'),
    11 => $this->countModules('custom-2-A'),
    12 => $this->countModules('custom-2-B1'),
    13 => $this->countModules('custom-2-B2'),
    14 => $this->countModules('custom-2-C1'),
    15 => $this->countModules('custom-2-C2'),
    16 => $this->countModules('custom-2-C3'),
    17 => $this->countModules('custom-2-D1'),
    18 => $this->countModules('custom-2-D2'),
    19 => $this->countModules('custom-2-D3'),
    20 => $this->countModules('custom-2-D4'),
    21 => $this->countModules('custom-3-A'),
    22 => $this->countModules('custom-3-B1'),
    23 => $this->countModules('custom-3-B2'),
    24 => $this->countModules('custom-3-C1'),
    25 => $this->countModules('custom-3-C2'),
    26 => $this->countModules('custom-3-C3'),
    27 => $this->countModules('custom-3-D1'),
    28 => $this->countModules('custom-3-D2'),
    29 => $this->countModules('custom-3-D3'),
    30 => $this->countModules('custom-3-D4'),
    31 => $this->countModules('custom-4-A'),
    32 => $this->countModules('custom-4-B1'),
    33 => $this->countModules('custom-4-B2'),
    34 => $this->countModules('custom-4-C1'),
    35 => $this->countModules('custom-4-C2'),
    36 => $this->countModules('custom-4-C3'),
    37 => $this->countModules('custom-4-D1'),
    38 => $this->countModules('custom-4-D2'),
    39 => $this->countModules('custom-4-D3'),
    40 => $this->countModules('custom-4-D4')
	);
?>
