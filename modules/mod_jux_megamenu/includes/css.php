<?php
/**
 * @version		$Id$
 * @author		JoomlaUX!
 * @package		Joomla.Site
 * @subpackage	mod_jux_megamenucss3
 * @copyright	Copyright (C) 2015 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html GNU/GPL version 3
*/

defined('_JEXEC') or die('Restricted access'); 

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class modJUXMegaMenu {
	
	protected $prefix = '#jux_megamanu';
	
	public function __construct($prefix = ''){
		$this->prefix = $prefix;
	}

	protected function _renderCssObject($property,$cssJson,$one = false){
		$cssArr = json_decode($cssJson,true);
		$cssString = '';
		if (count($cssArr)){
			foreach ($cssArr as $key=>$v){
				if ($key == 'dd_shadow_inset'){
					$cssArr[$key] = $v == '1' ? 'inset' : '';
				}else{
					if (is_array($v)){
						$rbg = $this->HextoRBG($v['color']);
						$rbg['opacity'] = $v['opacity'];
						$cssArr[$key] = 'rgba('.implode(',',$rbg).')';
					}
				}
			}
			
			if (is_array($property)){
				foreach ($property as $k=>$pr){
					if (!$one){
						$cssString .= $pr.': '.implode(' ',$cssArr).';'."\n";
					}else{
						$cssArr = array_values($cssArr);
						$cssString .=$pr.':'.(isset($cssArr[$k]) ? $cssArr[$k] : '').';'."\n";
                                                
					}
				}
			}elseif (is_string($property)){
				$cssString .= $property.': '.implode(' ',$cssArr).';'."\n";
			}
		}
		return $cssString;
	}
	
	protected function _dropdown(&$params){
		$dropdown = '';
		$special_id=$params->get('special_id');
                $dropdown .=' '.$this->prefix.' #js-mainnav.megamenu .js-megamenu.dropdown-menucss'.$special_id.'{ display: block!important; } ';  
            
            $res= $params->get('responsive_menu','1');
            if($res==1){
                $dropdown .=' @media screen and (max-width: 768px) {';  
                $dropdown .=' '.$this->prefix.' #js-mainnav.megamenu.horizontal ul.level1 li.submenu-align-right a.haschild.megacss span.menu-title,'.$this->prefix.' #js-mainnav.megamenu.horizontal ul.level1 li.submenu-align-right a.haschild-over.megacss span.menu-title {margin-left: 0px;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav .jux-fa.jux-fa-bars{font-size: 30px; float: left; color: #FFF; margin-top: -6px; margin-left:4px;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav.megamenu.vertical.right .js-megamenu ul.level0 li.megacss .childcontent {margin-left: 0% !important;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav ul.megamenu li.haschild.megacss:hover>div.childcontent.adddropdown, '.$this->prefix.'  .childcontent.open>.dropdown-menu{  -moz-animation: none!important; -webkit-animation: none!important;  animation:none!important;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav .js-megamenu  .childcontent-inner-wrap.dropdown-menu{display: none;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav .js-megamenu .open .childcontent-inner-wrap.dropdown-menu{display: block;   background: #f9f9f9;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav.megamenu .js-megamenu{display: none;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav.megamenu div.megaMenuToggle{display: block; height: 15px;cursor: pointer;  box-sizing: content-box; }';
                //$dropdown .=' '.$this->prefix.'  #js-mainnav div.js-megamenu{ background: -webkit-gradient(linear, left top, left bottom, from(#3d3d3d), to(#212121));background: -webkit-linear-gradient(top, #3d3d3d,  #212121); background: -moz-linear-gradient(top, #3d3d3d, #212121); background: -ms-linear-gradient(top, #3d3d3d, #212121);background: -o-linear-gradient(top, #3d3d3d, #212121);}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav.megamenu.noJS ul.megamenu li.haschild > div.childcontent{ display: block!important;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav.megamenu.noJS ul.megamenu li{box-shadow: none !important;}';
                //$dropdown .=' '.$this->prefix.'  #js-mainnav.megamenu .childcontent-inner-wrap{margin: -2px 0px 6px 0px !important;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav.horizontal ul.level0 li.megacss.submenu-align-center > .childcontent{left: 0%!important;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav.noJS.up ul.megamenu li.haschild > div.childcontent{position: static;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav.horizontal ul.level0 li.megacss.submenu-align-left > .childcontent, '.$this->prefix.' #js-mainnav.megamenu.vertical.left .js-megamenu ul.level0 li.megacss .childcontent, '.$this->prefix.' #megamenucss   #js-mainnav.noJS ul.megamenu li.haschild > div.childcontent {top: 0px !important; left: 0px !important;bottom: 0px !important; right: 0px !important; }';
                $dropdown .=' '.$this->prefix.'  #js-mainnav.megamenu.vertical.left .js-megamenu ul.level0 li.megacss .childcontent{margin-left: 0% !important; margin-top: -32px; }';
                $dropdown .=' '.$this->prefix.'  #js-mainnav.megamenu.horizontal{border-radius: 0px !important;}';
                $dropdown .=' '.$this->prefix.'  .megamenu ul.level0 li.megacss a.megacss{border: none !important;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav.megamenu ul.level1 li.megacss span.megacss, '.$this->prefix.' #js-mainnav.megamenu ul.level1 li.megacss a.megacss{border: none !important;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav.horizontal ul.level1 li.megacss.submenu-align-right > .childcontent{right: 0% !important;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav.horizontal ul.level0 li.megacss.submenu-align-center > .childcontent{left: 0%;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav ul.level0{border: none !important;}';
                $dropdown .=' '.$this->prefix.'  .group-title{margin-left: -25px;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav.megamenu #arrow-icon{display: block;}';
                $dropdown .=' '.$this->prefix.'  .dropdown-menu{position: inherit;  top: -6px; left: 0;z-index: 1000;display: none; float: left;min-width: 100%;  padding: 0; margin: 0;list-style: none;background-color: #fff; border: none;  -webkit-border-radius: 0px;-moz-border-radius: 0px;  border-radius: 0px; -webkit-box-shadow: none;-moz-box-shadow: none; box-shadow: none; -webkit-background-clip: padding-box;-moz-background-clip: padding; background-clip: padding-box;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav ul.megamenu li.haschild.megacss > div.childcontent{ opacity: 1; visibility: visible; display: block;}';
                $dropdown .=' '.$this->prefix.'  #arrow-icon {position: absolute; font-size: 25px; text-decoration: none; right: -2px; margin-top: -44px;color: #fff; padding: 10px 30px ;z-index: 9999999; cursor: pointer; }';
                $dropdown .=' '.$this->prefix.'  #js-mainnav.megamenu .open > .dropdown-menu{display: block !important;}';
                $dropdown .=' '.$this->prefix.'  ul li ul li div.dropdown-menucss {display: block;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav.megamenu ul li ul li #arrow-icon {display: none;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav.megamenu {border: 1px solid #6c5a5a;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav.megamenu ul.level1 li.megacss a {  padding: 10px 8px 10px 8px!important;}';
                $dropdown .=' '.$this->prefix.'  .group-title {  padding-left:10px!important;}';
                $dropdown .=' }'; 

                $dropdown .=' @media screen and (max-width: 480px) {';  
                $dropdown .=' '.$this->prefix.' #js-mainnav.megamenu .childcontent-inner-wrap{ margin: -2px 0px 6px 0px !important;}';
                $dropdown .=' '.$this->prefix.'  #js-mainnav.megamenu #arrow-icon{padding: 5px 10px!important;}';
                $dropdown .=' '.$this->prefix.' a{text-decoration: none;}';
                $dropdown .=' }';
            }
            return $dropdown;
	}

	protected function _reponsive(&$params){
            $reponsive = '';
                $res= $params->get('responsive_menu','1');
                if($res==1){
                    $reponsive .=' '.$this->prefix.'   #js-mainnav .js-megamenu{display: block;}';
                    $reponsive .=' @media screen and (max-width: 768px) {';  
                    $reponsive .=' '.$this->prefix.'  .megamenu-sticky{text-align: left;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu.noJS #css3-megaMenuToggle,'.$this->prefix.'  #js-mainnav.megamenu #js-megaMenuToggle{display: block;padding: 12px 15px;cursor: pointer;font-size: 10px;text-transform: uppercase; text-align: left;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu #js-megaMenuToggle{display: block !important;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu.noJS #css3-megaMenuToggle .megaMenuToggle-icon, '.$this->prefix.' #js-mainnav.megamenu #js-megaMenuToggle .megaMenuToggle-icon {display: inline-block;background: rgba(230, 230, 230, 0.7);height: 2px; width: 16px; position: relative; float: right; margin-top: 10px;text-align: left; }';
                    //$reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu.noJS #css3-megaMenuToggle .megaMenuToggle-icon:before, '.$this->prefix.' #js-mainnav.megamenu #js-megaMenuToggle .megaMenuToggle-icon:before{ content: '';position: absolute;background: rgba(230, 230, 230, 0.8);height: 2px;  width: 16px; top: -4px;}';
                    //$reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu.noJS #css3-megaMenuToggle .megaMenuToggle-icon:after, '.$this->prefix.' #js-mainnav.megamenu #js-megaMenuToggle .megaMenuToggle-icon:after{ content: ''; position: absolute;background: rgba(230, 230, 230, 0.9); height: 2px; width: 16px; top: -8px;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu{width: 100% !important; overflow: hidden;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu ul.level0{float:none !important; font-size: 12px;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu ul.level0 li.megacss{float:none !important;position:relative; background-image:none !important;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu ul.megamenu li.megacss a.megacss span.menu-desc{display: none;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu ul.level1 li.megacss span.megacss, '.$this->prefix.' #js-mainnav.megamenu ul.level1 li.megacss a.megacss{padding-left:20px !important;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu ul.level2 li.megacss span.megacss, '.$this->prefix.' #js-mainnav.megamenu ul.level2 li.megacss a.megacss {padding-left:45px !important;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu ul.level3 li.megacss span.megacss, '.$this->prefix.' #js-mainnav.megamenu ul.level3 li.megacss a.megacss{padding-left:70px !important;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu ul.level4 li.megacss span.megacss,   '.$this->prefix.' #js-mainnav.megamenu ul.level4 li.megacss a.megacss{padding-left:90px !important;}'; 
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu ul.level0 li.megacss span.megacss,' . $this->prefix . ' #js-mainnav.megamenu ul.level0 li.megacss a.megacss{padding:15px 10px;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu .js-megamenu{font-size: 12px;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu.megamenu.vertical.right ul.level0 li.megacss a.megacss{text-align:left !important;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu.megamenu.vertical.right .js-megamenu ul.level0 li.megacss .childcontent{right:0px !important;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu .js-megamenu li.megacss .childcontent{height: 100% !important; margin: 0 !important;position: relative;width: 100% !important; overflow:visible !important; le }';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu ul.level0 li a.haschild.megacss span.menu-title,' . $this->prefix . ' #js-mainnav.megamenu ul.level0 li a.haschild-over.megacss span.menu-title{background:none !important; padding-left:0px !important;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu ul.level1 li.haschild, ' . $this->prefix . ' #js-mainnav.megamenu ul.level1 li.haschild-over {background: none !important;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu .childcontent-inner-wrap,' . $this->prefix . ' #js-mainnav.megamenu .childcontent-inner, ' . $this->prefix . ' #js-mainnav.megamenu .megacol{width:100% !important;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu ul.megamenu {margin: 0px !important;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu .megacol,.childcontent-inner{border:none !important; float: none !important;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu ul.level0 li.haschild span.arrow-icon,' . $this->prefix . ' #js-mainnav.megamenu ul.level0 li.haschild-over span.arrow-icon{ display: block; float: right;position: absolute; right: 2px; top:2px; z-index: 99; }';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu ul.level0 li.haschild span.arrow-icon,' . $this->prefix . ' #js-mainnav.megamenu ul.level0 li.haschild-over span.arrow-icon {display:block; cursor: pointer;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu ul.level0 li.haschild span.arrow-icon{background: url("../images/arrow_down.png") no-repeat scroll 100% 0px transparent !important;	display: block !important; text-indent: 9999px;width: 40px;height: 31px; border: 0 none !important; padding: 0 !important;margin: 0 !important; -webkit-box-shadow: none !important;-moz-box-shadow: none !important; box-shadow: none !important; }';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu ul.level0 li.haschild-over span.arrow-icon{background: url("../images/close.png") no-repeat scroll 100% 0 transparent;display: block !important; height: 31px;text-indent: 9999px; width: 40px; padding: 0 !important;margin: 0 !important;border: 0 none !important; -webkit-box-shadow: none !important; -moz-box-shadow: none !important;box-shadow: none !important; }';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu ul.level1 li.megacss span.arrow-icon{display:none;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu ul.level1 li.haschild span.arrow-icon,' . $this->prefix . ' #js-mainnav.megamenu ul.level1 li.haschild-over span.arrow-icon{display: block; float: right; position: absolute;right: 2px; top:2px; z-index: 99; }';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu ul.level0 li.megacss.submenu-align-fullwidth{position: relative !important;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu.noJS ul.megamenu li{padding: 0 !important; border-bottom: 1px solid rgba(255, 255, 255, 0.3); border-radius: 0 !important;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu.noJS ul.megamenu li a{position: relative;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu.noJS #css3-megaMenuToggle{display: block; border-radius: 5px;padding: 15px; }';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu.noJS:hover ul.megamenu li{display: block;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu.noJS ul.megamenu li.haschild > div.childcontent {display: none;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu.noJS ul.megamenu li.haschild:hover > div.childcontent{display: block;}';
                    $reponsive .=' '.$this->prefix.'  #js-mainnav.megamenu.noJS ul.level0 li.megacss.haschild:hover > a.haschild:after{border-top-color: #fff;}';
                    $reponsive .=' }';
                }
         return $reponsive;       
	}
    protected function _renderDropdowStyle(&$params){
        $logoCssDropdow = '';
       
        return $logoCssDropdow;  
    }

	public function HextoRBG($hex){
		$hex = str_replace("#", "", $hex);
		$color = array();
		if(strlen($hex) == 3) {
			$color['r'] = hexdec(substr($hex, 0, 1) . $r);
			$color['g'] = hexdec(substr($hex, 1, 1) . $g);
			$color['b'] = hexdec(substr($hex, 2, 1) . $b);
		}
		else if(strlen($hex) == 6) {
			$color['r'] = hexdec(substr($hex, 0, 2));
			$color['g'] = hexdec(substr($hex, 2, 2));
			$color['b'] = hexdec(substr($hex, 4, 2));
		}

		return $color;
	}
	
	public function RBGtoHex($r, $g, $b){
		$hex = "#";
		$hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
		$hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
		$hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);

		return $hex;
	}
	
	protected  function getCssData(&$params){
		$dataCss  = '';		
                $dataCss .= $this->_dropdown($params);
                $dataCss .= $this->_reponsive($params);
                $dataCss .= $this->_renderDropdowStyle($params);
                $dataCss .= $params->get('custom_css');
		return $dataCss;
	}
	
	public static function process(&$params,$filename,$prefix=''){
		$css = new modJUXMegaMenu($prefix);
               
                    return $css->_processor($params, $filename);
                
                
	}
	
	protected function _processor(&$params,$filename = 'custom.css'){
		
		$minCss = "/*===============================\n time:".($params->get('juxtime',''))."\n================================================================================*/\n";
		//$path = JPath::clean($path);
		$file = JPATH_SITE.'/'.$filename;
//		if (JFile::exists($file)){
//			$data = file($file);
//			$time = '';
//			foreach ($data as $k=>$v){
//				if ($k == 1){
//					$time = trim($v);
//					break;
//				}
//			}
//			$timeArr =  explode(':',$time);
//
//			if ($timeArr[1] != $params->get('juxtime','')){
//				$minCss .= Minify_CSS_Compressor::process(self::getCssData($params));
//				return JFile::write($file, $minCss);
//			}
//			return true;	
//		}else{
//			$minCss .= Minify_CSS_Compressor::process(self::getCssData($params));
//			return JFile::write($file, $minCss);
//
//		}
                    $minCss .= Minify_CSS_Compressor::process(self::getCssData($params));
              
                     return JFile::write($file, $minCss);
				
                    return false;
            
	}
}

