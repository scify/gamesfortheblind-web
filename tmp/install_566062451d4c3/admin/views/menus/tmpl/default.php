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

$item=$this->items;

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

?>

<script type="text/javascript">
Joomla.submitbutton = function (task) {
    var selItems = document.getElementsByName('cid[]'), num = selItems.length;
	if (task == "apply" || task == "save") {
		var form=document.adminForm;
		form.task.value=task;
		var i = 0;
		while(eval(document.getElementById("cb"+i))){
			document.getElementById("cb"+i).checked = true;
			i++;
		}
	} else if (task == 'copy_title_key') {
        if(num) {
            for(var i = 0; i< num; i++) {
                if(selItems[i].checked == true){
                    itemName = "metatitle"+selItems[i].value;
                    itemName1 = "metakey"+selItems[i].value;
                    source = document.getElementById(itemName).value;
                    source=source.replace("&amp;","&");
                    dest = document.getElementById(itemName1);
                    //replaces all matches(spaces,blank lines) with the string ''
                    dest.value = source.replace(/^\s*|\s*$/g,'');
                    countKey(dest, dest.value, selItems[i].value, 'Characters', '200');
                }
            }
        }	
        return false;
    } else if (task == 'copy_key_title') {
        if (num) {
            for(var i = 0; i< num; i++) {
                if(selItems[i].checked == true) {
                    itemName = "metakey"+selItems[i].value;
                    itemName1 = "metatitle"+selItems[i].value;
                    source = document.getElementById(itemName).value;
                    source=source.replace("&amp;","&");
                    dest = document.getElementById(itemName1);
                    //replaces all matches(spaces,blank lines) with the string ''
                    dest.value = source.replace(/^\s*|\s*$/g,'');
                    countTitle(dest, dest.value, selItems[i].value, 'Characters', '200');
                }
            }        
        }
        return false;
    } else if (task == 'copy_article_key') {
        if (num) {
            for(var i = 0; i< num; i++) {
                if(selItems[i].checked == true) {
                    id = document.getElementById("cb"+i).value;
					itemName = "name"+id;
                    itemName1 = "metakey" + selItems[i].value;
                    //console.log(itemName);
                    source = document.getElementById(itemName).innerHTML;
                    source=source.replace("&amp;","&");
                    dest = document.getElementById(itemName1);
                    //replaces all matches(spaces,blank lines) with the string ''
                    dest.value = source.replace(/^\s*|\s*$/g,'');
                    countKey(dest, dest.value, selItems[i].value, 'Characters', '200');
                }
            }
        }
        return false;
    } else if (task == 'copy_article_title') {
        if (num) {
            for(var i = 0; i< num; i++) {
                if(selItems[i].checked == true) {
                    id = document.getElementById("cb"+i).value;
					itemName = "name"+id;
                    itemName1 = "metatitle"+selItems[i].value;
                    source = document.getElementById(itemName).innerHTML;
                    source=source.replace("&amp;","&");
                    dest = document.getElementById(itemName1);
                    //replaces all matches(spaces,blank lines) with the string ''
                    dest.value = source.replace(/^\s*|\s*$/g,'');
                    countTitle(dest, dest.value, selItems[i].value, 'Characters', '200');
                }
            }
        }
        return false;
    }
	submitform(task);
}
<?php if (isset($_GET['choosemain'])) { ?>
function chose_main() {
    var i, len = document.getElementById('menu_types').options.length, found = false;
    for (i=0; i <= len-1; i++) {
        if ((typeof(document.getElementById('menu_types').options[i]) != 'undefined') && 
            (document.getElementById('menu_types').options[i].value == "mainmenu")) {
            
            found = true;
            document.getElementById('menu_types').options[i].selected = true;
        }
    }
    if (!found) {
        for (i=0; i <= len-1; i++) {
            if ((typeof(document.getElementById('menu_types').options[i]) != 'undefined') && 
                (document.getElementById('menu_types').options[i].value)) {

                if (document.getElementById('menu_types').options[i].value) {
                    document.getElementById('menu_types').options[i].selected = true;
                    found = true;
                }
            }
        }
    }
    if (found) { document.adminForm.submit(); }
}
window.addEvent('domready', chose_main);
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
<div class="metatags-content">			
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
    					<input type="text" style="margin-bottom:0px;" value="<?php echo $this->state->get('filter.search'); ?>" id="search" name="search" onchange="document.adminForm.submit();" />                
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
       <table class="table table-striped table-bordered" id="metatags">  
		<thead>
			<th align="center"><?php echo JText::_('#'); ?></th>
			<th align="center"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" /><span class="lbl"></span></th>			
			<th align="center"><?php echo JText::_("COM_IJOOMLA_SEO_MENU_TITLE") ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_VIEW'); ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_TITLE_METATAG'); ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_KEYWORDS_METATAG'); ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_DESCRIPTIONS_METATAG'); ?></th>			
		</thead>
		<tbody>
		<?php
		$app		= JFactory::getApplication('administrator');
		$limistart	= $app->getUserStateFromRequest('com_ijoomla_seo.menus'.'.list.start', 'limitstart');
		$limit		= $app->getUserStateFromRequest('com_ijoomla_seo.menus'.'.list.limit', 'limit');
		$k = $limistart+1;
		if (isset($_SESSION["session_titletag"])) {
			$session_titletag = $_SESSION["session_titletag"];
		} else {
			$session_titletag = null;
		}
		if (isset($_SESSION["session_metakey"])) {
			$session_metakey = $_SESSION["session_metakey"];
		} else {
			$session_metakey = null;
		}
		
		for($i=0;$i<count($this->items);$i++){
			$item=$this->items[$i];			
			$page_title = json_decode($item->params, true);
			if(isset($session_titletag[$item->id])){
				$page_title["page_title"] = $session_titletag[$item->id];
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
				<td id="name<?php echo $item->id; ?>">
					<?php echo $item->title; ?>
				</td>
				<td>
					<?php
                    	$link = JUri::root().$item->link;
						if(strpos($link, "Itemid") === FALSE){
							if(strpos($link, "?") === FALSE){
								$link .= "?Itemid=".intval($item->id);
							}
							else{
								$link .= "&Itemid=".intval($item->id);
							}
						}
					?>
					<a href="<?php echo $link; ?>" class="modal" rel="{handler: 'iframe', size: {x: 1000, y: 600}}"><?php echo "view"; ?></a>
				</td>
				<td valign="top" align="center">
					<?php
					if ($ijseo_type_title == "Words"){
						if(isset($page_title["page_title"])){
							$var = explode(' ', trim($page_title["page_title"]));
							$num = count($var);
							if($var[$num-1] == ""){
								unset($var[$num-1]);
							}	
							$num = count($var);
							$do = $ijseo_allow_no2 - $num;
						}
					} else {
						if(isset($page_title["page_title"])){
							$var = strlen(utf8_decode($page_title["page_title"]));
						}
						else{
							$var = 0;
						}
						if(isset($ijseo_allow_no2)) {
							$do = $ijseo_allow_no2 - $var;
						}	
				 	}
					?>
					<textarea id="<?php echo "metatitle".$item->id; ?>" name="page_title[<?php echo $item->id; ?>]" onkeyup="javascript: countTitle(this, this.value, '<?php echo $item->id; ?>', '<?php echo $ijseo_type_title; ?>', '<?php echo $ijseo_allow_no2; ?>');" rows="4"><?php if(isset($page_title["page_title"])){ echo $page_title["page_title"];} ?></textarea>
					<span id="go_<?php echo $item->id; ?>" name="go_<?php echo $item->id; ?>"><?php if(isset($do)) echo $do; ?></span>
					<?php
					if(isset($do) &&($do < 0)){
						echo '<script type="text/javascript"> changeColor('.$item->id.', "go", "red") </script>';
					}	
					else{ 
						echo '<script type="text/javascript"> changeColor('.$item->id.', "go", "#666666") </script>';
					}	
					if(!empty($page_title["page_title"])){
						echo '<script type="text/javascript">
								unColor("metatitle['.$item->id.']");
							</script>';
					}				
					?>				
				</td>
				<td valign="top" align="center">
					<?php
					if(isset($session_metakey[$item->id])){
						$page_title["menu-meta_keywords"] = $session_metakey[$item->id];
					}
					
					if ($ijseo_type_key=="Words"){
						if(isset($page_title["menu-meta_keywords"])){
							$var = explode(' ', trim($page_title["menu-meta_keywords"]));
							$num = count($var);
							if($var[$num-1] == ""){
								unset($var[$num-1]);
							}	
							$num = count($var);
							$no = $ijseo_allow_no - $num;
						}
					}
					else{
						if(isset($page_title["menu-meta_keywords"])){
							$var = strlen(utf8_decode($page_title["menu-meta_keywords"]));
						}
						else{
							$var = 0;
						}
						if(isset($ijseo_allow_no)){
							$no = $ijseo_allow_no - $var;
						}	
				 	}
					?>
					<textarea id="<?php echo "metakey".$item->id; ?>" name="metakey[<?php echo $item->id; ?>]" onkeyup="javascript: countKey(this, this.value, '<?php echo $item->id; ?>', '<?php echo $ijseo_type_key; ?>', '<?php echo $ijseo_allow_no; ?>');" rows="4"><?php if(isset($page_title["menu-meta_keywords"])){echo $page_title["menu-meta_keywords"];} ?></textarea>
					<span id="no_<?php echo $item->id; ?>" name="no_<?php echo $item->id; ?>"><?php if(isset($no)) echo $no; ?></span>
					<?php
					if(isset($no) &&($no < 0)){
						echo '<script type="text/javascript"> changeColor('.$item->id.', "no", "red") </script>';
					}	
					else{ 
						echo '<script type="text/javascript"> changeColor('.$item->id.', "no", "#666666") </script>';
					}	
					if(!empty($page_title["menu-meta_keywords"])){
						echo '<script type="text/javascript">
								unColor("metakey['.$item->id.']");
							</script>';
					}				
					?>
				</td>
				<td valign="top" align="center">
					<?php					
					if ($ijseo_type_desc=="Words"){
						if(isset($page_title["menu-meta_description"])){
							$var = explode(' ', trim($page_title["menu-meta_description"]));
							$num = count($var);
							if($var[$num-1] == ""){
								unset($var[$num-1]);
							}	
							$num = count($var);
							$do = $ijseo_allow_desc - $num;
						}
					}
					else{
						if(isset($page_title["menu-meta_description"])){
							$var = strlen(utf8_decode($page_title["menu-meta_description"]));
						}
						else{
							$var = 0;
						}
						if(isset($ijseo_allow_desc)){
							$do = $ijseo_allow_desc - $var;
						}	
				 	}
					?>
					<textarea id="<?php echo "metadesc".$item->id; ?>" name="metadesc[<?php echo $item->id; ?>]" onkeyup="javascript: countDesc(this, this.value, '<?php echo $item->id; ?>', '<?php echo $ijseo_type_desc; ?>', '<?php echo $ijseo_allow_desc; ?>');" rows="4"><?php if(isset($page_title["menu-meta_description"])){echo $page_title["menu-meta_description"];} ?></textarea>
					<span id="do_<?php echo $item->id; ?>" name="do_<?php echo $item->id; ?>"><?php if(isset($do)) echo $do; ?></span>
					<?php
					if(isset($do) &&($do < 0)){
						echo '<script type="text/javascript"> changeColor('.$item->id.', "do", "red") </script>';
					}	
					else{ 
						echo '<script type="text/javascript"> changeColor('.$item->id.', "do", "#666666") </script>';
					}	
					if(!empty($page_title["menu-meta_description"])){
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
	<input type="hidden" name="controller" value="menus" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
    
    <input type="hidden" name="old_menu_types" value="<?php echo JRequest::getVar('menu_types', ''); ?>" />
    <input type="hidden" name="old_types" value="menus" />
    
	<?php echo JHtml::_('form.token'); ?>
</form>