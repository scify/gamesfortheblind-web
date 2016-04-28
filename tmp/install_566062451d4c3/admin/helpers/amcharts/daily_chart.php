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

$db = JFactory::getDBO();
$sql = "SELECT `check_date`, `rank_up`, `rank_down`, `rank_same` FROM `#__ijseo_statistics` order by `check_date` asc";
$db->setQuery($sql);
$db->query();
$result = $db->loadAssocList();

if(!isset($result) || count($result) == 0){
	$result = array("0"=>array("total"=>"0.1", "check_date"=>date("Y-m-d")));
}

if(isset($result) && count($result) > 0){
	$total_values = array();
	
	foreach($result as $key=>$value){
		@$total_values["rank_up"][strtotime($value["check_date"])."000"] += @$value["rank_up"];
		@$total_values["rank_down"][strtotime($value["check_date"])."000"] += @$value["rank_down"];
		@$total_values["rank_same"][strtotime($value["check_date"])."000"] += @$value["rank_same"];
	}
	
	if(isset($total_values) && count($total_values) > 0){
		echo '<script type="text/javascript" language="javascript">'."\n";
		$k = 1;
		$params_array = array();
		
		foreach($total_values as $key=>$value){
			ksort($value);
			$temp_array = array();
			
			foreach($value as $time=>$total){
				$temp_array[] = '['.$time.','.$total.']';
			}
			
			echo 'var variable_'.$k.' = ['.implode(", ", $temp_array).'];'."\n";
			echo 'var variable_'.$k.$k.' = ['.implode(", ", $temp_array).'];'."\n";
		 	
			$text = str_replace("rank_", "", $key);
			$text = ucfirst($text);
			
			$params_array[] = '{ data: variable_'.$k.', label: label_'.$k.' }';
			
			echo 'var label_'.$k.' = "'.$text.'";'."\n";
			echo 'var label_'.$k.$k.' = "'.$text.'";'."\n";
			
			$k ++;
		}
		
		echo '	function doPlot(position) {
					params_array = new Array();
					if(document.getElementById("ij-graf-up").checked){
						variable_1 = variable_11;
						label_1 = label_11;
					}
					else{
						variable_1 = [];
						label_1 = "";
					}
					
					if(document.getElementById("ij-graf-down").checked){
						variable_2 = variable_22;
						label_2 = label_22;
					}
					else{
						variable_2 = [];
						label_2 = "";
					}
					
					if(document.getElementById("ij-graf-same").checked){
						variable_3 = variable_33;
						label_3 = label_33;
					}
					else{
						variable_3 = [];
						label_3 = "";
					}
					
					$.plot("#placeholder", [
						'.implode(",", $params_array).'
					], {
						xaxes: [ { mode: "time" } ],
						yaxes: [ { min: 0 }, {
							// align if we are to the right
							alignTicksWithAxis: position == "right" ? 1 : null,
							position: position
						} ],
						legend: { position: "sw" }
					});
				}';
				
		echo '$(function() {'."\n";
		echo 	'doPlot("right");';				
		echo '});'."\n";
		
		echo '</script>';
	}
}
?>