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

class JFormFieldInterests extends JFormField {

    public function getInput() {
        jimport('joomla.filesystem.file');
        if (!JFile::exists(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/libraries/MCAPI.class.php')) {
            $app = JFactory::getApplication();
            $app->enqueueMessage(JText::_('PLG_USER_JOOMLAMAILER_INSTALL_JOOMLAMAILER'), 'error');
            $app->redirect('index.php?option=com_plugins');
        } else {
            $listid = $this->form->getValue('listid', 'params');
            require_once(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/libraries/MCAPI.class.php');
            $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
            $MCapi = $params->get('params.MCapi');
            $api = new joomlamailerMCAPI($MCapi);
            $interests = $api->listInterestGroupings($listid);

            if ($interests) {
                $options = array();
                foreach ($interests as $interest) {
                    if ($interest['form_field'] != 'hidden') {
                        $options[] = array('name' => $interest['name']);
                    }
                }
                if (count($options)) {
                    return JHtml::_('select.genericlist', $options, 'jform[params][interests][]', 'multiple="multiple"',
                        'name', 'name', $this->value, $this->id);
                }
            }

            return JText::_('PLG_USER_JOOMLAMAILER_NO_INTEREST_GROUPS');
        }
    }
}
