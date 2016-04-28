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
defined('_JEXEC') or die;

require_once JPATH_SITE.'/components/com_content/helpers/route.php';
jimport( 'joomla.plugin.helper');
JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models', 'ContentModel');

abstract class modSPAccordionHelper
{		
	public static function getList($params)
	{
		$app		= JFactory::getApplication();
		$db			= JFactory::getDbo();

		// Get an instance of the generic articles model
		$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

		// Set application parameters in model
		$appParams 	= $app->getParams();
		$model->setState('params', $appParams);

		// Set the filters based on the module params
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('count', 5));
		$model->setState('filter.published', 1);
		
		// Access filter
		$access 	= !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$model->setState('filter.access', $access);	
		
		// Category filter
		$model->setState('filter.category_id', $params->get('catid', array()));		

		// User filter
		$userId = JFactory::getUser()->get('id');
		switch ($params->get('user_id'))
		{
			case 'by_me':
				$model->setState('filter.author_id', (int) $userId);
				break;
			case 'not_me':
				$model->setState('filter.author_id', $userId);
				$model->setState('filter.author_id.include', false);
				break;

			case '0':
				break;

			default:
				$model->setState('filter.author_id', (int) $params->get('user_id'));
				break;
		}		

		// Filter by language
		$model->setState('filter.language', $app->getLanguageFilter());	
		
		//  Featured switch
		switch ($params->get('show_featured'))
		{
			case '1':
				$model->setState('filter.featured', 'only');
				break;
			case '0':
				$model->setState('filter.featured', 'hide');
				break;
			default:
				$model->setState('filter.featured', 'show');
				break;
		}
		
		// Set ordering
		$order_map = array(
			'm_dsc' => 'a.modified DESC, a.created',
			'mc_dsc' => 'CASE WHEN (a.modified = '.$db->quote($db->getNullDate()).') THEN a.created ELSE a.modified END',
			'c_dsc' => 'a.created',
			'p_dsc' => 'a.publish_up',
		);

		$ordering 			= JArrayHelper::getValue($order_map, $params->get('ordering'), 'a.ordering');
		$ordering_direction	= $params->get('ordering_direction', 'ASC');

		$model->setState('list.ordering', $ordering);
		$model->setState('list.direction', $ordering_direction);

		$items = $model->getItems();

		foreach ($items as &$item) {
			$item->slug = $item->id.':'.$item->alias;
			$item->catslug 		= $item->catid.':'.$item->category_alias;
			$author 			= JFactory::getUser($item->created_by);
			$item->author 		= $author->name;	
			$item->created 		= $item->created;
			$item->hits 		= $item->hits;
			$item->title 		= htmlspecialchars( self::getText($item->title, $params->get('limit',0)) );
			$item->introtext 	= JHtml::_('content.prepare', $item->introtext);
			$item->link 	 	= JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
		}

		return $items;
	}

	private static function getText($text, $limit) {
		if ($limit!=0)
			$text=utf8_substr($text,0,$limit);
		
		return $text;
	}
}