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
JHtml::_('behavior.tooltip');

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_ijoomla_seo/css/seostyle.css");
$document->addScript("components/com_ijoomla_seo/javascript/scripts.js");
$document->addScript("components/com_ijoomla_seo/javascript/newilinks.js");

$id = JRequest::getVar("id", "0", "get");
$ilinkType = "";

$name = "";
$t_article = "block";
$t_menu = "none";
$t_menu_2 = "none";
$t_url = "none";
$menu_type = "";
$location1 = "";
$location2 = ""; // http://
$location = "";
$published = "1";
$type = "";
$target = "";
$articleId = "";
$loc_id = "";
$catid = "";
$other_phrases = "0";
$title = "";
$include_in = 1;
$activate_for_some = 0;
$enable_jomsocial = 1;
$enable_zoo = 1;
$enable_k2 = 1;
$enable_kunena = 1;
$enable_easyblog = 1;

if($id != "0"){
	$values = $this->getValues();
	$name = $values["0"]->name;
    $title = $values["0"]->title;
	$name = str_replace('"', "&quot;", $name);
	$published = $values["0"]->published;
	$type = $values["0"]->type;
	$location = $values["0"]->location;	
	$target = $values["0"]->target;	
	$articleId = $values["0"]->articleId;	
	$location2 = $values["0"]->location2;	
	$menu_type = $values["0"]->menu_type;
	$loc_id = $values["0"]->loc_id;	
	$location1 = $values["0"]->location1;
	$catid = $values["0"]->catid;
	$other_phrases = $values["0"]->other_phrases;
    $include_in = $values["0"]->include_in;
    $activate_for_some = $values["0"]->activate_for_some;
	
	$params = json_decode($values["0"]->params, true);
	if(isset($params["enable_jomsocial"])){
		$enable_jomsocial = $params["enable_jomsocial"];
	}
	if(isset($params["enable_zoo"])){
		$enable_zoo = $params["enable_zoo"];
	}
	if(isset($params["enable_k2"])){
		$enable_k2 = $params["enable_k2"];
	}
	if(isset($params["enable_kunena"])){
		$enable_kunena = $params["enable_kunena"];
	}
	if(isset($params["enable_easyblog"])){
		$enable_easyblog = $params["enable_easyblog"];
	}
	
	if($type == "1"){
		$t_article = "block";
		$t_menu = "none";
		$t_menu_2 = "none";
		$t_url = "none";
	}
	elseif($type == "2"){
		$t_article = "none";
		$t_menu = "block";
		$t_menu_2 = "block";
		$t_url = "none";
	}
	elseif($type == "3"){
		$t_article = "none";
		$t_menu = "none";
		$t_menu_2 = "none";
		$t_url = "block";
	}
}

if(isset($values) && is_array($values) && is_array($values["0"]->articles) && (count($values["0"]->articles)) ) {
    $has_assigned_articles = true;
}
else{
    $has_assigned_articles = false;
}
?>

<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton){
		var form = document.adminForm;		
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
		}		
		else if(pressbutton == 'save' || pressbutton == 'apply') {
			if (form.name.value == "") {
				alert("<?php echo JText::_("COM_IJOOMLA_SEO_WORD_PHRASE"); ?> <?php echo " ".JText::_("COM_IJOOMLA_SEO_IS_REQUIRED")."."; ?>");
				return false;
			}
			else if (form.catid.value == "0") {
				alert("<?php echo JText::_("COM_IJOOMLA_SEO_CATEGORY"); ?> <?php echo " ".JText::_("COM_IJOOMLA_SEO_IS_REQUIRED")."."; ?>");
				return false;
			}			
			else if (!form.title.value) {
				alert("<?php echo JText::_("COM_IJOOMLA_TITLE_TOOLTIP"); ?> <?php echo " ".JText::_("COM_IJOOMLA_SEO_IS_REQUIRED")."."; ?>");
				return false;
			}
			else if(form.type.value == 1){// articles
				if(form.articleId.value == "" || form.articleId.value == "0"){
					alert("<?php echo JText::_("COM_IJOOMLA_SEO_ARTICLE_MUST_ADDED"); ?>");
					return false;
				}
				else{
					submitform( pressbutton );
				}
			}
			else{
				submitform( pressbutton );
			}	
		}
		else{
			submitform( pressbutton );
		}
		
	}
    setTimeout(function() {
        changeMenu();
    }, 1);
