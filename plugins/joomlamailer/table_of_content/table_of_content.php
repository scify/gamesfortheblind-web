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
defined('_JEXEC') or die('Restricted access');

class plgJoomlamailerTable_of_content extends JPlugin {

    private $id = 'table_of_content';

    public function getSidebarElement() {
        // load language files. include en-GB as fallback
        $jlang = JFactory::getLanguage();
        $jlang->load('plg_joomlamailer_table_of_content', JPATH_ADMINISTRATOR, 'en-GB', true);
        $jlang->load('plg_joomlamailer_table_of_content', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
        $jlang->load('plg_joomlamailer_table_of_content', JPATH_ADMINISTRATOR, null, true);

        $data = array();
        $data[0]['title'] = JText::_('JM_TABLE_OF_CONTENTS');

        $toc  = JRequest::getVar('toc',false, '', 'string');
        $toct = JRequest::getVar('toct',false, '', 'string');
        if (!$toc){  $toc  = JRequest::getVar($this->id, '', 'POST', 'string'); }
        if (!$toct){ $toct = JRequest::getVar($this->id . '_type', '', 'POST', 'string'); }
        $checked = ($toc) ? ' checked="checked"' : '';
        $data[0]['element'] = '<label for="' . $this->id . '">'
            . '<input class="checkbox" type="checkbox" name="' . $this->id . '" id="' . $this->id . '" value="1"' . $checked . ' /> '
            . JText::_('JM_TABLE_OF_CONTENTS_INFO')
            . '</label>';

        $data[1]['title'] = JText::_('JM_ANCHOR_HYPERLINK');
        $checked = ($toct) ? ' checked="checked"' : '';
        $data[1]['element'] = '<label for="' . $this->id . '_type" class="labelNode">'
            . '<input class="checkbox" type="checkbox" name="' . $this->id . '_type" id="' . $this->id . '_type" value="1"' . $checked . ' /> '
            . JText::_('JM_TABLE_OF_CONTENTS_TYPE_INFO')
            . '</label>'
            . '<script type="text/javascript">' . "\n"
            . 'sidebarElements[Object.keys(sidebarElements).length] = "' . $this->id . '";' . "\n"
            . 'sidebarElements[Object.keys(sidebarElements).length] = "' . $this->id . '_type";' . "\n"
            . 'postData["' . $this->id . '"] = "document.getElementById(\'' . $this->id . '\').checked;";' . "\n"
            . 'postData["' . $this->id . '_type"] = "document.getElementById(\'' . $this->id . '_type\').checked;";' . "\n"
            . '</script>';

        return $data;
    }

    public function insert_table_of_content ($sidebarData, $template, $article_titles) {
        $tocCheckbox = $sidebarData[$this->id];
        $tocType = $sidebarData[$this->id . '_type'];
        $templateFolder = JRequest::getVar('template');

        // define abs paths for regex
        $abs_path  = '$1="' . JURI::root() . '$2$3';
        $imagepath = '$1="' . JURI::root() . 'administrator/components/com_joomailermailchimpintegration/templates/' . $templateFolder . '/$2$3';

        // table of contents
        $regex = '!<#tableofcontents#[^>]*>(.*)<#/tableofcontents#>!is';
        preg_match($regex, $template, $tableofcontents);
        if (isset($tableofcontents[0])) {
            $tableofcontents = preg_replace('#(href|src)="([^:"]*)("|(?:(?:%20|\s|[.]|\+)[^"]*"))#i', $imagepath, $tableofcontents[0]);
            $regex = '!<#title_repeater#[^>]*>(.*)<#/title_repeater#>!is';
            preg_match($regex, $template, $titleRepeater);
            $titleRepeater = (isset($titleRepeater[0])) ? $titleRepeater[0] : '';
        } else {
            /*
            // load language files. include en-GB as fallback
            $jlang = JFactory::getLanguage();
            $jlang->load('plg_joomlamailer_table_of_content', JPATH_ADMINISTRATOR, 'en-GB', true);
            $jlang->load('plg_joomlamailer_table_of_content', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
            $jlang->load('plg_joomlamailer_table_of_content', JPATH_ADMINISTRATOR, null, true);
            $response['msg'] = '<div style="border: 2px solid #ff0000; margin:15px 0 5px;padding:10px 15px 12px;">' .
                '<img src="' . JURI::root() . 'media/com_joomailermailchimpintegration/backend/images/warning.png" align="left"/>' .
                '<div style="padding-left: 45px; line-height: 28px; font-size: 14px;">' .
                    JText::_('JM_NO_TOC_PLACEHOLDER') .
                '</div></div>';
            */
            $tableofcontents = $titleRepeater = '';
        }

        // create table of contents
        $toc = '';
        foreach ($article_titles as $art_title) {
            $toc .= str_ireplace('<#article_title#>', $art_title, $titleRepeater);
        }
        $tableofcontents = preg_replace('!<#title_repeater#[^>]*>(.*)<#/title_repeater#>!is', $toc, $tableofcontents);
        $toReplace = array('<#tableofcontents#>', '<#/tableofcontents#>', '<#title_repeater#>', '<#/title_repeater#>');
        $tableofcontents = str_ireplace($toReplace, '', $tableofcontents);

        // insert table of contents
        if ($tocCheckbox && $toc) {
            $tableofcontents = str_ireplace('$' , '\$', $tableofcontents);
            $template = preg_replace('!<#tableofcontents#[^>]*>(.*?)<#/tableofcontents#>!is', $tableofcontents, $template);
        } else {
            $template = preg_replace('!<#tableofcontents#[^>]*>(.*?)<#/tableofcontents#>!is', '', $template);
        }

        return $template;
    }

    public function insert (&$template) {
        $sidebarData = array();
        $sidebarData[$this->id] = JRequest::getVar($this->id, 0, 'post', 'int');
        $sidebarData[$this->id . '_type'] = JRequest::getVar($this->id . '_type', 0, 'post', 'int');
        $article_titles = json_decode(JRequest::getVar('article_titles', '', 'post', 'string', JREQUEST_ALLOWRAW));

        $template = $this->insert_table_of_content($sidebarData, $template, $article_titles);
    }

    public function addPlaceholderToTemplateEditor() {
        // load language files. include en-GB as fallback
        $jlang = JFactory::getLanguage();
        $jlang->load('plg_joomlamailer_table_of_content', JPATH_ADMINISTRATOR, 'en-GB', true);
        $jlang->load('plg_joomlamailer_table_of_content', JPATH_ADMINISTRATOR, $jlang->getDefault(), true);
        $jlang->load('plg_joomlamailer_table_of_content', JPATH_ADMINISTRATOR, null, true);

        $data = array();
        $data['js'] = 'joomlamailerJS.templates.placeholders["' . $this->id . '"] = \'<#tableofcontents#><br /><span class="sideColumnTitle">In this issue</span><ul><#title_repeater#><li><#article_title#></li><#/title_repeater#></ul><br /><#/tableofcontents#>\';';
        $data['checkbox'] = '<input type="checkbox" class="phCb" value="' . $this->id . '" id="' . $this->id . '"/><label for="' . $this->id . '">' . JText::_('JM_TABLE_OF_CONTENTS') . '</label>';

        return $data;
    }
}
