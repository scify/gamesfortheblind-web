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

$isWritable = new checkPermissions();
echo $isWritable->check();

$params = JComponentHelper::getParams('com_joomailermailchimpintegration');
$MCapi = $params->get('params.MCapi');
$JoomlamailerMC = new JoomlamailerMC();

if (!$MCapi) {
    echo '<table>' . $JoomlamailerMC->apiKeyMissing();
    return;
} else if (!$JoomlamailerMC->pingMC()) {
    echo '<table>' . $JoomlamailerMC->apiKeyMissing(1);
    return;
}

$fName = $params->get('params.from_name', $this->app->getCfg('sitename'));
$fMail = $params->get('params.from_email', $this->app->getCfg('mailfrom'));
$rMail = $params->get('params.reply_email', $this->app->getCfg('mailfrom'));
$cMail = $params->get('params.confirmation_email', $this->app->getCfg('mailfrom'));

$tt_image = JURI::root() .'media/com_joomailermailchimpintegration/backend/images/info.png';

$campaign_name      = JRequest::getVar('cn', '', '', 'string');
$subject            = JRequest::getVar('sj', '', '', 'string');
$from_name          = JRequest::getVar('fn', '', '', 'string');
$from_email         = JRequest::getVar('fe', '', '', 'string');
$reply_email        = JRequest::getVar('re', '', '', 'string');
$confirmation_email = JRequest::getVar('ce', '', '', 'string');
$text_only		    = JRequest::getVar('text_only', 0, '', 'int');
$text_only_content  = JRequest::getVar('text_only_content', '', '', 'string');

$pop                = JRequest::getVar('pop', false, '', 'string');
$popAmount          = JRequest::getVar('popA', false, '', 'string');
$pex                = JRequest::getVar('pex', false, '', 'string');
if ($pex){ $pex     = explode(';', $pex); }
$pin                = JRequest::getVar('pin', false, 'get');
if ($pin){ $pin     = explode(';', $pin); }
$pk2                = JRequest::getVar('pk2', false, '', 'string');
$pk2ex              = JRequest::getVar('pk2ex', false, 'get');
if ($pk2ex){ $pk2ex = explode(';', $pk2ex); }
$pk2in              = JRequest::getVar('pk2in', false, 'get');
if ($pk2in){ $pk2in = explode(';', $pk2in); }
$pk2o               = JRequest::getVar('pk2o', false, '', 'string');

$template           = JRequest::getVar('tpl', false, '', 'string');
$editorcontent      = urldecode(JRequest::getVar('intro', false, '', 'string', JREQUEST_ALLOWRAW));
$gaSource           = JRequest::getVar('gaS', false, '', 'string');
$gaMedium           = JRequest::getVar('gaM', false, '', 'string');
$gaName             = JRequest::getVar('gaN', false, '', 'string');
$gaExcluded         = urldecode(html_entity_decode(urldecode(JRequest::getVar('gaE',   false, '', 'string'))));

if (!$campaign_name) {      $campaign_name      = JRequest::getVar('campaign_name',     '', 'POST', 'string'); }
if (!$subject){             $subject            = JRequest::getVar('subject',           '', 'POST', 'string'); }
if (!$from_name){           $from_name          = JRequest::getVar('from_name',         $fName, 'POST', 'string'); }
if (!$from_email){          $from_email         = JRequest::getVar('from_email',        $fMail, 'POST', 'string'); }
if (!$reply_email){         $reply_email        = JRequest::getVar('reply_email',       $rMail, 'POST', 'string'); }
if (!$confirmation_email){  $confirmation_email = JRequest::getVar('confirmation_email',$cMail, 'POST', 'string'); }
if (!@$listid){             $listid             = JRequest::getVar('listid',       array(), 'POST', 'array'); }
if (!@$toc){                $toc                = JRequest::getVar('tableofcontents',   '', 'POST', 'string'); }
if (!@$toct){               $toct               = JRequest::getVar('tableofcontents_type', '', 'POST', 'string'); }
if (!$pop){                 $pop                = JRequest::getVar('populararticles',false, 'POST', 'string'); }
if (!$popAmount){           $popAmount          = JRequest::getVar('populararticlesAmount', 5, 'POST', 'string'); }
if (!$pex){                 $pex                = JRequest::getVar('pex',            false, 'POST');
    if ($pex){  $pex = explode(';', $pex); }
}
if (!$pin){                 $pin                = JRequest::getVar('pin',            false, 'POST');
	if ($pin){  $pin = explode(';', $pin); }
}
if (!$pk2){                 $pk2                = JRequest::getVar('populark2',        false, 'POST', 'string'); }
if (!$pk2ex){               $pk2ex              = JRequest::getVar('pk2ex',            false, 'POST');
	if ($pk2ex){ $pk2ex = explode(';', $pk2ex); }
}
if (!$pk2in){               $pk2in              = JRequest::getVar('pk2in',            false, 'POST');
	if ($pk2in){ $pk2in = explode(';', $pk2in); }
}
if (!$pk2ex){               $pk2ex              = explode(';', JRequest::getVar('pk2ex',  '', 'POST', 'string'));}
if (!$pk2in){               $pk2in              = explode(';', JRequest::getVar('pk2in',  '', 'POST', 'string'));}
if (!$pk2o){                $pk2o               = JRequest::getVar('populark2_only',    '', 'POST', 'string'); }

