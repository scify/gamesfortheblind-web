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
//JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.modal');

//JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
//JHtml::_('formbehavior.chosen', 'select');

include_once(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."helpers".DS."meta.php");

$item = $this->items;

$ijseo_type_key = $this->params->ijseo_type_key;
$ijseo_allow_no = $this->params->ijseo_allow_no;

$ijseo_type_title = $this->params->ijseo_type_title;
$ijseo_allow_no2 = $this->params->ijseo_allow_no2;

$ijseo_type_desc = $this->params->ijseo_type_desc;
$ijseo_allow_desc = $this->params->ijseo_allow_no_desc;

$meta = new Meta();

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_ijoomla_seo/css/seostyle.css");
$document->addScript("components/com_ijoomla_seo/javascript/scripts.js");

$app = JFactory::getApplication('administrator');
$limistart = $app->getUserStateFromRequest('com_ijoomla_seo.articles'.'.list.start', 'limitstart');
$limit = $app->getUserStateFromRequest('com_ijoomla_seo.articles'.'.list.limit', 'limit');
?>

<script type="text/javascript">
Joomla.submitbutton = function (task){
	if(task == "apply" || task == "save"){
		var form=document.adminForm;
		form.task.value=task;
		var i = 0;
		while(eval(document.getElementById("cb"+i))){
			document.getElementById("cb"+i).checked = true;
			i++;
		}
	}	
	submitform(task);
}
<?php 
    $selected = JRequest::getVar('selected');
    if ($selected == 'menus') {
?>
setTimeout(function() {
    // Set "menus" as selected type when on metatags by default
    document.getElementById('types').options[1].selected = true;
    showMenu('menus');
}, 400);
<?php } ?>
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
		
	<div class="row-fluid">
            <h2 class="pub-page-title"><?php echo JText::_("COM_IJOOMLA_SEO_SEO_METATAGS"); ?></h2>
            	<a class="modal seo_video_meta pull-right"  rel="{handler: 'iframe', size: {x: 740, y: 425}}" style="font-size: 16px;" target="_blank"
                        href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=28774680">
                    <img src="<?php echo JURI::base(); ?>components/com_ijoomla_seo/images/icon_video.gif" class="video_img" />
                    <?php echo JText::_("COM_IJOOMLA_SEO_WHY_NOT_SHOW"); ?>
                </a>
				<span class="pull-right" style="color:#FF0000; font-size:16px;"><?php echo JText::_('COM_IJOOMLA_SEO_MUST_WATCH'); ?></span>
    </div>

<div class="row-fluid">
	<div class="span12">
	    <a class="modal seo_video_meta pull-right" href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=13155551" rel="{handler: 'iframe', size: {x: 740, y: 425}}">
	        <img src="components/com_ijoomla_seo/images/icon_video.gif">
	        <?php echo JText::_('COM_IJOOMLA_SEO_HOWTO_METATAGS_VIDEO'); ?>
	    </a>
    </div>
