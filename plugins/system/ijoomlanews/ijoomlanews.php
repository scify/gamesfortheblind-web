<?php
/**
 * @copyright   (C) 2010 iJoomla, Inc. - All rights reserved.
 * @license  GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author  iJoomla.com <webmaster@ijoomla.com>
 * @url   http://www.ijoomla.com/licensing/
 * the PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript  *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0 
 * More info at http://www.ijoomla.com/licensing/
*/
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');
jimport('joomla.filesystem.file');
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

class  plgSystemiJoomlaNews extends JPlugin{

	public function plgSystemiJoomlaNews(&$subject, $config){
		parent::__construct($subject, $config);
		$this->mainframe = JFactory::getApplication();
		$this->loadPlugin();
	}

	public function loadPlugin(){
		$option = JRequest::getVar("option", "");
		$controller = JRequest::getVar("controller");
		
		$list_components = array("com_adagency"=>"0",
								 "com_digistore"=>"0",
								 "com_ijoomla_archive"=>"0",
								 "com_ijoomla_rss"=>"0",
								 "com_ijoomla_seo"=>"0",
								 "com_magazine"=>"0",
								 "com_news_portal"=>"0",
								 "com_sidebars"=>"0",
								 "com_surveys"=>"0",
								 "com_guru"=>"0",
								 "com_publisher"=>"0");
		if($this->mainframe->isAdmin() && isset($list_components[$option]) && $controller == ""){
			include_once(JPATH_SITE.DS."plugins".DS."system".DS."ijoomlanews".DS."ijoomlanews".DS."feed.php");
			include_once(JPATH_SITE.DS."plugins".DS."system".DS."ijoomlanews".DS."ijoomlanews".DS."tabs.php");
			return true;
		}
		return false;
	}
	
	function onAfterDispatch(){
		if($this->loadPlugin()){
			$document = JFactory::getDocument();
			$script = 'window.addEvent(\'domready\', function(){ $$(\'dl.tabs\').each(function(tabs){ new JTabs(tabs, {}); }); })';
            JHtml::_('behavior.framework', true);
			$document->addScript(JURI::root()."media/system/js/tabs.js");
			$document->addScriptDeclaration($script);
		}	
	}
	
	public function onAfterRender(){		
		if($this->loadPlugin()){
			$class = new CreateTabs();
			$this->renderStatus();
		}			
	}
	
