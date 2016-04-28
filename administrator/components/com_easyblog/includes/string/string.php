<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class EasyBlogString
{
	public function __construct()
	{
		$this->config = EB::config();
	}

	/**
	 * Normalizes an URL and ensure that it contains the protocol
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function normalizeUrl($url)
	{
		$url = trim($url);
		$regex = '/^(http|https|ftp):\/\/*?/i';

		$matched = preg_match($regex, $url, $matches);

		if ($matches) {
			return $url;
		}

		return 'http://' . $url;
	}

	/**
	 * Strip off known extension tags
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function stripKnownTags($text)
	{
		// Remove JFBConnect codes.
		$pattern = '/\{JFBCLike(.*)\}/i';
		$text = preg_replace($pattern, '', $text);

		return $text;
	}

	/**
	 * Retrieves the language code
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getLangCode()
	{
		$lang 		= JFactory::getLanguage();
		$locale 	= $lang->getLocale();
		$langCode    = null;

		if(empty($locale))
		{
			$langCode    = 'en-GB';
		}
		else
		{
			$langTag    = $locale[0];
			$langData    = explode('.', $langTag);
			$langCode   = JString::str_ireplace('_', '-', $langData[0]);
		}
		return $langCode;
	}

	public function getNoun($var, $count, $includeCount = false)
	{
		$zeroAsPlural = $this->config->get('layout_zero_as_plural');

		$count = (int) $count;

		$var = ($count===1 || $count===-1 || ($count===0 && !$zeroAsPlural)) ? $var . '_SINGULAR' : $var . '_PLURAL';

		if ($includeCount) {
			return JText::sprintf($var, $count);
		}

		return JText::_($var);
	}

	/*
	 * Convert string from ejax post into assoc-array
	 * param - string
	 * return - assc-array
	 */
	public static function ejaxPostToArray($params)
	{
		$post		= array();

		foreach($params as $item)
		{
			$pair   = explode('=', $item);

			if( isset( $pair[ 0 ] ) && isset( $pair[ 1 ] ) )
			{
				$key	= $pair[0];
				$value	= EasyBlogStringHelper::ejaxUrlDecode( $pair[ 1 ] );

				if( JString::stristr( $key , '[]' ) !== false )
				{
					$key			= JString::str_ireplace( '[]' , '' , $key );
					$post[ $key ][]	= $value;
				}
				else
				{
					$post[ $key ] = $value;
				}
			}
		}

		return $post;
	}

	/**
	 * Captures the first <img> tag in a given content
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getImage($contents)
	{
		$image = false;
		$pattern = '/<\s*img [^\>]*src\s*=\s*[\""\']?([^\""\'\s>]*)/i';

		preg_match($pattern, $contents, $matches);

		if (!$matches) {
			return $image;
		}

		$image = isset($matches[1]) ? $matches[1] : '';

		if (stristr($image, 'https:') === false && stristr($image, 'http:') === false) {

			if (stristr($image, '//') === false) {
				$image = rtrim(JURI::root(), '/') . '/' . ltrim($image);
			} else {
				$uri = JURI::getInstance();

				$scheme = $uri->toString(array('scheme'));

				$scheme = str_replace('://', ':', $scheme);

				$image = $scheme . $image;
			}
		}

		return $image;
	}

	/**
	 * This would inject a rel=nofollow attribute into anchor links.
	 *
	 * @access	public
	 * @param 	string	$content 	The content subject.
	 * @return 	string 				The content which is fixed.
	 */
	public static function addNoFollow($content)
	{
		// @rule: Try to replace any rel tag that already exist.
		$pattern = '/rel=[^>]*"/i';

		preg_match($pattern, $content, $matches);

		if ($matches) {
			foreach ($matches as $match) {
				$result = str_ireplace('rel="', 'rel="nofollow', $match);
				$content = str_ireplace($match, $result, $content);
			}
		} else {
			$content = str_ireplace('<a', '<a rel="nofollow"', $content);
		}
		return $content;
	}

	/**
	 * A pior php 4.3.0 version of
	 * html_entity_decode
	 */
	public static function unhtmlentities($string)
	{
		// replace numeric entities
		$string = preg_replace_callback('~&#x([0-9a-f]+);~i', function($m) { return chr(hexdec($m[1])); }, $string);
		$string = preg_replace_callback('~&#([0-9]+);~', function($m) { return chr($m[1]); }, $string);
		// replace literal entities
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		$trans_tbl = array_flip($trans_tbl);
		return strtr($string, $trans_tbl);
	}




	public static function linkTweets( $source )
	{
		// Link hashes
		$pattern	= '/\#(\w*)/i';
		$replace	= '<a target="_blank" href="http://twitter.com/#!/search?q=$1" rel="nofollow">$0</a>';
		$source		= preg_replace( $pattern , $replace , $source );

		// Link authors
		$pattern	= '/\@(\w*)/i';
		$replace	= '<a target="_blank" href="http://twitter.com/$1" rel="nofollow">$0</a>';
		$source		= preg_replace( $pattern , $replace , $source );

		return  $source;
	}

	public static function url2link( $string )
	{
		$newString  = $string;

		preg_match('/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms', $newString, $matches);

		$patterns   = array('/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms',
							"/([\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/i",
							"/([^\w\/])(www\.[a-z0-9\-]+\.[a-z0-9\-]+)/i");

		$replace    = array('[bbcode-url]',
							"<a target=\"_blank\" href=\"$1\" rel=\"nofollow\">$1</a>",
							"<a target=\"_blank\" href=\"http://$2\" rel=\"nofollow\">$2</a>");

		$newString	= preg_replace($patterns, $replace, $newString);

		//now convert back again.
		if(count($matches) > 0)
		{
			$patterns   = array('/\[bbcode\-url\]/ms');
			$replace    = array($matches[0]);
			$newString	= preg_replace($patterns, $replace, $newString);
		}

		return $newString;
	}

	/**
	 * Ensure that the link contains a valid http link
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function htmlAnchorLink($url, $string)
	{
		if (!$string) {
			return $string;
		}

		//
		if( JString::strpos( $url , 'http://' ) === false && JString::strpos( $url , 'https://' ) === false )
		{
			$url 	= 'http://' . $url;
		}

		$pattern 	= "/(((http[s]?:\/\/)|(www\.))(([a-z][-a-z0-9]+\.)?[a-z][-a-z0-9]+\.[a-z]+(\.[a-z]{2,2})?)\/?[a-z0-9._\/~#&=;%+?-]+[a-z0-9\/#=?]{1,1})/is";
		$newString 	= preg_replace($pattern, '<a href="$1" target="_blank" rel="nofollow">' . $string. '</a>', $url);

		//this is not a link
		if ($newString == $url) {
			return $string;
		}

		return $newString;
	}

	public static function escape( $var )
	{
		return htmlspecialchars( $var, ENT_COMPAT, 'UTF-8' );
	}

	public static function tidyHTMLContent( $content )
	{
		require_once(dirname(__FILE__) . '/helpers/htmlawed.php');

		$htmLawedConfig = array( 'cdata' => 1,
								 'clean_ms_char' => 1,
								 'comment' => 1,
								 'safe' => 1,
								 'tidy' => 1,
								 'valid_xhtml' =>1,
								 'deny_attribute' => '* -title -href -target -alt',
								 'keep_bad' => 6,
								 'anti_link_spam' => array('`.`','')
							);

		//return htmLawed( $content, $htmLawedConfig);
		return htmLawed( $content );
	}

// 	function convert2UTF8( $html )
// 	{
// 		$encoding = 'iso-8859-1';
// 		$encoding   = strtoupper( $encoding );
//
// 		$html 	= @mb_convert_encoding($html, 'UTF-8', $encoding);
// 		return $html;
// 	}

	/* reference: http://publicmind.in/blog/url-encoding/ */
	public static function encodeURL( $url )
	{
		$reserved = array(
		":" => '!%3A!ui',
		"/" => '!%2F!ui',
		"?" => '!%3F!ui',
		"#" => '!%23!ui',
		"[" => '!%5B!ui',
		"]" => '!%5D!ui',
		"@" => '!%40!ui',
		"!" => '!%21!ui',
		"$" => '!%24!ui',
		"&" => '!%26!ui',
		"'" => '!%27!ui',
		"(" => '!%28!ui',
		")" => '!%29!ui',
		"*" => '!%2A!ui',
		"+" => '!%2B!ui',
		"," => '!%2C!ui',
		";" => '!%3B!ui',
		"=" => '!%3D!ui',
		"%" => '!%25!ui',
		);

		$url = str_replace(array('%09','%0A','%0B','%0D'),'',$url); // removes nasty whitespace
		$url = rawurlencode($url);
		$url = preg_replace(array_values($reserved), array_keys($reserved), $url);
		return $url;
	}

	public static function rel2abs($rel, $base)
	{
		/* return if already absolute URL */
		if (parse_url($rel, PHP_URL_SCHEME) != '') return $rel;

		/* queries and anchors */
		if (@$rel[0]=='#' || @$rel[0]=='?') return $base.$rel;

		/* parse base URL and convert to local variables:
		   $scheme, $host, $path */
		extract(parse_url($base));

		/* remove non-directory element from path */
		$path = preg_replace('#/[^/]*$#', '', $path);

		/* destroy path if relative url points to root */
		if ( @$rel[0] == '/') $path = '';

		/* dirty absolute URL */
		$abs = "$host$path/$rel";
		/* replace '//' or '/./' or '/foo/../' with '/' */
		$re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
		for($n=1; $n>0; $abs=preg_replace($re, '/', $abs, -1, $n)) {}

		/* absolute URL is ready! */
		return $scheme.'://'.$abs;
	}

	/**
	 * @author   "Sebastián Grignoli" <grignoli@framework2.com.ar>
	 * @package  forceUTF8
	 * @version  1.1
	 * @link     http://www.framework2.com.ar/dzone/forceUTF8-es/
	 * @example  http://www.framework2.com.ar/dzone/forceUTF8-es/
	  */

	public static function forceUTF8($text)
	{
		if(is_array($text))
		{
		  foreach($text as $k => $v)
		  {
			$text[$k] = EasyBlogStringHelper::forceUTF8($v);
		  }
		  return $text;
		}

		$max = strlen($text);
		$buf = "";
		for($i = 0; $i < $max; $i++){
			$c1 = $text{$i};
			if($c1>="\xc0"){ //Should be converted to UTF8, if it's not UTF8 already
			  $c2 = $i+1 >= $max? "\x00" : $text{$i+1};
			  $c3 = $i+2 >= $max? "\x00" : $text{$i+2};
			  $c4 = $i+3 >= $max? "\x00" : $text{$i+3};
				if($c1 >= "\xc0" & $c1 <= "\xdf"){ //looks like 2 bytes UTF8
					if($c2 >= "\x80" && $c2 <= "\xbf"){ //yeah, almost sure it's UTF8 already
						$buf .= $c1 . $c2;
						$i++;
					} else { //not valid UTF8.  Convert it.
						$cc1 = (chr(ord($c1) / 64) | "\xc0");
						$cc2 = ($c1 & "\x3f") | "\x80";
						$buf .= $cc1 . $cc2;
					}
				} elseif($c1 >= "\xe0" & $c1 <= "\xef"){ //looks like 3 bytes UTF8
					if($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf"){ //yeah, almost sure it's UTF8 already
						$buf .= $c1 . $c2 . $c3;
						$i = $i + 2;
					} else { //not valid UTF8.  Convert it.
						$cc1 = (chr(ord($c1) / 64) | "\xc0");
						$cc2 = ($c1 & "\x3f") | "\x80";
						$buf .= $cc1 . $cc2;
					}
				} elseif($c1 >= "\xf0" & $c1 <= "\xf7"){ //looks like 4 bytes UTF8
					if($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf" && $c4 >= "\x80" && $c4 <= "\xbf"){ //yeah, almost sure it's UTF8 already
						$buf .= $c1 . $c2 . $c3;
						$i = $i + 2;
					} else { //not valid UTF8.  Convert it.
						$cc1 = (chr(ord($c1) / 64) | "\xc0");
						$cc2 = ($c1 & "\x3f") | "\x80";
						$buf .= $cc1 . $cc2;
					}
				} else { //doesn't look like UTF8, but should be converted
						$cc1 = (chr(ord($c1) / 64) | "\xc0");
						$cc2 = (($c1 & "\x3f") | "\x80");
						$buf .= $cc1 . $cc2;
				}
			} elseif(($c1 & "\xc0") == "\x80"){ // needs conversion
					$cc1 = (chr(ord($c1) / 64) | "\xc0");
					$cc2 = (($c1 & "\x3f") | "\x80");
					$buf .= $cc1 . $cc2;
			} else { // it doesn't need convesion
				$buf .= $c1;
			}
		}
		return $buf;
	}

	public static function forceLatin1($text)
	{
	  if(is_array($text)) {
		foreach($text as $k => $v) {
		  $text[$k] = EasyBlogStringHelper::forceLatin1($v);
		}
		return $text;
	  }
	  return utf8_decode( EasyBlogStringHelper::forceUTF8($text) );
	}

	public static function fixUTF8($text)
	{
	  if(is_array($text)) {
		foreach($text as $k => $v) {
		  $text[$k] = EasyBlogStringHelper::fixUTF8($v);
		}
		return $text;
	  }

	  $last = "";
	  while($last <> $text){
		$last = $text;
		$text = EasyBlogStringHelper::forceUTF8( utf8_decode( EasyBlogStringHelper::forceUTF8($text) ) );
	  }
	  return $text;
	}


	/**
	 * Returns an array of blocked words.
	 *
	 * @access	public
	 * @param 	null
	 * @return 	array
	 */
	public function getBlockedWords()
	{
		static $words 	= null;

		if( is_null( $words ) )
		{
			$config 	= EasyBlogHelper::getConfig();
			$words		= trim( $config->get( 'main_blocked_words' ) , ',');

			if( !empty( $words ) )
			{
				$words 		= explode( ',' , $words );
			}
			else
			{
				$words 		= array();
			}

		}

		return $words;
	}

	/**
	 * Determines if the text provided contains any blocked words
	 *
	 * @access	public
	 * @param	string	$text	The text to lookup for
	 * @return	boolean			True if contains blocked words, false otherwise.
	 *
	 */
	public function hasBlockedWords( $text )
	{
		$words		= self::getBlockedWords();

		if( empty( $words ) || !$words )
		{
			return false;
		}

		foreach( $words as $word )
		{
			if( preg_match('/\b'.$word.'\b/i', $text) )
			{
 				// Immediately exit the method since we now know that there's at least
 				// 1 blocked word.
 				return $word;
			}
		}

		return false;
	}

	/**
	 * Determines if the given string is a valid url
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function isHyperlink($string)
	{
		// http://
		$result = JString::substr($string, 0, 7);

		if ($result == 'http://') {
			return true;
		}

		// https://
		$result = JString::substr($string, 0, 8);

		if ($result == 'https://') {
			return true;
		}

		// //path
		$result = JString::substr($string, 0, 2);

		if ($result == '//') {
			return true;
		}

		return false;
	}

	/**
	 * Determines if the domain is valid
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function isValidDomain($url)
	{
		// $regex = '/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/';
		$regex = '/([a-zA-Z0-9\-_]+\.)?[a-zA-Z0-9\-_]+\.[a-zA-Z]{2,5}/';
		preg_match($regex, $url, $matches);

		if (!$matches) {
			return false;
		}

		return true;
	}

	public function isValidEmail($data, $strict = false)
	{
		$regex = $strict?
			'/^([.0-9a-z_-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i' :
			'/^([*+!.&#$¦\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i'
		;

		if (preg_match($regex, trim($data), $matches))
		{
			return array($matches[1], $matches[2]);
		}
		else
		{
			return false;
		}
	}

	public function cleanHtml($content='')
	{
		$pattern = array(
			'/<p><br _mce_bogus="1"><\/p>/i',
			'/<p><br mce_bogus="1"><\/p>/i',
			'/<br _mce_bogus="1">/i',
			'/<br mce_bogus="1">/i',
			'/<p><br><\/p>/i'
		);

		$replace = array('','','','','');
		$content = preg_replace($pattern, $replace, $content);

		return $content;
	}

	/**
	 * Given a set of content, filter the content by normalizing the content
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function filterHtml($content = '')
	{
		static $filter;
		static $filterType;

		// If filter hasn't been initialized before, do it now.
		if (!isset($filter)) {

			jimport('joomla.filter.filterinput');

			// Get tags & attributes that should be stripped.
			$filterTags = EasyBlogAcl::getFilterTags();
			$filterAttrs = EasyBlogAcl::getFilterAttributes();
			$filterType = 'html';

			// Create filter instance.
			$filter = JFilterInput::getInstance($filterTags, $filterAttrs, 1, 1, 0);
			$filter->tagBlacklist  = $filterTags;
			$filter->attrBlacklist = $filterAttrs;

			// Disable filtering if there's nothing to filter
			if (count($filterTags) < 1 && count($filterAttrs) < 1) {
				$filter = false;
			}
		}

		// If we can skip filtering, just return content.
		if ($filter == false) {
			return $content;
		}

		// Strip blacklisted tags & attributes.
		return $filter->clean($content, $filterType);
	}

	/**
	 * Search for an image tag in a given content
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function searchImage($content, $limit = 1)
	{
		$pattern = '#<img[^>]*>#i';

		preg_match($pattern, $content, $matches);

		if ($matches) {

			if ($limit == 1) {
				return $matches[0];
			}

			return $matches;
		}

		return array();
	}
}
