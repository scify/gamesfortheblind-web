<?php
/*------------------------------------------------------------------------
# JoomShaper Accordion Module by JoomShaper.com
# ------------------------------------------------------------------------
# author    JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2012 JoomShaper.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomshaper.com
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.framework', true);
$uniqid 			= $module->id;
$document 			= JFactory::getDocument();
$style 				= $params->get('sp_style','style1');
$opacity 			= $params->get('opacity',1) ? "true" : "false";
$hidefirst			= $params->get('hidefirst');
$showauthor			= $params->get('showauthor');
$showdate			= $params->get('showdate');
$date_format		= $params->get('date_format');
$showreadon			= $params->get('showreadon');

$document->addStyleSheet(JURI::base(true) . '/modules/mod_sp_accordion/style/' . $style . '.css', 'text/css' );
// Include the syndicate functions only once
require_once (dirname(__FILE__).'/helper.php');
$list = modSPAccordionHelper::getList($params);
require(JModuleHelper::getLayoutPath('mod_sp_accordion'));