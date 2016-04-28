<?php

/**
 * @copyright	Copyright (C) 2015 Cédric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * http://www.template-creator.com
 * @license		GNU/GPL
 * */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.event.plugin');

class plgSystemScrolltock extends JPlugin {

	function plgSystemScrolltock(&$subject, $params) {
		parent::__construct($subject, $params);
	}

	public function onBeforeRender() {
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$doctype = $doc->getType();
		$input = new JInput();

		// si pas en frontend, on sort
		if ($app->isAdmin()) {
			return false;
		}

		// si pas HTML, on sort
		if ($doctype !== 'html') {
			return;
		}

		if (version_compare(JVERSION, '3') >= 1 ) { 
			JHTML::_('jquery.framework', true);
		} else {
//			$jquerycall = "\n\t<script src=\"" . JURI::base(true) . "/plugins/system/scrolltock/assets/jquery.min.js\" type=\"text/javascript\"></script>";
		}

//		$doc->addStyleSheet(JURI::base(true) . "/plugins/system/scrolltock/assets/scrolltock.css");
		// charge les paramètres par défaut
		$this->loadLanguage();
		$plugin = JPluginHelper::getPlugin('system', 'scrolltock');
		$pluginParams = new JRegistry($plugin->params);
		$fxduration = $pluginParams->get('fxduration', '1000');
		$offsety = $pluginParams->get('offsety', '0');
		$activatetotop = $pluginParams->get('activatetotop', '1');
		$totop_startoffset = $pluginParams->get('totop_startoffset', '100');

		// for the scroll to top button
		$scrolltotop = "$(document.body).append('<a href=\"#\" class=\"scrollToTop\">" . JText::_('PLG_SCROLLTOCK_SCROLL_TO_TOP') . "</a>');
					//Check to see if the window is top if not then display button
					$(window).scroll(function(){
						if ($(this).scrollTop() > " . (int) $totop_startoffset . ") {
							$('.scrollToTop').fadeIn();
						} else {
							$('.scrollToTop').fadeOut();
						}
					});

					//Click event to scroll to top
					$('.scrollToTop').click(function(){
						$('html, body').animate({scrollTop : 0},". (int) $fxduration . ");
						return false;
					});";

		// add the script
		$js = "\n\tjQuery(document).ready(function($){";
		if ($activatetotop) {
			$js .= $scrolltotop;
		}
		$js .= "jQuery('.scrollTo').click( function(event) {
					var pageurl = window.location.href.split('#');
					var linkurl = $(this).attr('href').split('#');

					if ( $(this).attr('href').indexOf('#') != 0
						&& ( ( $(this).attr('href').indexOf('http') == 0 && pageurl[0] != linkurl[0] )
						|| $(this).attr('href').indexOf('http') != 0 && pageurl[0] != '" . JUri::base() . "' + linkurl[0].replace('" . JUri::base(true) . "/', '') )
						) {
						// here action is the natural redirection of the link to the page
					} else {
						event.preventDefault();
						$(this).scrolltock();
					}
				});

				$.fn.scrolltock = function() {
					var link = $(this);
					var page = jQuery(this).attr('href');
					var pattern = /#(.*)/;
					var targetEl = page.match(pattern);
					if (! targetEl.length) return;
					if (! jQuery(targetEl[0]).length) return;

					// close the menu hamburger
					if (link.parents('ul').length) {
						var menu = $(link.parents('ul')[0]);
						if (menu.parent().find('> .mobileckhambuger_toggler').length && menu.parent().find('> .mobileckhambuger_toggler').attr('checked') == 'checked') {
							menu.animate({'opacity' : '0'}, function() { menu.parent().find('> .mobileckhambuger_toggler').attr('checked', false); menu.css('opacity', '1'); });
						}
					}
					var speed =  ". (int) $fxduration . ";
					jQuery('html, body').animate( { scrollTop: jQuery(targetEl[0]).offset().top + ". (int) $offsety . " }, speed, scrolltock_setActiveItem() );
					return false;
				}
				// Cache selectors
				var lastId,
				baseItems = jQuery('.scrollTo');
				// Anchors corresponding to menu items
				scrollItems = baseItems.map(function(){
					// if (! jQuery(jQuery(this).attr('href')).length) return;
					var pattern = /#(.*)/;
					var targetEl = jQuery(this).attr('href').match(pattern);

						if (targetEl == null ) return;
						if (! targetEl[0]) return;
						if (! jQuery(targetEl[0]).length) return;
						var item = jQuery(targetEl[0]);
					if (item.length) { return item; }
				});
				// Bind to scroll
				jQuery(window).scroll(function(){
						scrolltock_setActiveItem();
					});
				
					function scrolltock_setActiveItem() {
				   // Get container scroll position
				   var fromTop = jQuery(this).scrollTop()- (". (int) $offsety . ") + 2;
				   
				   // Get id of current scroll item
				   var cur = scrollItems.map(function(){
					 if (jQuery(this).offset().top < fromTop)
					   return this;
				   });
				   // Get the id of the current element
				   cur = cur[cur.length-1];
				   var id = cur && cur.length ? cur[0].id : '';
				   
				   if (lastId !== id) {
					   lastId = id;
					   // Set/remove active class
							baseItems.parent().parent().find('.active').removeClass('active');
					   baseItems
						 .parent().removeClass('active')
						 .end().filter('[href$=#'+id+']').parent().addClass('active');
				   }                   
					}
			}); // end of dom ready

			jQuery(window).load(function(){
				// loop through the scrolling links to check if the scroll to anchor is needed on the page load
				jQuery('.scrollTo').each( function() {
					var pageurl = window.location.href;
					var linkurl = jQuery(this).attr('href');
					var pattern = /#(.*)/;
					var targetLink = linkurl.match(pattern);
					var targetPage = pageurl.match(pattern);

					if (targetLink == null ) return;
					if (targetPage == null ) return;
					if (! targetLink.length) return;
					if (! jQuery(targetLink[0]).length) return;

					if (jQuery(targetPage[0]).length && targetLink[0] == targetPage[0]) {
						jQuery(this).scrolltock();
					}
				});
			});";

		$doc->addScriptDeclaration($js);
		
		$css= $this->addCss() . "
				.scrollToTop:hover{
					text-decoration:none;
				}";
		$doc->addStyleDeclaration($css);

		return;
	}

	private function addCss() {
//		$plugin = JPluginHelper::getPlugin('system', 'scrolltock');
//		$pluginParams = new JRegistry($plugin->params);

		$css = '.scrollToTop {
			padding:10px; 
			text-align:center; 
			font-weight: bold;
			text-decoration: none;
			position:fixed;
			bottom: 20px;
			right: 20px;
			display:none;
			width: 100px;
			height: 100px;
			background: url(' . JURI::base(true) . '/plugins/system/scrolltock/images/arrow_up.png) center center no-repeat;'
			. " } ";

		return $css;
	}
}