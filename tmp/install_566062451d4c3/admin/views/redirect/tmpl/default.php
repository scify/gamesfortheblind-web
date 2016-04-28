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
$lang_target = array("_blank"=>JText::_("COM_IJOOMLA_SEO_TARGET_BLANK"), 
					"_self"=>JText::_("COM_IJOOMLA_SEO_TARGET_SELF"), 
					"_parent"=>JText::_("COM_IJOOMLA_SEO_TARGET_PARENT"), 
					"_top"=>JText::_("COM_IJOOMLA_SEO_TARGET_TOP"));
$search = JRequest::getVar("search", "");
?>

<script type="text/javascript">
	Joomla.submitbutton = function (task){
		if(task == "new"){
			if(eval(document.getElementById("cb0")) && eval(document.getElementById("cb1")) && eval(document.getElementById("cb2")) && eval(document.getElementById("cb3")) && eval(document.getElementById("cb4"))){
				document.getElementById("redirect-light").style.display = "";
				return false;
			}
		}	
		submitform(task);
	}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	
	<div class="row-fluid">
            <h2 class="pub-page-title"><?php echo JText::_("COM_IJOOMLA_SEO_SEO_REDIRECT"); ?></h2>
            	<a class="modal seo_video pull-right" rel="{handler: 'iframe', size: {x: 740, y: 425}}" href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=13155666">                
                    <img src="<?php echo JURI::base(); ?>components/com_ijoomla_seo/images/icon_video.gif" class="video_img" />
                    <?php echo JText::_("COM_IJOOMLA_SEO_REDIRECT_VID"); ?>
                </a> 	
    </div>

<div class="redirect-content"> 
	
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
    						<?php 
								echo $this->selectAllCategories(); 
							?>             
    					</div>	      
    					<div class="btn-group pull-right hidden-phone">
		                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
		                    <?php echo $this->pagination->getLimitBox(); ?>
		                </div>	         
    							
    			</div>    
    		</div>    
    		      
	   
  <div class="row-fluid">
      <div class="span12">
       <table class="table table-striped table-bordered" id="ilinkscategory">  
		<thead> 
			<th width="5%">
                <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
                <span class="lbl"></lbl>
            </th>           
        	<th align="left" nowrap><?php echo JText::_("COM_IJOOMLA_SEO_NAME"); ?></th>
			<th align="left" nowrap><?php echo JText::_("COM_IJOOMLA_SEO_URL"); ?></th>
			<th align="left" nowrap><?php echo JText::_("COM_IJOOMLA_SEO_LINKS_TO"); ?></th>
			<th align="left" nowrap><?php echo JText::_("COM_IJOOMLA_SEO_TARGET"); ?></th>
			<th align="left" nowrap><?php echo JText::_("COM_IJOOMLA_SEO_HITS"); ?></th>
			<th align="left" nowrap><?php echo JText::_("COM_IJOOMLA_SEO_RESET"); ?></th>
			<th align="center" nowrap><?php echo JText::_("COM_IJOOMLA_SEO_ID"); ?></th>
			<th align="center" nowrap><?php echo JText::_("COM_IJOOMLA_SEO_CATEGORY_COLUMN"); ?></th>
		</thead>
		<tbody>	
        <?php
		$app		= JFactory::getApplication('administrator');
		$limistart	= $app->getUserStateFromRequest('com_ijoomla_seo.redirect'.'.list.start', 'limitstart');
		$limit		= $app->getUserStateFromRequest('com_ijoomla_seo.redirect'.'.list.limit', 'limit');
		$row_switch = 0;			
		for($i=0; $i<count($this->items); $i++){
			$item=$this->items[$i];
			$url = 'index.php?option=com_ijoomla_seo&id='.$item->id;
			$path = JURI::root();
			$test_url = $url."&nbsp;&nbsp;(".'<a href="'.$path.('index.php?option=com_ijoomla_seo&controller=redirect&task=testredirect&id='.$item->id).'" target="_blank">'.JText::_("COM_IJOOMLA_SEO_TEST_URL").')</a>';
			$links_to = strlen($item->links_to) > 30 ? substr($item->links_to, 0, 30)."..." : $item->links_to;		
		?>
			<tr class="row<?php echo $row_switch; ?>">
				<td><?php echo  $checked = JHTML::_('grid.id', $i, $item->id);  ?>
					<span class="lbl"></span>
				</td>
				<td>
					<a href="index.php?option=com_ijoomla_seo&controller=newredirect&task=edit&id=<?php echo $item->id; ?>"><?php echo $item->name; ?></a>
				</td>
				<td><?php echo $test_url; ?></td>
				<td><?php echo $links_to; ?></td>
				<td><?php echo $lang_target[$item->target]; ?></td>
				<td align="center"><?php echo $item->hits; ?></td>
				<td><?php echo $item->last_hit_reset; ?></td>
				<td align="center">{ijseo_redirect id=<?php echo $item->id; ?>}</td>
				<td><?php echo $item->cat_name; ?></td>
			</tr>
		<?php
			$row_switch = 1 - $row_switch;
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
	<input type="hidden" name="controller" value="redirect" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
</form>

<div class="alert-light" id="redirect-light" style="display:none;">
    <p style="font-size: 17px; font-weight: bold; text-align: justify;"><?php echo JText::_("COM_IJOOMLA_SEO_ONE_REDIRECT"); ?></p>
    <br />
    <br />
    <p class="pagination-centered">
        <form id="prform" name="prform" target="_blank" style="margin:0px; text-align: center;" method="post" action="http://www.ijoomla.com/index.php?option=com_digistore&controller=digistoreCart&task=add&pid[0]=94&cid[0]=94" onsubmit="return prodformsubmit4a60cb04c1341();">
            <input name="qty" value="1" type="hidden" />
            <input name="pid" id="product_id" value="94" type="hidden" />
            <input name="Button" type="submit" class="btn btn-warning" value="Buy Pro" />
        </form>
    </p>
    <br />
    <br />
    <p class="pagination-centered">
        <a href="http://seo.ijoomla.com/pricing/" style="font-size:18px; text-decoration:underline;" target="_blank"><?php echo JText::_("COM_IJOOMLA_SEO_COMPARE"); ?></a>
    </p>
    <p class="pagination-right">
        <a href="#" onclick="javascript:closePopUp('redirect-light'); return false;" style="font-size:14px;" >Close</a>
    </p>
</div>