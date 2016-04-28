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

class iJoomla_SeoModelConfig extends JModelLegacy{
	
	function getParams(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();		
		$query->select('params');
		$query->from('#__ijseo_config');
		$db->setQuery($query);		
		$db->query();
		$result = $db->loadColumn();
		$result = $result["0"];
		return $result;
	}
	
	function save(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();		
		$query->select('params');
		$query->from('#__ijseo_config');
		$db->setQuery($query);		
		$db->query();
		$result = $db->loadColumn();
		$result = $result["0"];
		$result_array = json_decode($result);
		
		$result_array->ijseo_Replace1 = trim(JRequest::getVar("Replace1", "null") != "null") ?  JRequest::getVar("Replace1", "") : $result_array->ijseo_Replace1;
		$result_array->ijseo_Replace2 = trim(JRequest::getVar("Replace2", "null") != "null") ? JRequest::getVar("Replace2", "") : $result_array->ijseo_Replace2;
		$result_array->ijseo_Replace3 = trim(JRequest::getVar("Replace3", "null") != "null") ? JRequest::getVar("Replace3", "") : $result_array->ijseo_Replace3;
		$result_array->ijseo_Replace4 = trim(JRequest::getVar("Replace4", "null") != "null") ? JRequest::getVar("Replace4", "") : $result_array->ijseo_Replace4;
		$result_array->ijseo_Replace5 = trim(JRequest::getVar("Replace5", "null") != "null") ? JRequest::getVar("Replace5", "") : $result_array->ijseo_Replace5;
		$result_array->ijseo_Replace1_with = trim(JRequest::getVar("Replace1_with", "null") != "null") ? JRequest::getVar("Replace1_with") : $result_array->ijseo_Replace1_with;
		$result_array->ijseo_Replace2_with = trim(JRequest::getVar("Replace2_with", "null") != "null") ? JRequest::getVar("Replace2_with") : $result_array->ijseo_Replace2_with;
		$result_array->ijseo_Replace3_with = trim(JRequest::getVar("Replace3_with", "null") != "null") ? JRequest::getVar("Replace3_with") : $result_array->ijseo_Replace3_with;
		$result_array->ijseo_Replace4_with = trim(JRequest::getVar("Replace4_with", "null") != "null") ? JRequest::getVar("Replace4_with") : $result_array->ijseo_Replace4_with;
		$result_array->ijseo_Replace5_with = trim(JRequest::getVar("Replace5_with", "null") != "null") ? JRequest::getVar("Replace5_with") : $result_array->ijseo_Replace5_with;
		$result_array->ijseo_wrap_key = trim(JRequest::getVar("wrap_key", "null") != "null") ? JRequest::getVar("wrap_key") : $result_array->ijseo_wrap_key;
		$result_array->ijseo_wrap_partial = trim(JRequest::getVar("wrap_partial", "null") != "null") ? JRequest::getVar("wrap_partial") : $result_array->ijseo_wrap_partial;
		$result_array->ijseo_allow_no = trim(JRequest::getVar("allow_no", "null") != "null") ? JRequest::getVar("allow_no") : $result_array->ijseo_allow_no;
		$result_array->ijseo_type_key = trim(JRequest::getVar("type_key", "null") != "null") ? JRequest::getVar("type_key") : $result_array->ijseo_type_key;
		$result_array->ijseo_allow_no2 = trim(JRequest::getVar("allow_no2", "null") != "null") ? JRequest::getVar("allow_no2") : $result_array->ijseo_allow_no2;
		$result_array->ijseo_type_title = trim(JRequest::getVar("type_title", "null") != "null") ? JRequest::getVar("type_title") : $result_array->ijseo_type_title;
		$result_array->ijseo_gdesc = trim(JRequest::getVar("gdesc", "null") != "null") ? JRequest::getVar("gdesc") : $result_array->ijseo_gdesc;
		$result_array->ijseo_allow_no_desc = trim(JRequest::getVar("allow_no_desc", "null") != "null") ? JRequest::getVar("allow_no_desc") : $result_array->ijseo_allow_no_desc;
		$result_array->ijseo_type_desc = trim(JRequest::getVar("type_desc", "null") != "null") ? JRequest::getVar("type_desc") : $result_array->ijseo_type_desc;
		
		$exclude_key = JRequest::getVar("exclude_key", "null");
		if($exclude_key != "null"){
			$key_array =  explode(",", JRequest::getVar("exclude_key", ""));
			$result_array->exclude_key = $key_array;
		}
		
		$result_array->ijseo_Image_what = trim(JRequest::getVar("Image_what", "null") != "null") ? JRequest::getVar("Image_what") : $result_array->ijseo_Image_what;
		$result_array->ijseo_Image_number = trim(JRequest::getVar("Image_number", "null") != "null") ? JRequest::getVar("Image_number") : $result_array->ijseo_Image_number;
		$result_array->ijseo_Image_where = trim(JRequest::getVar("Image_where", "null") != "null") ? JRequest::getVar("Image_where") : $result_array->ijseo_Image_where;
		$result_array->ijseo_Image_when = trim(JRequest::getVar("Image_when", "null") != "null") ? JRequest::getVar("Image_when") : $result_array->ijseo_Image_when;
		$result_array->ijseo_gposition = trim(JRequest::getVar("gposition", "null") != "null") ? JRequest::getVar("gposition") : $result_array->ijseo_gposition;
		$result_array->ijseo_check_grank = trim(JRequest::getVar("check_gr", "null") != "null") ? JRequest::getVar("check_gr") : $result_array->ijseo_check_grank;
		$result_array->ijseo_keysource = trim(JRequest::getVar("keysource", "null") != "null") ? JRequest::getVar("keysource") : $result_array->ijseo_keysource;
		$result_array->ijseo_check_ext = trim(JRequest::getVar("ijseo_check_ext", "null") != "null") ? JRequest::getVar("ijseo_check_ext") : $result_array->ijseo_check_ext;
		$result_array->check_nr = trim(JRequest::getVar("check_nr", "null") != "null") ? JRequest::getVar("check_nr") : $result_array->check_nr;
		$result_array->delimiters = trim(JRequest::getVar("delimiters", "null") != "null") ? JRequest::getVar("delimiters", ",|;:") : $result_array->delimiters;
        $result_array->replace_in = trim(JRequest::getVar("replace_in", "null") != "null") ? JRequest::getVar("replace_in", "") : $result_array->replace_in;
		$result_array->case_sensitive = trim(JRequest::getVar("case_sensitive", "null") != "null") ? JRequest::getVar("case_sensitive", 0) : $result_array->case_sensitive;
		$result_array->sb_start = trim(JRequest::getVar("sb_start", "null") != "null") ? JRequest::getVar("sb_start") : $result_array->sb_start;
		$result_array->sb_end = trim(JRequest::getVar("sb_end", "null") != "null") ? JRequest::getVar("sb_end") : $result_array->sb_end;
		
		$params = json_encode($result_array);
		$query->clear();
		$query->update("#__ijseo_config");		
		$query->set("`params`='".addslashes(trim($params))."'");
		$db->setQuery($query);
		
		if(!$db->query()){
			return FALSE;
		}
		//move all titles to _titlekeys if is set tile keywords
		if(JRequest::getVar("keysource", "") == "1"){
			$this->moveTitles();
		}
		return TRUE;
	}
	
