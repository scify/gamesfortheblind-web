<?php
/**
* @package      EasyBlog
* @copyright    Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

jimport('joomla.filesystem.file');

$engine = JPATH_ADMINISTRATOR . '/components/com_easyblog/includes/easyblog.php';

if (!JFile::exists($engine)) {
	return;
}

require_once($engine);
require_once(__DIR__ . '/helper.php');

// This module will require the main script file since composer needs to be loaded
EB::init('site');

// Ensure that all script are loaded in the site.
EB::init('module');

// Attach modules stylesheet
EB::stylesheet('site')->attach();
EB::stylesheet('module')->attach();

// Get the current user.
$my = JFactory::getUser();

// Get the return url
$return = modEasyBlogWelcomeHelper::getReturnURL($params);

// Get the blogger object
$author = EB::user($my->id);

// Get available options
$config = EB::config();
$acl = EB::acl();

// Determines if we should allow registration
$usersConfig = JComponentHelper::getParams('com_users');
$allowRegistration = $usersConfig->get('allowUserRegistration');

require(JModuleHelper::getLayoutPath('mod_easyblogwelcome'));
