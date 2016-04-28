<?php
/**
 * @package            JUserTube
 * @version            8.1
 * @author             Md. Afzal Hossain <afzal.csedu@gmail.com>
 * @link               http://www.srizon.com
 * @copyright          Copyright 2012 Md. Afzal Hossain All Rights Reserved
 * @license            http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
if (!class_exists('SrizonResourceLoader')) {
	class SrizonResourceLoader {
		protected static $jquery_loaded = false;
		protected static $mag_popup_loaded = false;
		protected static $collage_plus_loaded = false;
		protected static $elastislide_loaded = false;
		protected static $srizon_custom_js_loaded = false;
		protected static $srizon_custom_css_loaded = false;

		public static function get_joomla_version() {
			if (version_compare(JVERSION, '3', 'l')) {
				return 2;
			} else return 3;
		}

		public static function load_jquery() {
			if (self::$jquery_loaded) return;
			if (self::get_joomla_version() == 3) {
				JHtml::_('jquery.framework');
			} else {
				$jdoc = JFactory::getDocument();
				$jdoc->addScript(JURI::base(true).'/media/srizon/js/jquery.min.js');
				$jdoc->addScript(JURI::base(true).'/media/srizon/js/jquery-noconflict.js');
			}
			self::$jquery_loaded = true;
		}

		public static function load_mag_popup() {
			if (self::$mag_popup_loaded) return;
			self::load_srizon_custom_js();
			$jdoc = JFactory::getDocument();
			$jdoc->addScript(JURI::base(true).'/media/srizon/js/mag-popup.js');
			$jdoc->addStyleSheet(JURI::base(true).'/media/srizon/css/mag-popup.min.css');
			self::$mag_popup_loaded = true;
		}

		public static function load_collage_plus() {
			if (self::$collage_plus_loaded) return;
			self::load_srizon_custom_js();
			$jdoc = JFactory::getDocument();
			$jdoc->addScript(JURI::base(true).'/media/srizon/js/jquery.collagePlus.min.js');
			self::$collage_plus_loaded = true;
		}

		public static function load_custom_script($script) {
			$jdoc = JFactory::getDocument();
			$jdoc->addScriptDeclaration($script);
		}

		public static function load_srizon_custom_js() {
			if (self::$srizon_custom_js_loaded) return;
			self::load_jquery();
			$jdoc = JFactory::getDocument();
			$jdoc->addScript(JURI::base(true).'/media/srizon/js/srizon.custom.min.js');
			self::$srizon_custom_js_loaded = true;
		}

		public static function load_srizon_custom_css() {
			if (self::$srizon_custom_css_loaded) return;
			$jdoc = JFactory::getDocument();
			$jdoc->addStyleSheet(JURI::base(true).'/media/srizon/css/srizon.custom.min.css');
			self::$srizon_custom_css_loaded = true;
		}

		public static function load_elastislide() {
			if (self::$elastislide_loaded) return;
			$jdoc = JFactory::getDocument();
			$jdoc->addScript(JURI::base(true).'/media/srizon/js/modernizr.js');
			self::load_srizon_custom_js();
			$jdoc->addScript(JURI::base(true).'/media/srizon/js/jquery.elastislide.min.js');
			$jdoc->addStyleSheet(JURI::base(true).'/media/srizon/css/elastislide.min.css');
			self::$elastislide_loaded = true;
		}
	}
}