</script>

<style type="text/css">
	.chzn-drop{
		width: auto !important;
	}
	
	.form-horizontal .control-label{
		width:10% !important;
	}
</style>

<form class="form-horizontal" action="index.php" method="post" name="adminForm" id="adminForm">

	<?php
		if($id != "0"){
			echo '<h2 class="pub-page-title">'.JText::_("COM_IJOOMLA_SEO_UPDATE_AUTOMATIC_KEYLINK")."</h2>";
		}
		else{
			echo '<h2 class="pub-page-title">'.JText::_("COM_IJOOMLA_SEO_AUTOMATIC_KEYLINK")."</h2>";
		}
	?>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_("COM_IJOOMLA_SEO_WORD_PHRASE"); ?>:<span style="color:#FF0000;">*</span></label>
        <div class="controls">
        	<input name="name" type="text" class="inputbox" id="name" value="<?php echo $name; ?>" size="50" />
        	 <span class="editlinktip hasTip" title="<?php echo JText::_("COM_IJOOMLA_SEO_KEYPHRASE_TIP"); ?>" >					
			<img src="<?php echo JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"; ?>" border="0"/></span>	
			
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_("COM_IJOOMLA_SEO_CATEGORY"); ?>:<span style="color:#FF0000;">*</span></label>
        <div class="controls">
        	<?php echo $this->selectAllCategories($catid); ?>
        	 <span class="editlinktip hasTip" title="<?php echo JText::_("COM_IJOOMLA_SEO_CATEGORY_TIP"); ?>" >					
			<img src="<?php echo JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"; ?>" border="0"/></span>	
			
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_('COM_IJOOMLA_TITLE_TOOLTIP'); ?>:<span style="color:#FF0000;">*</span></label>
        <div class="controls">
        	<input name="title" type="text" class="inputbox" id="title" value="<?php echo $title; ?>" size="50" />
        	 <span class="editlinktip hasTip" title="<?php echo JText::_("COM_IJOOMLA_SEO_TITLE_TOOL_TIP"); ?>" >					
			<img src="<?php echo JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"; ?>" border="0"/></span>	
			
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_("COM_IJOOMLA_SEO_WORD_PHRASE_LINK_TO"); ?></label>
        <div class="controls">
        	<table cellpadding="0" cellspacing="0" border="0" >
                <tr>
                    <td valign="top">
                        <div style="float:left; margin-right:10px;">
                        <?php
                            echo $this->getType($type); 								
                        ?>
                        </div>
                    </td>
                    <td align="left">
                        <div id="t_article" style="display:<?php echo $t_article; ?>;float:left;">
                            <?php
                                echo $this->displayArticle($articleId, $type, trim($location));
                            ?>
                        </div>
                        <div id="t_menu" style="display:<?php echo $t_menu; ?>;float:left; margin-right:10px;">
                            <?php
                                echo $this->getAllMenu($menu_type);
                            ?>
                        </div>
                        <div id="t_menu_2" style="display:<?php echo $t_menu_2; ?>;float:left;">
                            <?php
                                if(isset($menu_type) && trim($menu_type) != ""){
                                    echo $this->getAllMenuItems($menu_type, $loc_id);
                                }
                            ?>
                        </div>
                        <div id="t_url" style="display:<?php echo $t_url; ?>;float:left;">
                            <?php
                                echo $this->displayUrl($location2);
                            ?>
                        </div>&nbsp;
                         <span class="editlinktip hasTip" title="<?php echo JText::_("COM_IJOOMLA_SEO_LINKTO_TIP"); ?>" >					
						<img src="<?php echo JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"; ?>" border="0"/></span>	
                        
                    </td>											
                </tr>
            </table>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_("COM_IJOOMLA_SEO_OPEN_LINK"); ?></label>
        <div class="controls">
        	<?php
				  echo $this->openLink($target);
			 ?>
			  <span class="editlinktip hasTip" title="<?php echo JText::_("COM_IJOOMLA_SEO_TARGET_TIP"); ?>" >					
			<img src="<?php echo JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"; ?>" border="0"/></span>	
			
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_("COM_IJOOMLA_SEO_PUBLISHED"); ?></label>
        <div class="controls">
        	<fieldset class="radio btn-group" id="jform_published">
                <?php
                    $no_checked = "";
                    $yes_cheched = "";
                    $display = "block";
                    
                    if($published == 0){
                        $no_checked = 'checked="checked"';
                        $display = "none";
                    }
                    else{
                        $yes_cheched = 'checked="checked"';
                    }
                ?>
                 <input type="hidden" name="published" value="0">
				 <input type="checkbox" <?php echo $yes_cheched; ?> value="1" name="published" class="ace-switch ace-switch-5">
				 <span class="lbl"></span>
                
            </fieldset>
             <span class="editlinktip hasTip" title="<?php echo JText::_("COM_IJOOMLA_SEO_PUBLISH_TIP"); ?>" >					
			<img src="<?php echo JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"; ?>" border="0"/></span>	
           
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_("COM_IJOOMLA_SEO_OTHER_PHRASES"); ?></label>
        <div class="controls">
        	<fieldset class="radio btn-group" id="jform_other_phrases">
                <?php
                    $no_checked = "";
                    $yes_cheched = "";
                    $display = "block";
                    
                    if($other_phrases == 0){
                        $no_checked = 'checked="checked"';
                        $display = "none";
                    }
                    else{
                        $yes_cheched = 'checked="checked"';
                    }
                ?>
                <input type="hidden" name="other_phrases" value="0">
				 <input type="checkbox" <?php echo $yes_cheched; ?> value="1" name="other_phrases" class="ace-switch ace-switch-5">
				<span class="lbl"></span>
              
            </fieldset>
             <span class="editlinktip hasTip" title="<?php echo JText::_("COM_IJOOMLA_SEO_OTHER_PHRASES_TIP"); ?>" >					
			<img src="<?php echo JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"; ?>" border="0"/></span>	
           
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_("COM_IJOOMLA_SEO_ENABLE_JOMSOCIAL"); ?></label>
        <div class="controls">
        	<fieldset class="radio btn-group" id="jform_enable_jomsocial">
                <?php
                    $no_checked = "";
                    $yes_cheched = "";
                    
                    if($enable_jomsocial == 0){
                        $no_checked = 'checked="checked"';
                    }
                    else{
                        $yes_cheched = 'checked="checked"';
                    }
                ?>
                <input type="hidden" name="enable_jomsocial" value="0">
				<input type="checkbox" <?php echo $yes_cheched; ?> value="1" name="enable_jomsocial" class="ace-switch ace-switch-5">
				<span class="lbl"></span>
              
            </fieldset>
            <span class="editlinktip hasTip" title="<?php echo JText::_("COM_IJOOMLA_SEO_ENABLE_JOMSOCIAL_TIP"); ?>" >
			<img src="<?php echo JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"; ?>" border="0"/></span>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_("COM_IJOOMLA_SEO_ENABLE_ZOO"); ?></label>
        <div class="controls">
        	<fieldset class="radio btn-group" id="jform_enable_zoo">
                <?php
                    $no_checked = "";
                    $yes_cheched = "";
                    
                    if($enable_zoo == 0){
                        $no_checked = 'checked="checked"';
                    }
                    else{
                        $yes_cheched = 'checked="checked"';
                    }
                ?>
                <input type="hidden" name="enable_zoo" value="0">
				<input type="checkbox" <?php echo $yes_cheched; ?> value="1" name="enable_zoo" class="ace-switch ace-switch-5">
				<span class="lbl"></span>
              
            </fieldset>
            <span class="editlinktip hasTip" title="<?php echo JText::_("COM_IJOOMLA_SEO_ENABLE_ZOO_TIP"); ?>" >
			<img src="<?php echo JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"; ?>" border="0"/></span>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_("COM_IJOOMLA_SEO_ENABLE_K2"); ?></label>
        <div class="controls">
        	<fieldset class="radio btn-group" id="jform_enable_zoo">
                <?php
                    $no_checked = "";
                    $yes_cheched = "";
                    
                    if($enable_k2 == 0){
                        $no_checked = 'checked="checked"';
                    }
                    else{
                        $yes_cheched = 'checked="checked"';
                    }
                ?>
                <input type="hidden" name="enable_k2" value="0">
				<input type="checkbox" <?php echo $yes_cheched; ?> value="1" name="enable_k2" class="ace-switch ace-switch-5">
				<span class="lbl"></span>
              
            </fieldset>
            <span class="editlinktip hasTip" title="<?php echo JText::_("COM_IJOOMLA_SEO_ENABLE_K2_TIP"); ?>" >
			<img src="<?php echo JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"; ?>" border="0"/></span>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_("COM_IJOOMLA_SEO_ENABLE_KUNENA"); ?></label>
        <div class="controls">
        	<fieldset class="radio btn-group" id="jform_enable_kunena">
                <?php
                    $no_checked = "";
                    $yes_cheched = "";
                    
                    if($enable_kunena == 0){
                        $no_checked = 'checked="checked"';
                    }
                    else{
                        $yes_cheched = 'checked="checked"';
                    }
                ?>
                <input type="hidden" name="enable_kunena" value="0">
				<input type="checkbox" <?php echo $yes_cheched; ?> value="1" name="enable_kunena" class="ace-switch ace-switch-5">
				<span class="lbl"></span>
              
            </fieldset>
            <span class="editlinktip hasTip" title="<?php echo JText::_("COM_IJOOMLA_SEO_ENABLE_KUNENA_TIP"); ?>" >
			<img src="<?php echo JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"; ?>" border="0"/></span>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_("COM_IJOOMLA_SEO_ENABLE_EASYBLOG"); ?></label>
        <div class="controls">
        	<fieldset class="radio btn-group" id="jform_enable_easyblog">
                <?php
                    $no_checked = "";
                    $yes_cheched = "";
                    
                    if($enable_easyblog == 0){
                        $no_checked = 'checked="checked"';
                    }
                    else{
                        $yes_cheched = 'checked="checked"';
                    }
                ?>
                <input type="hidden" name="enable_easyblog" value="0">
				<input type="checkbox" <?php echo $yes_cheched; ?> value="1" name="enable_easyblog" class="ace-switch ace-switch-5">
				<span class="lbl"></span>
              
            </fieldset>
            <span class="editlinktip hasTip" title="<?php echo JText::_("COM_IJOOMLA_SEO_ENABLE_EASYBLOG_TIP"); ?>" >
			<img src="<?php echo JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"; ?>" border="0"/></span>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_('COM_IJOOMLA_SEO_INCLUDE_IN_ARTICLES'); ?></label>
        <div class="controls">
        	<fieldset class="radio btn-group" id="jform_include_in">
                <?php
                    $no_checked = "";
                    $yes_cheched = "";
                    $display = "none";
                    
                    if($include_in == 0){
                        $no_checked = 'checked="checked"';
                    }
                    else{
						$display = "block";
                        $yes_cheched = 'checked="checked"';
                    }
                ?>
                <input type="radio" value="0" <?php echo $no_checked; ?> name="include_in" id="jform_include_in0" onclick="javascript:insideArticles('no'); return false;">
                <label for="jform_include_in0" class="btn"><?php echo JText::_('COM_IJOOMLA_SEO_ALL_ARTICLES'); ?></label>
                
                <input type="radio" value="1" <?php echo $yes_cheched; ?> name="include_in" id="jform_include_in1" onclick="javascript:insideArticles('yes'); return false;">
                <label for="jform_include_in1" class="btn"><?php echo JText::_("COM_IJOOMLA_SEO_SELECTED_ARTICLES"); ?></label>
            </fieldset>
            
            <div id="inside_articles" style="display:<?php echo $display; ?>;">
                <div id="contains_selects">
                <?php
                    if(!$has_assigned_articles){
                        $document->addScriptDeclaration('window.addEvent("domready", Newilinks.clickAddMore);');
                    }
                    else{
                        $js_output = '';
                        foreach ($values["0"]->articles as $element){
                            $js_output .= "Newilinks.clickAddMore({
                            'id': '" . $element->id . "',
                            'title': '" . $element->title . "'
                            });";
                        }
                        $document->addScriptDeclaration('window.addEvent("domready", function(){'.$js_output.'});');
                    }
                ?>
                </div>
                <a href="#" id="addmore_seo"><?php echo JText::_('COM_IJOOMLA_SEO_ADD_MORE'); ?></a>
                <input type="hidden" id="last_clicked" value="" />
                <span class="editlinktip hasTip" title="<?php echo JText::_("COM_IJOOMLA_SEO_INCLUDE_IN_ARTICLES_TIP"); ?>" >					
                <img src="<?php echo JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"; ?>" border="0"/></span>	
            </div>
            
        </div>
    </div>
	
	<input type="hidden" name="option" value="com_ijoomla_seo" />
	<input type="hidden" name="controller" value="newilinks" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>