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

class JFormFieldLists extends JFormField {

    public function getInput()	{
        $app = JFactory::getApplication();

        jimport('joomla.filesystem.file');
        if (!JFile::exists(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/libraries/MCAPI.class.php')) {
            $app->enqueueMessage(JText::_('PLG_USER_JOOMLAMAILER_INSTALL_JOOMLAMAILER'), 'error');
            $app->redirect('index.php?option=com_plugins');
        } else {
            require_once(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/libraries/MCAPI.class.php');
            require_once(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/helpers/JoomlamailerMC.php');
            $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
            $MCapi = $params->get('params.MCapi');
            $JoomlamailerMC = new JoomlamailerMC();
            if (!$MCapi || !$JoomlamailerMC->pingMC()) {
                $app->enqueueMessage(JText::_('PLG_USER_JOOMLAMAILER_INVALID_API_KEY'), 'error');
                $app->redirect('index.php?option=com_joomailermailchimpintegration&view=main');
            }

            $api = new joomlamailerMCAPI($MCapi);
            $lists = $api->lists();

            $options = array(array('id' => '', 'name' => '-- ' . JText::_('PLG_USER_JOOMLAMAILER_PLEASE_SELECT_A_LIST') . ' --'));
            foreach ($lists as $list) {
                $options[] = array('id' => $list['id'], 'name' => $list['name']);
            }

            $attribs = 'onchange="Joomla.submitbutton(\'plugin.apply\')"';

            return JHtml::_('select.genericlist', $options, 'jform[params][listid]', $attribs, 'id', 'name',
                $this->value, $this->id);
        }
    }
}
