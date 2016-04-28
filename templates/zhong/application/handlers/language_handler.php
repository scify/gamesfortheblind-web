<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

/*----------------------------------------------------------------
-  TEMPLATE LANGUAGE HANDLER
---------------------------------------------------------------- */

switch(ZHONGFRAMEWORK_WEBSITE_LANGUAGE){
	case "it-it":
		require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/language/it-IT/it-IT_template.php');
		break;
	case "fr-fr":
		require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/language/fr-FR/fr-FR_template.php');
		break;
	case "de-de":
		require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/language/de-DE/de-DE_template.php');
		break;
	case "zh-cn":
		require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/language/zh-CN/zh-CN_template.php');
		break;
	case "nb-no":
		require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/language/nb-NO/nb-NO_template.php');
		break;
	case "nl-nl":
		require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/language/nl-NL/nl-NL_template.php');
		break;
	case "ru-ru":
		require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/language/ru-RU/ru-RU_template.php');
		break;
	case "es-es":
		require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/language/es-ES/es-ES_template.php');
		break;
	case "hr-hr":
		require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/language/hr-HR/hr-HR_template.php');
		break;
	case "el-gr":
		require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/language/el-GR/el-GR_template.php');
		break;
	case "pl-pl":
		require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/language/pl-PL/pl-PL_template.php');
		break;
	default: //The English language is loaded by default
		require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/language/en-GB/en-GB_template.php');
	}

?>