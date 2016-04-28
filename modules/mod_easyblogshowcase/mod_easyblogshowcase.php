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

jimport('joomla.filesystem.file');

$engine = JPATH_ADMINISTRATOR . '/components/com_easyblog/includes/easyblog.php';

if (!JFile::exists($engine)) {
	return;
}

// Include constants
require_once($engine);
require_once(__DIR__ . '/helper.php');

// Retrieve a list of posts
$posts = modEasyBlogShowcaseHelper::getItems($params);

if (!$posts) {
	return;
}

// Ensure that all script are loaded in the site.
EB::init('module');

// Attach modules stylesheet
EB::stylesheet('module')->attach();

$config = EB::config();

// Should we display the ratings.
$disabled = $params->get('enableratings') ? false : true;
$layout = $params->get('layout', 'default');
$autoplay = $params->get('autorotate', false) ? 1 : 0;
$autoplayInterval = $params->get('autorotate_seconds', 30);

require(JModuleHelper::getLayoutPath('mod_easyblogshowcase', $layout));