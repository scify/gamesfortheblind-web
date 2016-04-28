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

class EasyBlogAvatarMightyTouch
{
	public function exists()
	{
		$file 	= JPATH_ROOT . '/components/com_juser/api.php';

		if (!JFile::exists($file)) {
			return false;
		}

		require_once($file);

		return true;
	}

	public function getAvatar($profile)
	{
		if (!$this->exists()) {
			return false;
		}
		
		$avatar = JSUserApi::getAvatarPath($profile->id);

		return $avatar;
	}
}