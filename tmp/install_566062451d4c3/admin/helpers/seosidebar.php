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

jimport('joomla.application.component.modellist');
jimport('joomla.utilities.date');

$task = JRequest::getVar("task", "");
if($task == "vimeo" || $task == "youtube" || $task == "edit_page" || $task == "change_sticky"){
	return false;
}

$controller_req = JRequest::getVar("controller", "");
$layout = JRequest::getVar("layout", "");
$task = JRequest::getVar("task2", "");
$action = JRequest::getVar("action", "");
$pending = JRequest::getVar("pending", "0");
$state = JRequest::getVar("state", "");

$display_settings = "none";
$display_redirrect = "none";
$display_keywords = "none";
$display_metatags = "none";

$li_settings = "";
$li_redirrect = "";
$li_keywords = "";
$li_metatags = "";
$li_track = "";
$li_pages = "";
$li_about = "";


if(($controller_req == "config" &&  ($task =="general" || $task =="track_keywords" || $task =="google_ping" ))|| $controller_req == "language"){
    $display_settings = "block";
    $li_settings = 'class="open"';
}
elseif($controller_req == "redirect" || $controller_req == "redirectcategory" || $controller_req == "newredirect" || $controller_req == "newredcategory"){
    $display_redirrect = "block";
    $li_redirrect = 'class="open"';
}
elseif($controller_req == "ilinks" || $controller_req == "ilinkscategory" || $controller_req == "newilinks" || $controller_req == "newilinkscategory" || ($controller_req == "config" && $task == "keyword_linking")){
    $display_keywords = "block";
    $li_keywords = 'class="open"';
}
elseif($controller_req == "menus" || ($controller_req == "config" && $task =="manage_meta")) {
    $display_metatags = "block";
    $li_metatags = 'class="open"';
}
elseif($controller_req == "about") {
    $li_about = 'class="active"';
}
elseif($controller_req == "keys") {
    $li_track = 'class="active"';
}
elseif($controller_req == "pages") {
    $li_pages = 'class="active"';
}

?>


<div id="sidebar" class="sidebar">

<ul class="nav nav-list">

<li <?php if($controller_req == ""){ echo 'class="active"';} ?>>
    <a href="index.php?option=com_ijoomla_seo">
        <i class="icon-home"></i>
        <?php echo JText::_("COM_IJOOMLA_SEO_CONTROL_PANEL"); ?>
    </a>
</li>

<li <?php echo $li_settings; ?>>
    <a class="dropdown-toggle" href="#">
        <i class="icon-wrench"></i>
        <span class="menu-text">  <?php echo JText::_("COM_IJOOMLA_SEO_LM_SETTINGS"); ?> </span>
        <b class="arrow js-icon-angle-down"></b>
    </a>

    <ul class="submenu" style="display:<?php echo $display_settings; ?>;">
        <li <?php if($controller_req == "config" && $task == "general"){ echo 'class="active"';} ?> >
            <a href="index.php?option=com_ijoomla_seo&controller=config&task2=general">
                <i class="js-icon-double-angle-right"></i>
                <?php echo JText::_("COM_IJOOMLA_SEO_GENERAL"); ?>
            </a>
        </li>

        <li <?php if($controller_req == "config" && $task == "track_keywords"){ echo 'class="active"';} ?>>
            <a href="index.php?option=com_ijoomla_seo&controller=config&task2=track_keywords">
                <i class="js-icon-double-angle-right"></i>
                <?php echo JText::_("COM_IJOOMLA_SEO_KEYWORDS_KEY"); ?>
            </a>
        </li>

        <li <?php if($controller_req == "config" && $task == "google_ping"){ echo 'class="active"';} ?>>
            <a href="index.php?option=com_ijoomla_seo&controller=config&task2=google_ping">
                <i class="js-icon-double-angle-right"></i>
                <?php echo JText::_("COM_IJOOMLA_SEO_GOOGLE_PING"); ?>
            </a>
        </li>

        <li <?php if($controller_req == "language"){ echo 'class="active"';} ?>>
            <a href="index.php?option=com_ijoomla_seo&controller=language&id=english.ijoomla_seo&hidemainmenu=1">
                <i class="js-icon-double-angle-right"></i>
                <?php echo JText::_("COM_IJOOMLA_SEO_LANGUAGES"); ?>
            </a>
        </li>
    </ul>
