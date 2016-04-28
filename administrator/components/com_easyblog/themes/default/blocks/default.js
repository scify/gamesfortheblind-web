
EasyBlog.require()
.script('admin/grid')
.done(function($) {

    $('[data-grid-eb]').implement(EasyBlog.Controller.Grid);

    $('[data-filter-group]').on('change', function(){
        $("[data-grid-eb]").submit();
    });

    $.Joomla("submitbutton", function(task) {

        if (task == 'category.create') {
            window.location     = '<?php echo JURI::root();?>administrator/index.php?option=com_easyblog&view=categories&layout=form';
            return false;
        }

        if (task != 'remove' || confirm('<?php echo JText::_('COM_EASYBLOG_ARE_YOU_SURE_CONFIRM_DELETE', true); ?>')) {
            $.Joomla("submitform", [task]);
        }
    });
});
