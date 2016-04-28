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
   CUSTOM USER STYLE
==========================================================================*/
?>
	
<?php
/*----------------------------------------------------------------
-  CUSTOM LEFT/RIGHT COLUMN WIDTH
---------------------------------------------------------------- */
//If the user sets a custom width for the left & right column AND the "default layout mode" is selected
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_COLUMNS_WIDTH=='false' && ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout" ): ?>
	.left-column-width-3 #left-column,
	.left-column-width-2 #left-column
		{width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_LEFT_COLUMN_WIDTH;?>%;}
	.right-column-width-3 #right-column,
	.right-column-width-2 #right-column
		{width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_RIGHT_COLUMN_WIDTH;?>%;}
	<?php /* Let's calculate the content column width: */
		$content_width = 100;
		if(ZHONGFRAMEWORK_RIGHT_COLUMN_EXISTS && ZHONGFRAMEWORK_LEFT_COLUMN_EXISTS){
			//Both columns are printed
			$content_width -= ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_LEFT_COLUMN_WIDTH + ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_RIGHT_COLUMN_WIDTH;
			}
		else{
			//Only left column is printed
			if(ZHONGFRAMEWORK_LEFT_COLUMN_EXISTS)
				$content_width -= ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_LEFT_COLUMN_WIDTH;
			//Only right column is printed
			if(ZHONGFRAMEWORK_RIGHT_COLUMN_EXISTS)
				$content_width -= ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_RIGHT_COLUMN_WIDTH;
			}
		?>
	.main-content-container-width-3 #main-content-container,
	.main-content-container-width-2 #main-content-container,
	.main-content-container-width-1 #main-content-container
		{width:<?php echo $content_width;?>%;}
<?php endif; ?>

<?php
/*----------------------------------------------------------------
-  CUSTOM MIN/MAX LAYOUT WIDTH
---------------------------------------------------------------- */
if(ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout"): ?>
	.full-layout-width .layout-width-rail{
		width:96%;
		max-width:96%;
		min-width:320px;
		}
	.liquid-layout-width .layout-width-rail{
		width:<?php echo ZHONGFRAMEWORK_PARAMETER_LIQUID_LAYOUT_WIDTH;?>%;
		min-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MIN_LIQUID_LAYOUT_WIDTH;?>px;
		max-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAX_LIQUID_LAYOUT_WIDTH;?>px;
		}
	.fixed-layout-width .layout-width-rail{
		width:<?php echo ZHONGFRAMEWORK_PARAMETER_FIXED_LAYOUT_WIDTH;?>px;
		max-width:<?php echo ZHONGFRAMEWORK_PARAMETER_FIXED_LAYOUT_WIDTH;?>px;
		min-width:<?php echo ZHONGFRAMEWORK_PARAMETER_FIXED_LAYOUT_WIDTH;?>px;
		}
	.fixed-layout-width #top-bar,
	.fixed-layout-width #accessibility-panel{
		min-width:<?php echo ZHONGFRAMEWORK_PARAMETER_FIXED_LAYOUT_WIDTH;?>px;
		}
	.custom-layout-width .layout-width-rail{
		max-width:96%;
		min-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MIN_LIQUID_LAYOUT_WIDTH;?>px;
		}
<?php endif; ?>

<?php
/*----------------------------------------------------------------
-  CUSTOM LOGO SIZE
---------------------------------------------------------------- */
?>
#logo-wrap,
#logo-wrap #logo-wrap-link,
#logo-wrap img{
	<?php
		// Print height if set
		if( ZHONGFRAMEWORK_PARAMETER_LOGO_HEIGHT!="0" && ZHONGFRAMEWORK_PARAMETER_LOGO_HEIGHT!="auto" && ZHONGFRAMEWORK_PARAMETER_LOGO_HEIGHT!=""){
			echo 'height:'.intval(ZHONGFRAMEWORK_PARAMETER_LOGO_HEIGHT).'px;'; }
		// Print width if set
		if( ZHONGFRAMEWORK_PARAMETER_LOGO_WIDTH!="0" && ZHONGFRAMEWORK_PARAMETER_LOGO_WIDTH!="auto" && ZHONGFRAMEWORK_PARAMETER_LOGO_WIDTH!=""){
			echo 'width:'.intval(ZHONGFRAMEWORK_PARAMETER_LOGO_WIDTH).'px;'; }
	?>
	}

<?php
/*----------------------------------------------------------------
-  CUSTOM FONT SIZE
---------------------------------------------------------------- */
?>
body{
	font-size:<?php echo ZHONGFRAMEWORK_FONT_SIZE; ?>%;
	}

<?php
/*----------------------------------------------------------------
-  GENERAL (custom user color)
---------------------------------------------------------------- */
?>

<?php //Body Background Color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_BODY_BG=='custom'){ ?>
	body.default-graphic-mode{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_BODY_BG;?> !important;
		}
	.default-graphic-mode #gradient-effect{display:none;}
<?php } ?>

<?php //Text Color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_TEXT_COLOR=='custom'){ ?>
	body.default-graphic-mode{
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TEXT_COLOR;?>;
		}
<?php } ?>

<?php //Headings Color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_HEADINGS_COLOR=='custom'){ ?>
	.default-graphic-mode h1,
	.default-graphic-mode h2,
	.default-graphic-mode h3,
	.default-graphic-mode h4,
	.default-graphic-mode h5,
	.default-graphic-mode h6{
		text-shadow: 0 0 0 #000 !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_HEADINGS_COLOR;?>;
		}