</li>

<li <?php echo $li_metatags; ?>>
    <a class="dropdown-toggle" href="#">
        <i class="icon-tags"></i>
        <span class="menu-text">  <?php echo JText::_("COM_IJOOMLA_SEO_METATAGS"); ?> </span>
        <b class="arrow js-icon-angle-down"></b>
    </a>
    <ul class="submenu" style="display:<?php echo $display_metatags; ?>;">
        <li <?php if($controller_req == "menus"){ echo 'class="active"';} ?>>
            <a href="index.php?option=com_ijoomla_seo&controller=articles&choosemain=1">
                <i class="js-icon-double-angle-right"></i>
                <?php echo JText::_("COM_IJOOMLA_SEO_SEO_METATAGS"); ?>
            </a>
        </li>

        <li <?php if($controller_req == "config" && $task == "manage_meta"){ echo 'class="active"';} ?>>
            <a href="index.php?option=com_ijoomla_seo&controller=config&task2=manage_meta">
                <i class="js-icon-double-angle-right"></i>
                <?php echo JText::_("COM_IJOOMLA_SEO_LM_SETTINGS"); ?>
            </a>
        </li>
    </ul>
</li>

<li <?php echo $li_track; ?>>
    <a href="index.php?option=com_ijoomla_seo&controller=keys">
        <i class="icon-book"></i>
        <?php echo JText::_("COM_IJOOMLA_SEO_KEYWORDS"); ?>
    </a>
</li>

<li <?php echo $li_pages; ?>>
    <a href="index.php?option=com_ijoomla_seo&controller=pages">
        <i class="icon-file"></i>
        <?php echo JText::_("COM_IJOOMLA_SEO_PAGES"); ?>
    </a>
</li>

<li <?php echo $li_redirrect; ?>>
    <a class="dropdown-toggle" href="#">
        <i class="icon-reply"></i>
        <span class="menu-text">  <?php echo JText::_("COM_IJOOMLA_SEO_REDIRECTS"); ?> </span>
        <b class="arrow js-icon-angle-down"></b>
    </a>

    <ul class="submenu" style="display:<?php echo $display_redirrect; ?>;">
        <li <?php if($controller_req == "redirect" || $controller_req == "newredirect"){ echo 'class="active"';} ?>>
            <a href="index.php?option=com_ijoomla_seo&controller=redirect">
                <i class="js-icon-double-angle-right"></i>
                <?php echo JText::_("COM_IJOOMLA_SEO_REDIRECTS"); ?>
            </a>
        </li>

        <li <?php if($controller_req == "redirectcategory" || $controller_req == "newredcategory"){ echo 'class="active"';} ?>>
            <a href="index.php?option=com_ijoomla_seo&controller=redirectcategory">
                <i class="js-icon-double-angle-right"></i>
                <?php echo JText::_("COM_IJOOMLA_SEO_KTWO_CAETGORY"); ?>
            </a>
        </li>
    </ul>
</li>

