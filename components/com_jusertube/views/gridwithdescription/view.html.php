<?php
/**
 * @package			JUserTube 
 * @version			8.1
 * @author			Md. Afzal Hossain <afzal.csedu@gmail.com>
 * @link			http://www.srizon.com
 * @copyright       Copyright 2012 Md. Afzal Hossain All Rights Reserved
 * @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
/* class name alias for joomla 2.5 support */
if (!class_exists('JViewLegacy')) {
	/* class alias function definition for php version less than 5.3*/
	if (!function_exists('class_alias')) {
		function class_alias($original, $alias) {
			eval('class ' . $alias . ' extends ' . $original . ' {}');
		}
	}
	class_alias('JView', 'JViewLegacy');
}

class JusertubeViewgridwithdescription extends JViewLegacy
{

	function display($tpl = null)
	{
		$app		= JFactory::getApplication();
		$params		= $app->getParams();
		require_once(dirname(__FILE__) . '/../../lib/srizon_resource_loader.php');
		require_once(dirname(__FILE__) . '/../../lib/srizon_yt_album.php');
		require_once(dirname(__FILE__) . '/../../lib/srizon_pagination.php');
		include(dirname(__FILE__) . '/../../lib/read_parameters.php');


		$vid = new SrizonYoutubeFeedReader($updatefeed, $jusertube_key);
		$videos = $vid->get_youtube_top($youtubeuser,$totalvideop);
		$videos = array_slice($videos,0,$totalvideop);
		if($needtoreverse) $videos = array_reverse($videos);

		$cur_page = (JURI::getInstance()->getVar($paging_id, 1)) - 1;

		$totalvidall = count($videos);
		$this->videos = array_slice($videos, $cur_page * $totalvid, $totalvid);

		$tpl_params = array(
			'cur_page',
			'paging_id',
			'vidicon',
			'totalvid',
			'truncate_len2',
			'totalvidall',
			'thumbpadding',
			'roundingclass',
			'shadowclass',
			'thumbsinarowl',
			'thumbsinarows',
			'showtitlethumb',
			'truncate_len',
			'titlethumb_height',
			'show_page_heading',
			'page_heading',
			'show_date',
			'date_format',
			'pre_text',
			'post_text',
			'thumbres',
			'scroller_id');
		foreach ($tpl_params as $param) {
			$this->$param = $$param;
		}
		parent::display($tpl);
	}

}
