<?php
/**
 * Item FormField
 * @package JoomShareBarPro
 * @Copyright (C) 2013 JooMarketer.com
 * @ All rights reserved
 * @ Joomla! is Free Software
 * @ Released under GNU/GPL v3.0 License : hhttp://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');
class JFormFieldItem extends JFormField {
    protected $type = 'Item';
    protected function getInput() {
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JURI::root().$this->element['path'].'style.css');
        return null;
    }
}
