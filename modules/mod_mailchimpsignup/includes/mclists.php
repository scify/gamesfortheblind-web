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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFormFieldMclists extends JFormField {

    public function getInput() {
        jimport('joomla.filesystem.file');
        $mainframe = JFactory::getApplication();
        if (!JFile::exists(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/libraries/MCAPI.class.php')) {
            $mainframe->enqueueMessage(JText::_('JM_PLEASE_INSTALL_JOOMLAMAILER'), 'error');
            $mainframe->redirect('index.php');
        }

        jimport('joomla.plugin.plugin');
        require_once(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/libraries/MCAPI.class.php');
        require_once(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/helpers/JoomlamailerMC.php');
        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $MCapi = $params->get('params.MCapi');
        $JoomlamailerMC = new JoomlamailerMC();

        if (!$MCapi || !$JoomlamailerMC->pingMC()) {
            $mainframe->enqueueMessage(JText::_('APIKEY ERROR'), 'error');
            $mainframe->redirect('index.php?option=com_joomailermailchimpintegration&view=main');
        }

        $api = new joomlamailerMCAPI($MCapi);
        $lists = $api->lists();
        $options = array();
        $options[] = array(
            'id' => '',
            'name' => '-- ' . JText::_('JM_PLEASE_SELECT_A_LIST') . ' --'
        );
        foreach ($lists as $list) {
            $options[] = array(
                'id' => $list['id'],
                'name' => $list['name']
            );
        }

        $attribs = 'onchange="submitbutton(\'module.apply\')"';
        if (count($options)) {
            return JHtml::_('select.genericlist', $options, 'jform[params][listid]', $attribs, 'id', 'name', $this->value, $this->id);
        }

        return '';
    }
}
