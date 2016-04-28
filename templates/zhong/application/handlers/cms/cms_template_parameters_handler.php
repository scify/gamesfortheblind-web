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
   TEMPLATE PARAMETERS HANDLER
   (Get the parameters from the Joomla backend)
==========================================================================*/

/*----------------------------------------------------------------
-  BASIC OPTIONS
---------------------------------------------------------------- */
define("ZHONGFRAMEWORK_PARAMETER_TITLE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_title"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_SHOW_TITLE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_showTitle"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_SUBTITLE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_subtitle"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_SHOW_SUBTITLE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_showSubtitle"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_SHOW_LOGO",
	htmlspecialchars($this->params->get("zhongframework_Jparam_showLogo"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_LOGO_PATH",
	htmlspecialchars($this->params->get("zhongframework_Jparam_logoPath"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_LOGO_HEIGHT",
	htmlspecialchars($this->params->get("zhongframework_Jparam_logoHeight"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_LOGO_WIDTH",
	htmlspecialchars($this->params->get("zhongframework_Jparam_logoWidth"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ALT_LOGO",
	htmlspecialchars($this->params->get("zhongframework_Jparam_altLogo"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_LOGO_LINK",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_logoLink"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_PRESENTATION_ALIGNMENT",
	htmlspecialchars($this->params->get("zhongframework_Jparam_presentationAlignment"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_WEBSITE_FAVICON",
	htmlspecialchars($this->params->get("zhongframework_Jparam_websiteFavicon"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_LAYOUT_WIDTH_MODE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_fullWidth"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_FIXED_LAYOUT_WIDTH",
	htmlspecialchars($this->params->get("zhongframework_Jparam_fixedLayoutWidth"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_LIQUID_LAYOUT_WIDTH",
	htmlspecialchars($this->params->get("zhongframework_Jparam_liquidLayoutWidth"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MIN_LIQUID_LAYOUT_WIDTH",
	htmlspecialchars($this->params->get("zhongframework_Jparam_minLayoutWidth"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAX_LIQUID_LAYOUT_WIDTH",
	htmlspecialchars($this->params->get("zhongframework_Jparam_maxLayoutWidth"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_USER_LAYOUT_WIDTH_RESIZE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_user_layout_width_resize"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_LAYOUT_COLUMN_ORDER",
	htmlspecialchars($this->params->get("zhongframework_Jparam_columnOrder"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_FONT_SIZE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_fontSize"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ACCESSIBILITY_BUTTON_STYLE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_accessButtonStyle"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_WEBSITE_TITLE_FONT_SIZE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customFontSize_title'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_TITLE_FONT_SIZE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customFontSize_title'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_WEBSITE_SUBTITLE_FONT_SIZE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customFontSize_subtitle'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_SUBTITLE_FONT_SIZE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customFontSize_subtitle'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_COLUMNS_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customWidth_leftRightColumn'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_LEFT_COLUMN_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customWidth_leftColumn'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_RIGHT_COLUMN_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customWidth_rightColumn'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_TOP_BUTTON_DEFAULT_LAYOUT",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_top_button_default_layout"),ENT_QUOTES));

define("ZHONGFRAMEWORK_PARAMETER_ENABLE_MOBILE_LINK",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_mobile_link"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_NIGHT_LINK",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_night_mode_link"),ENT_QUOTES));


/*----------------------------------------------------------------
-  MENU STYLE
---------------------------------------------------------------- */
define("ZHONGFRAMEWORK_PARAMETER_MAIN_MENU_STYLE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_main_menu_style"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_MAIN_MENU_ALIGNMENT",
	htmlspecialchars($this->params->get("zhongframework_Jparam_main_menu_alignment"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_SIDE_MENU_STYLE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_side_menu_style"),ENT_QUOTES));

/*----------------------------------------------------------------
-  ACCESSIBILITY PANEL OPTIONS
---------------------------------------------------------------- */
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_TOP_BAR",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_access_bar"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_FIXED_TOP_BAR",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_fixed_access_bar"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_ACCESSIBILITY_PANEL",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_access_panel"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_GRAPHIC_MODES_MENU",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_graphicMode_menu"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_BEST_LEGIBILITY_MODE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_best_legibility"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_LAYOUT_MODES_MENU",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_layout_modes_menu"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_FULL_ACCESS_LAYOUT",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_full-access_mode"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_HIGH_VISIBILITY_LAYOUT",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_high_contrast"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_FONT_RESIZER",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_font_resize"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_TOGGLE_LAYOUT_WIDTH",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_toggle_layout"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_LIQUID_LAYOUT_WIDTH",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_liquid_layout"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_FULL_LAYOUT_WIDTH",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_full_layout"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_FIXED_LAYOUT_WIDTH",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_fixed_layout"),ENT_QUOTES));

/*----------------------------------------------------------------
-  ACCESSIBILITY OPTIONS
---------------------------------------------------------------- */
define("ZHONGFRAMEWORK_PARAMETER_HEADINGS_LEVEL_MODE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_headingsLevel'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_HIDDEN_HEADINGS_DEFAULT_LAYOUT",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enableHiddenHeadingsDefaultLayout'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_SCREENREADER_HIDDEN_MESSAGE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_show_message_screenReaderUsers'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_MENUS_NAVIGATION_MODE_FULL_ACCESS",
	htmlspecialchars($this->params->get('zhongframework_Jparam_menusMode_fullAccess'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_MENUS_NAVIGATION_MODE_HIGH_CONTRAST",
	htmlspecialchars($this->params->get('zhongframework_Jparam_menusMode_highContrast'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_SHOW_LOGO_HIGH_VISIBILITY",
	htmlspecialchars($this->params->get("zhongframework_Jparam_showLogo_highVisibility"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_LOGO_PATH_HIGH_VISIBILITY",
	htmlspecialchars($this->params->get("zhongframework_Jparam_logoPath_highVisibility"),ENT_QUOTES));


/*----------------------------------------------------------------
-  MOBILE OPTIONS
---------------------------------------------------------------- */
define("ZHONGFRAMEWORK_PARAMETER_DEFAULT_FONT_SIZE_MOBILE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_fontSize_mobile'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_MENUS_NAVIGATION_MODE_MOBILE_LAYOUT",
	htmlspecialchars($this->params->get('zhongframework_Jparam_mobile_menu_mode'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_NIGHT_LINK_MOBILE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_night_mode_mobile"),ENT_QUOTES));


/*----------------------------------------------------------------
-  CUSTOM STYLE & COLORS
---------------------------------------------------------------- */
//General
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_BODY_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_background'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_BODY_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_background'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_TEXT_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_text'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TEXT_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_text'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_HEADINGS_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_headings'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_HEADINGS_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_headings'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_LINKS_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_links'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_LINKS_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_links'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_VISITED_LINKS_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_visitedLinks'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_HOVER_LINKS_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_hoverLinks'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_BUTTONS", //Buttons
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_buttons'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_BUTTONS_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_buttons_BG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_BUTTONS_BG_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_buttons_BG_hover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_BUTTONS_TEXT",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_buttons_Text'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_BUTTONS_TEXT_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_buttons_Text_hover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_BUTTONS_BORDER_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_buttons_BorderColor'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_BUTTONS_BORDER_COLOR_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_buttons_BorderColor_hover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_BUTTONS_BORDER_STYLE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_buttons_BorderStyle'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_BUTTONS_BORDER_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_buttons_BorderWidth'),ENT_QUOTES));


//Main layout
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_WEBSITE_TITLE_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_title'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_TITLE_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_title'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_WEBSITE_SUBTITLE_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_subtitle'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_SUBTITLE_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_subtitle'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_LAYOUT_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_layoutBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_LAYOUT_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_layoutBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_LAYOUT_BORDER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_layoutBorder'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_LAYOUT_BORDER_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_layoutBorderColor'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_LAYOUT_BORDER_STYLE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_layoutBorderStyle'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_LAYOUT_BORDER_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_layoutBorderWidth'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_WEBSITE_HEADER_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_headerBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_HEADER_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_headerBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_HEADER_BORDER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_headerBorder'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_HEADER_BORDER_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_headerBorderColor'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_HEADER_BORDER_STYLE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_headerBorderStyle'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_HEADER_BORDER_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_headerBorderWidth'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_BODY_BG", //Main body
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_mainBodyBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_BODY_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainBodyBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_BODY_BORDER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_mainBodyBorder'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_BODY_BORDER_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainBodyBorderColor'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_BODY_BORDER_STYLE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainBodyBorderStyle'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_BODY_BORDER_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainBodyBorderWidth'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_CONTENT_CONTAINER_BG", //Main Content Container
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_mainContentContainerBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_CONTENT_CONTAINER_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainContentContainerBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_CONTENT_CONTAINER_BORDER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_mainContentContainerBorder'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_CONTENT_CONTAINER_BORDER_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainContentContainerBorderColor'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_CONTENT_CONTAINER_BORDER_STYLE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainContentContainerBorderStyle'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_CONTENT_CONTAINER_BORDER_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainContentContainerBorderWidth'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_TOP_BUTTON", //Top button
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_topButton'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BUTTON_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_topButton_BG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BUTTON_BG_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_topButton_BG_hover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BUTTON_TEXT",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_topButton_Text'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BUTTON_TEXT_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_topButton_Text_hover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BUTTON_BORDER_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_topButton_BorderColor'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BUTTON_BORDER_COLOR_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_topButton_BorderColor_hover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BUTTON_BORDER_STYLE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_topButton_BorderStyle'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BUTTON_BORDER_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_topButton_BorderWidth'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_WEBSITE_FOOTER_BG", //Footer
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_footerBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_FOOTER_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_FOOTER_BORDER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_footerBorder'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_BORDER_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerBorderColor'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_BORDER_STYLE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerBorderStyle'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_BORDER_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerBorderWidth'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_WEBSITE_FOOTER_TEXT_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_footerText'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_FOOTER_TEXT_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerText'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_WEBSITE_FOOTER_HEADERS_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_footerHeaders'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_FOOTER_HEADERS_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerHeaders'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_WEBSITE_FOOTER_LINKS_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_footerLinks'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_FOOTER_LINKS_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerLinks'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_FOOTER_VISITED_LINKS_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerVisitedLinks'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_FOOTER_HOVER_LINKS_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerHoverLinks'),ENT_QUOTES));

//Top bar
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_TOP_BAR_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_topBarBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BAR_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_topBarBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_TOP_BAR_BORDER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_topBarBorder'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BAR_BORDER_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_topBarBorderColor'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BAR_BORDER_STYLE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_topBarBorderStyle'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BAR_BORDER_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_topBarBorderWidth'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_TOP_BAR_FONT",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_topBarFontColor'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BAR_FONT_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_topBarFontColor'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_TOP_BAR_LINKS",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_topBarLinksColor'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BAR_LINKS_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_topBarLinksColor'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BAR_LINKS_COLOR_VISITED",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_topBarLinksColorVisited'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BAR_LINKS_COLOR_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_topBarLinksColorHover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_ACCESSIBILITY_PANEL_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_accessibilityPanelBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ACCESSIBILITY_PANEL_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_accessibilityPanelBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_ACCESSIBILITY_PANEL_BORDER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_accessibilityPanelBorder'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ACCESSIBILITY_PANEL_BORDER_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_accessibilityPanelBorderColor'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ACCESSIBILITY_PANEL_BORDER_STYLE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_accessibilityPanelBorderStyle'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ACCESSIBILITY_PANEL_BORDER_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_accessibilityPanelBorderWidth'),ENT_QUOTES));
	
//Main menu
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_MENU_CONTAINER_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_mainMenu_containerBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_CONTAINER_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_containerBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_MENU_CONTAINER_BORDER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_mainMenu_containerBorder'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_CONTAINER_BORDER_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_containerBorderColor'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_CONTAINER_BORDER_STYLE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_containerBorderStyle'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_CONTAINER_BORDER_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_containerBorderWidth'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_MENU_LINKS_1_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_mainMenu_linksText1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_1_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_linksText1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_1_COLOR_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_linksText1Hover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_MENU_LINKS_1_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_mainMenu_linksBG1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_1_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_linksBG1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_1_BG_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_linksBG1Hover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_MENU_LINKS_1_BORDER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_mainMenu_linksBorder1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_1_BORDER_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_linksBorder1Color'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_1_BORDER_COLOR_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_linksBorder1ColorHover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_1_BORDER_STYLE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_linksBorder1Style'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_1_BORDER_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_linksBorder1Width'),ENT_QUOTES));
//Main menu (sub-links)
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_MENU_LINKS_SUB_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_mainMenu_linksTextSub'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_SUB_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_linksTextSub'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_SUB_COLOR_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_linksTextSubHover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_MENU_LINKS_SUB_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_mainMenu_linksBGSub'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_SUB_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_linksBGSub'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_SUB_BG_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_linksBGSubHover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_MENU_LINKS_SUB_BORDER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_mainMenu_linksBorderSub'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_SUB_BORDER_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_linksBorderSubColor'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_SUB_BORDER_COLOR_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_linksBorderSubColorHover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_SUB_BORDER_STYLE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_linksBorderSubStyle'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_SUB_BORDER_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_mainMenu_linksBorderSubWidth'),ENT_QUOTES));

//Side menus
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SIDE_MENUS_LINKS_1_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_sideMenus_linksText1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_1_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_sideMenus_linksText1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_1_COLOR_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_sideMenus_linksText1Hover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SIDE_MENUS_LINKS_1_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_sideMenus_linksBG1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_1_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_sideMenus_linksBG1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_1_BG_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_sideMenus_linksBG1Hover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SIDE_MENUS_LINKS_1_BORDER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_sideMenus_linksBorder1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_1_BORDER_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_sideMenus_linksBorder1Color'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_1_BORDER_COLOR_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_sideMenus_linksBorder1ColorHover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_1_BORDER_STYLE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_sideMenus_linksBorder1Style'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_1_BORDER_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_sideMenus_linksBorder1Width'),ENT_QUOTES));
//Side menus (sub-links)
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SIDE_MENUS_LINKS_SUB_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_sideMenus_linksTextSub'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_SUB_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_sideMenus_linksTextSub'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_SUB_COLOR_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_sideMenus_linksTextSubHover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SIDE_MENUS_LINKS_SUB_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_sideMenus_linksBGSub'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_SUB_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_sideMenus_linksBGSub'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_SUB_BG_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_sideMenus_linksBGSubHover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SIDE_MENUS_LINKS_SUB_BORDER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_sideMenus_linksBorderSub'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_SUB_BORDER_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_sideMenus_linksBorderSubColor'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_SUB_BORDER_COLOR_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_sideMenus_linksBorderSubColorHover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_SUB_BORDER_STYLE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_sideMenus_linksBorderSubStyle'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_SUB_BORDER_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_sideMenus_linksBorderSubWidth'),ENT_QUOTES));

//Side menu
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SUPPORT_MENU_CONTAINER_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_supportMenu_containerBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_CONTAINER_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_supportMenu_containerBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SUPPORT_MENU_CONTAINER_BORDER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_supportMenu_containerBorder'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_CONTAINER_BORDER_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_supportMenu_containerBorderColor'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_CONTAINER_BORDER_STYLE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_supportMenu_containerBorderStyle'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_CONTAINER_BORDER_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_supportMenu_containerBorderWidth'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SUPPORT_MENU_LINKS_1_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_supportMenu_linksText1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_LINKS_1_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_supportMenu_linksText1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_LINKS_1_COLOR_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_supportMenu_linksText1Hover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SUPPORT_MENU_LINKS_1_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_supportMenu_linksBG1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_LINKS_1_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_supportMenu_linksBG1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_LINKS_1_BG_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_supportMenu_linksBG1Hover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SUPPORT_MENU_LINKS_1_BORDER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_supportMenu_linksBorder1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_LINKS_1_BORDER_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_supportMenu_linksBorder1Color'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_LINKS_1_BORDER_COLOR_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_supportMenu_linksBorder1ColorHover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_LINKS_1_BORDER_STYLE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_supportMenu_linksBorder1Style'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_LINKS_1_BORDER_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_supportMenu_linksBorder1Width'),ENT_QUOTES));

//Footer menu
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_FOOTER_MENU_CONTAINER_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_footerMenu_containerBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_CONTAINER_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerMenu_containerBG'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_FOOTER_MENU_CONTAINER_BORDER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_footerMenu_containerBorder'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_CONTAINER_BORDER_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerMenu_containerBorderColor'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_CONTAINER_BORDER_STYLE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerMenu_containerBorderStyle'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_CONTAINER_BORDER_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerMenu_containerBorderWidth'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_FOOTER_MENU_LINKS_1_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_footerMenu_linksText1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_LINKS_1_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerMenu_linksText1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_LINKS_1_COLOR_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerMenu_linksText1Hover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_FOOTER_MENU_LINKS_1_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_footerMenu_linksBG1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_LINKS_1_BG",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerMenu_linksBG1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_LINKS_1_BG_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerMenu_linksBG1Hover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_FOOTER_MENU_LINKS_1_BORDER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_customColor_footerMenu_linksBorder1'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_LINKS_1_BORDER_COLOR",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerMenu_linksBorder1Color'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_LINKS_1_BORDER_COLOR_HOVER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerMenu_linksBorder1ColorHover'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_LINKS_1_BORDER_STYLE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerMenu_linksBorder1Style'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_LINKS_1_BORDER_WIDTH",
	htmlspecialchars($this->params->get('zhongframework_Jparam_customColor_footerMenu_linksBorder1Width'),ENT_QUOTES));



/*----------------------------------------------------------------
-  CUSTOM FONTS
---------------------------------------------------------------- */
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_GOOGLE_IMPORT_URL_1",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customFonts_imported1_googleFontURL"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_GOOGLE_IMPORT_URL_2",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customFonts_imported2_googleFontURL"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_GOOGLE_IMPORT_URL_3",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customFonts_imported3_googleFontURL"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_GOOGLE_IMPORT_URL_4",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customFonts_imported4_googleFontURL"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_GENERAL",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customFonts_general"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_FAMILY_GENERAL",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customFonts_general_fontFamily"),ENT_NOQUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_HEADING",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customFonts_heading"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_FAMILY_HEADING",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customFonts_heading_fontFamily"),ENT_NOQUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_WEBSITE_TITLE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customFonts_websiteTitle"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_FAMILY_WEBSITE_TITLE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customFonts_websiteTitle_fontFamily"),ENT_NOQUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_WEBSITE_SUBTITLE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customFonts_websiteSubtitle"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_FAMILY_WEBSITE_SUBTITLE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customFonts_websiteSubtitle_fontFamily"),ENT_NOQUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_MAIN_MENU",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customFonts_mainMenu"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_FAMILY_MAIN_MENU",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customFonts_mainMenu_fontFamily"),ENT_NOQUOTES));


/*----------------------------------------------------------------
-  ADVANCED OPTIONS
---------------------------------------------------------------- */
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_JAVASCRIPT_DEBUGGER",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_javascript_debugger"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_PREVENT_JAVASCRIPT_ERRORS_IE_ALERT",
	htmlspecialchars($this->params->get("zhongframework_Jparam_prevent_javascript_errors_IE_alert"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_PHP_DEBUGGER",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_php_debugger"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_JQUERY_LIBRARY",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_jquery_library"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_MODERNIZR_LIBRARY",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_modernizr_library"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_MINIFY_METHODS",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_minify_methods"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_SHOW_MESSAGE_IEUSERS",
	htmlspecialchars($this->params->get("zhongframework_Jparam_show_message_IEusers"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_SHOW_WELCOME_MESSAGE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_show_access_button_message"),ENT_QUOTES));

define("ZHONGFRAMEWORK_PARAMETER_HEADER_MOBILE_STYLE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_header_mobile_style"),ENT_QUOTES));

define("ZHONGFRAMEWORK_PARAMETER_GLOBAL_LAYOUT_WIDTH_TYPE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_global_layout_width_type"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_INLINE_CSS",
	htmlspecialchars($this->params->get("zhongframework_Jparam_custom_user_inlineCSS"),ENT_NOQUOTES));


/*----------------------------------------------------------------
-  OTHER OPTIONS
---------------------------------------------------------------- */
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_JAVASCRIPT_TOOLTIPS",
	htmlspecialchars($this->params->get('zhongframework_Jparam_enable_JStooltips'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_PRINT_FONT_TYPE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_print_font_type'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_PRINT_FONT_SIZE",
	htmlspecialchars($this->params->get('zhongframework_Jparam_print_font_size'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_PRINT_SHOW_HEADER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_print_show_header'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_PRINT_SHOW_LOGO",
	htmlspecialchars($this->params->get('zhongframework_Jparam_print_show_logo'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_PRINT_SHOW_BREADCRUMBS",
	htmlspecialchars($this->params->get('zhongframework_Jparam_print_show_breadcrumb'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_PRINT_SHOW_FOOTER",
	htmlspecialchars($this->params->get('zhongframework_Jparam_print_show_footer'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_PRINT_IMAGES",
	htmlspecialchars($this->params->get('zhongframework_Jparam_print_images'),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_GOOGLE_ANALYTICS",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_googleAnalytics"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_GOOGLE_ANALYTICS_ID",
	htmlspecialchars($this->params->get("zhongframework_Jparam_googleAnalytics_ID"),ENT_QUOTES));

/*----------------------------------------------------------------
-  Custom module style
---------------------------------------------------------------- */
define("ZHONGFRAMEWORK_PARAMETER_SAME_HEIGHT_CUSTOM_MODULES",
	htmlspecialchars($this->params->get("zhongframework_Jparam_sameHeight_customModules"),ENT_QUOTES));
//User modules
$ZHONGFRAMEWORK_PARAMETER_USERMODULES_STYLE = array(
	1 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_1'),ENT_QUOTES),
	2 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_2'),ENT_QUOTES),
	3 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_3'),ENT_QUOTES),
	4 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_4'),ENT_QUOTES),
	5 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_5'),ENT_QUOTES),
	6 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_6'),ENT_QUOTES),
	7 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_7'),ENT_QUOTES),
	8 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_8'),ENT_QUOTES),
	9 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_9'),ENT_QUOTES),
	10 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_10'),ENT_QUOTES),
	11 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_11'),ENT_QUOTES),
	12 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_12'),ENT_QUOTES),
	13 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_13'),ENT_QUOTES),
	14 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_14'),ENT_QUOTES),
	15 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_15'),ENT_QUOTES),
	16 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_16'),ENT_QUOTES),
	17 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_17'),ENT_QUOTES),
	18 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_18'),ENT_QUOTES),
	19 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_19'),ENT_QUOTES),
	20 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_20'),ENT_QUOTES),
	21 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_21'),ENT_QUOTES),
	22 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_22'),ENT_QUOTES),
	23 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_23'),ENT_QUOTES),
	24 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_24'),ENT_QUOTES),
	25 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_25'),ENT_QUOTES),
	26 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_26'),ENT_QUOTES),
	27 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_27'),ENT_QUOTES),
	28 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_28'),ENT_QUOTES),
	29 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_29'),ENT_QUOTES),
	30 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_30'),ENT_QUOTES),
	31 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_31'),ENT_QUOTES),
	32 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_32'),ENT_QUOTES),
	33 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_33'),ENT_QUOTES),
	34 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_34'),ENT_QUOTES),
	35 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_35'),ENT_QUOTES),
	36 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_36'),ENT_QUOTES),
	37 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_37'),ENT_QUOTES),
	38 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_38'),ENT_QUOTES),
	39 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_39'),ENT_QUOTES),
	40 => htmlspecialchars($this->params->get('zhongframework_Jparam_userModuleStyle_40'),ENT_QUOTES)
	);

//Important modules
$ZHONGFRAMEWORK_PARAMETER_MAINMODULES_STYLE = array(
	"side-menu" => htmlspecialchars($this->params->get('zhongframework_Jparam_moduleStyle_mainMenu'),ENT_QUOTES),
	"login" => htmlspecialchars($this->params->get('zhongframework_Jparam_moduleStyle_login'),ENT_QUOTES),
	"left-column" => htmlspecialchars($this->params->get('zhongframework_Jparam_moduleStyle_left'),ENT_QUOTES),
	"right-column" => htmlspecialchars($this->params->get('zhongframework_Jparam_moduleStyle_right'),ENT_QUOTES)
	);


//User custom module style (1)
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_1_BG",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_1_BG"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_1_BORDER_COLOR",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_1_borderColor"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_1_BORDER_STYLE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_1_borderStyle"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_1_BORDER_WIDTH",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_1_borderWidth"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_1_MARGIN",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_1_margin"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_1_PADDING",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_1_padding"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_CUSTOM_USER_MODULE_STYLE_1_TEXT_COLOR",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_customUserModuleStyle_1_textColor"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_1_TEXT_COLOR",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_1_textColor"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_CUSTOM_USER_MODULE_STYLE_1_LINKS_COLOR",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_customUserModuleStyle_1_linkColor"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_1_LINKS_COLOR",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_1_linkColor"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_1_LINKS_COLOR_HOVER",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_1_linkColorHover"),ENT_QUOTES));
//User custom module style (2)
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_2_BG",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_2_BG"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_2_BORDER_COLOR",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_2_borderColor"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_2_BORDER_STYLE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_2_borderStyle"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_2_BORDER_WIDTH",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_2_borderWidth"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_2_MARGIN",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_2_margin"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_2_PADDING",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_2_padding"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_CUSTOM_USER_MODULE_STYLE_2_TEXT_COLOR",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_customUserModuleStyle_2_textColor"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_2_TEXT_COLOR",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_2_textColor"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_CUSTOM_USER_MODULE_STYLE_2_LINKS_COLOR",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_customUserModuleStyle_2_linkColor"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_2_LINKS_COLOR",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_2_linkColor"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_2_LINKS_COLOR_HOVER",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_2_linkColorHover"),ENT_QUOTES));
//User custom module style (3)
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_3_BG",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_3_BG"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_3_BORDER_COLOR",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_3_borderColor"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_3_BORDER_STYLE",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_3_borderStyle"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_3_BORDER_WIDTH",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_3_borderWidth"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_3_MARGIN",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_3_margin"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_3_PADDING",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_3_padding"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_CUSTOM_USER_MODULE_STYLE_3_TEXT_COLOR",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_customUserModuleStyle_3_textColor"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_3_TEXT_COLOR",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_3_textColor"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_ENABLE_CUSTOM_USER_MODULE_STYLE_3_LINKS_COLOR",
	htmlspecialchars($this->params->get("zhongframework_Jparam_enable_customUserModuleStyle_3_linkColor"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_3_LINKS_COLOR",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_3_linkColor"),ENT_QUOTES));
define("ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_3_LINKS_COLOR_HOVER",
	htmlspecialchars($this->params->get("zhongframework_Jparam_customUserModuleStyle_3_linkColorHover"),ENT_QUOTES));


/*----------------------------------------------------------------
-  CUSTOM HEADING LEVELS
---------------------------------------------------------------- */
$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL = array(
	'websiteTitle' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_websiteTitle'),ENT_QUOTES),
	'websiteSubtitle' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_websiteSubtitle'),ENT_QUOTES),
	'websiteLogo' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_websiteLogo'),ENT_QUOTES),
	'locationPath' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_locationPath'),ENT_QUOTES),
	'accessibilityOptions' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_accessibilityOptions'),ENT_QUOTES),
	'modulesOnAccessibilityPanel' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_modulesOnaccessibilityPanel'),ENT_QUOTES),
	'headerMenu' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_headerMenu'),ENT_QUOTES),
	'languageOptions' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_languageOptions'),ENT_QUOTES),
	'search' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_search'),ENT_QUOTES),
	'topMenu' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_topMenu'),ENT_QUOTES),
	'mainMenu' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_mainMenu'),ENT_QUOTES),
	'additionalResourcesLeft' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_additionalResourcesLeft'),ENT_QUOTES),
	'login' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_login'),ENT_QUOTES),
	'additionalResourcesRight' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_additionalResourcesRight'),ENT_QUOTES),
	'mainContent' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_mainContent'),ENT_QUOTES),
	'supplementaryContentUpper' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_supplementaryContentUpper'),ENT_QUOTES),
	'supplementaryContentLower' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_supplementaryContentLower'),ENT_QUOTES),
	'footer' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_footer'),ENT_QUOTES),
	'footerMenu' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_footerMenu'),ENT_QUOTES),
	'quickLinksMenu' => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadings_quickLinksMenu'),ENT_QUOTES)
	);


/*----------------------------------------------------------------
-  CUSTOM HEAD TAGS
---------------------------------------------------------------- */
$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEAD_TAG = array(
	1 => array(
		"type" => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_1_type'),ENT_QUOTES),
		"attributes" => array( // Pattern: "attributeValue" => "attributeContent"
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_1_attribute_1'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_1_attribute_1_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_1_attribute_2'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_1_attribute_2_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_1_attribute_3'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_1_attribute_3_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_1_attribute_4'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_1_attribute_4_content'),ENT_QUOTES)
			)
		),
	2 => array(
		"type" => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_2_type'),ENT_QUOTES),
		"attributes" => array( // Pattern: "attributeValue" => "attributeContent"
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_2_attribute_1'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_2_attribute_1_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_2_attribute_2'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_2_attribute_2_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_2_attribute_3'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_2_attribute_3_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_2_attribute_4'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_2_attribute_4_content'),ENT_QUOTES)
			)
		),
	3 => array(
		"type" => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_3_type'),ENT_QUOTES),
		"attributes" => array( // Pattern: "attributeValue" => "attributeContent"
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_3_attribute_1'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_3_attribute_1_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_3_attribute_2'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_3_attribute_2_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_3_attribute_3'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_3_attribute_3_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_3_attribute_4'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_3_attribute_4_content'),ENT_QUOTES)
			)
		),
	4 => array(
		"type" => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_4_type'),ENT_QUOTES),
		"attributes" => array( // Pattern: "attributeValue" => "attributeContent"
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_4_attribute_1'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_4_attribute_1_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_4_attribute_2'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_4_attribute_2_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_4_attribute_3'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_4_attribute_3_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_4_attribute_4'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_4_attribute_4_content'),ENT_QUOTES)
			)
		),
	5 => array(
		"type" => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_5_type'),ENT_QUOTES),
		"attributes" => array( // Pattern: "attributeValue" => "attributeContent"
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_5_attribute_1'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_5_attribute_1_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_5_attribute_2'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_5_attribute_2_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_5_attribute_3'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_5_attribute_3_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_5_attribute_4'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_5_attribute_4_content'),ENT_QUOTES)
			)
		),
	6 => array(
		"type" => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_6_type'),ENT_QUOTES),
		"attributes" => array( // Pattern: "attributeValue" => "attributeContent"
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_6_attribute_1'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_6_attribute_1_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_6_attribute_2'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_6_attribute_2_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_6_attribute_3'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_6_attribute_3_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_6_attribute_4'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_6_attribute_4_content'),ENT_QUOTES)
			)
		),
	7 => array(
		"type" => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_7_type'),ENT_QUOTES),
		"attributes" => array( // Pattern: "attributeValue" => "attributeContent"
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_7_attribute_1'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_7_attribute_1_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_7_attribute_2'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_7_attribute_2_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_7_attribute_3'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_7_attribute_3_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_7_attribute_4'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_7_attribute_4_content'),ENT_QUOTES)
			)
		),
	8 => array(
		"type" => htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_8_type'),ENT_QUOTES),
		"attributes" => array( // Pattern: "attributeValue" => "attributeContent"
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_8_attribute_1'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_8_attribute_1_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_8_attribute_2'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_8_attribute_2_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_8_attribute_3'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_8_attribute_3_content'),ENT_QUOTES),
			htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_8_attribute_4'),ENT_QUOTES) => 
				htmlspecialchars($this->params->get('zhongframework_Jparam_customHeadTag_8_attribute_4_content'),ENT_QUOTES)
			)
		),
	);


?>
