<?php
/**
 * jQuery loading plugin
 *
 * @version		1.5.0
 * @author		Martin Rasser, Elovaris Applications
 * @copyright	Copyright (C) 2009 Elovaris Applications. All rights reserved.
 * @license		MIT license (http://www.opensource.org/licenses/mit-license.php) or GNU/GPL (http://www.gnu.org/licenses/gpl-2.0.html)
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );

class plgSystemJquery extends JPlugin {
	
	static $jQueryVersion;
	static $optimizedMode;
	
	function plgSystemJquery(& $subject, $config) {
		parent::__construct($subject, $config);
		
		plgSystemJquery::$jQueryVersion = $this->params->get('version', '1.8.3' );
		plgSystemJquery::$optimizedMode = $this->params->get('optimized', '0' );
	}
	
	function onAfterDispatch() {
		
		$doc = JFactory::getDocument();
		
		// Stop if type is not HTML
		if ($doc->getType() != "html") return;
		
		// Add jquery to the scripts
		$doc->addScript( plgSystemJquery::getFileFolder() . plgSystemJquery::getjQueryFilename() );
		$doc->addScript( plgSystemJquery::getFileFolder() . 'no_conflict.js');
		
		// Get head data
		$headdata = $doc->getHeadData();
		
		// Take the scripts part
		$scripts = $headdata['scripts'];		
		
		// Reverse the array so we can pick jquery easily
		$revscripts = array_reverse($scripts,true);
		
		// Take the jquery field
		$keys = array_keys($revscripts);
		$jQueryKey = $keys[1];
				
		// Reconstruct the script array with jquery as first
		$newscripts[$jQueryKey]  = $revscripts[$jQueryKey];
		
		// If we are in optimized mode, put the noconflict file in place, otherwise remove it from the scripts
		if (plgSystemJquery::$optimizedMode == 0) {
			$noConflictKey = $keys[0];
			$noConflictScript = $revscripts[$noConflictKey];
			$newscripts[$noConflictKey]  = $noConflictScript;
		}
		else {
			array_pop($scripts);
		}
		
		foreach ($scripts as $path=>$type) {
			$newscripts[$path] = $type;
		}
		
		// Set the new head data
		$doc->setHeadData (array('scripts'=>$newscripts));
		
	}
	
	function getFileFolder() {
		
		// Get Joomla version
		jimport('joomla.version');
		$jversion = new JVersion();
		
		// Get path for Joomla 1.5 or Joomla > 1.5
		if ($jversion->getShortVersion() >= 1.6) {
			$path = 'plugins/system/jquery/jquery/';
		}
		else {
			$path = 'plugins/system/jquery/';
		}
		$app = JFactory::getApplication();
		// Prepend ../ if we're in the backend
		if($app->isAdmin()) {
			$path = '../'.$path;
		}
		
		return $path;
		
	}
	
	function getjQueryFilename() {
		// Get parameters
		$version = plgSystemJquery::$jQueryVersion;
		$optimized = plgSystemJquery::$optimizedMode;
		
		$config = JFactory::getConfig();
		
		if (method_exists($config,'getValue')) {
			$debug = $config->getValue('config.debug',0);
		}
		else {
			$debug = $config->get('config.debug',0);
		}
		
		// Optimized or raw minified version
		if ($optimized)	$file = 'jquery.php?version='.$version . ( ( $debug ) ? '&debug=1':'' );
		else $file = 'jquery-'.$version.'.' .  ( ( $debug ) ? 'src':'min' ) . '.js';
		
		return $file;
	}
	
}
