
<?php if ($this->config->get('layout_composer_publishingdate')) { ?>
EasyBlog.require()
    .library('datetimepicker')
    .script('composer/datetime')
    .done(function($) {
        $('[data-publish]').addController('EasyBlog.Controller.Post.Datetime', {
            format: "<?php echo JText::_('COM_EASYBLOG_MOMENTJS_DATE_DMY24H'); ?>",
            emptyText: "<?php echo JText::_('COM_EASYBLOG_COMPOSER_IMMEDIATELY'); ?>"
        });
    });
<?php } ?>