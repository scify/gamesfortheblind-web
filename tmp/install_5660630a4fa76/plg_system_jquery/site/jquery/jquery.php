<?php

/**
 * jQuery loading plugin
 *
 * @version		1.5.0
 * @author		Martin Rasser, Elovaris Applications
 * @copyright	Copyright (C) 2009 Elovaris Applications. All rights reserved.
 * @license		MIT license (http://www.opensource.org/licenses/mit-license.php) or GNU/GPL (http://www.gnu.org/licenses/gpl-2.0.html)
 */

// If someone puts nasty stuff in the version parameter, drop down dead
if (strstr('/',$_GET['version']) !== false) die();
if (strstr("\\",$_GET['version']) !== false) die();
if (strstr('..',$_GET['version']) !== false) die();

if (!isset($_GET['version'])) {
	$_GET['version'] = '1.8.3';
}

$file = 'jquery-'.$_GET['version'].'.'. (isset($_GET['debug']) ? 'src':'min')  .'.js';

ob_start("ob_gzhandler");

header('Content-Type: application/x-javascript');
header('Expires: '.date('r', strtotime('+3 years',time())));

readfile(dirname(__FILE__).'/'.$file);
echo "\njQuery.noConflict();";
