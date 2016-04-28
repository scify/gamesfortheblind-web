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

jimport('joomla.error.error');
jimport('joomla.html.parameter');
jimport('joomla.application.component.controller');
jimport('joomla.application.component.helper');

if (version_compare(JVERSION, '3.0', 'ge')) {
    class jmController extends JControllerLegacy {
        public function display($cachable = false, $urlparams = array()) {
            parent::display($cachable, $urlparams);
        }
    }
} else {
    class jmController extends JController {
        public function display($cachable = false, $urlparams = false) {
            parent::display($cachable, $urlparams);
        }
    }
}
