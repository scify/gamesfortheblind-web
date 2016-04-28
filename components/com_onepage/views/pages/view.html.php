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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Content component
 *
 * @package		Joomla.Site
 * @subpackage	com_content
 * @since 1.5
 */
class OnepageViewPages extends JViewLegacy
{
    protected $state; 
	protected $items;
	protected $pagination;

	function display($tpl = null)
	{     
        $model = $this->getModel();   
        $app    = JFactory::getApplication();  
        $this->state    = $this->get('State');
        $this->params    = $this->state->params;          
        //$this->items        = $this->get('Items');  
        $request = $this->params->get('request');
        $this->onepage_id = $request['onepage_id'];   
        $this->items        = $model->getPages($this->onepage_id);
		$this->pageitem		= $model->getPagesitem($this->onepage_id);
        $this->menu_mode = $this->params->get('menu_mode');
		$this->pagination 	= $this->get('Pagination');   

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		//TODO: prepare document
	}
    public $shortcode_tags = array();     


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
        // WARNING! Do not change this regex without changing do_xpshortcode_tag() and strip_shortcodes()
        return '(.?)\[('.$tagregexp.')\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)';
    }


    function do_xpshortcode_tag($m) {
        global $shortcode_tags;

        // allow [[foo]] syntax for escaping a tag
        if($m[1] == '[' && $m[6] == ']') {
            return substr($m[0], 1, -1);
        }

        $tag = $m[2];
        $attr = $this->xpshortcode_parse_atts($m[3]);

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

        $pattern = $this->get_xpshortcode_regex();

        return preg_replace('/' . $pattern . '/s', '$1$6', $content);
    }

    public static function includeShortcode(){
        jimport( 'joomla.filesystem.folder' );
        jimport( 'joomla.filesystem.file' );
        require_once JPATH_COMPONENT . '/helpers/includes/common.php';   
            $src = JPATH_COMPONENT . "/helpers/shortcode";
            $lists = JFolder::files($src);
            foreach($lists as $f){
                if(JFile::getExt($f)=='php'){
                    require_once($src.'/'.$f);
                }
            }

    }

  
}