	function moveTitles(){
		//-------------------------------------- start for all components
		$database = JFactory::getDBO();
		$delimiters = JRequest::getVar("delimiters", ",|;:");
		$jnow	=  JFactory::getDate();
		$date	=  $jnow->toSQL();
		$sql = "select `titletag`, `id`, `mtype` from #__ijseo_metags";
		$database->setQuery($sql);
		$database->query();
		$old_titles1 = $database->loadAssocList();//exactly like in database, with |;,:				
		//make an array with id->titletag
		$temp = array();
		if(isset($old_titles1) && count($old_titles1) > 0){
			$i=0;
			foreach($old_titles1 as $key=>$value){
				if(trim($value["titletag"]) != ""){
					$temp[$value["id"]."*".$value["mtype"]."*".$i] = $value["titletag"];
				}
				$i++;
			}
			$old_titles1 = $temp;
		}				
						
		$old_titles2 = array();//titles without |;,:
		if(trim($delimiters) != ""){
			if(isset($old_titles1) && is_array($old_titles1) && count($old_titles1) > 0){
				foreach($old_titles1 as $key=>$value){
					if(trim($value) != ""){
						$temp = str_split(trim($delimiters));																			
						$value = str_replace($temp, "******", $value);//replace |,:; with ******								
						$temp_array = explode("******", $value);
						if(is_array($temp_array) && count($temp_array) > 0){
							foreach($temp_array as $temp_key=>$temp_value){
								if(trim($temp_value) != ""){
									$old_titles2[$key."*".$temp_key] = trim($temp_value);
								}
							}
						}
					}
				}//foreach
			}//if titles
		}//if delimiters
		else{
			$old_titles2 = $old_titles1; //if don't break by ;|;, then we have the original array
		}		
		//extract existings titles from _titlekeys, to not duplicate this rows
		$sql = "select `title` from #__ijseo_titlekeys";
		$database->setQuery($sql);
		$database->query();
		$new_titles1 = $database->loadColumn();//titles already existent
		
		if(isset($old_titles2) && isset($new_titles1)){
			$new_titles2 = array_diff($old_titles2, $new_titles1);//result titles aren't saved in titlekeys			
			if(isset($new_titles2) && is_array($new_titles2) && count($new_titles2) > 0){
				foreach($new_titles2 as $key=>$value){
					$temp_key = explode("*", $key);
					$sql  = "insert into #__ijseo_titlekeys(`title`, `rank`, `rchange`, `mode`, `checkdate`, `sticky`, `type`, `joomla_id`) values ";
					$sql .= "('".addslashes(trim($value))."', 0, 0, -1, '".$date."', 0, '".$temp_key["1"]."', ".$temp_key["0"].")";
					$database->setQuery($sql);
					$database->query();
				}
			}
		}
		//-------------------------------------- stop for all components
		//-------------------------------------- start for articles			
		$sql = "select article_id, title from #__ijseo_title ";
		$database->setQuery($sql);
		$database->query();
		$old_titles1 = $database->loadAssocList();//exactly like in database, with |;,:
		//make an array with id->titletag
		$temp = array();
		if(isset($old_titles1) && count($old_titles1) > 0){
			$i=0;
			foreach($old_titles1 as $key=>$value){
				if(trim($value["title"]) != ""){
					$temp[$value["article_id"]."*"."article"."*".$i] = $value["title"];
				}
				$i++;
			}
			$old_titles1 = $temp;
		}				
		$old_titles2 = array();//titles without |;,:
		
		if(trim($delimiters) != ""){
			if(isset($old_titles1) && is_array($old_titles1) && count($old_titles1) > 0){
				foreach($old_titles1 as $key=>$value){
					if(trim($value) != ""){
						$temp = str_split(trim($delimiters));																			
						$value = str_replace($temp, "******", $value);//replace |,:; with ******
						$temp_array = explode("******", $value);
						if(is_array($temp_array) && count($temp_array) > 0){
							foreach($temp_array as $temp_key=>$temp_value){
								if(trim($temp_value) != ""){
									$old_titles2[$key."*".$temp_key] = trim($temp_value);
								}
							}
						}
					}
				}//foreach
			}//if titles
		}//if delimiters
		else{
			$old_titles2 = $old_titles1; //if don't break by ;|;, then we have the original array
		}
		
		//extract existings titles from _titlekeys, to not duplicate this rows
		$sql = "select `title` from #__ijseo_titlekeys";
		$database->setQuery($sql);
		$database->query();
		$new_titles1 = $database->loadColumn();//titles already existent
		
		if(isset($old_titles2) && isset($new_titles1)){
			$new_titles2 = array_diff($old_titles2, $new_titles1);//result titles aren't saved in titlekeys			
			if(isset($new_titles2) && is_array($new_titles2) && count($new_titles2) > 0){
				foreach($new_titles2 as $key=>$value){
					$temp_key = explode("*", $key);
					$sql  = "insert into #__ijseo_titlekeys(`title`, `rank`, `rchange`, `mode`, `checkdate`, `sticky`, `type`, `joomla_id`) values ";
					$sql .= "('".addslashes(trim($value))."', 0, 0, -1, '".$date."', 0, '".$temp_key["1"]."', ".$temp_key["0"].")";
					$database->setQuery($sql);
					$database->query();
				}
			}
		}
		//-------------------------------------- stop for articles
	}
}

?>