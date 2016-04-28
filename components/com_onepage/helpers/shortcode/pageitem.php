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
add_xpshortcode('pageitem_desi', 'pageitemDesignShortcode');
function pageitemDesignShortcode($atts,$content=null){     
 	extract(xpshortcode_atts(array(
    	"id" => '0',
    	"showtitle"=>0,
        "moduleclass"=>""
    	), $atts));
    if($id==0 || $id=='') return false;
    $st = new OnepagePlugin();
    $pageitem =  $st->getPageitem($id);    
    $req = OnepagePlugin::addTemplate('pageitem');
	if($req["check"]){
		require $req["src"];
	}
}
?>