	public function renderStatus(){
		$articles_list_components = array("com_adagency"=>"http://adagency.ijoomla.com/index.php?option=com_obrss&task=feed&id=3",
								 "com_digistore"=>"http://ijoomla.com/component/obrss/digistore-news/",
								 "com_ijoomla_archive"=>"http://ijoomla.com/component/obrss/archive-news/",
								 "com_ijoomla_rss"=>"http://rss.ijoomla.com/ijoomla_rss/rss/xml/ijoomla-rss-feeder/RSS2.0/",
								 "com_ijoomla_seo"=>"http://seo.ijoomla.com/blog/latest/?format=feed&type=rss",
								 "com_magazine"=>"http://ijoomla.com/component/obrss/magazine-news/",
								 "com_news_portal"=>"http://ijoomla.com/component/obrss/news-portal-news/",
								 "com_sidebars"=>"http://ijoomla.com/component/obrss/sidebars-news/",
								 "com_surveys"=>"http://ijoomla.com/component/obrss/surveys-news/",
								 "com_guru"=>"http://guru.ijoomla.com/blog-rss/",
								 "com_publisher"=>"http://publisher.ijoomla.com/easyblog/latest/?format=feed&type=rss");
								 					 
		$changelog_list_components = array("com_adagency"=>"http://feeds.feedburner.com/ChangeLog3x",
								 "com_digistore"=>"http://ecommerce.ijoomla.com/ijoomla_rss/rss/xml/ijoomla-digistore/change-log/RSS2.0/",
								 "com_ijoomla_archive"=>"http://archive.ijoomla.com/ijoomla_rss/rss/xml/ijoomla-search-a-archive/change-log/RSS2.0/",
								 "com_ijoomla_rss"=>"http://rss.ijoomla.com/ijoomla_rss/rss/xml/ijoomla-rss-feeder/change-log/RSS2.0/",
								 "com_ijoomla_seo"=>"http://seo.ijoomla.com/index.php?option=com_obrss&task=feed&id=4",
								 "com_magazine"=>"http://magazine.ijoomla.com/ijoomla_rss/rss/xml/ijoomla-magazine/change-log/RSS2.0/",
								 "com_news_portal"=>"http://newsportal.ijoomla.com/ijoomla_rss/rss/xml/ijoomla-news-portal/change-log/RSS2.0/",
								 "com_sidebars"=>"http://sidebars.ijoomla.com/index.php?option=com_ijoomla_rss&act=xml&cat=459",
								 "com_surveys"=>"http://surveys.ijoomla.com/index.php?option=com_ijoomla_rss&act=xml&cat=459",
								 "com_guru"=>"http://guru.ijoomla.com/index.php?option=com_obrss&task=feed&id=3",
								 "com_publisher"=>"http://publisher.ijoomla.com/index.php?option=com_obrss&task=feed&id=3");
								 			
		$video_list_components = array("com_adagency"=>"http://adagency.ijoomla.com/ijoomla_rss/rss/xml/ijoomla-ad-agency/videos/RSS2.0/",
								 "com_digistore"=>"http://ecommerce.ijoomla.com/ijoomla_rss/rss/xml/ijoomla-digistore/videos/RSS2.0/",
								 "com_ijoomla_archive"=>"http://archive.ijoomla.com/ijoomla_rss/rss/xml/ijoomla-search-a-archive/videos/RSS2.0/",
								 "com_ijoomla_rss"=>"http://rss.ijoomla.com/ijoomla_rss/rss/xml/ijoomla-rss-feeder/videos/RSS2.0/",
								 "com_ijoomla_seo"=>"http://seo.ijoomla.com/ijoomla_rss/rss/xml/ijoomla-seo/video-tutorials/RSS2.0/",
								 "com_magazine"=>"http://magazine.ijoomla.com/ijoomla_rss/rss/xml/ijoomla-magazine/videos/RSS2.0/",
								 "com_news_portal"=>"http://newsportal.ijoomla.com/ijoomla_rss/rss/xml/ijoomla-news-portal/videos/RSS2.0/",
								 "com_sidebars"=>"http://sidebars.ijoomla.com/ijoomla_rss/rss/xml/ijoomla-sidebars/videos/RSS2.0/",
								 "com_surveys"=>"http://surveys.ijoomla.com/ijoomla_rss/rss/xml/ijoomla-survey/videos/RSS2.0/",
								 "com_guru"=>"http://guru.ijoomla.com/ijoomla_rss/rss/xml/guru/videos/RSS2.0/",
								 "com_publisher"=>"http://guru.ijoomla.com/ijoomla_rss/rss/xml/guru/videos/RSS2.0/");			
								 
		$html	= JResponse::getBody();	
		
		
		if(strpos($html, "ijoomla_news_tabs") !== FALSE){
			$article_numbers = $this->getArticleNumbers();
			$option = JRequest::getVar("option", "");
			$url = $articles_list_components[$option];
			$feed = new NewsRss();
			//-----------------------------------
			$feed->feed_url($url);
			$feed->set_timeout(10);
			$feed->replace_headers(true);
			$feed->init();
			$articles = $feed->get_items(0, $article_numbers);
			//-----------------------------------
			$feed->feed_url("http://ijoomla.com/component/obrss/ijoomla-com/");
			$feed->set_timeout(10);
			$feed->replace_headers(true);
			$feed->init();
			$ijoomla_news = $feed->get_items(0, $article_numbers);
			//-----------------------------------
			$feed->feed_url("http://www.ijoomla.com/blog/feed/rss/");
			$feed->set_timeout(10);
			$feed->replace_headers(true);
			$feed->init();
			$ijoomla_blog = $feed->get_items(0, $article_numbers);
			//-----------------------------------
			$url = $changelog_list_components[$option];
			$feed->feed_url($url);
			$feed->set_timeout(10);
			$feed->replace_headers(true);
			$feed->init();
			$change_log = $feed->get_items(0, $article_numbers);
			//-----------------------------------
			$url = $video_list_components[$option];
			$feed->feed_url($url);
			$feed->set_timeout(10);
			$feed->replace_headers(true);
			$feed->init();
			$video = $feed->get_items(0, $article_numbers);
			//-----------------------------------
			
			$tabs_class = new CreateTabs();
			$tabs = $tabs_class->tabs($articles, $ijoomla_news, $ijoomla_blog, $change_log, $video, $option);
			$html = str_replace('<div id="ijoomla_news_tabs">', '<div id="ijoomla_news_tabs">'.$tabs, $html);
		}
		JResponse::setBody($html);
	}
	
	function getArticleNumbers(){
		$db = JFactory::getDBO();
		$sql = "select params from #__extensions where element = 'ijoomlanews'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadResult();
		if(trim($result == "")){
			$sql = "update #__extensions set params='nr_articles=3' where element = 'ijoomlanews'";
			$db->setQuery($sql);
			$db->query();
			return "3";
		}
		else{
			$result = json_decode($result);
			return $result->nr_articles;
		}
	}
}

?>
