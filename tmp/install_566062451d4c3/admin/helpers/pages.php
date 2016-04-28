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
JHtml::_('bootstrap.tooltip');
JHTML::_('behavior.modal');

class Page{
	
	function createEditPage(){
		$db = JFactory::getDBO();
		$id = JRequest::getVar("id", "0");
		
		$sql = "select c.`introtext`, c.`fulltext`, c.`metakey`, c.`metadesc`, m.`titletag` from #__content c left outer join #__ijseo_metags m on m.`id`=c.`id` and m.`mtype`='article' where c.`id`=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		
		$content = $result["0"]["introtext"].'<hr id="ijoomla_separator"/>'.$result["0"]["fulltext"];
		
		$editor = JFactory::getEditor();
		$param = JRequest::getVar("dataOBJ");
		$param = str_replace("\\", "", $param);
		
		$param = str_replace('*ij*', '&', $param);
		
		$params = json_decode($param);
		echo '<link type="text/css" rel="stylesheet" href="templates/khepri/css/general.css"></link>
			<link type="text/css" rel="stylesheet" href="templates/khepri/css/component.css">
			<link type="text/css" rel="stylesheet" href="templates/khepri/css/template.css">
			<link type="text/css" rel="stylesheet" href="components/com_ijoomla_seo/seostyle.css">
			<script type="text/javascript" src="components/com_ijoomla_seo/javascript/scripts.js"></script>
			
			<style type="text/css">
				#textedit_ifr{
					height:auto !important;
				}
				
				form{
					margin: 0px 0px 0px 0px !important;
				}
			</style>
			';
						
		echo '<form name="metatags" id="metatags" method="post" action="index.php?option=com_ijoomla_seo&amp;task=savepage&amp;id='.JRequest::getVar("id").'" >
		   <table cellspacing="3" cellpadding="0" border="0">
			<tr>
				<td valign="top" align="center" colspan="2" style="padding:5px 0px 5px 0px;">
					<input type="submit" class="btn btn-warning" value=" Save " name="saveMtags" />
				</td>
			</tr>
			<tr>
				<td width="35%" valign="top">'.JText::_("COM_IJOOMLA_SEO_TITLE_MTAGS").'</td>				
				<td valign="top">
					<input type="text" name="mtitle" value="'.$result["0"]["titletag"].'" id="mtitle" style="padding:2px; width:300px"/>
				</td>
			</tr>
			<tr>
				<td valign="top">'.JText::_("COM_IJOOMLA_SEO_KEYWORDS_MTAGS").'</td>				
				<td valign="top">
					<textarea cols="45" rows="1"  name="metakey" id="mkey">'.$result["0"]["metakey"].'</textarea>
				</td>
			</tr>
			<tr>
				<td valign="top">'.JText::_("COM_IJOMLA_SEO_DESCRIPTION_METATAG").'</td>				
				<td valign="top">
					<textarea cols="45" rows="2"  name="metadesc" id="mdesc">'.$result["0"]["metadesc"].'</textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2">'.$editor->display( 'textedit', $content , '95%', '200px', '20', '20' ) .'</td>
			</tr>
			<tr><td colspan="2"><br/></td></tr>
						
