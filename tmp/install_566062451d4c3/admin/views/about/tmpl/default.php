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

defined('_JEXEC') or die('Restricted Access');

//include(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."left.php");

$dir = JPath::clean(JPATH_SITE.DS."administrator".DS."components".DS."com_ijoomla_seo" );	
$xmlfilecomp = $dir.DS."ijoomla_seo.xml";
$plugin1 = JPath::clean(JPATH_SITE.DS."plugins".DS."content".DS."ijseo_plugin");
$plugin1 .=	DS."ijseo_plugin.xml";
$plugin2 = JPath::clean(JPATH_SITE.DS."plugins".DS."system".DS."ijseo");
$plugin2 .=	DS."ijseo.xml";

// read component/plugins version from xml file
$dates = $this->getVersion($xmlfilecomp, "iJoomla SEO");
$dates1 = $this->getVersion($plugin1, "ijseo_plugin");
$dates2 = $this->getVersion($plugin2, "System - iJSEO");

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_ijoomla_seo/css/seostyle.css");
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
        	<h2 class="pub-page-title" ><?php echo JText::_("COM_IJOOMLA_SEO_ABOUT"); ?></h2><div id = "about-content">
        <table class="table table-bordered">
        	<tr>
            	<th style="border-top: none !important; width:20%;"></th>
                <th style="border-top: none !important; width:20%;"><?php echo JText::_("COM_IJOOMLA_SEO_COMPONENT_NAME"); ?></th>
                <th style="border-top: none !important; width:20%;"><?php echo JText::_("COM_IJOOMLA_SEO_STATUS"); ?></th>
                <th style="border-top: none !important; width:30%;"><?php echo JText::_("COM_IJOOMLA_SEO_VERSION"); ?></th>
            </tr>
            <tr>
            	<td>
                	<b><?php echo JText::_("COM_IJOOMLA_SEO_COMPONENTS"); ?> </b>
                </td>
                <td>
                	+ <?php echo $dates['name']; ?>
                </td>
                <td>
                	<b><span style="color:green"><?php echo $dates['installed']?></span></b>
                </td>
                <td>
					<?php echo $dates['version']; ?>
                </td>
            </tr>
            <tr>
            	<td>
                	<b>
						<?php echo JText::_("COM_IJOOMLA_SEO_PLUGIN"); ?> - <?php echo JText::_("COM_IJOOMLA_SEO_CONTENT"); ?>
                    </b>
                </td>
                <td>
                	+ <?php echo $dates1['name']; ?>
                </td>
                <td>
                	<b><span style="color:green"><?php echo $dates1['installed']?></span></b>
                </td>
                <td>
					<?php echo $dates1['version']; ?>
                </td>
            </tr>
            <tr>
            	<td>
                	<b>
						<?php echo JText::_("COM_IJOOMLA_SEO_PLUGIN"); ?> - <?php echo JText::_("COM_IJOOMLA_SEO_SYSTEM"); ?>
                    </b>
                </td>
                <td>
                	+ <?php echo $dates2['name']; ?>
                </td>
                <td>
                	<b><span style="color:green"><?php echo $dates2['installed']?></span></b>
                </td>
                <td>
					<?php echo $dates2['version']; ?>
                </td>
            </tr>
        </table>
        <br/><div class="well well-minimized">
        <strong>iJoomla SEO</strong> is the ultimate solution for optimizing your Joomla site for search engines. Climb search results quickly, easily and with minimum effort, and spend more time developing your content!<br/><br/>
        With iJoomla SEO you can:<br/>

        &nbsp;&nbsp;&nbsp;&nbsp;* Get free traffic for your website!<br/>
        &nbsp;&nbsp;&nbsp;&nbsp;* Save time and money on professional SEO experts.<br/>
        &nbsp;&nbsp;&nbsp;&nbsp;* Let Google promote your site while you sleep.<br/>
        <br>
        For more information, visit <a href="http://www.ijoomla.com" target="_blank">www.ijoomla.com</a>
	</div></div>
    
	<input type="hidden" name="option" value="com_ijoomla_seo" />
	<input type="hidden" name="controller" value="about" />
	<input type="hidden" name="task" value="" />
</form>