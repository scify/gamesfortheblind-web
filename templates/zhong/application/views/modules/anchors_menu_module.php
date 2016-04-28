<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

/*==========================================================================
   INTERNAL PAGE NAVIGATION
==========================================================================*/

//Printed only in Full access layout
if(ZHONGFRAMEWORK_LAYOUT_MODE=='full-access') : ?>

<!-- INTERNAL PAGE NAVIGATION -->
<nav role="navigation"><div id="anchors-menu-container">

	<?php //Print section heading
	printSectionHeading(ZHONGFRAMEWORK_LANGUAGE_INTERNAL_NAV_HEADING,2,$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['quickLinksMenu'],false,''); ?>

	<ul id="anchors-menu">
		<?php
			//Print the anchor for the "main content"
			echo '<li><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.'#main-content-container-inner" accesskey="'.ZHONGFRAMEWORK_LANGUAGE_ACCESSKEY_MAIN_CONTENT.'" class="internal-link" id="internal-link-content">'.ZHONGFRAMEWORK_LANGUAGE_INTERNAL_NAV_MAIN_CONTENT.'</a></li>';
			
			//Print the anchor for the "supplementary content (upper)"
			if(ZHONGFRAMEWORK_CUSTOM_MODULES_BLOCK_1_EXISTS) {
			echo '<li><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.'#complementary-content-upper" accesskey="'.ZHONGFRAMEWORK_LANGUAGE_ACCESSKEY_SUPPLEMENTARY_CONTENT_UPPER.'" class="internal-link" id="internal-link-content">'.ZHONGFRAMEWORK_LANGUAGE_INTERNAL_NAV_SUPPLEMENTARY_CONTENT_UPPER.'</a></li>'; }
			
			//Print the anchor for the "supplementary content (lower)"
			if(ZHONGFRAMEWORK_CUSTOM_MODULES_BLOCK_3_EXISTS) {
			echo '<li><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.'#complementary-content-lower" accesskey="'.ZHONGFRAMEWORK_LANGUAGE_ACCESSKEY_SUPPLEMENTARY_CONTENT_LOWER.'" class="internal-link" id="internal-link-content">'.ZHONGFRAMEWORK_LANGUAGE_INTERNAL_NAV_SUPPLEMENTARY_CONTENT_LOWER.'</a></li>'; }

			//Print the anchor for the "main menu" only if some element is present in "top-mod" module
			if(ZHONGFRAMEWORK_MAIN_MENU_MOD_EXISTS) {
				echo '<li><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.'#main-menu-container" accesskey="'.ZHONGFRAMEWORK_LANGUAGE_ACCESSKEY_MAIN_MENU.'" class="internal-link"  id="internal-link-topmenu">'.ZHONGFRAMEWORK_LANGUAGE_INTERNAL_NAV_MAIN_MENU.'</a></li>'; }

			//Print the anchor for the "side menu"  only if some element is present in "side-menu" module
			if(ZHONGFRAMEWORK_SIDE_MENU_EXISTS) {
				echo '<li><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.'#side-menu" accesskey="'.ZHONGFRAMEWORK_LANGUAGE_ACCESSKEY_SIDE_MENU.'" class="internal-link"  id="internal-link-mainmenu">'.ZHONGFRAMEWORK_LANGUAGE_INTERNAL_NAV_SIDE_MENU.'</a></li>';}

			//Print the anchor for the "left column" only if some element is present in "left-column" module
			if(ZHONGFRAMEWORK_LEFT_MOD_EXISTS) {
				echo '<li><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.'#left-additional" accesskey="'.ZHONGFRAMEWORK_LANGUAGE_ACCESSKEY_ADDITIONAL_RESOURCES_LEFT_COLUMN.'" class="internal-link" id="internal-link-left">'.ZHONGFRAMEWORK_LANGUAGE_INTERNAL_NAV_ADDITIONAL_RESOURCES_LEFT_COLUMN.'</a></li>'; }

			//Print the anchor for the "right column" only if some element is present in "right-column" module
			if(ZHONGFRAMEWORK_RIGHT_MOD_EXISTS) {
				echo '<li><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.'#right-column" accesskey="'.ZHONGFRAMEWORK_LANGUAGE_ACCESSKEY_ADDITIONAL_RESOURCES_RIGHT_COLUMN.'" class="internal-link" id="internal-link-right">'.ZHONGFRAMEWORK_LANGUAGE_INTERNAL_NAV_ADDITIONAL_RESOURCES_RIGHT_COLUMN.'</a></li>'; }

			//Print the anchor for the "website help link" only if some element is present in "access-sitemap-mod" module
			if(ZHONGFRAMEWORK_SUPPORT_MENU_MOD_EXISTS) {
				echo '<li><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.'#support-menu-outer" accesskey="'.ZHONGFRAMEWORK_LANGUAGE_ACCESSKEY_SUPPORT_MENU.'" id="internal-link-help" class="internal-link">'.ZHONGFRAMEWORK_LANGUAGE_INTERNAL_NAV_SUPPORT_MENU.'</a></li>'; }
		
			//Print the anchor for the "footer menu"
			if(ZHONGFRAMEWORK_FOOTER_MENU_MOD_EXISTS) {
			echo '<li><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.'#footer-menu" accesskey="'.ZHONGFRAMEWORK_LANGUAGE_ACCESSKEY_FOOTER_MENU.'" class="internal-link" id="internal-link-footermenu">'.ZHONGFRAMEWORK_LANGUAGE_INTERNAL_NAV_FOOTER_MENU.'</a></li>'; }

			//Print the anchor for the "footer"
			if(ZHONGFRAMEWORK_FOOTER_EXISTS) {
			echo '<li><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.'#footer-wrapper" accesskey="'.ZHONGFRAMEWORK_LANGUAGE_ACCESSKEY_FOOTER.'" class="internal-link" id="internal-link-footer">'.ZHONGFRAMEWORK_LANGUAGE_INTERNAL_NAV_FOOTER.'</a></li>'; }
			
			//Print the anchor for the "search" only if some element is present in "search" module
			if(ZHONGFRAMEWORK_SEARCH_MOD_EXISTS) {
				echo '<li><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.'#search-module-inner" accesskey="'.ZHONGFRAMEWORK_LANGUAGE_ACCESSKEY_SEARCH.'" class="internal-link" id="internal-link-search">'.ZHONGFRAMEWORK_LANGUAGE_INTERNAL_NAV_SEARCH.'</a></li>';}

			//Print the anchor for the "login" only if some element is present in "login" module
			if(ZHONGFRAMEWORK_LOGIN_MOD_EXISTS) {
				echo '<li><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.'#login-module" accesskey="'.ZHONGFRAMEWORK_LANGUAGE_ACCESSKEY_LOGIN.'" class="internal-link" id="internal-link-login">'.ZHONGFRAMEWORK_LANGUAGE_INTERNAL_NAV_LOGIN.'</a></li>'; }
			
			//Print the anchor for the "language switcher" only if some element is present in "language-switcher" module
			if(ZHONGFRAMEWORK_LANGUAGE_SWITCHER_MOD_EXISTS) {
				echo '<li><a href="'.ZHONGFRAMEWORK_WEBSITE_CURRENT_URI_WITH_PARAMETERS.'#language-switcher-inner" accesskey="'.ZHONGFRAMEWORK_LANGUAGE_ACCESSKEY_LANGUAGE_OPTIONS.'" class="internal-link" id="internal-link-language">'.ZHONGFRAMEWORK_LANGUAGE_INTERNAL_NAV_LANGUAGE_OPTIONS.'</a></li>'; }

		?>
	</ul>
</div></nav>
<hr class="removed"/>
<!-- END internal page navigation -->

<?php endif; ?>
