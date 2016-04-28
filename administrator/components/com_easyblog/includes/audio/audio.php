<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class EasyBlogAudio extends EasyBlog
{
	public function strip($content)
	{
		// In case Joomla tries to entity the contents, we need to replace accordingly.
		$content = str_ireplace( '&quot;' , '"' , $content );
		$pattern = '/\[embed=audio\](.*)\[\/embed\]/uiU';
		$replace = '';

		return preg_replace( $pattern , $replace , $content );
	}

	/**
	 * Retrieves the html codes for the player
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getPlayer($file)
	{
		$theme = EB::template();

		// Generate a unique id for the player
		$uid = uniqid();

		$theme->set('file', $file);
		$theme->set('uid', $uid);

		$output = $theme->output('site/audio/player');

		return $output;
	}

	/**
	 * Used in conjunction with EB::formatter()
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function format(EasyBlogPost &$blog, $plain = false)
	{
		$blog->intro 	= $this->process($blog->intro, $plain);
		$blog->content	= $this->process($blog->content, $plain);
	}

	/**
	 * Used in conjunction with EB::formatter()
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function stripCodes(EasyBlogPost &$post)
	{
		$post->text = $this->strip($post->text);
	}

	/**
	 * Retrieves a list of audio items from the content
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function getItems($content)
	{
		$pattern = '/\[embed=audio\](.*)\[\/embed\]/uiU';
		$audios = array();

		preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

		if (!$matches) {
			return $audios;
		}

		foreach ($matches as $match) {

			$text = $match[0];
			$jsonData = $match[1];

			$audio = json_decode($jsonData, true);

			if (!$audio) {
				continue;
			}

			$options = array();

			// generate the player
			$audios[] = EB::media()->renderAudioPlayer($audio['uri'], $options);
		}

		return $audios;
	}

	/**
	 * Processes the audio tags on the content for legacy posts
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function process($contents)
	{
		$pattern = '/\[embed=audio\](.*)\[\/embed\]/uiU';

		preg_match_all($pattern, $contents, $matches, PREG_SET_ORDER);

		if (empty($matches)) {
			return $contents;
		}

		// Get the config library
		$config = EB::config();

		foreach ($matches as $match) {

			list($text, $json) = $match;

			// Convert the json string into an object
			$audio = json_decode($json);

			if (!$audio) {
				$contents = JString::str_ireplace($text, '', $contents);

				continue;
			}

			// New EasyBlog 5 format
			if (isset($audio->uri)) {
				$uri = $audio->uri;
			} else {
				// Generate a new uri for the new audio player
				$uri = $audio->place . $audio->file;
			}

			$autostart = isset($audio->autostart) && $audio->autostart ? true : false;

			$options = array('autoplay' => $autostart);
			$player = EB::media()->renderAudioPlayer($uri, $options);

			// Alter the contents of the file
			$contents = JString::str_ireplace($text, $player, $contents);
		}

		return $contents;
	}
}