<?php } ?>

<?php //Links Color (also visited and hover)
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_LINKS_COLOR=='custom'){ ?>
	.default-graphic-mode a,
	.default-graphic-mode a:link{
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_LINKS_COLOR;?>;
		}
	.default-graphic-mode a:visited{
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_VISITED_LINKS_COLOR;?>;
		}
	.default-graphic-mode a:hover,
	.default-graphic-mode a:focus,
	.default-graphic-mode a:active{
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_HOVER_LINKS_COLOR;?>;
		}
<?php } ?>

<?php //Buttons (no !important here)
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_BUTTONS=='custom'){ ?>
	.default-graphic-mode button,
	.default-graphic-mode .button,
	.default-graphic-mode .button:visited,
	.default-graphic-mode input[type=button]{
		text-shadow: 0 0 0 #000;
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_BUTTONS_BG;?>;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_BUTTONS_TEXT;?>;
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_BUTTONS_BORDER_COLOR;?>;
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_BUTTONS_BORDER_STYLE;?>;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_BUTTONS_BORDER_WIDTH;?>;
		}
	.default-graphic-mode button:hover,
	.default-graphic-mode .button:hover,
	.default-graphic-mode input[type=button]:hover,
	.default-graphic-mode button:focus,
	.default-graphic-mode .button:focus,
	.default-graphic-mode input[type=button]:focus,
	.default-graphic-mode button:active,
	.default-graphic-mode .button:active,
	.default-graphic-mode input[type=button]:active{
		text-shadow: 0 0 0 #000;
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_BUTTONS_BG_HOVER;?>;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_BUTTONS_TEXT_HOVER;?>;
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_BUTTONS_BORDER_COLOR_HOVER;?>;
		}
<?php } ?>

<?php

?>
<?php
/*----------------------------------------------------------------
-  CUSTOM FONT FAMILY
---------------------------------------------------------------- */
if(ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_GENERAL=="custom"){
	echo 'body{font-family:'.ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_FAMILY_GENERAL.';}';
	}
if(ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_HEADING=="custom"){
	echo 'h1,h2,h3,h4,h5,h6{font-family:'.ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_FAMILY_HEADING.';}';
	}
if(ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_WEBSITE_TITLE=="custom"){
	echo '#header #title{font-family:'.ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_FAMILY_WEBSITE_TITLE.';}';
	}
if(ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_WEBSITE_SUBTITLE=="custom"){
	echo '#header #subtitle{font-family:'.ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_FAMILY_WEBSITE_SUBTITLE.';}';
	}
if(ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_MAIN_MENU=="custom"){
	echo '#main-menu-container{font-family:'.ZHONGFRAMEWORK_PARAMETER_CUSTOM_FONT_FAMILY_MAIN_MENU.';}';
	}
?>

<?php
/*----------------------------------------------------------------
-  MAIN LAYOUT (custom user color)
---------------------------------------------------------------- */
?>

<?php //Website Title Color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_WEBSITE_TITLE_COLOR=='custom'){ ?>
	.default-graphic-mode #titles-container #title{
		text-shadow: 0 0 0 #000 !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_TITLE_COLOR;?> !important;
		}
<?php } ?>

<?php // Title font size
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_WEBSITE_TITLE_FONT_SIZE=='custom'): ?>
	.default-graphic-mode #titles-container #title{
		font-size:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_TITLE_FONT_SIZE;?>em;
	}
	.default-graphic-mode.presentation-align-left #header #website-banner #titles-container-inner,
	.default-graphic-mode.presentation-align-right #header #website-banner #titles-container-inner{
		top:-<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_TITLE_FONT_SIZE/100*75;?>em;
	}
<?php endif; ?>

<?php //Website Subtitle Color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_WEBSITE_SUBTITLE_COLOR=='custom'){ ?>
	.default-graphic-mode #titles-container #subtitle{
		text-shadow: 0 0 0 #000 !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_SUBTITLE_COLOR;?> !important;
		}
<?php } ?>

<?php // Subtitle font size
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_WEBSITE_SUBTITLE_FONT_SIZE=='custom'): ?>
	.default-graphic-mode #titles-container #subtitle{
		font-size:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_SUBTITLE_FONT_SIZE;?>em;
	}
<?php endif; ?>

<?php //Layout Container Background Color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_LAYOUT_BG=='custom'){ ?>
	.default-graphic-mode #layout-container_zng{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_LAYOUT_BG;?> !important;
		}
<?php } ?>

<?php //Layout Container Border Color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_LAYOUT_BORDER=='custom'){ ?>
	.default-graphic-mode #layout-container_zng{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_LAYOUT_BORDER_COLOR;?> !important;
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_LAYOUT_BORDER_STYLE;?> !important;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_LAYOUT_BORDER_WIDTH;?> !important;
		}
<?php } ?>

<?php //Header Background
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_WEBSITE_HEADER_BG=='custom'){ ?>
	.default-graphic-mode #header{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_HEADER_BG;?> !important;
		}
<?php } ?>

<?php //Layout Container Border
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_HEADER_BORDER=='custom'){ ?>
	.default-graphic-mode #header{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_HEADER_BORDER_COLOR;?> !important;
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_HEADER_BORDER_STYLE;?> !important;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_HEADER_BORDER_WIDTH;?> !important;
		}
<?php } ?>

<?php //Main Body Background
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_BODY_BG=='custom'){ ?>
	.default-graphic-mode #main-body{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_BODY_BG;?> !important;
		}
<?php } ?>

