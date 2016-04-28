<?php
/**
 * Cb FormField
 * @package JoomShareBarPro
 * @Copyright (C) 2013 JooMarketer.com
 * @ All rights reserved
 * @ Joomla! is Free Software
 * @ Released under GNU/GPL v3.0 License : hhttp://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');
class JFormFieldCb extends JFormField
{
    protected $type = 'Cb';
    protected function getInput()
    {
        $checked = ((string) $this->element['value'] == $this->value) ? ' checked="checked"' : '';

        $onclick = $this->element['onclick'] ? ' onclick="' . (string) $this->element['onclick'] . '"' : '';

        return '<div class="iphone-toggle-buttons"><ul><li style="border: 0px; padding: 0px;"><label for="' . $this->id . '"><input type="checkbox" name="' . $this->name . '" id="' . $this->id . '" value="'
            . htmlspecialchars((string) $this->element['value'], ENT_COMPAT, 'UTF-8') . '"' . $checked . $onclick .'/><span>checkbox 0</span></label></li></ul></div>';
    }
}