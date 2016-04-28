<?php
/**
* @copyright   (C) 2010 iJoomla, Inc. - All rights reserved.
* @license  GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html) 
* @author  iJoomla.com webmaster@ijoomla.com
* @url   http://www.ijoomla.com/licensing/
* the PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript  
* are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0 
* More info at http://www.ijoomla.com/licensing/
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
jimport( 'joomla.utilities.date' );

class iJoomla_SeoModelPreview extends JModelLegacy{
	
	function getArticlePreview(){
		$id = JRequest::getVar("id", "0");
		
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();		
		$query->select('c.introtext, c.fulltext');
		$query->from('#__content c');
		$query->where("c.id=".$id);
		$db->setQuery($query);		
		$db->query();
		$result = $db->loadAssocList();
		$return = "";		
		if(isset($result["0"]["introtext"]) && trim($result["0"]["introtext"]) != ""){
			$return .= $result["0"]["introtext"];
		}
		if(isset($result["0"]["fulltext"]) && trim($result["0"]["fulltext"]) != ""){
			$return .= $result["0"]["fulltext"];
		}
		echo $return;
	}
}

?>

