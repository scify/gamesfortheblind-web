<?php
/**
 * @package            JUserTube
 * @version            8.1
 * @author             Md. Afzal Hossain <afzal.csedu@gmail.com>
 * @link               http://www.srizon.com
 * @copyright          Copyright 2012 Md. Afzal Hossain All Rights Reserved
 * @license            http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
// + Video Source Options
// + Video Source Options
$userorplaylist = $params->get('userorplaylist','user');
if($userorplaylist == 'playlist') $youtubeuser = $params->get('youtubeuserpl','');
else $youtubeuser = $params->get('youtubeuser','');

$plsortorder = $params->get('plsortorder','published');
if($plsortorder == 'positionr'){
	$plsortorder = 'position';
	$needtoreverse = true;
}
else{
	$needtoreverse = false;
}
// - Video Source Options

// + General Parameters
$updatefeed = $params->get('updatefeed',300);
$totalvid = $params->get('totalvideo',18);
$totalvideop = $params->get('totalvideop',50);
$vidicon = $params->get('vidicon','yes');
// - General Parameters

// + Layout Related Parameters
$thumbsinarowl = $params->get('thumbsinarowl',3);
$thumbsinarows = $params->get('thumbsinarows',1);
$thumbpadding = $params->get('thumbpadding',7);
$roundingclass = $params->get('roundingclass','rounding7');
$shadowclass = $params->get('shadowclass','shadow10d');
$truncate_len = $params->get('truncate_len','');
$truncate_len2 = $params->get('truncate_len2','');
$showtitlethumb = $params->get('showtitlethumb','yes');
$titlethumb_height = (int) $params->get('titlethumb_height',50);
$ratioclass = $params->get('ratioclass','ratio2080');
// - Layout Related Parameters

// + Generate Unique ID
if (!isset($GLOBALS['jusermod'])) $GLOBALS['jusermod'] = 1;
else $GLOBALS['jusermod']++;
$scroller_id = 'jusertube-scroller-' . $GLOBALS['jusermod'];
// - Generate Unique ID
$paging_id = 'jut' . $GLOBALS['jusermod'];
$pageprotocol = $params->get('pageprotocol', 'http');

$show_page_heading = $params->get('show_page_heading', 1);
$page_heading = $params->get('page_heading', '');
$thumbres = $params->get('thumbres', 'medium');
$show_date = $params->get('show_date', '0');
$date_format = $params->get('date_format', 'M d, Y');

$pre_text = $params->get('pre_text', '');
$post_text = $params->get('post_text', '');

$jusertube_key = $params->get('jusertube_key', 'AIzaSyBEreAv5Ps2kTKcYDVnUTWARTh_KoLjweA');

if (!function_exists('srizon_show_pagination')) {
	function srizon_show_pagination($per_page, $total, $scroller_id, $paging_id) {
		$with_hash = false;
		if (!$total > $per_page) return;
		SrizonResourceLoader::load_srizon_custom_css();
		require_once(dirname(__FILE__) . '/srizon_pagination.php');
		$paginator = new SrizonPagination($per_page, $paging_id);
		$paginator->set_total($total);
		$u = JURI::getInstance();
		$u->delVar($paging_id);
		$url = $u->toString();
		if (strpos($url, '?')) {
			if($with_hash) echo $paginator->page_links($url . '&', '#' . $scroller_id);
			else echo $paginator->page_links($url . '&');
		} else {
			if($with_hash) echo $paginator->page_links($url . '?', '#' . $scroller_id);
			else echo $paginator->page_links($url . '?');
		}
	}
}