			</table>
			<input type="hidden" name="option" value="com_ijoomla_seo" />
			<input type="hidden" name="controller" value="pages" />
			<input type="hidden" name="task" value="savepage" />
			<input type="hidden" name="id" value="'.$id.'" />
			</form>';
	}
	
	function createviewArticle(){
		$params = $this->getComponentParams();	
		$key_temp = JRequest::getVar("dataOBJ", "");
		$key = str_replace("\\", "", $key_temp);
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		
		if($params->ijseo_keysource == "0"){
			$query->select('`id`, `title`');
			$query->from('#__content');
			$query->where("metakey like '%,".addslashes($key).",%' or metakey like '%".addslashes($key)."%' or metakey like '%".addslashes($key).",%' or metakey like '%,".addslashes($key)."'");
		}
		else{
			$query->select('`id`, `title`');
			$query->from('#__content');
			$query->where("attribs like '%".trim(addslashes($key))."%'");
		}		
		$db->setQuery($query);		
		$db->query();
		$result = $db->loadAssocList();
		$return = "";
		if(count($result)>0){
			$return .= '<link type="text/css" rel="stylesheet" href="templates/khepri/css/general.css"></link>
			<link type="text/css" rel="stylesheet" href="templates/khepri/css/component.css">
			<link type="text/css" rel="stylesheet" href="templates/khepri/css/template.css">
			<link type="text/css" rel="stylesheet" href="components/com_ijoomla_seo/seostyle.css">
			<script type="text/javascript" src="components/com_ijoomla_seo/javascript/scripts.js"></script>';
			$i = 1;
			$return .= "<table>";			
			foreach($result as $key=>$value){
				$return .= "<tr>";
				$return .= 		"<td>";
				$return .= 			'<a href="index.php?option=com_ijoomla_seo&task=edit_article&tmpl=component&controller=keysarticles&id='.$value["id"].'">'.$i."&nbsp;&nbsp;".$value["title"]."</a>";
				$return .= 		"</td>";
				$return .= "</tr>";
				$i++;
			}
			$return .= "</table>";
		}
		echo $return;
	}
	
	function createEditArticle(){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->clear();
		$query->select('`introtext`, `fulltext`, `attribs`, `metakey`, `metadesc`');
		$query->from('#__content');
		$query->where("`id`=".intval(JRequest::getVar("id")));
		$db->setQuery($query);		
		$db->query();
		$result = $db->loadAssocList();
		$content = $result["0"]["introtext"].$result["0"]["fulltext"];
		
		$editor = JFactory::getEditor();
		$param = $result["0"]["attribs"];
		$param = str_replace("\\", "", $param);
		$params = json_decode($param);
		
		echo '<link type="text/css" rel="stylesheet" href="templates/khepri/css/general.css"></link>
			<link type="text/css" rel="stylesheet" href="templates/khepri/css/component.css">
			<link type="text/css" rel="stylesheet" href="templates/khepri/css/template.css">
			<link type="text/css" rel="stylesheet" href="components/com_ijoomla_seo/css/seostyle.css">
			<script type="text/javascript" src="components/com_ijoomla_seo/javascript/scripts.js"></script>';
						
		echo '<form name="metatags" id="metatags" method="post" action="index.php?option=com_ijoomla_seo&amp;task=savepage&amp;id='.JRequest::getVar("id").'" >
		   <table cellspacing="3" cellpadding="0" border="0">
			<tr>
				<td width="35%" valign="top">'.JText::_("COM_IJOOMLA_SEO_TITLE_MTAGS").'</td>				
				<td valign="top">
					<input type="text" name="mtitle" value="'.$params->page_title.'" id="mtitle" style="padding:2px; width:300px"/>
				</td>
			</tr>
			<tr>
				<td valign="top">'.JText::_("COM_IJOOMLA_SEO_KEYWORDS_MTAGS").'</td>				
				<td valign="top">
					<textarea cols="45" rows="1"  name="metakey" id="mkey">'.$result["0"]["metakey"].'</textarea>
				</td>
			</tr>
			<tr>
				<td valign="top">'.JText::_("COM_IJOMLA_SEO_DESCRIPTION_METATAG").'</td>				
				<td valign="top">
					<textarea cols="45" rows="2"  name="metadesc" id="mdesc">'.$result["0"]["metadesc"].'</textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2">'.$editor->display( 'textedit', $content , '100%', '200px', '55', '10' ) .'</td>
			</tr>
			<tr><td colspan="2"><br/></td></tr>
			<tr>				
				<td valign="top" align="left">
					<a id="box_button1" rel="{handler: \'iframe\', size: {x: 600, y: 500}}" href="index.php?option=com_ijoomla_seo&amp;task=view_articles&amp;tmpl=component&amp;controller=keysarticles&amp;id='.intval(JRequest::getVar("id")).'&amp;dataOBJ='. urlencode(mysql_real_escape_string($result["0"]["metakey"])).'" class="modal" >'.JText::_("COM_IJOOMLA_SEO_GOBACK").'</a>
				</td>
				
				<td valign="top" align="right">
					<input type="submit" class="btn btn-warning" onclick="f_refresh2()" value=" Save " name="saveMtags" id="box_button2">
				</td>
			</tr>
			</table>
			<input type="hidden" name="option" value="com_ijoomla_seo" />
			<input type="hidden" name="controller" value="keysarticles" />
			<input type="hidden" name="task" value="savepage" />
			<input type="hidden" name="id" value="'.JRequest::getVar("id").'" />
			</form>';
	}
	
	function createOutLinks(){
		$data =  JRequest::getVar('data', 0, 'get', 'int');	 
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->clear();
		$query->select('*');
		$query->from('#__content');
		$query->where("`id`=".intval($data));
		$db->setQuery($query);		
		$db->query();
		$row = $db->loadObjectList();		
		$content = $row[0]->introtext.$row[0]->fulltext;		
		$sitehost = preg_quote($_SERVER['HTTP_HOST']);			 
		$regx = '#http:[^<\s>]+[.][_a-z]{2,5}#i';
		preg_match_all($regx, $content, $links, PREG_PATTERN_ORDER);
		
		if(count($links)>0){			
			$z = 1;
			echo "<table>";
			foreach($links[0] as $i => $link){
				echo "<tr>";
				echo 	"<td>";
				echo 		$z."&nbsp;&nbsp;".$link;
				echo 	"</td>";
				echo "</tr>";
				$z ++;
			}
			echo "</table>";
		}
	}
	
	function getComponentParams(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();		
		$query->select('params');
		$query->from('#__ijseo_config');
		$db->setQuery($query);		
		$db->query();
		$result_string = $db->loadColumn();
		$result_string = @$result_string["0"];
		
		$result = json_decode($result_string);
		return $result;
	}
} 

?>
