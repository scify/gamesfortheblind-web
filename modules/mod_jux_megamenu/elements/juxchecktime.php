<?php
/**
 * @version		$Id$
 * @author		JoomlaUX
 * @package		Joomla.Site
 * @subpackage	mod_jux_megamenucss3
 * @copyright	Copyright (C) 20013 - 20115 JoomlaUX. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');
 
JFormHelper::loadFieldClass('hidden');
class JFormFieldJUXChecktime extends JFormFieldHidden {
	
	protected $type = 'juxchecktime';
	
	protected function getInput()
	{
		$valueArr = $this->value;
		$html = array();
		$html[] = '<input type="hidden" data-time ='.strtotime(date('Y-m-d H:i:s')).'  name="' . $this->name . '" id="' . $this->id . '" value="'
		. strtotime(date('Y-m-d H:i:s')) .'" />';
		
		return implode($html);
	}
	
	protected function getLabel(){
		return '';
	}
	
}