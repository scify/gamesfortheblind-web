<?php
/**
 * Sub FormField
 * @package JoomShareBarPro
 * @Copyright (C) 2013 JooMarketer.com
 * @ All rights reserved
 * @ Joomla! is Free Software
 * @ Released under GNU/GPL v3.0 License : hhttp://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');
class JFormFieldSubsub extends JFormField
{
    protected $type = 'Subsub';
    protected function getInput()
    {
        $text  	= (string) $this->element['text'];
        return '<div class="subHeader sub'.(($text != '') ? ' hasText' : '').'"><span>' . JText::_($text) . '</span></div>';
    }
}