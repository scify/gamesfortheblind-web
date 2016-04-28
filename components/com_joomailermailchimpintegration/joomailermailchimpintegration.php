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

// register classes to make sure we are using ours in case of naming conflicts
JLoader::register('jmModel', JPATH_COMPONENT . '/models/jmModel.php', true);
JLoader::register('jmView', JPATH_COMPONENT . '/views/jmView.php', true);
JLoader::register('jmController', JPATH_COMPONENT . '/controllers/jmController.php', true);

// Require the MC base file
require_once(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/libraries/MCAPI.class.php');
require_once(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/helpers/MCerrorHandler.php');
require_once(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/helpers/JoomlamailerMC.php');

// Include the css file
if (JRequest::getVar('format') != 'raw') {
    $document = JFactory::getDocument();
    $document->addStyleSheet('media/com_joomailermailchimpintegration/frontend/css/default.css', 'text/css', 'screen');
}

// Require the base controller
require_once(JPATH_COMPONENT . '/controller.php');

// Require specific controller if requested
if ($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT . '/controllers/' . $controller . '.php';
    if (file_exists($path)) {
       require_once($path);
    } else {
       $controller = '';
    }
}

// Create the controller
$classname = 'joomailermailchimpintegrationController' . $controller;
$controller = new $classname();

// Perform the Request task
$controller->execute(JRequest::getVar('task'));

// Redirect if set by the controller
$controller->redirect();
