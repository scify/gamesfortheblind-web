<?php
/*------------------------------------------------------------------------
# com_onepage - XIPAT Onepage component
# version: 1.0
# ------------------------------------------------------------------------
# author    Nguyen Huy Kien - www.xipat.com
# copyright Copyright (C) 2013 www.xipat.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.xipat.com
# Technical Support:  http://www.xipat.com/support.html
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', 'com_onepage.category');
$saveOrder	= $listOrder=='ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_onepage&view=items'); ?>" method="post" name="adminForm" id="adminForm">

       <div id="filter-bar" class="btn-toolbar">
    
            <div class="filter-search btn-group pull-left">
                <label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
                <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_ONEPAGE_SEARCH_IN_TITLE'); ?>" />
            </div>    
            <div class="btn-group pull-left">
                <button type="submit" class="btn hasTooltip"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
                <button type="button" class="btn hasTooltip" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
            </div>
            <div class="btn-group pull-right hidden-phone">
                
                <select name="filter_state" class="inputbox" onchange="this.form.submit()">
                    <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
                    <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true);?>
                </select>
                
            </div>   
            <div class="btn-group pull-right">
                <label for="filter_pages" class="element-invisible"><?php echo JText::_('COM_ONEPAGE_FILTER_PAGES');?></label>
                <select name="filter_page" id="filter_page" class="input-medium" onchange="this.form.submit()">
                    <option value=""><?php echo JText::_('COM_ONEPAGE_FILTER_PAGES');?></option>
                    <?php echo JHtml::_('select.options', $this->pages, 'value', 'text', $this->state->get('filter.page'), true);?>   
                </select>
            </div>            
        </div>
        <div class="clearfix"> </div>         
    
        <?php if (empty($this->items)) : ?>
            <div class="alert alert-no-items">
                <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
            </div>
        <?php else : ?>     

	<table class="table table-striped" id="articleList"> 
		<thead>
			<tr>
				<th width="1%" class="nowrap center hidden-phone">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="1%" class="hidden-phone">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', '#__onepage_items.id', $listDirn, $listOrder); ?>
				</th>
                <th>
                    <?php echo JHtml::_('grid.sort',  'COM_ONEPAGE_HEADING_ONEPAGE_TITLE', '#__onepage_items.title', $listDirn, $listOrder); ?>
                </th>				
				<th>
					<?php echo JHtml::_('grid.sort',  'COM_ONEPAGE_HEADING_ONEPAGE_ID', '#__onepage_items.onepage_id', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort',  'COM_ONEPAGE_HEADING_MENU_ID', '#__onepage_items.menu_id', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort',  'COM_ONEPAGE_HEADING_MENU_TYPE', '#__onepage_items.menu_type', $listDirn, $listOrder); ?>
				</th>
				
				<th width="5%">
					<?php echo JHtml::_('grid.sort', 'JSTATUS', 'state', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'ordering', $listDirn, $listOrder); ?>
					<?php if ($canOrder && $saveOrder): ?>
						<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'items.saveorder'); ?>
					<?php endif;?>
				</th>
				
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="13">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$ordering	= ($listOrder == 'ordering');
			$canCreate	= $user->authorise('core.create');
			$canEdit	= $user->authorise('core.edit');
			$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
			$canChange	= $user->authorise('core.edit.state') && $canCheckin;
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td class="center">
                    <?php echo $this->escape($item->id); ?>
				</td>
				
                <td>
                    <?php if ($item->checked_out) : ?>
                        <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'items.', $canCheckin); ?>
                    <?php endif; ?>
                    <?php if ($canEdit) : ?>
                        <a href="<?php echo JRoute::_('index.php?option=com_onepage&task=item.edit&id='.(int) $item->id); ?>">
                            <?php echo $this->escape($item->title_item); ?></a>
                    <?php else : ?>
                            <?php echo $this->escape($item->title_item); ?>
                    <?php endif; ?>
                </td>				
				<td>
					<?php echo $this->escape($item->pagetitle); ?>
				</td>
				<td>
					<?php echo $this->escape($item->menutitle); ?>
				</td>
				<td>
					<?php echo $this->escape($item->menutype); ?>
				</td>
				
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $item->state, $i, 'items.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
				</td>
				<td class="order">
					<?php if ($canChange) : ?>
						<?php if ($saveOrder) : ?>
							<?php if ($listDirn == 'asc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, (@$this->items[$i-1]->catid == $item->catid), 'items.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (@$this->items[$i+1]->catid == $item->catid), 'items.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php elseif ($listDirn == 'desc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, (@$this->items[$i-1]->catid == $item->catid), 'items.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (@$this->items[$i+1]->catid == $item->catid), 'items.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php endif; ?>
						<?php endif; ?>
						<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled; ?> class="text-area-order" />
					<?php else : ?>
						<?php echo $item->ordering; ?>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
    <?php endif; ?> 
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
</form>
