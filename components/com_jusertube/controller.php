<?php
/**
 * @package            JUserTube
 * @version            8.1
 *
 * @author            Md. Afzal Hossain <afzal.csedu@gmail.com>
 * @link            http://www.srizon.com
 * @copyright        Copyright 2012 Md. Afzal Hossain All Rights Reserved
 * @license            http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
/* class name alias for joomla 2.5 support */
if (!class_exists('JControllerLegacy')) {
	/* class alias function definition for php version less than 5.3*/
	if (!function_exists('class_alias')) {
		function class_alias($original, $alias) {
			eval('class ' . $alias . ' extends ' . $original . ' {}');
		}
	}
	class_alias('JController', 'JControllerLegacy');
}

class JusertubeController extends JControllerLegacy {
	public function display($cachable = false, $urlparams = false) {
		$vName = JRequest::getVar('view', 'fullpage');
		JRequest::setVar('view', $vName);
		parent::display($cachable, $urlparams);
		return $this;
	}
}