if (!$template){            $template           = JRequest::getVar('template',          '', 'POST', 'string'); }
if (!$editorcontent){       $editorcontent      = JRequest::getVar('intro',             '', 'POST', 'string', JREQUEST_ALLOWRAW); }
if (!$gaSource){            $gaSource           = JRequest::getVar('gaSource','newsletter', 'POST', 'string'); }
if (!$gaMedium){            $gaMedium           = JRequest::getVar('gaMedium',     'email', 'POST', 'string'); }
if (!$gaName){              $gaName             = JRequest::getVar('gaName',            '', 'POST', 'string'); }
if (!$gaExcluded){          $gaExcluded         = JRequest::getVar('gaExcluded', "twitter.com\nfacebook.com\nmyspace.com", 'POST', 'string'); } ?>

<div id="create">
    <form action="index.php?option=com_joomailermailchimpintegration&view=create" method="post" name="adminForm" id="adminForm">
        <div id="buttons">
            <div id="previewButtonContainer">
	            <span id="ajax-spin" class="hidden"></span>
	            <div id="previewButton" class="JMbuttonOrange">
	                <span></span>
	                <?php echo JText::_('JM_PREVIEW');?>
	            </div>
            </div>
            <div id="saveButton" class="JMbuttonBlue">
	            <span></span>
	            <?php echo JText::_('JM_SAVE_DRAFT'); ?>
            </div>
            <div style="clear:both;"></div>
        </div>
        <?php
        echo JHtml::_('bootstrap.startTabSet', 'create_campaign', array('active' => JRequest::getVar('activeTab', 'create_main')));
        echo JHtml::_('bootstrap.addTab', 'create_campaign', 'create_main', JText::_('JM_MAIN_SETTINGS', true)); ?>
        <table class="admintable" width="100%">
	        <tr>
	            <td width="130" align="right" class="key">
		        <label for="campaign_name">
		            <?php echo JText::_('JM_CAMPAIGN_NAME'); ?>:
		        </label>
	            </td>
	            <td width="5">
		        <input class="text_area" type="text" name="campaign_name" id="campaign_name" <?php echo (JRequest::getVar('action','')=='edit')?'readonly="readonly" onfocus="$(\'subject\').focus()"':'';?> size="48" maxlength="250" value="<?php echo $campaign_name; ?>" style="margin-right: 10px;<?php echo (JRequest::getVar('action','')=='edit')?'color:#AFAFAF;':'';?>" />
	            </td>
	            <td>
		        <div class="inputInfo"><?php echo JText::_('JM_CAMPAIGN_NAME_INFO'); ?></div>
	            </td>
	        </tr>
	        <tr>
	            <td align="right" class="key">
		        <label for="subject">
		            <?php echo JText::_('JM_SUBJECT'); ?>:
		        </label>
	            </td>
	            <td>
		        <input class="text_area" type="text" name="subject" id="subject" size="48" maxlength="250" value="<?php echo $subject; ?>" style="margin-right: 10px;" />
	            </td>
	            <td>
		        <div class="inputInfo"><?php echo JText::_('JM_SUBJECT_INFO'); ?></div>
	            </td>
	        </tr>
	        <tr>
	            <td align="right" class="key">
		        <label for="from_name">
		            <?php echo JText::_('JM_FROM_NAME'); ?>:
		        </label>
	            </td>
	            <td>
		        <input class="text_area" type="text" name="from_name" id="from_name" size="48" maxlength="250" value="<?php echo $from_name; ?>" style="margin-right: 10px;" />
	            </td>
	            <td>
		        <div class="inputInfo"><?php echo JText::_('JM_FROM_NAME_INFO'); ?></div>
	            </td>
	        </tr>
	        <tr>
	            <td align="right" class="key">
		        <label for="from_email">
		            <?php echo JText::_('JM_FROM_EMAIL'); ?>:
		        </label>
	            </td>
	            <td>
		        <input class="text_area" type="text" name="from_email" id="from_email" size="48" maxlength="250" value="<?php echo $from_email; ?>" style="margin-right: 10px;" />
	            </td>
	            <td>
		        <div class="inputInfo"><?php echo JText::_('JM_FROM_EMAIL_INFO'); ?></div>
	            </td>
	        </tr>
	        <tr>
	            <td align="right" class="key">
		        <label for="reply_email">
		            <?php echo JText::_('JM_REPLY_EMAIL'); ?>:
		        </label>
	            </td>
	            <td>
		        <input class="text_area" type="text" name="reply_email" id="reply_email" size="48" maxlength="250" value="<?php echo $reply_email; ?>" style="margin-right: 10px;" />
	            </td>
	            <td>
		        <div class="inputInfo"><?php echo JText::_('JM_REPLY_EMAIL_INFO'); ?></div>
	            </td>
	        </tr>
	        <tr>
	            <td align="right" class="key">
		        <label for="confirmation_email">
		            <?php echo JText::_('JM_CONFIRMATION_EMAIL'); ?>:
		        </label>
	            </td>
	            <td>
		        <input class="text_area" type="text" name="confirmation_email" id="confirmation_email" size="48" maxlength="250" value="<?php echo $confirmation_email; ?>" style="margin-right: 10px;" />
	            </td>
	            <td>
		        <div class="inputInfo"><?php echo JText::_('JM_CONFIRMATION_EMAIL_INFO'); ?></div>
	            </td>
	        </tr>
	        <tr>
	            <td align="right" class="key">
		        <label for="text_only">
		            <?php echo JText::_('JM_TEXT_ONLY_CAMPAIGN'); ?>:
		        </label>
	            </td>
	            <td style="padding-left: 5px;">
		        <input class="checkbox" type="checkbox" name="text_only" id="text_only" value="1" <?php echo ($text_only)?'checked="checked"':'';?> />
	            </td>
	            <td>
		        <div class="inputInfo"><?php echo JText::_('JM_TEXT_ONLY_CAMPAIGN_INFO'); ?></div>
	            </td>
	        </tr>
        </table><?php
        echo JHtml::_('bootstrap.endTab');
        echo JHtml::_('bootstrap.addTab', 'create_campaign', 'select_content', JText::_('JM_CONTENT', true)); ?>
        <div id="html_container"<?php echo ($text_only) ? ' style="display:none;"' : '';?>>
            <table class="admintable" width="100%">
	            <tr>
	                <td>
		            <h3 style="margin:0;"><?php echo JText::_('JM_CHOOSE_TEMPLATE'); ?></h3>
		            <?php
		            $template_folders = Jfolder::listFolderTree('../administrator/components/com_joomailermailchimpintegration/templates/' , '', 1);
		            ?>
		            <select name="template" id="template" style="width: 210px;font-size:14px;margin:5px 0 0 0;">
		                <?php
		                foreach ($template_folders as $tf){
			            if ($tf['name'] == $template) { $sel = ' selected="selected"'; } else { $sel = ''; } ?>
			            <option value="<?php echo $tf['name'];?>"<?php echo $sel;?>><?php echo $tf['name'];?></option><?php
		                }
		                ?>
		            </select>
	                </td>
	            </tr>
	            <tr>
	                <td><?php
                        echo '<h3>'.JText::_('JM_INTRO_TEXT').'</h3>';
                        $buttons2exclude = array('pagebreak', 'readmore'); ?>
                        <div style="width: 100%;float: left;">
                            <div style="margin-right: 270px;"><?php
                                echo $this->editor->display('intro', $editorcontent, '550', '200', '60', '20', $buttons2exclude); ?>
                            </div>
                        </div>
                        <div style="width: 250px;margin-left: -250px;float:left;"><?php
                            echo JHTML::tooltip(JText::_('JM_TOOLTIP_INTRO'), JText::_('JM_INTRO'), $tt_image, ''); ?>
                            <br />
                            <br />
                            <?php echo JText::_('JM_MERGE_TAGS_AVAILABLE');?> <a href="http://www.mailchimp.com/resources/merge/" title="<?php echo JText::_('JM_MERGE_TAG_CHEATSHEET'); ?>" class="modal" rel="{handler: 'iframe', size: {x: 980, y: 550} }" style="margin:5px;position:relative;top:-2px;">
                                <img src="<?php echo $tt_image;?>" />
                            </a>
                            <br />
                            <br />
                            <select class="insertMergeTag" data-editor="intro">
                                <option value=""><?php echo JText::_('JM_INSERT_MERGE_TAG');?></option>
                                <option value="*|FNAME|*"><?php echo JText::_('JM_FIRST_NAME');?></option>
                                <option value="*|LNAME|*"><?php echo JText::_('JM_LAST_NAME');?></option>
                                <option value="*|DATE:d/m/y|*"><?php echo JText::_('JM_DATE');?></option>
                                <option value="*|MC:SUBJECT|*"><?php echo JText::_('JM_SUBJECT');?></option>
                                <option value="*|EMAIL|*"><?php echo JText::_('JM_RECIPIENTS_EMAIL');?></option>
                            </select>
                            <br />
                            <br /><?php
                            if ($this->merge) {
                                echo JText::_('JM_LIST_SPECIFIC_MERGE_TAGS');?> <?php echo JHTML::tooltip(JText::_('JM_LIST_SPECIFIC_MERGE_TAGS_INFO'), JText::_('JM_LIST_SPECIFIC_MERGE_TAGS'), $tt_image.'" style="margin:0 5px;position:relative;top:-2px;"', '');?>
                                <br />
                                <br />
                                <select class="insertMergeTag" data-editor="intro">
                                    <option value=""><?php echo JText::_('JM_INSERT_MERGE_TAG');?></option>
                                    <?php
                                     foreach ($this->merge as $k => $v) {
                                        echo '<optgroup label="' . JText::_('JM_LIST') . ': ' . $k . '">';
                                        foreach ($v as $tag) {
                                            echo '<option value="*|' . $tag['tag'] . '|*">' . $tag['name'] . '</option>';
                                        }
                                        echo '</optgroup>';
                                    } ?>
                                </select><?php
                            } ?>
                        </div>
                        <div style="clear: both;"></div>
	                </td>
	            </tr>
	            <tr>
	                <td><?php
                        $activeSlider = JRequest::getVar('activeArticleListSlider', 'article_lists_sliders_1');
		                $articleLists = $this->plugins->trigger('getArticleList');
                        echo JHtml::_('bootstrap.startAccordion', 'article_lists_sliders', array('active' => $activeSlider));
		                foreach ($articleLists as $index => $al) {
		                    if ($al) {
                                echo JHtml::_('bootstrap.addSlide', 'article_lists_sliders', JText::_($al['title']), 'article_lists_sliders_' . ($index + 1));
				                echo $al['table'];
                                echo JHtml::_('bootstrap.endSlide');
                            }
		                }
                        echo JHtml::_('bootstrap.endAccordion'); ?>
	                </td>
	            </tr>
            </table>
            <input type="hidden" name="activeArticleListSlider" id="activeArticleListSlider" value="<?php echo $activeSlider;?>" />
        </div>
        <div id="text_only_container"<?php echo ($text_only) ? '' : 'style="display:none;"';?>>
            <table class="admintable" width="100%">
	            <tr>
	                <td>
		            <h3 style="margin:0;"><?php echo JText::_('JM_CAMPAIGN_CONTENT'); ?></h3>
		            <textarea name="text_only_content" id="text_only_content" cols="100" rows="20" style="width: 600px"><?php echo $text_only_content;?></textarea>
	                </td>
	            </tr>
            </table>
        </div>
        <?php
        echo JHtml::_('bootstrap.endTab');
        echo JHtml::_('bootstrap.addTab', 'create_campaign', 'create_sidebar', JText::_('JM_SIDEBAR', true)); ?>
        <div id="sidebar_info"><?php echo JText::_('JM_SIDEBAR_INFO');?></div>
        <table class="admintable" width="100%"><?php
	        $sidebarElements = $this->plugins->trigger('getSidebarElement');
	        foreach ($sidebarElements as $se) {
	            if (! isset($se[0])) { $tmp = $se; $se = array(); $se[0] = $tmp; }
	            foreach ($se as $s) { ?>
	                <tr>
		                <td width="130" align="right" class="key" valign="top">
		                    <?php echo $s['title'];?>:
		                </td>
		                <td>
		                    <?php echo $s['element'];?>
		                </td>
	                </tr><?php
                }
	        } ?>
	        <tr>
	            <td align="right" class="key" valign="top">
		            <?php echo JText::_('JM_POPULAR_ARTICLES'); ?>:
	            </td>
	            <td>
                    <label for="populararticles">
		                <input class="checkbox" type="checkbox" name="populararticles" id="populararticles" data-container="popSlide" value="1" <?php echo ($pop)?'checked="checked"':'';?> />
                        <?php $input = '<input type="text" name="populararticlesAmount" id="populararticlesAmount" size="1" value="' . $popAmount . '" style="float:none;font-size:12px;height:15px;text-align:center;width:15px;padding: 0 5px;" />';
		                echo JText::sprintf('JM_POPULAR_ARTICLES_INFO', $input); ?>
                    </label>
		            <div style="clear:both;"></div>
		            <div id="popSlide" <?php if (!$pop) { echo 'style="display:none;"'; }?>>
		                <table>
			                <tr>
			                    <td valign="top">
				                    <?php echo JText::_('JM_INCLUDE');?>:
				                    <div style="padding: 4em 0 0 0;text-align:right;">
                                        <img class="deselect pointer" data-field="popInclude" src="<?php echo JURI::root();?>media/com_joomailermailchimpintegration/backend/images/deselect.png" title="<?php echo JText::_('JM_CLEAR_SELECTION');?>" />
                                    </div>
			                    </td>
			                    <td valign="top">
				                    <select multiple="multiple" name="popInclude[]" id="popInclude" size="5">
				                        <?php foreach($this->categories as $category){
					                        if ($pin){
					                            if (in_array($category->cid, $pin)){
						                        $selected = 'selected="selected"';
					                            } else {
						                        $selected = '';
					                            }
					                        } else {
					                            $selected = 'selected="selected"';
					                        }
					                        $indent = ($category->level - 1) * 8;
					                        $category->ctitle = str_pad('', $indent, "|&mdash;", STR_PAD_LEFT) . $category->ctitle;

					                        echo '<option value="' . $category->cid . '" ' . $selected . '>' . $category->ctitle . '</option>';
				                        } ?>
				                    </select>
			                    </td>
			                    <td valign="top">
				                    <?php echo JText::_('JM_EXCLUDE');?>:
				                    <div style="padding: 4em 0 0 0;text-align:right;">
                                        <img class="deselect pointer" data-field="popExclude" src="<?php echo JURI::root();?>media/com_joomailermailchimpintegration/backend/images/deselect.png" title="<?php echo JText::_('JM_CLEAR_SELECTION');?>" />
                                    </div>
			                    </td>
			                    <td valign="top">
				                    <select multiple="multiple" name="popExclude[]" id="popExclude" size="5">
				                        <?php foreach($this->categories as $category){
					                        if ($pex){
					                            if (in_array($category->cid, $pex)) {
						                            $selected = ' selected="selected"';
					                            } else {
						                            $selected = '';
					                            }
					                        } else {
					                            $selected = '';
					                        }

					                        echo "<option value=\"{$category->cid}\"{$selected}>{$category->ctitle}</option>";
				                        } ?>
				                    </select>
			                    </td>
			                </tr>
		                </table>
		            </div>
	            </td>
	        </tr>
	        <?php if ($this->K2Installed) { ?>
	            <tr>
	                <td align="right" class="key" valign="top">
		                <?php echo JText::_('JM_INCLUDE_K2_ARTICLES'); ?>:
	                </td>
	                <td>
                        <label for="populark2" class="labelNode">
                            <input class="checkbox" type="checkbox" name="populark2" id="populark2" data-container="popk2Slide" value="1" <?php echo ($pk2)?'checked="checked"':'';?> />
                            <?php echo JText::_('JM_INCLUDE_K2_ARTICLES_INFO'); ?>
                        </label>
		                <div id="popk2Slide" <?php if (!$pk2) { echo 'style="display:none;"'; }?>>
		                    <table>
		                        <tr>
			                        <td valign="top">
			                            <?php echo JText::_('JM_INCLUDE');?>:
			                            <div style="padding: 4em 0 0 0;text-align:right;">
                                            <img class="deselect pointer" data-field="popk2Include" src="<?php echo JURI::root();?>media/com_joomailermailchimpintegration/backend/images/deselect.png" title="<?php echo JText::_('JM_CLEAR_SELECTION');?>" />
                                        </div>
			                        </td>
			                        <td valign="top">
			                            <select multiple="multiple" name="popk2Include[]" id="popk2Include" size="5">
				                        <?php foreach($this->allk2cat as $sc){
					                        if ($pk2in){
					                            if (in_array($sc->id, $pk2in)){
						                        $selected = 'selected="selected"';
					                            } else {
						                        $selected = '';
					                            }
					                        } else {
					                            $selected = 'selected="selected"';
					                        }
					                        echo '<option value="'.$sc->id.'" '.$selected.'>'.$sc->name.'</option>';
				                        } ?>
			                            </select>
			                        </td>
			                        <td valign="top">
			                            <?php echo JText::_('JM_EXCLUDE');?>:
			                            <div style="padding: 4em 0 0 0;text-align:right;">
                                            <img class="deselect pointer" data-field="popk2Exclude" src="<?php echo JURI::root();?>media/com_joomailermailchimpintegration/backend/images/deselect.png" title="<?php echo JText::_('JM_CLEAR_SELECTION');?>" />
                                        </div>
			                        </td>
			                        <td valign="top">
			                            <select multiple="multiple" name="popk2Exclude[]" id="popk2Exclude" size="5">
				                        <?php foreach($this->allk2cat as $sc){
					                        if ($pk2ex){
					                            if (in_array($sc->id, $pk2ex)){
						                        $selected = 'selected="selected"';
					                            } else {
						                        $selected = '';
					                            }
					                        } else {
					                            $selected = '';
					                        }
					                        echo '<option value="'.$sc->id.'" '.$selected.'>'.$sc->name.'</option>';
				                        } ?>
			                            </select>
			                        </td>
		                        </tr>
		                    </table>
		                </div>
	                </td>
	            </tr>
	            <tr>
	                <td align="right" class="key" valign="top">
		                <?php echo JText::_('JM_ONLY_K2_ARTICLES'); ?>:
	                </td>
	                <td>
                        <label for="populark2_only" class="labelNode indent">
		                    <input class="checkbox" type="checkbox" name="populark2_only" id="populark2_only" value="1" <?php echo ($pk2o)?'checked="checked"':'';?> />
		                    <?php echo JText::_('JM_ONLY_K2_ARTICLES_INFO'); ?>
                        </label>
	                </td>
	            </tr><?php
            }
	        $socialIcons = $this->plugins->trigger('getSocialIcon');
            if ($socialIcons) { ?>
                <tr>
                    <td align="right" class="key"></td>
                    <td>
                        <hr style="border: 0;border-bottom: 1px dotted #666666;margin:10px 0;padding:0;"/>
                    </td>
                </tr><?php
            }
	        foreach ($socialIcons as $si) {
	            if (! isset($si[0])) { $tmp = $si; $si = array(); $si[0] = $tmp; }
	            foreach ($si as $s) { ?>
	                <tr>
		                <td align="right" class="key">
		                    <?php echo $s['title'];?>
		                </td>
		                <td>
		                    <?php echo $s['element'];?>
		                </td>
	                </tr><?php
                }
            } ?>
        </table><?php
        echo JHtml::_('bootstrap.endTab');
        echo JHtml::_('bootstrap.addTab', 'create_campaign', 'gaSettings', JText::_('JM_ANALYTICS', true));?>
        <table class="admintable" width="100%">
	        <tr>
	            <td width="155" style="width:155px;" align="right" class="key" valign="top">
		            <label for="gaEnabled"><?php echo JText::_('JM_ENABLE_GOOGLE_ANALYTICS'); ?>:</label>
	            </td>
	            <td width="5">
		            <input class="checkbox" type="checkbox" name="gaEnabled" id="gaEnabled" value="1" />
	            </td>
	            <td></td>
	        </tr>
            <tr>
	            <td align="right" class="key">
		            <label for="gaSource">
		                <?php echo JText::_('JM_SOURCE'); ?>:
		            </label>
	            </td>
	            <td>
		            <input class="text_area" type="text" name="gaSource" id="gaSource" value="<?php echo $gaSource;?>" size="48" style="margin-right: 10px;" />
	            </td>
	            <td>
		            <div class="inputInfo"><?php echo JText::_('JM_GASOURCE_INFO'); ?></div>
	            </td>
	        </tr>
	        <tr>
	            <td align="right" class="key">
		            <label for="gaMedium">
		                <?php echo JText::_('JM_MEDIUM'); ?>:
		            </label>
	            </td>
	            <td>
		            <input class="text_area" type="text" name="gaMedium" id="gaMedium" value="<?php echo $gaMedium;?>" size="48" style="margin-right: 10px;"/>
	            </td>
	            <td>
		            <div class="inputInfo"><?php echo JText::_('JM_GAMEDIUM_INFO'); ?></div>
	            </td>
	        </tr>
	        <tr>
	            <td align="right" class="key">
		            <label for="gaName">
		                <?php echo JText::_('JM_NAME'); ?>:
		            </label>
	            </td>
	            <td>
		            <input class="text_area" type="text" name="gaName" id="gaName" value="<?php echo $gaName;?>" size="48" style="margin-right: 10px;"/>
	            </td>
	            <td>
		            <div class="inputInfo"><?php echo JText::_('JM_GANAME_INFO'); ?></div>
	            </td>
	        </tr>
	        <tr>
	            <td align="right" class="key" valign="top">
		            <label for="gaExcluded">
		                <?php echo JText::_('JM_EXCLUDE_URLS'); ?>:
		            </label>
	            </td>
	            <td>
		            <textarea name="gaExcluded" id="gaExcluded" rows="10" style="width:302px;margin-right: 10px; padding: 5px;"><?php echo $gaExcluded;?></textarea>
	            </td>
	            <td style="vertical-align:top;">
		            <div class="inputInfo" style="display:block;"><?php echo JText::_('JM_GAEXCLUDED_INFO'); ?></div>
	            </td>
	        </tr>
        </table><?php
        echo JHtml::_('bootstrap.endTab');
        echo JHtml::_('bootstrap.addTab', 'create_campaign', 'Folders', JText::_('JM_FOLDERS', true)); ?>
        <table class="admintable" width="100%">
	        <tr>
	            <td width="155" style="width:155px;" align="right" class="key">
		        <?php echo JText::_('JM_CHOOSE_A_FOLDER'); ?>:
	            </td>
	            <td>
		        <?php echo $this->foldersDropDown; ?>
	            </td>
	        </tr>
	        <tr>
	            <td width="155" style="width:155px;" align="right" class="key">
		        <?php echo JText::_('JM_CREATE_A_NEW_FOLDER'); ?>:
	            </td>
	            <td>
		        <input class="text_area" type="text" name="folder_name" id="folder_name" value="" size="48" style="float:left;margin-right: 20px;" />
		        <?php echo JHTML::tooltip(JText::_('JM_FOLDER_INFO'), JText::_('JM_FOLDER_INFO_HEADING'), $tt_image.'" style="margin:0 5px;position:relative;top:3px;"', '');?>
	            </td>
	        </tr>
        </table><?php
        echo JHtml::_('bootstrap.endTabSet'); ?>
        <a name="preview"></a>
        <div class="clr"></div>
        <span id="preview"></span>

        <input type="hidden" name="k2_installed" id="k2_installed" value="<?php echo ($this->K2Installed) ? 1 : 0;?>" />
        <input type="hidden" name="list_names" id="list_names" value="" />
        <input type="hidden" name="cid" value="<?php echo JRequest::getVar('cid', 0);?>" />
        <input type="hidden" name="offset" id="offset" value="" />
        <input type="hidden" name="activeTab" id="activeTab" value="<?php echo JRequest::getVar('activeTab', 'create_main');?>" />
        <input type="hidden" name="option" value="com_joomailermailchimpintegration" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="action" value="<?php echo JRequest::getVar('action', '');?>" />
        <input type="hidden" name="boxchecked" value="1" />
        <input type="hidden" name="articlechecked" value="0" />
        <input type="hidden" name="k2checked" value="0" />
        <input type="hidden" name="controller" value="create" />
        <input type="hidden" name="type" value="create" />
    </form>
</div>