<?php //Main Body Border
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_BODY_BORDER=='custom'){ ?>
	.default-graphic-mode #main-body{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_BODY_BORDER_COLOR;?> !important;
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_BODY_BORDER_STYLE;?> !important;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_BODY_BORDER_WIDTH;?> !important;
		}
<?php } ?>

<?php //Main Content Container Background
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_CONTENT_CONTAINER_BG=='custom'){ ?>
	.default-graphic-mode #main-content-container,
	.default-graphic-mode #main-article-container{background:none !important;}
	.default-graphic-mode #main-content-container-inner{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_CONTENT_CONTAINER_BG;?> !important;
		}
<?php } ?>

<?php //Main Content Container Border
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_CONTENT_CONTAINER_BORDER=='custom'){ ?>
	.default-graphic-mode #main-content-container,
	.default-graphic-mode #main-article-container{border:none !important;}
	.default-graphic-mode #main-content-container-inner{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_CONTENT_CONTAINER_BORDER_COLOR;?> !important;
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_CONTENT_CONTAINER_BORDER_STYLE;?> !important;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_CONTENT_CONTAINER_BORDER_WIDTH;?> !important;
		}
<?php } ?>

<?php //Top button
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_TOP_BUTTON=='custom'){ ?>
	.default-graphic-mode #goto-top{
		text-shadow: 0 0 0 #000 !important;
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BUTTON_BG;?> !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BUTTON_TEXT;?> !important;
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BUTTON_BORDER_COLOR;?> !important;
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BUTTON_BORDER_STYLE;?> !important;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BUTTON_BORDER_WIDTH;?> !important;
		}
	.default-graphic-mode #goto-top:hover,
	.default-graphic-mode #goto-top:focus,
	.default-graphic-mode #goto-top:active{
		text-shadow: 0 0 0 #000 !important;
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BUTTON_BG_HOVER;?> !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BUTTON_TEXT_HOVER;?> !important;
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BUTTON_BORDER_COLOR_HOVER;?> !important;
		}
<?php } ?>

<?php //Footer Background Color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_WEBSITE_FOOTER_BG=='custom'){ ?>
	.default-graphic-mode #footer,
	.default-graphic-mode #footer-inner,
	.default-graphic-mode #footer-wrapper-inner,
	.default-graphic-mode #footer-credits{background:none !important;border:0 !important;}
	.default-graphic-mode #footer-wrapper{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_FOOTER_BG;?> !important;
		}
<?php } ?>

<?php //Footer Background Border
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_FOOTER_BORDER=='custom'){ ?>
	.default-graphic-mode #footer,
	.default-graphic-mode #footer-inner,
	.default-graphic-mode #footer-wrapper-inner,
	.default-graphic-mode #footer-credits{border:0 !important;}
	.default-graphic-mode #footer-wrapper{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_BORDER_COLOR;?> !important;
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_BORDER_STYLE;?> !important;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_BORDER_WIDTH;?> !important;
		}
<?php } ?>

<?php //Footer Text Color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_WEBSITE_FOOTER_TEXT_COLOR=='custom'){ ?>
	.default-graphic-mode #footer-wrapper{
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_FOOTER_TEXT_COLOR;?> !important;
		}
	.default-graphic-mode #footer,
	.default-graphic-mode #footer-menu,
	.default-graphic-mode #footer-credits{color:inherit !important;}		
<?php } ?>

<?php //Footer Header Color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_WEBSITE_FOOTER_HEADERS_COLOR=='custom'){ ?>
	.default-graphic-mode #footer-wrapper h1,
	.default-graphic-mode #footer-wrapper h2,
	.default-graphic-mode #footer-wrapper h3,
	.default-graphic-mode #footer-wrapper h4,
	.default-graphic-mode #footer-wrapper h5,
	.default-graphic-mode #footer-wrapper h6{
		text-shadow: 0 0 0 #000 !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_FOOTER_HEADERS_COLOR;?> !important;
		}
<?php } ?>

<?php //footer Links Color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_WEBSITE_FOOTER_LINKS_COLOR=='custom'){ ?>
	.default-graphic-mode #footer-wrapper a{
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_FOOTER_LINKS_COLOR;?> !important;
		}
	.default-graphic-mode #footer-wrapper a:visited{
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_FOOTER_VISITED_LINKS_COLOR;?> !important;
		}
	.default-graphic-mode #footer-wrapper a:hover,
	.default-graphic-mode #footer-wrapper a:focus,
	.default-graphic-mode #footer-wrapper a:active{
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_WEBSITE_FOOTER_HOVER_LINKS_COLOR;?> !important;
		}
<?php } ?>


<?php //Top bar Background
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_TOP_BAR_BG=='custom'){ ?>
	.default-graphic-mode #top-layout-container #top-bar,
	.default-graphic-mode #top-layout-container #top-bar-middle,
	.default-graphic-mode #top-layout-container #top-bar-inner,
	.default-graphic-mode #top-layout-container #top-bar .top-bar-module{background:none !important;}
	.default-graphic-mode #top-layout-container #top-bar .top-bar-module{border-color:transparent !important;}
	.default-graphic-mode #top-layout-container #top-bar{ 
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BAR_BG;?> !important;
		}
<?php } ?>

<?php //Top bar Border
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_TOP_BAR_BORDER=='custom'){ ?>
	.default-graphic-mode #top-layout-container #top-bar-middle,
	.default-graphic-mode #top-layout-container #top-bar-inner{border:0 !important;}
	.default-graphic-mode #top-layout-container #top-bar{ 
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BAR_BORDER_COLOR;?> !important;
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BAR_BORDER_STYLE;?> !important;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BAR_BORDER_WIDTH;?> !important;
		}
	.default-graphic-mode #top-layout-container #top-bar .top-bar-module{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BAR_BORDER_COLOR;?> !important;
		}
