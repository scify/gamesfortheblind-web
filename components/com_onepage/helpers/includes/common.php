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

defined('_JEXEC') or die('Restricted access');

$shortcode_tags = array();


function add_xpshortcode($tag, $func) {
	global $shortcode_tags;
	if(is_callable($func))
		$shortcode_tags[$tag] = $func;
}


function remove_xpshortcode($tag) {
	global $shortcode_tags;
	unset($shortcode_tags[$tag]);
}

function remove_all_xpshortcodes() {
	global $shortcode_tags;

	$shortcode_tags = array();
}

function do_xpshortcode($content) {
	global $shortcode_tags;

	if(empty($shortcode_tags) || !is_array($shortcode_tags))
		return urldecode($content) ;

	$pattern = get_xpshortcode_regex();

	return urldecode(preg_replace_callback('/' . $pattern . '/s', 'do_xpshortcode_tag', $content));
}


function get_xpshortcode_regex() {
	global $shortcode_tags;
	$tagnames  = array_keys($shortcode_tags);
	$tagregexp = join('|', array_map('preg_quote', $tagnames));
	// WARNING! Do not change this regex without changing do_xpshortcode_tag() and strip_xpshortcodes()
	return '(.?)\[('.$tagregexp.')\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)';
}


function do_xpshortcode_tag($m) {
	global $shortcode_tags;

	// allow [[foo]] syntax for escaping a tag
	if($m[1] == '[' && $m[6] == ']') {
		return substr($m[0], 1, -1);
	}

	$tag = $m[2];
	$attr = xpshortcode_parse_atts($m[3]);

	if(isset($m[5])) {
		// enclosing tag - extra parameter
		return $m[1] . call_user_func($shortcode_tags[$tag], $attr, $m[5], $tag) . $m[6];
	}
	else {
		// self-closing tag
		return $m[1] . call_user_func($shortcode_tags[$tag], $attr, NULL,  $tag) . $m[6];
	}
}


function xpshortcode_parse_atts($text) {
	$atts    = array();
	$pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
	$text    = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
	if(preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
		foreach($match as $m) {
			if(!empty($m[1]))
				$atts[strtolower($m[1])] = stripcslashes($m[2]);
			elseif(!empty($m[3]))
				$atts[strtolower($m[3])] = stripcslashes($m[4]);
			elseif(!empty($m[5]))
				$atts[strtolower($m[5])] = stripcslashes($m[6]);
			elseif(isset($m[7]) and strlen($m[7]))
				$atts[] = stripcslashes($m[7]);
			elseif(isset($m[8]))
				$atts[] = stripcslashes($m[8]);
		}
	}
	else {
		$atts = ltrim($text);
	}
	return $atts;
}


function xpshortcode_atts($pairs, $atts) {
	$atts =(array)$atts;
	$out  = array();
	foreach($pairs as $name => $default) {
		if(array_key_exists($name, $atts))
			$out[$name] = $atts[$name];
		else
			$out[$name] = $default;
	}
	return $out;
}


function strip_xpshortcodes($content) {
	global $shortcode_tags;

	if(empty($shortcode_tags) || !is_array($shortcode_tags))
		return $content;

	$pattern = get_xpshortcode_regex();

	return preg_replace('/' . $pattern . '/s', '$1$6', $content);
}


class OnepagePlugin
{
	public $doc=null;
	private $version="";
	public function __construct(){
		$this->doc = JFactory::getDocument();
		$arr = explode('.',JVERSION);
		$this->version = $arr[0];
	}

	public static function addTemplate($file){
		jimport( 'joomla.filesystem.file' );
		$tpl 		= JFactory::getApplication()->getTemplate();
		$src 		= JPATH_COMPONENT.'/templates/'.$tpl.'/html/template/'.$file.'.php';
		$srcbase 	= JPATH_COMPONENT . "/helpers/template/".$file.'.php';            
		if(JFile::exists($src)){
			return array("check"=>true,"src"=>$src);
		}else if(JFile::exists($srcbase)){
			return array("check"=>true,"src"=>$srcbase);
		}else{
			return array("check"=>false,"src"=>"");
		}
	}

	public function loadModule($position,$count=0,$style="none"){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("*")->from("#__modules")->where('position=\''.$position.'\'');
		$db->setQuery($query);
		$result = $db->loadObjectList();
		if(count($result)==1){
			$title = $result[0]->title;
			$mod = $result[0]->module;
		}else{
			$title = $result[$count]->title;
			$mod = $result[$count]->module;
		}
		$module = JModuleHelper::getModule( $mod, $title );
		$attribs['style'] = $style;                                
		return JModuleHelper::renderModule( $module,$attribs);
	}

	public function loadModuleId($id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("*")->from("#__modules")->where('id=\''.$id.'\'');
		$db->setQuery($query);
		$result = $db->loadObject();
		$title = $result->title;
		$mod = $result->module;
		$module = JModuleHelper::getModule( $mod, $title );
		$module->content = JModuleHelper::renderModule( $module,array('style'=>'none'));  
		return $module;
		//return JModuleHelper::renderModule( $module );
	}

    public function getPageitem($id){
        $db = JFactory::getDbo(); 
        $query        = $db->getQuery(true);
        
        $query->select(
            '*'
        );
        
        $query->from('`#__onepage_items`');
        $query->where('id = '.$id.' AND state = 1');
        $query->order('ordering');
        
        $db->setQuery($query);
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
        }
        return $db->loadObjectList();
    }    
    
}