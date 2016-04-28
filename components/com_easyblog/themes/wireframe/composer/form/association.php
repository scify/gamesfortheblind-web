<?php
/**
* @package      EasyBlog
* @copyright    Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
// var_dump($languages);exit;
?>
<?php if ($languages) { ?>
    <div class="eb-composer-fieldset<?php echo ($post->language == '*' || $post->language == '') ? ' hide': '';?>" data-name="association" data-composer-association>
        <div class="eb-composer-fieldset-header">
            <strong><?php echo JText::_('Associations');?></strong>
        </div>
        <div class="eb-composer-fieldset-content">

            <?php foreach ($languages as $lang) { ?>
            <?php

                $postId = '';
                $postTitle = '';
                $hide = false;

                if (isset($associations[$lang->lang_code]) && $lang->lang_code != $post->language) {
                    $postId = $associations[$lang->lang_code]->post_id;
                    $postTitle = $associations[$lang->lang_code]->title;
                }

                if ($lang->lang_code == $post->language) {
                    $hide = true;
                }

            ?>
                <div class="eb-composer-associate-lang<?php echo ($hide) ? ' hide':'';?>"
                     data-composer-association-item
                     data-id="<?php echo $lang->lang_id; ?>"
                     data-code="<?php echo $lang->lang_code; ?>"
                >
                    <i class="input-flag"><?php echo JHtml::_('image', 'mod_languages/' . $lang->image . '.gif', $lang->title_native, array('title' => $lang->title_native), true);?></i>
                    <div class="input-group">
                        <input class="form-control" type="text" name="assoc_post[]" value="<?php echo $postTitle; ?>" id="assoc-postname<?php echo $lang->lang_id; ?>" readonly="true"/>
                        <input type="hidden" name="assoc_postids[]" value="<?php echo $postId;?>" id="assoc-postid<?php echo $lang->lang_id; ?>"/>
                        <input type="hidden" name="assoc_code[]" value="<?php echo $lang->lang_code; ?>" id="assoc-code<?php echo $lang->lang_id; ?>"/>

                        <span class="input-group-btn">
                            <a href="javascript:void(0);" class="btn btn-default" data-assoc-clear data-id="<?php echo $lang->lang_id;?>">
                                <i class="fa fa-times"></i>
                            </a>
                        </span>

                        <span class="input-group-btn">
                            <a  href="index.php?option=com_easyblog&view=composer&layout=getPosts&code=<?php echo $lang->lang_code;?>&codeid=<?php echo $lang->lang_id;?>&tmpl=component&browse=1"
                                rel="{handler: 'iframe', size: {x: 750, y: 475}}"
                                class="modal btn btn-default"
                                data-assoc-select
                                data-id="<?php echo $lang->lang_id;?>"
                            >
                                <span><?php echo JText::_('COM_EASYBLOG_ASSOCIATION_SELECT_POST'); ?></span>
                            </a>
                        </span>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>




