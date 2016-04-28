<?php
/**
 * @version  $Id$
 * @author  JoomlaUX!
 * @package  Joomla.Site
 * @subpackage mod_jux_megamenu
 * @copyright Copyright (C) 2015 by JoomlaUX. All rights reserved.
 * @license  http://www.gnu.org/licenses/gpl.html
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('note');
require_once(JPATH_ROOT.'/libraries/joomla/form/fields/note.php');

class JFormFieldJUXUpdate extends JFormFieldNote {

	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'JUXUpdate';
	

	/** @var int The extension_id of this component */
	protected $extension_id = 0;

	/**
	 * Active sub-fields.
	 * 
	 * @var		string
	 */
	protected function getLabel()
	{

		$heading = $this->element['heading'] ? (string) $this->element['heading'] : 'h4';
		$class = !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$close = (string) $this->element['close'];
		$download_url = (string) $this->element['download_url'];
		$html = array();

		if ($close)
		{
			$close = $close == 'true' ? 'alert' : $close;
			$html[] = '<button type="button" class="close" data-dismiss="' . $close . '">&times;</button>';
		}
		$this->extension_id = $this->getExtensionID();
		$updateInfo = $this->getUpdates();
		if ($updateInfo['hasUpdate']) {
			$html[] = '<h3><i class="icon-download" style="margin-right: 5px; padding-right: 5px;"></i>An updated version of '.$updateInfo['name'].' (<b>'.$updateInfo['version'].'</b>) is available for download.

		</h3>
		<p>Before updating ensure that the update is compatible with your current version</p>
		<p>
			<a href="'.$download_url.'" target="_blank" class="btn btn-primary">
				Get this version
			</a>
			<a href="'.$updateInfo['infoURL'].'" target="_blank" class="btn btn-small btn-info">
				More information
			</a>
		</p>';		
		return '</div><div ' . $class . '>' . implode('', $html);
	} else {
		return '';
	}

}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		return '';
	}

	protected function getExtensionID() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('extension_id')
		->from('#__extensions')
		->where($db->qn('element') . ' = ' . $db->q('mod_jux_megamenu'));
		$db->setQuery($query);
		try	{
			$ids = $db->loadColumn();
		} catch (Exception $exc)	{
			return false;
		}

		if (empty($ids)) {
			return false;
		}

		return $extension_id = array_shift($ids);
	}


	/**
	 * Gets the update site Ids for our extension.
	 *
	 * @return 	mixed	An array of Ids or null if the query failed.
	 */
	public function getUpdateSiteIds()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
		->select($db->qn('update_site_id'))
		->from($db->qn('#__update_sites_extensions'))
		->where($db->qn('extension_id') . ' = ' . $db->q($this->extension_id));
		$db->setQuery($query);
		$updateSiteIds = $db->loadColumn(0);

		return $updateSiteIds;
	}

	public function getUpdates($force = false)
	{
		$db = JFactory::getDBO();

		// Default response (no update)
		$updateResponse = array(
			'hasUpdate' => false,
			'version'   => '',
			'infoURL'   => '',
			'name'      => ''
			);

		if (empty($this->extension_id))
		{
			return $updateResponse;
		}

		// If we are forcing the reload, set the last_check_timestamp to 0
		// and remove cached component update info in order to force a reload
		if ($force)
		{
			// Find the update site IDs
			$updateSiteIds = $this->getUpdateSiteIds();

			if (empty($updateSiteIds))
			{
				return $updateResponse;
			}

			// Set the last_check_timestamp to 0
			$query = $db->getQuery(true)
			->update($db->qn('#__update_sites'))
			->set($db->qn('last_check_timestamp') . ' = ' . $db->q('0'))
			->where($db->qn('update_site_id') .' IN ('.implode(', ', $updateSiteIds).')');
			$db->setQuery($query);
			$db->execute();

			// Remove cached component update info from #__updates
			$query = $db->getQuery(true)
			->delete($db->qn('#__updates'))
			->where($db->qn('update_site_id') .' IN ('.implode(', ', $updateSiteIds).')');
			$db->setQuery($query);
			$db->execute();
		}

		// Use the update cache timeout specified in com_installer
		$comInstallerParams = JComponentHelper::getParams('com_installer', false);
		$timeout = 3600 * $comInstallerParams->get('cachetimeout', '6');
		// Load any updates from the network into the #__updates table

		// Get the update record from the database
		$query = $db->getQuery(true)
		->select('*')
		->from($db->qn('#__updates'))
		->where($db->qn('extension_id') . ' = ' . $db->q($this->extension_id));
		$db->setQuery($query);
		$updateRecord = $db->loadObject();

		// If we have an update record in the database return the information found there
		if (is_object($updateRecord))
		{
			$updateResponse = array(
				'hasUpdate' => true,
				'version'   => $updateRecord->version,
				'infoURL'   => $updateRecord->infourl,
				'name'		=> $updateRecord->name
				);
		}
		return $updateResponse;
	}
}