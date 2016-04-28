<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

// Include parent library
require_once( dirname( __FILE__ ) . '/controller.php' );

class EasyBlogControllerMaintenanceExecute extends EasyBlogSetupController
{
	public function execute()
	{
		$this->engine();

		$script = $this->input->get('script', '', 'default');
	
		// Run the maintenance script now
		$maintenance = EB::maintenance();
		$state = $maintenance->runScript($script);

		if (!$state) {
			$message = $maintenance->getError();
			$result = $this->getResultObj($message, false);

			return $this->output($result);
		}

		$title = $maintenance->getScriptTitle($script);
		$message = JText::sprintf('COM_EASYBLOG_INSTALLATION_MAINTENANCE_EXECUTED_SCRIPT', $title);

		$result = $this->getResultObj($message, true);

		return $this->output($result);
	}
}
