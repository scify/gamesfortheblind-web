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
**/

defined('JPATH_PLATFORM') or die;

class JFormFieldFields extends JFormField {

    public function getInput() {
        jimport( 'joomla.filesystem.file' );
        if (!JFile::exists(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/libraries/MCAPI.class.php')) {
            $app = JFactory::getApplication();
            $app->enqueueMessage(JText::_('PLG_USER_JOOMLAMAILER_INSTALL_JOOMLAMAILER'), 'error');
            $app->redirect('index.php?option=com_plugins');
        } else {
            $listid = $this->form->getValue('listid', 'params');
            require_once(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/libraries/MCAPI.class.php');
            $params = JComponentHelper::getParams( 'com_joomailermailchimpintegration' );
            $MCapi = $params->get('params.MCapi');
            $api = new joomlamailerMCAPI($MCapi);
            $fields = $api->listMergeVars($listid);

            if ($fields) {
                $options = array();
                foreach ($fields as $field) {
                    if (!in_array($field['tag'], array('EMAIL', 'FNAME', 'LNAME', 'SIGNUPAPI'))) {
                        $options[]= array('tag' => $field['tag'], 'name' => $field['name']);
                    }
                }

                if (count($options)){
                    return JHtml::_('select.genericlist', $options, 'jform[params][fields][]', 'multiple="multiple"',
                        'tag', 'name', $this->value, $this->id);
                }
            }

            return JText::_('PLG_USER_JOOMLAMAILER_NO_MERGE_VARS');
        }
    }
}