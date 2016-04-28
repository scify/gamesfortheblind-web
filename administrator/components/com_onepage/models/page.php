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
 * Page model.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_onepage
 * @since		1.6
 */
class OnepageModelPage extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_ONEPAGE_PAGE';

	
	
	

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Page', $prefix = 'OnepageTable', $config = array())
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
		$form = $this->loadForm('com_onepage.page', 'page', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		// Determine correct permissions to check.
		if ($this->getState('page.id')) {
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
		$data = JFactory::getApplication()->getUserState('com_onepage.edit.page.data', array());

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

    public function savePage($data,$id,$name,$desc){
        $this->AddDesign($id,$data);
        foreach ($data as $item) {
            $this->getCode(json_decode($item));
        }

        $kt=false;
        if($id==0){

        }else{
            $kt =$this->editPage($id,$name,$desc);
        }
        if($kt){
            return JText::_("COM_ONEPAGE_SUCCESS_SAVE");
        }else{
            echo "Error!";
        }
    }  
    private function editPage($id,$name,$desc){
        $db     = $this->getDbo();
        $query  = $db->getQuery(true);
        $field  = array('code="'.$this->code.'"','title="'.$name.'"','description="'.$desc.'"');
        $where  = array('id='.$id);
        $query->update($db->quoteName('#__onepage'))->set($field)->where($where);

        
        $db->setQuery($query);

        $db->setQuery($query);
        try {
            if(JVERSION==3){
                $result=$db->execute();
            }else{
                $result=$db->query();
            }
        } catch (Exception $e) {
            $result = false;
        }
        return $result;
    }      
    
    public function getItemcode(){
        $db     = $this->getDbo();
        $query  = $db->getQuery(true);
        $query->select('*')->from('#__onepage_type');
        $db->setQuery($query);
        return $db->loadObjectList();
    } 
    public function getListModule(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("*")->from("#__modules")->where('published=1 and access=1 and client_id=0');
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    public function getListPageitem($pageid){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("*")->from("#__onepage_items")->where('state=1 AND onepage_id = '.$pageid);
        $db->setQuery($query);
        return $db->loadObjectList();
    }    
    public function getDesign($id){
        $db     = $this->getDbo();
        $query  = $db->getQuery(true);
        $query         ->select('a.*,b.class,b.name')
                    ->from('#__onepage_design AS a')
                    ->join('INNER', '#__onepage_type AS b ON (a.type = b.type)')
                    ->where('pageid='.$id)
                    ->order('a.id ASC');
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    public function getPageName($id){
        $db     = $this->getDbo();
        $query  = $db->getQuery(true);
        $query->select('title')->from('#__onepage')->where('id='.$id);
        $db->setQuery($query);
        return $db->loadResult();
    } 
    public function getListitem($pageid) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("id, title")->from("#__onepage_items")->where('state=1 AND onepage_id = '.$pageid);
        $db->setQuery($query);
        return $db->loadObjectList();        
    }  
    private function AddDesign($id,$data){
        $db     = $this->getDbo();
        $query  = $db->getQuery(true);
        $query->delete($db->quoteName('#__onepage_design'))->where('pageid='.$id);
        $db->setQuery($query);
        try {
            if(JVersion==3){
                $result=$db->execute();
            }else{
                $result=$db->query();
            }
        } catch (Exception $e) {
           // catch the error.
        }
        
        $columns = array('pageid','json','type');
        foreach($data as $item){
            $query  = $db->getQuery(true);
            $values = array($id, $db->quote($item),$db->quote(json_decode($item)->type));
            $query     ->insert($db->quoteName('#__onepage_design'))
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $values));
            $db->setQuery($query);
            try {
                if(JVersion==3){
                    $result=$db->execute();
                }else{
                    $result=$db->query();
                }
            } catch (Exception $e) {
               // catch the error.
            }
        }
    }

    private function getCode($data){
        $this->code.='['.$data->type.' '.$this->getCodeAttr($data->attr).'] ';
        if(is_array($data->content)){
            foreach($data->content as $item){
                $this->getCode($item);
            }
        }else
            $this->code.=$data->content;
        $this->code.='[/'.$data->type.'] ';
    }
    private function getCodeAttr($data){
        $arr = get_object_vars($data);
        $attr="";
        if(count($arr)>0){
            foreach ($arr as $key => $value) {
                $attr.="{$key}='{$value}' ";
            }
        }
        return $attr;
    }
    // Duplicate 
    public function duplicate($id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__onepage')->where('id='.$id);
        $db->setQuery($query);
        $page = $db->loadObject();

        $query = $db->getQuery(true);
        $columns = array('name', 'code');
        $values = array($db->quote($page->name.' - (copy)'), $db->quote($page->code));
        $query         ->insert($db->quoteName('#__onepage'))
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $values));
           $db->setQuery($query);
        $db->query();
        $newid = $db->insertid();

        $query = $db->getQuery(true);
        $query->select('id')->from('#__onepage_design')->where('pageid='.$id)->order('pageid ASC');
        $db->setQuery($query);
        $desiId = $db->loadObjectList();
        foreach ($desiId as $key => $desi) {
            $this->duplicateDesign($desi->id,$newid);
        }
        
    }

    private function duplicateDesign($id,$newid){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*')->from('#__onepage_design')->where('id='.$id);
        $db->setQuery($query);
        $desi = $db->loadObject();

        $query = $db->getQuery(true);
        $columns = array('pageid', 'json', 'type');
        $values = array($newid, $db->quote($desi->json), $db->quote($desi->type));
        $query         ->insert($db->quoteName('#__onepage_design'))
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $values));
           $db->setQuery($query);
        $db->query();
    }                
}
