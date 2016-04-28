<?php
/**
 * @package        Srizon
 * @version        1.0.0
 *
 * @author        Md. Afzal Hossain <afzal.csedu@gmail.com>
 * @link        http://www.srizon.com
 * @copyright    Copyright 2012 Md. Afzal Hossain All Rights Reserved
 * @license        GNU General Public License version 2 or later
 */
// no direct access
defined('_JEXEC') or die;
// import library dependencies
jimport('joomla.plugin.plugin');

class plgSystemSrizonmodifier extends JPlugin {
	function onAfterRender() {
		$app = JFactory::getApplication();
		$runonadmin = $this->params->get('runonadmin', '0');
		$removelines = $this->params->get('removelines', '');
		$addaftertitle = $this->params->get('addaftertitle', '');
		$addbeforeheadclose = $this->params->get('addbeforeheadclose', '');
		$addbeforebodyclose = $this->params->get('addbeforebodyclose', '');
		if ($app->isAdmin() and $runonadmin == 0) return true;
		if (JRequest::getVar('format') == 'feed') return true;
		if (JRequest::getVar('format') == 'raw') return true;
		$body = JResponse::getBody();
		/* Remove unwanted lines */
		$removelinesarr = explode("\n", $removelines);
		foreach ($removelinesarr as $line) {
			if (trim($line)) {
				$body = str_replace(trim($line), '', $body);
			}
		}
		/* add new lines inside head tag after title*/
		if (trim($addaftertitle)) {
			$pos = strpos($body, '</title>');
			$body = substr_replace($body, "\n  " . $addaftertitle, $pos + 8, 0);
		}
		/* add new lines inside head tag - before closing head tag*/
		if (trim($addbeforeheadclose)) {
			$headplusres = "  ".$addbeforeheadclose . "\n". '</head>';
			$body = str_replace('</head>', $headplusres, $body);
		}
		/* add new lines inside body tag - before closing body tag*/
		if (trim($addbeforebodyclose)) {
			$bodyplusres = "  ".$addbeforebodyclose . "\n". '</body>';
			$body = str_replace('</body>', $bodyplusres, $body);
		}
		JResponse::setBody($body);
		return true;
	}
}