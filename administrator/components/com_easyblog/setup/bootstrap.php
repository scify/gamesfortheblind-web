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
jimport('joomla.filesystem.folder');

// Get application
$app = JFactory::getApplication();
$input = $app->input;

// Ensure that the Joomla sections don't appear.
$input->set('tmpl', 'component');

// Determines if the current mode is re-install
$reinstall = $input->get('reinstall', false, 'bool') || $input->get('install', false, 'bool');

// If the mode is update, we need to get the latest version
$update = $input->get('update', false, 'bool');

// Determines if we are now in developer mode.
$developer = $input->get('developer', false, 'bool');

// If this is in developer mode, we need to set the session
if ($developer) {
	$session = JFactory::getSession();
	$session->set('easyblog.developer', true);
}

if (!function_exists('dump')) {

	function isDevelopment()
	{
		$session = JFactory::getSession();
		$developer = $session->get('easyblog.developer');

		return $developer;
	}

	function dump()
	{
		$args = func_get_args();

		echo '<pre>';
		
		foreach ($args as $arg) {
			var_dump($arg);
		}
		echo '</pre>';

		exit;
	}
}

############################################################
#### Constants
############################################################
$path = dirname(__FILE__);
define('EB_PACKAGES', $path . '/packages');
define('EB_CONFIG', $path . '/config');
define('EB_THEMES', $path . '/themes');
define('EB_LIB', $path . '/libraries');
define('EB_CONTROLLERS', $path . '/controllers');
define('EB_SERVER', 'http://stackideas.com');
define('EB_VERIFIER', 'http://stackideas.com/updater/verify');
define('EB_MANIFEST', 'http://stackideas.com/updater/manifests/easyblog');
define('EB_SETUP_URL', JURI::base() . 'components/com_easyblog/setup');
define('EB_TMP', $path . '/tmp');
define('EB_BETA', false);


############################################################
#### Process controller
############################################################
$controller = $input->get('controller', '', 'cmd');
$task = $input->get('task', '');

if (!empty($controller)) {

	$file = strtolower($controller) . '.' . strtolower($task) . '.php';
	$file = EB_CONTROLLERS . '/' . $file;

	require_once($file);

	$className = 'EasyBlogController' . ucfirst($controller) . ucfirst($task);
	$controller = new $className();
	return $controller->execute();
}


############################################################
#### Initialization
############################################################
$contents = JFile::read(EB_CONFIG . '/install.json');
$steps = json_decode($contents);

############################################################
#### Workflow
############################################################
$active = $input->get('active', 0, 'default');

if ($active === 'complete') {
	$activeStep = new stdClass();

	$activeStep->title = JText::_('COM_EASYBLOG_INSTALLER_INSTALLATION_COMPLETED');
	$activeStep->template = 'complete';

	// Assign class names to the step items.
	if ($steps) {
		foreach ($steps as $step) {
			$step->className = ' done';
		}
	}
} else {

	if ($active == 0) {
		$active = 1;
		$stepIndex = 0;
	} else {
		$active += 1;
		$stepIndex = $active - 1;
	}

	// Get the active step object.
	$activeStep = $steps[$stepIndex];

	// Assign class names to the step items.
	foreach ($steps as $step) {
		$step->className = $step->index == $active || $step->index < $active ? ' current' : '';
		$step->className .= $step->index < $active ? ' done' : '';
	}
}

require(EB_THEMES . '/default.php');