<?php } ?>

<?php //Top bar Text
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_TOP_BAR_FONT=='custom'){ ?>
	.default-graphic-mode #top-bar,
	.default-graphic-mode #top-bar-middle,
	.default-graphic-mode #top-bar-inner{
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BAR_FONT_COLOR;?> !important;
		}
<?php } ?>

<?php //Top bar Links
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_TOP_BAR_LINKS=='custom'){ ?>
	.default-graphic-mode #top-bar a,
	.default-graphic-mode #top-bar a *, /*(there are spans for the icons!)*/
	.default-graphic-mode #top-bar button, /*IMPORTANT! button as well because of the top bar icons*/
	.default-graphic-mode #top-bar button *{
		text-shadow: 0 0 0 #000 !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BAR_LINKS_COLOR;?> !important;
		}
	.default-graphic-mode #top-bar a:visited{
		text-shadow: 0 0 0 #000 !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BAR_LINKS_COLOR_VISITED;?> !important;
		}
	.default-graphic-mode #top-bar a:hover,
	.default-graphic-mode #top-bar a:active,
	.default-graphic-mode #top-bar a:focus,
	.default-graphic-mode #top-bar a:hover *,
	.default-graphic-mode #top-bar a:active *,
	.default-graphic-mode #top-bar a:focus *,
	.default-graphic-mode #top-bar button:hover,
	.default-graphic-mode #top-bar button:active,
	.default-graphic-mode #top-bar button:focus,
	.default-graphic-mode #top-bar button:hover *,
	.default-graphic-mode #top-bar button:active *,
	.default-graphic-mode #top-bar button:focus *{
		text-shadow: 0 0 0 #000 !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_TOP_BAR_LINKS_COLOR_HOVER;?> !important;
		}
<?php } ?>

<?php //Accessibility Panel Background Color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_ACCESSIBILITY_PANEL_BG=='custom'){ ?>
	.default-graphic-mode #top-layout-container #accessibility-panel,
	.default-graphic-mode #top-layout-container #accessibility-panel-inner{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ACCESSIBILITY_PANEL_BG;?> !important;
		}
<?php } ?>

<?php //Accessibility Panel Border
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_ACCESSIBILITY_PANEL_BORDER=='custom'){ ?>
	.default-graphic-mode #top-layout-container #accessibility-panel{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ACCESSIBILITY_PANEL_BORDER_COLOR;?> !important;
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ACCESSIBILITY_PANEL_BORDER_STYLE;?> !important;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ACCESSIBILITY_PANEL_BORDER_WIDTH;?> !important;
		}
	.default-graphic-mode #top-layout-container #accessibility-panel-inner{border:0 !important;}
<?php } ?>

<?php
/*----------------------------------------------------------------
-  MAIN MENU (custom user color)
---------------------------------------------------------------- */
?>

<?php //Main Menu Container Background Color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_MENU_CONTAINER_BG=='custom'){ ?>
	.default-graphic-mode #header #main-menu-container,
	.default-graphic-mode #header #main-menu-container ul,
	.default-graphic-mode #header #main-menu-container-inner{background:none !important}
	.default-graphic-mode #header #main-menu-container{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_CONTAINER_BG;?> !important;
		}
<?php } ?>

<?php //Main Menu Container Border
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_MENU_CONTAINER_BORDER=='custom'){ ?>
	.default-graphic-mode #header #main-menu-container,
	.default-graphic-mode #header #main-menu-container-inner{border:0 !important;}
	.default-graphic-mode #header #main-menu-container{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_CONTAINER_BORDER_COLOR;?> !important;
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_CONTAINER_BORDER_STYLE;?> !important;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_CONTAINER_BORDER_WIDTH;?> !important;
		}
<?php } ?>

<?php //Links 1 color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_MENU_LINKS_1_COLOR=='custom'){ ?>
	.default-graphic-mode #header #main-menu-container ul a{
		text-shadow: 0 0 0 #000 !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_1_COLOR;?> !important;
		}
	.default-graphic-mode #header #main-menu-container ul a:hover,
	.default-graphic-mode #header #main-menu-container ul a:focus,
	.default-graphic-mode #header #main-menu-container ul a:active,
	.default-graphic-mode #header #main-menu-container ul li.active>a,
	.default-graphic-mode #header #main-menu-container ul li.current>a{
		text-shadow: 0 0 0 #000 !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_1_COLOR_HOVER;?> !important;
		}
<?php } ?>

<?php //Links 1 background
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_MENU_LINKS_1_BG=='custom'){ ?>
	.default-graphic-mode #header #main-menu-container ul li{background:none !important}
	.default-graphic-mode #header #main-menu-container ul a{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_1_BG;?> !important;
		}
	.default-graphic-mode #header #main-menu-container ul a:hover,
	.default-graphic-mode #header #main-menu-container ul a:focus,
	.default-graphic-mode #header #main-menu-container ul a:active,
	.default-graphic-mode #header #main-menu-container ul li.active>a,
	.default-graphic-mode #header #main-menu-container ul li.current>a{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_1_BG_HOVER;?> !important;
		}
<?php } ?>

