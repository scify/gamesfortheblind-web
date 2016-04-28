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

jimport('joomla.application.component.model');

if (version_compare(JVERSION, '3.0', 'ge')) {
    class jmModelHelper extends JModelLegacy {
        public function __construct($config = array()) {
            parent::__construct($config);
        }
    }
} else {
    class jmModelHelper extends JModel {
        public function __construct($config = array()) {
            parent::__construct($config);
        }
    }
}

class jmModel extends jmModelHelper {

    public static $MC = null;
    public static $cache = array();

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    public function getMcObject() {
        if (!jmModel::$MC) {
            $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
            $MCapi = $params->get('params.MCapi');
            jmModel::$MC = new joomlamailerMCAPI($MCapi);
        }

        return jmModel::$MC;
    }

    public function getModel($model) {
        if (version_compare(JVERSION, '3.0', 'ge')) {
            return JModelLegacy::getInstance($model, 'joomailermailchimpintegrationModel');
        } else {
            return JModel::getInstance($model, 'joomailermailchimpintegrationModel');
        }
    }

    public function cache($cacheGroup) {
        if (!isset(jmModel::$cache[$cacheGroup])) {
            jimport('joomla.cache.cache');
            $cacheOptions = array();
            $cacheOptions['caching'] = true;
            $cacheOptions['lifetime'] = 60;
            $cacheOptions['cachebase'] = JPATH_ADMINISTRATOR . '/cache';
            $cacheOptions['defaultgroup'] = $cacheGroup;

            jmModel::$cache[$cacheGroup] = new JCache($cacheOptions);
        }

        return jmModel::$cache[$cacheGroup];
    }
}