<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class EasyBlogJConfig
{
	public $helper		= null;

	public function __construct()
	{
		$this->config 	= JFactory::getConfig();
	}

	public function get( $key , $default = null )
	{
		if( EasyBlogHelper::getJoomlaVersion() >= '3.0' )
		{
			return $this->config->get( $key , $default );
		}

		return $this->config->getValue( $key , $default );
	}
}