</div>
<div class="metatagsarticles-content">			
  <div class="well well-minimize">            	
    		<?php echo JText::_("COM_IJOOMLA_SEO_METATAGS_DESCRIPTION"); ?>
   </div>   	
   

   	 <div class="span6 pull-right">
   	 	<div class="pull-right hidden-phone">	 
			<?php echo $meta->createOptions(); ?>
		</div>
	    <div class="pull-right hidden-phone">	        
			<?php echo $meta->getList(); ?>
	   	</div>
	   	<div class="pull-right">
          <span class="title_page"><?php echo JText::_("COM_IJOOMLA_SEO_SELECTITEMS_TO_EDIT"); ?></span>
		  <img alt="arrow" src="components/com_ijoomla_seo/images/redarrow.png" style="vertical-align:top;">&nbsp;
    	</div>
	</div>

    	             
    		<div id="filter-bar" class="row-fluid">            
    			<div class="span12">                
    				<div class="filter-search btn-group pull-left">                    
    					<label for="search" class="element-invisible"><?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC');?></label>                    
    					<input type="text" style="margin-bottom:0px;" value="<?php echo $this->state->get('filter.search'); ?>" name="search" id="search" onchange="document.adminForm.submit();" />                
    					</div>                                                
    					<div class="btn-group pull-left hidden-phone">                    
    						<button class="btn tip hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>                    
    						<button class="btn tip hasTooltip" type="button" onclick="document.id('search').value='';this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>                
    					</div> 
    				
    					<div class="btn-group pull-right hidden-phone">	                
    						<?php
							echo $this->createCriterias();
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
       <table class="table table-striped table-bordered" id="metatags-articles">  
		<thead>
			<th align="center"><?php echo JText::_('#'); ?></th>
			<th align="center"><input type="checkbox" name="toggle" id="toggle" value="" onclick="Joomla.checkAll(this);"/>
				<span class="lbl"></span>
			</th>
			<th align="center"><?php echo JText::_("COM_IJOOMLA_SEO_ARTICLE_TITLE") ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_VIEW'); ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_TITLE_METATAG'); ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_KEYWORDS_METATAG'); ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_DESCRIPTIONS_METATAG'); ?></th>			
		</thead>
		<tbody>
		<?php
		$app		= JFactory::getApplication('administrator');
		$limistart	= $app->getUserStateFromRequest('com_ijoomla_seo.articles'.'.list.start', 'limitstart');
		$limit		= $app->getUserStateFromRequest('com_ijoomla_seo.articles'.'.list.limit', 'limit');
		$k = $limistart+1;
		$session_titletag = "";
		$session_metakey = "";
		$session_description = "";
		if(isset($_SESSION["session_titletag"])){
			$session_titletag = $_SESSION["session_titletag"];
		}
		if(isset($_SESSION["session_metakey"])){
			$session_metakey = $_SESSION["session_metakey"];
		}
		if(isset($_SESSION["session_description"])){
			$session_description = $_SESSION["session_description"];
		}
		
		for($i=0;$i<count($this->items);$i++){
			$item=$this->items[$i];
			$metatitle = $item->titletag;
			if(isset($session_titletag[$item->id])){
				$metatitle = $session_titletag[$item->id];
			}
		?>
			<tr class="row<?php echo $i%2; ?>">
				<td align="center">
					<?php echo $k;?>
				</td>				
				<td align="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					<span class="lbl"></span>
				</td>				
				<td>
					<?php echo $item->title; ?>
				</td>
				<td>
					<a href="index.php?option=com_ijoomla_seo&controller=preview&id=<?php echo $item->id; ?>&tmpl=component&task=article_preview" class="modal" rel="{handler: 'iframe', size: {x: 640, y: 480}}"><?php echo "view"; ?></a>
				</td>
				<td valign="top" align="center">
					<?php
                    // Metatitle
					if ($ijseo_type_title == "Words"){
						$var = explode(' ', trim($metatitle));
						$num = count($var);
						if($var[$num-1] == ""){
							unset($var[$num-1]);
						}	
						$num = count($var);
						$do = $ijseo_allow_no2 - $num;
					}
					else{
						$var = strlen(utf8_decode($metatitle));
						if(isset($ijseo_allow_no2)){
							$do = $ijseo_allow_no2 - $var;
						}	
				 	}
                    
					?>					
					<textarea id="<?php echo "metatitle".$item->id; ?>" name="page_title[<?php echo $item->id; ?>]" onkeyup="javascript: countTitle(this, this.value, '<?php echo $item->id; ?>', '<?php echo $ijseo_type_title; ?>', '<?php echo $ijseo_allow_no2; ?>');" rows="4"><?php echo $metatitle; ?></textarea>
					<span id="go_<?php echo $item->id; ?>" name="go_<?php echo $item->id; ?>"><?php if(isset($do)) echo $do; ?></span>
					<?php
					if(isset($do) &&($do < 0)){
						echo '<script type="text/javascript"> changeColor('.$item->id.', "go", "red") </script>';
					}	
					else{ 
						echo '<script type="text/javascript"> changeColor('.$item->id.', "go", "#666666") </script>';
					}	
					if(!empty($metatitle)){
						echo '<script type="text/javascript">
								unColor("metatitle['.$item->id.']");
							</script>';
					}				
					?>				
				</td>
				<td valign="top" align="center">
					<?php
					if(isset($session_metakey[$item->id])){
						$item->metakey = $session_metakey[$item->id];
					}
					
					if ($ijseo_type_key=="Words"){
						$var = explode(' ', trim($item->metakey));
						$num = count($var);
						if($var[$num-1] == ""){
							unset($var[$num-1]);
						}	
						$num = count($var);
						$no = $ijseo_allow_no - $num;
					}
					else{
						$var = strlen(utf8_decode(trim($item->metakey)));
						if(isset($ijseo_allow_no)){
							$no = $ijseo_allow_no - $var;
						}	
				 	}
                    
					?>
					<textarea id="<?php echo "metakey".$item->id; ?>" name="metakey[<?php echo $item->id; ?>]" onkeyup="javascript: countKey(this, this.value, '<?php echo $item->id; ?>', '<?php echo $ijseo_type_key; ?>', '<?php echo $ijseo_allow_no; ?>');" rows="4"><?php echo $item->metakey; ?></textarea>
					<span id="no_<?php echo $item->id; ?>" name="no_<?php echo $item->id; ?>"><?php if(isset($no)) echo $no; ?></span>
					<?php
					if(isset($no) &&($no < 0)){
						echo '<script type="text/javascript"> changeColor('.$item->id.', "no", "red") </script>';
					}	
					else{ 
						echo '<script type="text/javascript"> changeColor('.$item->id.', "no", "#666666") </script>';
					}	
					if(!empty($item->metakey)){
						echo '<script type="text/javascript">
								unColor("metakey['.$item->id.']");
							</script>';
					}				
					?>
				</td>
				<td valign="top" align="center">
					<?php
					if(isset($session_description[$item->id])){
						$item->metadesc = $session_description[$item->id];
					}
					
					if ($ijseo_type_desc=="Words"){
						$var = explode(' ', trim($item->metadesc));
						$num = count($var);
						if($var[$num-1] == ""){
							unset($var[$num-1]);
						}	
						$num = count($var);
						$do = $ijseo_allow_desc - $num;
					}
					else{
						$var = strlen(utf8_decode($item->metadesc));
						if(isset($ijseo_allow_desc)){
							$do = $ijseo_allow_desc - $var;
						}	
				 	}
					?>
					<textarea id="<?php echo "metadesc".$item->id; ?>" name="metadesc[<?php echo $item->id; ?>]" onkeyup="javascript: countDesc(this, this.value, '<?php echo $item->id; ?>', '<?php echo $ijseo_type_desc; ?>', '<?php echo $ijseo_allow_desc; ?>');" rows="4"><?php echo $item->metadesc; ?></textarea>
					<span id="do_<?php echo $item->id; ?>" name="do_<?php echo $item->id; ?>"><?php if(isset($do)) echo $do; ?></span>
					<?php
					if(isset($do) &&($do < 0)){
						echo '<script type="text/javascript"> changeColor('.$item->id.', "do", "red") </script>';
					}	
					else{ 
						echo '<script type="text/javascript"> changeColor('.$item->id.', "do", "#666666") </script>';
					}	
					if(!empty($item->metadesc)){
						echo '<script type="text/javascript">
								unColor("metadesc['.$item->id.']");
							</script>';
					}				
					?>					
				</td>
			</tr>			
		<?php
			$k++;
		}
		unset($_SESSION["session_titletag"]);
		unset($_SESSION["session_metakey"]);
		unset($_SESSION["session_description"]);
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
	<input type="hidden" name="controller" value="articles" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter" value="<?php echo JRequest::getVar("filter", ""); ?>" />
	<input type="hidden" name="value" value="<?php echo JRequest::getVar("value", ""); ?>" />
    
    <input type="hidden" name="old_types" value="articles" />
    
	<?php echo JHtml::_('form.token'); ?>
</form>

<div class="alert-light" id="articles-light" style="display:none;">
    <p style="font-size: 17px; font-weight: bold; text-align: justify;"><?php echo JText::_("COM_IJOOMLA_SEO_ONE_META"); ?></p>
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
        <a href="#" onclick="javascript:closePopUp('articles-light'); return false;" style="font-size:14px;" >Close</a>
    </p>
</div>