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

// no direct access
defined('_JEXEC') or die('Restricted Access');

class joomailermailchimpintegrationControllerLists extends joomailermailchimpintegrationController {

	public function __construct() {
		parent::__construct();
		$this->registerTask('add' , 'edit');
	}

	public function addUsers() {
		$this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=sync');
	}

	public function edit() {
		JRequest::setVar('view', 'joomailermailchimpintegration');
		JRequest::setVar('layout', 'form');
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

    /*
	function save()
	{
        $listid = JRequest::getVar('id'  ,  0, 'post', 'string');
        $title  = JRequest::getVar('name',  0, 'post', 'string');
        $type   = JRequest::getVar('type',  1, 'post', 'string');

        if ($type == 1) { $confirmOptIn = 'false'; } else { $confirmOptIn = 'true'; }

        $cm  = $this->cm_object();

        $clients   = $cm->userGetClients($api['anyType']);
        $client_id = $clients['anyType']['Client']['ClientID'];

        if ($listid){
        $result = $cm->listUpdate($listid, $title, '', $confirmOptIn, '');
        $action = JText::_('updated');
        } else {
        $result = $cm->listCreate($client_id, $title, '', $confirmOptIn, '');
        $action = JText::_('created');
        }

		if ($result['Result']['Code'] == 0) {
			$msg = JText::_('List').' '.$action;
		} else {
			$msg = JText::_('Error: List Could not be').' '.$action.'!';
		}

		$link = 'index.php?option=com_joomailermailchimpintegration&view=lists';
		$this->setRedirect($link, $msg);
	}// function

	function remove()
	{
        $db	= JFactory::getDBO();
        $cm = $this->cm_object();

        $listid = JRequest::getVar('listid',  0, '', 'string');

        $delete = $cm->listDelete($listid);

        $error = false;
    if (in_array($delete['Result']['Code'], array('0', '100', '101', '252'))) {

				switch($delete['Result']['Code']) {

                    case '0':
						$error = JText::_('List deleted');
						break;

                    case '100':
						$error = JText::_('JM_INVALID_API_KEY');
						break;

                    case '101':
						$error = JText::_('JM_INVALID_LISTID');
						break;

                    case '252':
						$error = JText::_('LIST HAS CAMPAIGNS');

						$drafts = $this->getModel()->getAssociatedDrafts($listid);
						$error .= ' '.JText::_('Associated campaign drafts').': '.$drafts;

						break;

				}

			}

        $query = 'DELETE FROM #__joomailermailchimpintegration WHERE listid = "'.$listid.'" ';
        $db->setQuery($query);
        $db->execute();

		if ($delete['Result']['Code'] != '0') {
			$msg = JText::_('Error').': '.$error;
		} else {
			$msg = $error;
		}

		$this->setRedirect('index.php?option=com_joomailermailchimpintegration&view=lists', $msg);
	}
    */

    public function cancel() {
		$this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=lists');
	}
}
