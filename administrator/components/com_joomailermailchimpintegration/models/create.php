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

class joomailermailchimpintegrationModelCreate extends jmModel {

    public static $sefLinks = array();

    public function __construct(){
        parent::__construct();
    }

    public function getK2Installed() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true)
            ->select($db->qn('extension_id'))
            ->from($db->qn('#__extensions'))
            ->where($db->qn('type') . ' = ' . $db->q('component'))
            ->where($db->qn('element') . ' = ' . $db->q('com_k2'))
            ->where($db->qn('enabled') . ' = ' . $db->q(1));
        $db->setQuery($query);
        $k2Installed = $db->loadResult();

        return ($k2Installed) ? true : false;
    }

    public function getCategories() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true)
            ->select($db->qn(array('id', 'title', 'parent_id', 'level'), array('cid', 'ctitle', 'parent_id', 'level')))
            ->from($db->qn('#__categories'))
            ->where($db->qn('extension') . ' = ' . $db->q('com_content'))
            ->order('lft');
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    public function getK2cat($catid = 0) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true)
            ->select($db->qn(array('id', 'name')))
            ->from($db->qn('#__k2_categories'));
        if ($catid > 0) {
            $query->where($db->qn('id') . ' = ' . $db->q($catid));
        }
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    public function getMClists() {
        return $this->getModel('lists')->getLists();
    }

    public function getMergeTags() {
        $result = array();
        foreach ($this->getMClists() as $list) {
            $merges = $this->getModel('lists')->getListMergeVars($list['id']);
            if (count($merges)) {
                $result[$list['name']] = $merges;
            }
        }

        return $result;
    }

    public function getFolders() {
        return $this->getModel('campaignlist')->getFolders();
    }

    public function createFolder($folderName) {
        // check if a folder with the given name exists
        $folders = $this->getFolders();
        foreach ($folders as $folder) {
            if ($folder['name'] == $folderName) {
                return $folder['folder_id'];
            }
        }

        // create new folder and return folder_id
        return $this->getMcObject()->createFolder($folderName);
    }

    public static function getSefLink($data, $component = 'com_content', $view = '') {
        if (is_string($data)) {
            $data = array($data);
        }

        $key = implode('', $data);

        if (!isset(self::$sefLinks[$component][$key])) {
            $config = JFactory::getConfig();
            $db = JFactory::getDBO();
            $app = JFactory::getApplication();
            $router = $app::getRouter('site', array('mode' => $config->get('sef')));

            switch ($component) {
                case 'com_content':
                    $link = 'index.php?option=com_content&view=article&id=%d';
                    break;
                case 'com_k2':
                    $link = 'index.php?option=com_k2&view=item&layout=item&id=%d';
                    break;
                case 'com_virtuemart':
                    $link = 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=%d&virtuemart_category_id=%d';
                    break;
                case 'com_community':
                    if ($view == 'profile') {
                        $link = 'index.php?option=com_community&view=profile&userid=%d';
                    } else if ($view == 'discussion') {
                        $link = 'index.php?option=com_community&view=groups&task=viewdiscussion&groupid=%d&topicid=%d';
                        $link = 'index.php?option=com_community&view=groups&task=viewdiscussion&groupid=%d&topicid=%d';
                    }
            }
            $link = vsprintf($link, $data);

            // check if we have a menu item pointing to this article
            $query = $db->getQuery(true)
                ->select($db->qn('id'))
                ->from($db->qn('#__menu'))
                ->where($db->qn('link') . ' = ' . $db->q($link));
            $db->setQuery($query);
            $itemId = $db->loadResult();

            // use first jomsocial menu we can find
            if (!$itemId && $component == 'com_community') {
                $query = 'SELECT ' . $db->qn('id') . ' FROM ' . $db->qn('#__menu') . ' WHERE '
                        . $db->qn('link') . ' LIKE ' . $db->q('%com_community%')
                        . 'AND ' . $db->qn('published') . '=' . $db->q(1) . ' '
                        . 'AND ' . $db->qn('menutype') . '!=' . $db->q($config->get('toolbar_menutype')) . ' '
                        . 'AND ' . $db->qn('type') . '=' . $db->q('component');
                $db->setQuery($query);
                $res = $db->loadResult();

                if ($res) {
                    $link .= '&Itemid=' . $res;
                }
            }

            if ($router->getMode() == JROUTER_MODE_SEF) {
                if ($itemId && strpos($link, 'Itemid=') === false) {
                    $link = 'index.php?Itemid=' . $itemId;
                }

                $link = str_replace('/administrator', '', $router->build(JURI::root() . $link));
            } else {
                $link = JUri::root() . $link;
                if ($itemId) {
                    $link .= '&Itemid=' . $itemId;
                }
            }

            self::$sefLinks[$component][$key] = JFilterOutput::ampReplace(htmlspecialchars($link));
        }

        return self::$sefLinks[$component][$key];
    }
}
