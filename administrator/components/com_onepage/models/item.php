<?php
/*------------------------------------------------------------------------
# com_onepage - XIPAT Onepage component
# version: 1.0
# ------------------------------------------------------------------------
# author    Nguyen Huy Kien - www.xipat.com
# copyright Copyright (C) 2013 www.xipat.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.xipat.com
# Technical Support:  http://www.xipat.com/support.html
-------------------------------------------------------------------------*/

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Item model.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_onepage
 * @since		1.6
 */
class OnepageModelItem extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_ONEPAGE_ITEM';

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Item', $prefix = 'OnepageTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	} 

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_onepage.item', 'item', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		// Determine correct permissions to check.
		if ($this->getState('item.id')) {
			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('#FIELD_CATEGORY_ID#', 'action', 'core.edit');
		} else {
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('#FIELD_CATEGORY_ID#', 'action', 'core.create');
		}

		// Modify the form based on access controls.
		if (!$this->canEditState((object) $data)) {
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is a record you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_onepage.edit.item.data', array());

		if (empty($data))
			$data = $this->getItem();

		return $data;
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param	object	A record object.
	 * @return	array	An array of conditions to add to add to ordering queries.
	 * @since	1.6
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		
		$condition[] = 'state >= 0';
		return $condition;
	}  
    
    public function getMenuitems($menuid) {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        // Construct the query     
        $query->select('a.id, a.menutype, a.title, a.alias, a.link');
        $query->from('#__menu_types as m'); 
        $query->where('m.id = '.$menuid.' AND a.published = 1 AND a.home = 0 AND a.link NOT LIKE "index.php?option=com_onepage&view=pages"');
        $query->join('LEFT', $db->quoteName('#__menu') . ' AS a ON a.menutype = m.menutype');
        // Setup the query
        $db->setQuery($query->__toString());

        // Return the result
        return $db->loadObjectList();        
    }   
    public function getMenucontent($menuid) {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        // Construct the query     
        $query->select('*');
        $query->from('#__menu'); 
        $query->where('id = '.$menuid.' AND published = 1 AND home = 0');
        // Setup the query
        $db->setQuery($query->__toString());

        // Return the result
        return $db->loadObject();        
    }      
}
