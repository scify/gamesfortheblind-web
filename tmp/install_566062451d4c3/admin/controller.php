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

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

jimport('joomla.application.component.controller');

class iJoomla_SeoController extends JControllerLegacy{
	
	function __construct() {			
		parent::__construct();
		$task = JRequest::getVar("task", '', "get");
		$controller = JRequest::getVar("controller",'',"get");
		$controller_request = JRequest::getVar("controller",'',"get");
		
		if($controller_request == "about" && $task == "stats"){
			// do nothing, ajax call
		}
		elseif($controller_request == "keys" && ($task == "change_sticky" || $task == "get_Grank" || $task == "change")){
			// do nothing, ajax call
		}
		elseif($controller_request == "newilinks" && $task == "changeMenuItems"){
			// do nothing, ajax call
		}
		else{
			$document = JFactory::getDocument();
			$document->addStyleSheet("components/com_ijoomla_seo/css/ij30.css");
			
			$document->addStyleSheet( 'components/com_ijoomla_seo/css/bootstrap.min.css' );
			$document->addStyleSheet( 'components/com_ijoomla_seo/css/font-awesome.min.css' );
			
			if($controller_request == "pages" && $task == "edit_page"){
				// do nothing
			}
			elseif($controller_request == "keys" && ($task == "change_sticky")){
				// do nothing
			}
			elseif($controller_request == "about" && ($task == "vimeo" || $task == "youtube")){
				// do nothing
			}
			else{
				$document->addStyleSheet("components/com_ijoomla_seo/css/tmploverride.css");
				$document->addStyleSheet( 'components/com_ijoomla_seo/css/ace-fonts.css' );
				$document->addStyleSheet( 'components/com_ijoomla_seo/css/ace.min.css' );
			}
			$document->addStyleSheet( 'components/com_ijoomla_seo/css/fullcalendar.css' );
	?>	
			
            <style>
				body{
					padding:0px !important;
				}
			</style>
            
            <script>
            	$ = jQuery.noConflict();
            </script>
			<script src="components/com_ijoomla_seo/javascript/ace-elements.min.js"></script>
            <script src="components/com_ijoomla_seo/javascript/ace.min.js"></script>
            <script type="text/javascript">
                <?php
                    $controller = JRequest::getVar("controller", "");
                    if($controller != ""){
                ?>
						document.write("<script src='components/com_ijoomla_seo/javascript/jquery-1.9.1.min.js'>"+"<"+"/script>");
                <?php
                    }
                ?>
                //document.write("<script src='http://code.jquery.com/ui/1.10.3/jquery-ui.js'>"+"<"+"/script>");
				document.write("<script src='<?php echo JURI::root(); ?>administrator/components/com_ijoomla_seo/javascript/jquery-ui-1.10.3.min.js'>"+"<"+"/script>");
				document.write("<script src='<?php echo JURI::root(); ?>administrator/components/com_ijoomla_seo/javascript/jquery-ui-1.10.3.custom.min.js'>"+"<"+"/script>");
            </script>

<?php
		}
	}

	function display($cachable = false, $urlparams = array()){		
		JRequest::setVar('view', JRequest::getCmd('view', 'iJoomla_Seo'));
		$this->setParams();
		parent::display($cachable, $urlparams);
	}
	
	function setParams(){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->clear();		
		$query->select ('`params`');
		$query->from('#__ijseo_config');
		$db->setQuery($query);			
		$db->query();
		$result = $db->loadColumn();
		$result = @$result["0"];
		
		if($result == "" || $result == "{}"){
			$params = array();
			$params["ijseo_allow_no_desc"] = "150";
			$params["ijseo_check_grank"] = "1";
			$params["ijseo_keysource"] = "0";
			$params["ijseo_gposition"] = "1";
			$params["ijseo_allow_no"] = "300";
            $params["ijseo_allow_no2"] = "57";
			$params["ijseo_Replace1"] = "";
			$params["ijseo_Replace2"] = "";
			$params["ijseo_Replace3"] = "";
			$params["ijseo_Replace4"] = "";
			$params["ijseo_Replace5"] = "";
			$params["ijseo_Replace1_with"] = "noreplace";
			$params["ijseo_Replace2_with"] = "noreplace";
			$params["ijseo_Replace3_with"] = "noreplace";
			$params["ijseo_Replace4_with"] = "noreplace";
			$params["ijseo_Replace5_with"] = "noreplace";
			$params["ijseo_wrap_key"] = "nowrap";
			$params["ijseo_type_title"] = "Characters";
            $params["ijseo_type_key"] = "Characters";
			$params["ijseo_gdesc"] = "intro";
			$params["ijseo_type_desc"] = "Characters";
			$params["exclude_key"] = array('');
			$params["ijseo_Image_what"] = "up to";
			$params["ijseo_Image_number"] = "1";
			$params["ijseo_Image_where"] = "keyword";
			$params["ijseo_Image_when"] = "NotSpecified";
			$params["ijseo_wrap_partial"] = "0";
			$params["delimiters"] = ",|;:";
			$params["ijseo_check_ext"] = "com";
			$params["check_nr"] = "20";
			
			$sql = "insert into #__ijseo_config (`params`) values ('".json_encode($params)."')";
			$db->setQuery($sql);			
			$db->query();			
		}				
	}
}

?>