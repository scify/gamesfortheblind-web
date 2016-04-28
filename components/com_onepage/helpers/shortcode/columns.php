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
// Columns
$column_new_array = array();
add_xpshortcode('columns_desi', 'columnsDesignShortcode');
function columnsDesignShortcode($atts, $content = null){
    extract(xpshortcode_atts(array(
        "id" => '',
        "class"=>'',
        "fullwidth"=>'0'
    ), $atts));
	global $column_new_array;
    $column_new_array = array();
    extract(xpshortcode_atts(array(
    	"class" => ''
    	), $atts));
    do_xpshortcode($content);

   $req = OnepagePlugin::addTemplate('columns');
	if($req["check"]){
		require $req["src"];
	}
}

add_xpshortcode('column_item_desi', 'columnItemDesignShortcode');
function columnItemDesignShortcode($atts, $content = null)
{
    extract(xpshortcode_atts(array(
        "col" => 'span12',
        "class"=>''
    ), $atts));
    global $column_new_array;
    $column_new_array[] = array("col" => $col , "content" => $content,'class'=>$class);
}

