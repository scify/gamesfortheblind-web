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

jimport('joomla.application.component.controller');

/**
 * Onepage master display controller.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_onepage
 * @since		1.6
 */
class OnepageController extends JControllerLegacy
{
	public $default_view = 'pages';
	
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/onepage.php';

		// Load the submenu.
		OnepageHelper::addSubmenu(JRequest::getCmd('view', 'pages'));

		parent::display();

		return $this;
	}
    public function getMenu()
    {
        $jform_menu_type=JRequest::getVar('jform_menu_type');
        $model=$this->getModel('item'); 
        $menu=$model->getMenuitems($jform_menu_type);
        if ($menu)
        {
            $result['status']=1;
            $select = '<select aria-required="true" required="" name="jform[menu_id]" id="jform_menu_id" class="" aria-invalid="false" onclick="loadContentmenu();">
                    <option value="">'.JText::_('COM_ONEPAGE_SELECT').'</option>';
           foreach ($menu as $key => $item) {
               $select .= '<option value="'.$item->id.'">'.$item->title.'</option>';
           } 
           $select .= '</select>';
           $result['select']=$select;               
        }
        else
        {
            $result['status']=0;    
        }
        echo json_encode($result);
    } 
    public function getMenuitem()
    {
        $jform_menu_id=JRequest::getVar('jform_menu_id');
        $model=$this->getModel('item'); 
        $menu=$model->getMenucontent($jform_menu_id);
        if ($menu)
        {
            $mystring = $menu->link;
            $findme   = 'http';
            $pos = strpos($mystring, $findme);

            if ($pos === false) {
                $url = JURI::root().$menu->link;
            } else {
                $url = $menu->link;
            }                
            $result['status']=1;
            $content = '<iframe id="myIframe" onload="inspectItem();" src="'.$url.'" width="100%" height="400" scrolling="yes"></iframe>';
           $result['value']=$content;               
           $result['link']=$menu->link;               
        }
        else
        {
            $result['status']=0;    
        }
        echo json_encode($result);
    }          
}
