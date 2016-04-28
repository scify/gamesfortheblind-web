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

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
jimport('joomla.application.component.helper');

/**
 * Pages model for the onepage component.
 *
 * @package		Joomla.Site
 * @subpackage	com_onepage
 * @since		1.6
 */
class OnepageModelPages extends JModelList
{
    
    public function __construct($config = array()) {
        parent::__construct($config);
    }     
    
    function getPages($page_id)
    {
        $db            = $this->getDbo();
        $query        = $db->getQuery(true);
        
        $query->select(
            '*'
        );
        
        $query->from('`#__onepage_items`');
        $query->where('onepage_id = '.$page_id.' AND state = 1');
        $query->order('ordering');
        
        $db->setQuery($query);
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
        }
        
        return $db->loadObjectList();
    }
    
    function getPagesitem($page_id)
    {
        $db    = $this->getDbo(); 
        $query = $db->getQuery(true);
        $query->select('*')->from('#__onepage')->where('id='.$page_id);
        $db->setQuery($query);
        $data = $db->loadObject();  
        
        return $data;
    } 
       
    protected function populateState($ordering = NULL, $direction = NULL) {
        // Get the application object.
        $app = JFactory::getApplication();
        $params = $app->getParams('com_onepage');

        // Load the parameters.
        $this->setState('params', $params);
    } 
}