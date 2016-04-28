<?php
/**
* Copyright (C) 2015  freakedout (www.freakedout.de)
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// no direct access
defined('_JEXEC') or die('Restricted Access');


class JFormFieldPassword extends JFormField {

    public function getInput() {
        unset($_SESSION['MCping']);

        $value = htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8');
        $class = (isset($this->element['class']) ? 'class="' . $this->element['class'] . '"' : 'class="text_area"');
        $size = (isset($this->element['size']) ? 'size="' . $this->element['size'] . '"' : '');
        $onchange = (isset($this->element['onchange']) ? 'onchange="' . $this->element['onchange'] . '"' : '');

        $html = '<input type="password" name="' . $this->name . '" id="' . $this->id . '" value="' . $value . '" ' .
            $class . ' ' . $size . ' ' . $onchange . ' />';

        $document = JFactory::getDocument();
        $document->addScript(JURI::root() . 'media/com_joomailermailchimpintegration/backend/js/joomlamailer.js');

        return $html;
    }
}
