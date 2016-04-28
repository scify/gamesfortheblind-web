<?php 
/*------------------------------------------------------------------------
# com_onepage - XIPAT Onepage component
# version: 1.0
# ------------------------------------------------------------------------
# author    Nguyen Huy Kien - www.xipat.com
# copyright Copyright (C) 2013 www.xipat.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.xipat.com
# Technical Support:  http://www.xipat.com/support.html
-------------------------------------------------------------------------*/
defined('_JEXEC') or die;

add_xpshortcode("divider_desi", "dividerDesignShortcode");
function dividerDesignShortcode($atts,$content=null){
	extract(xpshortcode_atts(array(
        "class" => '',
        "style" => '',
        "margin"=> ''
    ), $atts));
    $req = OnepagePlugin::addTemplate('divider');
	if($req["check"]){
		require $req["src"];
	}
    
}