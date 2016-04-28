<?php
/**
 * @package   ZhongFramework - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

if(defined('_JEXEC')){

	//If "_JEXEC" is defined, then the parent CMS is Joomla
	define("ZHONGFRAMEWORK_PARENT_CMS_PLATFORM","Joomla");
	
	//If "JPlatform" is defined (starting from Joomla 1.7), then get the Platform version 
	try{
		//Joomla 1.7.x & 2.5.x = Platform 11.x
		//Joomla 3 = Platform 12.x && 13.x
		define("ZHONGFRAMEWORK_PARENT_CMS_PLATFORM_VERSION", JPlatform::getShortVersion());

		if(substr(ZHONGFRAMEWORK_PARENT_CMS_PLATFORM_VERSION, 0, 2)=="11")
			define("ZHONGFRAMEWORK_PARENT_CMS_RELEASE_CLASS", "Joomla25");
		elseif(substr(ZHONGFRAMEWORK_PARENT_CMS_PLATFORM_VERSION, 0, 2)=="12" || substr(ZHONGFRAMEWORK_PARENT_CMS_PLATFORM_VERSION, 0, 2)=="13")
			define("ZHONGFRAMEWORK_PARENT_CMS_RELEASE_CLASS", "Joomla3");
		else
			//If nothing is matched, then probably a new version of Joomla has been released, so keep the last version active
			define("ZHONGFRAMEWORK_PARENT_CMS_RELEASE_CLASS", "Joomla3");
						
		}
	//If JPlatform is not defined, then the Joomla version is 1.6.x (Platform v.10)
	catch(Exception $e){
		define("ZHONGFRAMEWORK_PARENT_CMS_PLATFORM_VERSION", "10");
		define("ZHONGFRAMEWORK_PARENT_CMS_RELEASE_CLASS", "Joomla16");
		}

	}
elseif(!defined('_JEXEC')){
	die( 'Restricted access' );
	}
?>