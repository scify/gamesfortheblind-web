<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

// Add as many spiders you want in this array
$spiders = array('Googlebot', 'Baidu', 'Yammybot', 'Openbot', 'Yahoo', 'Slurp', 'msnbot', 'ia_archiver', 'Lycos', 'Scooter', 'AltaVista', 'Teoma', 'Gigabot');

$ZHONGFRAMEWORK_IS_CLIENT_CRAWLER=false;
// Loop through each spider and check if it appears in the User Agent
foreach ($spiders as $spider){
	if( preg_match('/'.$spider.'/i', $_SERVER['HTTP_USER_AGENT']) || $_SERVER['HTTP_USER_AGENT']==""){
		$ZHONGFRAMEWORK_IS_CLIENT_CRAWLER=true;
		break;
		}
	}

define("ZHONGFRAMEWORK_IS_CLIENT_CRAWLER",$ZHONGFRAMEWORK_IS_CLIENT_CRAWLER);
?>