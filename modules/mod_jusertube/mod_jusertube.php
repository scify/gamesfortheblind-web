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
require_once(dirname(__FILE__) . '/lib/srizon_yt_album.php');
require_once(dirname(__FILE__) . '/lib/srizon_resource_loader.php');
include(dirname(__FILE__) . '/lib/read_parameters.php');
// + Get The videos
$srizon_yt = new SrizonYoutubeFeedReader($updatefeed, $jusertube_key);
$videos = $srizon_yt->get_youtube_top($youtubeuser, $totalvid);
if ($need_reversal) $videos = array_reverse($videos);
$videos = array_slice($videos,0,$totalvid);

$cur_page = (JURI::getInstance()->getVar($paging_id, 1)) - 1;
$totalvidall = count($videos);
$videos = array_slice($videos, $cur_page * $totalvideopagi, $totalvideopagi);
require JModuleHelper::getLayoutPath('mod_jusertube', $params->get('layout', 'default'));