<?php //Links 1 border
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_MENU_LINKS_1_BORDER=='custom'){ ?>
	.default-graphic-mode #header #main-menu-container ul,
	.default-graphic-mode #header #main-menu-container ul li{border:0 !important}
	.default-graphic-mode #header #main-menu-container ul a{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_1_BORDER_COLOR;?> !important;
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_1_BORDER_STYLE;?> !important;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_1_BORDER_WIDTH;?> !important;
		}
	.default-graphic-mode #header #main-menu-container ul a:hover,
	.default-graphic-mode #header #main-menu-container ul a:focus,
	.default-graphic-mode #header #main-menu-container ul a:active,
	.default-graphic-mode #header #main-menu-container ul li.active>a,
	.default-graphic-mode #header #main-menu-container ul li.current>a{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_1_BORDER_COLOR_HOVER;?> !important;
		}
<?php } ?>

<?php //Links sub color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_MENU_LINKS_SUB_COLOR=='custom'){ ?>
	.default-graphic-mode #header #main-menu-container ul ul a{
		text-shadow: 0 0 0 #000 !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_SUB_COLOR;?> !important;
		}
	.default-graphic-mode #header #main-menu-container ul ul a:hover,
	.default-graphic-mode #header #main-menu-container ul ul a:focus,
	.default-graphic-mode #header #main-menu-container ul ul a:active,
	.default-graphic-mode #header #main-menu-container ul ul li.active>a,
	.default-graphic-mode #header #main-menu-container ul ul li.current>a{
		text-shadow: 0 0 0 #000 !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_SUB_COLOR_HOVER;?> !important;
		}
<?php } ?>

<?php //Links sub background
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_MENU_LINKS_SUB_BG=='custom'){ ?>
	.default-graphic-mode #header #main-menu-container ul ul,
	.default-graphic-mode #header #main-menu-container ul ul li{background:none !important}
	.default-graphic-mode #header #main-menu-container ul ul a,
	.default-graphic-mode #header #main-menu-container ul ul a:visited{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_SUB_BG;?> !important;
		}
	.default-graphic-mode #header #main-menu-container ul ul a:hover,
	.default-graphic-mode #header #main-menu-container ul ul a:focus,
	.default-graphic-mode #header #main-menu-container ul ul a:active,
	.default-graphic-mode #header #main-menu-container ul ul li.active>a,
	.default-graphic-mode #header #main-menu-container ul ul li.current>a{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_SUB_BG_HOVER;?> !important;
		}
<?php } ?>

<?php //Links sub border
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_MAIN_MENU_LINKS_SUB_BORDER=='custom'){ ?>
	.default-graphic-mode #header #main-menu-container ul ul,
	.default-graphic-mode #header #main-menu-container ul ul li{border:0 !important}
	.default-graphic-mode #header #main-menu-container ul ul a{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_SUB_BORDER_COLOR;?> !important;
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_SUB_BORDER_STYLE;?> !important;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_SUB_BORDER_WIDTH;?> !important;
		}
	.default-graphic-mode #header #main-menu-container ul ul a:hover,
	.default-graphic-mode #header #main-menu-container ul ul a:focus,
	.default-graphic-mode #header #main-menu-container ul ul a:active,
	.default-graphic-mode #header #main-menu-container ul ul li.active>a,
	.default-graphic-mode #header #main-menu-container ul ul li.current>a{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_MAIN_MENU_LINKS_SUB_BORDER_COLOR_HOVER;?> !important;
		}
<?php } ?>

<?php
/*----------------------------------------------------------------
-  SIDE MENUS (custom user color)
---------------------------------------------------------------- */
?>

<?php //Links 1 color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SIDE_MENUS_LINKS_1_COLOR=='custom'){ ?>
	.default-graphic-mode #main-body .menu-container ul a{
		text-shadow: 0 0 0 #000 !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_1_COLOR;?> !important;
		}
	.default-graphic-mode #main-body .menu-container ul a:hover,
	.default-graphic-mode #main-body .menu-container ul a:focus,
	.default-graphic-mode #main-body .menu-container ul a:active,
	.default-graphic-mode #main-body .menu-container ul li.active>a,
	.default-graphic-mode #main-body .menu-container ul li.current>a{
		text-shadow: 0 0 0 #000 !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_1_COLOR_HOVER;?> !important;
		}
<?php } ?>

<?php //Links 1 background
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SIDE_MENUS_LINKS_1_BG=='custom'){ ?>
	.default-graphic-mode #main-body .menu-container ul,
	.default-graphic-mode #main-body .menu-container ul li{background:none !important;}
	.default-graphic-mode #main-body .menu-container ul a{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_1_BG;?> !important;
		}
	.default-graphic-mode #main-body .menu-container ul a:hover,
	.default-graphic-mode #main-body .menu-container ul a:focus,
	.default-graphic-mode #main-body .menu-container ul a:active,
	.default-graphic-mode #main-body .menu-container ul li.active>a,
	.default-graphic-mode #main-body .menu-container ul li.current>a{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_1_BG_HOVER;?> !important;
		}
<?php } ?>

<?php //Links 1 border
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SIDE_MENUS_LINKS_1_BORDER=='custom'){ ?>
	.default-graphic-mode #main-body .menu-container ul,
	.default-graphic-mode #main-body .menu-container ul li{border:0 !important}
	.default-graphic-mode #main-body .menu-container ul a{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_1_BORDER_COLOR;?> !important;
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_1_BORDER_STYLE;?> !important;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_1_BORDER_WIDTH;?> !important;
		}
	.default-graphic-mode #main-body .menu-container ul a:hover,
	.default-graphic-mode #main-body .menu-container ul a:focus,
	.default-graphic-mode #main-body .menu-container ul a:active,
	.default-graphic-mode #main-body .menu-container ul li.active>a,
	.default-graphic-mode #main-body .menu-container ul li.current>a{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_1_BORDER_COLOR_HOVER;?> !important;
		}
