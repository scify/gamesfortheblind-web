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

class EasyBlogControllerLicenseVerify extends EasyBlogSetupController
{
	public function execute()
	{
		$key = $this->input->get('key', '', 'default');
		$result = new stdClass();

		if (!$key) {
			$result->state = 400;

			return $this->output($result);
		}
		
		// Verify the key
		$response = $this->verify($key);

		if ($response === false) {
			$result->state = 400;
			$result->message = JText::_('COM_EASYBLOG_SETUP_UNABLE_TO_VERIFY');
			return $this->output($result);
		}

		if ($response->state == 400) {
			return $this->output($response);
		}

		// Get the total number of licenses
		if (count($response->licenses) < 1) {
			$result->state = 400;
			$result->message = JText::_('COM_EASYBLOG_SETUP_UNABLE_TO_DETECT_LICENSES');
			return $this->output($result);
		}

		ob_start();
?>
		<select name="license" data-source-license>
			<?php foreach ($response->licenses as $license) { ?>
			<option value="<?php echo $license->reference;?>"><?php echo $license->title;?> - <?php echo $license->reference; ?></option>
			<?php } ?>
		</select>
<?php
		$output = ob_get_contents();
		ob_end_clean();

		$response->html = $output;
		return $this->output($response);
	}

	public function verify($key)
	{
		$post = array('apikey' => $key, 'product' => 'easyblog');
		$resource = curl_init();

		curl_setopt($resource, CURLOPT_URL, EB_VERIFIER);
		curl_setopt($resource, CURLOPT_POST , true);
		curl_setopt($resource, CURLOPT_TIMEOUT, 120);
		curl_setopt($resource, CURLOPT_POSTFIELDS, $post);
		curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($resource);
		curl_close($resource);

		if (!$result) {
			return false;
		}

		$result = json_decode($result);

		return $result;
	}
}