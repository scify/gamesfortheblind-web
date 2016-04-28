<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class EasyBlogAutoPosting extends EasyBlog
{
	/**
	 * Auto post to social network sites
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function shareUser(EasyBlogPost &$post, $sites = array(), $force = false)
	{
		// Ensure that the sites are not empty
		if (!$sites) {
			return false;
		}

		// Determines if the primary category of this post requires auto posting or not
		$category = $post->getPrimaryCategory();

		if (!$category->autopost) {
			return;
		}

		// Process user auto posting first
		foreach ($sites as $type) {
			// Check whether the settings really permits the user to auto post to their own
			if (!$this->config->get('integrations_' . strtolower($type) . '_centralized_and_own')) {
				return false;
			}

			// Load the oauth client
			$table = EB::table('OAuth');
			$exists = $table->load(array('user_id' => $post->created_by, 'type' => $type, 'system' => '0'));

			// Ensure that the client exists
			if (!$exists) {
				continue;
			}

			// Push the message to the respective sites now
			$table->push($post);
		}

		return;
	}

	/**
	 * Auto post to social network sites
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function shareSystem(EasyBlogPost &$post, $type = null, $force = false)
	{
		// Determines if the primary category of this post requires auto posting or not
		$category = $post->getPrimaryCategory();

		if (!$category->autopost) {
			return;
		}

		// Get a list of system oauth clients.
		if (is_null($type)) {
			$model = EB::model('Oauth');
			$clients = $model->getSystemClients();

			foreach ($clients as $client) {

				// If this is a new post, ensure that it respects the settings before auto posting
				if ($post->isNew() && !$this->config->get('integrations_' . $client->type . '_centralized_auto_post') && !$force) {
					continue;
				}

				// If the post is updated, ensure that it respects the settings before auto posting
				if (!$post->isNew() && !$this->config->get('integrations_' . $client->type . '_centralized_send_updates') && !$force) {
					continue;
				}

				$table = EB::table('OAuth');
				$table->bind($client);

				// Push the item now
				$table->push($post);
			}

			return true;
		}

		// If this is a new post, ensure that it respects the settings before auto posting
		if ($post->isNew() && !$this->config->get('integrations_' . $type . '_centralized_auto_post') && !$force) {
			continue;
		}

		// If the post is updated, ensure that it respects the settings before auto posting
		if (!$post->isNew() && !$this->config->get('integrations_' . $type . '_centralized_send_updates') && !$force) {
			continue;
		}

		$table = EB::table('OAuth');
		$table->load(array('type' => $type, 'system' => 1));

		// Push the item now
		$table->push($post);
	}
}
