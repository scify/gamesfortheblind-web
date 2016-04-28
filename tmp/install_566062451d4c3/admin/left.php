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

$task = JRequest::getVar("task", "");
if($task == "vimeo" || $task == "youtube" || $task == "edit_page" || $task == "change_sticky"){
	return false;
}

function getCurrentVersionData(){
	$component = "com_ijoomla_seo";
	$version = "";		
	$data = 'www.ijoomla.com/ijoomla_latest_version.txt';		
	$ch = @curl_init($data);
	@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	@curl_setopt($ch, CURLOPT_TIMEOUT, 10); 							
	
	$version = @curl_exec($ch);
	if(isset($version) && trim($version) != ""){					
		$pattern = "/3.0_".$component."=(.*);/msU";	
		preg_match($pattern, $version, $result);
		if(is_array($result) && count($result) > 0){
			$version = trim($result["1"]);
		}
		return $version;
	}
	return false;
}

function getLocalVersionString(){
	$component = "com_ijoomla_seo";
	$xml_file = "ijoomla_seo.xml";
	
	$version = '';
	$path = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.$component.DS.$xml_file;
	if(file_exists($path)){
		$data = implode("", file($path));
		$pos1 = strpos($data,"<version>");
		$pos2 = strpos($data,"</version>");
		$version = substr($data, $pos1+strlen("<version>"), $pos2-$pos1-strlen("<version>"));
		return $version;
	}
	else{
		return "";
	}
}

$latest_version = getCurrentVersionData();
$installed_version = getLocalVersionString();

?>
<div class="g_admin_top_wrap">
<div class="ui-app">

    <div class="navbar">
        <div class="navbar-inner">
            <div class="container-fluid">
                <div class="nav-collapse collapse">
                    <div class="pull-left">
                        <a target="_blank" href="http://seo.ijoomla.com/"><img src="components/com_ijoomla_seo/images/logo_top.png" /></a>
                        <span class="badge badge-important" id="jomsocial-version">V <?php echo $installed_version; ?></span>
                        <?php
                        	if($latest_version != $installed_version){
								echo '&nbsp;&nbsp;<span class="white-color">'.JText::_("COM_IJOOMLA_SEO_NEW_VERSION_AVAILABLE").": V ".$latest_version.'&nbsp; (<a href="http://www.ijoomla.com/redirect/seo/changelog.htm" target="_blank">'.JText::_("COM_IJOOMLA_SEO_CHANGE_LOG").'</a>)  (<a href="http://www.ijoomla.com/redirect/general/latestversion.htm" target="_blank">'.JText::_("COM_IJOOMLA_SEO_DOWNLOAD").'</a>) </span>';
							}
						?>
                    </div>
                    <div class="pull-right">
                        <div class="ui-app">
                            <div class="navbar2">
                                <div class="g_navbar-inner">
                                    <div class="container-fluid">
                                        <div class="nav-collapse collapse">
                                            <div class="span12">
                                                <div id="g_rating">
                                                	<ul>
                                                        <li class="pull-right"><a href="http://twitter.com/ijoomla" target="_blank" />
                                                            <?php
                                                            echo '<span class="small-text">'.JText::_("COM_SEO_TWITTER").'</span>';
                                                            ?>
                                                            <img src="components/com_ijoomla_seo/images/icons/twitter.png" />
                                                            </a></li>
                                                        <li class="pull-right"><a href="https://www.facebook.com/ijoomla" target="_blank" />
                                                            <?php
                                                            echo '<span class="small-text">'.JText::_("COM_SEO_FACEBOOK").'</span>';
                                                            ?>
                                                            <img src="components/com_ijoomla_seo/images/icons/facebook.png" />
                                                            </a></li>
                                                     </ul>
                                                 </div>    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end nav bar-->
</div>
<div class="clearfix"></div>


<div class="clearfix"></div>
</div>