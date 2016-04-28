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

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Pages records.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_onepage
 * @since		1.6
 */
class OnepageModelPages extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', '#__onepage.id',
				'state', '#__onepage.state',
				'title', '#__onepage.title', 'description', '#__onepage.description'
			);
		}

		parent::__construct($config);
	}
	
	
	
	#FUNCTION_GET_AUTHOR#

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Initialise variables.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'#__onepage.id, #__onepage.state, #__onepage.checked_out AS checked_out, #__onepage.checked_out_time AS checked_out_time, 
				#__onepage.publish_up, #__onepage.publish_down, #__onepage.ordering
				, #__onepage.title, #__onepage.description'
			)
		);
		$query->from('`#__onepage`');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=#__onepage.checked_out');
		
		
		
		// Filter by published state
							$published = $this->getState('filter.state');
							if (is_numeric($published)) {
								$query->where('#__onepage.state = '.(int) $published);
							} else if ($published === '') {
								$query->where('(#__onepage.state IN (0, 1))');
							}
						
		
		// Filter by search
						$search = $this->getState('filter.search');
						if (!empty($search))
						{							
							$searchLike = $db->Quote('%'.$db->escape($search, true).'%');
							$search = $db->Quote($db->escape($search, true));
						$query->where('(#__onepage.id = '.$search.' OR #__onepage.title = '.$search.' OR #__onepage.description = '.$search.')');
							} //end search
		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		
		$query->order($db->escape($orderCol.' '.$orderDirn));

		//echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Pages', $prefix = 'OnepageTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
							$this->setState('filter.search', $search);
		
		$state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
							$this->setState('filter.state', $state);
		

		// List state information.
		parent::populateState('#__onepage.id', 'DESC');
	}
    
    public function countItems($pageid){
        $db        = $this->getDbo();
        $query    = $db->getQuery(true);
        $query->select('count(id) as totalitem');
        $query->from('`#__onepage_items`');    
        $query->where('onepage_id = '.(int) $pageid);   
        $db->setQuery($query->__toString());
        // Return the result
        return $db->loadObject();   
    }
}