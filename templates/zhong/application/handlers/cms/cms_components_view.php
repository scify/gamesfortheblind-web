<?php
/**
 * @package   ZhongFramework - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

class ParentCMSHandler{
	
	//Define the constructor
	public function __construct(){}

	/**
	 * PRINT FUNCTIONS 
	**/
	
	public function printHead(){
		//If Joomla3,
		if(ZHONGFRAMEWORK_PARENT_CMS_RELEASE_CLASS=="Joomla3"){
			//Import twitter bootstrap
			JHtml::_('bootstrap.framework');
			// Load optional RTL Bootstrap CSS?
			//JHtml::_('bootstrap.loadCss', false, ZHONGFRAMEWORK_WEBSITE_TEXT_DIRECTION);
			}
		//Include Joomla head
		echo '<jdoc:include type="head" />';
		}
	
	public function printDebugModule(){
		echo '<jdoc:include type="modules" name="debug" style="xhtml" />';
		}
	
	public function printAccessibilityPanelCustomModule(){
		echo '<jdoc:include type="modules" name="accessibility-panel" />';
		}
	
	public function printBreadcrumbsModule(){
		echo '<jdoc:include type="modules" name="breadcrumbs" />';
		}
	
	public function printHeaderMenuModule(){
		echo '<jdoc:include type="modules" name="support-menu" />';
		}
	
	public function printMainDocumentContent(){
		echo '<jdoc:include type="component" style="xhtml" />';
		}
	
	public function printFooterCreditsModule(){
		echo '<jdoc:include type="modules" name="footer-credits" />';
		}
	
	public function printTopMenuModule(){
		echo '<jdoc:include type="modules" name="main-menu" />';
		}
	
	public function printLeftColumnModule(){
		echo '<jdoc:include type="modules" name="left-column" style="xhtml" />';
		}
	
	public function printLoginModule(){
		echo '<jdoc:include type="modules" name="login" style="xhtml" />';
		}
	
	public function printPreDocumentContent(){
		echo '<jdoc:include type="message" style="xhtml" />';
		}
	
	public function printLanguageSwitcherModule(){
		echo '<jdoc:include type="modules" name="language-switcher" />';
		}
	
	public function printRightColumnModule(){
		echo '<jdoc:include type="modules" name="right-column" style="xhtml" />';
		}
	
	public function printSearchModule(){
		echo '<jdoc:include type="modules" name="search" />';
		}
	
	public function printFooterMenuModule(){
		echo '<jdoc:include type="modules" name="footer-menu" />';
		}
	
	public function printMainMenuModule(){
		echo '<jdoc:include type="modules" name="side-menu" style="xhtml" />';
		}
	
	public function printCustomUserModule($layoutBlock,$logicalWidth,$position){
		echo '<jdoc:include type="modules" name="custom-'.$layoutBlock.'-'.$logicalWidth.$position.'" style="xhtml"/>';
		}
	}
	//END Class

?>
