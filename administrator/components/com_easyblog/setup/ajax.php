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


############################################################
#### Process ajax calls
############################################################
$app = JFactory::getApplication();

if ($app->input->get('ajax')) {

	// Perform ajax methods here.
	$controller = $app->input->get('controller', '', 'cmd');
	$task = $app->input->get('task', '', 'cmd');

	$file = EB_CONTROLLERS . '/' . strtolower($controller) . '.php';

	require_once($file);

	$class 		= 'EasyBlogController' . ucfirst($controller);
	$controller = new $class();

	return $controller->$task();
}