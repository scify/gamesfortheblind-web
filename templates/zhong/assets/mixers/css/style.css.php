<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

define('_ZHONGFRAMEWORK',true);
define('ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR',dirname(__FILE__)."/");
define('ZHONGFRAMEWORK_LAYOUT_MODE',htmlentities($_GET['layoutMode'],ENT_QUOTES));
define('ZHONGFRAMEWORK_GRAPHIC_MODE',htmlentities($_GET['graphicMode'],ENT_QUOTES));
define('ZHONGFRAMEWORK_PARAMETER_ENABLE_MINIFY_METHODS',htmlentities($_GET['minify'],ENT_QUOTES));
define('ZHONGFRAMEWORK_PARENT_CMS_PLATFORM',htmlentities($_GET['platform'],ENT_QUOTES));
define('ZHONGFRAMEWORK_PARENT_CMS_RELEASE_CLASS',htmlentities($_GET['platformVersion'],ENT_QUOTES));
define('ZHONGFRAMEWORK_WEBSITE_TEXT_DIRECTION',htmlentities($_GET['textDirection'],ENT_QUOTES));

//Include the main style handler
include_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'../../../application/handlers/assets/stylesheets_handler.php');

/*----------------------------------------------------------------
-  CSS INCLUSION METHOD
---------------------------------------------------------------- */
	
//Set the CSS content type for the output file
header('Content-type: text/css');

//Caching
header("Cache-Control: must-revalidate");
$expires = "Expires: " . gmdate("D, d M Y H:i:s", time() + 60 * 60 * 24 * 7) . " GMT"; // Cache for 1 weeks
header($expires);

//Initialize the compressing method
include_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'../../../application/helpers/minify_methods.php');
ob_start("simpleCSSMinifyMethod");

//Include all CSS files
foreach($css_files_to_include as $css_file){
	include_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'../../'.$css_file);
	}

//Flush the output (due to the compressing method)
ob_end_flush();

?>
