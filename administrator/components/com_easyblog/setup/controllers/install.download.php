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

require_once(__DIR__ . '/controller.php');

class EasyBlogControllerInstallDownload extends EasyBlogSetupController
{
	/**
	 * Downloads the file from the server
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function execute()
	{
		// Check the api key from the request
		$apiKey = $this->input->get('apikey', '', 'default');
		$license = $this->input->get('license', '', 'default');

		// If the user is updating, we always need to get the latest version.
		$update = $this->input->get('update', false, 'bool');

		// Get information about the current release.
		$info = $this->getInfo($update);

		if (!$info) {
			$result = new stdClass();
			$result->state = false;
			$result->message = JText::_('COM_EASYBLOG_INSTALLATION_ERROR_REQUEST_INFO');

			$this->output($result);
			exit;
		}

		if (isset($info->error) && $info->error == 408) {
			$result = new stdClass();
			$result->state = false;
			$result->message = $info->message;

			$this->output($result);
			exit;
		}

		// Download the component installer.
		$storage = $this->getDownloadFile($info, $apiKey, $license);

		if ($storage === false) {
			$result = new stdClass();
			$result->state = false;
			$result->message = JText::_('COM_EASYBLOG_INSTALLATION_ERROR_DOWNLOADING_INSTALLER');

			$this->output($result);
			exit;
		}

		// Get the md5 hash of the stored file
		$hash = md5_file($storage);

		// Check if the md5 check sum matches the one provided from the server.
		if (!in_array($hash, $info->md5)) {
			$result = new stdClass();
			$result->state = false;
			$result->message = JText::_('COM_EASYBLOG_INSTALLATION_ERROR_MD5_CHECKSUM');
			$this->output($result);
			exit;
		}

		// Check if the temporary folder exists
		if (!JFolder::exists(EB_TMP)) {
			JFolder::create(EB_TMP);
		}

		// Extract files here.
		$tmp = EB_TMP . '/com_easyblog_v' . $info->version;

		// If folder exists previously, remove it first
		if (JFolder::exists($tmp)) {
			JFolder::delete($tmp);
		}

		// Try to extract the files
		$state = JArchive::extract($storage, $tmp);

		if (!$state) {
			$result = new stdClass();
			$result->state = false;
			$result->message = JText::_('COM_EASYBLOG_INSTALLATION_ERROR_EXTRACT_ERRORS');

			$this->output($result);
			exit;
		}

		// delete the donwloaded file after successfully extracted.
		@JFile::delete($storage);

		$result = new stdClass();

		$result->message = JText::_('COM_EASYBLOG_INSTALLATION_ARCHIVE_DOWNLOADED_SUCCESS');
		$result->state = $state;
		$result->path = $tmp;

		header('Content-type: text/x-json; UTF-8');
		echo json_encode($result);
		exit;
	}

	/**
	 * Executes the file download from the server.
	 *
	 * @since	5.0
	 * @access	public
	 * @param	object 	The manifest data from server.
	 * @param	string	The user's api key.
	 * @param	string	The license key to use for this installation
	 * @return	mixed	false if download failed or path to the file if success.
	 */
	public function getDownloadFile($info, $apikey, $license)
	{
		// Request the server to download the file.
		$url = $info->install;

		// Get the latest version
		$ch = curl_init($info->install);

		// // Debug
		// dump($info->install, 'extension=easyblog&apikey=' . $apikey . '&license=' . $license . '&version=' . $info->version);

		// We need to pass the api keys to the server
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'extension=easyblog&apikey=' . $apikey . '&license=' . $license . '&version=' . $info->version);

		// We don't want the output immediately.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Set a large timeout incase the server fails to download in time.
		curl_setopt($ch, CURLOPT_TIMEOUT, 30000);

		// Get the response of the server
		$result = curl_exec($ch);

		// Close the connection
		curl_close($ch);

		// Set the storage page
		$storage = EB_PACKAGES . '/easyblog_v' . $info->version . '_component.zip';

		// Delete zip archive if it already exists.
		if (JFile::exists($storage)) {
			JFile::delete($storage);
		}

		// Debug md5
		// $result 	= $result . 'somedebugcontents';

		$state = JFile::write($storage, $result);

		if (!$state) {
			return false;
		}

		return $storage;
	}

}
