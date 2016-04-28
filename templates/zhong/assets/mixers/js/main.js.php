<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

define('_ZHONGFRAMEWORK',true);
define("ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR",dirname(__FILE__)."/");
define('ZHONGFRAMEWORK_PARAMETER_ENABLE_MINIFY_METHODS',htmlentities($_GET['minify'],ENT_QUOTES));

//Set the JS content type for the output file
header('Content-type: text/javascript');

//Caching
header("Cache-Control: must-revalidate");
$expires = "Expires: " . gmdate("D, d M Y H:i:s", time() + 60 * 60 * 24 * 7) . " GMT"; // Cache for 1 week
header($expires);

//Initialize the compressing method
include_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'../../../application/helpers/minify_methods.php');
ob_start("simpleJSMinifyMethod");

//Include main.js & plugins.js
include_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'../../javascript/plugins.js');
include_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'../../javascript/main.js');

//Include custom JavaScript
include_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'../../custom-overrides/js/custom-scripts.js');

//Flush the output (due to the compression method if set)
ob_end_flush();

?>