if (!class_exists('Minify_CSS_Compressor')){
	/**
	* Class Minify_CSS_Compressor
	* @package Minify
	*/
	
	/**
	* Compress CSS
	*
	* This is a heavy regex-based removal of whitespace, unnecessary
	* comments and tokens, and some CSS value minimization, where practical.
	* Many steps have been taken to avoid breaking comment-based hacks,
	* including the ie5/mac filter (and its inversion), but expect tricky
	* hacks involving comment tokens in 'content' value strings to break
	* minimization badly. A test suite is available.
	*
	* @package Minify
	* @author Stephen Clay <steve@mrclay.org>
	* @author http://code.google.com/u/1stvamp/ (Issue 64 patch)
	*/
	class Minify_CSS_Compressor {

	    /**
	* Minify a CSS string
	*
	* @param string $css
	*
	* @param array $options (currently ignored)
	*
	* @return string
	*/
	public static function process($css, $options = array())
	{
		$obj = new Minify_CSS_Compressor($options);
		return $obj->_process($css);
	}

	    /**
	* @var array
	*/
	protected $_options = null;

	    /**
	* Are we "in" a hack? I.e. are some browsers targetted until the next comment?
	*
	* @var bool
	*/
	protected $_inHack = false;


	    /**
	* Constructor
	*
	* @param array $options (currently ignored)
	*/
	private function __construct($options) {
		$this->_options = $options;
	}

	    /**
	* Minify a CSS string
	*
	* @param string $css
	*
	* @return string
	*/
	protected function _process($css)
	{
		$css = str_replace("\r\n", "\n", $css);

	        // preserve empty comment after '>'
	        // http://www.webdevout.net/css-hacks#in_css-selectors
		$css = preg_replace('@>/\\*\\s*\\*/@', '>/*keep*/', $css);

	        // preserve empty comment between property and value
	        // http://css-discuss.incutio.com/?page=BoxModelHack
		$css = preg_replace('@/\\*\\s*\\*/\\s*:@', '/*keep*/:', $css);
		$css = preg_replace('@:\\s*/\\*\\s*\\*/@', ':/*keep*/', $css);

	        // apply callback to all valid comments (and strip out surrounding ws
		$css = preg_replace_callback('@\\s*/\\*([\\s\\S]*?)\\*/\\s*@'
			,array($this, '_commentCB'), $css);

	        // remove ws around { } and last semicolon in declaration block
		$css = preg_replace('/\\s*{\\s*/', '{', $css);
		$css = preg_replace('/;?\\s*}\\s*/', '}', $css);

	        // remove ws surrounding semicolons
		$css = preg_replace('/\\s*;\\s*/', ';', $css);

	        // remove ws around urls
		$css = preg_replace('/
			url\\( # url(
				\\s*
				([^\\)]+?) # 1 = the URL (really just a bunch of non right parenthesis)
		\\s*
		\\) # )
		/x', 'url($1)', $css);

	        // remove ws between rules and colons
		$css = preg_replace('/
			\\s*
			([{;]) # 1 = beginning of block or rule separator
				\\s*
				([\\*_]?[\\w\\-]+) # 2 = property (and maybe IE filter)
				\\s*
				:
				\\s*
				(\\b|[#\'"-]) # 3 = first character of a value
				/x', '$1$2:$3', $css);

	        // remove ws in selectors
		$css = preg_replace_callback('/
			(?: # non-capture
				\\s*
				[^~>+,\\s]+ # selector part
				\\s*
				[,>+~] # combinators
				)+
		\\s*
		[^~>+,\\s]+ # selector part
		{ # open declaration block
			/x'
			,array($this, '_selectorsCB'), $css);

	        // minimize hex colors
		$css = preg_replace('/([^=])#([a-f\\d])\\2([a-f\\d])\\3([a-f\\d])\\4([\\s;\\}])/i'
			, '$1#$2$3$4$5', $css);

	        // remove spaces between font families
		$css = preg_replace_callback('/font-family:([^;}]+)([;}])/'
			,array($this, '_fontFamilyCB'), $css);

		$css = preg_replace('/@import\\s+url/', '@import url', $css);

	        // replace any ws involving newlines with a single newline
		$css = preg_replace('/[ \\t]*\\n+\\s*/', "\n", $css);

	        // separate common descendent selectors w/ newlines (to limit line lengths)
		$css = preg_replace('/([\\w#\\.\\*]+)\\s+([\\w#\\.\\*]+){/', "$1\n$2{", $css);

	        // Use newline after 1st numeric value (to limit line lengths).
		$css = preg_replace('/
			((?:padding|margin|border|outline):\\d+(?:px|em)?) # 1 = prop : 1st numeric value
			\\s+
			/x'
			,"$1\n", $css);

	        // prevent triggering IE6 bug: http://www.crankygeek.com/ie6pebug/
		$css = preg_replace('/:first-l(etter|ine)\\{/', ':first-l$1 {', $css);

		return trim($css);
	}

	    /**
	* Replace what looks like a set of selectors
	*
	* @param array $m regex matches
	*
	* @return string
	*/
	protected function _selectorsCB($m)
	{
	        // remove ws around the combinators
		return preg_replace('/\\s*([,>+~])\\s*/', '$1', $m[0]);
	}

	    /**
	* Process a comment and return a replacement
	*
	* @param array $m regex matches
	*
	* @return string
	*/
	protected function _commentCB($m)
	{
		$hasSurroundingWs = (trim($m[0]) !== $m[1]);
		$m = $m[1];
	        // $m is the comment content w/o the surrounding tokens,
	        // but the return value will replace the entire comment.
		if ($m === 'keep') {
			return '/**/';
		}
		if ($m === '" "') {
	            // component of http://tantek.com/CSS/Examples/midpass.html
			return '/*" "*/';
		}
		if (preg_match('@";\\}\\s*\\}/\\*\\s+@', $m)) {
	            // component of http://tantek.com/CSS/Examples/midpass.html
			return '/*";}}/* */';
		}
		if ($this->_inHack) {
	            // inversion: feeding only to one browser
			if (preg_match('@
				^/ # comment started like /*/
				\\s*
				(\\S[\\s\\S]+?) # has at least some non-ws content
				\\s*
				/\\* # ends like /*/ or /**/
				@x', $m, $n)) {
	                // end hack mode after this comment, but preserve the hack and comment content
				$this->_inHack = false;
			return "/*/{$n[1]}/**/";
		}
	}
	        if (substr($m, -1) === '\\') { // comment ends like \*/
	            // begin hack mode and preserve hack
	        	$this->_inHack = true;
	        	return '/*\\*/';
	        }
	        if ($m !== '' && $m[0] === '/') { // comment looks like /*/ foo */
	            // begin hack mode and preserve hack
	        	$this->_inHack = true;
	        	return '/*/*/';
	        }
	        if ($this->_inHack) {
	            // a regular comment ends hack mode but should be preserved
	        	$this->_inHack = false;
	        	return '/**/';
	        }
	        // Issue 107: if there's any surrounding whitespace, it may be important, so
	        // replace the comment with a single space
	        return $hasSurroundingWs // remove all other comments
	        ? ' '
	        : '';
	    }
	    
	    /**
	* Process a font-family listing and return a replacement
	*
	* @param array $m regex matches
	*
	* @return string
	*/
	protected function _fontFamilyCB($m)
	{
	        // Issue 210: must not eliminate WS between words in unquoted families
		$pieces = preg_split('/(\'[^\']+\'|"[^"]+")/', $m[1], null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
		$out = 'font-family:';
		while (null !== ($piece = array_shift($pieces))) {
			if ($piece[0] !== '"' && $piece[0] !== "'") {
				$piece = preg_replace('/\\s+/', ' ', $piece);
				$piece = preg_replace('/\\s?,\\s?/', ',', $piece);
			}
			$out .= $piece;
		}
		return $out . $m[2];
	}
}
}