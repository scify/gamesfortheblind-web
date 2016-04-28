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

class EasyBlogControllerMaintenanceGetScripts extends EasyBlogSetupController
{
	public function __construct()
	{
		// Include foundry's library, since we know that foundry is already available here.
		$this->engine();
	}
	
	public function execute()
	{
		$maintenance = EB::maintenance();

		// Get previous version installed
		$previous = $this->getPreviousVersion('scriptversion');

		$files = $maintenance->getScriptFiles($previous);

		// Don't execute if no previous version is found
		// No previous version means this is a fresh installation, and this is not needed on fresh instlalation
		
		$msg = JText::sprintf('COM_EASYBLOG_INSTALLATION_MAINTENANCE_NO_SCRIPTS_TO_EXECUTE');
		
		if ($files) {
			$msg = JText::sprintf('COM_EASYBLOG_INSTALLATION_MAINTENANCE_TOTAL_FILES_TO_EXECUTE', count($files));
		}

		$result = array('message' => $msg, 'scripts' => $files);

		return $this->output($result);
	}
}
