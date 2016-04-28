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
$userorplaylist = $params->get('userorplaylist', 'user');
if ($userorplaylist == 'playlist') $youtubeuser = $params->get('youtubeuserpl', '');
else $youtubeuser = $params->get('youtubeuser', '');
$plsortorder = $params->get('plsortorder', 'published');
if ($plsortorder == 'positionr') {
	$plsortorder = 'position';
	$need_reversal = true;
} else {
	$need_reversal = false;
}
// - Video Source Options
// + General Parameters
$updatefeed = $params->get('updatefeed', 600);
$totalvid = $params->get('totalvideo', 18);
$totalvideop = $params->get('totalvideop', 50);
$totalvideopagi = $params->get('totalvideopagi', 100);
$liststyle = $params->get('liststyle', 'respslider');
// - General Parameters
// + Thumbgrid Layout Options
$thumbsinarowl = $params->get('thumbsinarowl', 3);
$thumbsinarows = $params->get('thumbsinarows', 1);
$thumbpadding = $params->get('thumbpadding', 3);
$showtitlethumb = $params->get('showtitlethumb', 'yes');
$titlethumb_height = (int)$params->get('titlethumb_height', 50);
// - Thumbgrid Layout Options
// + Thumb with Description Layout Options
$ratioclass = $params->get('ratioclass', 'ratio2080');
$truncate_len2 = $params->get('truncate_len2', '100');
// - Thumb with Description Layout Options
// + Thumbgrid and Thumb With Description Common Options
$truncate_len = $params->get('truncate_len', '');
$roundingclass = $params->get('roundingclass', 'rounding7');
$shadowclass = $params->get('shadowclass', 'shadow10d');
$vidicon = $params->get('vidicon', 'yes');
// - Thumbgrid and Thumb With Description Common Options
// + Responsive slider related options
$respslidespeed = $params->get('respslidespeed', '500');
$respslideminitem = $params->get('respslideminitem', '2');
$respslidestart = $params->get('respslidestart', '0');
$targetheight = $params->get('targetheight', '200');
// - Responsive slider related options
// + Generate Unique ID
if (!isset($GLOBALS['jusermod'])) $GLOBALS['jusermod'] = 1;
else $GLOBALS['jusermod']++;
$scroller_id = 'jusertube-scroller-' . $GLOBALS['jusermod'];
$paging_id = 'jus' . $GLOBALS['jusermod'];
// - Generate Unique ID
$thumbres = $params->get('thumbres', 'medium');
$date_format = $params->get('date_format', 'M d, Y');
$show_date = $params->get('show_date', '0');
$pre_text = $params->get('pre_text', '');
$post_text = $params->get('post_text', '');

$scroll_interval = $params->get('scroll_interval', '0');

$c_params = JComponentHelper::getParams('com_jusertube');
$jusertube_key = $c_params->get('jusertube_key', 'AIzaSyBEreAv5Ps2kTKcYDVnUTWARTh_KoLjweA');

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