<?php } ?>

<?php //Links sub color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SIDE_MENUS_LINKS_SUB_COLOR=='custom'){ ?>
	.default-graphic-mode #main-body .menu-container ul ul a{
		text-shadow: 0 0 0 #000 !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_SUB_COLOR;?> !important;
		}
	.default-graphic-mode #main-body .menu-container ul ul a:hover,
	.default-graphic-mode #main-body .menu-container ul ul a:focus,
	.default-graphic-mode #main-body .menu-container ul ul a:active,
	.default-graphic-mode #main-body .menu-container ul ul li.active>a,
	.default-graphic-mode #main-body .menu-container ul ul li.current>a{
		text-shadow: 0 0 0 #000 !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_SUB_COLOR_HOVER;?> !important;
		}
<?php } ?>

<?php //Links sub background
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SIDE_MENUS_LINKS_SUB_BG=='custom'){ ?>
	.default-graphic-mode #main-body .menu-container ul ul,
	.default-graphic-mode #main-body .menu-container ul ul li{background:none !important}
	.default-graphic-mode #main-body .menu-container ul ul a,
	.default-graphic-mode #main-body .menu-container ul ul a:visited{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_SUB_BG;?> !important;
		}
	.default-graphic-mode #main-body .menu-container ul ul a:hover,
	.default-graphic-mode #main-body .menu-container ul ul a:focus,
	.default-graphic-mode #main-body .menu-container ul ul a:active,
	.default-graphic-mode #main-body .menu-container ul ul li.active>a,
	.default-graphic-mode #main-body .menu-container ul ul li.current>a{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_SUB_BG_HOVER;?> !important;
		}
<?php } ?>

<?php //Links sub border
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SIDE_MENUS_LINKS_SUB_BORDER=='custom'){ ?>
	.default-graphic-mode #main-body .menu-container ul ul,
	.default-graphic-mode #main-body .menu-container ul ul li{border:0 !important}
	.default-graphic-mode #main-body .menu-container ul ul a{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_SUB_BORDER_COLOR;?> !important;
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_SUB_BORDER_STYLE;?> !important;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_SUB_BORDER_WIDTH;?> !important;
		}
	.default-graphic-mode #main-body .menu-container ul ul a:hover,
	.default-graphic-mode #main-body .menu-container ul ul a:focus,
	.default-graphic-mode #main-body .menu-container ul ul a:active,
	.default-graphic-mode #main-body .menu-container ul ul li.active>a,
	.default-graphic-mode #main-body .menu-container ul ul li.current>a{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SIDE_MENUS_LINKS_SUB_BORDER_COLOR_HOVER;?> !important;
		}
<?php } ?>

<?php
/*----------------------------------------------------------------
-  SUPPORT MENU (custom user color)
---------------------------------------------------------------- */
?>

<?php //Support Menu Container Background Color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SUPPORT_MENU_CONTAINER_BG=='custom'){ ?>
	.default-graphic-mode #header #support-menu-inner ul,
	.default-graphic-mode #header #support-menu-inner{background:none !important}
	.default-graphic-mode #header #support-menu-outer{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_CONTAINER_BG;?> !important;
		}
<?php } ?>

<?php //Support Menu Container Border
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SUPPORT_MENU_CONTAINER_BORDER=='custom'){ ?>
	.default-graphic-mode #header #support-menu-inner{border:0 !important;}
	.default-graphic-mode #header #support-menu-outer{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_CONTAINER_BORDER_COLOR;?> !important;
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_CONTAINER_BORDER_STYLE;?> !important;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_CONTAINER_BORDER_WIDTH;?> !important;
		}
<?php } ?>

<?php //Links 1 color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SUPPORT_MENU_LINKS_1_COLOR=='custom'){ ?>
	.default-graphic-mode #header #support-menu-outer ul a,
	.default-graphic-mode #header #support-menu-outer ul a:visited{
		text-shadow: 0 0 0 #000 !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_LINKS_1_COLOR;?> !important;
		}
	.default-graphic-mode #header #support-menu-outer ul a:hover,
	.default-graphic-mode #header #support-menu-outer ul a:focus,
	.default-graphic-mode #header #support-menu-outer ul a:active,
	.default-graphic-mode #header #support-menu-outer ul li.active>a,
	.default-graphic-mode #header #support-menu-outer ul li.current>a{
		text-shadow: 0 0 0 #000 !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_LINKS_1_COLOR_HOVER;?> !important;
		}
<?php } ?>

<?php //Links 1 background
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SUPPORT_MENU_LINKS_1_BG=='custom'){ ?>
	.default-graphic-mode #header #support-menu-outer ul li{background:none !important}
	.default-graphic-mode #header #support-menu-outer ul a{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_LINKS_1_BG;?> !important;
		}
	.default-graphic-mode #header #support-menu-outer ul a:hover,
	.default-graphic-mode #header #support-menu-outer ul a:focus,
	.default-graphic-mode #header #support-menu-outer ul a:active,
	.default-graphic-mode #header #support-menu-outer ul li.active>a,
	.default-graphic-mode #header #support-menu-outer ul li.current>a{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_LINKS_1_BG_HOVER;?> !important;
		}
<?php } ?>

