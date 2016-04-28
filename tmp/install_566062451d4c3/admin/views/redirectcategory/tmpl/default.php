<?php
/**
* @copyright   (C) 2010 iJoomla, Inc. - All rights reserved.
* @license  GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html) 
* @author  iJoomla.com webmaster@ijoomla.com
* @url   http://www.ijoomla.com/licensing/
* the PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript  
* are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0 
* More info at http://www.ijoomla.com/licensing/
*/

defined('_JEXEC') or die('Restricted Access');
JHtml::_('jquery.framework', false);
JHTML::_('behavior.modal');
JHtml::_('bootstrap.modal');
JHtml::_('dropdown.init');

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_ijoomla_seo/css/seostyle.css");
$document->addScript("components/com_ijoomla_seo/javascript/scripts.js");

$search = JRequest::getVar("search", "");
$filter_status = JRequest::getVar("filter_status", "-1");
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	
	<div class="row-fluid">
		 <h2 class="pub-page-title"><?php echo JText::_("COM_IJOOMLA_SEO_REDIRECT_MANAGER"); ?></h2>
		 <a class="modal seo_video pull-right" rel="{handler: 'iframe', size: {x: 740, y: 425}}" href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=13155666">                
              <img src="<?php echo JURI::base(); ?>components/com_ijoomla_seo/images/icon_video.gif" class="video_img" />
              <?php echo JText::_("COM_IJOOMLA_SEO_REDIRECT_VID"); ?>
         </a>
	</div>

 
<div class="redirectcategory-content"> 
	
	<div class="well well-minimize">            	
    		<?php echo JText::_("COM_IJOOMLA_SEO_REDIRECTS_DESCRIPTION"); ?>        
    	</div>   	
    	             
    		<div id="filter-bar" class="row-fluid">            
    			<div class="span12">                
    				<div class="filter-search btn-group pull-left">                    
    					<label for="search" class="element-invisible"><?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC');?></label>                    
    					<input type="text" style="margin-bottom:0px;" value="<?php echo $search; ?>" name="search" onchange="document.adminForm.submit();" />                
    					</div>                                                
    					<div class="btn-group pull-left hidden-phone">                    
    						<button class="btn tip hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>                    
    						<button class="btn tip hasTooltip" type="button" onclick="document.id('earch').value='';this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>                
    					</div> 
    					<div class="btn-group pull-right hidden-phone">	                
    						 <select name="filter_status" onchange="document.adminForm.submit();">
                                <option value="-1" <?php if($filter_status == "-1"){echo 'selected="selected"';} ?> ><?php echo JText::_("COM_IJOOMLA_SEO_SELECT_STATE"); ?></option>
                                <option value="1" <?php if($filter_status == 1){echo 'selected="selected"';} ?> ><?php echo JText::_("COM_IJOOMLA_SEO_PUBLISHED"); ?></option>
                                <option value="0" <?php if($filter_status == 0){echo 'selected="selected"';} ?> ><?php echo JText::_("COM_IJOOMLA_SEO_UNPUBLISHED"); ?></option>
                            </select>            
    					</div>	      
    					<div class="btn-group pull-right hidden-phone">
		                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
		                    <?php echo $this->pagination->getLimitBox(); ?>
		                </div>	         
    							
    			</div>    
    		</div>    
    		      
	
<div class="row-fluid">
<div class="span12">             
	  <table class="table table-striped table-bordered" id="redirectcategory">              
        <thead>
            <th width="5%">#</th>
            <th width="5%" align="center"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" /> <span class="lbl"> </span></th>
            <th><?php echo JText::_("COM_IJOOMLA_SEO_CATEGORY"); ?></th>			
            <th><?php echo JText::_("COM_IJOOMLA_SEO_PUBLISHED"); ?></th>
            <th><?php echo JText::_("COM_IJOOMLA_SEO_ITEMS"); ?></th>            
        </thead>
        <tbody>
        <?php
		$app		= JFactory::getApplication('administrator');
		$limistart	= $app->getUserStateFromRequest('com_ijoomla_seo.articles'.'.list.start', 'limitstart');
		$limit		= $app->getUserStateFromRequest('com_ijoomla_seo.articles'.'.list.limit', 'limit');
		$k = $limistart+1;
		for($i=0; $i<count($this->items); $i++){
			$item=$this->items[$i];				
		?>
			<?php if($item->name == "General"){ ?>
				<tr class="row<?php echo $i%2; ?>">				
					<td align="center">
						<?php echo $k;?>
					</td>				
					<td align="center">
						<input type="checkbox" disabled="disabled">
						<span class="lbl"> </span>
					</td>
					<td>
						<?php echo $item->name; ?>
					</td>
					<td>
						<img border="0" alt="Published" src="<?php echo JURI::root(); ?>/administrator/components/com_ijoomla_seo/images/tick.png">
					</td>
					<td>
						<?php echo $this->count($item->id); ?>
					</td>
				</tr> 
			<?php
			}
			else{
			?>
				<tr class="row<?php echo $i%2; ?>">				
				<td align="center">
					<?php echo $k;?>
				</td>				
				<td align="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					<span class="lbl"> </span>
                    <input type="hidden" name="total_links_<?php echo $item->id; ?>" id="total_links_<?php echo $item->id; ?>" value="<?php echo $this->count($item->id); ?>" />
				</td>
                <td>
					<a href="index.php?option=com_ijoomla_seo&controller=newredcategory&task=edit&id=<?php echo $item->id; ?>"><?php echo $item->name; ?></a>
				</td>
                <td>
                	<?php echo $published = JHTML::_('grid.published', $item->published, $i); ?>                    
                </td>
                <td>
                	<?php echo $this->count($item->id); ?>
                </td>
            </tr> 
			<?php
			}
			?>
        <?php
			$k++;
		}
		?> 
		</tbody>
        <tfoot>
			<tr>
				<td colspan="16">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>	
</div>	
</div>
</div>
	<input type="hidden" name="option" value="com_ijoomla_seo" />
	<input type="hidden" name="controller" value="redirectcategory" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::_('form.token'); ?>
</form>