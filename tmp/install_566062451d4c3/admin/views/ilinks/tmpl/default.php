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
JHtml::_('bootstrap.tooltip');
JHTML::_('behavior.modal');

//JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
//JHtml::_('formbehavior.chosen', 'select');


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
				document.getElementById("ilinks-light").style.display = "";
				return false;
			}
		}	
		submitform(task);
	}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		   <h2 class="pub-page-title"><?php echo JText::_("COM_IJOOMLA_SEO_INTERNAL_LINKS_MANAGER"); ?><h2>
		   	<a class="modal seo_video pull-right" rel="{handler: 'iframe', size: {x: 740, y: 425}}" href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=13155445">                
                    <img src="<?php echo JURI::base(); ?>components/com_ijoomla_seo/images/icon_video.gif" class="video_img" />
                    <?php echo JText::_("COM_IJOOMLA_SEO_INTERNAL_VID"); ?>
                </a>
	</div>

	
<div class="ilinks-content">
		
		<div class="well well-minimize">            	
    		<?php echo JText::_("COM_IJOOMLA_SEO_INTERNAL_DESCRIPTION"); ?>           
    	</div>   	
    	
    	<div id="filter-bar" class="row-fluid">            
    			<div class="span12">                
    				<div class="filter-search btn-group pull-left">                    
    					<label for="search" class="element-invisible"><?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC');?></label>                    
    					<input style="margin-bottom: 0px !important;" type="text" value="<?php echo $search; ?>" name="search" onchange="document.adminForm.submit();" />                
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
       <table class="table table-striped table-bordered" id="ilinks"> 
		<thead> 		
			<th width="5%">
				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
                <span class="lbl"></span>				
			</th>
            <th align="left"><?php echo JText::_("COM_IJOOMLA_SEO_INTERNAL_LINK"); ?></th>
            <th align="left"><?php echo JText::_("COM_IJOOMLA_SEO_PUBLISHED"); ?></th>
            <th align="left"><?php echo JText::_("COM_IJOOMLA_SEO_TYPE"); ?></th>
            <th align="left"><?php echo JText::_("COM_IJOOMLA_SEO_LOCATIN"); ?></th>
            <th align="left"><?php echo JText::_("COM_IJOOMLA_SEO_OPEN_IN"); ?></th>
			<th align="left"><?php echo JText::_("COM_IJOOMLA_SEO_CATEGORY_COLUMN"); ?></th>		
		</thead>	
		<tbody>
        <?php
		$app		= JFactory::getApplication('administrator');
		$limistart	= $app->getUserStateFromRequest('com_ijoomla_seo.ilinks'.'.list.start', 'limitstart');
		$limit		= $app->getUserStateFromRequest('com_ijoomla_seo.ilinks'.'.list.limit', 'limit');
		$row_switch = 0;
		$host = JURI::root();		
		for($i=0; $i<count($this->items); $i++){
			$item=$this->items[$i];
			$type = "";
			
			switch ($item->type){
            	case 1:
                	$type = JText::_("COM_IJOOMLA_SEO_ARTICLE");
					$link_location = $host."index.php?option=com_content&view=article&id=".$item->articleId;
                    $location = $item->location;
					break;
                case 2:
                    $type = JText::_("COM_IJOOMLA_SEO_MENU");					
					$link_location = $this->getLocation($item->loc_id);
					if(strpos($link_location, "http://")== false && strpos($link_location, "www.") == false){			 	
						$link_location = $host.$link_location;
					}	
					$location = $item->location;					
                    break;
                case 3:
                   	$type = JText::_("COM_IJOOMLA_SEO_EXTERNAL_URL");
					$link_location = $item->location2;
                    $location = $item->location2;
                    break;
				case 4:
                   	$type = "#(no link)";
					$link_location = "";
                    $location = "";
                    break;
            }
			$url = '<a href="'.$link_location.'" target="_blank">'.$location.'</a>';			
		?>
			<tr class="row<?php echo $row_switch; ?>">
                <td><?php echo  $checked = JHTML::_('grid.id', $i, $item->id);  ?> <span class="lbl"></span>	</td>
                <td><a href="index.php?option=com_ijoomla_seo&controller=newilinks&id=<?php echo $item->id; ?>"><?php echo $item->name; ?></a></td>
                <td><?php echo $published = JHTML::_('grid.published', $item->published, $i); ?></td>
                <td><?php echo $type ?></td>
                <td><?php echo $url ?></td>
                <td><?php echo ($item->target==1) ? JText::_("COM_IJOOMLA_SEO_TARGET_SAME") : JText::_("COM_IJOOMLA_SEO_TARGET_BLANK"); ?></td>
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
	<input type="hidden" name="controller" value="ilinks" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
</form>

<div class="alert-light" id="ilinks-light" style="display:none;">
    <p style="font-size: 17px; font-weight: bold; text-align: justify;"><?php echo JText::_("COM_IJOOMLA_SEO_ONE_ILINK"); ?></p>
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
        <a href="#" onclick="javascript:closePopUp('ilinks-light'); return false;" style="font-size:14px;" >Close</a>
    </p>
</div>