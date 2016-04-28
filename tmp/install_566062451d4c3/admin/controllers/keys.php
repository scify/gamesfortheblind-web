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

defined( '_JEXEC' ) or die( 'Restricted access' );

class iJoomla_SeoControllerKeys extends iJoomla_SeoController{
	
	function __construct() {	  
		parent::__construct();		
		// Register Extra tasks		
		$this->registerTask('', 'keys');
		$this->registerTask('keys', 'keys');
		$this->registerTask('save', 'save');
		$this->registerTask('remove', 'delete');
		$this->registerTask('sticky', 'sticky_unsticky');
		$this->registerTask('unsticky', 'sticky_unsticky');
		$this->registerTask('change_sticky', 'changeSticky');
		$this->registerTask('get_Grank', 'getKeyRank');
		$this->registerTask('change', 'change');
	}
	
	function keys(){
		JRequest::setVar( 'view', 'Keys' );	
		parent::display();
	}
	
	function sticky_unsticky(){
		$model = $this->getModel('keys');
		$result = $model->getStickyUnsticky();
		$link = "index.php?option=com_ijoomla_seo&controller=keys";
		if($result === TRUE){
			$msg = JText::_("COM_IJOOMLA_SEO_STICKY_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		}
		else{
			$msg = JText::_("COM_IJOOMLA_SEO_STICKY_UNSUCCESSFULLY");
			$this->setRedirect($link, $msg, 'notice');
		}
	}
	
	function save(){
		$model = $this->getModel('keys');
		$result = $model->save();
		$link = "index.php?option=com_ijoomla_seo&controller=keys";
		if($result === TRUE){
			$msg = JText::_("COM_IJOOMLA_SEO_ADDED_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		}
		else{
			$msg = JText::_("COM_IJOOMLA_SEO_ADDED_UNSUCCESSFULLY");
			$this->setRedirect($link, $msg, 'error');
		}
		return true;
	}
	
	function delete(){
		$model = $this->getModel('keys');
		$result = $model->delete();
		$link = "index.php?option=com_ijoomla_seo&controller=keys";
		if($result === TRUE){
			$msg = JText::_("COM_IJOOMLA_SEO_DELETED_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		}
		else{
			$msg = JText::_("COM_IJOOMLA_SEO_DELETED_UNSUCCESSFULLY");
			$this->setRedirect($link, $msg, 'error');
		}
		return true;
	}
	
	function cancel(){
		$msg = JText::_('COM_IJOOMLA_SEO_OPERATION_CANCELED');
		$this->setRedirect('index.php?option=com_ijoomla_seo', $msg);
	}
	
	function changeSticky(){
		$database = JFactory::getDBO();
		
		$sql = "select params from #__ijseo_config";
		$database->setQuery($sql);
		$database->query();
		$param = $database->loadColumn();
		$param = @$param["0"];
		$params = json_decode($param);
	
		$sid = intval(JRequest::getVar("sid"));
		$onoff = intval(JRequest::getVar("onoff"));
		
		if($params->ijseo_keysource == "0"){
			$sql = " update #__ijseo_keys set `sticky` = ".$onoff." where id = ".$sid;
			$database->setQuery($sql);		
			if(!$database->query()){
				 echo $database->getErrorMsg();
			}
		}
		else{
			$sql = " update #__ijseo_titlekeys set `sticky` = ".$onoff." where id = ".$sid;
			$database->setQuery($sql);		
			if(!$database->query()){
				 echo $database->getErrorMsg();
			}
		}
		die();
	}
	
	function getKeyRank(){
		$key = JRequest::getVar("key", "");	
		$key = str_replace("*and*", "&", $key);
		$oldrank = JRequest::getVar("oldrank");
		
		global $database;
			
		$params = $this->getComponentParams();
	
		// exact word or phrase
		if(!isset($params->ijseo_check_ext)){
			$params->ijseo_check_ext = "com";
		}	
		if(!isset($params->check_nr)){
			$params->check_nr = "10";
		}
		
		$google_pos = $this->google_position($params->check_nr, $key, $params->ijseo_check_ext);
		
		$currentDate = date("Y-m-d G:i:s");
		
		if(intval($google_pos) > 0){
			$this->updateRank($oldrank, $google_pos, $currentDate, $key);
		}
		else{
			$this->updateRank($oldrank, 0, $currentDate, $key);
		}
		echo intval($google_pos);
		die();
	}
	
	function getComponentParams(){
		$database = JFactory::getDBO();
		$sql = "select params from #__ijseo_config";
		$database->setQuery($sql);
		$database->query();
		$param = $database->loadColumn();
		$param = @$param["0"];
		$params = json_decode($param);
		return $params;
	}
	
	function google_position($total_to_search, $searchquery, $google){
		$searchurl = $_SERVER['HTTP_HOST'];
		
		if(!empty($searchquery) && !empty($searchurl)){
			$query = str_replace(" ","+",$searchquery);
			$query = str_replace("%26","&",$query);
	 
			// The number of hits per page.
			$hits_per_page = 10;
	 
			// Obviously, the total pages / queries we will be doing is
			// $total_to_search / $hits_per_page
	 
			// This will be our rank
			$position = 0;
	 
			for($i=0; $i<$total_to_search; $i+=$hits_per_page){
				// Open the search page.
				// We are filling in certain variables -
				// $query,$hits_per_page and $start.
	 
				$filename = "http://www.google.$google/search?as_q=$query".
				"&num=$hits_per_page&hl=en&ie=UTF-8&btnG=Google+Search".
				"&as_epq=&as_oq=&as_eq=&lr=&as_ft=i&as_filetype=".
				"&as_qdr=all&as_nlo=&as_nhi=&as_occt=any&as_dt=i".
				"&as_sitesearch=&safe=images&start=$i";
				
				if(function_exists('curl_init')) {
					$ch = curl_init($filename); // initialize curl with given url
					curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // add useragent
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // write the response to a variable
					if((ini_get('open_basedir') == '') && (ini_get('safe_mode') == 'Off')) {
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects if any
					}
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // max. seconds to execute
					curl_setopt($ch, CURLOPT_FAILONERROR, 1); // stop when it encounters an error
					$var = curl_exec($ch);
				}
				else{ 
					$var = file_get_contents($filename);
				}
				
				// split the page code by "<h3 class" which tops each result
				$fileparts = explode("<h3 class=", $var);
				
				for($f=1; $f<sizeof($fileparts); $f++) {
					$position++;
					if(strpos($fileparts[$f], $searchurl)){
						return $position;
					}
				}
			}
		}
	}
	
	function updateRank($oldrank, $newrank, $currentDate, $key){				
		$change = 0;
		$mode = -1;
		$database = JFactory::getDBO();
		$params = $this->getComponentParams();
		
		if($newrank > 0){
			$change = abs($newrank - $oldrank);
		}	
		if($newrank > $oldrank && $oldrank > 0){
			$mode = 0;
		}	
		elseif(($oldrank >0  && $newrank < $oldrank) || ($oldrank == 0 && $newrank >0)){
			$mode = 1;
		}	
		
		if($params->ijseo_keysource == "1"){
			$sql = "update #__ijseo_titlekeys set rank = ".$newrank." , rchange =  ".$change.", mode = ".$mode." , checkdate = '".$currentDate."' where title = '".addslashes(trim($key))."'";
		}
		else{
			$sql = "update #__ijseo_keys set rank = ".$newrank." , rchange =  ".$change.", mode = ".$mode." , checkdate = '".$currentDate."' where title = '".addslashes(trim($key))."'";
		}	
		$database->setQuery($sql);
		
		if(!$database->query()){
			return $database->getErrorMsg();
		}
		
		//save statistics rank
		$currentDate = strtotime($currentDate);
		$currentDate = date("Y-m-d", $currentDate);
		
		$sql = "select count(*) from #__ijseo_statistics where `check_date` = '".trim($currentDate)."'";
		$database->setQuery($sql);
		$database->query();
		$count = $database->loadColumn();
		$count = @$count["0"];
		
		if(intval($oldrank) < intval($newrank)){ // rank_down
			if($count == 0){
				$sql = "insert into #__ijseo_statistics (`check_date`, `rank_up`, `rank_down`, `rank_same`) values ('".$currentDate."', 0, 1, 0)";
				$database->setQuery($sql);
				$database->query();
			}
			else{
				$sql = "update #__ijseo_statistics set `rank_down` = `rank_down`+1 where `check_date`='".$currentDate."'";
				$database->setQuery($sql);
				$database->query();
			}
		}
		elseif(intval($oldrank) > intval($newrank)){ // rank_up
			if($count == 0){
				$sql = "insert into #__ijseo_statistics (`check_date`, `rank_up`, `rank_down`, `rank_same`) values ('".$currentDate."', 1, 0, 0)";
				$database->setQuery($sql);
				$database->query();
			}
			else{
				$sql = "update #__ijseo_statistics set `rank_up` = `rank_up`+1 where `check_date`='".$currentDate."'";
				$database->setQuery($sql);
				$database->query();
			}
		}
		else{ // rank_same
			if($count == 0){
				$sql = "insert into #__ijseo_statistics (`check_date`, `rank_up`, `rank_down`, `rank_same`) values ('".$currentDate."', 0, 0, 1)";
				$database->setQuery($sql);
				$database->query();
			}
			else{
				$sql = "update #__ijseo_statistics set `rank_same` = `rank_same`+1 where `check_date`='".$currentDate."'";
				$database->setQuery($sql);
				$database->query();
			}
		}
	}
	
	function change(){
		$params = $this->getComponentParams();
		$key = JRequest::getVar("key");
		$key = str_replace("*and*", "&", trim($key));
		$val = intval(JRequest::getVar("val"));
		$mode = intval(JRequest::getVar("mode"));
		$database = JFactory::getDBO();
		
		if($val == 0){
			$mode = -1;
		}
		if($params->ijseo_keysource == "1"){
			$sql = "update #__ijseo_titlekeys set rchange = ".$val.", mode = ".$mode." where title = '".addslashes(trim($key))."'";	
			$database->setQuery($sql);
			if(!$database->query()){
				return $database->getErrorMsg();
			}
		}
		else{
			$sql = "update #__ijseo_keys set rchange = ".$val.", mode = ".$mode." where title = '".addslashes(trim($key))."'";	
			$database->setQuery($sql);
			if(!$database->query()){
				return $database->getErrorMsg();
			}
		}
		die();	
	}
}

?>