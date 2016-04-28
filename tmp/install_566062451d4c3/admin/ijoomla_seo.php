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

jimport('joomla.application.component.controller');
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

$controller_request = JRequest::getVar("controller", "");
$task = JRequest::getVar("task", "");

// Require the base controller
require_once( JPATH_COMPONENT.DS.'controller.php' );
// Require specific controller if requested
$pattern = '/^[A-Za-z]*$/';
if(preg_match($pattern,JRequest::getVar('controller'))){	
	$controller = JRequest::getVar('controller');	
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';	
	if(file_exists($path)){
		require_once $path;
	}
	else{
		$controller = '';
	}
}

// Create the controller
if($task == "change_sticky" || $task == "stats" || $task == "article_preview" || $task == "get_Grank" || $task == "change" || $task == "changeMenuItems"){
	$classname = "iJoomla_SeoController".$controller;
	if(class_exists($classname)){
		$controller = new $classname();
		$controller->execute($task);
		$controller->redirect();
	}
}
else{
?>

	<div id="js-cpanel">
		<?php 
        if($controller_request == "pages" && $task == "edit_page"){
            // do nothing
        }
        elseif($controller_request != "about" && $task != "vimeo"){
            include(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."left.php");
        }
        
        // Create the controller
        $classname	= 'iJoomla_SeoController'.$controller;
        $task = JRequest::getVar('task');
        $controller	= new $classname();
        
        ?>
        
        <div id="admin_content_wrapper">
            <?php
    
            $controller_request = JRequest::getVar("controller", "");
            if($task == "edit_page"){
                // do nothing
            }
            elseif($controller_request == "about" && ($task == "vimeo" || $task == "youtube")){
                // do nothing
            }
            else{
                require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'seosidebar.php');
            }
            
            ?>	
            <div class="main-content">
                <div class="page-content">
                    <?php
                        if($controller_request != "pages" && $task != "edit_page"){
                    ?>
                            <div class="page-header clearfix no-padding">
                                <?php
                                    $pageTitle = "";
                                    $image_pub_top = '<a href="http://www.ijoomla.com" target="_blank"><img src="components/com_ijoomla_seo/images/ijoomla-logo.png"></a>';
                                    $controller_request = JRequest::getVar("controller", "");
                                    $layout = JRequest::getVar("task2", "");
                                    
                                    if($controller_request == "config" && $layout == "general"){
                                        $pageTitle = JText::_("COM_IJOOMLA_SEO_CONFIG")." > ".JText::_("COM_IJOOMLA_SEO_GENERAL");
                                        $image_pub_top = '';
                                    }
                                    elseif($controller_request == "config" && $layout == "track_keywords"){
                                        $pageTitle = JText::_("COM_IJOOMLA_SEO_CONFIG")." > ".JText::_("COM_IJOOMLA_SEO_KEYWORDS_KEY");
                                        $image_pub_top = '';
                                    }
                                    elseif($controller_request == "config" && $layout == "google_ping"){
                                        $pageTitle = JText::_("COM_IJOOMLA_SEO_CONFIG")." > ".JText::_("COM_IJOOMLA_SEO_GOOGLE_PING");
                                        $image_pub_top = '';
                                    }
                                    elseif($controller_request == "config" && $layout == "manage_meta"){
                                        $pageTitle = JText::_("COM_IJOOMLA_SEO_CONFIG")." > ".JText::_("COM_IJOOMLA_SEO_METATAGS_META");
                                        $image_pub_top = '';
                                    }
                                    elseif($controller_request == "config" && $layout == "keyword_linking"){
                                        $pageTitle = JText::_("COM_IJOOMLA_SEO_CONFIG")." > ".JText::_("COM_IJOOMLA_SEO_INTERNAL_LINKS");
                                        $image_pub_top = '';
                                    }
                                    elseif($controller_request == "language"){
                                        $pageTitle = JText::_("COM_IJOOMLA_SEO_CONFIG")." > ".JText::_("COM_IJOOMLA_SEO_LANGUAGES");
                                        $image_pub_top = '';
                                    }
                                    elseif($controller_request != ""){
                                        $image_pub_top = "";
                                    }
                                    elseif($controller_request == ""){
                                        $pageTitle = JText::_("PAGE_DASHBOARD_HEAD");
                                    }
                                ?>
                                <div id="btn_divider" class="clearfix"></div>
                                <h2 class="pull-left"><?php echo $pageTitle; ?></h2>
                                <div class="pull-right"><?php echo $image_pub_top; ?></div>
                            </div>
                    <?php
                        }
                    ?>
    
                        <?php 
                        $controller->execute(JRequest::getVar('task', ""));
                        $controller->redirect();
                        ?>
                </div>
              
                <script>
                    // move the Joomla button toolbar to the layout
                    // move cutom button on Meta Tags Manager
                    jQuery("#toolbar-share").addClass("pull-right no-margin").prependTo(".page-header");
                    jQuery("#toolbar-arrow-right").addClass("pull-right no-margin").prependTo(".page-header");
                    jQuery("#toolbar-forward").addClass("pull-right no-margin").prependTo(".page-header");
                    jQuery("#toolbar-share-alt").addClass("pull-right no-margin").prependTo(".page-header");
                    jQuery("#toolbar-repeat").addClass("pull-right no-margin").prependTo(".page-header");
                    
                    jQuery("#btn_divider").insertBefore("#toolbar-share-alt");
                    jQuery("#btn_divider").insertBefore("#toolbar-repeat");
                        
                    
                     jQuery("#toolbar").addClass("pull-right no-margin").prependTo(".page-header");
    
                                        
                    // move the page title
                    jQuery(".pub-page-title").addClass("pull-left no-margin").prependTo(".page-header");
                    // Apply class to doc buttons 
                    //jQuery('ul.nav.ace-nav.pull-right > li > a.dropdown-toggle').dropdown();
                </script>
                
            </div>
        </div>
    </div>
<?php
}
?>