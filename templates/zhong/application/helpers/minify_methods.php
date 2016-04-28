<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

//Define a simple minify method that will be used with CSS and some Javascript
function simpleCSSMinifyMethod($buffer){
	if(ZHONGFRAMEWORK_PARAMETER_ENABLE_MINIFY_METHODS=="true"){
		//Remove comments (NOT the in line ones = '//')
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
		//Remove tabs, spaces, newlines, etc.
		$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
		}
	return $buffer;
	}

//Define a simple minify method that will be used with CSS and some Javascript
//Thanks to: http://code.google.com/p/samstyle-php-framework/source/browse/trunk/sp.php
function simpleJSMinifyMethod($buffer){
	if(ZHONGFRAMEWORK_PARAMETER_ENABLE_MINIFY_METHODS=="true"){
		$regex = array(
			"`^([\t\s]+)`ism"=>'',
			"`^\/\*(.+?)\*\/`ism"=>"",
			"`([\n\A;]+)\/\*(.+?)\*\/`ism"=>"$1",
			"`([\n\A;\s]+)//(.+?)[\n\r]`ism"=>"$1\n",
			"`(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+`ism"=>"\n"
			);
		$buffer = preg_replace(array_keys($regex),$regex,$buffer);
		}
	return $buffer;
	}
?>