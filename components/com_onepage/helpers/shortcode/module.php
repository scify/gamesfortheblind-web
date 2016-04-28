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
add_xpshortcode('moduleid_desi', 'moduleidDesignShortcode');  
function moduleidDesignShortcode($atts,$content=null){     
 	extract(xpshortcode_atts(array(
    	"id" => '0',
    	"showtitle"=>0,
    	"moduleclass"=>""
    	), $atts));
    if($id==0 || $id=='') return false;
    $st = new OnepagePlugin();
    $module =  $st->loadModuleId($id);
    $params = json_decode($module->params);
    $req = OnepagePlugin::addTemplate('module');    
	if($req["check"]){
		require $req["src"];
	}
}
?>