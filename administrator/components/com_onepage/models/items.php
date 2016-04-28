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
 * Methods supporting a list of Items records.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_onepage
 * @since		1.6
 */
class OnepageModelItems extends JModelList
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
				'id', '#__onepage_items.id',
				'state', '#__onepage_items.state',
				'onepage_id', '#__onepage_items.onepage_id', 'menu_id', '#__onepage_items.menu_id', 'menu_type', '#__onepage_items.menu_type', 'state', '#__onepage_items.state', 'ordering', '#__onepage_items.ordering'
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
				'#__onepage_items.id, #__onepage_items.state, #__onepage_items.checked_out AS checked_out, #__onepage_items.checked_out_time AS checked_out_time, 
				#__onepage_items.publish_up, #__onepage_items.publish_down, #__onepage_items.ordering
				, #__onepage_items.onepage_id, #__onepage_items.menu_id, #__onepage_items.menu_type, #__onepage_items.state, #__onepage_items.ordering, o.title AS pagetitle, 
                m.title AS menutitle, t.title AS menutype, #__onepage_items.title as title_item'
			)
		);
		$query->from('`#__onepage_items`');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=#__onepage_items.checked_out');
        $query->join('LEFT', '#__onepage AS o ON o.id=#__onepage_items.onepage_id');
		$query->join('LEFT', '#__menu AS m ON m.id=#__onepage_items.menu_id');
        $query->join('LEFT', '#__menu_types AS t ON t.id=#__onepage_items.menu_type');
		
		
		// Filter by published state
							$published = $this->getState('filter.state');
							if (is_numeric($published)) {
								$query->where('#__onepage_items.state = '.(int) $published);
							} else if ($published === '') {
								$query->where('(#__onepage_items.state IN (0, 1))');
							}
						
		
		// Filter by search
						$search = $this->getState('filter.search');
						if (!empty($search))
						{							
							$searchLike = $db->Quote('%'.$db->escape($search, true).'%');
							$search = $db->Quote($db->escape($search, true));
						$query->where('(#__onepage_items.onepage_id = '.$search.' OR #__onepage_items.menu_id = '.$search.' OR #__onepage_items.menu_type = '.$search.')');
							} //end search
		
        // Filter on the page.
        $page = $this->getState('filter.page');
        if ($page) {
            $query->where('o.id = '.$db->Quote($page));
        }        
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
	public function getTable($type = 'Items', $prefix = 'OnepageTable', $config = array())
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
                               
        $page = $this->getUserStateFromRequest($this->context.'.filter.page', 'filter_page', '', 'string');
                            $this->setState('filter.page', $page);		
		
		

		// List state information.
		parent::populateState('#__onepage_items.id', 'DESC');
	}
    
    public function getPages() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Construct the query
        $query->select('c.id AS value, c.title AS text');
        $query->from('#__onepage AS c');
//        $query->join('INNER', '#__onepage_items AS a ON a.onepage_id = c.id');
//        $query->group('c.id, c.title');
        $query->order('c.title');

        // Setup the query
        $db->setQuery($query->__toString());

        // Return the result
        return $db->loadObjectList();
    }      
}