<?php //Links 1 border
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_SUPPORT_MENU_LINKS_1_BORDER=='custom'){ ?>
	.default-graphic-mode #header #support-menu-outer ul,
	.default-graphic-mode #header #support-menu-outer ul li{border:0 !important}
	.default-graphic-mode #header #support-menu-outer ul a{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_LINKS_1_BORDER_COLOR;?> !important;
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_LINKS_1_BORDER_STYLE;?> !important;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_LINKS_1_BORDER_WIDTH;?> !important;
		}
	.default-graphic-mode #header #support-menu-outer ul a:hover,
	.default-graphic-mode #header #support-menu-outer ul a:focus,
	.default-graphic-mode #header #support-menu-outer ul a:active,
	.default-graphic-mode #header #support-menu-outer ul li.active>a,
	.default-graphic-mode #header #support-menu-outer ul li.current>a{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_SUPPORT_MENU_LINKS_1_BORDER_COLOR_HOVER;?> !important;
		}
<?php } ?>

<?php
/*----------------------------------------------------------------
-  FOOTER MENU (custom user color)
---------------------------------------------------------------- */
?>

<?php //Footer Menu Container Background Color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_FOOTER_MENU_CONTAINER_BG=='custom'){ ?>
	.default-graphic-mode #footer-menu-inner ul,
	.default-graphic-mode #footer-menu-inner{background:none !important}
	.default-graphic-mode #footer-menu{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_CONTAINER_BG;?> !important;
		}
<?php } ?>

<?php //Footer Menu Container Border
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_FOOTER_MENU_CONTAINER_BORDER=='custom'){ ?>
	.default-graphic-mode #footer-menu-inner{border:0 !important;}
	.default-graphic-mode #footer-menu{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_CONTAINER_BORDER_COLOR;?> !important;
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_CONTAINER_BORDER_STYLE;?> !important;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_CONTAINER_BORDER_WIDTH;?> !important;
		}
<?php } ?>

<?php //Links 1 color
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_FOOTER_MENU_LINKS_1_COLOR=='custom'){ ?>
	.default-graphic-mode #footer-menu ul a,
	.default-graphic-mode #footer-menu ul a:visited{
		text-shadow: 0 0 0 #000 !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_LINKS_1_COLOR;?> !important;
		}
	.default-graphic-mode #footer-menu ul a:hover,
	.default-graphic-mode #footer-menu ul a:focus,
	.default-graphic-mode #footer-menu ul a:active,
	.default-graphic-mode #footer-menu ul li.active>a,
	.default-graphic-mode #footer-menu ul li.current>a{
		text-shadow: 0 0 0 #000 !important;
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_LINKS_1_COLOR_HOVER;?> !important;
		}
<?php } ?>

<?php //Links 1 background
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_FOOTER_MENU_LINKS_1_BG=='custom'){ ?>
	.default-graphic-mode #footer-menu ul li{background:none !important}
	.default-graphic-mode #footer-menu ul a{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_LINKS_1_BG;?> !important;
		}
	.default-graphic-mode #footer-menu ul a:hover,
	.default-graphic-mode #footer-menu ul a:focus,
	.default-graphic-mode #footer-menu ul a:active,
	.default-graphic-mode #footer-menu ul li.active>a,
	.default-graphic-mode #footer-menu ul li.current>a{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_LINKS_1_BG_HOVER;?> !important;
		}
<?php } ?>

<?php //Links 1 border
if(ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_ENABLED_FOOTER_MENU_LINKS_1_BORDER=='custom'){ ?>
	.default-graphic-mode #footer-menu ul,
	.default-graphic-mode #footer-menu ul li{border:0 !important}
	.default-graphic-mode #footer-menu ul a{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_LINKS_1_BORDER_COLOR;?> !important;
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_LINKS_1_BORDER_STYLE;?> !important;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_LINKS_1_BORDER_WIDTH;?> !important;
		}
	.default-graphic-mode #footer-menu ul a:hover,
	.default-graphic-mode #footer-menu ul a:focus,
	.default-graphic-mode #footer-menu ul a:active,
	.default-graphic-mode #footer-menu ul li.active>a,
	.default-graphic-mode #footer-menu ul li.current>a{
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOMSTYLE_FOOTER_MENU_LINKS_1_BORDER_COLOR_HOVER;?> !important;
		}
<?php } ?>

<?php
/*----------------------------------------------------------------
-  CUSTOM USER MODULES (custom user color)
---------------------------------------------------------------- */
//Check if the user is using the custom style. If so, print the custom style
if (in_array("user-custom-1 custom-module-style_user-custom", $ZHONGFRAMEWORK_PARAMETER_USERMODULES_STYLE)): ?>
	.default-graphic-mode .custom-module-style_user-custom-1{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_1_BG; ?>;
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_1_BORDER_COLOR; ?>;
		}
	.default-graphic-mode .custom-module-style_user-custom-1,	
	.night-mode .custom-module-style_user-custom-1{
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_1_BORDER_STYLE; ?>;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_1_BORDER_WIDTH; ?>;
		margin:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_1_MARGIN; ?>;
		padding:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_1_PADDING; ?>;
		}
	<?php if(ZHONGFRAMEWORK_PARAMETER_ENABLE_CUSTOM_USER_MODULE_STYLE_1_TEXT_COLOR=="custom"): ?>
	.default-graphic-mode .custom-module-style_user-custom-1,
	.default-graphic-mode .custom-module-style_user-custom-1 h1,
	.default-graphic-mode .custom-module-style_user-custom-1 h2,
	.default-graphic-mode .custom-module-style_user-custom-1 h3,
	.default-graphic-mode .custom-module-style_user-custom-1 h4,
	.default-graphic-mode .custom-module-style_user-custom-1 h5,
	.default-graphic-mode .custom-module-style_user-custom-1 h6{
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_1_TEXT_COLOR; ?>;
		}
		<?php endif; ?>
	<?php if(ZHONGFRAMEWORK_PARAMETER_ENABLE_CUSTOM_USER_MODULE_STYLE_1_LINKS_COLOR=="custom"): ?>		
	.default-graphic-mode .custom-module-style_user-custom-1 a{
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_1_LINKS_COLOR; ?>;
		}
	.default-graphic-mode .custom-module-style_user-custom-1 a:hover,
	.default-graphic-mode .custom-module-style_user-custom-1 a:focus,
	.default-graphic-mode .custom-module-style_user-custom-1 a:active{
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_1_LINKS_COLOR_HOVER; ?>;
		}
		<?php endif; ?>	
	<?php endif; ?>

