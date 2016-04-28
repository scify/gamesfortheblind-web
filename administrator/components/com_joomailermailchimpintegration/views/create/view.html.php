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

class joomailermailchimpintegrationViewCreate extends jmView {

    public function display($tpl = null) {
        if (!JOOMLAMAILER_CREATE_DRAFTS) {
            $this->app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
            $this->app->redirect('index.php?option=com_joomailermailchimpintegration');
        }

        JToolBarHelper::title(JText::_('JM_NEWSLETTER_CREATE_DRAFT'), $this->getPageTitleClass());

        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $MCapi = $params->get('params.MCapi');
        $JoomlamailerMC = new JoomlamailerMC();

        if (!$MCapi) {
            $user = JFactory::getUser();
            if ($user->authorise('core.admin', 'com_joomailermailchimpintegration')) {
                JToolBarHelper::preferences('com_joomailermailchimpintegration', '350');
            }
        } else if (!$JoomlamailerMC->pingMC()) {
            $user = JFactory::getUser();
            if ($user->authorise('core.admin', 'com_joomailermailchimpintegration')) {
                JToolBarHelper::preferences('com_joomailermailchimpintegration', '350');
                JToolBarHelper::spacer();
            }
        } else {
            JHTML::_('behavior.modal');
            JHTML::_('behavior.tooltip');

            // Include css/js files
            $document = JFactory::getDocument();
            $document->addScript(JURI::root() . 'media/com_joomailermailchimpintegration/backend/js/joomlamailer.create.js');
            $document->addScript(JURI::root() . 'media/com_joomailermailchimpintegration/backend/js/joomlamailer.preview.js');
            $document->addScript(JURI::root() . 'media/com_joomailermailchimpintegration/backend/js/tablednd.js');
            $document->addScript(JURI::root() . 'media/com_joomailermailchimpintegration/backend/js/sorttable.js');

            if (version_compare(JVERSION, '3.0.0') < 0) {
                $document->addScript(JURI::root() . 'media/com_joomailermailchimpintegration/backend/js/joomlamailer.create_2_5.js');
            }

            // plugin support
            JPluginHelper::importPlugin('joomlamailer');
            $plugins = JDispatcher::getInstance();
            $this->assignRef('plugins', $plugins);

            $db = JFactory::getDBO();
            $query = $db->getQuery(true)
                ->select('enabled')
                ->from('#__extensions')
                ->where($db->qn('type') . ' = ' . $db->q('plugin'))
                ->where($db->qn('element') . ' = ' . $db->q('tinymce'));
            $db->setQuery($query);
            $tinymce = $db->loadResult();

            $editortype = ($tinymce) ? 'tinymce' : 'none';
            $editor = JFactory::getEditor($editortype);
            $this->assignRef('editor', $editor);

            $script = '!function($){
                $(document).ready(function(){
                    joomlamailerJS.create.getIntroContent = function() {
                        return ' . $editor->getContent('intro') . ';
                    }
                    joomlamailerJS.create.onSubmit = function() {' .
                        $editor->save('intro');
                        $submitformJavascript = $plugins->trigger('submitformJavascript');
                        foreach($submitformJavascript as $sj){
                            $script .= $sj;
                        }
            $script.= '}';
                    if (JRequest::getVar('text_only', 0, '', 'int')) {
                         $script .= "$('.create_sidebar').css('display', 'none');";
                    }
            $script.= '});
                }(jQuery);

                var includeComponents = new Object();
                var includeComponentsOptions = new Object();
                var includeComponentsFields = new Object();
                var includeTableofcontent = false;
                var includeTableofcontentComponents = new Object();
                var sidebarElements = new Object();
                var postData = new Object();
                var socialIcons = new Object();';
            $document->addScriptDeclaration($script);

            $categories = $this->get('Categories');
            $this->assignRef('categories', $categories);

            $merge = $this->get('MergeTags');
            $this->assignRef('merge', $merge);

            $K2Installed = $this->get('K2Installed');
            $this->assignRef('K2Installed', $K2Installed);

            if ($K2Installed) {
                $allk2cat = $this->getModel()->getK2Cat();
                $this->assignRef('allk2cat', $allk2cat);
            }

            $folders = $this->get('Folders');
            $unfiled = array(array('folder_id' => 0, 'name' => JText::_('JM_UNFILED')));
            $folder_id = JRequest::getVar('folder_id', 0, '', 'int');
            if ($folders) {
                $folders = array_merge($unfiled, $folders);
            } else {
                $folders = $unfiled;
            }
            $foldersDropDown = JHTML::_('select.genericlist', $folders, 'folder_id', '', 'folder_id', 'name' , $folder_id);
            $this->assignRef('foldersDropDown', $foldersDropDown);

            $user = JFactory::getUser();
            $this->assignRef('user', $user);
        }

        parent::display($tpl);
        require_once(JPATH_COMPONENT . '/helpers/jmFooter.php');
    }
}
