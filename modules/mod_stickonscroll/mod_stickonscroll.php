<?php
/**
* @package 	mod_stickonscroll - Stick on Scroll
* @version		1.0.1
* @created		October 2013

* @author		PluginValley
* @email		pluginvalley@ymail.com
* @website		http://www.pluginvalley.com
* @support		Forum - http://www.pluginvalley.com/forum.html
* @copyright	Copyright (C) 2012 pluginvalley. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*
*/
// no direct access
defined('_JEXEC') or die('');
	// require helper
	require_once(dirname(__FILE__).DS.'helper.php');
		
	// base url
	$baseurl	=	JURI::base( true );
	
	// get parameters from the module's configuration
	$usejq			= 	$params->get('usejq', '1'); // jquery
	$tbarid			= 	$params->get('tbarid', 'mystickydiv'); // container id
	$topspacing		= 	$params->get('topspacing', '0'); // container id
	$tbarcss		= 	$params->get('tbarcss', 'background: none repeat scroll 0 0 #0C1A3E; color: #FFFFFF; height: 30px; margin: 0 10px !important; min-height: 20px; padding: 15px 15px 15px 0; width: 1015px; z-index: 100000;'); // container css
	$tbarcontent	= 	$params->get('tbarcontent', '<span class="promocode1">AUTUMN PROMO 25%0FF!</span><span class="promocode2"> UNTIL 1ST NOVEMBER! </span><span class="promocode3">PROMO CODE :</span> <span class="promocode4">VALLEY25</span>'); // show close button
	// jquery usage - scripts and css to head
	$document = &JFactory::getDocument();
		if( $usejq ):
			$document->addScript( $baseurl.'/modules/mod_stickonscroll/assets/jquery/jquery-1.9.1.min.js' );	
		endif;
		$document->addScript( $baseurl.'/modules/mod_stickonscroll/assets/jquery/jquery.sticky.js' );
	// plugin
	// css
	$document->addStyleSheet( $baseurl.'/modules/mod_stickonscroll/assets/css/style.css' );	
//print_r($list);
require(JModuleHelper::getLayoutPath('mod_stickonscroll', 'default'));