<?php
//Check if the user is using the custom style. If so, print the custom style
if (in_array("user-custom-2 custom-module-style_user-custom", $ZHONGFRAMEWORK_PARAMETER_USERMODULES_STYLE)): ?>
	.default-graphic-mode .custom-module-style_user-custom-2{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_2_BG; ?>;
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_2_BORDER_COLOR; ?>;
		}
	.default-graphic-mode .custom-module-style_user-custom-2,	
	.night-mode .custom-module-style_user-custom-2{	
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_2_BORDER_STYLE; ?>;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_2_BORDER_WIDTH; ?>;
		margin:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_2_MARGIN; ?>;
		padding:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_2_PADDING; ?>;
		}
	<?php if(ZHONGFRAMEWORK_PARAMETER_ENABLE_CUSTOM_USER_MODULE_STYLE_2_TEXT_COLOR=="custom"): ?>	
	.default-graphic-mode .custom-module-style_user-custom-2,
	.default-graphic-mode .custom-module-style_user-custom-2 h1,
	.default-graphic-mode .custom-module-style_user-custom-2 h2,
	.default-graphic-mode .custom-module-style_user-custom-2 h3,
	.default-graphic-mode .custom-module-style_user-custom-2 h4,
	.default-graphic-mode .custom-module-style_user-custom-2 h5,
	.default-graphic-mode .custom-module-style_user-custom-2 h6{
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_2_TEXT_COLOR; ?>;
		}
		<?php endif; ?>
	<?php if(ZHONGFRAMEWORK_PARAMETER_ENABLE_CUSTOM_USER_MODULE_STYLE_2_LINKS_COLOR=="custom"): ?>	
	.default-graphic-mode .custom-module-style_user-custom-2 a{
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_2_LINKS_COLOR; ?>;
		}
	.default-graphic-mode .custom-module-style_user-custom-2 a:hover,
	.default-graphic-mode .custom-module-style_user-custom-2 a:focus,
	.default-graphic-mode .custom-module-style_user-custom-2 a:active{
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_2_LINKS_COLOR_HOVER; ?>;
		}
		<?php endif; ?>
	<?php endif; ?>

<?php
//Check if the user is using the custom style. If so, print the custom style
if (in_array("user-custom-3 custom-module-style_user-custom", $ZHONGFRAMEWORK_PARAMETER_USERMODULES_STYLE)): ?>
	.default-graphic-mode .custom-module-style_user-custom-3{
		background:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_3_BG; ?>;
		border-color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_3_BORDER_COLOR; ?>;
		}
	.default-graphic-mode .custom-module-style_user-custom-3,	
	.night-mode .custom-module-style_user-custom-3{	
		border-style:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_3_BORDER_STYLE; ?>;
		border-width:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_3_BORDER_WIDTH; ?>;
		margin:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_3_MARGIN; ?>;
		padding:<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_3_PADDING; ?>;
		}
	<?php if(ZHONGFRAMEWORK_PARAMETER_ENABLE_CUSTOM_USER_MODULE_STYLE_3_TEXT_COLOR=="custom"): ?>	
	.default-graphic-mode .custom-module-style_user-custom-3,
	.default-graphic-mode .custom-module-style_user-custom-3 h1,
	.default-graphic-mode .custom-module-style_user-custom-3 h2,
	.default-graphic-mode .custom-module-style_user-custom-3 h3,
	.default-graphic-mode .custom-module-style_user-custom-3 h4,
	.default-graphic-mode .custom-module-style_user-custom-3 h5,
	.default-graphic-mode .custom-module-style_user-custom-3 h6{
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_3_TEXT_COLOR; ?>;
		}
		<?php endif; ?>
	<?php if(ZHONGFRAMEWORK_PARAMETER_ENABLE_CUSTOM_USER_MODULE_STYLE_3_LINKS_COLOR=="custom"): ?>	
	.default-graphic-mode .custom-module-style_user-custom-3 a{
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_3_LINKS_COLOR; ?>;
		}
	.default-graphic-mode .custom-module-style_user-custom-3 a:hover,
	.default-graphic-mode .custom-module-style_user-custom-3 a:focus,
	.default-graphic-mode .custom-module-style_user-custom-3 a:active{
		color:#<?php echo ZHONGFRAMEWORK_PARAMETER_CUSTOM_USER_MODULE_STYLE_3_LINKS_COLOR_HOVER; ?>;
		}
		<?php endif; ?>
	<?php endif; ?>
<?php

?>