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

class iJoomla_SeoModelLanguage extends JModelLegacy{
	
	function getLanguage(){
		$filename = JPATH_SITE.DS."administrator".DS."language".DS."en-GB".DS."en-GB.com_ijoomla_seo.ini";
		$handle = fopen($filename, 'r');
		$language = fread($handle, filesize($filename));		
		return $language;
	}
	
	function save(){
        $ok = false; 
		$data = JRequest::get( 'post', JREQUEST_ALLOWHTML );	
		$filename = JPATH_SITE.DS."administrator".DS."language".DS."en-GB".DS."en-GB.com_ijoomla_seo.ini";		
        $text = $this->slashes($data["filecontent"]);	
		$g = fopen ($filename, "w");
		if(fwrite($g, $text)){
		    $ok = true; 
		}		
		fclose ($g);		
		return $ok;
    } 
	
	function slashes($string){	
		while(strstr($string, '\\')) {
			$string=stripslashes($string);
		}
		return $string;
	}
}

?>