<li <?php echo $li_keywords; ?>>
    <a class="dropdown-toggle" href="#">
        <i class="icon-link"></i>
        <span class="menu-text">  <?php echo JText::_("COM_IJOOMLA_SEO_INTERNAL_LINKS"); ?> </span>
        <b class="arrow js-icon-angle-down"></b>
    </a>

    <ul class="submenu" style="display:<?php echo $display_keywords; ?>;">
        <li <?php if($controller_req == "ilinks" || $controller_req == "newilinks"){ echo 'class="active"';} ?>>
            <a href="index.php?option=com_ijoomla_seo&controller=ilinks">
                <i class="js-icon-double-angle-right"></i>
                <?php echo JText::_("COM_IJOOMLA_SEO_INTERNAL_LIST"); ?>
            </a>
        </li>

        <li <?php if($controller_req == "ilinkscategory" || $controller_req == "newilinkscategory"){ echo 'class="active"';} ?>>
            <a href="index.php?option=com_ijoomla_seo&controller=ilinkscategory">
                <i class="js-icon-double-angle-right"></i>
                <?php echo JText::_("COM_IJOOMLA_SEO_KTWO_CAETGORY"); ?>
            </a>
        </li>

        <li <?php if($controller_req == "config" && $task=="keyword_linking"){ echo 'class="active"';} ?>>
            <a href="index.php?option=com_ijoomla_seo&controller=config&task2=keyword_linking">
                <i class="js-icon-double-angle-right"></i>
                <?php echo JText::_("COM_IJOOMLA_SEO_LM_SETTINGS"); ?>
            </a>
        </li>
    </ul>
</li>

<li>
    <a class="dropdown-toggle" href="#">
        <i class="icon-question-sign"></i>
        <span class="menu-text">  <?php echo JText::_("COM_IJOOMLA_SEO_DOCUMENTATION"); ?> </span>
        <b class="arrow js-icon-angle-down"></b>
    </a>

    <ul class="submenu">
        <li class="">
            <a href="http://www.ijoomla.com/redirect/seo/course.htm" target="_blank">
                <i class="js-icon-double-angle-right"></i>
                <?php echo JText::_("COM_IJOOMLA_SEO_GURU_COURSE"); ?>
            </a>
        </li>

        <li class="">
            <a href="http://www.ijoomla.com/" target="_blank">
                <i class="js-icon-double-angle-right"></i>
                <?php echo JText::_("COM_IJOOMLA_SEO_IJOOMLA_WEBSITE"); ?>
            </a>
        </li>

        <li class="">
            <a href="http://www.ijoomla.com/redirect/general/support.htm" target="_blank">
                <i class="js-icon-double-angle-right"></i>
                <?php echo JText::_("COM_IJOOMLA_SEO_SUPPORT_HELP"); ?>
            </a>
        </li>

        <li class="">
            <a href="http://www.ijoomla.com/redirect/seo/forum.htm" target="_blank">
                <i class="js-icon-double-angle-right"></i>
                <?php echo JText::_("COM_IJOOMLA_SEO_FORUMS"); ?>
            </a>
        </li>

        <li class="">
            <a href="http://www.ijoomla.com/redirect/general/templates.htm" target="_blank">
                <i class="js-icon-double-angle-right"></i>
                <?php echo JText::_("COM_IJOOMLA_SEO_TEMPLATES"); ?>
            </a>
        </li>

        <li class="">
            <a href="http://www.ijoomla.com/redirect/general/latestversion.htm" target="_blank">
                <i class="js-icon-double-angle-right"></i>
                <?php echo JText::_("COM_IJOOMLA_SEO_LATEST_VERSION"); ?>
            </a>
        </li>

        <li class="">
            <a href="http://www.ijoomla.com/redirect/general/othercomponents.htm" target="_blank">
                <i class="js-icon-double-angle-right"></i>
                <?php echo JText::_("COM_IJOOMLA_SEO_OTHER_COMPONENTS"); ?>
            </a>
        </li>
    </ul>
</li>

<li <?php echo $li_about; ?>>
    <a href="index.php?option=com_ijoomla_seo&controller=about">
        <i class="icon-star"></i>
        <?php echo JText::_("COM_IJOOMLA_SEO_ABOUT"); ?>
    </a>
</li>

</ul>

<div id="sidebar-collapse" class="sidebar-collapse">
    <i class="js-icon-double-angle-left"></i>
</div>

</div>